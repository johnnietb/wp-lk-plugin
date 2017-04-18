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
			'alder' => array(
				'type' => 'text',
				'title' => 'Billedet er taget da jeg var ca. X år gammel',
				'placeholder' => 'Skriv din alder'
			),
			'fordi' => array(
				'type' => 'text',
				'title' => 'og er taget da:',
				'placeholder' => 'Skriv hvorfor billedet blev taget'
			),
			'husker' => array(
				'type' => 'text',
				'title' => 'Jeg husker følgende fra den tid',
				'placeholder' => 'Skriv hvad du husker'
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