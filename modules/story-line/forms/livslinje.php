<h2>Livslinje</h2>
<?php if(current_user_can('edit_others_pages')): ?>
	<div class="form-guide">
		<span>Vejledning</span>
		<div class="form-guide-content">
			<p>Vælg den ønskede kategori i øverste felt og besvar spørgsmålet i felterne nedenfor baseret på din alder.</p>
		</div>
	</div>
<?php endif; ?>

<?php
foreach ($instance->fields as $id => $field) {
	echo '<div class="' . ($field['inline'] == 1 ? 'inline ' : '') . '' . $id . ' form-group">';
	$instance->input($id, $field, $data);
	echo '</div>';
};
?>
