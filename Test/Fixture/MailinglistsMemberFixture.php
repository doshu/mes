<?php
/**
 * MailinglistsMemberFixture
 *
 */
class MailinglistsMemberFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'member_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'mailinglist_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'fk_members_has_mailinglists_mailinglists1' => array('column' => 'mailinglist_id', 'unique' => 0),
			'fk_members_has_mailinglists_members1' => array('column' => 'member_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'member_id' => 1,
			'mailinglist_id' => 1,
			'id' => 1
		),
	);

}
