<?php
/**
 * MissionFixture
 *
 */
class MissionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'battle_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'position' => array('type' => 'integer', 'null' => false, 'default' => null),
		'filename' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'highscore' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'hs_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'hs_pin' => array('type' => 'integer', 'null' => true, 'default' => null),
		'complexity' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'potentialscore' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'playercraft' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'warheads' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'primary_goals' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'secondary_goals' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'bonus_goals' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'reinforcements' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'battle_id' => 1,
			'position' => 1,
			'filename' => 'Lorem ipsum dolor sit amet',
			'highscore' => 1,
			'hs_name' => 'Lorem ipsum dolor sit amet',
			'hs_pin' => 1,
			'complexity' => 1,
			'potentialscore' => 1,
			'playercraft' => 'Lorem ipsum dolor sit amet',
			'warheads' => 'Lorem ipsum dolor sit amet',
			'primary_goals' => 1,
			'secondary_goals' => 1,
			'bonus_goals' => 1,
			'reinforcements' => 1
		),
	);

}
