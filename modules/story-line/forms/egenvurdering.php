<h1>Egenvurdering</h1>

<?php
foreach ($instance->fields as $id => $field) {
	echo '<p class="'.($field['inline'] == 1 ? 'inline ' : '').''.$id.' question form-group">';
		$instance->input($id, $field, $data);
	echo '</p>';
};
?>
