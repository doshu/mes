<?php
	$variableDialogSelectData = array(array('', ''));
	foreach($member_custom_fields as $field) {
		$variableDialogSelectData[] = array($field['Memberfield']['name'], $field['Memberfield']['code']);
	}
	$variableDialogSelectData[] = array('Email', 'email');
	$this->Javascript->setGlobal(compact('variableDialogSelectData'));
?>
