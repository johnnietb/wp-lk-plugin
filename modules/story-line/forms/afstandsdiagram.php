<h1>Afstandsdiagram</h1>

<?php
foreach ($instance->fields as $id => $field) {
	echo '<div class="'.($field['inline'] == 1 ? 'inline ' : '').''.$id.' question form-group">';
		$instance->input($id, $field, $data);
	echo '</div>';
};
?>
