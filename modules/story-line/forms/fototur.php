<h1><?=$info['Title']; ?></h1>

	<div class="form-guide">
		<span>Vejledning</span>
		<div class="form-guide-content">
			<p>Upload et fotografi fra fototuren.</p>

			<p>Lav derefter en beskrivelse om stedet, minderne og gensynet.</p>
		</div>
	</div>

<?php
foreach ($instance->fields as $id => $field) {
	echo '<div class="'.($field['inline'] == 1 ? 'inline ' : '').''.$id.' form-group">';
		$instance->input($id, $field, $data);
	echo '</div>';
};
?>
