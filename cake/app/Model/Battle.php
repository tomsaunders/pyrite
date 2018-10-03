<?php
App::uses('AppModel', 'Model');
/**
 * Battle Model
 *
 * @property Bug $Bug
 */

class Battle extends AppModel
{

    public $hasMany = array(
        'Bug' => array(
            'className' => 'Bug',
            'foreignKey' => 'battle_id',
            'dependent' => false,
            'order' => '',
        ),
        'Mission' => array(
            'className' => 'Mission',
            'foreignKey' => 'battle_id',
            'dependent' => false,
            'order' => array('Mission.position')
        ),
        'Note' => array(
            'className' => 'Note',
            'foreignKey' => 'battle_id',
            'dependent' => false,
            'order' => '',
        ),
    );

    public function download()
    {
        $this->recursive = -1;
        $battles = $this->find('all', array('conditions' => array('Battle.gotzip' => false), 'limit' => 20));
        $update = array();

        $root = 'http://tc.emperorshammer.org/downloads/battles/';
        $print = array();
        $print[] = date("H:i:s");
        foreach ($battles as $battle) {
            $b = $battle['Battle'];
            $type = $b['type'];
            list($plat, $sub) = explode("-", $type);
            $file = $plat . ($sub === 'free' ? 'F' : $sub) . $b['num'] . '.zip';
            $url = $root . $plat . '/' . $sub . '/' . $file;
            $res = file_put_contents(WWW_ROOT . DS . 'files' . DS . 'battles' . DS . $file, file_get_contents($url));
            set_time_limit(30);
            if ($res) {
                $print[] = $url;
                $update[] = array('id' => $b['id'], 'gotzip' => true);
            }
        }
        $this->saveAll($update);
        $print[] = date("H:i:s");
        return $print;
    }

    public function checkBattlePage($id)
    {
        $url = "http://tc.emperorshammer.org/download.php?id=$id&type=info";
        $page = file_get_contents($url);
        $doc = phpQuery::newDocument($page);
        foreach (pq("a[href^='/download']") as $a) {
            $a = pq($a);
            $title = trim($a->text());
            $path = trim($a->attr('href'));
            if ($title === 'ZIP file') {
                return true;
            }
        }
        return false;
    }

    public function loadBattlePage($id)
    {
        $url = "http://tc.emperorshammer.org/download.php?id=$id&type=info";
        $page = file_get_contents($url);
        $doc = phpQuery::newDocument($page);
        foreach (pq("a[href^='/download']") as $a) {
            $a = pq($a);
            $title = trim($a->text());
            $path = trim($a->attr('href'));
            if ($title === 'ZIP file') {
                $bits = explode('/', $path);
                $file = end($bits);
                $zipURL = 'http://tc.emperorshammer.org' . $path;
                set_time_limit(30);
                $zipPath = BATTLES_PATH . DS . $file;
                $res = file_put_contents($zipPath, file_get_contents($zipURL));
                set_time_limit(30);
                $table = pq('table[width=420]');
                $battle = $table->find('tr:nth-child(1)')->text();
                list($type, $num) = explode(' battle ', $battle);
                $rating = $table->find('tr:nth-child(11) td:nth-child(2)')->text();
                if ($rating == 'unrated') {
                    $rating = '0.0';
                }

                $b = array(
                    'id'        => (int)$id,
                    'type'      => $type,
                    'num'       => (int)$num,
                    'name'      => $table->find('tr:nth-child(3) td:nth-child(2)')->text(),
                    'missions'  => (int)$table->find('tr:nth-child(4) td:nth-child(2)')->text(),
                    'rating'    => $rating,
                    'flown'     => (int)str_replace(' - view stats', '', $table->find('tr:nth-child(14) td:nth-child(2)')->text()),
                    'gotzip'    => true
                );
                $this->save($b);

                $folder = str_replace('.zip', '', $file);
                $path = BATTLES_PATH . $folder;
                if (!file_exists($path)) {
                    mkdir($path);
                }
                $zip = new ZipArchive;
                $res = $zip->open($zipPath);
                if ($res === true) {
                    $zip->extractTo($path);
                    $zip->close();
                }
                $this->importMissions();
                $this->importMissionFiles();
            }
        }
    }


    public function import()
    {
        $root = 'http://tc.emperorshammer.org/';

        $typeList = $root . 'battlecenter.php?id=types';
        $typeData = file_get_contents($typeList);
        $doc = phpQuery::newDocument($typeData);
        $types = array();
        foreach (pq('a[href^=battlecenter') as $a) {
            $a = pq($a);
            $type = trim($a->text());
            $url = trim($a->attr('href'));
            $b = $this->importType($type, $root . $url);
            $types[$type] = count($b);
        }
        return $types;
    }

    public function importType($type, $url)
    {
        $data = file_get_contents($url);
        $doc = phpQuery::newDocument($data);

        $battles = array();
        foreach (pq('td:nth-child(3) a[href^=download.php]') as $a) {
            $a = pq($a);
            $tr = $a->parent()->parent();
            $b = array('type' => $type);
            $b['id']        = (int)str_replace(array('download.php?id=','&type=info'), '', $a->attr('href'));
            $b['num']       = (int)$tr->find('td:nth-child(2)')->text();
            $b['name']      =      $tr->find('td:nth-child(3)')->text();
            $b['missions']  = (int)$tr->find('td:nth-child(4)')->text();
            $b['rating']    =      $tr->find('td:nth-child(5)')->text();
            $b['flown']     = (int)$tr->find('td:nth-child(6)')->text();
            $battles[] = $b;
        }
        $this->saveAll($battles);
        return $battles;
    }

    public function importMissions()
    {
        $battles = $this->find('all', array('recursive' => -1,
            'fields' => array('id', 'type', 'num', 'name', 'missions'),
            'conditions' => array(
                'Battle.hs_total' => 0,
                'Battle.flyable'
            )
        ));

        $start = microtime(true);
        $missions = array();
        $bs = array();
        foreach ($battles as $battle) {
            set_time_limit(30);
            $missionRowOffset = 2;
            $b = $battle['Battle'];
//            $b['name'] = substr($b['name'],4); WTF sense would that make?
            list($plat, $sg) = explode('-', $b['type']);
            if ($sg === 'free') {
                $sg = 'F';
                $missionRowOffset = 1;
            }

            $path = BATTLES_PATH . $plat . $sg . $b['num'] . DS;
            $list = $this->listMissions($path, $plat, $b['missions']);
            $hsPage = 'http://tc.emperorshammer.org/download.php?id=' . $b['id'] . '&type=hs';
            $doc = phpQuery::newDocument(file_get_contents($hsPage));

            //battle high
            list($b['hs_total'], $b['hs_name'], $b['hs_pin']) = $this->handleScoreRow(pq('table[width=730] tr:nth-child(2)'));

            //missions
            foreach ($list as $i => $mission) {
                $pos = $i + 1;
                $m = array('battle_id' => $b['id'], 'position' => $pos, 'filename' => $mission);
                $rowIndex = $missionRowOffset + $pos;
                list($m['hs_total'], $m['hs_name'], $m['hs_pin']) = $this->handleScoreRow(pq("table[width=730] tr:nth-child($rowIndex)"));
                $missions[] = $m;
            }
            $bs[] = $b;
        }
        $this->saveAll($bs);
        $this->Mission->saveAll($missions);
        $end = microtime(true);
        return count($battles) . ' battles and ' . count($missions) . ' missions processed in ' . ($end-$start) . ' seconds';
    }

    public function updateAllHS()
    {
        $battles = $this->find('list', array(
            'conditions' => array(
                'Battle.type LIKE' => 'XvT-%',
                'Battle.gotinfo' => 0
            ),
//            'limit' => 10
        ));
        $print = array();
        foreach ($battles as $battleID => $name) {
            $updated = $this->updateHS($battleID);
            $print[$battleID] = ($updated ? 'Updated ' : '' ) . $this->calculate($battleID);
        }
        return $print;
    }

    public function updateHS($battleID)
    {
        set_time_limit(30);
        $updated = false;
        $battle = $this->read(array('id', 'name', 'type', 'num', 'missions', 'hs_total'), $battleID);
        $missionRowOffset = 2;
        $b = $battle['Battle'];
        $b['gotinfo'] = 0;
        list($plat, $sg) = explode('-', $b['type']);
        if ($sg === 'free') {
            $missionRowOffset = 1;
        }

        $hsPage = 'http://tc.emperorshammer.org/download.php?id=' . $b['id'] . '&type=hs';
        $doc = phpQuery::newDocument(file_get_contents($hsPage));

        //battle high
        list($b['hs_total'], $b['hs_name'], $b['hs_pin']) = $this->handleScoreRow(pq('table[width=730] tr:nth-child(2)'));

        //missions
        $missions = array();
        foreach ($battle['Mission'] as $i => $mission) {
            $m = array('id' => $mission['id']);
            $rowIndex = $missionRowOffset + $mission['position'];
            list($m['hs_total'], $m['hs_name'], $m['hs_pin']) = $this->handleScoreRow(pq("table[width=730] tr:nth-child($rowIndex)"));
            if ($mission['hs_total'] != $m['hs_total']) {
                $missions[] = $m;
            }
        }

        if (count($missions)) {
            $this->Mission->saveAll($missions);
            $updated = true;
            $b['gotinfo'] = 2;
        }
        if ($battle['Battle']['hs_total'] != $b['hs_total']) {
            $updated = true;
        }
        $this->save($b);
        return $updated;
    }

    public function importMissionFiles()
    {
        $results = $this->Mission->find('all', array(
            'fields' => array(
                'Mission.id', 'Mission.filename', 'Mission.position',
                'Battle.type', 'Battle.num', 'Battle.missions'
            ),
            'conditions' => array(
                'Mission.filename' => '',
                'Battle.flyable'
            )
        ));

        $start = microtime(true);
        $missions = array();
        foreach ($results as $r) {
            $b = $r['Battle'];
            $m = $r['Mission'];
            list($plat, $sg) = explode('-', $b['type']);
            if ($sg === 'free') {
                $sg = 'F';
            }

            $path = BATTLES_PATH . $plat . $sg . $b['num'] . DS;
            $list = $this->listMissions($path, $plat, $b['missions']);

            if (count($list) === (int)$b['missions']) {
                $m['filename'] = strtolower($list[(int)$m['position']-1]);
                if (!file_exists($path . $m['filename'])) {
                    if (strpos($m['filename'], '.tie') === false) {
                        $m['filename'] .= '.tie';
                    }
                }
                $missions[] = $m;
            }
        }
        $this->Mission->saveAll($missions);
        $end = microtime(true);
        return count($missions) . ' missions processed in ' . ($end-$start) . ' seconds';
    }

//    public function calculatePotential(){
//        $results = $this->Mission->find('all', array(
//            'fields' => array(
//                'Mission.id', 'Mission.filename', 'Mission.position',
//                'Battle.type', 'Battle.num', 'Battle.missions'
//            ),
//            'conditions' => array(
//                'Battle.type LIKE' => 'TIE%',
//                'Battle.flyable'
//            )
//        ));
//
//        $start = microtime(TRUE);
//        $missions = array();
//        foreach ($results as $r){
//            set_time_limit(30);
//            $b = $r['Battle'];
//            $m = $r['Mission'];
//            list($plat, $sg) = explode('-', $b['type']);
//            if ($sg === 'free') {
//                $sg = 'F';
//            }
//
//            $m['filename'] = trim(strtolower($m['filename']));
//            if (strpos($m['filename'], '.tie') === FALSE) $m['filename'] .= '.tie';
//            $path = BATTLES_PATH . $plat . $sg . $b['num'] . DS . $m['filename'];
//            if (file_exists($path)){
//                $TIE = new Pyrite\TIE\Mission($path);
//                $score = new \Pyrite\TIE\ScoreKeeper($TIE);
//                $dump = $score->printDump();
//                $m['potentialscore'] = $score->total;
//                $missions[] = $m;
//            }
//        }
//        $this->Mission->saveAll($missions);
//        $end = microtime(TRUE);
//        return count($missions) . ' missions processed in ' . ($end-$start) . ' seconds';
//    }

    public function calculate($battleID = null)
    {
        $conditions = array(
            'Battle.type NOT LIKE' => 'XWA-%',
            'Battle.flyable'
        );
        if ($battleID) {
            $conditions = array('Battle.id' => $battleID);
        }
        $results = $this->Mission->find('all', array(
            'fields' => array(
                'Mission.id', 'Mission.filename', 'Mission.complexity',
                'Battle.type', 'Battle.num', 'Battle.missions'
            ),
            'conditions' => $conditions
        ));

        $start = microtime(true);
        $missions = array();
        foreach ($results as $r) {
            set_time_limit(30);
            $b = $r['Battle'];
            $m = $r['Mission'];
            list($plat, $sg) = explode('-', $b['type']);
            if ($sg === 'free') {
                $sg = 'F';
            }

            $path = BATTLES_PATH . $plat . $sg . $b['num'] . DS . $m['filename'];
            if (!file_exists($path)) {
                $path .= '.tie';
            }
            if (file_exists($path)) {
                if ($plat == 'TIE') {
                    $TIE     = new Pyrite\TIE\Mission($path);
                    $complex = new \Pyrite\TIE\Complexity($TIE);
                    $score   = new \Pyrite\TIE\ScoreKeeper($TIE);
                    $medium = new \Pyrite\TIE\ScoreKeeper($TIE, 'Medium');
                } elseif ($plat == 'XvT') {
                    $TIE     = new Pyrite\XvT\Mission($path);
                    $complex = new \Pyrite\XvT\Complexity($TIE);
                    $score   = new \Pyrite\XvT\ScoreKeeper($TIE);
                    $medium  = new \Pyrite\XvT\ScoreKeeper($TIE, 'Medium');
                } elseif ($plat == 'XW') {
                    $TIE     = new Pyrite\XWING\Mission($path);
                    $complex = new \Pyrite\XWING\Complexity($TIE);
                    $score   = new \Pyrite\XWING\ScoreKeeper($TIE);
                }
                
                $complex->printDump();
                $m['complexity'] = $complex->total;
                $score->printDump();
                $m['potentialscore'] = $score->total;
                $m['potential_m'] = $medium->total;
                $m['playercraft'] = $score->player;
                $m['warheads'] = $score->warhead;
                $missions[] = $m;
            }
        }
        $this->Mission->saveAll($missions);
        $end = microtime(true);
        return count($missions) . ' missions processed in ' . ($end-$start) . ' seconds';
    }

    private function handleScoreRow($row)
    {
        $score   = (int)str_replace(',', '', $row->find('td:nth-child(3)')->text());
        $who     = $row->find('td:nth-child(4) a');
        $whoName = $who->text();
        $whoPIN  = str_replace(array('record.php?pin=','&type=profile'), '', $who->attr('href'));
        return array($score, $whoName, $whoPIN);
    }

    private function listMissions($path, $plat, $missionCount)
    {
        $missions = array();
        foreach (scandir($path) as $file) {
            if (strlen($file) > 2 && $file !== strtolower($file)) {
                rename($path . $file, $path . strtolower($file));
            }
        }
        if ($plat === 'XvT' || $plat === 'BoP' || $plat === 'XWA') {
            $lstpath = $plat === 'XWA' ? 'mission' : 'imperial';
            $lstpath .= '.lst';
            $lst = file_get_contents($path . $lstpath);
            $bits = explode("\n", $lst);
            $lines = array();
            foreach ($bits as $bit) {
                if (strlen($bit) && in_array($bit[0], array('[', '/'))) {
                    continue;
                }
                $lines[] = $bit;
            }

            if ($plat === 'XWA') {
                $lines = array_splice($lines, array_search(53, $lines));
            }
            for ($i = 0; $i < $missionCount; $i++) {
                $idx = array_shift($lines);
                $missions[] = trim(str_replace('* ', '', array_shift($lines)));
                $name = array_shift($lines);
            }
        } elseif ($plat === 'TIE') {
            //read Battle1.lfd etc
            $bat = new \Pyrite\TIE\Battle($path, $missionCount);
            $missions = array_map('strtolower', $bat->filenames);
        } elseif ($plat === 'XW') {
            //TODO case sensitive filenames?
            $xw = array('waistem.xwi', 'max4.xwi', 'satlit1.xwi', 'max5.xwi', 'halley.xwi', 'keyan.xwi', 'ywaistem.xwi', 'ywastem.xwi');
            $missions = array_splice($xw, 0, $missionCount);
        }
        return $missions;
    }

    public function loko()
    {
        ini_set('memory_limit', '512M');
        
        $loko = [];
        $battles = $this->find('all', [
            'conditions' => ['Battle.type' => 'TIE-free']
        ]);

        foreach ($battles as $battle) {
            $mission = $battle['Mission'][0];
            $b = "TIEF" . $battle['Battle']['num'];
            $path = BATTLES_PATH . $b . DS . $mission['filename'];

            $TIE = new Pyrite\TIE\Mission($path);
            $score = new Pyrite\TIE\ScoreKeeper($TIE);
            $loko[$b] = $score->bonus();
        }

        uasort($loko, function ($a, $b) {
            $a = count($a) ;
            $b = count($b);

            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        });

        return $loko;
    }

    public function golfList()
    {
        ini_set('memory_limit', '512M');
        
        $golf = [];
        $battles = $this->find('all', [
            'conditions' => ['Battle.type' => 'TIE-free']
        ]);

        foreach ($battles as $battle) {
            $mission = $battle['Mission'][0];
            $b = "TIEF" . $battle['Battle']['num'];
            $path = BATTLES_PATH . $b . DS . $mission['filename'];

            $TIE = new Pyrite\TIE\Mission($path);
            $fgs = ['Player' => '', 'ImpCap' => [], 'ImpFighter' => [], 'Hostile' => []];
            $points = ['ImpCap' => 0, 'ImpFighter' => 0, 'Hostile' => 0];

            foreach ($TIE->flightGroups as $idx => $fg) {
                if (!$fg->isInDifficultyLevel('Easy')) {
                    continue;
                }

                $name = (string)$fg;
                $pts = $fg->assetValue();
                if ($fg->isFriendly()) {
                    if ($fg->isPlayerCraft()) {
                        $fgs['Player'] = $name;
                    } elseif ($fg->isFighter()) {
                        $points['ImpFighter'] += $pts;
                        $fgs['ImpFighter'][] = "$name friendly fighter $pts";
                    } else {
                        $points['ImpCap'] += $pts;
                        $fgs['ImpCap'][] = "$name friendly cap $pts";
                    }
                } elseif ($fg->isHostile() && $pts) {
                    $points['Hostile'] += $pts;
                    $fgs['Hostile'][] = $name . ' hostile ' . $pts;
                }
            }
            $complex = new Pyrite\TIE\GoalKeeper($TIE);
            $golf[] = [
                'mission' => $b, 'points' => $points, 'fgs' => $fgs, 'reinf' => $TIE->hasReinforcements(), 'goals' =>
                $complex->goals()
            ];
        }

        usort($golf, function ($a, $b) {
            $a = $a['points']['ImpFighter'] + $a['points']['ImpCap'];
            $b = $b['points']['ImpFighter'] + $b['points']['ImpCap'];

            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        });

        return $golf;
    }

    public function potentialList()
    {
        $battles = $this->find('all', [
            'conditions' => [
                'Battle.type' => 'LIKE XvT%'
            ]
        ]);

        foreach ($battles as &$x) {
            $m = $x['Mission'][0];
            $m['filename'] = trim(strtolower($m['filename']));

            // $path = BATTLES_PATH . "XWAF" . $x['Battle']['num'] . DS . $m['filename'];
            $path = BATTLES_PATH . "XvTF" . $x['Battle']['num'] . DS . $m['filename'];
            $bonus = 0;
            if (file_exists($path)) {
                // $TIE = new Pyrite\XWA\Mission($path);
                // $sk = new \Pyrite\XWA\ScoreKeeper($TIE, "Hard");

                $TIE = new Pyrite\XvT\Mission($path);
                $sk = new \Pyrite\XvT\ScoreKeeper($TIE, "Medium");

                $bonus = $sk->bonus;
            }

            $x['Battle']['mdiff'] = $x['Battle']['hs_total'];
            $x['Battle']['bdiff'] = $x['Battle']['hs_total'] - $sk->total;
        }

        return $battles;
    }

    // public function potentialList(){
    //     $mdiff = array();
    //     $bdiff = array();

    //     $spam = $this->Mission->find('all', array(
    //         'conditions' => array(
    //             'Battle.missions > ' => 1,
    //             'Mission.potentialscore >' => 0
    //         ),
    //         'fields' => array('Battle.id', 'AVG(Mission.potentialscore - Mission.hs_total) AS mdiff', '(SUM(Mission.potentialscore) - Battle.hs_total) as bdiff'),
    //         'group' => array('Mission.battle_id'),
    //         'order' => array('mdiff desc'),
    //         'limit' => 50
    //     ));

    //     $spamIDs = array();
    //     foreach ($spam as $r){
    //         $id = $r['Battle']['id'];
    //         $score = $r[0]['mdiff'];
    //         $mdiff[$id] = $score;
    //         $bdiff[$id] = $r[0]['bdiff'];
    //         $spamIDs[] = $id;
    //     }

    //     $battles = $this->find('all', array(
    //         'conditions' => array('Battle.id' => $spamIDs)
    //     ));
    //     $bindex = array();
    //     foreach ($battles as $battle){
    //         $battle['Battle']['mdiff'] = $mdiff[$battle['Battle']['id']];
    //         $battle['Battle']['bdiff'] = $bdiff[$battle['Battle']['id']];
    //         $bindex[$battle['Battle']['id']] = $battle;
    //     }

    //     $result = array();
    //     foreach ($spam as $r){
    //         $result[] = $bindex[$r['Battle']['id']];
    //     }
    //     return $result;
    // }

    public function spamList()
    {
        $complex = array();

        $spam = $this->Mission->find('all', array(
            'conditions' => array(
//                'Battle.missions > ' => 1,
                'Mission.complexity >' => 0,
                'Battle.type' => 'TIE-free'
            ),
            'fields' => array('Battle.id', 'AVG(Mission.complexity) as complexity'),
            'group' => array('Mission.battle_id'),
            'order' => array('(Battle.minutes / Battle.missions) asc', 'complexity asc'),
            'limit' => 2000
        ));

        $spamIDs = array();
        foreach ($spam as $r) {
            $id = $r['Battle']['id'];
            $score = $r[0]['complexity'];
            $complex[$id] = $score;
            $spamIDs[] = $id;
        }

        $battles = $this->find('all', array(
            'conditions' => array('Battle.id' => $spamIDs)
        ));
        $bindex = array();
        foreach ($battles as &$battle) {
            $battle['Battle']['complexity'] = $complex[$battle['Battle']['id']];
            $battle['weighting'] = round($battle['Battle']['complexity'] / sqrt($battle['Battle']['missions']));
//            $battle['weighting'] = round($battle['Battle']['minutes'] / $battle['Battle']['missions']);
            $bindex[$battle['Battle']['id']] = $battle;
        }

        function cmp($a, $b)
        {
            $a = $a['weighting'];
            $b = $b['weighting'];
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        }

        usort($battles, 'cmp');
        return $battles;
    }
}
