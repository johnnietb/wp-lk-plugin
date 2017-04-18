<?php
/*
Title: Dagbogsindlæg
Description: Tilføj et nyt dagbogsindlæg
Category: Dagbogsindlæg
Author: Johnnie Bertelsen
Version: 0.1
*/

Class isl_dagbog extends Inzite_Story_Line {
	
	public function __construct() {
		$this->fields = array(
			'title' => array(
				'type' => 'text',
				'title' => 'Titel',
				'placeholder' => 'Skriv en overskrift for dette indlæg'
			),
			'content' => array(
				'type' => 'editor',
				'title' => 'Indhold',
				'placeholder' => 'Skriv din tekst her'
			)
		);
	}
}
?>