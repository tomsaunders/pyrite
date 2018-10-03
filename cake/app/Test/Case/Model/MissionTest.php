<?php
App::uses('Mission', 'Model');

/**
 * Mission Test Case
 *
 */
class MissionTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.mission',
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
		$this->Mission = ClassRegistry::init('Mission');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Mission);

		parent::tearDown();
	}

}
