<h1><?=$info['Title']; ?></h1>

<?php
foreach ($instance->fields as $id => $field) {
	echo '<p '.($field['inline'] == 1 ? 'class="inline"' : '').'>';
		$instance->input($id, $field, $data);
	echo '</p>';
};
?>