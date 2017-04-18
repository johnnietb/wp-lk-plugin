<h2>Foto</h2>
<date><?=$date; ?></date>

<?php if (!empty($data)) { ?>
	<div class="foto" id="foto_<?= $id; ?>">
		<img src="<?= $data['foto']; ?>" alt="<?= $fields['alder']['title']; ?>"/>

		<div>
			<?= $fields['alder']['title']; ?> <b><?= $data['alder']; ?></b> <?= $fields['fordi']['title']; ?>
		</div>

		<p>
			<?= $data['fordi']; ?>
		</p>

		<div>
			<?= $fields['husker']['title']; ?>
		</div>

		<p>
			<?= $data['husker']; ?>
		</p>


	</div>
<?php }; ?>