<?php
/*
Title: Foto
Description: Tilføj foto sektion
Category:
Author: Johnnie Bertelsen
Version: 0.1
*/

Class isl_foto extends Inzite_Story_Line {

	public function __construct() {
		$this->fields = array(
			'age' => array(
				'type' => 'number',
				'title' => 'Billedet blev taget da jeg var ca. ',
				'text_after' => ' år gammel.',
				'placeholder' => '',
				'min' => '0',
				'max' => '120',
				'inline' => true
			),
			'fordi' => array(
				'type' => 'textarea',
				'title' => 'Billedet er taget da:',
				'placeholder' => ''
			),
			'husker' => array(
				'type' => 'textarea',
				'title' => 'Jeg husker følgende fra den tid:',
				'placeholder' => ''
			),
			'foto' => array(
				'type' => 'file',
				'title' => 'Foto',
				'placeholder' => 'Upload billede'
			)
		);
	}
}
?>
