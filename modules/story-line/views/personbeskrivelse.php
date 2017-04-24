<div class="isl-item__title-line">
	<h2 class="isl-item__title">Personbeskrivelse</h2>
	<date><?php echo $date; ?></date>
</div>


<?php foreach ($data as $id => $field) { ?>
	<?php if (!empty($field)) { ?>

			<div class="isl-item__content livslinje_<?php echo  $id; ?>">

				<?php if ( $id == 'beskrivelse' ) {
					echo '<h3 class="isl-item__subtitle">' . $fields[$id]['title'] . ' ' . $field . '</h3>';
				} elseif( $id == 'age' ) {
					echo '<p>Han er <span class="answer">' . $field . '</span> år gammel</p>';
				} elseif( $id == 'kan_lide_mig' || $id == 'hader_mig' || $id == 'ligner' || $id == 'sidst_set' ) {

					if (!empty($fields[$id]['title']))
					{
						echo '<p>';
						echo $fields[$id]['title'];
						echo '</p>';
					};
					echo '<p><span class="answer"> ' . $field . '</span></p>';
				} else {
					echo '<p>';
					if (!empty($fields[$id]['title']))
					{
						echo $fields[$id]['title'];
					};
					echo '<span class="answer"> ' . $field . '</span></p>';
				}


			?> </div>
		<?php }; ?>
<?php }; ?>
