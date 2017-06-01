<div class="isl-item__title-line">
	<h2 class="isl-item__title">Historisk foto</h2>
	<date><?php echo $date; ?></date>
</div>

<?php if (!empty($data)) { ?>
	<div class="isl-item__content" id="foto_<?php echo $id; ?>">
		<div class="photo">
			<a href="<?php echo $data['foto']; ?>" class="fancybox">
				<img src="<?php echo $data['foto']; ?>" alt="<?php echo $fields['alder']['title']; ?>"/>
			</a>
		</div>

		<div class="caption">
			<p>
				Billedet er taget da jeg var ca. <span class="answer"><?php echo $data['age']; ?> år gammel</span> og er taget da:
			</p>

			<p>
				<span class="answer"><?php echo $data['fordi']; ?></span>
			</p>

			<p>
				<?php echo $fields['husker']['title']; ?>
			</p>

			<p>
				<span class="answer"><?php echo $data['husker']; ?></span>
			</p>
		</div>

	</div>
<?php }; ?>
