<h2>Livslinje</h2>

<?php
foreach ($instance->fields as $id => $field) {
	echo '<div class="' . ($field['inline'] == 1 ? 'inline ' : '') . '' . $id . ' form-group">';
	$instance->input($id, $field, $data);
	echo '</div>';
};
?>
