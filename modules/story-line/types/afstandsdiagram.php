<?php
/*
Title: Relationsdiagram
Description: Tilføj Relationsdiagram
Category:
Author: Johnnie Bertelsen
Version: 0.1
*/

Class isl_afstandsdiagram extends Inzite_Story_Line {

	public function __construct() {
		$this->fields = array(
			'type' => array(
				'type' => 'select',
				'title' => 'Kategori',
				'placeholder' => '',
				'options' => array(
					'option_1' => '1. Dem jeg let bliver sur eller gal på',
					'option_2' => '2. Dem der har betydning for mig i øjeblikket',
					'option_3' => '3. Dem det støtter mig og hjælper mig i forandringerne',
					'option_4' => 'Dem jeg kan være fornuftig sammen med',
				)
			),
			'question_1_1' => array(
				'type' => 'textarea',
				'rows' => 8,
				'title' => 'Dem jeg sjældent bliver sur eller gal på.',
				'placeholder' => 'Skriv et navn pr. linie...',
			),
			'question_1_2' => array(
				'type' => 'textarea',
				'rows' => 8,
				'title' => 'Dem jeg engang imellem bliver sur eller gal på.',
				'placeholder' => 'Skriv et navn pr. linie...'
			),
			'question_1_3' => array(
				'type' => 'textarea',
				'rows' => 8,
				'title' => 'Den jeg hurtigt bliver sur eller gal på.',
				'placeholder' => 'Skriv et navn pr. linie...'
			),
            'question_1_4' => array(
				'type' => 'textarea',
				'rows' => 8,
				'title' => 'Dem jeg kan blive sur eller gal på bare ved tanken.',
				'placeholder' => 'Skriv et navn pr. linie...'
			),
            'question_2_1' => array(
				'type' => 'textarea',
				'rows' => 8,
                'title' => 'Dem der har stor betydning for mig.',
				'placeholder' => 'Skriv et navn pr. linie...',
			),
			'question_2_2' => array(
				'type' => 'textarea',
				'rows' => 8,
                'title' => 'Dem der har en middel betydning for mig.',
				'placeholder' => 'Skriv et navn pr. linie...'
			),
			'question_2_3' => array(
				'type' => 'textarea',
				'rows' => 8,
                'title' => 'Dem der har en lille betydning for mig.',
				'placeholder' => 'Skriv et navn pr. linie...'
			),
            'question_2_4' => array(
				'type' => 'textarea',
				'rows' => 8,
                'title' => 'Dem der ikke har den betydning de havde engang.',
				'placeholder' => 'Skriv et navn pr. linie...'
			),
            'question_3_1' => array(
				'type' => 'textarea',
				'rows' => 8,
                'title' => 'Dem der støtter mig 100%.',
				'placeholder' => 'Skriv et navn pr. linie...',
			),
			'question_3_2' => array(
				'type' => 'textarea',
				'rows' => 8,
                'title' => 'Dem der næsten altid støtter mig.',
				'placeholder' => 'Skriv et navn pr. linie...'
			),
			'question_3_3' => array(
				'type' => 'textarea',
				'rows' => 8,
                'title' => 'Dem der støtter mig engang imellem.',
				'placeholder' => 'Skriv et navn pr. linie...'
			),
            'question_3_4' => array(
				'type' => 'textarea',
				'rows' => 8,
                'title' => 'Dem der ikke yder støtte - men de syntes jeg skal få et bedre liv.',
				'placeholder' => 'Skriv et navn pr. linie...'
			),
            'question_4_1' => array(
				'type' => 'textarea',
				'rows' => 8,
                'title' => 'Dem jeg altid kan bevare fornuften sammen med.',
				'placeholder' => 'Skriv et navn pr. linie...',
			),
			'question_4_2' => array(
				'type' => 'textarea',
				'rows' => 8,
                'title' => 'Dem jeg for det meste bevarer fornuften sammen med.',
				'placeholder' => 'Skriv et navn pr. linie...'
			),
			'question_4_3' => array(
				'type' => 'textarea',
				'rows' => 8,
                'title' => 'Dem jeg har svært ved at bevare fornuften sammen med.',
				'placeholder' => 'Skriv et navn pr. linie...'
			),
            'question_4_4' => array(
				'type' => 'textarea',
				'rows' => 8,
                'title' => 'Dem jeg sjældent kan bevare fornuften sammen med.',
				'placeholder' => 'Skriv et navn pr. linie...'
			),
		);
	}
}
?>
