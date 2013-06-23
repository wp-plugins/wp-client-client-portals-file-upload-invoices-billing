<?php
// If uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
   exit();

global $wpdb;


/*
* Delete all tables
*/
//tables name
$tables = array(
    'wpc_client_clients_page',
    'wpc_client_login_redirects',
    'wpc_client_groups',
    'wpc_client_group_clients'

);

//remove all tables
foreach( $tables as $key ) {
    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}{$key}'" ) == "{$wpdb->prefix}{$key}" ) {
        $wpdb->query( "DROP TABLE {$wpdb->prefix}{$key}" );
    }
}



/*
* Delete all options
*/
delete_option( 'wpc_create_client' );
delete_option( 'wpc_link_text' );
delete_option( 'wpc_login_alerts' );
delete_option( 'wpc_graphic' );
delete_option( 'clients_page' );
delete_option( 'wpc_settings' );
delete_option( 'client_com' );
delete_option( 'custom_login_options' );
delete_option( 'sender_email' );
delete_option( 'sender_name' );
delete_option( 'new_email_client_template' );
delete_option( 'wpclients_theme' );
delete_option( 'wp-password-generator-opts' );
delete_option( 'wpc_disable_jquery' );



/*
* Delete all plugin users
*/
$clients_id = get_users( array( 'role' => 'wpc_client', 'fields' => 'ID', ) );
if ( is_array( $clients_id ) && 0 < count( $clients_id ) )
    foreach( $clients_id as $user_id )
        wp_delete_user( $user_id );


/*
* Remove all plugin roles
*/
global $wp_roles;
//remore roles
$wp_roles->remove_role( "wpc_client" );

/*
* Remove all hub pages
*/
$args = array(
    'numberposts'   => -1,
    'post_type'     => 'hubpage',
);
$hub_pages = get_posts( $args );
if ( is_array( $hub_pages ) && 0 < count( $hub_pages ) ) {
    foreach( $hub_pages as $hub_page )
        wp_delete_post( $hub_page->ID );
}


/*
* Remove all clients pages
*/
$args = array(
    'numberposts' => -1,
    'post_type' => 'clientspage',
);
$clint_pages = get_posts( $args );
if ( is_array( $clint_pages ) && 0 < count( $clint_pages ) ) {
    foreach( $clint_pages as $clint_page )
        wp_delete_post( $clint_page->ID );
}



/*
* Remove all plugin pages
*/
$args = array(
    'hierarchical'  => 0,
    'meta_key'      => 'wpc_client_page',
    'post_type'     => 'page',
    'post_status'   => 'publish,trash,pending,draft,auto-draft,future,private,inherit',
);
$wpc_client_pages = get_pages( $args );
if ( is_array( $wpc_client_pages ) && 0 < count( $wpc_client_pages ) ) {
    foreach( $wpc_client_pages as $wpc_client_page )
        wp_delete_post( $wpc_client_page->ID, true );
}


?>