<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Inzite_User_Data {
	public function __construct() {
		if ( is_admin() ) {
			// display fields
			add_action( 'show_user_profile', array( $this, 'inzite_user_data_fields' ) );
			add_action( 'admin_enqueue_scripts',  array( $this, 'inzite_enqueue_scripts') );

			// add fields
			add_action( 'edit_user_profile', array( $this, 'inzite_user_data_fields' ) );
			add_action( 'user_new_form', array( $this, 'inzite_user_data_fields' ) );

			// save / update fields
			add_action( 'user_register', array( $this, 'inzite_user_data_save' ) );
			add_action( 'edit_user_profile_update', array( $this, 'inzite_user_data_save' ) );

			// table columns
			add_filter( 'manage_users_columns' , array( $this, 'inzite_add_custom_columns') );
			add_filter( 'manage_users_custom_column' , array( $this, 'inzite_manage_custom_columns') , 10 , 3 );
		}

		// change author/member url
		add_action( 'init' , array( $this, 'inzite_change_author_permalinks') );

		// enqueue scripts/styles
		add_action( 'wp_enqueue_scripts' , array( $this, 'inzite_enqueue_scripts' ) );

		// add profile, login and logout options to menu
		add_filter( 'wp_nav_menu_items', array( $this, 'add_inzite_menu_items'), 10, 2 );

		// login/logout redirect
		add_filter( 'authenticate', array( $this, 'inzite_authenticate' ), 10, 3 );
		add_filter( 'allow_password_reset', array( $this, 'inzite_allow_password_reset' ), 10, 2 );
		add_action( 'login_redirect', array( $this, 'inzite_login_redirect'), 10, 3 );
		add_action( 'wp_logout', array( $this, 'inzite_logout_redirect') );

		// create separate menu / header
		add_action( 'inzite_profile_menu', array( $this, 'inzite_profile_menu') );
		add_action( 'inzite_profile_header', array( $this, 'inzite_profile_header'), 1, 2 );

		// add check on profile update posts
		add_action( 'wp_loaded', array( $this, 'inzite_profile_update') );

		// action to display profile
		add_action( 'inzite_profile_viewer', array( $this, 'inzite_profile_viewer') );



	}

	function inzite_enqueue_scripts( $post_type ) {
		if ( is_author() || ( is_admin() && ($post_type == 'user-new.php' || $post_type == 'user-edit.php') ) ) {
			wp_enqueue_style( 'inzite-user-styles', plugins_url( 'user-data.css', __FILE__ ) );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'inzite-user-data', plugins_url( 'user-data.js', __FILE__ ) );
		}
	}

	function inzite_change_author_permalinks() {
	    global $wp_rewrite;
	    $wp_rewrite->author_base = 'profile';
	    $wp_rewrite->author_structure = '/' . $wp_rewrite->author_base. '/%author%';
			$wp_rewrite->flush_rules();
	}


	function add_inzite_menu_items ( $items, $args ) {
	  if ($args->theme_location == 'header-menu-right') {
	    if (is_user_logged_in()) {
				$current_user = wp_get_current_user();
				$items .= '<li class="inzite-profile-highlight"><a href="'.site_url( '/profile/' . $current_user->user_nicename . '/' ).'">Din profil</a></li>';
	      $items .= '<li><a href="'.site_url( '/wp-login.php?action=logout' ).'">Log ud</a></li>';
	    }
	    else {
	      $items .= '<li><a href="'.site_url( '/wp-login.php' ).'">Log ind</a></li>';
	    }
	  }
	  return $items;
	}

	function inzite_check_expiration( $user_id ) {
		$user_expire_date = get_user_meta($user_id, 'user_expire_date', true );
		if ( is_date($user_expire_date) ) {
			if (date('d-m-Y', $user_expire_date) < date('d-m-Y')) {
				return true;
			}
		}
		return false;
	}

	function inzite_authenticate( $user, $username, $password ) {
		$checkuser = get_user_by( 'login', $username );
		if ( $checkuser ) {
			$expired = $this->inzite_check_expiration( $checkuser->ID );
			if ( $expired ) {
					// do something here reset password etc...
					return new WP_Error( 'expire_users_expired', sprintf( '<strong>%s</strong> %s', __( 'ERROR:' ), __( 'Your user details have expired.', 'expire-users' ) ) );
			}
		}
		return $user;
	}

	function inzite_allow_password_reset( $allow, $user_ID ) {
		if ( absint( $user_ID ) > 0 ) {
			$expired = $this->inzite_check_expiration( $user_ID );
			if ( $expired ) {
				$allow = new WP_Error( 'expire_users_expired_password_reset', sprintf( '<strong>%s</strong> %s', __( 'ERROR:' ), __( 'Your user details have expired so you are no longer able to reset your password.', 'expire-users' ) ) );
			}
		}
		return $allow;
	}


	function inzite_login_redirect( $redirect_to, $request, $user){
		//is there a user to check?
		global $user;

		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			//check for admins
			//update last logins...
			$last_logins = get_user_meta($user->data->ID,'last_logins',true);
			if ($last_logins) {


				krsort($last_logins);
				$ll = 0;
				foreach ($last_logins as $key => $logins) {
					$ll++;
					if ($ll >= 5) {
						unset($last_logins[$key]);
					}
				}
				ksort($last_logins);

			}
			$last_logins[] = time();

			update_user_meta( $user->data->ID, 'last_logins', $last_logins);

			if ( in_array( 'subscriber', $user->roles ) ) {
				return site_url( '/profile/' . $user->data->user_nicename . '/' );
			} else {
				return $redirect_to;
			}
		} else {
			return $redirect_to;
		}
	}

	function inzite_logout_redirect(){
	  wp_redirect( home_url() );
	  exit();
	}

	function inzite_profile_menu() {
		$current_user = wp_get_current_user();
		if ( $current_user->ID != 0 ) {
		echo '<div class="loggedin-nav">';
			echo '<div class="loggedin-nav-inner">';
				echo '<ul>';
				echo '<li><a href="'.site_url( '/profile/' . $current_user->user_nicename . '/' ).'">Din profil</a></li>';
				echo '<li><a href="'.site_url( '/profile/' . $current_user->user_nicename . '/?chats=show' ).'">Chat grupper';
				$post_unread = $this->inzite_check_unread();
				if ( $post_unread ) {
					echo ' ( ' . sprintf( _n( '%d', '%d', $post_unread, 'inzite-user-data' ), $post_unread ) . ' )';
				}
				echo '</a></li>';
				echo '<li><a href="'.site_url( '/downloads/' ).'">Downloads</a></li>';
				echo '<li><a href="'.site_url( '/profile/' . $current_user->user_nicename . '/?story=show' ).'">Livshistorie</a></li>';
				$user_is_parent = intval(get_user_meta($current_user->ID, 'max_subusers', true ));
				if ($user_is_parent > 0) {
					echo '<li><a href="'.site_url( '/profile/' . $current_user->user_nicename . '/?users=show' ).'">Bruger administration</a></li>';
				}

				echo '</ul>';
			echo '</div>';
		echo '</div>';
		}
	}

	function inzite_profile_header($current_user = '', $user_data = '') {
		if ( empty($user_data) ) {
			$user_data = wp_get_current_user();
			$current_user = wp_get_current_user();
		}
		if ( $current_user->ID != 0 ) {
			echo '<div class="page-header">';
				echo '<div class="page-header-inner">';
				echo '<div class="profile-welcome">';
				if ($current_user->ID == $user_data->data->ID) {
					echo 'Velkommen tilbage';
				} else {
					echo 'Oplysninger for brugeren';
				}
				echo '</div>';
					echo '<h1>';
					echo '<a href="' . site_url( '/profile/' . $user_data->data->user_nicename . '/' ) . '">' . $user_data->data->display_name . '</a>';
					echo '</h1>';
					echo '<a class="profile-edit-button" href="' . site_url( '/profile/' . $user_data->data->user_nicename . '/?edit=show' ) . '">Rediger bruger</a>';

				echo '</div>';
			echo '</div>';
		}
	}

	function inzite_check_unread($post_id = '', $post_name = '', $unread_totals = 0) {
		$current_user = wp_get_current_user();
		if ($post_id && $post_name) {
			$post_last_view = intval(get_post_meta($post_id, 'chat_'.$post_name, true));
			$user_last_view = intval(get_user_meta($current_user->ID, 'chat_'.$post_name, true));
			$unread_totals = intval($post_last_view-$user_last_view);

		} else {
			// total count
			$posts = $this->inzite_get_chatrooms($current_user->ID);
			foreach($posts as $post) {
				//echo '<pre>' . $post->ID . ','.  $post->post_name . ','.  $unread_totals . '</pre>';
				$unread_totals += $this->inzite_check_unread($post->ID, $post->post_name, $unread_totals);
			}

		}
		return $unread_totals;
	}

	function inzite_profile_viewer( ) {
		if ( is_author() ) {

				$user_data = get_queried_object();
				$current_user = wp_get_current_user();
				if ( $current_user->ID != 0 ) {
					// echo '<pre>';
					// print_r( $user_data );
					// echo '</pre>';

					// check if user is admin, self or parent
					$user_parent = intval(get_user_meta($user_data->data->ID, 'parent_userid', true ));
					$user_is_parent = intval(get_user_meta($user_data->data->ID, 'max_subusers', true ));
					if ( in_array( 'administrator', $current_user->roles ) || $current_user->ID == $user_data->data->ID || $current_user->ID == $user_parent ) {
						// check if new data is posted...
						$this->inzite_profile_header( $current_user, $user_data );

						do_action( 'inzite_profile_menu' );

						echo '<div class="content">';
							echo '<div class="content-inner">';
								if ($_GET['story']) {
									$this->inzite_profile_story($current_user);
								} else if ($_GET['chats']) {
									$this->inzite_profile_chats($current_user);
								} else if ($_GET['users'] && $user_is_parent > 0) {
									$this->inzite_profile_users($current_user, $user_is_parent);
								} else if ($_GET['edit']) {
									$this->inzite_profile_form($user_data, $current_user);
								} else if ($_GET['add']) {
									$this->inzite_profile_form(array(), $current_user, $user_is_parent);
								} else {
									$this->inzite_profile_data($user_data);
								}

							echo '</div>';
						echo '</div>';
					} else {
						$this->inzite_no_access();
					}
				} else {
					$this->inzite_no_access();
				}

		}
	}



	function inzite_profile_story($current_user) {
		echo 'Bruger livshistorie her...';
	}

	function inzite_get_chatrooms($user_id) {
		if ( $user_id ) {
			global $wpdb;
			$where =	"Select post_title, ID, post_name FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'inzite-chat' AND {$wpdb->posts}.ID IN " .
			"( SELECT ID FROM $wpdb->posts WHERE ID NOT IN ( SELECT post_id FROM $wpdb->postmeta WHERE {$wpdb->postmeta}.meta_key = 'groups-groups_read_post' ) ";

			if (! user_can( $user_id, 'administrator' ) ) {

				// 1. Get all the capabilities that the user has, including those that are inherited:
				$caps = array();
				if ( $user = new Groups_User( $user_id ) ) {
					$capabilities = $user->capabilities_deep;
					if ( is_array( $capabilities ) ) {
						foreach ( $capabilities as $capability ) {
							$caps[] = "'". $capability . "'";
						}
					}
				}

				if ( count( $caps ) > 0 ) {
					$caps = implode( ',', $caps );
				} else {
					$caps = '\'\'';
				}

				$where .=	"UNION ALL " .
					"SELECT post_id AS ID FROM $wpdb->postmeta WHERE {$wpdb->postmeta}.meta_key = 'groups-groups_read_post' AND {$wpdb->postmeta}.meta_value IN ($caps) ";

			} else {
				// admin can view all
				$where .=	"UNION ALL " .
					"SELECT post_id AS ID FROM $wpdb->postmeta WHERE {$wpdb->postmeta}.meta_key = 'groups-groups_read_post' ";
			}
		}

		$where .= " ) ORDER BY post_title asc";
		return $wpdb->get_results( $where, OBJECT);
	}

	function inzite_profile_chats($current_user) {

		$user_id = $current_user->data->ID;
		$posts = $this->inzite_get_chatrooms($user_id);

		echo '<h2>Chat grupper</h2>';
		echo '<table class="chat_groups">';
		foreach ($posts as $post) {
			echo '<tr>';
				echo '<td><a href="' . get_permalink($post->ID) . '">';
				$post_unread = $this->inzite_check_unread($post->ID, $post->post_name);
				if ($post_unread) {
					echo $post->post_title. ' ( ' . sprintf( _n( '<b>%d</b> ulæst besked', '<b>%d</b> ulæste beskeder', $post_unread, 'inzite-user-data' ), $post_unread ) . ' )';
				} else {
					echo $post->post_title;
				}
				echo '</a></td>';
			echo '</tr>';
		}
		echo '</table>';

		//$posts = $wpdb->get_results( "SELECT  FROM $wpdb->posts INNER JOIN $wpdb->postmeta ON user_id = ID WHERE meta_key = 'parent_userid' and meta_value = $parent_id ", OBJECT );
	}
	function inzite_get_subusers( $parent_id) {
		global $wpdb;
		$subusers = $wpdb->get_results( "SELECT display_name, user_email, user_registered, user_nicename, user_id FROM $wpdb->usermeta INNER JOIN $wpdb->users ON user_id = ID WHERE meta_key = 'parent_userid' and meta_value = $parent_id ", OBJECT );
		return $subusers;
	}
	function inzite_profile_users($current_user, $user_is_parent = 0) {

		$parent_id = $current_user->data->ID;
		$subusers = $this->inzite_get_subusers( $parent_id );

		echo '<h2>Bruger administration ('  . count($subusers) . '/' . $user_is_parent . ')</h2>';
		echo '<table class="user_table">';
		echo '<thead>';
		echo '<tr>';
			echo '<th>Bruger</th>';
			echo '<th>Email</th>';
			echo '<th>Oprettelses dato</th>';
			echo '<th></th>';
			echo '<th></th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach ($subusers as $key => $subuser) {
			echo '<tr>';
			echo '<td>';
			echo '<a href="' . site_url( '/profile/' . $subuser->user_nicename . '/' ) . '">' . $subuser->display_name . '</a>';
			echo '</td>';
			echo '<td>';
			echo '<a href="mailto:' . $subuser->user_email . '">' . $subuser->user_email . '</a>';
			echo '</td>';
			echo '<td>';
			echo $subuser->user_registered;
			echo '</td>';
			echo '<td>';
			echo '<a href="' . site_url( '/profile/' . $subuser->user_nicename . '/?edit=show' ) . '">Ret</a>';
			echo '</td>';
			echo '<td>';
			echo '<a href="?users=delete&userid=' . $subuser->user_id . '">Slet</a>';
			echo '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';

		if (count($subusers) < $user_is_parent) {
			echo '<a href="?add=new" class="button btn-brand">Opret bruger</a>';
		}
	}

	function inzite_profile_data($user_data) {
		echo '<div class="user-profile">';

		echo '<p class="data-user_login">';
			echo '<label>Login</label>';
			echo ''.$user_data->data->user_login.'';
		echo '</p>';

		echo '<p class="data-display_name">';
			echo '<label>Kaldenavn / Vist navn</label>';
			echo ''.$user_data->data->display_name.'';
		echo '</p>';

		echo '<p class="data-first_name">';
			echo '<label>Fornavn</label>';
			echo ''.get_user_meta( $user_data->data->ID, 'first_name', true).'';
		echo '</p>';

		echo '<p class="data-last_name">';
			echo '<label>Efternavn</label>';
			echo ''.get_user_meta( $user_data->data->ID, 'last_name', true).'';
		echo '</p>';

		echo '<p class="data-user_email">';
			echo '<label>Email</label>';
			echo '<a href="mailto:' . $user_data->data->user_email . '">' . $user_data->data->user_email . '</a>';
		echo '</p>';

		echo '</div>';

		echo '<div class="user-stats">';

			$last_logins = get_user_meta($user_data->data->ID, 'last_logins' , true );
			if ($last_logins) {
				krsort($last_logins);
				echo '<div class="last_logins">';
				echo '<label>' .count($last_logins). ' sidste logins</label>';
				foreach ($last_logins as $key => $logins) {
					echo '<div>' . date('d-m-Y', $logins) . ' - kl ' .date('G:i:s', $logins) . '</div>';
				}
				echo '</div>';
			}

		echo '</div>';
	}

	function inzite_profile_form($user_data = array(), $current_user, $user_is_parent = 0) {
		if ($user_data) {
			echo '<h2>Rediger bruger</h2>';

			echo '<form method="post" action="'.  site_url( '/profile/' . $user_data->data->user_nicename . '/' ) .'">';

			echo '<div class="profile-form">';
				echo '<p class="form-user_login">';
					echo '<label for="user_login">Login (kan desværre ikke ændres.)</label>';
					echo '<input class="text-input" name="user_login" type="text" readonly="readonly" value="'.$user_data->data->user_login.'" />';
				echo '</p>';

				echo '<p class="form-display_name">';
					echo '<label for="display_name">Kaldenavn / Vist navn</label>';
					echo '<input class="text-input" name="display_name" type="text" value="'.$user_data->data->display_name.'" />';
				echo '</p>';

				echo '<p class="form-first_name">';
					echo '<label for="first_name">Fornavn</label>';
					echo '<input class="text-input" name="first_name" type="text" value="'.get_user_meta( $user_data->data->ID, 'first_name', true).'" />';
				echo '</p>';

				echo '<p class="form-last_name">';
					echo '<label for="last_name">Efternavn</label>';
					echo '<input class="text-input" name="last_name" type="text" value="'. get_user_meta( $user_data->data->ID, 'last_name', true) .'" />';
				echo '</p>';

				echo '<p class="form-user_email">';
					echo '<label for="user_email">Email adresse</label>';
					echo '<input class="text-input" name="user_email" type="text" value="'.$user_data->data->user_email.'" />';
				echo '</p>';

			echo '</div>';

				echo '<button class="user_passchange button btn-success">Skift adgangskode</button>';
				echo '<div class="form-user_passchange">';

				// echo '<p class="form-user_login">';
				// 	echo '<label for="user_login">Login</label>';
				// 	echo '<input class="text-input" name="user_login" type="text" value="'.$user_data->data->user_login.'" />';
				// echo '</p>';

				echo '<p class="form-user_pass">';
					echo '<label for="user_pass">Ny adgangskode</label>';
					echo '<input class="text-input" name="user_pass" type="password" value="" />';
				echo '</p>';

				echo '<p class="form-user_pass2">';
					echo '<label for="user_pass2">Ny adgangskode (Gentag)</label>';
					echo '<input class="text-input" name="user_pass2" type="password" value="" />';
				echo '</p>';

				echo '</div>';

				echo '<div class="form-user_submit">';
					echo '<input name="user_id" type="hidden" value="'.$user_data->data->ID.'" />';
					echo '<input name="action" type="hidden" value="inzite-update-user" />';

					echo '<input name="user_submit" type="submit" value="Opdater profil" class="button btn-brand" /> <button onclick="history.go(-1);" class="button">Tilbage</button>';
				echo '</div>';

			echo '</form>';
		} else {

			$parent_id = $current_user->data->ID;
			$subusers = $this->inzite_get_subusers( $parent_id );

			if (count($subusers) < $user_is_parent) {

				echo '<h2>Opret bruger</h2>';

				echo '<form method="post" action="'.  site_url( '/profile/' . $current_user->user_nicename . '/?users=show' ) .'">';

				echo '<div class="profile-form">';

					echo '<p class="form-user_login">';
						echo '<label for="user_login">Login</label>';
						echo '<input class="text-input" name="user_login" type="text" value="" />';
					echo '</p>';

					echo '<p class="form-display_name">';
						echo '<label for="display_name">Kaldenavn / Vist navn</label>';
						echo '<input class="text-input" name="display_name" type="text" value="" />';
					echo '</p>';

					echo '<p class="form-first_name">';
						echo '<label for="first_name">Fornavn</label>';
						echo '<input class="text-input" name="first_name" type="text" value="" />';
					echo '</p>';

					echo '<p class="form-last_name">';
						echo '<label for="last_name">Efternavn</label>';
						echo '<input class="text-input" name="last_name" type="text" value="" />';
					echo '</p>';

					echo '<p class="form-user_email">';
						echo '<label for="user_email">Email adresse</label>';
						echo '<input class="text-input" name="user_email" type="text" value="" />';
					echo '</p>';

				echo '</div>';

					echo '<div class="form-user_submit">';
						echo '<input name="action" type="hidden" value="inzite-create-user" />';
						echo '<input name="user_submit" type="submit" value="Opret bruger" class="button btn-brand" /> <button onclick="javascript:history.go(-1);" class="button">Tilbage</button>';
					echo '</div>';

				echo '</form>';

			}

		}
	}

	function inzite_no_access() {
		wp_die( 'Du har ikke rettigheder til at se denne side' );
	}

	function inzite_profile_update() {
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && (($_POST['action'] == 'inzite-update-user' && !empty( $_POST['user_id'] ) ) || $_POST['action'] == 'inzite-create-user')  ) {


			$error = array();
			$current_user = wp_get_current_user();
			$user_email = esc_attr( $_POST['user_email'] );

			if ($_POST['action'] == 'inzite-create-user') {

				$user_name = esc_attr( $_POST['user_login'] );
				$update_user_id = username_exists( $user_name );
				if ( !$update_user_id && is_email($user_email) && email_exists($user_email) == false ) {

					$user_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
					$update_user_id = wp_create_user( $user_name, $user_password, $user_email );
					update_user_meta( $update_user_id, 'parent_userid', $current_user->ID );
					$user_parent = $current_user->ID;
					update_user_meta( $user_id , 'parent_userid' , $user_parent );

					$this->inzite_group_updates( $update_user_id, $user_parent, 0 );


				} else {
					$error[] = __('User already exists.  Password inherited.');
				}

			} else {

				$update_user_id = intval($_POST['user_id']);
				$user_parent = get_user_meta($update_user_id, 'parent_userid', true );

			}


			// echo '<pre>';
			// print_r( $user_data );
			// echo '</pre>';
			//
			// echo '<pre>';
			// print_r( $current_user );
			// echo '</pre>';



			if ( $update_user_id != $update_user_id || ( !in_array( 'administrator', $current_user->roles ) && $current_user->ID != $update_user_id && $current_user->ID != $user_parent  ))  {
				$this->inzite_no_access();
				exit;
			}

	    /* Update user password. */
	    if ( !empty($_POST['user_pass'] ) && !empty( $_POST['user_pass2'] ) ) {
	        if ( $_POST['user_pass'] == $_POST['user_pass2'] )
	            wp_set_password( esc_attr( $_POST['user_pass'] ) , $update_user_id);
	        else
	            $error[] = __('The passwords you entered do not match.  Your password was not updated.', 'profile');
	    }

	    /* Update user information. */
	    if ( !empty( $_POST['user_email'] ) ){
	        if (!is_email(esc_attr( $_POST['user_email'] ))) {
	            $error[] = __('The Email you entered is not valid.  please try again.', 'profile');
	        } elseif(email_exists(esc_attr( $_POST['user_email'] )) && email_exists(esc_attr( $_POST['user_email'] )) != $update_user_id ) {
	            $error[] = __('This email is already used by another user.  try a different one.', 'profile');
	        } else {
	            wp_update_user( array ('ID' => $update_user_id, 'user_email' => esc_attr( $_POST['user_email'] )));
	        }
	    }

			if ( !empty( $_POST['display_name'] ) ) {
        	update_user_meta( $update_user_id, 'nickname', esc_attr( $_POST['display_name'] ) );
					wp_update_user( array( 'ID' => $update_user_id, 'display_name' => esc_attr( $_POST['display_name'] ) ) );
			}

	    if ( !empty( $_POST['first_name'] ) )
	        update_user_meta( $update_user_id, 'first_name', esc_attr( $_POST['first_name'] ) );

	    if ( !empty( $_POST['last_name'] ) )
	        update_user_meta($update_user_id, 'last_name', esc_attr( $_POST['last_name'] ) );


			if ( count($error) == 0 ) {
        //action hook for plugins and extra fields saving
				$user = get_userdata($update_user_id);
				wp_redirect( site_url( '/profile/' . $user->user_nicename . '/' ));
    	} else {
				//wp_redirect( site_url( '/profile/' . $user->user_nicename . '/?edit=1&' . http_build_query($error)  ));
			}

		}

	}

	function inzite_user_data_fields( $user_id ) {
		global $wpdb;

		echo '<h3>Ekstra indstillinger</h3>';
		echo '<div class="postbox" style="padding:0 15px;">';
		echo '<table class="form-table">';

		if ( intval(get_user_meta( $user_id->ID, 'parent_userid', true )) == 0) {
    echo '<tr class="moderator">';
	    echo '<th><label for="max_subusers">Max antal brugere tilknyttet</label></th>';
	    echo '<td><input type="number" name="max_subusers" value="'. intval(get_user_meta( $user_id->ID, 'max_subusers', true )) .'" /></td>';
    echo '</tr>';
		}

		// check if user have subusers
		$subusers = $wpdb->get_results( "SELECT user_nicename FROM $wpdb->usermeta INNER JOIN $wpdb->users ON user_id = ID WHERE meta_key = 'parent_userid' and meta_value = $user_id->ID ", OBJECT );

		if ($subusers) {
			//show subusers
			echo '<tr class="subuser">';
		    echo '<th><label for="parent_userid">Under-brugere</label></th>';
		    echo '<td><ul>';
				foreach ($subusers as $users) :
					echo '<li>'. $users->user_nicename .'</li>';
				endforeach;
				echo '</ul></td>';
	    echo '</tr>';

		} else {

			$user_list = $wpdb->get_results(
			"SELECT user_nicename, ID FROM $wpdb->usermeta as um INNER JOIN $wpdb->users ON um.user_id = ID
			WHERE um.meta_key = 'max_subusers' AND um.meta_value > 0
			AND um.meta_value > (SELECT COUNT(*) as existing_count FROM $wpdb->usermeta as um2 WHERE um2.user_id <> $user_id->ID AND um2.meta_key = 'parent_userid' and um2.meta_value = um.user_id)"
			, OBJECT );

			//intval(get_user_meta( $user->ID, 'parent_userid', true ))
			echo '<tr class="subuser">';
		    echo '<th><label for="parent_userid">Hoved bruger</label></th>';
		    echo '<td><select name="parent_userid">';
					echo '<option value="0">Ingen</option>';
					foreach ($user_list as $users) :
						echo '<option value="'. $users->ID .'"';
						if ( intval($users->ID) == intval(get_user_meta( $user_id->ID, 'parent_userid', true )) ) :
							echo ' selected';
						endif;
						echo '>'. $users->user_nicename .'</option>';

					endforeach;
				echo '</td>';
	    echo '</tr>';

		}

		if ($user_id == 'add-new-user') {
			$expire_timestamp = date('d-m-Y', strtotime('+1 year'));
		} else {
			$expire_timestamp = get_user_meta( $user_id->ID, 'user_expire_date', true );
		}

		echo '<tr class="subuser">';
			echo '<th><label for="parent_userid">Udløbsdato</label></th>';
			echo '<td>';

				echo '<input class="widefat" id="user_expire_date" type="text" name="user_expire_date" placeholder="Udløbsdato" value="'.$expire_timestamp.'" />';

			echo '</td>';
		echo '</tr>';

		echo '</table> ';
		echo '</div> ';
	}

	function inzite_user_data_save( $user_id ) {

		if ( current_user_can('edit_user', $user_id) ) {

			$parent_userid = intval( sanitize_text_field( $_POST['parent_userid'] ) );
			$max_subusers =  intval( sanitize_text_field( $_POST['max_subusers'] ) );
			$user_expire_date = sanitize_text_field( $_POST['user_expire_date'] );
      update_user_meta( $user_id , 'max_subusers' , $max_subusers );
			update_user_meta( $user_id , 'parent_userid' , $parent_userid );
			update_user_meta( $user_id , 'user_expire_date' , $user_expire_date );

			$this->inzite_group_updates( $user_id, $parent_userid, $max_subusers );

		}
	}

	function inzite_group_updates( $user_id, $parent_userid = 0, $max_subusers = 0 ) {
		global $wpdb;
		if ($this->groups_is_active()) {
			 // check if there is a group named the same as main users / check if groups plugin is installed...
			 $groups_table = $wpdb->prefix . 'groups_group';
			 $groups_user_table = $wpdb->prefix . 'groups_user_group';
			 $groups_group_capability_table = $wpdb->prefix . 'groups_group_capability';
			 $groups_capability_table = $wpdb->prefix . 'groups_capability';
			 $parent_group_id = 0;

			 if ( $parent_userid > 0 ) {
				// find parent user-nicename and add same group to this user..
				$parent = get_userdata($parent_userid);

				$parent_group_id = $wpdb->get_var( "SELECT MAX(gt.group_id) FROM $groups_table as gt INNER JOIN $groups_user_table as gu ON gu.group_id = gt.group_id WHERE gt.name = '$parent->user_nicename' and gu.user_id = $parent_userid LIMIT 1" );
				if ($parent_group_id) {
					$wpdb->insert( $groups_user_table , array('user_id' => $user_id, 'group_id' => $parent_group_id) );
				}

			 }

			 if ( $max_subusers > 0 || $parent_userid > 0 ) {

				 $user = get_userdata($user_id);
				 $group_id = $wpdb->get_var( "SELECT MAX(gt.group_id) FROM $groups_table as gt INNER JOIN $groups_user_table as gu ON gu.group_id = gt.group_id WHERE gt.name = '$user->user_nicename' and gu.user_id = $user_id LIMIT 1" );

				 if (!$group_id) {

						$wpdb->insert( $groups_table , array('creator_id' => $user_id, 'datetime' => date( 'Y-m-d H:i:s', time() ), 'name' => $user->user_nicename, 'description' => '') );
						$group_id = $wpdb->insert_id;

						$wpdb->insert( $groups_user_table , array('user_id' => $user_id, 'group_id' => $group_id) );
						if ( $parent_userid > 0 ) {
							$wpdb->insert( $groups_user_table , array('user_id' => $parent_userid, 'group_id' => $group_id) );
						}

						$wpdb->insert( $groups_capability_table , array('capability' => $user->user_nicename) );
						$capability_id = $wpdb->insert_id;

						$wpdb->insert( $groups_group_capability_table, array('group_id' => $group_id, 'capability_id' => $capability_id) );

						$groups_options = get_option('groups_options');
						if ($groups_options) {
							if (!in_array($user->user_nicename, $groups_options['general']['read_post_capabilities']) ) {
								$groups_options['general']['read_post_capabilities'][] = $user->user_nicename;
								update_option('groups_options', $groups_options);
							}
						}

						$new_post = array(
							'post_title' => $user->user_nicename,
							'post_name' => $user->user_nicename . '-chat',
							'post_content' => '',
							'post_parent' => $parent_group_id,
							'post_type' => 'inzite-chat',
							'post_status' => 'publish'
						);
						$post_id = wp_insert_post($new_post);
						update_post_meta($post_id, 'groups-groups_read_post', $user->user_nicename);
					}
			 }

		}
	}

	private static function groups_is_active() {
		$active_plugins = get_option( 'active_plugins', array() );
		return in_array( 'groups/groups.php', $active_plugins );
	}

	//public function inzite_user_custom_columns() {

	function inzite_add_custom_columns($columns) {
	  $columns['parent_userid'] = 'Bruger tilknytning';
	 	return $columns;
	}

	function inzite_manage_custom_columns($empty = '', $col, $id) {
		switch($col) {
        case 'parent_userid':
					$parent_user = get_user_meta( $id , 'parent_userid' , intval( $_POST['parent_userid'] ) );
					$is_parent = get_user_meta( $id , 'max_subusers' , intval( $_POST['max_subusers'] ) );

					if ($parent_user[0]) {

						$user = get_userdata($parent_user[0]);
          	return '<div style="font-size:10px; padding:2px 5px; background:#fff; border:1px solid #eee; border-radius:2px;">Tilknyttet brugeren: <br/><a href="user-edit.php?user_id='.$user->ID.'">' . $user->user_login . '</a></div>';

					} else if ($is_parent[0]) {

						global $wpdb;
						$subusers = $wpdb->get_results( "SELECT user_login, ID FROM $wpdb->usermeta INNER JOIN $wpdb->users ON user_id = ID WHERE meta_key = 'parent_userid' and meta_value = $id ", OBJECT );
						$subuser_list = '';
						foreach ($subusers as $users) :
							$subuser_list .= '<div> - <a href="user-edit.php?user_id='.$users->ID.'">'. $users->user_login .'</a></div>';
						endforeach;
						return '<div style="font-size:10px; padding:2px 5px; background:#fff; border:1px solid #eee; border-radius:2px;">Tilknyttede brugere:' . $subuser_list . '</div>';

					}
        break;
	  }

	}

}
$GLOBALS['Inzite_User_Data'] = new Inzite_User_Data();
?>
