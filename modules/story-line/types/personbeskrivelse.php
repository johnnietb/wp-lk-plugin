<?php
/*
Title: Personbeskrivelse
Description: Tilføj Personbeskrivelse
Category: Personbeskrivelse
Author: Johnnie Bertelsen
Version: 0.1
 */

class isl_personbeskrivelse extends Inzite_Story_Line
{

    public function __construct()
    {
        $this->fields = array(
            'sex'          => array(
                'type'        => 'select',
                'title'       => 'Personens køn',
                'placeholder' => '',
                'options'     => array(
                    'Mand',
                    'Kvinde',
                ),
            ),
            'beskrivelse'  => array(
                'type'        => 'text',
                'title'       => 'Personens forhold til mig',
                'placeholder' => '',
                'inline'      => true,
            ),
            'age'          => array(
                'type'        => 'number',
                'title'       => 'Hans/hendes alder',
                'text_after'  => '',
                'placeholder' => 'Skriv alder i år.',
                'min'         => '0',
                'max'         => '120',
                'inline'      => true,
            ),
            'bor_i'        => array(
                'type'        => 'text',
                'title'       => 'Han/hun bor i',
                'inline'      => true,
                'placeholder' => '..et lille hus på landet.',
            ),
            'bor_med'      => array(
                'type'        => 'text',
                'title'       => 'Han/hun bor sammen med',
                'inline'      => true,
                'placeholder' => '..sine to børn.',
            ),
            'kan_lide'     => array(
                'type'        => 'text',
                'title'       => 'Han/hun kan godt lide',
                'inline'      => true,
                'placeholder' => '',
            ),
            'hader'        => array(
                'type'        => 'text',
                'title'       => 'Han/hun hader at',
                'inline'      => true,
                'placeholder' => '',
            ),
            'arbejde'      => array(
                'type'        => 'text',
                'title'       => 'Hans/hendes arbejde er',
                'inline'      => true,
                'placeholder' => '',
            ),
            'fritid'       => array(
                'type'        => 'text',
                'title'       => 'Hans/hendes fritid er',
                'inline'      => true,
                'placeholder' => '',
            ),
            'vaeremaade'   => array(
                'type'        => 'text',
                'title'       => 'Hans/hendes måde at være på er',
                'inline'      => true,
                'placeholder' => '',
            ),
            'relation'     => array(
                'type'        => 'text',
                'title'       => 'Hans/hendes relation til mig er',
                'inline'      => true,
                'placeholder' => '',
            ),
            'kan_lide_mig' => array(
                'type'        => 'textarea',
                'rows'        => 5,
                'title'       => 'Det kan jeg lide ham/hende for',
                'placeholder' => '',
            ),
            'hader_mig'    => array(
                'type'        => 'textarea',
                'rows'        => 5,
                'title'       => 'Det værste ved ham/hende',
                'placeholder' => '',
            ),
            'ligner'       => array(
                'type'        => 'textarea',
                'rows'        => 5,
                'title'       => 'Vores liv har lignet hinanden på flg. områder',
                'placeholder' => '',
            ),
            'sidst_set'    => array(
                'type'        => 'textarea',
                'rows'        => 5,
                'title'       => 'Sidst jeg var sammen med ham/hende',
                'placeholder' => '',
            ),
        );
    }
}
