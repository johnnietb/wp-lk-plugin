<h1><?=$info['Title']; ?></h1>

<?php
foreach ($instance->fields as $id => $field) {
	echo '<div>';
		$instance->input($id, $field, $data);
	echo '</div>';
};
?>