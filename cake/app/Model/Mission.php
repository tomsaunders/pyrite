<?php
App::uses('AppModel', 'Model');
/**
 * Mission Model
 *
 * @property Battle $Battle
 */
class Mission extends AppModel {

	public $belongsTo = array(
		'Battle' => array(
			'className' => 'Battle',
			'foreignKey' => 'battle_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

    public $virtualFields = array(
        'ScoreDiff' => 'Mission.potentialscore - Mission.hs_total'
    );

    public function scoreKeeper($r, $diff = 'Hard'){
        $b = $r['Battle'];
        $m = $r['Mission'];
        list($plat, $sg) = explode('-', $b['type']);
        if ($sg === 'free') {
            $sg = 'F';
        }

        $m['filename'] = trim(strtolower($m['filename']));
        $path = BATTLES_PATH . $plat . $sg . $b['num'] . DS . $m['filename'];
        $out = "";
        if ($plat == "TIE"){
            $TIE = new Pyrite\TIE\Mission($path);
            $sk = new \Pyrite\TIE\ScoreKeeper($TIE, $diff);
            $out = $sk->printDump();
        } else if ($plat == "XvT" || $plat == 'BoP'){
            $TIE = new Pyrite\XvT\Mission($path);
            $sk = new \Pyrite\XvT\ScoreKeeper($TIE, $diff);
            $out = $sk->printDump();
        } else if ($plat == "XWA"){
            $TIE = new Pyrite\XWA\Mission($path);
            $sk = new \Pyrite\XWA\ScoreKeeper($TIE, $diff);
            $out = $sk->printDump();
        } else if ($plat == "XW"){
            $TIE = new Pyrite\XWING\Mission($path);
            $sk = new \Pyrite\XWING\ScoreKeeper($TIE, $diff);
            $out = $sk->printDump();
        }
        $m['potentialscore'] = $sk->total;
        $m['playercraft'] = $sk->player;
        $this->save($m);
        return $out;
    }

    public function dump($r){
        $b = $r['Battle'];
        $m = $r['Mission'];
        list($plat, $sg) = explode('-', $b['type']);
        if ($sg === 'free') {
            $sg = 'F';
        }

        $m['filename'] = trim(strtolower($m['filename']));
        $path = BATTLES_PATH . $plat . $sg . $b['num'] . DS . $m['filename'];
        if ($plat == 'TIE') {
            $TIE = new Pyrite\TIE\Mission($path);
        } else if ($plat == 'XWA'){
            $TIE = new Pyrite\XWA\Mission($path);
        }
//        $sk = new \Pyrite\TIE\ScoreKeeper($TIE);
        return $TIE->printDump();
    }

    public function golf($r){
        $b = $r['Battle'];
        $m = $r['Mission'];
        list($plat, $sg) = explode('-', $b['type']);
        if ($sg === 'free') {
            $sg = 'F';
        }

        $m['filename'] = trim(strtolower($m['filename']));
        $path = BATTLES_PATH . $plat . $sg . $b['num'] . DS . $m['filename'];
        $TIE = new Pyrite\TIE\Mission($path);
        $sk = new \Pyrite\TIE\Golf($TIE);
        return $sk->printDump();
    }

    public function complex($r){
        $b = $r['Battle'];
        $m = $r['Mission'];
        list($plat, $sg) = explode('-', $b['type']);
        if ($sg === 'free') {
            $sg = 'F';
        }

        $m['filename'] = trim(strtolower($m['filename']));
        $path = BATTLES_PATH . $plat . $sg . $b['num'] . DS . $m['filename'];
        $TIE = new Pyrite\TIE\Mission($path);
        $sk = new \Pyrite\TIE\Complexity($TIE);
        return $sk->printDump();
    }
}
