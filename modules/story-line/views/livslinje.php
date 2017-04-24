<div class="isl-item__title-line">
	<h2 class="isl-item__title">Livslinje</h2>
	<date><?php echo $date; ?></date>
</div>

<?php
$types_array = array(
	'option_1' => '1. Steder og adresser jeg har boet på.',
	'option_2' => '2. Pasningsordninger, daginstitutioner og skoler jeg har gået på.',
	'option_3' => '3. Fritidsinteresser eller noget jeg har brugt meget tid på.',
	'option_4' => '4. Kammerater, venner og fjender der har haft betydning.',
	'option_5' => '5. Fødsler, skilsmisser, dødsfald, samliv og andre familiære forandringer.',
	'option_6' => '6. Voksne som har haft indflydelse på mit liv.',
	'option_7' => '7. Cigaretter, alkohol, hash, gas, piller, stoffer og andet der påvirker adfærden.',
	'option_8' => '8. Hærværk, kriminalitet og andet ulovligt.',
	'option_9' => '9. Forelskelser, kærester, forhold og seksualitet.',
	'option_10' => '10. Oplevelser og beslutninger som har forandret mit liv',
);

$type = $types_array[$data['type']];

?>

<h3 class="isl-item__subtitle"><?php echo $type; ?></h3>

<?php foreach ($data as $id => $field) { ?>
	<?php if (!empty($field)) {

		if ($id == 'type') {
			# code...
		} else { ?>
			<div class="isl-item__content livslinje_<?php echo  $id; ?>">

				<?php if (!empty($fields[$id]['title'])) { ?>
					<p>
						<?php echo  $fields[$id]['title']; ?>
					</p>
				<?php }; ?>

				<p>
					<span class="answer"><?php echo  $field; ?></span>
				</p>

			</div>
		<?php }
	}; ?>
<?php }; ?>
