<div class="isl-item__title-line">
	<h2 class="isl-item__title">Egenvurdering</h2>
	<date><?php echo $date; ?></date>
</div>

<?php if (!empty($data)) {
	foreach ($data as $id => $field) {

		if ($field && $id != 'type'):
	    $width = (intval($field)/10)*100;

			if( strpos($id, "question_4") !== 0): ?>
				<div class="isl-item__content" id="foto_<?php echo $id; ?>">

					<div class="title">
						<?php print_r($fields[$id]['title']); ?>
					</div>

					<div class="progress">
						<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $width; ?>%" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $width; ?>%; style="min-width: 2em;"">
							<?php echo $field; ?>
						</div>
					</div>

				</div>
			<?php
			else:
				echo '<p>' . $fields[$id]['title'] . '</p>';
				echo '<p><span class="answer"> ' . $field . '</span></p>';
			endif;
		endif;
	}
}; ?>
