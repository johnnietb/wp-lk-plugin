<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
Class Inzite_Chat {
	function __construct() {
		date_default_timezone_set('Europe/Copenhagen');

		// init functions
		register_activation_hook( __FILE__ , array( $this, 'chat_activation_hook' ) );
		register_deactivation_hook( __FILE__ , array( $this, 'chat_deactivation_hook' ) );
		add_action( 'init' , array( $this, 'register_chat_post_types' ) );
		add_action( 'wp_enqueue_scripts' , array( $this, 'enqueue_scripts' ) );

		// admin functions
		add_action( 'add_meta_boxes', array( $this, 'add_extra_fields_admin' ) );

		// chat room functions
		add_action( 'save_post' , array( $this, 'maybe_create_chatroom_log_file' ), 10, 2 );
		add_action( 'wp_ajax_inzite_check_updates', array( $this, 'ajax_check_updates_handler' ) );
		add_action( 'wp_ajax_inzite_send_message', array( $this, 'ajax_send_message_handler' ) );
		add_filter( 'the_content', array( $this, 'the_content_filter' ) );

	}

	function chat_activation_hook() {
		$this->register_post_types();
		flush_rewrite_rules();
	}

	function chat_deactivation_hook() {
		flush_rewrite_rules();
	}

	function register_chat_post_types() {
		$labels = array(
			'name' => _x( 'Chat Rum', 'post type general name', 'chatroom' ),
			'singular_name' => _x( 'Chat Rum', 'post type singular name', 'chatroom' ),
			'add_new' => _x( 'Tilføj ny', 'book', 'chatroom' ),
			'add_new_item' => __( 'Tilføj nyt Chat Rum', 'chatroom' ),
			'edit_item' => __( 'Rediger Chat Rum', 'chatroom' ),
			'new_item' => __( 'Nyt Chat Rum', 'chatroom' ),
			'all_items' => __( 'Alle Chat Rum', 'chatroom' ),
			'view_item' => __( 'Vis Chat Rum', 'chatroom' ),
			'search_items' => __( 'Søg Chat Rum', 'chatroom' ),
			'not_found' => __( 'Ingen Chat Rum fundet', 'chatroom' ),
			'not_found_in_trash' => __( 'Ingen Chat Rum fundet', 'chatroom' ),
			'parent_item_colon' => '',
			'menu_name' => __( 'Chat Rum', 'chatroom' )
		);
		$rewrite = array(
			'slug'                  => 'chat',
			'with_front'            => true,
			'pages'                 => true,
			'feeds'                 => true,
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => null,
			'menu_icon' => 'dashicons-admin-comments',
			'show_in_nav_menus' => true,
			'supports' => array( 'title' ),
			'rewrite' => $rewrite
		);
		register_post_type( 'inzite-chat', $args );
		flush_rewrite_rules();

	}

	function enqueue_scripts() {
		global $post;
		if ( empty($post->post_type) || $post->post_type != 'inzite-chat' )
			return;
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'inzite-chat', plugins_url( 'chat-room.js', __FILE__ ) );
		wp_enqueue_style( 'inzite-chat-styles', plugins_url( 'chat-room.css', __FILE__ ) );
		wp_localize_script( 'inzite-chat' , 'inzite_chat', array('ajaxurl' => admin_url( 'admin-ajax.php') , 'inzite_chat_slug' => $post->post_name, 'inzite_chat_id' => $post->ID ));
	}

	function add_extra_fields_admin()
	{
	    add_meta_box(
	        'chat_overview',
	        __( 'Tidligere chat' ),
	        array( $this, 'admin_chat_overview'),
	        'inzite-chat',
	        'normal',
	        'high'
	    );
	}

	function admin_chat_overview( $chatroom ) {
		global $post;
		//update_post_meta( $post->ID , 'chat_dates' , array('20160208'=>'08-02-2016','20160209'=>'09-02-2016') );
		$chat_dates = get_post_meta( $post->ID , 'chat_dates' , true );
		if ($chat_dates) {
			krsort($chat_dates);
			foreach ( $chat_dates as $key => $dates ) {
				$chat_data = get_post_meta($post->ID , 'chat_' . $this->get_key_from_db($dates) , true );
				if ($chat_data) {
						echo '<div style="padding-bottom: 1.33em; border-bottom: 5px solid #eee;">';
						echo '<h4>Dato: ' . $dates . '</h4>';

							$messages = $chat_data;
							foreach ( $messages as $key => $message ) {
								echo $message['html'];
							}

						echo '</div>';
				}
			}
		} else {
			echo 'Ingen chat fundet sted...';
		}

	}

	function maybe_create_chatroom_log_file( $post_id, $post ) {

		if ( empty( $post->post_type ) || $post->post_type != 'inzite-chat' )
			return;
		$chat_dates = get_post_meta( $post->ID , 'chat_dates' , true );
		if ( $chat_dates ) {
			krsort($chat_dates);

			foreach ($chat_dates as $key => $date) {
				if ( $this->get_key_from_db() != $key) {
					$messages = get_post_meta($post->ID , 'chat_' . $this->get_key_from_db($date) , true );
					if ( empty($messages) ) {
						unset( $chat_dates[$key] );
					}
				}
			}
			if ( !array_key_exists( $this->get_key_from_db() , $chat_dates) ) {
				$chat_dates[ $this->get_key_from_db() ] = date('d-m-Y');
				update_post_meta( $post->ID , 'chat_' . $this->get_key_from_db() ,  array() );
			}
			
		}
		else {
			$chat_dates[ $this->get_key_from_db() ] = date('d-m-Y');
		}
		update_post_meta( $post->ID , 'chat_dates' , $chat_dates );
	}


	function ajax_check_updates_handler($date = '') {
		$inzite_chat_slug = sanitize_title($_POST['inzite_chat_slug']);
		$inzite_chat_id = intval($_POST['inzite_chat_id']);
		$last_update_id = 0;
		$date = esc_attr($_POST['previous']);
		if ( empty($date) )
		 	$date = '';

		$messages = get_post_meta($inzite_chat_id , 'chat_' . $this->get_key_from_db($date) , true );
		if ($messages && $date == '') {
			foreach ( $messages as $key => $message ) {
				if ( $message['id'] <= intval($_POST['last_update_id']) ) {
					unset( $messages[$key] );
				} else {
					$last_update_id = $message['id'];
				}
			}
			$messages = array_values( $messages );
		}

		if ($messages && $date == '') {
			$current_user = wp_get_current_user();
			if ($current_user) {
				update_user_meta($current_user->id, 'chat_'.$inzite_chat_slug, $last_update_id);
				update_post_meta($inzite_chat_id, 'chat_'.$inzite_chat_slug, $last_update_id);
			}
		} else if( $last_update_id == 0) {
			$current_user = wp_get_current_user();
			if ($current_user) {
				$last_update_id = intval($_POST['last_update_id']);
				$current_update_id = intval(get_user_meta($current_user->id, 'chat_'.$inzite_chat_slug, true));
				if ($current_update_id < $last_update_id) {
					update_user_meta($current_user->id, 'chat_'.$inzite_chat_slug, $last_update_id);
				}
			}

		}

		echo json_encode( $messages );
		die;
	}

	function ajax_send_message_handler() {
		$current_user = wp_get_current_user();
		$this->save_message( intval($_POST['inzite_chat_id']), $current_user->id, $_POST['message'] );
		die;
	}


	function save_message( $inzite_chat_id, $user_id, $content ) {
		$user = get_userdata( $user_id );

		if ( ! $user_text_color = get_user_meta( $user_id, 'user_chat_color', true ) ) {
	    	// Set random color for each user
	    	$red = rand( 0, 16 );
	    	$green = 16 - $red;
	    	$blue = rand( 0, 16 );
		    $user_text_color = '#' . dechex( $red^2 ) . dechex( $green^2 ) . dechex( $blue^2 );
	    	update_user_meta( $user_id, 'user_chat_color', $user_text_color );
	    }

		$content = (nl2br(esc_attr( $content )));


		$messages = get_post_meta($inzite_chat_id , 'chat_' . $this->get_key_from_db() , true );
		//$messages = json_decode( $messages );
		$last_message_id = 0;
		if ( ! empty( $messages ) ) {
			$last_message_id = end( $messages )['id'];
		} else {
			// get last ID from previous chat!
			$chat_dates = get_post_meta( $inzite_chat_id , 'chat_dates' , true );
			if ( $chat_dates ) {
				krsort($chat_dates);
				foreach ($chat_dates as $key => $dates) {
					if ( $key != $this->get_key_from_db() ) {
						$old_messages = get_post_meta($inzite_chat_id , 'chat_' . $this->get_key_from_db($dates) , true );
						if ( ! empty( $old_messages ) ) {
							$last_message_id = end( $old_messages )['id'];
							break;
						}
					}
				}
			}
		}

		$new_message_id = $last_message_id + 1;


		$attachment = $this->process_attachment($inzite_chat_id);
		if ($attachment) {
			$content .= '<div><a class="chat-attachment" href="' . wp_get_attachment_url($attachment) . '" target="_blank">Fil knyttet, download filen: '. get_the_title($attachment) .'</a></div>';
		}
		$messages[] = array(
			'id' => $new_message_id,
			'time' => time(),
			'sender' => $user_id,
			'contents' => $content,
			'html' => '<div class="chat-message-' . $new_message_id . '"><strong style="color: ' . $user_text_color . ';">' . date( 'H:i:s' ,time()) . ' <a style="color: ' . $user_text_color . ';" href="' . site_url( '/profile/' . $user->user_nicename . '/' ) . '" target="_blank">' . $user->display_name . '</a></strong>: ' . $content . '</div>',
		); //'html' => '<div class="chat-message-' . $new_message_id . '"><strong style="color: ' . $user_text_color . ';">' . date( 'H:i:s' ,time()) . ' ' . $user->user_login . '</strong>: ' . $content . '</div>',
		//update_post_meta($inzite_chat_id , 'chat_' . $this->get_key_from_db() , json_encode($messages) );
		update_post_meta($inzite_chat_id , 'chat_' . $this->get_key_from_db() , ($messages) );
		//echo json_encode($messages);
	}


	function process_attachment($inzite_chat_id) {
		if ( !wp_verify_nonce( $_POST['attachment_nonce'], 'attachment') ) {
	        //return;
	  }

		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_FILES )) {
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			require_once(ABSPATH . "wp-admin" . '/includes/media.php');

					if ( isset($_FILES['file']['name']) ) {

						//($_FILES['file']['type'] == 'image/jpeg' || $_FILES['file']['type'] == 'image/png' || $_FILES['file']['type'] == 'application/pdf')
						if ( ($_FILES['file']['size'] > 0) && (preg_match("/\.(?i:)(?:jpg|jpeg|gif|png|doc|docx|xls|xlsx|pdf)$/", $_FILES['file']['name']))  )
						{
							return media_handle_upload('file',$inzite_chat_id);
						}
					}

			 return;
		}
		else
		{
			return;
		}
	}



	function get_key_from_db( $date = '' ) {
		if (!$date) {
			$date = date('Ymd');
		} else {
			$date = date('Ymd', strtotime($date));
		}
		$log_key_name = $date;
		return $log_key_name;
	}

	function the_content_filter( $content ) {
		global $post;
		if ( !in_the_loop() ) 
			return $content;

		if ( $post->post_type != 'inzite-chat' )
			return $content;

		if ( ! is_user_logged_in() )  {
			echo 'Du har ikke rettigheder til at se denne side.';
			return;
		}
		
		$this->maybe_create_chatroom_log_file( '' , $post);

		$chat_dates = get_post_meta( $post->ID , 'chat_dates' , true );
		if ( $chat_dates ) {
			ksort($chat_dates);
			echo '<div class="chat-sessions">';
			echo 'Tidligere sessioner: ';
			foreach ($chat_dates as $key => $date) {
				if ($this->get_key_from_db() != $key) {
					echo '<a data-date="'.$date.'">' . $date . '</a>'; // onclick="inzite_chat_check_updates(\''.$date.'\');"
				}
			}
			echo '</div>';
		}

		echo '<div class="chat-container"></div>';
		echo '<textarea class="chat-text-entry"></textarea>';
		echo '<button class="chat-submit">Send</button>';
		echo '<div class="chat-helper">Hold skift/shift nede mens du trykker enter/return for at lave linjeskift.</div>';
		echo '<div class="chat-upload">' . wp_nonce_field('attachment-upload', 'attachment_nonce'). '<input id="attachment-upload" name="attachment-upload" type="file" size="30" /></div>';

		return '';
	}

}
$GLOBALS['Inzite_Chat'] = new Inzite_Chat();
?>
