<h2>Livslinje</h2>
<date><?=$date; ?></date>
<h3><?=$info['Title']; ?></h3>


<?php foreach ($data as $id => $field) { ?>
	<?php if (!empty($field)) { ?>
	<div class="livslinje_<?= $id; ?>">
		
		<?php if (!empty($fields[$id]['title'])) { ?>
			<div>
				<b><?= $fields[$id]['title']; ?></b>
			</div>
		<?php }; ?>	

		<p>
			<?= $field; ?>
		</p>

	</div>
	<?php }; ?>
<?php }; ?>