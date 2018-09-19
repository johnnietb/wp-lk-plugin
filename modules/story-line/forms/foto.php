<h1><?=$info['Title']; ?></h1>

	<div class="form-guide">
		<span>Vejledning</span>
		<div class="form-guide-content">
			<p>Upload et fotografi eller billede fra din PC eller anden enhed.</p>

			<p>Lav derefter en beskrivelse af hvornår billedet blev taget og hvad du kan huske om tiden hvor fotoet er fra.</p>
		</div>
	</div>

<?php
foreach ($instance->fields as $id => $field) {
	echo '<div class="'.($field['inline'] == 1 ? 'inline ' : '').''.$id.' form-group">';
		$instance->input($id, $field, $data);
	echo '</div>';
};
?>
