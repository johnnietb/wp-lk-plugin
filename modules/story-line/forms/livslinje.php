<h2>Livslinje</h2>
<h3>Oplevelser og beslutninger som har forandret mit liv</h3>

<?php
foreach ($instance->fields as $id => $field) {
	echo '<div>';
		$instance->input($id, $field, $data);
	echo '</div>';
};
?>