<h1><?=$info['Title']; ?></h1>

<?php if(current_user_can('edit_others_pages')): ?>
	<div class="form-guide">
		<span>Vejledning</span>
		<div class="form-guide-content">
		<p>Først udfyldes felterne hvor den pågældende person beskrives i form af alder, køn og forhold.</p>

		<p>Beskriv derefter dit forhold til personen yderligere ud fra de enkelte spørgsmål.</p>
		</div>
	</div>
<?php endif; ?>

<?php
foreach ($instance->fields as $id => $field) {
	echo '<div class="'.($field['inline'] == 1 ? 'inline ' : '').''.$id.' form-group">';
		$instance->input($id, $field, $data);
	echo '</div>';
};
?>
