<?php
/**
* Plugin Name: Easy Digital Download License Activator Notifier
* Plugin URI: https://github.com/tjthouhid/edd-license-activator-notifier
* Description: This is a plugin for Easy Digital Download Software License Activator Notification Show on Dashboard as widget.
* Version: 1.0.3
* Author: Tj Thouhid
* Author URI: https://www.tjthouhid.com
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */


/**
 *  Creating Database on active
 */

global $jal_db_version;
$jal_db_version = '1.1';

function edd_notification_tbl_jal_install() {

	global $wpdb;
	$installed_ver = get_option( "edd_notification_plugin_db_version" );

	if ( $installed_ver != $jal_db_version ) {
		
		make_edd_licence_db();
		update_option( 'edd_notification_plugin_db_version', $jal_db_version );
	}else{
		
		make_edd_licence_db();
		add_option( 'edd_notification_plugin_db_version', $jal_db_version );

	}
}
register_activation_hook( __FILE__, 'edd_notification_tbl_jal_install' );

function make_edd_licence_db(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'edd_notification_tbl';
	
	$charset_collate = $wpdb->get_charset_collate();

 	$sql = "CREATE TABLE $table_name (
		n_id int(25) NOT NULL AUTO_INCREMENT,
		license_id int(25) NOT NULL,
		customer_email varchar(100) NOT NULL,
		customer_id int(25) NOT NULL,
		site_url varchar(255) NOT NULL,
		type enum('1','0') NOT NULL DEFAULT '1',
		PRIMARY KEY  (n_id)
	) $charset_collate;";
	

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
/**
 * Delete Database On Deactive
 */
register_deactivation_hook( __FILE__, 'edd_notification_plugin_remove_database' );
function edd_notification_plugin_remove_database() {
     global $wpdb;
     $table_name = $wpdb->prefix . 'edd_notification_tbl';
     $sql = "DROP TABLE IF EXISTS $table_name";
     $wpdb->query($sql);
     delete_option("edd_notification_plugin_db_version");
}   


/**
 * Processes the Add Site button
 *
 * @since       2.4
 * @return      void
*/
function new_edd_sl_process_add_site() {

	if ( ! wp_verify_nonce( $_POST['edd_add_site_nonce'], 'edd_add_site_nonce' ) ) {
		return;
	}

	if ( ! empty( $_POST['license_id'] ) && empty( $_POST['license'] ) ) {
		// In 3.5, we switched from checking for license_id to just license. Fallback check for backwards compatibility
		$_POST['license'] = $_POST['license_id'];
	}

	$license_id  = absint( $_POST['license'] );
	$edd_software_licensing=edd_software_licensing();
	$license     = $edd_software_licensing->get_license( $license_id );
	$emails=edd_software_licensing()->get_emails_for_license( $license_id );
	$payment_id  = $edd_software_licensing->get_payment_id( $license_id );
	$payment     = new EDD_Payment( $payment_id );
	 
	$customer_id = $payment->customer_id;
	$email=$emails[0];

	if ( $license_id !== $license->ID ) {
		return;
	}

	if ( ( is_admin() && ! current_user_can( 'edit_shop_payments'  ) ) || ( ! is_admin() && $license->user_id != get_current_user_id() ) ) {
		return;
	}

	$site_url = sanitize_text_field( $_POST['site_url'] );

	if ( $license->is_at_limit() && ! current_user_can( 'edit_shop_payments' ) ) {
		// The license is at its activation limit so stop and show an error
		wp_safe_redirect( add_query_arg( 'edd_sl_error', 'at_limit' ) ); exit;
	}
	if ( $license->add_site( $site_url ) ) {

		$license->status = 'active';
		global $wpdb;
		$table_notification=$wpdb->prefix . 'edd_notification_tbl';

		  $wpdb->insert( 
		    $table_notification, 
		    array( 
		        'license_id' => $license_id, 
		        'customer_email' => $email, 
		        'site_url' => $site_url, 
		        'customer_id' => $customer_id 
		    ), 
		    array( 
		        '%d', 
		        '%s', 
		        '%s', 
		        '%d' 
		    ) 
		 );

		if ( is_admin() ) {
			$redirect = admin_url( 'edit.php?post_type=download&page=edd-licenses&view=overview&license=' . $license->ID );
		} else {
			$redirect = remove_query_arg( array( 'edd_action', 'site_url', 'edd_sl_error', '_wpnonce' ) );
		}

	} else {
		$redirect = add_query_arg( 'edd_sl_error', 'error_adding_site' );
	}

	wp_safe_redirect( $redirect ); exit;
}

remove_action('edd_insert_site', 'edd_sl_process_add_site');
add_action( 'edd_insert_site', 'new_edd_sl_process_add_site' );

/**
 * Processes the Deactivate Site button
 *
 * @since       2.4
 * @return      void
*/
function new_edd_sl_process_deactivate_site() {
	if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'edd_deactivate_site_nonce' ) ) {
		return;
	}

	$license_id = absint( $_GET['license'] );
	$edd_software_licensing=edd_software_licensing();
	$license    = $edd_software_licensing->get_license( $license_id );

	$emails=edd_software_licensing()->get_emails_for_license( $license_id );
	$payment_id  = $edd_software_licensing->get_payment_id( $license_id );
	$payment     = new EDD_Payment( $payment_id );
	 
	$customer_id = $payment->customer_id;
	$email=$emails[0];
	if ( $license_id !== $license->ID ) {
		return;
	}

	if ( ( is_admin() && ! current_user_can( 'edit_shop_payments' ) ) || ( ! is_admin() && $license->user_id != get_current_user_id() ) ) {
		return;
	}

	$site_url = urldecode( $_GET['site_url'] );
	$license->remove_site( $site_url );
	global $wpdb;
	$table_notification=$wpdb->prefix . 'edd_notification_tbl';

	  $wpdb->insert( 
	    $table_notification, 
	    array( 
	        'license_id' => $license_id, 
	        'customer_email' => $email, 
	        'site_url' => $site_url, 
	        'customer_id' => $customer_id,
	        'type' => '0'
	    ), 
	    array( 
	        '%d', 
	        '%s', 
	        '%s', 
	        '%d',
	        '%s' 
	    ) 
	 );

	wp_safe_redirect( remove_query_arg( array( 'edd_action', 'site_url', 'edd_sl_error', '_wpnonce' ) ) ); exit;
}

remove_action('edd_deactivate_site', 'edd_sl_process_deactivate_site');
add_action( 'edd_deactivate_site', 'new_edd_sl_process_deactivate_site' );



add_action('wp_dashboard_setup', 'edd_license_activated_notifier_dashboard_widgets');

/**
 * Processes Adding Dashboard Widget
 *
 * @since       
 * @return      void
*/  
function edd_license_activated_notifier_dashboard_widgets() {
global $wp_meta_boxes;
if ( current_user_can( apply_filters( 'edd_dashboard_stats_cap', 'view_shop_reports' ) ) ) { 
	wp_add_dashboard_widget('edd_license_activated_notifier_help_widget', 'EDD Licenses Notification', 'edd_license_activated_notifier_dashboard_help');
	}
}
 
function edd_license_activated_notifier_dashboard_help() {
	global $wpdb;
	$table_notification=$wpdb->prefix . 'edd_notification_tbl';
	$sql="SELECT * FROM ".$table_notification." ORDER BY n_id DESC";
	$query=$wpdb->get_results($sql);
	$total_data=$wpdb->num_rows;
	include "templates/widget.php";
}
add_action( 'admin_menu', 'my_admin_plugin' );

function my_admin_plugin() {
    wp_register_script( 'edd_license_activated_notifier_script', plugins_url('/script.js', __FILE__), array('jquery'));
    wp_enqueue_script( 'edd_license_activated_notifier_script' );
	wp_localize_script( 'edd_license_activated_notifier_script', 'ajax_object', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));
}



add_action( 'wp_ajax_delete_edd_notification_nid', 'delete_edd_notification_nid' );
add_action( 'wp_ajax_nopriv_delete_edd_notification_nid', 'delete_edd_notification_nid' );
function delete_edd_notification_nid(){
	$n_id=$_REQUEST['id'];
	global $wpdb;
	$table_notification=$wpdb->prefix . 'edd_notification_tbl';
	if($wpdb->delete( $table_notification, array( 'n_id' => $n_id ) )){
		echo true;
		exit;
	}else{
		echo false;
		exit;	
	}
	
	

}

?>