<div class="isl-item__title-line">
	<h2 class="isl-item__title">Egenvurdering</h2>
	<date><?php echo $date; ?></date>
</div>



<?php if (!empty($data)) {



	foreach ($data as $key => $value) {
		if ($value && $key != 'type') {

      $width = (intval($value)/10)*100;

      ?>
    	<div class="isl-item__content" id="foto_<?php echo $id; ?>">

        <div class="title">
          <?php print_r($fields[$key]['title']); ?>
        </div>

        <div class="progress">
          <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $width; ?>%" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $width; ?>%; style="min-width: 2em;"">
            <?php echo $value; ?>
          </div>
        </div>

    	</div>
    <?php
		}
  }

}; ?>
