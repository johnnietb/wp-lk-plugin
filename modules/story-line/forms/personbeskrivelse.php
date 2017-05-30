<h1><?=$info['Title']; ?></h1>

<?php
foreach ($instance->fields as $id => $field) {
	echo '<div class="'.($field['inline'] == 1 ? 'inline ' : '').''.$id.' form-group">';
		$instance->input($id, $field, $data);
	echo '</div>';
};
?>
