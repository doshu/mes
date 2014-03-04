<?php
App::uses('MailinglistsMember', 'Model');

/**
 * MailinglistsMember Test Case
 *
 */
class MailinglistsMemberTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.mailinglists_member',
		'app.member',
		'app.mailinglist',
		'app.user'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MailinglistsMember = ClassRegistry::init('MailinglistsMember');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MailinglistsMember);

		parent::tearDown();
	}

}
