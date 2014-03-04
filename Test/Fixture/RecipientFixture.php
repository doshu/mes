<?php
/**
 * RecipientFixture
 *
 */
class RecipientFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'sending_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'opened' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'device' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'os' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lat' => array('type' => 'float', 'null' => true, 'default' => null),
		'lon' => array('type' => 'float', 'null' => true, 'default' => null),
		'member_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'fk_recipients_sendings1' => array('column' => 'sending_id', 'unique' => 0),
			'fk_recipients_members1' => array('column' => 'member_id', 'unique' => 0)
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
			'id' => 1,
			'sending_id' => 1,
			'opened' => 1,
			'device' => 'Lorem ipsum dolor sit amet',
			'os' => 'Lorem ipsum dolor sit amet',
			'lat' => 1,
			'lon' => 1,
			'member_id' => 1
		),
	);

}
