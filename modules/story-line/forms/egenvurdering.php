<h1>Egenvurdering</h1>

<?php if(current_user_can('edit_others_pages')): ?>
	<div class="form-guide">
		<span>Vejledning</span>
		<div class="form-guide-content">
		<p>Vælg først din ønskede spørgsmålskategori i det første felt.</p>

		<p>Herefter skal du besvare spørgsmålene baseret på det valgte spørgsmål.</p>
		</div>
	</div>
<?php endif; ?>

<?php
foreach ($instance->fields as $id => $field) {
	echo '<div class="'.($field['inline'] == 1 ? 'inline ' : '').''.$id.' question form-group">';
		$instance->input($id, $field, $data);
	echo '</div>';
};
?>
