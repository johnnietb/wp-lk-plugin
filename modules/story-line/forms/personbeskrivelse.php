<h1><?=$info['Title']; ?></h1>

	<div class="form-guide">
		<span>Vejledning</span>
		<div class="form-guide-content">
		<p>Først udfyldes felterne hvor den pågældende person beskrives med alder, køn og relation til dig. Er han din onkel, din lærer eller din kammerat?</p>

		<p>Beskriv derefter dit forhold til personen yderligere ud fra de enkelte spørgsmål.</p>
		</div>
	</div>

<?php
foreach ($instance->fields as $id => $field) {
	echo '<div class="'.($field['inline'] == 1 ? 'inline ' : '').''.$id.' form-group">';
		$instance->input($id, $field, $data);
	echo '</div>';
};
?>
