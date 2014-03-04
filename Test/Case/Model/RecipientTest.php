<?php
App::uses('Recipient', 'Model');

/**
 * Recipient Test Case
 *
 */
class RecipientTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.recipient',
		'app.sending',
		'app.member',
		'app.mailinglist',
		'app.user',
		'app.mailinglists_member'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Recipient = ClassRegistry::init('Recipient');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Recipient);

		parent::tearDown();
	}

}
