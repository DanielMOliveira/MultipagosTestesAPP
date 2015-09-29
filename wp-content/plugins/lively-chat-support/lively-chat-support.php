<?php

/*

Plugin Name: Lively Chat Support
Plugin URI: http://www.livelychatsupport.com
Description: The best FREE live chat for your WP website (supports images) - forget the hosted chat services.
Version: 1.0.28
Contributors: dallas22ca
Author: Dallas Read
Author URI: http://www.DallasRead.com
Text Domain: lively-chat-support
Donate link: Just purchase an addon!
Tags: free live chat, live chat, live support, online chat, customer service
Requires at least: 3.0.1
Tested up to: 3.9.1
Stable tag: 1.0.28
License: MIT

Copyright (c) 2013 Dallas Read.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

/*
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
*/

define('LIVELYCHATSUPPORT_ROOT', dirname(__FILE__));
define('LIVELYCHATSUPPORT_ADMIN', is_admin());

global $livelychatsupport_version;
global $livelychatsupport_db_version;
global $livelychatsupport_addon_version;

$livelychatsupport_version = "1.0.28";
$livelychatsupport_db_version = 1.11;
$livelychatsupport_addon_version = 1.5;

if (LIVELYCHATSUPPORT_ADMIN == true) {
  require_once LIVELYCHATSUPPORT_ROOT . "/admin/includes/functions.php";
}

require_once LIVELYCHATSUPPORT_ROOT . "/chatbox/includes/functions.php";
require_once LIVELYCHATSUPPORT_ROOT . "/shared/includes/functions.php";

register_activation_hook( __FILE__,                     "LivelyChatSupport_installation" );
register_deactivation_hook( __FILE__,                   "LivelyChatSupport_uninstallation" );

add_action( "admin_menu",                               "register_LivelyChatSupport_admin_menu" );
add_action( "wp_footer",                                "LivelyChatSupport_frontend_footer" );
add_action( "plugins_loaded",                           "LivelyChatSupport_update_db_check" );

add_action( "wp_ajax_poll",                             "LivelyChatSupport_poll" );
add_action( "wp_ajax_add_convo",                        "LivelyChatSupport_add_convo" );
add_action( "wp_ajax_read_convo",                       "LivelyChatSupport_read_convo" );
add_action( "wp_ajax_create_chatbox_message",           "LivelyChatSupport_create_chatbox_message" );
add_action( "wp_ajax_subscribe",                        "LivelyChatSupport_subscribe" );
add_action( "wp_ajax_save_survey",                      "LivelyChatSupport_save_survey" );
add_action( "wp_ajax_cache_support",                    "LivelyChatSupport_cache_support" );
add_action( "wp_ajax_delete_all_convos",                "LivelyChatSupport_delete_all_convos" );
add_action( "wp_ajax_find_visitors",                    "LivelyChatSupport_find_visitors" );
add_action( "wp_ajax_delete_history",                   "LivelyChatSupport_delete_history" );

add_action( "wp_ajax_nopriv_poll",                      "LivelyChatSupport_poll" );
add_action( "wp_ajax_nopriv_subscribe",                 "LivelyChatSupport_subscribe" );
add_action( "wp_ajax_nopriv_create_chatbox_message",    "LivelyChatSupport_create_chatbox_message" );
add_action( "wp_ajax_nopriv_save_survey",               "LivelyChatSupport_save_survey" );
add_action( "wp_ajax_nopriv_cache_support",             "LivelyChatSupport_cache_support" );
add_action( "wp_ajax_nopriv_delete_history",            "LivelyChatSupport_delete_history" );

?>