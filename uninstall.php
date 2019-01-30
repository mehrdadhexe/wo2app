<?php
/*
 * Uninstall plugin
 */
if (!defined( 'ABSPATH' )) exit;
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();
//	global $wpdb;
//	$table_names = array( 'woo2app_slider', 'woo2app_setting', 'woo2app_mainpage' , 'woo2app_menu' );
//	if( sizeof( $table_names ) > 0 ) {
//		foreach( $table_names as $table_name ) {
//			$table = $wpdb->prefix . $table_name;
//			$wpdb->query( "DROP TABLE IF EXISTS $table" );
//		}
//	}
		delete_option("email_WOO2APP");
		delete_option("password_WOO2APP");
		delete_option("appid_WOO2APP");
		delete_option("exp_time_WOO2APP");
		delete_option("last_android_apk_WOO2APP");
		delete_option("last_android_ver_number_WOO2APP");
		delete_option("last_android_ver_name_WOO2APP");
	