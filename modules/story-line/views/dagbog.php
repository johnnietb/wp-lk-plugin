<div class="isl-item__title-line">
	<h2 class="isl-item__title">Dagbogsindlæg</h2>
	<date><?php echo $date; ?></date>
</div>

<?php if (!empty($data)) { ?>
	<div class="isl-item__content" style="overflow: auto;" id="dagbog_<?php echo $id; ?>">
		<div class="caption" style="margin-bottom: 2rem;">
			<?php echo $data['content']; ?>
		</div>

		<div class="photo">
			<a href="<?php echo $data['file']; ?>" class="fancybox">
				<img src="<?php echo $data['file']; ?>" />
			</a>
		</div>
	</div>
<?php }; ?>
