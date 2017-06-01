<?php
/*
Title: Fototur
Description: Tilføj foto sektion
Category:
Author: Johnnie Bertelsen
Version: 0.1
*/

Class isl_fototur extends Inzite_Story_Line {

	public function __construct() {
		$this->fields = array(
			'fra' => array(
				'type' => 'text',
				'title' => 'Billedet er fra',
				'placeholder' => '',
			),
			'hukommelse' => array(
				'type' => 'textarea',
				'title' => 'Oplevelsen ved at gense stedet, og følelsen jeg mærkede kan beskrives sådan her:',
				'placeholder' => ''
			),
			'mit_liv' => array(
				'type' => 'text',
				'title' => 'Mit liv dengang husker jeg som:',
				'placeholder' => ''
			),
			'sammen_med' => array(
				'type' => 'textarea',
				'title' => 'Dem jeg var meget sammen med dengang:',
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
