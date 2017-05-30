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
					if ($data['sex'] == 1) {
						echo '<p>Hun er <span class="answer">' . $field . '</span> år gammel</p>';						
					} else {
						echo '<p>Han er <span class="answer">' . $field . '</span> år gammel</p>';
					}
				} elseif( $id == 'kan_lide_mig' || $id == 'hader_mig' || $id == 'ligner' || $id == 'sidst_set' ) {

					if (!empty($fields[$id]['title']))
					{
						echo '<p>';
						if ($data['sex'] == 1) {
							$title = $fields[$id]['title'];
							$title = str_replace("ham", "hende", $title);
							$title = str_replace("Ham", "Hende", $title);
							$title = str_replace("han", "hun", $title);
							$title = str_replace("Han", "Hun", $title);
							$title = str_replace("hans", "hendes", $title);
							$title = str_replace("Hans", "Hendes", $title);
							echo $title;
						} else {
							echo $fields[$id]['title'];
						}
						echo '</p>';
					};
					echo '<p><span class="answer"> ' . $field . '</span></p>';
				} else {
					echo '<p>';
					if (!empty($fields[$id]['title']))
					{
						if ($data['sex'] == 1) {
							$title = $fields[$id]['title'];
							$title = str_replace("ham", "hende", $title);
							$title = str_replace("Ham", "Hende", $title);
							$title = str_replace("hans", "hendes", $title);
							$title = str_replace("Hans", "Hendes", $title);
							$title = str_replace("han", "hun", $title);
							$title = str_replace("Han", "Hun", $title);
							echo $title;
						} else {
							echo $fields[$id]['title'];
						}
					};
					echo '<span class="answer"> ' . $field . '</span></p>';
				}


			?> </div>
		<?php }; ?>
<?php }; ?>
