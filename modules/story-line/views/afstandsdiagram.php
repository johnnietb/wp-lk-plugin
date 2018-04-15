<div class="isl-item__title-line">
	<h2 class="isl-item__title">Relationsdiagram</h2>
	<date><?php echo $date; ?></date>

</div>
<p style="margin-bottom: 16px;"><?=$fields['type']['options'][$data['type']];?></p>

<?php

//var_dump($data);

if ( !empty( $data ) ):

    echo '<div class="afstandsdiagram">';
    echo '<div class="me">';
    echo '<span>Her er jeg</span>';
    echo '</div>';

    // Get the type
    $question_id = str_replace( 'option_', '', $data['type'] );

    $answer_number   = 0;
    $question_number = 0;
    // Filter the questions based on the chosen type
    foreach ( $data as $key => $value ) {
        $exp_key = explode( '_', $key );
        if ( $exp_key[1] == $question_id ) {
            $question_number++;
            $arr_result[] = $value;
            $arr_title[]  = 'question_' . $question_id . '_' . $question_number;
        }
    }
    //var_dump($fields);
    //var_dump($arr_title);

    // Loop through the answers
    foreach ( $arr_result as $question ):

        // Split answer into lines
        $text   = trim( $question );
        $textAr = explode( "\n", $text );
        $textAr = array_filter( $textAr, 'trim' ); // remove any extra \r characters left behind

        $answer_distance = ['very-close', 'close', 'far', 'very-far'];

        echo '<div class="distance distance_' . $answer_distance[$answer_number] . '">';
        // Output the question name
        echo '<div class="distance__title">';
        echo '<span>' . $fields[$arr_title[$answer_number]]['title'] . '</span>';
        echo '</div>';
        echo '<ul class="distance__list">';
        foreach ( $textAr as $line ) {
            echo '<li class="distance__name">' . $line . '</li>';
        }

        echo '</ul>';
        echo '</div>';

        $answer_number++;
    endforeach;

    echo '</div>';
endif;
?>
