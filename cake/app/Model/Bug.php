<?php
App::uses('AppModel', 'Model');
/**
 * Bug Model
 *
 * @property Battle $Battle
 */
class Bug extends AppModel {

	public $belongsTo = array(
		'Battle' => array(
			'className' => 'Battle',
			'foreignKey' => 'battle_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function import(){
		$data = file_get_contents(WWW_ROOT . DS . 'files' . DS . 'bugs.txt');
		$lines = explode("\n", $data);
		$battles = $this->Battle->find('all');
		$bIdx = array();
		foreach ($battles as $battle){
			$b = $battle['Battle'];
			$bIdx[$b['type'] . ' ' . $b['num']] = $b['id'];
		}
		$bugs = array();
		foreach($lines as &$line){
			list($battle, $reporter, $date) = explode("    ", $line);
			$bKey = substr($battle, 0, strpos($battle, ':'));
//			$bIdx[$bKey] = $bKey;
			$bugs[] = array(
				'battle_id' => $bIdx[$bKey],
				'reporter' => $reporter,
				'date' => date("Y-m-d", strtotime($date))
			);
		}
		$this->saveAll($bugs);
		return $bugs;
	}
}
