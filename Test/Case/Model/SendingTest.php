<?php
App::uses('Sending', 'Model');

/**
 * Sending Test Case
 *
 */
class SendingTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.sending',
		'app.mail',
		'app.user',
		'app.attachment',
		'app.recipient',
		'app.member',
		'app.mailinglist',
		'app.mailinglists_member'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Sending = ClassRegistry::init('Sending');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Sending);

		parent::tearDown();
	}

}
