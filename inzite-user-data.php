<?php
/*
Plugin Name: Inzite User Data
Plugin URI: http://inzite.dk
Description: Adds extra user fields/data
Author: Johnnie Bertelsen
Version: 2.0.0
 */

define('BUNDLE_VERSION', '2.0.0');
define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('MY_PLUGIN_URL', plugin_dir_url(__FILE__));

if (!defined('ABSPATH')) {
    exit;
}

// Enqueue scripts
function lk_enqueue_scripts($hook)
{
    wp_enqueue_script('jquery-validate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js', array('jquery'), '1.16.0');
    wp_enqueue_script('form-validation', MY_PLUGIN_URL . '/javascripts/form-validation.js', array('jquery', 'jquery-validate'), '1.0.0');
}
add_action('wp_enqueue_scripts', 'lk_enqueue_scripts');

//include_once('modules/chat-room/chat-room.php');
include_once('modules/user-data/user-data.php');
include_once('modules/download-files/download-files.php');
include_once('modules/story-line/story-line.php');


