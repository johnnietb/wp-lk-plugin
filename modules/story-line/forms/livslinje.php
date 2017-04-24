<h2>Livslinje</h2>

<?php
foreach ($instance->fields as $id => $field) {
	echo '<p>';
		$instance->input($id, $field, $data);
	echo '</p>';
};
?>
