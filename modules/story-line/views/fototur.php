<div class="isl-item__title-line">
	<h2 class="isl-item__title">Fototur</h2>
	<date><?php echo $date; ?></date>
</div>

<?php if (!empty($data)) { ?>
	<div class="isl-item__content" id="foto_<?php echo $id; ?>">
		<div class="photo">
			<a href="<?php echo $data['foto']; ?>" class="fancybox">
				<img src="<?php echo $data['foto']; ?>" />
			</a>
		</div>

		<div class="caption">
			<p><?php echo $fields['hukommelse']['title']; ?></p>
			<p><span class="answer"><?php echo $data['hukommelse']; ?></span></p>

			<p><?php echo $fields['mit_liv']['title']; ?></p>
			<p><span class="answer"><?php echo $data['mit_liv']; ?></span></p>

			<p><?php echo $fields['sammen_med']['title']; ?></p>
			<p><span class="answer"><?php echo $data['sammen_med']; ?></span></p>
			
		</div>

	</div>
<?php }; ?>
