<?php
/**
 * BattleFixture
 *
 */
class BattleFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'num' => array('type' => 'integer', 'null' => false, 'default' => null),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'missions' => array('type' => 'integer', 'null' => false, 'default' => null),
		'rating' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 5, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'flown' => array('type' => 'integer', 'null' => false, 'default' => null),
		'gotzip' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'gotinfo' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'type' => 'Lorem ip',
			'num' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'missions' => 1,
			'rating' => 'Lor',
			'flown' => 1,
			'gotzip' => 1,
			'gotinfo' => 1
		),
	);

}
