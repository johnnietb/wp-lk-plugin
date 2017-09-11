<div class="isl-item__title-line">
	<h2 class="isl-item__title" ><?php echo $info['Title']; ?></h2>
	<date><?php echo $date; ?></date>
</div>

<?php foreach ($data as $id => $field) :
	echo '<div class="'.$id.'">';
		echo str_replace("\r\n", "<br \>", $field);
	echo '</div>';
endforeach; ?>
