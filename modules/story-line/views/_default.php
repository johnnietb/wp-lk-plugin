<div class="isl-item__title-line">
	<h2 class="isl-item__title" ><?=$info['Title']; ?></h2>
	<date><?=$date; ?></date>
</div>

<?php foreach ($data as $id => $field) { ?>
	<div class="<?= $id; ?>">
		<?= str_replace("\r\n", "<br \>", $field); ?>
	</div>
<?php }; ?>
