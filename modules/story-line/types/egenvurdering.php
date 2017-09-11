<?php
/*
Title: Egenvurdering
Description: Tilføj egenvurdering
Category:
Author: Johnnie Bertelsen
Version: 0.1
*/

Class isl_egenvurdering extends Inzite_Story_Line {

	public function __construct() {
		$this->fields = array(
			'type' => array(
				'type' => 'select',
				'title' => 'Kategori',
				'placeholder' => '',
				'options' => array(
					'option_1' => '1. Hvad er jeg god til, og hvad er jeg ikke så god til?',
					'option_2' => '2. Hvordan klarer jeg tingene nu?',
					'option_3' => '3. Mit syn på mig selv.',
					'option_4' => '4. Mit syn på vores familie og vores vaner.',
				)
			),
			'question_1_1' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At holde orden omkring mig',
				'placeholder' => '',
			),
			'question_1_2' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At gå i skole og lave lektier',
				'placeholder' => ''
			),
			'question_1_3' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At bruge min fantasi',
				'placeholder' => ''
			),
			'question_1_4' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At bruge min krop',
				'placeholder' => ''
			),
			'question_1_5' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At tegne, skabe, forme, eller bygge noget',
				'placeholder' => ''
			),
			'question_1_6' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At se hvad andre har brug for af hjælp',
				'placeholder' => ''
			),
			'question_1_7' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At bede om hjælp',
				'placeholder' => ''
			),
			'question_1_8' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At konkurrere med jævnaldrene',
				'placeholder' => ''
			),
			'question_2_1' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At stå op til tiden',
				'placeholder' => '',
			),
			'question_2_2' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At spise morgenmad og være klar til dagen',
				'placeholder' => '',
			),
			'question_2_3' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At koncentrere mig i skolen',
				'placeholder' => '',
			),
			'question_2_4' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At gøre mine pligter',
				'placeholder' => '',
			),
			'question_2_5' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At have et indhold i fritiden',
				'placeholder' => '',
			),
			'question_2_6' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At prioritere at spise tre sunde måltider om dagen',
				'placeholder' => '',
			),
			'question_2_7' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At gå i seng i ordentlig tid',
				'placeholder' => '',
			),
			'question_2_8' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At være sammen med nogen på min egen alder',
				'placeholder' => '',
			),
			'question_2_9' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At være sammen med nogen, som er en del yngre end jeg',
				'placeholder' => '',
			),
			'question_2_10' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At være sammen med nogen, som er en del ældre end jeg',
				'placeholder' => '',
			),
			'question_2_11' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At være sammen med min familie',
				'placeholder' => '',
			),
			'question_2_12' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At interessere mig for min hygiejne',
				'placeholder' => '',
			),
			'question_2_13' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At være klar over mit sprog og mit humør',
				'placeholder' => '',
			),
			'question_2_14' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'At huske aftaler',
				'placeholder' => '',
			),
			'question_3_1' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'Hvor godt syntes jeg om mig selv?',
				'placeholder' => '',
			),
			'question_3_2' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'Hvor tit bidrager jeg min mening på en konstruktiv måde?',
				'placeholder' => '',
			),
			'question_3_3' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'Hvor godt kan jeg lide at være sammen med andre?',
				'placeholder' => '',
			),
			'question_3_4' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'Hvor godt kan jeg lide at være alene?',
				'placeholder' => '',
			),
			'question_3_5' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'Hvor god er min start på dagen / min morgen?',
				'placeholder' => '',
			),
			'question_3_6' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'Hvor tilfreds er jeg typisk med min hverdag?',
				'placeholder' => '',
			),
			'question_3_7' => array(
				'type' => 'number',
				'min' => '1',
				'max' => '10',
				'title' => 'Hvor godt sover jeg om natten?',
				'placeholder' => '',
			),
			'question_4_1' => array(
				'type' => 'text',
				'title' => 'Hvad laver vi i min familie, når vi hygger os sammen?',
				'placeholder' => '',
			),
			'question_4_2' => array(
				'type' => 'text',
				'title' => 'Hvad laver vi i min familie, når vi har det sjovt sammen?',
				'placeholder' => '',
			),
			'question_4_3' => array(
				'type' => 'text',
				'title' => 'Hvad kan vi i min familie let blive uenige om/ komme til at skændes over?',
				'placeholder' => '',
			),
			'question_4_4' => array(
				'type' => 'text',
				'title' => 'Hvad kan vi i min familie lide at snakke om?',
				'placeholder' => '',
			),
			'question_4_5' => array(
				'type' => 'text',
				'title' => 'Hvad taler vi i min familie aldrig om?  Har vi et tabu?',
				'placeholder' => '',
			),
			'question_4_6' => array(
				'type' => 'text',
				'title' => 'Bruger vi i min familie platte jokes eller iron?  Hvad opnår nogen derved?',
				'placeholder' => '',
			),
			'question_4_7' => array(
				'type' => 'text',
				'title' => 'Er der i min familie én, som er blevet ”familiens sorte får”?',
				'placeholder' => '',
			),
			'question_4_8' => array(
				'type' => 'text',
				'title' => 'Er der i min familie én, som bestemmer mere end de andre?',
				'placeholder' => '',
			),
			'question_4_9' => array(
				'type' => 'textarea',
				'title' => 'Beskriv dine familiemedlemmer. Hvad kendetegner dem hver især?',
				'help_text' => 'Du kan måske finde inspiration i følgende ord: Temperamentsfuld, hjælpsom, hurtig, fornuftig, humoristisk, bestemmende, vidende, ufornuftig, sjov, fantasifuld, kedelig, iderig, langsom.',
				'placeholder' => '',
				'rows' => 8
			),
		);
	}
}
?>
