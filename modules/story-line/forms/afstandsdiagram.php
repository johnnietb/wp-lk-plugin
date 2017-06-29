<h1>Relationsdiagram</h1>

<?php
foreach ($instance->fields as $id => $field) {
	echo '<div class="'.($field['inline'] == 1 ? 'inline ' : '').''.$id.' question form-group">';
		$instance->input($id, $field, $data);
	echo '</div>';
};
?>
