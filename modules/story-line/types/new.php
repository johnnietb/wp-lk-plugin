<?php
/*
Title: New
Description: Tilføj sektion til livslinjen
Category: New
Author: Johnnie Bertelsen
Version: 0.1
*/

Class isl_new extends Inzite_Story_Line {
	
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
			),
			'email' => array(
				'type' => 'email',
				'title' => 'Email',
				'placeholder' => 'Skriv en email for dette indlæg'
			),
			'file' => array(
				'type' => 'file',
				'title' => 'Indhold',
				'placeholder' => 'Skriv din tekst her'
			)
		);
	}
}
?>