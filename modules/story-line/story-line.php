<?php
$isl = new Inzite_Story_Line();

Class Inzite_Story_Line {

	// prefix used for classes in the (i)nzite (s)tory (l)ine
	public $classPrefix = 'isl_';

	// storing all the types available
	public $types = array();

	// storing each templates fields
	public $fields = array();

	// storing each templates fields
	public $data = array();

	// maximum hours before disabling edit capabilities
	public $hours = 48;

	private static $headers = array(
		'Title'       => 'Title',
		'Description' => 'Description',
		'Author'      => 'Author',
		'Version'     => 'Version',
		'Category'	  => 'Category'
	);

	public function __construct() {
		foreach (glob(dirname( __FILE__ ) . '/types/*.php') as $type) {
  			require_once($type);
  			$typeName = str_replace(array(dirname( __FILE__ ) . '/types/', '.php'), '', $type);

  			if ( class_exists($this->classPrefix . $typeName) ) {
  				$this->types[$typeName] = get_file_data($type, self::$headers);
  			}
		}
	}

	public function processForm($className, $data, $post_id, $currentData = array(), $returnData = array()) {
		if (isset($this->types[$className])) {
			$class = $this->classPrefix . $className;
			$instance = new $class;

			$date = sanitize_text_field($data['isl_date']);
			if (!$date) {
				$date = date('d-m-Y');
			}

			$returnData['isl_date'] = $date;

			foreach($instance->fields as $field => $options) {

				if ($options['type'] == 'file') {
					$returnData[$field] = $this->processAttachment($field, $options, $post_id, $className, $currentData);
				} else {
					if (isset($data[$field])) {
						$returnData[$field] = $this->processField($data[$field], $options);

					}

				}
			}

			return array(
				$className => $returnData
			);
		}
	}

	public function processAttachment($field, $options, $post_id, $className, $currentData) {

		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_FILES )) {
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			require_once(ABSPATH . "wp-admin" . '/includes/media.php');

			if ( isset($_FILES[$field]['name']) ) {
				if ( ($_FILES[$field]['size'] > 0) && (preg_match("/\.(?i:)(?:jpg|jpeg|gif|png|doc|docx|xls|xlsx|pdf)$/", $_FILES[$field]['name']))  ) {
					$attachment = media_handle_upload($field, $post_id);

					if (!is_object($attachment)) {
						$image = wp_get_attachment_image_src( $attachment , 'inzite_user_images');

						if ($image) {
							return $image[0];
						} else {
							return wp_get_attachment_url($attachment);
						}
					}
				}
			}

		}

		return (isset($currentData[$className][$field])) ? $currentData[$className][$field] : '';
	}

	public function processField($field, $options) {
		// this should validate the fields depending on the type defined in $options, for now we just return everything.
		return htmlspecialchars($field);
	}

	public function forms($className, $data = null) {
		if (isset($this->types[$className])) {
			$class = $this->classPrefix . $className;
			$instance = new $class;
			$info = $this->types[$className];

			if (isset($data[$className])) {
				$data = $data[$className];
			}

			if ( file_exists(dirname( __FILE__ ) . '/forms/' . $className . '.php') ) {
				require_once(dirname( __FILE__ ) . '/forms/' . $className . '.php');
			} else {
				require_once(dirname( __FILE__ ) . '/forms/_default.php');
			}
		}
	}

	public function views($data = array()) {
		if (isset($data)) {
			$className = key($data);
			if (isset($this->types[$className])) {
				$class = $this->classPrefix . $className;
				$instance = new $class;

				// available commands from the templates
				$date = $data[$className]['isl_date'];
				unset($data[$className]['isl_date']);

				$data = $data[$className];
				$info = $this->types[$className];
				$fields = $instance->fields;



				ob_start();
				if ( file_exists(dirname( __FILE__ ) . '/views/' . $className . '.php') ) {
    				require(dirname( __FILE__ ) . '/views/' . $className . '.php');
				} else {
					require(dirname( __FILE__ ) . '/views/_default.php');
				}

				return ob_get_clean();
    			ob_end_flush();
			}
		}
	}

	public function input(string $id, array $field, $data = array()) {
		if (!empty($field['type'])) {

			if (!empty($field['title']))
				echo '<label for="'. $id .'">' . $field['title']; echo '</label>';

			$placeholder = '';
			if (!empty($field['placeholder']))
				$placeholder = $field['placeholder'];

			$value = '';
			if (!empty($data))
				$value = $data[$id];

			switch ($field['type']) {
				case 'editor': //tinymce
					$editor_settings =  array(
						'teeny' => true,
						'media_buttons' => false,
						'wpautop' => true,
						'tinymce' => array('link'=>false, 'resize' => false, 'wp_autoresize_on' => true)
					);
					wp_editor($value, $id, $editor_settings );
					break;

				case 'email': //email
				case 'number': //number
				case 'password': //password
					echo '<input id="' . $id . '" name="' . $id . '" type="' . $field['type'] . '" placeholder="' . $placeholder . '" value="' . $value . '">';
					break;

				case 'select': //password
					echo '<select id="' . $id . '" name="' . $id . '">';
					foreach ($field['options'] as $key => $label) {
						if ($value == $key) {
							echo '<option value="'.$key.'" selected>'.$label.'</option>';
						}
						echo '<option value="'.$key.'">'.$label.'</option>';
					}
					echo '</select>';
					break;

				case 'file': //file
					if ($value)
						echo '<p><img src="' . $value . '"></p>';
					echo '<input id="' . $id . '" name="' . $id . '" type="file" placeholder="' . $placeholder . '" value="' . $value . '">';
					break;

				case 'textarea':
					echo '<textarea id="' . $id . '" name="' . $id . '" placeholder="' . $placeholder . '">' . $value . '</textarea>';
					break;

				default: //text
					echo '<input id="' . $id . '" name="' . $id . '" type="text" placeholder="' . $placeholder . '" value="' . $value . '">';
					break;
			}

		}
	}

	public function form_start() {
		echo '<form action="?story=show" method="POST" enctype="multipart/form-data">';
	}

	public function form_end($type, $date, $userId, $metaId) {
		echo '<div class="submit"><input type="submit" class="story-submit" value="Gem / tilføj" /></div>';
		echo '<input type="hidden" name="isl_action" value="inzite-update-story">';
		echo '<input type="hidden" name="isl_type" value="' . $type . '">';
		echo '<input type="hidden" name="isl_date" value="' . $date . '">';
		echo '<input type="hidden" name="isl_user_id" value="' . $userId . '">';
		echo '<input type="hidden" name="isl_meta_id" value="' . $metaId . '">';
		echo '</form>';
	}

	public function types_menu($add) {
		$story_types = array();

		foreach($this->types as $key => $item)
		{
			if (empty($item['Category'])) {
				$item['Category'] = $item['Title'];
			}
		   $story_types[$item['Category']][$key] = $item;
		}

		ksort($story_types);

		echo '<div class="dropdown" role="menu">';
			if ($add) {
				echo '<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Tilføj sektion<span class="caret"></span></button>';
				echo '<ul class="dropdown-menu multi-level section_types">';

				foreach ($story_types as $category => $types) {

					if (count($types) > 1) {
						echo '<li class="dropdown-submenu"><a href="#">' . $category . ' <span class="badge">' . count($types) . '<span></a>' . PHP_EOL;
						echo '<ul class="dropdown-menu">' . PHP_EOL;
					}

					foreach ($types as $type => $headers) {
						echo '<li><a href="?update=show&type='.$type.'">' . $headers['Title'] . '</a></li>' . PHP_EOL;
					}

					if (count($types) > 1) {
						echo '</ul>' . PHP_EOL;
						echo '</li>' . PHP_EOL;
					}

				}

				echo '</ul>';
				echo '<a href="?pdf=download" target="_blank" class="button btn-success">Gem som PDF</a>';
			} else {
				echo '<a href="?pdf=download&user_id='.$current_user->ID.'" target="_blank" class="button btn-success">Gem som PDF</a>';
			}
		echo '</div>';
	}

}
?>
