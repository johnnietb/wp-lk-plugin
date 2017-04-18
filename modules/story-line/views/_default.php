<h2><?=$info['Title']; ?></h2>
<date><?=$date; ?></date>

<?php foreach ($data as $id => $field) { ?>
	<div class="<?= $id; ?>">
		<?= str_replace("\r\n", "<br \>", $field); ?>
	</div>
<?php }; ?>