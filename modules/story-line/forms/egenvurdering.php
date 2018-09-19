<h1>Egenvurdering</h1>

	<div class="form-guide">
		<span>Vejledning</span>
		<div class="form-guide-content">
		<p>Vælg først din ønskede spørgsmålskategori i øverste felt.</p>

		<p>Herefter besvares spørgsmålene fra valgte kategori.</p>
		</div>
	</div>

<?php
foreach ($instance->fields as $id => $field) {
	echo '<div class="'.($field['inline'] == 1 ? 'inline ' : '').''.$id.' question form-group">';
		$instance->input($id, $field, $data);
	echo '</div>';
};
?>
