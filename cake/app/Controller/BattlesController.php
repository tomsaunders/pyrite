<?php
App::uses('AppController', 'Controller');
/**
 * Battles Controller
 *
 * @property Battle $Battle
 * @property PaginatorComponent $Paginator
 */
class BattlesController extends AppController
{

    public $components = array('Paginator');

    public function index()
    {
		$this->set('print', $this->Battle->updateAllHS());
        $this->Battle->recursive = 0;
        $this->Paginator->settings['conditions'] = array(
            'flyable' => true
        );
        $this->set('battles', $this->Paginator->paginate());
    }

    public function spam($off = 0)
    {
//        $this->set('print', $this->Battle->calculate());
        $battles = $this->Battle->spamList();
        $battles = array_splice($battles, 0 + $off, 100 + $off);
        $this->set('export', true);
        $this->set('battles', $battles);
    }

    public function potential()
    {
        $battles = $this->Battle->potentialList();
        $this->set('battles', $battles);
    }

    
    public function loko()
    {
        $this->set('print', $this->Battle->loko());
    }

    public function lokoxvt(){
		ini_set('memory_limit', '512M');

		$loko = [];
		$battles = $this->Battle->find('all', [
			'conditions' => ['Battle.type' => 'XvT-free']
		]);

		foreach ($battles as $battle) {
			$mission = $battle['Mission'][0];
			$b = "XvTF" . $battle['Battle']['num'];
			$path = BATTLES_PATH . $b . DS . $mission['filename'];

			$TIE = new Pyrite\XvT\Mission($path);
			$score = new Pyrite\XvT\ScoreKeeper($TIE);
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

		$this->set('print', $loko);
	}

    public function golf()
    {
        $this->set('export', true);
        $battles = $this->Battle->golfList();
        $this->set('battles', $battles);
    }

    public function update($id)
    {
        $this->Battle->updateHS($id);
        $this->Session->setFlash($this->Battle->calculate($id));
        $this->redirect($this->referer());
    }

    public function view($id = null, $num = null)
    {
        if (is_numeric($id)) {
            if (!$this->Battle->exists($id)) {
                if (!$this->Battle->checkBattlePage($id)) {
                    throw new NotFoundException(__('Invalid battle'));
                } else {
                    $this->Battle->loadBattlePage($id);
                }
            }
            $options = array('conditions' => array('Battle.' . $this->Battle->primaryKey => $id));
        } elseif ($id && $num) {
            $options = array('conditions' => array('Battle.type' => $id, 'Battle.num' => $num));
        } else {
            throw new NotFoundException(__('Invalid battle'));
        }
        $this->set('battle', $this->Battle->find('first', $options));
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->Battle->create();
            if ($this->Battle->save($this->request->data)) {
                $this->Session->setFlash(__('The battle has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The battle could not be saved. Please, try again.'));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$this->Battle->exists($id)) {
            throw new NotFoundException(__('Invalid battle'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Battle->save($this->request->data)) {
                $this->Session->setFlash(__('The battle has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The battle could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Battle.' . $this->Battle->primaryKey => $id));
            $this->request->data = $this->Battle->find('first', $options);
        }
    }

    public function delete($id = null)
    {
        $this->Battle->id = $id;
        if (!$this->Battle->exists()) {
            throw new NotFoundException(__('Invalid battle'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Battle->delete()) {
            $this->Session->setFlash(__('The battle has been deleted.'));
        } else {
            $this->Session->setFlash(__('The battle could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function freedom()
    {
        $battles = $this->Battle->find('all', ['conditions' => ['Battle.type LIKE' => 'TIE-%']]);
        $names = [];
        foreach ($battles as $battle) {
            $path = BATTLES_PATH . str_replace("-", "", $battle['Battle']['type']) . $battle['Battle']['num'] . DS;
            foreach ($battle['Mission'] as $mission) {
                if (!file_exists($path)) {
                    continue;
                }
                $tie = new \Pyrite\TIE\Mission($path . $mission['filename']);
                foreach ($tie->flightGroups as $fg) {
                    $name = $fg->general['Name'];
                    if (!isset($names[$name])) {
                        $names[$name] = [];
                    }

                    $names[$name][] = $fg->getLabel();
                }
            }
        }

        uasort($names, function ($a, $b) {
            $a = count($a);
            $b = count($b);
            return $a === $b ? 0 : $a > $b ? -1 : 1;
        });

        $this->set('names', $names);
    }
}
