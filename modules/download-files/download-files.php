<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Inzite_Download_Files {
	public function __construct() {
    // init
    add_shortcode( 'downloads', array( $this, 'inzite_profile_downloads') );
		add_action( 'init', array( $this, 'post_downloads'), 0 );
		add_action( 'init', array( $this, 'cat_downloads'), 0 );

		// enqueue scripts/styles
		add_action( 'wp_enqueue_scripts' , array( $this, 'downloads_enqueue_scripts' ) );
	}

	function downloads_enqueue_scripts( $post_type ) {
		wp_enqueue_style( 'inzite-download-files', plugins_url( 'download-files.css', __FILE__ ) );
		wp_enqueue_style( 'dashicons' );
	}

  // Register Custom Downloads
function post_downloads() {

	$labels = array(
		'name'                  => _x( 'Downloads', 'Post Type General Name', 'secondthought' ),
		'singular_name'         => _x( 'Download', 'Post Type Singular Name', 'secondthought' ),
		'menu_name'             => __( 'Downloads', 'secondthought' ),
		'name_admin_bar'        => __( 'Downloads', 'secondthought' ),
		'archives'              => __( 'Download arkiv', 'secondthought' ),
		'parent_item_colon'     => __( 'Download forældre:', 'secondthought' ),
		'all_items'             => __( 'Alle downloads', 'secondthought' ),
		'add_new_item'          => __( 'Tilføj ny download', 'secondthought' ),
		'add_new'               => __( 'Tilføj ny', 'secondthought' ),
		'new_item'              => __( 'Ny download', 'secondthought' ),
		'edit_item'             => __( 'Ret download', 'secondthought' ),
		'update_item'           => __( 'Opdater download', 'secondthought' ),
		'view_item'             => __( 'Vis download', 'secondthought' ),
		'search_items'          => __( 'Søg downloads', 'secondthought' ),
		'not_found'             => __( 'Not found', 'secondthought' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'secondthought' ),
		'featured_image'        => __( 'Featured Image', 'secondthought' ),
		'set_featured_image'    => __( 'Set featured image', 'secondthought' ),
		'remove_featured_image' => __( 'Remove featured image', 'secondthought' ),
		'use_featured_image'    => __( 'Use as featured image', 'secondthought' ),
		'insert_into_item'      => __( 'Insert into item', 'secondthought' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'secondthought' ),
		'items_list'            => __( 'Items list', 'secondthought' ),
		'items_list_navigation' => __( 'Items list navigation', 'secondthought' ),
		'filter_items_list'     => __( 'Filter items list', 'secondthought' ),
	);
	$args = array(
		'label'                 => __( 'Download', 'secondthought' ),
		'description'           => __( 'Downloads Description', 'secondthought' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'page-attributes', ),
		'taxonomies'            => array( 'cat_downloads' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'menu_icon'             => 'dashicons-download',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'post_downloads', $args );

}
// Register Custom Taxonomy
function cat_downloads() {

	$labels = array(
		'name'                       => _x( 'Download kategorier', 'Taxonomy General Name', 'secondthought' ),
		'singular_name'              => _x( 'Download kategori', 'Taxonomy Singular Name', 'secondthought' ),
		'menu_name'                  => __( 'Download kategori', 'secondthought' ),
		'all_items'                  => __( 'Alle kategorier', 'secondthought' ),
		'parent_item'                => __( 'Parent Item', 'secondthought' ),
		'parent_item_colon'          => __( 'Parent Item:', 'secondthought' ),
		'new_item_name'              => __( 'Ny kategori', 'secondthought' ),
		'add_new_item'               => __( 'Tilføj ny kategori', 'secondthought' ),
		'edit_item'                  => __( 'Ret kategori', 'secondthought' ),
		'update_item'                => __( 'Opdater kategori', 'secondthought' ),
		'view_item'                  => __( 'Vis kategori', 'secondthought' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'secondthought' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'secondthought' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'secondthought' ),
		'popular_items'              => __( 'Popular Items', 'secondthought' ),
		'search_items'               => __( 'Search Items', 'secondthought' ),
		'not_found'                  => __( 'Not Found', 'secondthought' ),
		'no_terms'                   => __( 'No items', 'secondthought' ),
		'items_list'                 => __( 'Items list', 'secondthought' ),
		'items_list_navigation'      => __( 'Items list navigation', 'secondthought' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'cat_downloads', array( 'post_downloads' ), $args );

}



	function inzite_get_downloads($user_id, $category_id) {
		if ( $user_id ) {
			global $wpdb;
			$where =	"Select post_title, ID, post_name, post_content FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post_downloads'";

      if ( $category_id ) {
        $where .=	" AND EXISTS( SELECT object_id FROM $wpdb->term_relationships WHERE {$wpdb->term_relationships}.object_id = {$wpdb->posts}.ID AND {$wpdb->term_relationships}.term_taxonomy_id = '$category_id' ) ";
      }

      $where .=	" AND {$wpdb->posts}.ID IN " .
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
    //echo $where;
		return $wpdb->get_results( $where, OBJECT);
	}

	function inzite_profile_downloads() {


    $current_user = wp_get_current_user();
		$user_id = $current_user->ID;
    $downloads = '';
    $category_id = 0;
    if ( $current_user->ID != 0 ) {
      $cat_args = array(
	     'hide_empty'         => 1
      );
      $categories = get_terms( 'cat_downloads', $cat_args );
      foreach ($categories as $key => $category) {
        $category_id = $category->term_id;
        $category_name = $category->name;
    		$posts = $this->inzite_get_downloads($user_id, $category_id);
        if ($posts) {
          $downloads .= '<h4>' . $category_name . '</h4>';
      		$downloads .= '<table class="download_groups">';
      		foreach ($posts as $post) {
      			$downloads .= '<tr>';      			
      				if (get_field('youtube', $post->ID)) {
	      				$downloads .= '<td><a href="' . get_field('youtube', $post->ID) . '" class="button btn-brand" target="_blank"><span class="dashicons dashicons-video-alt3"></span> ';
	      			} else {
	      				$downloads .= '<td><a href="' . get_field('file', $post->ID) . '" class="button btn-brand"><span class="dashicons dashicons-download"></span> ';
      				}
      				$downloads .= $post->post_title;
      				$downloads .= '</a></td>';
              $downloads .= '<td>';
      				$downloads .= $post->post_content;
      				$downloads .= '</td>';
      			$downloads .= '</tr>';
      		}
      		$downloads .= '</table>';
        }
      }
    }

    return $downloads;

	}
	function inzite_no_access() {
		wp_die( 'Du har ikke rettigheder til at se denne side' );
	}

}
$GLOBALS['Inzite_Download_Files'] = new Inzite_Download_Files();
?>
