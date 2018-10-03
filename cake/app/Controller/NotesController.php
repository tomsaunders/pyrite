<?php
App::uses('AppController', 'Controller');
/**
 * Notes Controller
 *
 * @property Note $Note
 * @property PaginatorComponent $Paginator
 */
class NotesController extends AppController {

	public $components = array('Paginator');

	public function index() {
		$this->Note->recursive = 0;
		$this->set('notes', $this->Paginator->paginate());
	}

	public function view($id = null) {
		if (!$this->Note->exists($id)) {
			throw new NotFoundException(__('Invalid note'));
		}
		$options = array('conditions' => array('Note.' . $this->Note->primaryKey => $id));
		$this->set('note', $this->Note->find('first', $options));
	}

	public function add($battleID) {
		if ($this->request->is('post')) {
			$this->Note->create();
            $battleID = $this->request->data['Note']['battle_id'];
			if ($this->Note->save($this->request->data)) {
				$this->Session->setFlash(__('The note has been saved.'));
				return $this->redirect(array('controller' => 'battles', 'action' => 'view', $battleID));
			} else {
				$this->Session->setFlash(__('The note could not be saved. Please, try again.'));
			}
		}
		$battles = $this->Note->Battle->find('list');
		$this->set(compact('battles', 'battleID'));
	}

	public function edit($id = null) {
		if (!$this->Note->exists($id)) {
			throw new NotFoundException(__('Invalid note'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Note->save($this->request->data)) {
				$this->Session->setFlash(__('The note has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The note could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Note.' . $this->Note->primaryKey => $id));
			$this->request->data = $this->Note->find('first', $options);
		}
		$battles = $this->Note->Battle->find('list');
		$this->set(compact('battles'));
	}

	public function delete($id = null) {
		$this->Note->id = $id;
		if (!$this->Note->exists()) {
			throw new NotFoundException(__('Invalid note'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Note->delete()) {
			$this->Session->setFlash(__('The note has been deleted.'));
		} else {
			$this->Session->setFlash(__('The note could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
