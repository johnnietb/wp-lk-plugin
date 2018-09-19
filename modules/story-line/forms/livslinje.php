<h2>Livslinje</h2>
	<div class="form-guide">
		<span>Vejledning</span>
		<div class="form-guide-content">
			<p>Vælg først den ønskede kategori i øverste felt.</p>
			<p>Herefter udfyldes felterne med adresser, navne eller ord relevante for kategorien. Hvad og hvem har været en del af dit liv, hvornår?</p>
		</div>
	</div>

<?php
foreach ($instance->fields as $id => $field) {
	echo '<div class="' . ($field['inline'] == 1 ? 'inline ' : '') . '' . $id . ' form-group">';
	$instance->input($id, $field, $data);
	echo '</div>';
};
?>
