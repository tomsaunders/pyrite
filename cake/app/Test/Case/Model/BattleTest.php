<?php
App::uses('Battle', 'Model');

/**
 * Battle Test Case
 *
 */
class BattleTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.battle',
		'app.bug'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Battle = ClassRegistry::init('Battle');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Battle);

		parent::tearDown();
	}

}
