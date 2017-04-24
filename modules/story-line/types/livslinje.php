<?php
/*
Title: Livslinie
Description: Tilføj sektion til livslinjen
Category: Livlinje
Author: Johnnie Bertelsen
Version: 0.1
*/

Class isl_livslinje extends Inzite_Story_Line {

	public function __construct() {
		$this->fields = array(
			'type' => array(
				'type' => 'select',
				'title' => 'Kategori',
				'placeholder' => '',
				'options' => array(
					'option_1' => '1. Steder og adresser jeg har boet på.',
					'option_2' => '2. Pasningsordninger, daginstitutioner og skoler jeg har gået på.',
					'option_3' => '3. Fritidsinteresser eller noget jeg har brugt meget tid på.',
					'option_4' => '4. Kammerater, venner og fjender der har haft betydning.',
					'option_5' => '5. Fødsler, skilsmisser, dødsfald, samliv og andre familiære forandringer.',
					'option_6' => '6. Voksne som har haft indflydelse på mit liv.',
					'option_7' => '7. Cigaretter, alkohol, hash, gas, piller, stoffer og andet der påvirker adfærden.',
					'option_8' => '8. Hærværk, kriminalitet og andet ulovligt.',
					'option_9' => '9. Forelskelser, kærester, forhold og seksualitet.',
					'option_10' => '10. Oplevelser og beslutninger som har forandret mit liv',
				)
			),
			'1' => array(
				'type' => 'text',
				'title' => 'Da jeg var 1 år gammel',
				'placeholder' => ''
			),
			'2' => array(
				'type' => 'text',
				'title' => 'Da jeg var 2 år gammel',
				'placeholder' => ''
			),
			'3' => array(
				'type' => 'text',
				'title' => 'Da jeg var 3 år gammel',
				'placeholder' => ''
			),
			'4' => array(
				'type' => 'text',
				'title' => 'Da jeg var 4 år gammel',
				'placeholder' => ''
			),
			'5' => array(
				'type' => 'text',
				'title' => 'Da jeg var 5 år gammel',
				'placeholder' => ''
			),
			'6' => array(
				'type' => 'text',
				'title' => 'Da jeg var 6 år gammel',
				'placeholder' => ''
			),
			'7' => array(
				'type' => 'text',
				'title' => 'Da jeg var 7 år gammel',
				'placeholder' => ''
			),
			'8' => array(
				'type' => 'text',
				'title' => 'Da jeg var 8 år gammel',
				'placeholder' => ''
			),
			'9' => array(
				'type' => 'text',
				'title' => 'Da jeg var 9 år gammel',
				'placeholder' => ''
			),
			'10' => array(
				'type' => 'text',
				'title' => 'Da jeg var 10 år gammel',
				'placeholder' => ''
			),
			'11' => array(
				'type' => 'text',
				'title' => 'Da jeg var 11 år gammel',
				'placeholder' => ''
			),
			'12' => array(
				'type' => 'text',
				'title' => 'Da jeg var 12 år gammel',
				'placeholder' => ''
			),
			'13' => array(
				'type' => 'text',
				'title' => 'Da jeg var 13 år gammel',
				'placeholder' => ''
			),
			'14' => array(
				'type' => 'text',
				'title' => 'Da jeg var 14 år gammel',
				'placeholder' => ''
			),
			'15' => array(
				'type' => 'text',
				'title' => 'Da jeg var 15 år gammel',
				'placeholder' => ''
			),
			'16' => array(
				'type' => 'text',
				'title' => 'Da jeg var 16 år gammel',
				'placeholder' => ''
			),
			'17' => array(
				'type' => 'text',
				'title' => 'Da jeg var 17 år gammel',
				'placeholder' => ''
			),
			'18' => array(
				'type' => 'text',
				'title' => 'Da jeg var 18 år gammel',
				'placeholder' => ''
			)
		);
	}
}
?>
