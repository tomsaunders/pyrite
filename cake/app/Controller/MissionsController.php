<?php
App::uses('AppController', 'Controller');
/**
 * Missions Controller
 *
 * @property Mission $Mission
 * @property PaginatorComponent $Paginator
 */
class MissionsController extends AppController {

	public $paginate = array(
        'limit' => 50
    );

	public function index() {
		$this->Mission->recursive = 0;
		$this->set('missions', $this->Paginator->paginate());
	}

    public function potential(){
//        $this->set('print', $this->Mission->Battle->calculatePotential());
        $this->set('missions', $this->Mission->find('all', array(
            'conditions' => array(
                'Battle.flyable' => TRUE,
//                'Mission.potentialscore >' => 0,
                'Battle.type LIKE' => 'XvT-%',
//                'Mission.potentialscore / Mission.hs_total > 3',
                'Mission.hs_total <' => 20000,
                'Battle.missions <' => 30,
//                'Battle.missions >' => 1,
//                'Mission.complexity <' => 1000
            ),
            'order' => array('Battle.type desc', 'Battle.num', 'Mission.position'),
            'limit' => 100
        )));
    }

    public function xwa(){
        $easybeats = array('', 'HA Anahorn Dempsey', 'CPT Plif', 'CPT Nutrientman', 'COL Jarek La\'an');
        $this->set('missions', $this->Mission->find('all', array(
            'conditions' => array(
                'Battle.flyable' => TRUE,
                'Battle.type LIKE' => 'XWA%',
                'OR' => array(
                    'Battle.hs_name' => $easybeats,
                    'Mission.hs_name' => $easybeats
                )
            )
        )));
    }

    public function xw(){
        $this->set('missions', $this->Mission->find('all', array(
            'conditions' => array(
                'Battle.flyable' => TRUE,
                'Battle.type LIKE ' => 'XW-%'
            ),
            'order' => array('Mission.hs_total asc')
        )));
    }

    public function xvt(){
        $this->set('missions', $this->Mission->find('all', array(
            'conditions' => array(
                'Battle.flyable' => TRUE,
                'OR' => array(
                    'Battle.type LIKE "XvT-%"',
                    'Battle.type LIKE "BoP-%"'
                )
            ),
            'order' => array('Mission.hs_total asc', 'Battle.num')
        )));
    }

    public function complexity(){
//        $this->set('print', $this->Mission->Battle->calculateComplexity());
        $this->set('missions', $this->Mission->find('all', array(
            'conditions' => array(
                'Battle.flyable' => TRUE,
                'Mission.complexity >' => 0,
                'Battle.type' => 'TIE-free'
            ),
            'order' => array('Mission.complexity'),
            'limit' => 300
        )));
    }

	public function view($id = null) {
		if (!$this->Mission->exists($id)) {
			throw new NotFoundException(__('Invalid mission'));
		}
		$options = array('conditions' => array('Mission.' . $this->Mission->primaryKey => $id));
		$this->set('mission', $this->Mission->find('first', $options));
	}

    public function pilot($pin){
        $this->set('missions', $this->Mission->find('all', array(
            'conditions' => array(
                'Mission.hs_pin' => $pin,
                'Mission.potentialscore >' => 0
            ),
            'order' => array('Mission__ScoreDiff')
        )));
    }

    public function golf($id = null) {
        if (!$this->Mission->exists($id)) {
            throw new NotFoundException(__('Invalid mission'));
        }
        $options = array('conditions' => array('Mission.' . $this->Mission->primaryKey => $id));
        $mission = $this->Mission->find('first', $options);
        $score = $this->Mission->golf($mission);
        $this->set('mission', $mission);
        $this->set('score', $score);

        $neighbours = $this->Mission->find('neighbors', array(
            'field' => 'id', 'value' => $id,
            'order' => array('Mission.position')
        ));
        $next = isset($neighbours['next']) ? $neighbours['next']['Mission']['id'] : false;
        $this->set('next', $next);
    }

    public function score($id = null, $difficulty = 'Hard') {
        if (!$this->Mission->exists($id)) {
            throw new NotFoundException(__('Invalid mission'));
        }
        $options = array('conditions' => array('Mission.' . $this->Mission->primaryKey => $id));
        $mission = $this->Mission->find('first', $options);
        $score = $this->Mission->scoreKeeper($mission, $difficulty);
        $mission = $this->Mission->find('first', $options);
        $this->set('mission', $mission);
        $this->set('score', $score);

        $neighbours = $this->Mission->find('neighbors', array(
            'field' => 'id', 'value' => $id,
            'order' => array('Mission.position')
        ));
        $next = isset($neighbours['next']) ? $neighbours['next']['Mission']['id'] : false;
        $this->set('next', $next);
    }

    public function medium($id = null) {
        if (!$this->Mission->exists($id)) {
            throw new NotFoundException(__('Invalid mission'));
        }
        $options = array('conditions' => array('Mission.' . $this->Mission->primaryKey => $id));
        $mission = $this->Mission->find('first', $options);
        $score = $this->Mission->scoreKeeper($mission, 'Medium');
        $this->set('mission', $mission);
        $this->set('score', $score);

        $neighbours = $this->Mission->find('neighbors', array(
            'field' => 'id', 'value' => $id,
            'order' => array('Mission.position')
        ));
        $next = isset($neighbours['next']) ? $neighbours['next']['Mission']['id'] : false;
        $this->set('next', $next);
    }

    public function dump($id = null) {
        if (!$this->Mission->exists($id)) {
            throw new NotFoundException(__('Invalid mission'));
        }
        $options = array('conditions' => array('Mission.' . $this->Mission->primaryKey => $id));
        $mission = $this->Mission->find('first', $options);
        $dump = $this->Mission->dump($mission);
        $this->set('mission', $mission);
        $this->set('dump', $dump);

        $neighbours = $this->Mission->find('neighbors', array(
            'field' => 'id', 'value' => $id,
            'order' => array('Mission.position')
        ));
        $next = isset($neighbours['next']) ? $neighbours['next']['Mission']['id'] : false;
        $this->set('next', $next);
    }

    public function complex($id = null) {
        if (!$this->Mission->exists($id)) {
            throw new NotFoundException(__('Invalid mission'));
        }
        $options = array('conditions' => array('Mission.' . $this->Mission->primaryKey => $id));
        $mission = $this->Mission->find('first', $options);
        $score = $this->Mission->complex($mission);
        $this->set('mission', $mission);
        $this->set('complex', $score);

        $neighbours = $this->Mission->find('neighbors', array(
            'field' => 'id', 'value' => $id,
            'order' => array('Mission.position')
        ));
        $next = isset($neighbours['next']) ? $neighbours['next']['Mission']['id'] : false;
        $this->set('next', $next);
    }

	public function add() {
		if ($this->request->is('post')) {
			$this->Mission->create();
			if ($this->Mission->save($this->request->data)) {
				$this->Session->setFlash(__('The mission has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The mission could not be saved. Please, try again.'));
			}
		}
		$battles = $this->Mission->Battle->find('list');
		$this->set(compact('battles'));
	}

	public function edit($id = null) {
		if (!$this->Mission->exists($id)) {
			throw new NotFoundException(__('Invalid mission'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Mission->save($this->request->data)) {
				$this->Session->setFlash(__('The mission has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The mission could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Mission.' . $this->Mission->primaryKey => $id));
			$this->request->data = $this->Mission->find('first', $options);
		}
		$battles = $this->Mission->Battle->find('list');
		$this->set(compact('battles'));
	}

	public function delete($id = null) {
		$this->Mission->id = $id;
		if (!$this->Mission->exists()) {
			throw new NotFoundException(__('Invalid mission'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Mission->delete()) {
			$this->Session->setFlash(__('The mission has been deleted.'));
		} else {
			$this->Session->setFlash(__('The mission could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
