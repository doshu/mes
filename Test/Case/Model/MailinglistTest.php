<?php
App::uses('Mailinglist', 'Model');

/**
 * Mailinglist Test Case
 *
 */
class MailinglistTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.mailinglist',
		'app.user',
		'app.member',
		'app.mailinglists_member'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Mailinglist = ClassRegistry::init('Mailinglist');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Mailinglist);

		parent::tearDown();
	}

}
