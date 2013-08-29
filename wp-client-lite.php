<?php
/*
Plugin Name: WP-Client Lite :: Client Portals, Secure File Exchange, Messaging & Invoicing
Plugin URI: http://www.WP-Client.com
Description:  WP-Client WordPress Client Portal is a Client Management Plugin that gives you the ultimate in flexibility.  Integrate powerful client management and relations features into your current site.<a href="http://WP-Client.com">Visit Plugin Website</a>
Author: WP-Client.com
Version: 0.0.6
Author URI: http://www.WP-Client.com
*/


//current plugin version
define( 'WPC_CLIENT_VER', '0.0.6' );
define('WP_PASSWORD_GENERATOR_VERSION_WPCLIENT', '2.2');

// The text domain for strings localization
define( 'WPC_CLIENT_TEXT_DOMAIN', 'wp-client' );

function wpc_curl_download( $url ) {

    // is cURL installed yet?
    if( !function_exists( 'curl_init' ) )
        die( 'Sorry cURL is not installed!' );

    // OK cool - then let's create a new cURL resource handle
    $ch = curl_init();

    // Now set some options (most are optional)

    // Set URL to download
    curl_setopt( $ch, CURLOPT_URL, $url );
    // Set a referer
    curl_setopt( $ch, CURLOPT_REFERER, $_SERVER['SERVER_NAME'] );
    // User agent
    curl_setopt( $ch, CURLOPT_USERAGENT, 'MozillaXYZ/1.0' );
    // Include header in result? (0 = yes, 1 = no)
    curl_setopt( $ch, CURLOPT_HEADER, 0 );
    // Should cURL return or print out the data? (true = return, false = print)
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    // Timeout in seconds
    curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );

    // Download the given URL, and return output
    $output = curl_exec( $ch );

    // Close the cURL resource, and free system resources
    curl_close( $ch );

    return $output;
}


function wpc_format_bytes($a_bytes) {

    if ($a_bytes < 1024) {
        return $a_bytes .' B';
    } elseif ($a_bytes < 1048576) {
        return round($a_bytes / 1024, 2) .' KB';
    } elseif ($a_bytes < 1073741824) {
        return round($a_bytes / 1048576, 2) . ' MB';
    } elseif ($a_bytes < 1099511627776) {
        return round($a_bytes / 1073741824, 2) . ' GB';
    } elseif ($a_bytes < 1125899906842624) {
        return round($a_bytes / 1099511627776, 2) .' TB';
    } elseif ($a_bytes < 1152921504606846976) {
        return round($a_bytes / 1125899906842624, 2) .' PB';
    } elseif ($a_bytes < 1180591620717411303424) {
        return round($a_bytes / 1152921504606846976, 2) .' EB';
    } elseif ($a_bytes < 1208925819614629174706176) {
        return round($a_bytes / 1180591620717411303424, 2) .' ZB';
    } else {
        return round($a_bytes / 1208925819614629174706176, 2) .' YB';
    }
}


if(!class_exists("wpc_client")) {

    class wpc_client {

        var $plugin_dir;
        var $plugin_url;
        var $plugin;
        var $wpc_roles;
        var $advertising_message;
        var $shortcode_message;
        var $screenshots;
        var $screenshots_path;
        var $hub_template;
        var $client_template;

        function wpc_client() {
            __construct();
        }


        /**
        * PHP 5 constructor
        **/
        function __construct() {
            //setup proper directories
            if ( is_multisite() && defined( 'WPMU_PLUGIN_URL' ) && defined( 'WPMU_PLUGIN_DIR' ) && file_exists( WPMU_PLUGIN_DIR . '/wp-client-lite.php' ) ) {
                $this->plugin_dir = WPMU_PLUGIN_DIR . '/wp-client-client-portals-file-upload-invoices-billing/';
                $this->plugin_url = WPMU_PLUGIN_URL . '/wp-client-client-portals-file-upload-invoices-billing/';
            } else if ( defined( 'WP_PLUGIN_URL' ) && defined( 'WP_PLUGIN_DIR' ) && file_exists( WP_PLUGIN_DIR . '/wp-client-client-portals-file-upload-invoices-billing/wp-client-lite.php' ) ) {
                $this->plugin_dir = WP_PLUGIN_DIR . '/wp-client-client-portals-file-upload-invoices-billing/';
                $this->plugin_url = WP_PLUGIN_URL . '/wp-client-client-portals-file-upload-invoices-billing/';
            } else if ( defined('WP_PLUGIN_URL' ) && defined( 'WP_PLUGIN_DIR' ) && file_exists( WP_PLUGIN_DIR . '/wp-client-lite.php' ) ) {
                $this->plugin_dir = WP_PLUGIN_DIR;
                $this->plugin_url = WP_PLUGIN_URL;
            }

            //set plugin roles
            $this->wpc_roles = array(
                'wpc_client',
                'wpc_client_staff',
                'wpc_manager'
            );

            $this->advertising_message = 'Please get Pro version for get more features like these:';

            $this->shortcode_message = 'This feature is available in Pro version.';

            $this->screenshots = array(
                'wpclients' => array(
                    'approve' => array(
                        'WPC-ApproveClients-1.jpg'
                    ),
                    'convert' => array(
                        'convert_01.jpg',
                        'convert_02.jpg',
                        'convert_03.jpg',
                        'convert_04.jpg'
                    ),
                    'staff' => array(
                        'staff_01.jpg'
                    ),
                    'staff_approve' => array(
                        'WPC-ClientStaffApprove-1.jpg'
                    ),
                    'staff_add' => array(
                        'staff_add_01.jpg',
                        'staff_add_02.jpg'
                    ),
                    'custom_fields' => array(
                        'custom_field_01.jpg',
                        'custom_field_02.jpg',
                        'custom_field_03.jpg',
                        'custom_field_04.jpg',
                        'custom_field_05.jpg'
                    )
                ),
                'wpclients_managers' => array(
                    'general' => array(
                        'WPC-ManagerTable-1.jpg'
                    ),
                    'add' => array(
                        'WPC-AddManager-1.jpg'
                    )
                ),
                'wpclients_files' => array(
                    'general' => array(
                        'WPC-FileTable-1.jpg',
                        'WPC-FileUploader-1.jpg',
                        'WPC-AssignfromFTP-1.jpg',
                        'WPC-AddExternalFile-1.jpg'
                    ),
                    'cat' => array(
                        'files_cat_01.jpg'
                    )
                ),
                'wpclients_templates' => array(
                    'general' => array(
                        'templates_hub_01.jpg'
                    ),
                    'portal' => array(
                        'templates_portal_page_01.jpg'
                    ),
                    'email' => array(
                        'templates_email_01.jpg',
                        'templates_email_02.jpg',
                        'templates_email_03.jpg'
                    ),
                    'shortcode' => array(
                        'templates_shortcode_01.jpg',
                        'templates_shortcode_02.jpg'
                    )
                ),
                'wpclients_messages' => array(
                    'WPC-Messaging1.jpg'
                ),
                'wpclients_settings' => array(
                    'capabilities' => array(
                        'settings_capabilities_01.jpg'
                    )
                )

            );
            $this->screenshots_path = $this->plugin_url . 'images/screenshots/';


            $this->set_plugin_data();


            add_action( 'admin_head', array( &$this, 'style_for_logo' ) );

            add_filter( 'admin_notices', array( &$this, 'admin_notices' ) ) ;

            //login redirect
            add_action( 'login_redirect', array( &$this, 'login_redirect_rules' ), 10, 3 );
            add_action( 'wp_logout', array( &$this, 'logout_redirect_rules' ), 10 );

            //change template
            add_filter( 'page_template', array( &$this, 'get_page_template' ) ) ;

            //add uninstall link
            if ( !has_filter( 'plugin_action_links_wp-client-client-portals-file-upload-invoices-billing/wp-client-lite.php', 'wpc_client_more_action_links' ) ) {
                add_filter( 'plugin_action_links_wp-client-client-portals-file-upload-invoices-billing/wp-client-lite.php', array( &$this, 'add_action_links' ) );
            }

            add_action( 'plugins_loaded', array( &$this, 'load_textdomain' ) );

            add_action( 'admin_menu', array( &$this, 'adminmenu' ) );
            add_action( 'admin_init', array( &$this, 'request_action' ) );
            add_action( 'init', array( &$this, 'parent_page_func' ) );
            add_action( 'init', array( &$this, 'client_login_from_') );
            add_action( 'wp_login', array( &$this, 'alert_login_successful') );
            add_action( 'wp_login_failed', array( &$this, 'alert_login_failed') );

            // run wp_password_generator_load() during admin_print_scripts
            add_action( 'admin_print_scripts', array( &$this, 'wp_password_generator_load' ) );

            add_action( 'wp_ajax_generate_password', array( &$this, 'wp_password_generator_generate' ) );

            //ajax actions
            add_action( 'wp_ajax_wpc_view_client', array( &$this, 'ajax_view_client' ) );
            add_action( 'wp_ajax_wpc_get_client_internal_notes', array( &$this, 'ajax_get_client_internal_notes' ) );
            add_action( 'wp_ajax_wpc_update_client_internal_notes', array( &$this, 'ajax_update_client_internal_notes' ) );
            add_action( 'wp_ajax_wpc_check_page_shortcode', array( &$this, 'ajax_check_page_shortcode' ) );

            add_action( 'wp_ajax_get_all_groups', array( &$this, 'ajax_get_all_groups' ) );
            add_action( 'wp_ajax_get_name', array( &$this, 'ajax_get_name' ) );

            //assign clients/circles
            add_action( 'wp_ajax_get_popup_pagination_data', array( &$this, 'ajax_get_popup_pagination_data' ) );
            add_action( 'wp_ajax_update_assigned_data', array( &$this, 'update_assigned_data' ) );

            add_action( 'admin_init', array( &$this, 'wpc_run_activated_functions' ) );

            //add/update client
            add_action('wp_clients_update', array(&$this, 'wp_clients_update_func'));

            register_deactivation_hook( $this->plugin_dir . 'wp-client-lite.php', array( &$this, 'deactivation_func' ) );

            add_filter( 'rewrite_rules_array', array( &$this, 'insert_rewrite_rules' ) );
            add_filter( 'query_vars', array( &$this, 'insert_query_vars' ) );
            add_filter( 'posts_request', array( &$this, 'query_for_wpc_client_pages' ) );

            //protect clientpage and hubpage
            add_filter( 'the_posts', array( &$this, 'filter_posts' ), 99 );

            //get template for Portal Page
            add_filter( 'single_template',  array( &$this, 'get_clientpage_template' ) );

            add_action( 'admin_init', array( &$this, 'add_mce_button_shortcodes' ), 99 );

            //change view link for HUB post type table
            add_action( 'manage_hubpage_posts_custom_column', array( &$this, 'custom_hubpage_columns' ), 2 );
            add_action( 'manage_edit-hubpage_columns', array( &$this, 'hub_columns' ), 2 );

            add_filter( 'get_sample_permalink_html',  array( &$this, 'hub_edit_sample_permalink_html' ), 99, 4 );

        }


        /*
        *  Preview link on edit HUB page
        */
        function hub_edit_sample_permalink_html( $return, $id, $new_title, $new_slug ) {
            $post = get_post( $id );
            if ( $post && 'hubpage' == $post->post_type ) {
                $return = '<strong>' . __( 'Permalink:' ) . '</strong> ' . '<span id="sample-permalink" tabindex="-1">' . wpc_client_get_slug( 'hub_page_id' ) . '</span>';
                $return .= ' <span id="view-post-btn"><a href="'. wpc_client_get_slug( 'hub_page_id' ) . $post->ID .'" target="_blank" class="button button-small">Preview</a></span>';
            }
            return $return;
        }


        /*
        *  Add new columns to HUB post type
        */
        function hub_columns( $columns ) {
            $columns['hub_title'] = 'HUB Title';
            $columns['client'] = 'Client';

            unset( $columns['title'] );
            unset( $columns['date'] );

            $columns['date'] = 'Date';

            return $columns;
        }


        /*
        * Download file by parts
        */
        function custom_hubpage_columns( $column ) {
            global $post;

            switch ( $column ) {
                case "hub_title" :
                    $edit_link = get_edit_post_link( $post->ID );
                    $title = _draft_or_post_title();
                    $post_type_object = get_post_type_object( $post->post_type );
                    $can_edit_post = current_user_can( $post_type_object->cap->edit_post, $post->ID );

                    echo '<strong><a class="row-title" href="'.$edit_link.'">' . $title.'</a>';

                    _post_states( $post );

                    echo '</strong>';

                    if ( $post->post_parent > 0 )
                        echo '&nbsp;&nbsp;&larr; <a href="'. get_edit_post_link($post->post_parent) .'">'. get_the_title($post->post_parent) .'</a>';

                    // Excerpt view
                    if (isset($_GET['mode']) && $_GET['mode']=='excerpt') echo apply_filters('the_excerpt', $post->post_excerpt);

                    // Get actions
                    $actions = array();

                    $actions['edit'] = '<a href="' . get_edit_post_link( $post->ID, true ) . '" title="' . esc_attr( __( 'Edit this item' ) ) . '">' . __( 'Edit' ) . '</a>';

                    if ( $can_edit_post && 'trash' != $post->post_status ) {
                        $actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="' . esc_attr( __( 'Edit this item inline', WPC_CLIENT_TEXT_DOMAIN ) ) . '">' . __( 'Quick&nbsp;Edit', WPC_CLIENT_TEXT_DOMAIN ) . '</a>';
                    }
                    if ( current_user_can( $post_type_object->cap->delete_post, $post->ID ) ) {
                        if ( 'trash' == $post->post_status )
                            $actions['untrash'] = "<a title='" . esc_attr( __( 'Restore this item from the Trash', WPC_CLIENT_TEXT_DOMAIN ) ) . "' href='" . wp_nonce_url( admin_url( sprintf( $post_type_object->_edit_link . '&amp;action=untrash', $post->ID ) ), 'untrash-' . $post->post_type . '_' . $post->ID ) . "'>" . __( 'Restore', WPC_CLIENT_TEXT_DOMAIN ) . "</a>";
                        elseif ( EMPTY_TRASH_DAYS )
                            $actions['trash'] = "<a class='submitdelete' title='" . esc_attr( __( 'Move this item to the Trash', WPC_CLIENT_TEXT_DOMAIN ) ) . "' href='" . get_delete_post_link( $post->ID ) . "'>" . __( 'Trash', WPC_CLIENT_TEXT_DOMAIN ) . "</a>";
                        if ( 'trash' == $post->post_status || !EMPTY_TRASH_DAYS )
                            $actions['delete'] = "<a class='submitdelete' title='" . esc_attr( __( 'Delete this item permanently', WPC_CLIENT_TEXT_DOMAIN ) ) . "' href='" . get_delete_post_link( $post->ID, '', true ) . "'>" . __( 'Delete Permanently', WPC_CLIENT_TEXT_DOMAIN ) . "</a>";
                    }
                    if ( $post_type_object->public ) {
                        if ( 'trash' != $post->post_status ) {
                            $actions['view'] = '<a href="' . wpc_client_get_slug( 'hub_page_id' ) . $post->ID . '" target="_blank" title="' . esc_attr( sprintf( __( 'Preview &#8220;%s&#8221;', WPC_CLIENT_TEXT_DOMAIN ), $title ) ) . '" rel="permalink">' . __( 'Preview', WPC_CLIENT_TEXT_DOMAIN ) . '</a>';
                        }
                    }
                    $actions = apply_filters( 'post_row_actions', $actions, $post );

                    echo '<div class="row-actions">';

                    $i = 0;
                    $action_count = sizeof($actions);

                    foreach ( $actions as $action => $link ) {
                        ++$i;
                        ( $i == $action_count ) ? $sep = '' : $sep = ' | ';
                        echo "<span class='$action'>$link$sep</span>";
                    }
                    echo '</div>';

                    get_inline_data( $post );

                break;

                case "client":
                    $client = get_users( array( 'role' => 'wpc_client', 'meta_key' => 'wpc_cl_hubpage_id', 'meta_value' => $post->ID ) );

                    if ( $client ) {
                        echo $client[0]->user_login;
                    }

                break;

            }
        }


        /*
        *
        */
        function set_plugin_data() {

            //default values
            $this->plugin['title'] = "WP-Client <span style='color: #5FC2EE; font-style: italic;'>Lite</span>";
            $this->plugin['logo_content'] = '';
            $this->plugin['logo_style'] = ".wpc_logo {
                background: url( '" . $this->plugin_url . "images/page_header.png' ) no-repeat transparent;
                width: 625px;
                height: 40px;
            }";
            $this->plugin['icon_url'] = $this->plugin_url . 'client-icon.ico';
            $this->plugin['hide_about_tab'] = 0;
            $this->plugin['hide_help_menu'] = 0;


            //get custom values
            $new_plugin_info = get_option( 'whtlwpc_settings' );

            //set title
            if ( isset( $new_plugin_info['title'] ) && '' != trim( $new_plugin_info['title'] ) ) {
                $this->plugin['title'] = stripslashes( trim( $new_plugin_info['title'] ) ) ;
            }

            //disable admin logo image
            if ( isset( $new_plugin_info['disable_admin_logo_img'] ) && 1 == $new_plugin_info['disable_admin_logo_img'] ) {
                $this->plugin['logo_style'] = '';
            }

            //set admin pages logo content
            if ( isset( $new_plugin_info['logo_content'] ) && '' != trim( $new_plugin_info['logo_content'] ) ) {
                $this->plugin['logo_content'] = stripslashes( trim( $new_plugin_info['logo_content'] ) );
            }

            //set admin pages logo style
            if ( isset( $new_plugin_info['logo_style'] ) && '' != trim( $new_plugin_info['logo_style'] ) ) {
                $this->plugin['logo_style'] = stripslashes( trim( $new_plugin_info['logo_style'] ) );
            }

            //set icon url
            if ( isset( $new_plugin_info['icon_url'] ) && '' != trim( $new_plugin_info['icon_url'] ) ) {
                $this->plugin['icon_url'] = stripslashes( trim( $new_plugin_info['icon_url'] ) );
            }

            //hide help menu
            if ( isset( $new_plugin_info['hide_help_menu'] ) && 1 ==  $new_plugin_info['hide_help_menu'] )  {
                $this->plugin['hide_help_menu'] = $new_plugin_info['hide_help_menu'];
            }

            //hide about tab
            if ( isset( $new_plugin_info['hide_about_tab'] ) && 1 == $new_plugin_info['hide_about_tab'] ) {
                $this->plugin['hide_about_tab'] = $new_plugin_info['hide_about_tab'];
            }

        }


        /*
        *
        */
        function style_for_logo() {
        ?>

        <style type="text/css">
            <?php echo $this->plugin['logo_style'] ?>
        </style>

        <style type="text/css">
            span.mce_wpc_client_button_shortcodes {
                background-image: url("<?php echo $this->plugin['icon_url'] ?>") !important;
                background-position: center center !important;
                background-repeat: no-repeat !important;
            }
        </style>

        <?php
        }


        /*
        *
        */
        function get_plugin_logo_block() {
            return '<div class="wpc_logo">' . $this->plugin['logo_content'] . '</div><hr />';
        }


        /*
        * Show admin notices
        */
        function admin_notices() {

            if ( current_user_can( 'administrator' ) ) {

                $notices = get_option( 'wpc_client_flags' );

                if ( ( !isset( $notices['skip_install_pages'] ) || !$notices['skip_install_pages'] ) && '' == wpc_client_get_slug( 'hub_page_id' ) && !isset( $_GET['install_pages'] ) && !isset( $_GET['skip_install_pages'] ) ) {
                ?>

                <div id="message" class="updated">
                    <div class="squeezer">
                        <p>
                            <span style="font-weight: bold; margin: 0px 15px 0px 0px;" ><?php printf( __( "<strong>Welcome to %s</strong> - Plugin almost ready to start.", WPC_CLIENT_TEXT_DOMAIN ), $this->plugin['title'] ) ?></span>

                            <a href="<?php echo add_query_arg( 'install_pages', 'true', admin_url( 'admin.php?page=wpclients_settings&tab=pages' ) ) ?>" class="button-primary">
                            <?php  printf( __( 'Install %s Pages', WPC_CLIENT_TEXT_DOMAIN ), $this->plugin['title'] ) ?>
                            </a>
                            <a class="skip button-primary" href="<?php echo add_query_arg( 'skip_install_pages', 'true', admin_url( 'admin.php?page=wpclients_settings&tab=pages' ) ) ?>">
                            <?php _e( 'Skip Install', WPC_CLIENT_TEXT_DOMAIN ) ?>
                            </a>

                            <span style="padding: 15px 0 0 30px; vertical-align: bottom;" >
                                <a href="javascript:;" title="<?php _e( "When you click 'Install " . $this->plugin['title'] . " Pages', the plugin will automatically create the necessary pages for the plugin's operation & populate those pages with the correct shortcodes. This is the default configuration, but can be changed later. Advanced & experienced admins can also choose to skip this default configuration process & manually build your portal by creating / assigning pages and then adding the appropriate shortcode.", WPC_CLIENT_TEXT_DOMAIN ) ?>">
                                    <img src="<?php echo $this->plugin_url . 'images/icon_q.png' ?>" width="15" height="15"  alt="" />
                                </a>
                            </span>
                        </p>
                    </div>
                    <style>
                        .ui-tooltip {
                        padding: 8px;
                        background-color: #fff;
                        position: absolute;
                        z-index: 9999;
                        max-width: 300px;
                        -webkit-box-shadow: 0 0 5px #aaa;
                        box-shadow: 0 0 5px #aaa;
                        }

                        .ui-priority-secondary,
                        .ui-widget-content .ui-priority-secondary,
                        .ui-widget-header .ui-priority-secondary {
                            opacity: 0;
                            filter:Alpha(Opacity=0);
                            font-weight: normal;
                        }
                        .ui-state-disabled,
                        .ui-widget-content .ui-state-disabled,
                        .ui-widget-header .ui-state-disabled {
                            opacity: .0;
                            filter:Alpha(Opacity=0);
                            background-image: none;
                        }
                        .ui-state-disabled .ui-icon {
                            filter:Alpha(Opacity=0); /* For IE8 - See #6059 */
                        }
                    </style>
                </div>

                <?php
                }
            }
        }


        /*
        * Ajax function for get client details
        *
        * @return array json answer to js
        */
        function ajax_view_client() {

            if ( !isset( $_POST['id'] ) || !$_REQUEST['id'] ) {
                echo json_encode( array( 'id' => '', 'warning' => true ) );
                exit;
            }

            $id = explode( '_', $_POST['id'] );

            //check id and hash
            if ( isset( $id[0] ) && $id[0] && isset( $id[1] ) && md5( 'wpcclientview_' . $id[0] ) == $id[1] ) {
                $client = get_userdata( $id[0] );

                $current_manager_id     = get_user_meta( $id[0], 'admin_manager', true );
                if ( $current_manager_id ) {
                    $manager = get_userdata( $current_manager_id );
                }

                $business_name = get_user_meta( $id[0], 'wpc_cl_business_name', true );

                ob_start();
                ?>

                <style type="text/css">

                    #wpc_client_details_content input[type=text] {
                        width:400px;
                    }

                    #wpc_client_details_content input[type=password] {
                        width:400px;
                    }

                </style>

                <h2><?php _e( 'View Client', WPC_CLIENT_TEXT_DOMAIN ) ?>: <?php echo $client->user_login?></h2>

                <table class="form-table">
                    <tr>
                        <td>
                            <label for="business_name"><?php _e( 'Business Name', WPC_CLIENT_TEXT_DOMAIN ) ?>: </label> <br/>
                            <input type="text" readonly="readonly" value="<?php echo $business_name;?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="contact_name"><?php _e( 'Contact Name', WPC_CLIENT_TEXT_DOMAIN ) ?>:</label> <br/>
                            <input type="text" readonly="readonly" value="<?php echo $client->nickname; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="contact_email"><?php _e( 'Email', WPC_CLIENT_TEXT_DOMAIN ) ?>:</label> <br/>
                            <input type="text" readonly="readonly" value="<?php echo $client->user_email ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="contact_phone"><?php _e( 'Phone', WPC_CLIENT_TEXT_DOMAIN ) ?>:</label> <br/>
                            <input type="text" readonly="readonly" value="<?php echo $client->wp_contact_phone?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php _e( 'Client Circles', WPC_CLIENT_TEXT_DOMAIN ) ?>:</label> <br/>
                            <?php
                            $client_groups = $this->get_client_groups_id( $id );

                            if ( is_array( $client_groups ) && count( $client_groups ) ) {
                                foreach ( $client_groups as $id ) {
                                    $group = $this->get_group( $id );

                                    if ( $group ) {
                                        echo '<span>' . $group['group_name'] . '</span>, ';
                                    }

                                }
                            }
                            ?>
                        </td>
                    </tr>
                </table>

                <?php

                $content = ob_get_contents();
                ob_end_clean();

                echo json_encode( array( 'content' => $content ) );
                exit;
            }

            echo json_encode( array( 'content' => '' ) );
            exit;
        }


        /*
        * Ajax function for get client internal notes
        *
        * @return array json answer to js
        */
        function ajax_get_client_internal_notes() {

            if ( !isset( $_POST['id'] ) || !$_REQUEST['id'] ) {
                echo json_encode( array( 'id' => '', 'warning' => true ) );
                exit;
            }

            $id = explode( '_', $_POST['id'] );

            //check id and hash
            if ( isset( $id[0] ) && $id[0] && isset( $id[1] ) && md5( 'wpcclientinternalnote_' . $id[0] ) == $id[1] ) {
                $client = get_userdata( $id[0] );

                $internal_notes     = get_user_meta( $id[0], 'wpc__internal_notes', true );
                if ( $internal_notes ) {
                    echo json_encode( array( 'client_name' => $client->user_login, 'internal_notes' => $internal_notes ) );
                } else {
                    echo json_encode( array( 'client_name' => $client->user_login, 'internal_notes' => '' ) );
                }
                exit;
            }

            echo json_encode( array( 'internal_notes' => '' ) );
            exit;
        }


        /*
        * Ajax function for get client internal notes
        *
        * @return array json answer to js
        */
        function ajax_update_client_internal_notes() {

            if ( !isset( $_POST['id'] ) || !$_REQUEST['id'] ) {
                die( json_encode( array('status' => false, 'message' => 'Some problem with update.' ) ) );
            }

            $id = explode( '_', $_POST['id'] );

            //check id and hash
            if ( isset( $id[0] ) && $id[0] && isset( $id[1] ) && md5( 'wpcclientinternalnote_' . $id[0] ) == $id[1] ) {
                $client = get_userdata( $id[0] );

                if ( $client ) {
                    $internal_notes = ( isset( $_POST['notes'] ) ) ? base64_decode( str_replace( '-', '+', $_POST['notes'] ) ) : '';

                    update_user_meta( $id[0], 'wpc__internal_notes', $internal_notes );
                    die( json_encode( array('status' => true, 'message' => 'Notes is updated.' ) ) );
                }
            }

            die( json_encode( array('status' => false, 'message' => 'Some problem with update.' ) ) );
        }


        /*
        * Ajax function for checked content of page is consist shortcode
        *
        * @return array json answer to js
        */
        function ajax_check_page_shortcode() {

            if ( !isset( $_REQUEST['page_id'] ) || !$_REQUEST['page_id'] ) {
                echo json_encode( array( 'id' => '', 'warning' => true ) );
                exit;
            }

            if ( !isset( $_REQUEST['shortcode_type'] ) || !$_REQUEST['shortcode_type'] ) {
                echo json_encode( array( 'warning' => false ) );
                exit;
            }

            $shortcode_type = $_REQUEST['shortcode_type'];
            $page_id = $_REQUEST['page_id'];

            $page = get_page( $page_id );

            if ( false === strpos( $page->post_content, $shortcode_type ) ) {
                echo json_encode( array( 'nes_shortcode' => $shortcode_type, 'id' => $page_id, 'warning' => true ) );
            } else {
                echo json_encode( array( 'id' => $page_id, 'warning' => false ) );
            }
            exit;

        }



        /*
        * Add MCE button for plugin's shortcodes
        */
        function add_mce_button_shortcodes() {
            if ( current_user_can( 'administrator' ) && 'true' == get_user_option( 'rich_editing' ) ) {
                add_filter( 'mce_external_plugins', array( &$this, 'create_mce_button_shortcodes' ) );
                add_filter( 'mce_buttons', array( &$this, 'register_mce_button_shortcodes' ) );
            }
        }


        /*
        * Create MCE button for plugin's shortcodes
        */
        function create_mce_button_shortcodes( $plugin_array ) {
            $plugin_array['WPC_Client_Shortcodes'] = $this->plugin_url . 'js/mce_shortcodes.js';
            return $plugin_array;
        }


        /*
        * Register MCE button for plugin's shortcodes
        */
        function register_mce_button_shortcodes( $buttons ) {
            array_push( $buttons, '|', 'wpc_client_button_shortcodes' );
            return $buttons;
        }


        /*
        * add meta on plughin pages
        */
        function add_meta_to_plugin_pages() {
            echo '<meta name="robots" content="noindex"/>';
            echo '<meta name="robots" content="nofollow"/>';
        }


        /*
        * Pre-set all plugin's pages
        */
        function pre_set_pages() {
            $wpc_pages = array(
                array(
                    'title'     => __( 'Login Page', WPC_CLIENT_TEXT_DOMAIN ),
                    'name'      => 'Login Page',
                    'desc'      => __( 'Page content: [wpc_client_loginf]', WPC_CLIENT_TEXT_DOMAIN ),
                    'id'        => 'login_page_id',
                    'old_id'    => 'login',
                    'shortcode' => true,
                    'content'   => '[wpc_client_loginf]',
                ),
                array(
                    'title'     => __( 'HUB Page', WPC_CLIENT_TEXT_DOMAIN ),
                    'name'      => 'HUB Page',
                    'desc'      => __( 'Page content: [wpc_client_hub_page]', WPC_CLIENT_TEXT_DOMAIN ),
                    'id'        => 'hub_page_id',
                    'old_id'    => 'hub',
                    'shortcode' => true,
                    'content'   => '[wpc_client_hub_page]',
                ),
                array(
                    'title'     => __( 'Portal Page', WPC_CLIENT_TEXT_DOMAIN ),
                    'name'      => 'Portal Page',
                    'desc'      => __( 'Page content: [wpc_client_portal_page]', WPC_CLIENT_TEXT_DOMAIN ),
                    'id'        => 'portal_page_id',
                    'old_id'    => '',
                    'shortcode' => true,
                    'content'   => '[wpc_client_portal_page]',
                ),
                array(
                    'title'     => __( 'Error', WPC_CLIENT_TEXT_DOMAIN ),
                    'name'      => 'Error',
                    'desc'      => __( "Page content: You haven't permission for this page.", WPC_CLIENT_TEXT_DOMAIN ),
                    'id'        => 'error_page_id',
                    'old_id'    => '',
                    'shortcode' => false,
                    'content'   => "You haven't permission for this page.",
                )
            );

            return $wpc_pages;
        }


        /*
        * Create all plugin's pages
        */
        function create_pages() {

            $wpc_pages = $this->pre_set_pages();

            $wpc_client_page = get_page_by_title( 'Portal' );

            if ( !isset( $wpc_client_page ) || 0 >= $wpc_client_page->ID ) {

                $current_user = wp_get_current_user();
                //Construct args for the new page
                $args = array(
                    'post_title'     => 'Portal',
                    'post_status'    => 'publish',
                    'post_author'    => $current_user->ID,
                    'post_content'   => '[wpc_redirect_on_login_hub]',
                    'post_type'      => 'page',
                    'ping_status'    => 'closed',
                    'comment_status' => 'closed'
                );
                $parent_page_id = wp_insert_post( $args );
            }

            $settings = get_option( 'wpc_settings' );

            foreach( $wpc_pages as $wpc_page ) {

                $wpc_client_page = get_page_by_title( $wpc_page['name'] );

                if ( !isset( $wpc_client_page ) || 0 >= $wpc_client_page->ID ) {

                    $current_user = wp_get_current_user();
                    //Construct args for the new page
                    $args = array(
                        'post_title'        => $wpc_page['name'],
                        'post_status'       => 'publish',
                        'post_author'       => $current_user->ID,
                        'post_content'      => $wpc_page['content'],
                        'post_type'         => 'page',
                        'ping_status'       => 'closed',
                        'comment_status'    => 'closed',
                        'post_parent'       => $parent_page_id,
                    );
                    $page_id = wp_insert_post( $args );

                    $settings['pages'][$wpc_page['id']] = $page_id;
                }


            }

            update_option( 'wpc_settings', $settings );

        }


        /*
        * Get login URL
        */
        function get_login_url() {
            $login_url = ( '' != wpc_client_get_slug( 'login_page_id' ) ) ? wpc_client_get_slug( 'login_page_id' ) : wp_login_url();
            return $login_url;
        }


        /*
        * Get logout URL
        */
        function get_logout_url() {
            $logout_url = ( '' != wpc_client_get_slug( 'login_page_id' ) ) ? add_query_arg( array( 'logout' => 'true' ), wpc_client_get_slug( 'login_page_id' ) ) : wp_logout_url();
            return $logout_url;
        }


        function adminmenu() {
            global $current_user;

            //add main menu and sub menu for WP Clients

            $client_hub = get_user_meta( $current_user->ID, 'wpc_cl_hubpage_id', true );

            if ( 0 < $client_hub && !current_user_can("administrator") ) {
                add_menu_page( __( 'Hub Page', WPC_CLIENT_TEXT_DOMAIN ), __( 'My Hub Page', WPC_CLIENT_TEXT_DOMAIN ), 'wpc_client', 'wpclients2', array(&$this, 'wpclients_func2'), $this->plugin['icon_url'] );
            }

            add_menu_page( $this->plugin['title'], $this->plugin['title'], "manage_options", 'wpclients', array(&$this, 'wpclients_func'), $this->plugin['icon_url'] );
            add_submenu_page( 'wpclients', __( 'Managers', WPC_CLIENT_TEXT_DOMAIN ), __( 'Managers', WPC_CLIENT_TEXT_DOMAIN ), 'manage_options', 'wpclients_managers', array( &$this, 'managers_func' ) );
            add_submenu_page( 'wpclients', __( 'Add Portal Page', WPC_CLIENT_TEXT_DOMAIN ), __( 'Add Portal Page', WPC_CLIENT_TEXT_DOMAIN ), "manage_options", 'add_client_page', array( &$this, 'add_client_page_func' ) );
            add_submenu_page( 'wpclients', __( 'Templates', WPC_CLIENT_TEXT_DOMAIN ), __( 'Templates', WPC_CLIENT_TEXT_DOMAIN ), 'manage_options', 'wpclients_templates', array( &$this, 'wpclients_templates' ) );
            add_submenu_page( 'wpclients', __( 'File Sharing', WPC_CLIENT_TEXT_DOMAIN ), __( 'File Sharing', WPC_CLIENT_TEXT_DOMAIN ), "manage_options", 'wpclients_files', array( &$this, 'wpclients_files' ) );
            add_submenu_page( 'wpclients', __( 'Client Circles', WPC_CLIENT_TEXT_DOMAIN ), __( 'Circles', WPC_CLIENT_TEXT_DOMAIN ), 'manage_options', 'wpclients_groups', array( &$this, 'wpclients_groups' ) );
            add_submenu_page( 'wpclients', __( 'Settings', WPC_CLIENT_TEXT_DOMAIN ), __( 'Settings', WPC_CLIENT_TEXT_DOMAIN ), 'manage_options', 'wpclients_settings', array( &$this, 'wpclients_settings' ) );

            add_submenu_page( 'wpclients', sprintf( __( '%s Wordpress Client Management Portal | Documentation & Tips', WPC_CLIENT_TEXT_DOMAIN ), $this->plugin['title'] ), __( 'Help', WPC_CLIENT_TEXT_DOMAIN ), 'manage_options', 'wpclients_help', array( &$this, 'wpclients_help' ) );

            //hidden pages
            add_submenu_page( 'wpclients', __( 'Login/logout redirects', WPC_CLIENT_TEXT_DOMAIN ), __( 'Login/logout redirects', WPC_CLIENT_TEXT_DOMAIN ), 'manage_options', 'xyris-login-logout', 'wpc_client_rul_optionsmenu' );
            add_submenu_page( 'wpclients', __( 'Custom Login', WPC_CLIENT_TEXT_DOMAIN ), __( 'Custom Login', WPC_CLIENT_TEXT_DOMAIN ), 'manage_options', CL_PAGE, 'wpc_client_custom_login_options' );
            add_submenu_page( 'wpclients', __( 'Messages', WPC_CLIENT_TEXT_DOMAIN ), __( 'Messages', WPC_CLIENT_TEXT_DOMAIN ), "manage_options", 'wpclients_messages', array( &$this, 'wpclients_messages_func' ) );

            // custom actions
            add_action('wp_settings_update', array(&$this, 'wp_settings_update_func'));
        }

        function parent_page_func() {
            global $current_user;


            //hide admin bar for client\staff
            if ( ( current_user_can( 'wpc_client' ) || current_user_can( 'wpc_client_staff' ) ) && !current_user_can( 'manage_network_options' ) )  {
                $wpc_settings = get_option( 'wpc_settings' );
                if ( !isset( $wpc_settings['hide_admin_bar'] ) || 'yes' == $wpc_settings['hide_admin_bar'] ) {
                    add_filter( 'show_admin_bar', create_function( '', 'return false;' ) );
                }
            }

            $create_client=get_option("wpc_create_client");

            if(empty($create_client)) {
                update_option("wpc_create_client","yes");
            }

            $url = $this->plugin_url . 'images';
            $theme2 = get_option('wpclients_theme');

            if(!$theme2) {
                $theme2 = "light";
            }

            $url .= "/" . $theme2;

            $this->hub_template = <<< EOD

<p><span style="font-size: medium; color: #800000;">Welcome to your Private and Secure Client Portal <span style="font-size: small;">| [wpc_client_logoutb]</span></span></p>
<p>From here, you can access all the pages, documents, photos &amp; files that have been posted for you.</p>
<p>You can upload files to us using the "Upload Files Here" option. You can also send us a Private Message.</p>
<table style="width: 100%;" border="0" cellspacing="0" cellpadding="0" align="center">
<tbody>
<tr>
<td style="width: 50%;" valign="top"><img title="" alt="" src="[wpc_client_theme][/wpc_client_theme]/your_pages.png" /></td>
<td style="width: 50%;" valign="top"><img title="" alt="" src="[wpc_client_theme][/wpc_client_theme]/upload_files.png" /></td>
</tr>
<tr>
<td style="width: 50%;" valign="top">[wpc_client_pagel][/wpc_client_pagel]</td>
<td style="width: 50%;" valign="top">[wpc_client_uploadf][/wpc_client_uploadf]</td>
</tr>
<tr>
<td style="width: 50%;" valign="top"></td>
<td style="width: 50%;" valign="top"></td>
</tr>
<tr>
<td style="width: 50%;" valign="top"><img title="" alt="" src="[wpc_client_theme][/wpc_client_theme]/uploaded_files.png" /></td>
<td style="width: 50%;" valign="top"><img title="" alt="" src="[wpc_client_theme][/wpc_client_theme]/your_files.png" /></td>
</tr>
<tr>
<td style="width: 50%;" valign="top">[wpc_client_fileslu][/wpc_client_fileslu]</td>
<td style="width: 50%;" valign="top">[wpc_client_filesla][/wpc_client_filesla]</td>
</tr>
<tr>
<td style="width: 50%; height: 70px;" valign="top"></td>
<td style="width: 50%;" valign="top"></td>
</tr>
<tr>
<td colspan="2" valign="top"><img title="" alt="" src="[wpc_client_theme][/wpc_client_theme]/messages.png" /></td>
</tr>
<tr>
<td colspan="2" valign="top">[wpc_client_com][/wpc_client_com]</td>
</tr>
</tbody>
</table>

EOD;

            $this->client_template = '
<p>[wpc_client]<span style="font-size: medium;">Welcome {name} to your first Portal Page<span style="font-size: small;"> | [wpc_client_get_page_link page="hub" text="HUB Page"] | [wpc_client_logoutb]</span></span></p>
<p>We\'ll be using this page to relay information and graphics to you.</p>
<p>You can use the private messaging feature at the bottom of each page if you\'d like to communicate with us, and all of our interaction will be here in one place.</p>
<p>Thanks!</p>
<table style="width: 100%;" border="0" cellspacing="0" cellpadding="0" align="center">
<tbody>
<tr>
<td style="width: 50%; height: 70px;" valign="top"></td>
<td style="width: 50%;" valign="top"></td>
</tr>
<tr>
<td colspan="2" valign="top"><img title="" alt="" src="[wpc_client_theme][/wpc_client_theme]/messages.png" /></td>
</tr>
<tr>
<td colspan="2" valign="top">[wpc_client_com][/wpc_client_com]</td>
</tr>
</tbody>
</table>
<p>[/wpc_client]</p>
';

            $sender_name    = get_option("sender_name");
            $sender_email   = get_option("sender_email");

            if(empty($sender_name)) {
                update_option("sender_name", get_bloginfo('name'));
            }

            if(empty($sender_email)) {
                update_option("sender_email", get_bloginfo("admin_email"));
            }

        }


        function wpclients_func() {

            if ( isset( $_GET['tab'] ) )
                $tab = $_GET['tab'];
            else
                $tab = 'clients';

            switch( $tab ) {
                case 'clients':
                    include 'forms/clients.php';
                    break;

                case 'add_client':
                    include 'forms/addclient.php';
                    break;

                case 'edit_client':
                    include 'forms/editclient.php';
                    break;

                case 'approve':
                case 'convert':
                case 'staff':
                case 'staff_add':
                case 'staff_edit':
                case 'staff_approve':
                case 'custom_fields':
                    include 'forms/lite_form.php';
                    break;
            }
        }

        function managers_func() {
            include 'forms/lite_form.php';
        }

        /*
        * redirect client on HUB from admin menu
        */
        function wpclients_func2() {
            global $current_user;

            $client_hub = get_user_meta( $current_user->ID, 'wpc_cl_hubpage_id', true );

            if( 0 < $client_hub ) {
                echo "You will be redirected to the page in a few seconds, if it doesn't redirect , please click <a href='" . wpc_client_get_slug( 'hub_page_id' ) . "'>here</a>";
                echo "<script type='text/javascript'>document.location='" . wpc_client_get_slug( 'hub_page_id' ) . "';</script>";
            }
        }

        function wpclients_messages_func() {
            include 'forms/lite_form.php';
        }

        function add_client_page_func() {
            include 'forms/addclientpage.php';
        }

        /*
        * templates functions
        */
        function wpclients_templates() {
            include 'forms/lite_form.php';
        }

        //page Files
        function wpclients_files() {
            include 'forms/lite_form.php';
        }

        //page Client Circles
        function wpclients_groups() {
            include 'forms/groups.php';
        }

        //page Help
        function wpclients_help() {

            $url = $this->plugin_url . 'images/logo2.png';
            $content = wpc_curl_download("http://wp-client.com/clients_remote/help.txt");

            echo '<div class="clear"></div>';

            echo $this->get_plugin_logo_block();

            echo "<h3>" . sprintf( __( '%s Wordpress Client Management Portal | Documentation & Tips', WPC_CLIENT_TEXT_DOMAIN ), $this->plugin['title'] ) . "</h3>";
            echo $content;
        }

        /*
        * display settings page
        */
        function wpclients_settings() {
            if ( isset( $_GET['tab'] ) && 'addons' == $_GET['tab'] )
                include 'forms/lite_form.php';
            elseif ( isset( $_GET['tab'] ) && 'pages' == $_GET['tab'] )
                include 'forms/settings_pages.php';
            elseif ( isset( $_GET['tab'] ) && 'capabilities' == $_GET['tab'] )
                include 'forms/lite_form.php';
            else
                include 'forms/settings.php';
        }

        /*
        * called from submit settings
        */
        function wp_settings_update_func($settings) {

            switch ( $settings['key'] ) {

                case 'general':
                    update_option( 'wpc_graphic', $settings['graphic'] );
                    update_option( 'wpc_create_client', $settings['create_client'] );

                    $current_settings = get_option( 'wpc_settings' );
                    if ( !is_array( $current_settings ) )
                        $current_settings = array();

                    $settings['wpc_settings'] = array_merge( $current_settings, $settings['wpc_settings'] );
                    //update settings value
                    update_option( 'wpc_settings', $settings['wpc_settings'] );

                    break;

                case 'business_info':
                    $current_settings = get_option( 'wpc_business_info' );
                    if ( !is_array( $current_settings ) )
                        $current_settings = array();

                    $settings['wpc_business_info'] = array_merge( $current_settings, $settings['wpc_business_info'] );
                    //update settings value
                    update_option( 'wpc_business_info', $settings['wpc_business_info'] );

                    break;

                case 'skins':
                    if ( $settings['theme'] )
                        update_option( 'wpclients_theme', $settings['theme'] );
                    break;

                case 'login_alerts':
                    //login alerts notifications
                    if ( isset( $settings['login_alerts'] ) ) {
                        if( !isset( $settings['login_alerts']['successful'] ) && '1' != $settings['login_alerts']['successful'] )
                            $settings['login_alerts']['successful'] = 0;
                        if( !isset( $settings['login_alerts']['failed'] ) && '1' != $settings['login_alerts']['failed'] )
                            $settings['login_alerts']['failed'] = 0;

                        update_option( 'wpc_login_alerts', $settings['login_alerts'] );
                    }
                    break;
            }
        }


        /*
        * add & update the wp clients as users
        */
        function wp_clients_update_func( $userdata ) {
            global $rul_db_addresses, $wpdb;

            if ( !isset( $userdata['ID'] ) ) {
                $wpc_settings = get_option( 'wpc_settings' );

                // insert new user
                $new_user = wp_insert_user($userdata);

                if ( isset( $_REQUEST['circles'] ) && is_string( $_REQUEST['circles'] ) && 0 < $new_user ) {
                    if( $_REQUEST['circles'] == 'all' ) {
                        $group_ids = $this->get_group_ids();
                    } else {
                        $group_ids = explode( ',', $_REQUEST['circles'] );
                    }
                    foreach ( $group_ids as $group_id ) {
                        $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}wpc_client_group_clients SET group_id = %d, client_id = '%d'", $group_id,  $new_user ) );
                    }
                }

                update_user_option( $new_user, 'contact_phone', $userdata['contact_phone'], false );
                update_user_option( $new_user, 'unqiue', md5( time() ) );

                //set business name
                if ( isset( $userdata['business_name'] ) ) {
                     update_user_meta( $new_user, 'wpc_cl_business_name', $userdata['business_name'] );
                }

                //create hub page for the user
                $post = array();
                $post['post_type']      = 'hubpage'; //could be 'page' for example
                $post['post_content']   = html_entity_decode( $this->hub_template );
                $post['post_author']    = 1;
                $post['post_status']    = 'publish'; //draft
                $post['comment_status'] = 'closed';
                $post['post_title']     = $userdata['business_name'];
                $post['post_parent']    = 0;
                $post['post_status']    = "publish";

                $postid = wp_insert_post($post);

                if ( 0 < $postid )
                    update_user_meta( $new_user, 'wpc_cl_hubpage_id', $postid );


                // add Portal Page for this user
                $client_template = $this->client_template;
                $client_template = html_entity_decode($client_template);
                $client_template = str_replace("{name}", $userdata['business_name'], $client_template);
                $client_template = str_replace("{page_title}",$userdata['business_name'],$client_template);

                $create_client = get_option("wpc_create_client");

                if( $create_client == "yes" ) {

                    $clients = array(
                        'comment_status'    => 'closed',
                        'ping_status'       => 'closed',
                        'post_author'       => get_current_user_id(),
                        'post_content'      => $client_template,
                        'post_name'         => $userdata['business_name'],
                        'post_status'       => 'publish',
                        'post_title'        => $userdata['business_name'],
                        'post_type'         => 'clientspage'
                    );

                    $client_page_id = wp_insert_post($clients);

                    $user_ids = array();
                    $user_ids[] = '' . $new_user ;
                    update_post_meta( $client_page_id, "user_ids", $user_ids );
                }


                $link = get_permalink($postid);

                if( isset( $userdata['send_password'] ) && ( $userdata['send_password'] == 'on' || $userdata['send_password'] == '1' ) ) {

                    //get email template

                    $headers = "From: " . get_option("sender_name") . " <" . get_option("sender_email") . "> \r\n";
                    $headers .= "Reply-To: " . ( get_option( 'wpc_reply_email' ) ) ? get_option( 'wpc_reply_email' ) : get_option( 'admin_email' ) . "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                    $args = array( 'client_id' => $new_user, 'user_password' => $userdata['user_pass'], 'page_id' => $link,  'page_title' => $userdata['business_name'] );

                    $wpc_templates['emails']['new_client_password']['subject'] = 'Your Private and Unique Client Portal has been created';

                    $wpc_templates['emails']['new_client_password']['body'] = '<p>Hello {contact_name},<br /> <br /> Your Username is : <strong>{user_name}</strong> and Password is : <strong>{user_password}</strong></p>
        <p>Your private and secure Client Portal has been created. You can login by clicking <strong><a href="{admin_url}">HERE</a></strong></p>
        <p>Thanks, and please contact us if you experience any difficulties,</p>
        <p>YOUR COMPANY NAME HERE</p>';

                    $subject = $this->replace_placeholders( $wpc_templates['emails']['new_client_password']['subject'], $args, 'new_client' );
                    $subject = htmlentities( $subject, ENT_QUOTES, 'UTF-8' );
                    $message = $this->replace_placeholders( $wpc_templates['emails']['new_client_password']['body'], $args, 'new_client' );

                    wp_mail($userdata['user_email'], $subject, $message, $headers);
                }

            } else {

                    wp_update_user( $userdata );
                    //sending email to client for updated password information
                    if ( '1' == $userdata['send_password'] && '1' == $userdata['update_password'] ) {

                        //get email template

                        $headers = "From: " . get_option("sender_name") . " <" . get_option("sender_email") . "> \r\n";
                        $headers .= "Reply-To: " . ( get_option( 'wpc_reply_email' ) ) ? get_option( 'wpc_reply_email' ) : get_option( 'admin_email' ) . "\r\n";
                        $headers .= "MIME-Version: 1.0\r\n";
                        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                        $args = array( 'client_id' => $userdata['ID'], 'user_password' => $userdata['user_pass'] );

                        $wpc_templates['emails']['client_updated']['subject'] = 'Your Client Password has been updated';

                        $wpc_templates['emails']['client_updated']['body'] = '<p>Hello {contact_name},<br /> <br /> Your Username is : <strong>{user_name}</strong> and Password is : <strong>{user_password}</strong></p>
                                <p>Your password has been updated. You can login by clicking <strong><a href="{admin_url}">HERE</a></strong></p>
                                <p>Thanks, and please contact us if you experience any difficulties,</p>
                                <p>YOUR COMPANY NAME HERE</p>';

                        $subject = $this->replace_placeholders( $wpc_templates['emails']['client_updated']['subject'], $args, 'client_updated' );
                        $subject = htmlentities( $subject, ENT_QUOTES, 'UTF-8' );

                        $message = $this->replace_placeholders( $wpc_templates['emails']['client_updated']['body'], $args, 'client_updated' );

                        wp_mail($userdata['user_email'], $subject, $message, $headers);
                }

                //sending email to client for updated password information
                update_user_option( $userdata['ID'], 'contact_phone', $userdata['contact_phone'], false );
                update_user_meta( $userdata['ID'], 'admin_manager', $userdata['admin_manager'] );

                if ( isset( $_REQUEST['circles'] ) && is_string( $_REQUEST['circles'] ) ) {
                    if( $_REQUEST['circles'] == 'all' ) {
                        $group_ids = $this->get_group_ids();
                    } else {
                        $group_ids = explode( ',', $_REQUEST['circles'] );
                    }
                    $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}wpc_client_group_clients WHERE client_id = '%d'", $userdata['ID'] ) );
                    foreach ( $group_ids as $group_id ) {
                           $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}wpc_client_group_clients SET group_id = %d, client_id = '%d'", $group_id,  $userdata['ID'] ) );
                    }
                }

            }
        }


        function wp_password_generator_install() {

            $defaults   = array('version' => WP_PASSWORD_GENERATOR_VERSION_WPCLIENT, 'min-length' => 7, 'max-length' => 16);
            $opts       = get_option('wp-password-generator-opts');

            if($opts) {
                // Remove 'characters', which was only used in version 2.1. We'll use whatever is defined in wp_generate_password()
                if(isset($opts['characters'])) {
                    unset($opts['characters']);
                }

                if(isset($opts['min-length']) && intval($opts['min-length']) > 0) {
                    $defaults['min-length'] = intval($opts['min-length']);
                }

                if(isset($opts['max-length']) && intval($opts['max-length']) >= $defaults['min-length']) {
                    $defaults['min-length'] = intval($opts['max-length']);
                }

                /*
                We've checked what we need to. If there are other items in $stored, let them stay ($defaults won't overwrite them)
                as some dev has probably spent some time adding custom functionality to the plugin.
                */
                $defaults = array_merge($opts, $defaults);
            }

            update_option('wp-password-generator-opts', $defaults);

            return true;
        }


        function wp_password_generator_load() {
            if(isset($_GET['page']) and $_REQUEST['page'] == 'add_client') {
                wp_enqueue_script('wp-password-generator', WP_PLUGIN_URL . '/wp-client-client-portals-file-upload-invoices-billing/wp-password-generator.js', array('jquery'), '2.1', true);
            }

            return true;
        }


        function wp_password_generator_generate() {

            $opts = get_option('wp-password-generator-opts', false);

            if(!$opts || $opts['version'] < WP_PASSWORD_GENERATOR_VERSION_WPCLIENT) { // No options or an older version
                $this->wp_password_generator_install();
                $opts = get_option('wp-password-generator-opts', false);
            }

            $len = mt_rand($opts['min-length'], $opts['max-length']); // Min/max password lengths

            echo wp_generate_password($len, true, false);

            return true;
        }

        /*
        * Send alert when login successful
        */
        function alert_login_successful( $username ) {
            $login_alerts = get_option( 'wpc_login_alerts' );

            if ( isset( $login_alerts['successful'] ) && '1' == $login_alerts['successful'] ) {

                if ( isset( $login_alerts['email'] ) && '' != $login_alerts['email'] ) {

                    $subject    = 'Login Successful';
                    $body       = "
                        User Name: " . $username . "\n
                        Description: Was Logged Successfully\n
                        Alert From: " . get_option( 'siteurl' ) . "\n
                        IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n
                        Date: " . current_time( 'mysql' ) . "\n";
                    wp_mail( $login_alerts['email'], $subject, $body );
                }
            }
        }

        /*
        * Send alert when login failed
        */
        function alert_login_failed( $username ) {
            $login_alerts = get_option( 'wpc_login_alerts' );

            if ( isset( $login_alerts['failed'] ) && '1' == $login_alerts['failed'] ) {
                if ( isset( $login_alerts['email'] ) && '' != $login_alerts['email'] ) {
                    if ( username_exists( $username ) )
                        $status = 'Incorrect Password';
                    else
                        $status = 'Unknown User';

                    $subject    = 'Login Failed';
                    $body       = "
                        User Name: " . $username . "\n
                        Description: " . $status . "\n
                        Alert From: " . get_option( 'siteurl' ) . "\n
                        IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n
                        Date: " . current_time( 'mysql' ) . "\n";
                    wp_mail( $login_alerts['email'], $subject, $body );
                }
            }
        }

        /*
        * Function for actions
        */
        function request_action() {
            //skip this function for AJAX
            if ( defined( 'DOING_AJAX' ) )
                return '';

            //hide dashbord/backend - redirect Client and Staff to my-hub page
            if ( ( current_user_can( 'wpc_client' ) || current_user_can( 'wpc_client_staff' ) ) && !current_user_can( 'manage_network_options' ) )  {
                $wpc_settings = get_option( 'wpc_settings' );
                //hide dashbord/backend
                if ( isset( $wpc_settings['hide_dashboard'] ) && 'yes' == $wpc_settings['hide_dashboard'] ) {
                    wp_redirect( wpc_client_get_slug( 'hub_page_id' ) );
                    exit();
                }
            }

            //check admin capability and add if admin haven't
            if ( current_user_can( 'manage_options' ) && !current_user_can( 'manage_network_options' ) && !current_user_can( 'edit_clientpages' ) )  {
                global $wp_roles;

                $capability_map = array(
                    'read_clientpages'               => 'View Portal Page.',
                    'publish_clientpages'            => 'Add Portal Page.',
                    'edit_clientpages'               => 'Edit Portal Pages.',
                    'edit_published_clientpages'     => 'Edit Portal Pages.',
                    'delete_published_clientpages'   => 'Delete Portal Pages.',
                    'edit_others_clientpages'        => 'Edit others Client Pages.',
                    'delete_others_clientpages'      => 'Delete others Portal Pages.',
                );

                //set capability for Portal Pages to Admin
                foreach ( array_keys( $capability_map ) as $capability ) {
                    $wp_roles->add_cap( 'administrator', $capability );
                }
            }

            //Uninstall plugin - delete all plugin data
            if ( !defined( 'DOING_AJAX' ) &&  isset( $_GET['action'] ) && 'wpclient_uninstall' == $_GET['action'] ) {
                define( 'WP_UNINSTALL_PLUGIN', '1' );

                //deactivate the plugin
                $plugins = get_option( 'active_plugins' );
                if ( is_array( $plugins ) && 0 < count( $plugins ) ) {
                    $new_plugins = array();
                    foreach( $plugins as $plugin )
                        if ( 'wp-client-client-portals-file-upload-invoices-billing/wp-client-lite.php' != $plugin )
                            $new_plugins[] = $plugin;
                }
                update_option( 'active_plugins', $new_plugins );

                delete_option( 'wpc_run_activated_functions' );
                //uninstall
                include 'wp-client-uninstall.php';

                wp_redirect( get_admin_url() . 'plugins.php' );
                exit;
            }

            //private actions of the plugin
            if ( isset( $_REQUEST['wpc_action'] ) && ( current_user_can( 'manage_options' ) || current_user_can( 'wpc_manager' ) || current_user_can( 'manage_network_options' ) ) ) {
                switch( $_REQUEST['wpc_action'] ) {
                    //action for create new Client Circle
                    case 'create_group':
                        $args = array(
                            'group_id'          => '0',
                            'group_name'        => ( isset( $_REQUEST['group_name'] ) ) ? $_REQUEST['group_name'] : '',
                            'auto_select'       => ( isset( $_REQUEST['auto_select'] ) ) ? '1' : '0',
                            'auto_add_clients'  => ( isset( $_REQUEST['auto_add_clients'] ) ) ? '1' : '0',
                            'assign_all'        => ( isset( $_REQUEST['assign_all'] ) ) ? '1' : '0',
                        );
                        $this->create_group( $args );
                    break;

                    //action for edit Client Circle
                    case 'edit_group':
                        $args = array(
                            'group_id'          => ( isset( $_REQUEST['group_id'] ) && 0 < $_REQUEST['group_id'] ) ? $_REQUEST['group_id'] : '0',
                            'group_name'        => ( isset( $_REQUEST['group_name'] ) ) ? $_REQUEST['group_name'] : '',
                            'auto_select'       => ( isset( $_REQUEST['auto_select'] ) ) ? '1' : '0',
                            'auto_add_clients'  => ( isset( $_REQUEST['auto_add_clients'] ) ) ? '1' : '0',
                            'assign_all'        => '0',
                        );
                        $this->create_group( $args );
                    break;

                    //action for delete Client Circle
                    case 'delete_group':
                        $this->delete_group( $_REQUEST['group_id'] );
                    break;

                    //action for assign clients to Client Circle
                    case 'save_group_clients':
                        $this->assign_clients_group();
                    break;
                }
            }

        }


        /**
         * Create/Edit new Client Circle
         **/
        function create_group( $args ) {
            global $wpdb;

            //checking that Client Circle not exist other ID
            $result = $wpdb->get_row( $wpdb->prepare( "SELECT group_id FROM {$wpdb->prefix}wpc_client_groups WHERE LOWER(group_name) = '%s'",  strtolower( $args['group_name'] ) ), "ARRAY_A");
            if ( $result ) {
                if ( "0" != $args['group_id'] && $result['group_id'] == $args['group_id'] ) {

                } else {
                    //if Client Circle exist with other ID
                    wp_redirect( add_query_arg( array( 'page' => 'wpclients_groups', 'updated' => 'true', 'dmsg' => urlencode( __( 'The Group already exists!!!', WPC_CLIENT_TEXT_DOMAIN ) ) ), 'admin.php' ) );
                    exit;
                }
            }


            if ( '0' != $args['group_id'] ) {
                //update when edit Client Circle
                $result = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}wpc_client_groups SET group_name = '%s', auto_select = '%s', auto_add_clients = '%s' WHERE group_id = %d",
                trim( $args['group_name'] ),
                $args['auto_select'],
                $args['auto_add_clients'],
                $args['group_id'] ) );
                wp_redirect( add_query_arg( array( 'page' => 'wpclients_groups', 'updated' => 'true', 'dmsg' => urlencode( __( 'The changes of the group are saved!', WPC_CLIENT_TEXT_DOMAIN )  ) ), 'admin.php' ) );
                exit;
            } else {
                //create new Client Circle
                $result = $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}wpc_client_groups SET group_name = '%s', auto_select = '%s', auto_add_clients = '%s'",
                trim( $args['group_name'] ),
                $args['auto_select'],
                $args['auto_add_clients']
                ) );

                //assign all clients
                if ( '1' == $args['assign_all'] ) {
                    $new_group_id = $wpdb->insert_id;

                    $args = array(
                        'role' => 'wpc_client',
                    );

                    $clients = get_users( $args );

                    if ( is_array( $clients ) && 0 < count( $clients ) )
                        foreach ( $clients as $client ) {
                            $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}wpc_client_group_clients SET group_id = %d, client_id = '%d'", $new_group_id,  $client->ID ) );
                        }

                }


                wp_redirect( add_query_arg( array( 'page' => 'wpclients_groups', 'updated' => 'true', 'dmsg' => urlencode( __( 'Client Circle is created!', WPC_CLIENT_TEXT_DOMAIN ) ) ), 'admin.php' ) );
                exit;
            }

        }


        /**
         * Delete Client Circle
         **/
        function delete_group( $group_id ) {
            global $wpdb;
            //delete Client Circle
            $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}wpc_client_groups WHERE group_id = %d", $group_id ) );

            //delete all clients from Client Circle
            $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}wpc_client_group_clients WHERE group_id = %d", $group_id ) );

            wp_redirect( add_query_arg( array( 'page' => 'wpclients_groups', 'updated' => 'true', 'dmsg' => urlencode( __( 'Client Circle is deleted!', WPC_CLIENT_TEXT_DOMAIN ) ) ), 'admin.php' ) );
            exit;
        }


        /**
         * Assign Clients to Client Circle
         **/
        function assign_clients_group() {
            global $wpdb;

            $group_id           = $_POST['group_id'];
            $group_clients_id   = ( isset( $_POST['group_clients_id'] ) ) ? $_POST['group_clients_id'] : array();

            //delete clients from Client Circle
            $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}wpc_client_group_clients WHERE group_id = %d", $group_id ) );


            //Add clients to the Client Circle
            if ( is_array( $group_clients_id ) && 0 < count( $group_clients_id ) )
                foreach ( $group_clients_id as $client_id ) {
                    $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}wpc_client_group_clients SET group_id = %d, client_id = '%d'", $group_id,  $client_id ) );
                }

            wp_redirect( add_query_arg( array( 'page' => 'wpclients_groups', 'updated' => 'true', 'dmsg' => urlencode( __( 'Clients are assigned!', WPC_CLIENT_TEXT_DOMAIN ) ) ), 'admin.php' ) );
            exit;
        }


        /**
         * Get all data of all Client Circles
         **/
         function get_groups() {
            global $wpdb;
            $groups = $wpdb->get_results( "SELECT wcg.*, count(wcgc.client_id) as clients_count FROM {$wpdb->prefix}wpc_client_groups wcg LEFT JOIN {$wpdb->prefix}wpc_client_group_clients wcgc ON wcgc.group_id = wcg.group_id GROUP BY wcg.group_id", "ARRAY_A");
            return $groups;
         }

         function get_group_ids() {
            global $wpdb;
            $groups = $wpdb->get_col( "SELECT group_id FROM {$wpdb->prefix}wpc_client_groups");
            return $groups;
         }

         function get_client_ids() {
            //all clients
            $not_approved_clients   = get_users( array( 'role' => 'wpc_client', 'meta_key' => 'to_approve', 'fields' => 'ID', ) );
            $args = array(
                'role'      => 'wpc_client',
                'exclude'   => $not_approved_clients,
                'fields'    => array( 'ID' ),
                'orderby'   => 'user_login',
                'order'     => 'ASC',
            );

            $clients = get_users( $args );
            $clients_array = array();
            foreach( $clients as $client ) {
                $clients_array[] = $client->ID;
            }

            return $clients_array;
         }


        /**
         * Get Client Circle by ID
         **/
         function get_group( $id ) {
            global $wpdb;
            return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wpc_client_groups WHERE group_id = %d", $id ), "ARRAY_A");
         }


        /**
         * Get all clients for Client Circle
         **/
         function get_group_clients_id( $group_id ) {
            global $wpdb;

            if ( 0 >= $group_id )
                return array();

            $group_clients      = $wpdb->get_results( $wpdb->prepare( "SELECT client_id FROM {$wpdb->prefix}wpc_client_group_clients WHERE group_id = %d", $group_id ), "ARRAY_A" );
            $group_clients_id   = array();

            foreach( $group_clients as $group_client )
                $group_clients_id[] = $group_client['client_id'];

            return $group_clients_id;
         }


        /**
         * Get all Client Circles for client
         **/
         function get_client_groups_id( $client_id ) {
            global $wpdb;

            $client_groups_id   = $wpdb->get_col( $wpdb->prepare( "SELECT group_id FROM {$wpdb->prefix}wpc_client_group_clients WHERE client_id = %d", $client_id ) );

            if ( !is_array( $client_groups_id ) )
                $client_groups_id   = array();

            return $client_groups_id;
         }


         /**
         * AJAX - Get all Client Circles
         **/
         function ajax_get_all_groups() {
            global $wpdb;

            $groups = $this->get_groups();

            if ( is_array( $groups ) && 0 < count( $groups ) ) {

                $i = 0;
                $n = ceil( count( $groups ) / 5 );

                $html = '';
                $html .= '<ul class="clients_list">';



                foreach ( $groups as $group ) {
                    if ( $i%$n == 0 && 0 != $i )
                        $html .= '</ul><ul class="clients_list">';

                    $html .= '<li><label>';
                    $html .= '<input type="checkbox" name="groups_id[]" value="' . $group['group_id'] . '" /> ';
                    $html .= $group['group_id'] . ' - ' . $group['group_name'];
                    $html .= '</label></li>';

                    $i++;
                }

                $html .= '</ul>';
            } else {
                $html = 'false';
            }

            die( $html );

         }


         /*
         *Get client login or  circle name by ajax request
         */
         function ajax_get_name() {
             if( isset( $_POST['type'] ) && isset( $_POST['id'] ) ) {
                 switch( $_POST['type'] ) {
                     case 'clients':
                        $userdata = get_userdata( $_POST['id'] );
                        echo json_encode( array( 'status' => true, 'name' => $userdata->get('user_login') ) );
                        break;
                     case 'circles':
                        $res = $this->get_group( $_POST['id'] );
                        echo json_encode( array( 'status' => true, 'name' => $res['group_name'] ) );
                        break;
                     default:
                        echo json_encode( array( 'status' => false, 'message' => 'Wrong type' ) );
                        break;
                 }
             }
             exit;
         }


         /**
         * AJAX update assigned clients\cicles
         **/
         function update_assigned_data() {
             global $wpdb;
             $data = '';
             if( isset($_POST['datatype']) && !empty($_POST['datatype']) && isset($_POST['current_page']) && !empty($_POST['current_page']) ) {
                 $current_page = $_POST['current_page'];
                 $datatype = $_POST['datatype'];
                 switch( $current_page ) {
                    case 'wpclients_groups':
                        if( isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['data']) ) {
                            $id = $_POST['id'];
                            $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}wpc_client_group_clients WHERE group_id = %d", $id ) );
                            if( 'all' == $_POST['data'] ) {
                                $not_approved_clients   = get_users( array( 'role' => 'wpc_client', 'meta_key' => 'to_approve', 'fields' => 'ID', ) );
                               //all clients
                                $args = array(
                                    'role'      => 'wpc_client',
                                    'exclude'   => $not_approved_clients,
                                    'fields'    => array( 'ID' ),
                                );

                                $clients = get_users( $args );

                                foreach ( $clients as $client ) {
                                    //$data .= '#'.$client->ID.",";
                                    $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}wpc_client_group_clients SET group_id = %d, client_id = '%d'", $id,  $client->ID ) );
                                }
                            } else {
                                if(!empty($_POST['data'])) {
                                    $data = explode(',', $_POST['data']);
                                } else {
                                    $data = array();
                                }
                                foreach ( $data as $data_item ) {
                                    $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}wpc_client_group_clients SET group_id = %d, client_id = '%d'", $id,  $data_item ) );
                                }
                            }
                        }
                        break;
                 }
             } else {
                 echo json_encode(array('status' => false, 'message' => 'Wrong update data.'));
             }
             exit;
         }


        /**
         * AJAX popup pagination
         **/
         function ajax_get_popup_pagination_data($datatype = '', $cur_page = '1', $goto = 'first', $display_page = 'wpclient_files') {
             global $wpdb;
             $per_page = 50;
             $new_page = 1;
             $limit = '';
             $buttons = array('first' => true, 'prev' => true, 'next' => true, 'last' =>true);

             if( (isset($_POST['datatype']) && !empty($_POST['datatype'])) || !empty($datatype) ) {
                 $type = ( isset($_POST['datatype']) && !empty($_POST['datatype']) ) ? $_POST['datatype'] : $datatype;
                 $cur_page = ( isset($_POST['page']) && !empty($_POST['page']) ) ? $_POST['page'] : $cur_page;
                 $display_page = ( isset($_POST['current_page']) && !empty($_POST['current_page']) ) ? $_POST['current_page'] : $display_page;
                 $display = ( isset($_POST['display']) && !empty($_POST['display']) )?$_POST['display']:'user_login';

                 $id = '';
                 if( 'clients' != $type && 0 === strpos($type, 'clients') ){
                     $id = substr($type,strlen('clients'));
                     if( is_numeric($id) ) {
                        $type = "clients";
                     }
                 }else if( 'circles' != $type && 0 === strpos($type, 'circles') ){
                     $id = substr($type,strlen('circles'));
                     if( is_numeric($id) ) {
                        $type = "circles";
                     }
                 }

                 switch($type) {
                    case 'clients': case 'clients_return':
                    {

                        if( isset($_POST['order']) && !empty($_POST['order']) ) {
                             $temp_order_array = explode("_", $_POST['order']);
                             if( 2 == count($temp_order_array) ) {
                                 if( ( 'asc' == strtolower($temp_order_array[1]) || 'desc' == strtolower($temp_order_array[1]) ) && ( 'show' == strtolower($temp_order_array[0]) || 'date' == strtolower($temp_order_array[0]) || 'first' == strtolower($temp_order_array[0]) ) ) {
                                     if( 'business_name' == $display ) {
                                         $display = 'um.meta_value';
                                     }
                                     switch($temp_order_array[0]) {
                                        case 'show':
                                            if( strpos( $display, '.' ) ) {
                                                $order_type = $display;
                                            } else {
                                                $order_type = "u.".$display;
                                            }
                                            break;
                                        case 'date':
                                            $order_type = 'u.user_registered';
                                            break;
                                        case 'first':
                                            $order_type = 'u.user_login';
                                            break;
                                     }
                                     $order = $temp_order_array[1];
                                 } else {
                                    $order_type = 'user_login';
                                    $order = "ASC";
                                 }
                             } else {
                                $order_type = 'user_login';
                                $order = "ASC";
                             }
                         } else {
                             $order_type = 'user_login';
                             $order = "ASC";
                         }


                        $where = '';
                        $not_approved_clients   = get_users( array( 'role' => 'wpc_client', 'meta_key' => 'to_approve', 'fields' => 'ID', ) );
                        if ( current_user_can( 'wpc_manager' ) && !current_user_can( 'administrator' ) ) {
                            $where = " AND (mt3.meta_key = 'admin_manager' AND CAST(mt3.meta_value AS CHAR) LIKE '".get_current_user_id()."')";
                        }

                        if ( is_array( $not_approved_clients ) && count( $not_approved_clients ) ) {
                            $where .= " AND u.ID NOT IN (" . implode( ",", $not_approved_clients ) . ")";
                        }

                        if( isset($_POST['search']) && !empty($_POST['search']) ) {
                            $s = $_POST['search'];
                            $sql = "SELECT DISTINCT u.ID
                                    FROM {$wpdb->users} u
                                    INNER JOIN {$wpdb->usermeta} um ON (u.ID = um.user_id)
                                    INNER JOIN {$wpdb->usermeta} AS mt1 ON (u.ID = mt1.user_id)
                                    INNER JOIN {$wpdb->usermeta} AS mt2 ON (u.ID = mt2.user_id)
                                    INNER JOIN {$wpdb->usermeta} AS mt3 ON (u.ID = mt3.user_id)
                                    WHERE (u.user_login LIKE '%$s%'
                                              OR u.ID = '$s'
                                              OR u.user_email LIKE '%$s%'
                                              OR u.user_nicename LIKE '%$s%'
                                              OR (um.meta_key = 'user_firstname' AND CAST(um.meta_value AS CHAR) LIKE '%$s%')
                                              OR (mt1.meta_key = 'user_lastname' AND CAST(mt1.meta_value AS CHAR) LIKE '%$s%'))
                                          AND (mt2.meta_key = '{$wpdb->prefix}capabilities' AND CAST(mt2.meta_value AS CHAR) LIKE '%\"wpc_client\"%') $where";
                            $wpdb->query($sql);
                            $sql = "SELECT FOUND_ROWS()";
                            $clients_count = $wpdb->get_var($sql);
                        } else {
                            $sql = "SELECT DISTINCT u.ID
                                    FROM {$wpdb->users} u
                                    INNER JOIN {$wpdb->usermeta} um ON (u.ID = um.user_id)
                                    INNER JOIN {$wpdb->usermeta} AS mt1 ON (u.ID = mt1.user_id)
                                    INNER JOIN {$wpdb->usermeta} AS mt2 ON (u.ID = mt2.user_id)
                                    INNER JOIN {$wpdb->usermeta} AS mt3 ON (u.ID = mt3.user_id)
                                    WHERE (mt2.meta_key = '{$wpdb->prefix}capabilities' AND CAST(mt2.meta_value AS CHAR) LIKE '%\"wpc_client\"%') $where";
                            $wpdb->query($sql);
                            $sql = "SELECT FOUND_ROWS()";
                            $clients_count = $wpdb->get_var($sql);
                        }

                        if ( $clients_count > 0 ) {
                            if($clients_count > $per_page) {
                                $goto = ( isset($_POST['goto']) && !empty($_POST['goto']) ) ? $_POST['goto'] : $goto;

                                switch($goto) {
                                    case 'first':
                                        $offset = 0;
                                        $new_page = 1;
                                        $buttons['first'] = false;
                                        $buttons['prev'] = false;
                                        break;
                                    case 'prev':
                                        $offset = ($cur_page-2)*$per_page;
                                        $new_page = $cur_page - 1;
                                        if($new_page <= 1) {
                                            $buttons['first'] = false;
                                            $buttons['prev'] = false;
                                            $new_page = 1;
                                        }
                                        break;
                                    case 'next':
                                        $last_page = ceil($clients_count/$per_page);
                                        $offset = $cur_page*$per_page;
                                        $new_page = $cur_page + 1;
                                        if($new_page >= $last_page) {
                                            $buttons['next'] = false;
                                            $buttons['last'] = false;
                                            $new_page = $last_page;
                                        }
                                        break;
                                    case 'last':
                                        $last_page = ceil($clients_count/$per_page);
                                        $offset = ($last_page - 1)*$per_page;
                                        $new_page = $last_page;
                                        $buttons['next'] = false;
                                        $buttons['last'] = false;
                                        break;
                                    default:
                                        $offset = 0;
                                        $new_page = 1;
                                        $buttons['first'] = false;
                                        $buttons['prev'] = false;
                                        break;
                                }
                                /*if( isset($_POST['search']) && !empty($_POST['search']) ) {*/
                                    $limit = "LIMIT $offset, $per_page";
                                /*} else {
                                    $args = array_merge($args, array('number' => $per_page, 'offset' => $offset));
                                }*/
                            } else {
                                $buttons = array('first' => false, 'prev' => false, 'next' => false, 'last' =>false);
                            }

                            $order_by_sql = $order_type." ".$order;

                            if('um.meta_value' == $display) {
                                $sql_inner_part = " AND um.meta_key = 'wpc_cl_business_name'";
                            } else {
                                $sql_inner_part = '';
                            }

                            if( isset($_POST['search']) && !empty($_POST['search']) ) {
                                if( isset( $_POST['order'] ) && 'first_asc' == $_POST['order'] && isset( $_POST['already_assinged'] ) && !empty( $_POST['already_assinged'] ) && $_POST['already_assinged'] != 'all' ) {

                                    $assigned_users_str = $_POST['already_assinged'];
                                    $assigned_users = " AND u.ID IN ($assigned_users_str) ";
                                    $not_assigned_users = " AND u.ID NOT IN ($assigned_users_str) ";

                                    $sql = "(
                                                SELECT DISTINCT u.ID, $display as user_login
                                                FROM {$wpdb->users} u
                                                INNER JOIN {$wpdb->usermeta} um ON (u.ID = um.user_id $sql_inner_part)
                                                INNER JOIN {$wpdb->usermeta} AS mt1 ON (u.ID = mt1.user_id)
                                                INNER JOIN {$wpdb->usermeta} AS mt2 ON (u.ID = mt2.user_id)
                                                INNER JOIN {$wpdb->usermeta} AS mt3 ON (u.ID = mt3.user_id)
                                                WHERE (u.user_login LIKE '%$s%'
                                                          OR u.ID = '$s'
                                                          OR u.user_email LIKE '%$s%'
                                                          OR u.user_nicename LIKE '%$s%'
                                                          OR (um.meta_key = 'user_firstname' AND CAST(um.meta_value AS CHAR) LIKE '%$s%')
                                                          OR (mt1.meta_key = 'user_lastname' AND CAST(mt1.meta_value AS CHAR) LIKE '%$s%'))
                                                      $assigned_users
                                                      AND (mt2.meta_key = '{$wpdb->prefix}capabilities' AND CAST(mt2.meta_value AS CHAR) LIKE '%\"wpc_client\"%') $where
                                                ORDER BY $order_by_sql
                                            ) UNION (
                                                SELECT DISTINCT u.ID, $display as user_login
                                                FROM {$wpdb->users} u
                                                INNER JOIN {$wpdb->usermeta} um ON (u.ID = um.user_id $sql_inner_part)
                                                INNER JOIN {$wpdb->usermeta} AS mt1 ON (u.ID = mt1.user_id)
                                                INNER JOIN {$wpdb->usermeta} AS mt2 ON (u.ID = mt2.user_id)
                                                INNER JOIN {$wpdb->usermeta} AS mt3 ON (u.ID = mt3.user_id)
                                                WHERE (u.user_login LIKE '%$s%'
                                                          OR u.ID = '$s'
                                                          OR u.user_email LIKE '%$s%'
                                                          OR u.user_nicename LIKE '%$s%'
                                                          OR (um.meta_key = 'user_firstname' AND CAST(um.meta_value AS CHAR) LIKE '%$s%')
                                                          OR (mt1.meta_key = 'user_lastname' AND CAST(mt1.meta_value AS CHAR) LIKE '%$s%'))
                                                      $not_assigned_users
                                                      AND (mt2.meta_key = '{$wpdb->prefix}capabilities' AND CAST(mt2.meta_value AS CHAR) LIKE '%\"wpc_client\"%') $where
                                                ORDER BY $order_by_sql
                                            )
                                            $limit";
                                } else {
                                    $sql = "SELECT DISTINCT u.ID, $display as user_login
                                            FROM {$wpdb->users} u
                                            INNER JOIN {$wpdb->usermeta} um ON (u.ID = um.user_id $sql_inner_part)
                                            INNER JOIN {$wpdb->usermeta} AS mt1 ON (u.ID = mt1.user_id)
                                            INNER JOIN {$wpdb->usermeta} AS mt2 ON (u.ID = mt2.user_id)
                                            INNER JOIN {$wpdb->usermeta} AS mt3 ON (u.ID = mt3.user_id)
                                            WHERE (u.user_login LIKE '%$s%'
                                                      OR u.ID = '$s'
                                                      OR u.user_email LIKE '%$s%'
                                                      OR u.user_nicename LIKE '%$s%'
                                                      OR (um.meta_key = 'user_firstname' AND CAST(um.meta_value AS CHAR) LIKE '%$s%')
                                                      OR (mt1.meta_key = 'user_lastname' AND CAST(mt1.meta_value AS CHAR) LIKE '%$s%'))
                                                  AND (mt2.meta_key = '{$wpdb->prefix}capabilities' AND CAST(mt2.meta_value AS CHAR) LIKE '%\"wpc_client\"%') $where
                                            ORDER BY $order_by_sql
                                            $limit";
                                }
                                $clients = $wpdb->get_results($sql);
                            } else {
                                if( isset( $_POST['order'] ) && 'first_asc' == $_POST['order'] && isset( $_POST['already_assinged'] ) && !empty( $_POST['already_assinged'] ) && $_POST['already_assinged'] != 'all' ) {

                                    $assigned_users_str = $_POST['already_assinged'];
                                    $assigned_users = " AND u.ID IN ($assigned_users_str) ";
                                    $not_assigned_users = " AND u.ID NOT IN ($assigned_users_str) ";

                                    $sql = "(
                                                SELECT DISTINCT u.ID, $display as user_login
                                                FROM {$wpdb->users} u
                                                INNER JOIN {$wpdb->usermeta} um ON (u.ID = um.user_id $sql_inner_part)
                                                INNER JOIN {$wpdb->usermeta} AS mt1 ON (u.ID = mt1.user_id)
                                                INNER JOIN {$wpdb->usermeta} AS mt2 ON (u.ID = mt2.user_id)
                                                INNER JOIN {$wpdb->usermeta} AS mt3 ON (u.ID = mt3.user_id)
                                                WHERE (mt2.meta_key = '{$wpdb->prefix}capabilities' AND CAST(mt2.meta_value AS CHAR) LIKE '%\"wpc_client\"%') $where $assigned_users
                                                ORDER BY $order_by_sql
                                            ) UNION (
                                                SELECT DISTINCT u.ID, $display as user_login
                                                FROM {$wpdb->users} u
                                                INNER JOIN {$wpdb->usermeta} um ON (u.ID = um.user_id $sql_inner_part)
                                                INNER JOIN {$wpdb->usermeta} AS mt1 ON (u.ID = mt1.user_id)
                                                INNER JOIN {$wpdb->usermeta} AS mt2 ON (u.ID = mt2.user_id)
                                                INNER JOIN {$wpdb->usermeta} AS mt3 ON (u.ID = mt3.user_id)
                                                WHERE (mt2.meta_key = '{$wpdb->prefix}capabilities' AND CAST(mt2.meta_value AS CHAR) LIKE '%\"wpc_client\"%') $where $not_assigned_users
                                                ORDER BY $order_by_sql
                                            )
                                            $limit";
                                } else {
                                    $sql = "SELECT DISTINCT u.ID, $display as user_login
                                            FROM {$wpdb->users} u
                                            INNER JOIN {$wpdb->usermeta} um ON (u.ID = um.user_id $sql_inner_part)
                                            INNER JOIN {$wpdb->usermeta} AS mt1 ON (u.ID = mt1.user_id)
                                            INNER JOIN {$wpdb->usermeta} AS mt2 ON (u.ID = mt2.user_id)
                                            INNER JOIN {$wpdb->usermeta} AS mt3 ON (u.ID = mt3.user_id)
                                            WHERE (mt2.meta_key = '{$wpdb->prefix}capabilities' AND CAST(mt2.meta_value AS CHAR) LIKE '%\"wpc_client\"%') $where
                                            ORDER BY $order_by_sql
                                            $limit";
                                }
                                $clients = $wpdb->get_results($sql);
                            }
                            $i = 0;
                            if($clients_count > $per_page) {
                                $n = 0;
                                for($j = 5; $j > 1; $j--) {
                                    if($per_page%$j == 0) {
                                        $n = $j;
                                        break;
                                    }
                                }
                                if($n == 0) {
                                    $n = ceil( $clients_count / 5 / $per_page );
                                } else {
                                    $n = $per_page/$n;
                                }
                            } else {
                                $n = ceil( $clients_count / 5 );
                            }

                            $html = '';
                            $html .= '<ul class="clients_list">';


                            $input_type = ($display_page == 'wpclients_staff_edit') ? 'radio' : 'checkbox';
                            foreach ( $clients as $client ) {
                                if ( $i%$n == 0 && 0 != $i )
                                    $html .= '</ul><ul class="clients_list">';

                                $html .= '<li><label>';
                                $html .= '<input type="' . $input_type . '" name="nfile_client_id[]" onchange="change_checked_count(jQuery(this));" value="' . $client->ID . '" /> ';
                                $html .= $client->user_login;
                                $html .= '</label></li>';

                                $i++;
                            }
                            $html .= '</ul>';
                        } else {
                            $html = __( 'No Clients For Assign.', WPC_CLIENT_TEXT_DOMAIN );
                        }

                        if($type == 'clients_return')
                            return json_encode(array('html' => $html, 'page' => $new_page, 'buttons' => $buttons, 'per_page' => $per_page, 'count' => $clients_count)) ;
                        else
                            echo json_encode(array('html' => $html, 'page' => $new_page, 'buttons' => $buttons, 'per_page' => $per_page, 'count' => $clients_count)) ;
                        break;
                    }
                    case 'circles': case 'circles_return':
                    {

                        if( isset($_POST['order']) && !empty($_POST['order']) ) {
                             $temp_order_array = explode("_", $_POST['order']);
                             if( 2 == count($temp_order_array) ) {
                                 if( ( 'asc' == strtolower($temp_order_array[1]) || 'desc' == strtolower($temp_order_array[1]) ) && ( 'show' == strtolower($temp_order_array[0]) || 'date' == strtolower($temp_order_array[0]) || 'first' == strtolower($temp_order_array[0]) ) ) {
                                     switch($temp_order_array[0]) {
                                        case 'show':
                                            $order_type = "group_name";
                                            break;
                                        case 'date':
                                            $order_type = 'group_id';
                                            break;
                                        case 'first':
                                            $order_type = 'group_name';
                                            break;
                                     }
                                     $order = $temp_order_array[1];
                                 } else {
                                    $order_type = 'group_name';
                                    $order = "ASC";
                                 }
                             } else {
                                $order_type = 'group_name';
                                $order = "ASC";
                             }
                         } else {
                             $order_type = 'group_name';
                             $order = "ASC";
                         }

                        $where = '';
                        if( isset($_POST['search']) && !empty($_POST['search']) ) {
                            $where = " AND group_name LIKE '%".$_POST['search']."%'";
                        }
                        $sql = "SELECT * FROM {$wpdb->prefix}wpc_client_groups WHERE 1=1 $where ORDER BY $order_type $order";
                        $wpdb->query($sql);
                        $sql_count = "SELECT FOUND_ROWS()";
                        $circles_count = $wpdb->get_var($sql_count);

                        if( isset($_POST['order']) && 'first_asc' == $_POST['order'] ) {
                            if( isset( $_POST['already_assinged'] ) && !empty( $_POST['already_assinged'] ) ) {
                                $assigned_users_str = $_POST['already_assinged'];
                                if( $assigned_users_str != 'all' ) {
                                    $assigned_users = " AND group_id IN ($assigned_users_str) ";
                                    $not_assigned_users = " AND group_id NOT IN ($assigned_users_str) ";
                                } else {
                                    $assigned_users = '';
                                    $not_assigned_users = '';
                                }
                            } else {
                                $assigned_users = '';
                                $not_assigned_users = '';
                            }
                            if( isset( $assigned_users ) && !empty( $assigned_users ) ) {
                                $sql = "(SELECT * FROM {$wpdb->prefix}wpc_client_groups WHERE 1=1 $assigned_users $where ORDER BY $order_type $order)
                                        UNION
                                        (SELECT * FROM {$wpdb->prefix}wpc_client_groups WHERE 1=1 $not_assigned_users $where ORDER BY $order_type $order)";
                            }
                        }

                        if ( $circles_count > 0 ) {
                            if($circles_count > $per_page) {
                                $goto = ( isset($_POST['goto']) && !empty($_POST['goto']) ) ? $_POST['goto'] : $goto;
                                switch($goto) {
                                    case 'first':
                                        $offset = 0;
                                        $new_page = 1;
                                        $buttons['first'] = false;
                                        $buttons['prev'] = false;
                                        break;
                                    case 'prev':
                                        $offset = ($cur_page-2)*$per_page;
                                        $new_page = $cur_page - 1;
                                        if($new_page <= 1) {
                                            $buttons['first'] = false;
                                            $buttons['prev'] = false;
                                            $new_page = 1;
                                        }
                                        break;
                                    case 'next':
                                        $last_page = ceil($circles_count/$per_page);
                                        $offset = $cur_page*$per_page;
                                        $new_page = $cur_page + 1;
                                        if($new_page >= $last_page) {
                                            $buttons['next'] = false;
                                            $buttons['last'] = false;
                                            $new_page = $last_page;
                                        }
                                        break;
                                    case 'last':
                                        $last_page = ceil($circles_count/$per_page);
                                        $offset = ($last_page - 1)*$per_page;
                                        $new_page = $last_page;
                                        $buttons['next'] = false;
                                        $buttons['last'] = false;
                                        break;
                                    default:
                                        $offset = 0;
                                        $new_page = 1;
                                        $buttons['first'] = false;
                                        $buttons['prev'] = false;
                                        break;
                                }
                                $limit = " LIMIT $offset, $per_page";
                            } else {
                                $buttons = array('first' => false, 'prev' => false, 'next' => false, 'last' =>false);
                            }
                        } else {
                            $buttons = array('first' => false, 'prev' => false, 'next' => false, 'last' =>false);
                        }

                        $sql = $sql.$limit;
                        $groups = $wpdb->get_results( $sql, "ARRAY_A");

                        if ( is_array( $groups ) && 0 < count( $groups ) ) {

                            $i = 0;
                            if($circles_count > $per_page) {
                                $n = 0;
                                for($j = 5; $j > 1; $j--) {
                                    if($per_page%$j == 0) {
                                        $n = $j;
                                        break;
                                    }
                                }
                                if($n == 0) {
                                    $n = ceil( $circles_count / 5 / $per_page );
                                } else {
                                    $n = $per_page/$n;
                                }
                            } else {
                                $n = ceil( $circles_count / 5 );
                            }
                            $html = '';
                            $html .= '<ul class="clients_list">';

                            foreach ( $groups as $group ) {
                                if ( $i%$n == 0 && 0 != $i )
                                    $html .= '</ul><ul class="clients_list">';

                                $html .= '<li><label>';
                                $html .= '<input type="checkbox" name="nfile_groups_id[]" onchange="change_checked_count(jQuery(this));" value="' . $group['group_id'] . '" /> ';
                                $html .= $group['group_name'];
                                $html .= '</label></li>';

                                $i++;
                            }

                            $html .= '</ul>';
                        } else {
                            $html = __( 'No Client Circles For Assign.', WPC_CLIENT_TEXT_DOMAIN );
                        }
                        if($type == 'circles_return')
                            return json_encode(array('html' => $html, 'page' => $new_page, 'buttons' => $buttons, 'per_page' => $per_page, 'count' => $circles_count)) ;
                        else
                            echo json_encode(array('html' => $html, 'page' => $new_page, 'buttons' => $buttons, 'per_page' => $per_page, 'count' => $circles_count)) ;
                        break;
                    }
                 }
             }
             exit;
         }


         /**
         * decode64 multidimensional array
         **/
         function array_base64_decode($array = array()) {
             if(is_array($array)) {
                 foreach($array as $k=>$val) {
                    if(is_array($val)) {
                        $array[$k] = $this->array_base64_decode($val);
                    } else if(is_string($val)) {
                        $array[$k] = base64_decode(str_replace( "-", "+",$val));
                    }
                 }
             }
            return $array;
         }


         /**
         * get keys from multidimensional array
         **/
         function show_keys($ar) {
            $temp = array();
            foreach ($ar as $k => $v ) {
                $temp[] = $k;
                if (is_array($v)) {
                    $temp = array_merge($temp, $this->show_keys($v));
                }
            }
            return $temp;
        }


         /**
         * Display assign client popup
         **/
        function get_assign_clients_popup( $curent_page = '' ) {
            //var_dump($curent_page);
            $input_type = 'checkbox';
            ?>
            <script type="text/javascript">
                var wpc_current_page = '<?php echo $curent_page; ?>';
            </script>
            <div style="display: none;">
                <div id="popup_block2">
                    <div class="postbox" style="margin-bottom: 0px;">
                        <h3 style="cursor: auto; padding: 8px 0 8px 8px;">
                            <?php
                                switch( $curent_page ) {
                                    case 'wpclients_groups':
                                        _e( 'Assign Client(s) To Circle:', WPC_CLIENT_TEXT_DOMAIN );
                                        break;
                                    case 'add_client_page':
                                        _e( 'Assign Client(s) To Portal Page:', WPC_CLIENT_TEXT_DOMAIN );
                                        break;
                                    default:
                                        _e( 'Assign Client:', WPC_CLIENT_TEXT_DOMAIN );
                                        break;
                                }
                            ?>
                        </h3>
                        <select name="show" class="show">
                            <option value="user_login"><?php _e( 'Username', WPC_CLIENT_TEXT_DOMAIN ) ?></option>
                            <option value="user_nicename"><?php _e( 'Contact Name', WPC_CLIENT_TEXT_DOMAIN ) ?></option>
                            <option value="business_name"><?php _e( 'Business Name', WPC_CLIENT_TEXT_DOMAIN ) ?></option>
                        </select>
                        <select name="order" class="order">
                            <option value="show_asc"><?php _e( 'A to Z', WPC_CLIENT_TEXT_DOMAIN ) ?></option>
                            <option value="show_desc"><?php _e( 'Z to A', WPC_CLIENT_TEXT_DOMAIN ) ?></option>
                            <option value="date_asc"><?php _e( 'Date Added (Recent to Earlier)', WPC_CLIENT_TEXT_DOMAIN ) ?></option>
                            <option value="date_desc"><?php _e( 'Date Added (Earlier to Recent)', WPC_CLIENT_TEXT_DOMAIN ) ?></option>
                            <option value="first_asc"><?php _e( 'Assigned show first', WPC_CLIENT_TEXT_DOMAIN ) ?></option>
                        </select>
                        <input type="text" class="search_field" name="search" value="<?php _e( 'Search', WPC_CLIENT_TEXT_DOMAIN ) ?>" onfocus="if (this.value=='<?php _e( 'Search', WPC_CLIENT_TEXT_DOMAIN ) ?>') this.value='';" onblur="if (this.value==''){this.value='<?php _e( 'Search', WPC_CLIENT_TEXT_DOMAIN ) ?>'}" />
                        <?php if( $input_type == 'checkbox' ) { ?>
                            <span class="description" style="padding-left: 8px;">
                                <input type="checkbox" class="select_all_at_page" name="select_all_at_page2" id="select_all_at_page2" value="1" />
                                <label for="select_all_at_page2"><?php _e( 'Select all at this page.', WPC_CLIENT_TEXT_DOMAIN ) ?></label>
                            </span>
                            <span class="description" style="padding-left: 8px;">
                                <input type="checkbox" class="select_all2" name="select_all4" id="select_all4" value="1" />
                                <label for="select_all4"><?php _e( 'Select All.', WPC_CLIENT_TEXT_DOMAIN ) ?></label>
                            </span>
                        <?php } ?>
                        <div class="inside">
                            <table>
                                <tr>
                                    <td>
                                        <?php
                                            $popup_data_json = $this->ajax_get_popup_pagination_data('clients_return');
                                            $popup_data_array = json_decode($popup_data_json, true);
                                            echo $popup_data_array['html'];
                                         ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div style="clear: both; text-align: center; position: relative;">
                            <?php if($popup_data_array['count'] > $popup_data_array['per_page']) { ?>
                                <a href="javascript:void(0);" rel="first" class="pagination_links"><<</a>&nbsp;&nbsp;
                                <a href="javascript:void(0);" rel="prev" class="pagination_links"><</a>&nbsp;&nbsp;
                                <span class="page_num">1</span>&nbsp;&nbsp;
                                <a href="javascript:void(0);" rel="next" class="pagination_links">></a>&nbsp;&nbsp;
                                <a href="javascript:void(0);" rel="last" class="pagination_links">>></a>
                            <?php } ?>

                            <?php if( $input_type == 'checkbox' ) { ?>
                                <div class="popup_statistic">
                                    <span class="total_count">0</span> item(s). <span class="selected_count">0</span> item(s) was selected.
                                </div>
                            <?php } ?>
                        </div>
                        <div style="clear: both; height: 15px;">
                            &nbsp;
                        </div>
                        <div style="clear: both; text-align: center;">
                            <input type="hidden" name="input_ref" class="input_ref" />
                            <input type="button" name="Ok" value="<?php _e( 'Ok', WPC_CLIENT_TEXT_DOMAIN ) ?>" class="ok_popup2 button-primary" />
                            <input type="button" name="cancel" class="cancel_popup2 button" value="<?php _e( 'Cancel', WPC_CLIENT_TEXT_DOMAIN ) ?>" />
                        </div>
                    </div>
                </div>

            </div>

        <?php

        }


         /**
         * Display assign circles popup
         **/
        function get_assign_circles_popup( $curent_page = '' ) {

        ?>
            <script type="text/javascript">
                var wpc_current_page = '<?php echo $curent_page; ?>';
            </script>
            <div style="display: none;">
                <div id="circles_popup_block" >
                    <div class="postbox" style="margin-bottom: 0px;">
                        <h3 style="cursor: auto; padding: 8px 0 8px 8px;">
                            <?php
                                switch( $curent_page ) {
                                    case 'add_client':
                                        _e( 'Assign Circle(s) To Client:', WPC_CLIENT_TEXT_DOMAIN );
                                        break;
                                    case 'add_client_page':
                                        _e( 'Assign Circle(s) To Portal Page:', WPC_CLIENT_TEXT_DOMAIN );
                                        break;
                                    default:
                                        _e( 'Assign Circle(s):', WPC_CLIENT_TEXT_DOMAIN );
                                        break;
                                }
                            ?>
                        </h3>
                        <input type="text" class="search_field" name="search" value="<?php _e( 'Search', WPC_CLIENT_TEXT_DOMAIN ) ?>" onfocus="if (this.value=='<?php _e( 'Search', WPC_CLIENT_TEXT_DOMAIN ) ?>') this.value='';" onblur="if (this.value==''){this.value='<?php _e( 'Search', WPC_CLIENT_TEXT_DOMAIN ) ?>'}" />
                        <select name="order" class="order">
                            <option value="show_asc"><?php _e( 'A to Z', WPC_CLIENT_TEXT_DOMAIN ) ?></option>
                            <option value="show_desc"><?php _e( 'Z to A', WPC_CLIENT_TEXT_DOMAIN ) ?></option>
                            <option value="date_asc"><?php _e( 'Date Added (Recent to Earlier)', WPC_CLIENT_TEXT_DOMAIN ) ?></option>
                            <option value="date_desc"><?php _e( 'Date Added (Earlier to Recent)', WPC_CLIENT_TEXT_DOMAIN ) ?></option>
                            <option value="first_asc"><?php _e( 'Assigned show first', WPC_CLIENT_TEXT_DOMAIN ) ?></option>
                        </select>
                        <span class="description" style="padding-left: 8px;">
                            <input type="checkbox" class="select_all_at_page" name="select_all_at_page_circles" id="select_all_at_page_circles" value="1" />
                            <label for="select_all_at_page_circles"><?php _e( 'Select all at this page.', WPC_CLIENT_TEXT_DOMAIN ) ?></label>
                        </span>
                        <span class="description" style="padding-left: 8px;">
                            <input type="checkbox" class="select_all2" name="select_all2" id="select_all3" value="1" />
                            <label for="select_all3"><?php _e( 'Select All.', WPC_CLIENT_TEXT_DOMAIN ) ?></label>
                        </span>
                        <div class="inside">
                            <table>
                                <tr>
                                    <td>
                                        <?php
                                            $popup_data_json = $this->ajax_get_popup_pagination_data('circles_return');
                                            $popup_data_array = json_decode($popup_data_json, true);
                                            echo $popup_data_array['html'];
                                         ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div style="clear: both; text-align: center; position: relative;">
                            <?php if($popup_data_array['count'] > $popup_data_array['per_page']) { ?>
                                <a href="javascript:void(0);" rel="first" class="pagination_links"><<</a>&nbsp;&nbsp;
                                <a href="javascript:void(0);" rel="prev" class="pagination_links"><</a>&nbsp;&nbsp;
                                <span class="page_num">1</span>&nbsp;&nbsp;
                                <a href="javascript:void(0);" rel="next" class="pagination_links">></a>&nbsp;&nbsp;
                                <a href="javascript:void(0);" rel="last" class="pagination_links">>></a>
                            <?php } ?>
                            <div class="popup_statistic">
                                <span class="total_count">0</span> item(s). <span class="selected_count">0</span> item(s) was selected.
                            </div>
                        </div>
                        <div style="clear: both; height: 15px;">
                            &nbsp;
                        </div>
                        <div style="clear: both; text-align: center;">
                            <input type="hidden" name="input_ref" class="input_ref" />
                            <input type="button" name="Ok" value="<?php _e( 'Ok', WPC_CLIENT_TEXT_DOMAIN ) ?>" class="ok_popup2 button-primary" />
                            <input type="button" name="cancel" class="cancel_popup2 button" value="<?php _e( 'Cancel', WPC_CLIENT_TEXT_DOMAIN ) ?>" />
                        </div>
                    </div>
                </div>

            </div>
        <?php

        }


        /*
        * Create DB tables
        */
        function creating_db() {
            global $wpdb, $clients_page;

            //add table for Client Circles
            $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wpc_client_groups (
                `group_id` int(11) NOT NULL auto_increment,
                `group_name` varchar(255) NOT NULL,
                `auto_select` varchar(1) NULL,
                `auto_add_clients` varchar(1) NULL,
                PRIMARY KEY (`group_id`)
            ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

            $wpdb->query( $sql );


            //add table for Redirects
            $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wpc_client_login_redirects (
                `rul_type` enum('user','role','level','all') NOT NULL,
                `rul_value` varchar(255) NOT NULL default '',
                `rul_url` LONGTEXT NOT NULL,
                `rul_url_logout` LONGTEXT NOT NULL default '',
                `rul_order` int(2) NOT NULL default '0',
                UNIQUE KEY `rul_type` (`rul_type`,`rul_value`)
                )";

            $wpdb->query( $sql );


            //add table for Client Circle's CLIENTS
            $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wpc_client_group_clients (
                `group_id` int(11) NOT NULL,
                `client_id` int(11) NOT NULL
            ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

            $wpdb->query( $sql );


            $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wpc_client_clients_page (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                pagename tinytext NOT NULL,
                template tinytext NOT NULL,
                users tinytext NOT NULL,
                PRIMARY KEY  (id)
            ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

            $wpdb->query( $sql );

            $sql = 'CREATE TABLE IF NOT EXISTS  ' . $wpdb->prefix . 'wpc_client_login_redirects (
                `rul_type` enum(\'user\',\'role\',\'level\',\'all\') NOT NULL,
                `rul_value` varchar(255) NOT NULL default \'\',
                `rul_url` LONGTEXT NOT NULL,
                `rul_url_logout` LONGTEXT NULL,
                `rul_order` int(2) NOT NULL default \'0\',
                UNIQUE KEY `rul_type` (`rul_type`,`rul_value`)
            )';

            $wpdb->query( $sql );

        }


        /**
         * Update rewrite_rules
         */
        function update_rewrite_rules() {
            global $wp_roles;

            //remore old role
            $wp_roles->remove_role( "pcc_client" );

            //remore role for update capabilities
            $wp_roles->remove_role( "wpc_client" );
            //add role for clients
            $wp_roles->add_role( "wpc_client", 'WPC Client', array(
                    'read' => true
            ) );

            $capability_map = array(
                'read_clientpages'               => 'View Portal Page.',
                'publish_clientpages'            => 'Add Portal Page.',
                'edit_clientpages'               => 'Edit Portal Pages.',
                'edit_published_clientpages'     => 'Edit Portal Pages.',
                'delete_published_clientpages'   => 'Delete Portal Pages.',
                'edit_others_clientpages'        => 'Edit others Portal Pages.',
                'delete_others_clientpages'      => 'Delete others Portal Pages.',
            );

            //set capability for Portal Pages to Admin
            foreach ( array_keys( $capability_map ) as $capability ) {
                $wp_roles->add_cap( 'administrator', $capability );
            }


            //update rewrite rules
            flush_rewrite_rules( false );
            $this->creating_db();
        }


         /**
         * Adding a new rule
         **/
        function insert_rewrite_rules( $rules ) {
            $newrules = array();

            //portal page
            $newrules[wpc_client_get_slug( 'portal_page_id', false, false ) . '/(.+?)/?$'] = 'index.php?wpc_page=portal_page&wpc_page_value=$matches[1]';

            //preview for HUB
            $newrules[wpc_client_get_slug( 'hub_page_id', false, false ) . '/(\d*)/?$'] = 'index.php?wpc_page=hub_preview&wpc_page_value=$matches[1]';

            return $newrules + $rules;
        }


        /**
         * Adding the var for my-hub page
         **/
        function insert_query_vars( $vars ) {

            array_push( $vars, 'wpc_page' );
            array_push( $vars, 'wpc_page_value' );

            return $vars;
        }


        /**
         * Change query for show wpc pages
         **/
        function query_for_wpc_client_pages( $q ) {
            global $wp_query, $wpdb, $current_user;


                //for portal page
                if ( isset( $wp_query->query_vars['wpc_page'] ) && 'portal_page' == $wp_query->query_vars['wpc_page'] ) {
                    if ( is_user_logged_in() ) {

                        $wpc_settings = get_option( 'wpc_settings' );

                        if ( isset( $wpc_settings['pages'] ) && 0 < $wpc_settings['pages']['portal_page_id'] ) {
                            $q = "SELECT * FROM {$wpdb->prefix}posts WHERE 1=1 AND ID = '" . $wpc_settings['pages']['portal_page_id'] . "' AND post_type = 'page' ORDER BY post_date DESC ";
                        }
                    } else {
                        wp_redirect( $this->get_login_url() );
                        exit;
                    }

                }
                //for edit portal page
                elseif ( $q == $wp_query->request ) {

                    //for HUB previev
                    if ( isset( $wp_query->query_vars['wpc_page'] ) && 'hub_preview' == $wp_query->query_vars['wpc_page'] ) {
                        if ( is_user_logged_in() ) {
                            if ( current_user_can( 'administrator' ) ) {
                                 //for admin
                                $wpc_settings = get_option( 'wpc_settings' );

                                if ( isset( $wpc_settings['pages'] ) && 0 < $wpc_settings['pages']['hub_page_id'] ) {
                                    $q = "SELECT * FROM {$wpdb->prefix}posts WHERE 1=1 AND ID = '" . $wpc_settings['pages']['hub_page_id'] . "' AND post_type = 'page' ORDER BY post_date DESC ";
                                }
                            } else {
                                //for clients
                                wp_redirect( wpc_client_get_slug( 'hub_page_id' ) );
                                exit;
                            }
                        }  else {
                            wp_redirect( $this->get_login_url() );
                            exit;
                        }
                    }
                }
            return $q;
        }


        /**
         * Protect Cleint page and HUB from not logged user and Search Engine
         */
        function filter_posts( $posts ) {
            global $wp_query, $wpdb;

            $filtered_posts = array();

            //if empty
            if ( empty( $posts ) )
                return $posts;

            //other filter

            $wpc_settings = get_option( 'wpc_settings' );

            foreach( $posts as $post ) {


                //add no follow, no index on plugin pages
                if ( isset( $wpc_settings['pages'] )
                    && is_array( $wpc_settings['pages'] )
                    && in_array( $post->ID, array_values ( $wpc_settings['pages'] ) ) ) {

                    add_action( 'wp_head', array( &$this, 'add_meta_to_plugin_pages' ), 99 );
                }

                //for logout
                if ( isset( $wpc_settings['pages'] ) && $post->ID == $wpc_settings['pages']['login_page_id'] ) {
                    //make logout
                    if ( isset( $_REQUEST['logout'] ) && 'true' == $_REQUEST['logout'] ) {
                        wp_logout();
                    }
                }


                //for portal page
                if ( isset( $wpc_settings['pages'] ) && $post->ID == $wpc_settings['pages']['portal_page_id'] ) {
                    if ( is_user_logged_in() ) {
                        if ( isset( $wp_query->query_vars['wpc_page_value'] ) && '' != $wp_query->query_vars['wpc_page_value'] ) {

                            $new_post = get_page_by_path( $wp_query->query_vars['wpc_page_value'], object, 'clientspage' );
                            if ( $new_post ) {

                                $user_id = get_current_user_id();

                                $user_ids       = get_post_meta( $new_post->ID, 'user_ids', true );
                                $groups_id      = get_post_meta( $new_post->ID, 'groups_id', true ) ;

                                //get clients from Client Circles
                                if ( is_array( $groups_id ) && 0 < count( $groups_id ) )
                                    foreach( $groups_id as $group_id ) {
                                        $user_ids = array_merge( $user_ids, $this->get_group_clients_id( $group_id ) );
                                    }

                                if ( is_array( $user_ids ) && 0 < count( $user_ids ) )
                                    $user_ids = array_unique( $user_ids );

                                //filter posts for manager
                                if ( ( !empty( $user_ids ) && in_array( $user_id, $user_ids ) ) || current_user_can( 'administrator' ) ) {

                                    //replace placeholders in content
                                    if ( isset( $new_post->post_content ) ) {
                                        $args = array( 'client_id' => $user_id );
                                        $new_post->post_content = $this->replace_placeholders( $new_post->post_content, $args, 'portal_page' );
                                    }

                                    $wp_query->is_page      = true;
                                    $wp_query->is_home      = false;
                                    $wp_query->is_singular  = true;

                                    //set title and content for PP
                                    $post->post_title   = $new_post->post_title;
                                    $post->post_content = $new_post->post_content;

                                    //set title for PP needs for some themes
                                    $this->current_plugin_page['title'] = $new_post->post_title;
                                    //replace title for PP needs for some themes
                                    add_filter( 'the_title', array( &$this, 'change_portal_page_title' ), 99, 2 );

                                    $filtered_posts[] = $post;
                                    continue;
                                }

                                wp_redirect( wpc_client_get_slug( 'error_page_id' ) );
                                exit;
                            }
                        }
                    }
                    continue;
                }
                elseif ( isset( $wpc_settings['pages'] ) && $post->ID == $wpc_settings['pages']['hub_page_id'] ) {
                    if ( is_user_logged_in() ) {

                        $wpc_cl_hubpage_id = 0;

                        //for hub preview
                        if ( isset( $wp_query->query_vars['wpc_page'] ) && 'hub_preview' == $wp_query->query_vars['wpc_page'] && current_user_can( 'administrator' ) ) {
                            if ( isset( $wp_query->query_vars['wpc_page_value'] ) && '' != $wp_query->query_vars['wpc_page_value'] ) {
                                $client = get_users( array( 'role' => 'wpc_client', 'meta_key' => 'wpc_cl_hubpage_id', 'meta_value' => $wp_query->query_vars['wpc_page_value'], 'fields' => 'ID' ) );

                                if ( isset( $client[0] ) && $client[0] ) {
                                    $client_id          = $client[0];
                                    $wpc_cl_hubpage_id  = $wp_query->query_vars['wpc_page_value'];
                                }
                            }
                        } else {
                            $client_id = get_current_user_id();

                            $wpc_cl_hubpage_id = get_user_meta( $client_id, 'wpc_cl_hubpage_id', true );
                        }

                        if ( 0 < $wpc_cl_hubpage_id ) {
                            $hub_page = get_post( $wpc_cl_hubpage_id );
                            if ( $hub_page ) {

                                //set title for PP needs for some themes
                                $this->current_plugin_page['post_id']   = $post->ID;
                                $this->current_plugin_page['hub_id']    = $wpc_cl_hubpage_id;
                                $this->current_plugin_page['title']     = $hub_page->post_title;

                                //change HUB title
                                if ( !isset( $wpc_settings['show_hub_title'] ) || 'yes' == $wpc_settings['show_hub_title'] ) {
                                    //set title and content for PP
                                    $post->post_title = $hub_page->post_title;
                                    //replace title for HUB needs for some themes
                                    add_filter( 'the_title', array( &$this, 'change_hub_page_title' ), 99, 2 );
                                }

                            }
                        }

                        $filtered_posts[] = $post;
                        continue;
                    } else {
                        do_action( 'wp_client_redirect', $this->get_login_url() );
                        exit;
                    }
                }

                //add all other posts
                $filtered_posts[] = $post;
            }


            return $filtered_posts;
        }


        /*
        * Filter the template path to page{}.php templates.
        */
        function get_page_template( $template ) {
            global $wp_query;

            if ( isset( $wp_query->query_vars['wpc_page'] ) && '' != $wp_query->query_vars['wpc_page'] ) {
                if ( file_exists( get_template_directory() . "/page-wpc_{$wp_query->query_vars['wpc_page']}.php" ) )
                    return get_template_directory() . "/page-wpc_{$wp_query->query_vars['wpc_page']}.php";
            }

            return $template;
        }

        /**
         * filter for change title for portal page (for some themes)
         */
        function change_portal_page_title( $title, $id ) {
            $wpc_settings = get_option( 'wpc_settings' );
            if ( isset( $wpc_settings['pages'] ) && $id == $wpc_settings['pages']['portal_page_id'] ) {
                return $this->current_plugin_page['title'];
            }
            return $title;
        }


        /**
         * filter for change title for HUB page (for some themes)
         */
        function change_hub_page_title( $title, $id ) {
            $wpc_settings = get_option( 'wpc_settings' );
            if ( isset( $wpc_settings['pages'] ) && $id == $wpc_settings['pages']['hub_page_id'] ) {
                return $this->current_plugin_page['title'];
            }
            return $title;
        }


        /**
         * Gen tabs manu
         */
        function gen_tabs_menu( $page = 'clients' ) {
            global $wpdb;

            $tabs = '';
            $active = '';

            switch( $page ) {
                case 'clients':
                    $not_approved_clients   = get_users( array( 'role' => 'wpc_client', 'meta_key' => 'to_approve', 'fields' => 'ID', ) );
                    $not_approved_staff     = get_users( array( 'role' => 'wpc_client_staff', 'meta_key' => 'to_approve', 'fields' => 'ID', ) );

                    $active = ( !isset( $_GET['tab'] ) && isset( $_GET['page'] ) && 'wpclients' == $_GET['page'] ) ? 'class="active"' : '';
                    $tabs .= '<li id="clients" ' . $active . ' ><a href="admin.php?page=wpclients" >' . __( 'Clients', WPC_CLIENT_TEXT_DOMAIN ) . '</a></li>';

                    if ( current_user_can( 'wpc_add_clients' ) || current_user_can( 'administrator' ) ) {
                        $active = ( isset( $_GET['tab'] ) && 'add_client' == $_GET['tab'] ) ? 'class="active"' : '';
                        $tabs .= '<li id="add_client" ' . $active . ' ><a href="admin.php?page=wpclients&tab=add_client" >' . __( 'Add Client', WPC_CLIENT_TEXT_DOMAIN ) . '</a></li>';
                    }

                     //just for admin
                    if ( current_user_can( 'administrator' ) ) {
                        $active = ( isset( $_GET['tab'] ) && 'approve' == $_GET['tab'] ) ? 'class="active ' : 'class="';
                        $tabs .= '<li id="approve" ' . $active . 'pro_version" ><a href="admin.php?page=wpclients&tab=approve" >' . __( 'Approve Clients', WPC_CLIENT_TEXT_DOMAIN ). ' ('. count( $not_approved_clients ) . ')<span class="pro">  Pro</span></a></li>';

                        $active = ( isset( $_GET['tab'] ) && 'convert' == $_GET['tab'] ) ? 'class="active ' : 'class="';
                        $tabs .= '<li id="convert" ' . $active . 'pro_version" ><a href="admin.php?page=wpclients&tab=convert" >' . __( 'Convert Users', WPC_CLIENT_TEXT_DOMAIN ). '<span class="pro">  Pro</span></a></li>';

                        $active = ( isset( $_GET['tab'] ) && 'staff' == $_GET['tab'] ) ? 'class="active ' : 'class="';
                        $tabs .= '<li id="staff" ' . $active . 'pro_version" ><a href="admin.php?page=wpclients&tab=staff" >' . __( 'Clients Staff', WPC_CLIENT_TEXT_DOMAIN ) . '<span class="pro">  Pro</span></a></li>';

                        $active = ( isset( $_GET['tab'] ) && 'staff_approve' == $_GET['tab'] ) ? 'class="active ' : 'class="';
                        $tabs .= '<li id="staff_approve" ' . $active . 'pro_version" ><a href="admin.php?page=wpclients&tab=staff_approve" >' . __( 'Clients Staff Approve', WPC_CLIENT_TEXT_DOMAIN ) . ' ('. count( $not_approved_staff ) . ')<span class="pro">  Pro</span></a></li>';

                        $active = ( isset( $_GET['tab'] ) && 'staff_add' == $_GET['tab'] ) ? 'class="active ' : 'class="';
                        $tabs .= '<li id="staff_add" ' . $active . 'pro_version" ><a href="admin.php?page=wpclients&tab=staff_add" >' . __( 'Clients Staff Add', WPC_CLIENT_TEXT_DOMAIN ) . '<span class="pro">  Pro</span></a></li>';

                        $active = ( isset( $_GET['tab'] ) && 'custom_fields' == $_GET['tab'] ) ? 'class="active ' : 'class="';
                        $tabs .= '<li id="custom_fields" ' . $active . 'pro_version" ><a href="admin.php?page=wpclients&tab=custom_fields" >' . __( 'Custom Fields', WPC_CLIENT_TEXT_DOMAIN ) . '<span class="pro">  Pro</span></a></li>';
                    }
                    break;
                case 'managers':
                    if ( current_user_can( 'administrator' ) ) {
                         $active = ( !isset( $_GET['tab'] ) && isset( $_GET['page'] ) && 'wpclients_managers' == $_GET['page'] ) ? 'class="active ' : 'class="';
                         $tabs .= '<li id="managers" ' . $active . 'pro_version" ><a href="admin.php?page=wpclients_managers" >' . __( 'Managers', WPC_CLIENT_TEXT_DOMAIN ) . '<span class="pro">  Pro</span></a></li>';

                         $active = ( isset( $_GET['tab'] ) && 'add' == $_GET['tab'] ) ? 'class="active ' : 'class="';
                         $tabs .= '<li id="add" ' . $active . 'pro_version" ><a href="admin.php?page=wpclients_managers&tab=add" >' . __( 'Add Manager', WPC_CLIENT_TEXT_DOMAIN ) . '<span class="pro">  Pro</span></a></li>';
                    }
                    break;
                case 'file_sharing':

                    $active = ( !isset( $_GET['tab'] ) && isset( $_GET['page'] ) && 'wpclients_files' == $_GET['page'] ) ? 'class="active ' : 'class="';
                    $tabs .= '<li id="files" ' . $active . 'pro_version" ><a href="admin.php?page=wpclients_files" >' . __( 'Files', WPC_CLIENT_TEXT_DOMAIN ) . '<span class="pro">  Pro</span></a></li>';


                    if ( current_user_can( 'administrator' ) ) {
                         $active = ( isset( $_GET['tab'] ) && 'cat' == $_GET['tab'] ) ? 'class="active ' : 'class="';
                         $tabs .= '<li id="files_cat" ' . $active . 'pro_version" ><a href="admin.php?page=wpclients_files&tab=cat" >' . __( 'File Categories', WPC_CLIENT_TEXT_DOMAIN ) . '<span class="pro">  Pro</span></a></li>';
                    }

                    break;
                case 'templates':

                    if ( current_user_can( 'administrator' ) ) {

                        $active = ( !isset( $_GET['tab'] ) && isset( $_GET['page'] ) && 'wpclients_templates' == $_GET['page'] ) ? 'class="active ' : 'class="';
                        $tabs .= '<li id="hub_template" ' . $active . 'pro_version" ><a href="admin.php?page=wpclients_templates" >' . __( 'Hub Page Templates', WPC_CLIENT_TEXT_DOMAIN ) . '<span class="pro">  Pro</span></a></li>';

                        $active = ( isset( $_GET['tab'] ) && 'portal' == $_GET['tab'] ) ? 'class="active ' : 'class="';
                        $tabs .= '<li id="portal_template" ' . $active . 'pro_version" ><a href="admin.php?page=wpclients_templates&tab=portal" >' . __( 'Portal Page Templates', WPC_CLIENT_TEXT_DOMAIN ) . '<span class="pro">  Pro</span></a></li>';

                        $active = ( isset( $_GET['tab'] ) && 'email' == $_GET['tab'] ) ? 'class="active ' : 'class="';
                        $tabs .= '<li id="email_template" ' . $active . 'pro_version" ><a href="admin.php?page=wpclients_templates&tab=email" >' . __( 'Email Templates', WPC_CLIENT_TEXT_DOMAIN ) . '<span class="pro">  Pro</span></a></li>';

                        $active = ( isset( $_GET['tab'] ) && 'shortcode' == $_GET['tab'] ) ? 'class="active ' : 'class="';
                        $tabs .= '<li id="shortcode_template" ' . $active . 'pro_version" ><a href="admin.php?page=wpclients_templates&tab=shortcode" >' . __( 'Shortcode Templates', WPC_CLIENT_TEXT_DOMAIN ) . '<span class="pro">  Pro</span></a></li>';

                    }
                    break;
                case 'settings':

                    if ( current_user_can( 'administrator' ) ) {

                        $active = ( !isset( $_GET['tab'] ) && isset( $_GET['page'] ) && 'wpclients_settings' == $_GET['page'] ) ? 'class="active"' : '';
                        $tabs .= '<li id="general" ' . $active . ' ><a href="admin.php?page=wpclients_settings" >' . __( 'General', WPC_CLIENT_TEXT_DOMAIN ) . '</a></li>';

                        $active = ( isset( $_GET['tab'] ) && 'b_info' == $_GET['tab'] ) ? 'class="active"' : '';
                        $tabs .= '<li id="b_info" ' . $active . ' ><a href="admin.php?page=wpclients_settings&tab=b_info" >' . __( 'Business Info', WPC_CLIENT_TEXT_DOMAIN ) . '</a></li>';

                        $active = ( isset( $_GET['tab'] ) && 'pages' == $_GET['tab'] ) ? 'class="active"' : '';
                        $tabs .= '<li id="pages" ' . $active . ' ><a href="admin.php?page=wpclients_settings&tab=pages" >' . __( 'Pages', WPC_CLIENT_TEXT_DOMAIN ) . '</a></li>';

                        $active = ( isset( $_GET['tab'] ) && 'capabilities' == $_GET['tab'] ) ? 'class="active ' : 'class="';
                        $tabs .= '<li id="capabilities" ' . $active . 'pro_version" ><a href="admin.php?page=wpclients_settings&tab=capabilities" >' . __( 'Capabilities', WPC_CLIENT_TEXT_DOMAIN ) . '<span class="pro">  Pro</span></a></li>';

                        $active = ( isset( $_GET['tab'] ) && 'clogin' == $_GET['tab'] ) ? 'class="active"' : '';
                        $tabs .= '<li id="clogin" ' . $active . ' ><a href="admin.php?page=wpclients_settings&tab=clogin" >' . __( 'Custom Login', WPC_CLIENT_TEXT_DOMAIN ) . '</a></li>';

                        $active = ( isset( $_GET['page'] ) && 'xyris-login-logout' == $_GET['page'] ) ? 'class="active"' : '';
                        $tabs .= '<li id="redirects" ' . $active . ' ><a href="admin.php?page=xyris-login-logout" >' . __( 'Login/Logout Redirects', WPC_CLIENT_TEXT_DOMAIN ) . '</a></li>';

                        $active = ( isset( $_GET['tab'] ) && 'skins' == $_GET['tab'] ) ? 'class="active"' : '';
                        $tabs .= '<li id="skins" ' . $active . ' ><a href="admin.php?page=wpclients_settings&tab=skins" >' . __( 'Skins', WPC_CLIENT_TEXT_DOMAIN ) . '</a></li>';

                        $active = ( isset( $_GET['tab'] ) && 'alerts' == $_GET['tab'] ) ? 'class="active"' : '';
                        $tabs .= '<li id="alerts" ' . $active . ' ><a href="admin.php?page=wpclients_settings&tab=alerts" >' . __( 'Login Alerts', WPC_CLIENT_TEXT_DOMAIN ) . '</a></li>';

                        $active = ( isset( $_GET['tab'] ) && 'addons' == $_GET['tab'] ) ? 'class="active ' : 'class="';
                        $tabs .= '<li id="addons" ' . $active . 'pro_version" ><a href="admin.php?page=wpclients_settings&tab=addons" >' . __( 'Addons', WPC_CLIENT_TEXT_DOMAIN ) . '<span class="pro">  Pro</span></a></li>';

                        if ( !$this->plugin['hide_about_tab'] ) {

                            $active = ( isset( $_GET['tab'] ) && 'about' == $_GET['tab'] ) ? 'class="active"' : '';
                            $tabs .= '<li id="about" ' . $active . ' ><a href="admin.php?page=wpclients_settings&tab=about" >' . __( 'About', WPC_CLIENT_TEXT_DOMAIN ) . '</a></li>';

                        }

                    }
                    break;
            }

            return $tabs;
        }

        /**
         * Load translate textdomain file.
         */
        function load_textdomain() {
            load_plugin_textdomain( WPC_CLIENT_TEXT_DOMAIN, false, dirname( 'wp-client-client-portals-file-upload-invoices-billing/wp-client-lite.php' ) . '/languages/' );
        }


        /**
         * Load translate textdomain file.
         */
        function add_action_links( $links ) {
             $links['delete'] = '<a onclick="return confirm(\'' . __( 'Are you sure? You will lose all Clients, HUB Pages, Portal Pages, Private Messages & Files', WPC_CLIENT_TEXT_DOMAIN ) . '\');"  href="' . get_admin_url() . 'plugins.php?action=wpclient_uninstall" class="delete" >Uninstall</a>';
             return $links;
        }


        /**
         * Remove remote code from session after deactivate the plugin
         */
        function deactivation_func() {

            delete_option( 'wpc_run_activated_functions' );
        }


        /**
         * Client Login from widget
         */
        function client_login_from_() {

            if ( !is_user_logged_in() ) {

                if ( isset( $_POST['wpclient_login_button'] ) ) {
                    //login from widget
                    if ( !isset( $_POST['wpclient_login'] ) || '' == $_POST['wpclient_login'] ) {
                        $GLOBALS['wpclient_login_msg'] = __( 'Please enter your username!', WPC_CLIENT_TEXT_DOMAIN );
                        return;
                    }

                    if ( !isset( $_POST['wpclient_pass'] ) || '' == $_POST['wpclient_pass'] ) {
                        $GLOBALS['wpclient_login_msg'] = __( 'Please enter your password!', WPC_CLIENT_TEXT_DOMAIN );
                        return;
                    }

                    $args = array(
                        'user_login'    => $_POST['wpclient_login'],
                        'user_password' => $_POST['wpclient_pass'],
                        'remember'      => false,
                    );

                    $user = wp_signon( $args );

                    if ( isset( $user->errors ) ) {

                        if ( isset( $user->errors['invalid_username'] ) || isset( $user->errors['incorrect_password'] ) ) {
                            $GLOBALS['wpclient_login_msg'] = __( 'Invalid Login or Password!', WPC_CLIENT_TEXT_DOMAIN );
                            return;
                        } else {
                            return;
                        }
                    }

                    if ( user_can( $user, 'wpc_client' ) && !user_can( $user, 'manage_network_options' ) ) {
                        //redirect for client
                        $redirect_to = $this->login_redirect_rules( wpc_client_get_hub_link( $user->ID ) , '', $user );
                        wp_redirect( $redirect_to );
                    } elseif ( user_can( $user, 'wpc_client_staff' ) && !user_can( $user, 'manage_network_options' ) ) {
                        //redirect for staff
                        $client_id = get_user_meta( $user->ID, 'parent_client_id', true );
                        $redirect_to = $this->login_redirect_rules( wpc_client_get_hub_link( $client_id ), '', $user );
                        wp_redirect( $redirect_to );
                    } else {
                        //redirect for all
                        wp_redirect( admin_url() );
                    }
                    exit;
                } elseif ( isset( $_POST['wpc_login'] ) && 'login_form' == $_POST['wpc_login'] ) {
                    //login from login form
                    if ( !isset( $_POST['log'] ) || '' == $_POST['log'] ) {
                        $GLOBALS['wpclient_login_msg'] = __( 'Please enter your username!', WPC_CLIENT_TEXT_DOMAIN );
                        return;
                    }

                    if ( !isset( $_POST['pwd'] ) || '' == $_POST['pwd'] ) {
                        $GLOBALS['wpclient_login_msg'] = __( 'Please enter your password!', WPC_CLIENT_TEXT_DOMAIN );
                        return;
                    }

                    $args = array(
                        'user_login'    => isset( $_POST['log'] ) ? $_POST['log'] : '',
                        'user_password' => isset( $_POST['pwd'] ) ? $_POST['pwd'] : '',
                        'remember'      => isset( $_POST['rememberme'] ) ? $_POST['rememberme'] : false,
                    );

                    $user = wp_signon( $args );

                    if ( isset( $user->errors ) ) {

                        if ( isset( $user->errors['invalid_username'] ) || isset( $user->errors['incorrect_password'] ) ) {
                            $GLOBALS['wpclient_login_msg'] = __( 'Invalid Login or Password!', WPC_CLIENT_TEXT_DOMAIN );
                            return;
                        } else {
                            return;
                        }
                    }

                    $redirect_to = apply_filters( 'login_redirect', admin_url(), '', $user );
                    wp_safe_redirect( $redirect_to );
                    exit();

                }
            }
        }


        /**
         * Get date/time with timezone
         */
        function date_timezone( $format, $timestamp ) {
            $gmt_offset =  get_option( 'gmt_offset' );
            if ( false === $gmt_offset ) {
                $timestamp = $timestamp;
            } else {
                $timestamp = $timestamp + $gmt_offset * 3600;
            }
            return date( $format, $timestamp );
        }

        /**
         * Get template for Portal/HUB Pages from the assigned WP pages
         */
        function get_clientpage_template( $template ) {
            global $post;

/*

            if ( 'clientspage' == $post->post_type ) {
                $wpc_settings = get_option( 'wpc_settings' );
                if ( isset( $wpc_settings['pages']['portal_page_id'] ) && 0 < $wpc_settings['pages']['portal_page_id'] ) {
                    $page_template = get_post_meta( $wpc_settings['pages']['portal_page_id'], '_wp_page_template', true );
                    if ( $page_template ) {
                        if ( file_exists( get_template_directory() . "/{$page_template}" ) )
                            return get_template_directory() . "/{$page_template}";
                        else
                            return '';
                    }
                }     __use_same_as_portal_page
            } else   */



            if ( 'clientspage' == $post->post_type ) {
                    $page_template = get_post_meta( $post->ID, '_wp_page_template', true );

                    if ( !$page_template || '__use_same_as_portal_page' == $page_template ) {
                        $wpc_settings = get_option( 'wpc_settings' );
                        if ( isset( $wpc_settings['pages']['portal_page_id'] ) && 0 < $wpc_settings['pages']['portal_page_id'] ) {
                            $page_template = get_post_meta( $wpc_settings['pages']['portal_page_id'], '_wp_page_template', true );
                            if ( $page_template ) {
                                if ( file_exists( get_template_directory() . "/{$page_template}" ) )
                                    return get_template_directory() . "/{$page_template}";
                            }
                        }
                        return '';
                    } else {
                        if ( file_exists( get_template_directory() . "/{$page_template}" ) )
                            return get_template_directory() . "/{$page_template}";
                        else
                            return '';
                    }
            }
            elseif ( 'hubpage' == $post->post_type ) {
                $wpc_settings = get_option( 'wpc_settings' );
                if ( isset( $wpc_settings['pages']['hub_page_id'] ) && 0 < $wpc_settings['pages']['hub_page_id'] ) {
                    $page_template = get_post_meta( $wpc_settings['pages']['hub_page_id'], '_wp_page_template', true );
                    if ( $page_template ) {
                        if ( file_exists( get_template_directory() . "/{$page_template}" ) )
                            return get_template_directory() . "/{$page_template}";
                        else
                            return '';
                    }
                }
            }

            return $template;
        }



        /**
         * Run Activated funtions
         */
        function wpc_run_activated_functions( ) {
            if ( '1' != get_option( 'wpc_run_activated_functions' ) ) {
                $this->update_rewrite_rules();

                include_once 'login-redirect.php';
                wpc_client_rul_install();

                update_option( 'wpc_run_activated_functions', '1' );
            }

        }


        /*
        * fix for wpautop in templates
        */
        function remove_autop_template_content( $init, $editor_id ) {
            $init['apply_source_formatting'] = true;
            $init['wpautop'] = false;
            $init['remove_linebreaks'] = false;
            return $init;
        }


        /*
        * fix for wpautop in templates
        */
        function filter_template_content( $content ) {
            $content = addslashes( htmlspecialchars( $content, ENT_NOQUOTES ) );
            $func = "return stripslashes('$content');";
            add_filter( 'the_editor_content', create_function( '',$func), 90 );
            return $content;
        }


        /*
        * login redirect rules
        */
        function login_redirect_rules( $redirect_to, $requested_redirect_to, $user ) {

            // If they're on the login page, don't do anything
            if( !isset( $user->user_login ) ) {
                return $redirect_to;
            }


            //redirect by login/logout redirect table
            $wpc_enable_custom_redirects = get_option( 'wpc_enable_custom_redirects', 'no' );
            if ( 'yes' == $wpc_enable_custom_redirects ) {
                global $wpdb;

                $new_redirect_to = $wpdb->get_var( $wpdb->prepare( "SELECT rul_url FROM {$wpdb->prefix}wpc_client_login_redirects WHERE rul_value = '%s'", $user->user_login ) );
                if ( $new_redirect_to ) {
                    return $new_redirect_to;
                }
            }

            //redirect Client and Staff to my-hub page
            if ( ( user_can( $user, 'wpc_client' ) ) && !user_can( $user, 'manage_network_options' ) )  {
                return wpc_client_get_slug( 'hub_page_id' );
            }

            return $redirect_to;

        }


        /*
        * logout redirect rules
        */
        function logout_redirect_rules() {
            global $current_user;

            //for widget - doing redirect if it set in parameter
            if ( isset( $_REQUEST['logout'] ) && 'true' == $_REQUEST['logout'] ) {
                if ( isset( $_REQUEST['redirect_to'] ) && '' != $_REQUEST['redirect_to'] ) {
                    wp_redirect( $_REQUEST['redirect_to'] );
                    die();
                }
            }

            //redirect by login/logout redirect table
            $wpc_enable_custom_redirects = get_option( 'wpc_enable_custom_redirects', 'no' );
            if ( 'yes' == $wpc_enable_custom_redirects ) {
                global $wpdb;

                $redirect_to = $wpdb->get_var( $wpdb->prepare( "SELECT rul_url_logout FROM {$wpdb->prefix}wpc_client_login_redirects WHERE rul_value = '%s'", $current_user->user_login ) );
                if ( $redirect_to ) {
                    wp_redirect( $redirect_to );
                    die();
                }
            } else {
                //redirect for all if not set another
                wp_redirect( $this->get_login_url() );
                die();
            }
        }




        /*
        * Replace placeholders
        */
        function replace_placeholders( $content, $args = '', $label = '' ) {

                $content = stripslashes( $content );

                $client_id = '';
                if ( isset( $args['client_id'] ) && 0 < $args['client_id'] ) {
                    $client_id = $args['client_id'];

                    $user = get_userdata( $client_id );
                }

                $wpc_business_info   = get_option( 'wpc_business_info' );

                $ph_data = array (
                    '{site_title}'      => get_option( 'blogname' ),
                    '{contact_name}'    => ( $user ) ? $user->get( 'nickname' ) : '',
                    '{client_name}'     => ( $user ) ? $user->get( 'nickname' ) : '',
                    '{user_name}'       => ( $user ) ? $user->get( 'user_login' ) : '',
                    '{login_url}'       => $this->get_login_url(),
                    '{logout_url}'      => $this->get_logout_url(),
                    '{admin_url}'       => get_admin_url(),

                    '{business_logo_url}'           => ( isset( $wpc_business_info['business_logo_url'] ) ) ? $wpc_business_info['business_logo_url'] : '',
                    '{business_name}'               => ( isset( $wpc_business_info['business_name'] ) ) ? $wpc_business_info['business_name'] : '',
                    '{business_address}'            => ( isset( $wpc_business_info['business_address'] ) ) ? $wpc_business_info['business_address'] : '',
                    '{business_mailing_address}'    => ( isset( $wpc_business_info['business_mailing_address'] ) ) ? $wpc_business_info['business_mailing_address'] : '',
                    '{business_website}'            => ( isset( $wpc_business_info['business_website'] ) ) ? $wpc_business_info['business_website'] : '',
                    '{business_email}'              => ( isset( $wpc_business_info['business_email'] ) ) ? $wpc_business_info['business_email'] : '',
                    '{business_phone}'              => ( isset( $wpc_business_info['business_phone'] ) ) ? $wpc_business_info['business_phone'] : '',
                    '{business_fax}'                => ( isset( $wpc_business_info['business_fax'] ) ) ? $wpc_business_info['business_fax'] : '',

                    '{approve_url}'     => '',
                    '{password}'        => '',
                    '{page_title}'      => '',
                    '{page_id}'         => '',
                    '{admin_file_url}'  => '',
                    '{message}'         => '',
                    '{file_name}'       => '',
                    '{file_category}'   => '',
                );

                if ( '' != $label ) {
                    switch( $label ) {
                        case 'notify_client_about_message':
                        case 'notify_admin_about_message':
                            $ph_data['{user_name}'] = ( isset( $args['user_name'] ) ) ? $args['user_name'] : '';
                            $ph_data['{message}'] = ( isset( $args['message'] ) ) ? $args['message'] : '';
                            break;

                        case 'to_approve':
                            $ph_data['{approve_url}'] = get_admin_url() . 'admin.php?page=wpclients&tab=approve';
                            break;

                        case 'new_client':
                        case 'client_updated':
                            $ph_data['{user_name}']     = ( $user ) ? $user->get( 'user_login' ) : '';
                            $ph_data['{user_password}'] = ( isset( $args['user_password'] ) ) ? $args['user_password'] : '';
                            $ph_data['{page_id}']       = ( isset( $args['page_id'] ) ) ? $args['page_id'] : '';
                            $ph_data['{page_title}']    = ( isset( $args['page_title'] ) ) ? $args['page_title'] : '';
                            break;

                        case 'portal_page_updated':
                            $ph_data['{page_id}']       = ( isset( $args['page_id'] ) ) ? $args['page_id'] : '';
                            $ph_data['{page_title}']    = ( isset( $args['page_title'] ) ) ? $args['page_title'] : '';
                            break;
                    }
                }

                $ph_data = apply_filters( "wpc_client_replace_placeholders", $ph_data, $args, $label );

                $content = str_replace( array_keys( $ph_data ), array_values( $ph_data ), $content );

            return $content;
        }

    }

    $wpc_client = new wpc_client();
}


    /*
    * Get slug for wpc_page
    *
    * return slug for wpc_page
    */
    function wpc_client_get_slug( $page = '', $with_end_slash = true, $full_url = true ) {

        if ( '' != $page ) {
            global $wpc_client;
            $wpc_settings = get_option( 'wpc_settings' );
            if ( isset( $wpc_settings['pages'][$page] ) && 0 < $wpc_settings['pages'][$page] ) {

                $post = get_post( $wpc_settings['pages'][$page] );
                if ( isset( $post->post_name ) && '' != $post->post_name ) {
                    $url = '';
                    //parent exist
                    if ( 0 < $post->post_parent ) {
                        $parent = get_post( $post->post_parent );
                        $url = $parent->post_name . '/';
                    }

                    $url .= $post->post_name;

                    if ( $full_url ) {
                        if ( is_multisite() ) {
                            $url = get_home_url( get_current_blog_id() ) . '/' . $url;
                        } else {
                            $url = get_home_url() . '/' . $url;
                        }
                    }

                    if ( $with_end_slash ) {
                        $url = $url . '/';
                    }

                    return $url;

                }
            }
        }

        return '';
    }


    if( isset( $wpc_client ) ) {

        /////////// Add widget login/logout ///////////////
        include 'widget.php';
    }


    /*
    *  Functions
    */


    /*
    * Checking access for page
    *
    * return int $client_id - client ID
    */
    function wpc_client_checking_page_access() {
        global $wpc_client;
        //block not logged clients
        if ( !is_user_logged_in() )  {
            //Sorry, you do not have permission to see this page
            do_action( 'wp_client_redirect', $wpc_client->get_login_url() );
            exit;
        }


        if ( current_user_can( 'wpc_client' ) || current_user_can( 'administrator' ) )
            $client_id = get_current_user_id();
        else
            return false;

        return $client_id;
    }


    /*
    * Get Hub link
    *
    * return hub URL
    */
    function wpc_client_get_hub_link( $client_id = 0 ) {

        if ( 0 == $client_id ) {
            $client_id = get_current_user_id();
        }

        //get URL for HUB page
        if( get_option( 'permalink_structure' ) ) {
            $hub_url = wpc_client_get_slug( 'hub_page_id' );
        } else {
            $hub_id     = get_user_meta( $client_id, 'wpc_cl_hubpage_id', true );
            $hub_url    = get_permalink( $hub_id );
        }

        return $hub_url;
    }


    /*
    *  Shortcodes
    */
    function wpc_client_shortcode_wpclients($atts, $contents = null) {
            global $post, $current_user, $wpdb, $wpc_client, $wp_query;

            //checking access
            $user_id = wpc_client_checking_page_access();

            //if not client
            if ( false === $user_id ) {
                $user_id = get_current_user_id();
            }

            //Show Edit ClientPage
            if ( isset( $wp_query->query_vars['wpc_page'] ) && 'portal_page' == $wp_query->query_vars['wpc_page']
                && isset( $wp_query->query_vars['wpc_page_value'] ) && '' != $wp_query->query_vars['wpc_page_value'] ) {


                $new_post = get_page_by_path( $wp_query->query_vars['wpc_page_value'], object, 'clientspage' );

                if ( $new_post ) {
                    $post_id = $new_post->ID;
                }
            } else {
                $post_id = $post->ID;
            }


            $user_ids       = get_post_meta( $post_id, 'user_ids', true );
            $groups_id      = get_post_meta( $post_id, 'groups_id', true ) ;
            $post_contents  = '';

            //get clients from Client Circles
            if ( is_array( $groups_id ) && 0 < count( $groups_id ) )
                foreach( $groups_id as $group_id ) {
                    $user_ids = array_merge( $user_ids, $wpc_client->get_group_clients_id( $group_id ) );
                }

            $user_ids = array_unique( $user_ids );

            if (( !empty( $user_ids ) && in_array( $user_id, $user_ids ) ) || current_user_can( 'administrator' ) ) {
                $contents .= "<style type='text/css'>.navigation .alignleft, .navigation .alignright {display:none;}</style>";
                $contents = str_replace("{name}", $current_user->user_login, $contents);

                return do_shortcode($contents);
            } else {
                return "Sorry, $current_user->user_login you do not have permission to view this content.";
            }
    }


    function wpc_client_shortcode_private( $atts, $contents=null ) {
        global $current_user, $wpc_client, $wpdb;
        extract( shortcode_atts( array(
            'for' => '',
            'for_circle' => '',
        ), $atts ) );

        if ( is_user_logged_in() ) {

            if ( 'all' == $for && current_user_can( 'wpc_client' ) ) {
                //for all clients
                return do_shortcode( $contents );
            } elseif( $current_user->user_login == $for ) {
                //for some client
                return do_shortcode( $contents );
            } elseif ( 'all' == $for_circle ) {
                //for all groups
                $client_groups_id = $wpc_client->get_client_groups_id( $current_user->ID );
                if ( is_array( $client_groups_id ) && 0 < count( $client_groups_id ) ) {
                    //client in one of group
                    return do_shortcode( $contents );
                }
            } elseif ( '' != $for_circle ) {
                //for some group
                $group_id = $wpdb->get_var( $wpdb->prepare( "SELECT group_id FROM {$wpdb->prefix}wpc_client_groups WHERE group_name = %s", $for_circle ) );
                if ( 0 < $group_id ) {
                    $client_groups_id = $wpc_client->get_client_groups_id( $current_user->ID );
                    if ( is_array( $client_groups_id ) && 0 < count( $client_groups_id ) && in_array( $group_id, $client_groups_id ) ) {
                        //client in this group
                        return do_shortcode( $contents );
                    }
                }

            }

        }

        return '';
    }


    function wpc_client_shortcode_theme($atts, $contents = null) {
        global $wpc_client;

        $url    = $wpc_client->plugin_url . 'images';
        $theme2 = get_option('wpclients_theme');

        if(!$theme2) {
            $theme2 = "light";
        }

        $url .= "/" . $theme2;

        return $url;
    }


    function wpc_client_shortcode_loginf( $atts, $contents=null ) {

        if( !is_user_logged_in() ) {
            return ( include 'forms/login_form.php' );
        } else {
            if ( current_user_can( 'administrator' ) ) {
                do_action( 'wp_client_redirect', get_admin_url() );
                exit;
            } elseif ( ( current_user_can( 'wpc_client' ) ) && !current_user_can( 'manage_network_options' ) ) {
                do_action( 'wp_client_redirect', wpc_client_get_slug( 'hub_page_id' ) );
                exit;
            } else {
                do_action( 'wp_client_redirect', get_home_url() );
                exit;
            }
        }
    }


    function wpc_client_shortcode_logoutb($atts,$contents=null) {
        global $current_user;

        if(!$current_user->ID)
            return "";
        else
            return (include 'forms/logout.php');
    }


    /*
    * display files for client
    */
    function wpc_client_shortcode_filesla($atts, $contents = null) {
        global $wpc_client;
        //checking access
        $user_id = wpc_client_checking_page_access();

        if ( false === $user_id ) {
            return '';
        }

        return $wpc_client->shortcode_message;
    }


    /*
    * display file upload form
    */
    function wpc_client_shortcode_uploadf( $atts, $contents = null ) {
        global $wpc_client;
        //checking access
        $client_id = wpc_client_checking_page_access();

        if ( false === $client_id ) {
            return '';
        }

        return $wpc_client->shortcode_message;
    }


    /*
    * upload file from hub - client area
    */
    function wpc_client_shortcode_fileslu($atts, $contents = null) {
        global $wpc_client;
        //checking access
        $user_id = wpc_client_checking_page_access();

        if ( false === $user_id ) {
            return '';
        }

        return $wpc_client->shortcode_message;
    }



    function wpc_client_shortcode_pagel($atts, $contents = null) {
        global $post, $wpdb, $wp_query, $wpc_client;

        //checking access
        $user_id = wpc_client_checking_page_access();

        if ( false === $user_id ) {
            return '';
        }



        $post_contents  = '';
        $data           = array();

        if ( 'clientspage' != $post->post_type ) {

            // get cclient ID for hub preview
            if ( current_user_can( 'administrator' ) ) {
                $user_id = 0;

                if ( isset( $wp_query->query_vars['wpc_page'] ) && 'hub_preview' == $wp_query->query_vars['wpc_page'] && isset( $wp_query->query_vars['wpc_page_value'] ) && '' != $wp_query->query_vars['wpc_page_value'] ) {
                    $client = get_users( array( 'role' => 'wpc_client', 'meta_key' => 'wpc_cl_hubpage_id', 'meta_value' => $wp_query->query_vars['wpc_page_value'], 'fields' => 'ID' ) );

                    if ( isset( $client[0] ) && $client[0] ) {
                        $user_id = $client[0];
                    }
                }
            }

        }


        if ( isset( $atts['show_current_page'] ) && 'yes' == $atts['show_current_page'] ) {
            //show current portal page
            $show_current_page = '';
        } else {
            //hide current portal page
            $show_current_page = "$wpdb->posts.ID != $post->ID AND";
        }


        //get clientpages by user_ids
        $sql = "
                SELECT $wpdb->posts.ID FROM $wpdb->posts
                INNER JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID
                WHERE
                $show_current_page
                $wpdb->posts.post_type = 'clientspage' AND
                $wpdb->posts.post_status = 'publish' AND (
                $wpdb->postmeta.meta_key = 'user_ids' AND
                $wpdb->postmeta.meta_value like '%\"$user_id\"%'

                )
        ";

        $mypages_id = $wpdb->get_col( $sql );

        //get clientpages by groups_id
        $client_groups_id = $wpc_client->get_client_groups_id( $user_id );

        if ( is_array( $client_groups_id ) && 0 < count( $client_groups_id ) ) {


            foreach ( $client_groups_id as $groups_id )  {
                $sql = "
                        SELECT $wpdb->posts.ID FROM $wpdb->posts
                        INNER JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID
                        WHERE
                        $show_current_page
                        $wpdb->posts.post_type = 'clientspage' AND
                        $wpdb->posts.post_status = 'publish' AND (
                        $wpdb->postmeta.meta_key = 'groups_id' AND
                        $wpdb->postmeta.meta_value like '%\"$groups_id\"%'

                        )
                ";

                $mypages_id2 = $wpdb->get_col( $sql );

                $mypages_id = array_merge( $mypages_id, $mypages_id2 );
            }

        }

        $mypages_id = array_unique( $mypages_id );

        //sorting
        if ( !isset( $atts['sort_type'] ) || 'date' == strtolower( $atts['sort_type'] ) ) {
            //by date
            if ( isset( $atts['sort'] ) && 'desc' == strtolower( $atts['sort'] ) )
                rsort( $mypages_id );
            else
                sort( $mypages_id );
        } elseif ( 'title' == strtolower( $atts['sort_type'] ) ) {
            //by alphabetical
            if ( is_array( $mypages_id ) && $mypages_id ) {
                foreach( $mypages_id as $page_id ) {
                    $mypage = get_post( $page_id, 'ARRAY_A' );
                    $for_sort[nl2br( $mypage['post_title'] )] = $page_id;
                }

                if ( isset( $atts['sort'] ) && 'desc' == strtolower( $atts['sort'] ) )
                    krsort( $for_sort );
                else
                    ksort( $for_sort );

                $mypages_id = array_values( $for_sort );
            }
        }


        foreach( $mypages_id as $page_id ) {
            $mypage = get_post( $page_id, 'ARRAY_A' );
            $page = array();
            if ( 1 == get_post_meta( $mypage['ID'], 'allow_edit_clientpage', true ) )
                $page['edit_link'] = wpc_client_get_slug( 'edit_portal_page_id' ) . $mypage['post_name'];
            else
                $page['edit_link'] = '';

            $page['url']        = wpc_client_get_slug( 'portal_page_id' ) . $mypage['post_name'];
            $page['title']      = nl2br( $mypage['post_title'] );

            $data['pages'][]    = $page;
        }

        //template for pagel
        ob_start();
            include( $wpc_client->plugin_dir . 'forms/wpc_client_pagel.php' );
            $post_contents = ob_get_contents();
        ob_end_clean();

        return $post_contents;
    }


    function wpc_client_shortcode_comments($atts, $contents = null) {
        global $wpc_client;
        //checking access
        $user_id = wpc_client_checking_page_access();

        if ( false === $user_id ) {
            return '';
        }

        return $wpc_client->shortcode_message;
    }


    function wpc_client_shortcode_graphic() {

        $graphic = get_option("wpc_graphic");
        if( $graphic ) {
            return "<img class='wpc_client_graphic' src='$graphic' />";
        }
    }


    function wpc_client_shortcode_client_registration_form( $atts, $contents = null ) {
        global $current_user;
        global $wpc_client;
        if ( !$current_user->ID )
            return $wpc_client->shortcode_message;
        else
            return "";
    }


    function wpc_client_shortcode_registration_successful( $atts, $contents = null ) {
        global $current_user;
        global $wpc_client;
        if ( !$current_user->ID ) {
            return $wpc_client->shortcode_message;
        } else {
            return "";
        }
    }

    //show first name
    function wpc_client_shortcode_business_name( $atts, $contents = null ) {
        if ( is_user_logged_in() ) {
            global $current_user;
            return get_user_meta( $current_user->ID, 'wpc_cl_business_name', true );
        }
        return '';
    }


    //show last name
    function wpc_client_shortcode_contact_name( $atts, $contents = null ) {
        if ( is_user_logged_in() ) {
            global $current_user;
            return get_user_meta( $current_user->ID, 'nickname', true );
        }
        return '';
    }


    //show HUB page
    function wpc_client_shortcode_hub_page( $atts, $contents = null ) {
        global $wp_query, $wpc_client;

        if ( is_user_logged_in() ) {
            $wpc_cl_hubpage_id = 0;

            //for hub preview
            if ( isset( $wp_query->query_vars['wpc_page'] ) && 'hub_preview' == $wp_query->query_vars['wpc_page'] && current_user_can( 'administrator' ) ) {
                if ( isset( $wp_query->query_vars['wpc_page_value'] ) && '' != $wp_query->query_vars['wpc_page_value'] ) {
                    $client = get_users( array( 'role' => 'wpc_client', 'meta_key' => 'wpc_cl_hubpage_id', 'meta_value' => $wp_query->query_vars['wpc_page_value'], 'fields' => 'ID' ) );

                    if ( isset( $client[0] ) && $client[0] ) {
                        $client_id          = $client[0];
                        $wpc_cl_hubpage_id  = $wp_query->query_vars['wpc_page_value'];
                    }
                }
            } else {
                //for client/staff
                $client_id = get_current_user_id();

                $wpc_cl_hubpage_id = get_user_meta( $client_id, 'wpc_cl_hubpage_id', true );
            }


            if ( 0 < $wpc_cl_hubpage_id ) {
                $hub_page = get_post( $wpc_cl_hubpage_id );
                if ( isset( $hub_page->post_content ) ) {
                    $args = array( 'client_id' => $client_id );
                    return do_shortcode( $wpc_client->replace_placeholders( $hub_page->post_content, $args, 'hub_page' ) );
                }
            }

            return '';

        }

        do_action( 'wp_client_redirect', $wpc_client->get_login_url() );
        exit;
    }


    /**
    * Redirect to login or HUB page
    **/
    function wpc_client_shortcode_redirect_on_login_hub( $content ) {
        global $wpc_client;

        if ( is_user_logged_in() ) {
            //on HUB
            do_action( 'wp_client_redirect', wpc_client_get_slug( 'hub_page_id' ) );
            exit;

        }
        //on login form
        do_action( 'wp_client_redirect', $wpc_client->get_login_url() );
        exit;
    }


    /**
    * Get Page link
    **/
    function wpc_client_shortcode_get_page_link( $atts,  $contents = null ) {
        global $wpc_client;

        if ( isset( $atts['page'] ) && '' != $atts['page'] ) {
            $url = wpc_client_get_slug( $atts['page'] . '_page_id' );
            if ( '' != $url ) {
                $id     = ( isset( $atts['id'] ) && '' != $atts['id'] ) ?'id="' . $atts['id'] . '"' : '';
                $class  = ( isset( $atts['class'] ) && '' != $atts['class'] ) ? 'class="' . $atts['class'] . '"' : '';
                $style  = ( isset( $atts['style'] ) && '' != $atts['style'] ) ? 'style="' . $atts['style'] . '"' : '';
                $text   = ( isset( $atts['text'] ) && '' != $atts['text'] ) ? $atts['text'] : $atts['page'] . ' link';
                return '<a href="' . $url . '" ' . $id . ' ' . $class . ' ' . $style . '  >' . $text . '</a>';
            }
        }

        return '';
    }

    //show Portal page
    function wpc_client_shortcode_portal_page( $atts, $contents = null ) {
        if ( is_user_logged_in() ) {

            $client_id = get_current_user_id();

        }
        return '';
    }


    add_shortcode('wpc_client', 'wpc_client_shortcode_wpclients');
    add_shortcode('wpc_client_private', 'wpc_client_shortcode_private');
    add_shortcode('wpc_client_theme', 'wpc_client_shortcode_theme');
    add_shortcode('wpc_client_loginf', 'wpc_client_shortcode_loginf');
    add_shortcode('wpc_client_logoutb', 'wpc_client_shortcode_logoutb');
    add_shortcode('wpc_client_filesla', 'wpc_client_shortcode_filesla');
    add_shortcode('wpc_client_uploadf', 'wpc_client_shortcode_uploadf');
    add_shortcode('wpc_client_fileslu', 'wpc_client_shortcode_fileslu');
    add_shortcode('wpc_client_pagel', 'wpc_client_shortcode_pagel');
    add_shortcode('wpc_client_com', 'wpc_client_shortcode_comments');
    add_shortcode('wpc_client_graphic', 'wpc_client_shortcode_graphic');
    add_shortcode('wpc_client_registration_form', 'wpc_client_shortcode_client_registration_form');
    add_shortcode('wpc_client_business_name', 'wpc_client_shortcode_business_name');
    add_shortcode('wpc_client_contact_name', 'wpc_client_shortcode_contact_name');

    add_shortcode('wpc_client_hub_page', 'wpc_client_shortcode_hub_page');

    add_shortcode('wpc_client_get_page_link', 'wpc_client_shortcode_get_page_link');

    add_shortcode('wpc_client_portal_page', 'wpc_client_shortcode_portal_page');

    add_shortcode('wpc_redirect_on_login_hub', 'wpc_client_shortcode_redirect_on_login_hub');




    /*
    //filter for run sortcodes after wpautop function
    add_filter( 'the_content', 'wpc_client_run_shortcodes', 1000 );

    //run sortcodes
    function wpc_client_run_shortcodes( $content ) {

        $content = do_shortcode( $content );

        return $content;
    }

    */



    /*
    * meta box starts
    */

    function wpc_client_xyris_users_checkboxlist($checked = '') {
        global $wpdb, $wpc_client;
        ?>
        <span class="edit"><a href="#popup_block2" rel="clients" class="fancybox_link" title="assign clients" ><?php _e( 'Allowed Users', WPC_CLIENT_TEXT_DOMAIN ) ?></a></span>&nbsp;&nbsp;&nbsp;<span class="edit" id="counter_clients">(<?php echo count($checked);?>)</span>
        <input type="hidden" name="clients" id="clients" value="<?php echo implode( ',', $checked ); ?>" />
        <script type="text/javascript">
            var site_url = '<?php echo site_url();?>';
        </script>
        <?php
        $wpc_client->get_assign_clients_popup( 'wpclients_managers' );
    }


    function wpc_client_userids_options() {
        global $post;

        $user_ids = get_post_meta( $post->ID, 'user_ids', true );
        wpc_client_xyris_users_checkboxlist( $user_ids );
    }


    function wpc_client_page_update_options() {

        print ("
            <label for='send_update'>Send Update to selected Client(s)</label>
            <input name='send_update' id='send_update' type='checkbox' value='1' />
        ");
    }

    function wpc_client_groups_id_meta() {
        global $post, $wpdb, $wpc_client;
        $groups_id  = get_post_meta( $post->ID, 'groups_id', true );
        $groups_id = is_array( $groups_id ) ? $groups_id : array();
        ?>
        <span class="edit"><a href="#circles_popup_block" rel="circles" class="fancybox_link" title="assign Client Circles" ><?php _e( 'Allowed Client Circles', WPC_CLIENT_TEXT_DOMAIN ) ?></a></span>&nbsp;&nbsp;&nbsp;<span class="edit" id="counter_circles">(<?php echo count($groups_id);?>)</span>
        <input type="hidden" name="circles" id="circles" value="<?php echo implode( ',', $groups_id ); ?>" />
        <?php
        $wpc_client->get_assign_circles_popup( 'wpclients_managers' );
    }


    //show metabox for select template for clientpage
    function wpc_client_clientpage_template_meta() {
        global $post, $wpc_client;

        if ( 'clientspage' == $post->post_type && 0 != count( get_page_templates() ) ) {
            $template = get_post_meta( $post->ID, '_wp_page_template', true );

            ?>
            <p><strong><?php _e( 'Template', WPC_CLIENT_TEXT_DOMAIN ) ?></strong></p>
            <label class="screen-reader-text" for="clientpage_template"><?php _e( 'Portal Page Template', WPC_CLIENT_TEXT_DOMAIN ) ?></label>
            <select name="clientpage_template" id="clientpage_template">
                <option value='__use_same_as_portal_page' <?php echo ( !isset( $template ) || '__use_same_as_portal_page' == $template ) ? 'selected' : '' ?> ><?php _e( 'Use same as /portal-page', WPC_CLIENT_TEXT_DOMAIN ); ?></option>
                <option value='default' <?php echo ( isset( $template ) && 'default' == $template ) ? 'selected' : '' ?> ><?php _e( 'Default Template', WPC_CLIENT_TEXT_DOMAIN ); ?></option>
                <?php page_template_dropdown( $template ); ?>
            </select>
            <?php
        }
    }


    //show metabox for select template for clientpage
    function wpc_client_hubpage_template_meta() {
        global $post, $wpc_client;

        if ( 'hubpage' == $post->post_type && 0 != count( get_page_templates() ) ) {
            $template = get_post_meta( $post->ID, '_wp_page_template', true );

            ?>
            <p><strong><?php _e( 'Template', WPC_CLIENT_TEXT_DOMAIN ) ?></strong></p>
            <label class="screen-reader-text" for="hubpage_template"><?php _e( 'HUB Page Template', WPC_CLIENT_TEXT_DOMAIN ) ?></label>
            <select name="hubpage_template" id="hubpage_template">
                <option value='default'><?php _e( 'Default Template', WPC_CLIENT_TEXT_DOMAIN ); ?></option>
                <?php page_template_dropdown( $template ); ?>
            </select>
            <?php
        }
    }


    /*
    * Add meta box
    */
    function wpc_client_meta_init() {
        add_meta_box( 'wpclients_userids-meta', 'Allowed Users', 'wpc_client_userids_options', 'clientspage', 'advanced', 'low' );
        //meta box for select clientpage template
        add_meta_box( 'wpclients_clientpage_template',  'Template', 'wpc_client_clientpage_template_meta', 'clientspage', 'side', 'low' );

        //meta box for select hubpage template
        add_meta_box( 'wpclients_hubpage_template',  'Template', 'wpc_client_hubpage_template_meta', 'hubpage', 'side', 'low' );

        // metabox for Client Circles
        add_meta_box( 'wpclients_groups_id-meta', 'Allowed Client Circles', 'wpc_client_groups_id_meta', 'clientspage', 'advanced', 'low' );

        add_meta_box( 'wpclients_page_update-meta', 'Send Update to Client(s)', 'wpc_client_page_update_options', 'clientspage', 'advanced', 'low' );

    }

    add_action( 'admin_init', 'wpc_client_meta_init' );




    //add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));


    function wpc_client_save_meta( $post_id ) {
        global $wpc_client;


        //for quick edit
        if(defined('DOING_AJAX') && DOING_AJAX) {
            return $post_id;
        }

        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        if( defined('WPC_CLIENT_NOT_SAVE_META') && WPC_CLIENT_NOT_SAVE_META ) {
            return $post_id;
        }

        if ( isset( $_POST ) && 0 < count( $_POST ) ) {
            $post = get_post( $post_id );

            if ( 'clientspage' == $post->post_type ) {

                //updating from admin
                if( isset( $_POST['clients'] ) && '' != $_POST['clients'] ) {
                    if( $_POST['clients'] == 'all' ) {
                        $selected_clients = $wpc_client->get_client_ids();
                    } else {
                        $selected_clients = explode( ',', $_POST['clients'] );
                    }

                    if ( is_array( $selected_clients ) && count( $selected_clients ) ) {
                        update_post_meta( $post_id, 'user_ids', $selected_clients );
                    } else {
                        update_post_meta( $post_id, 'user_ids', '' );
                    }
                } else {
                    update_post_meta( $post_id, 'user_ids', '' );
                }

                //update clientpage file template
                if ( isset( $_POST['clientpage_template'] ) && '' != $_POST['clientpage_template'] ) {
                    update_post_meta( $post_id, '_wp_page_template', $_POST['clientpage_template'] );
                } else {
                    delete_post_meta( $post_id, '_wp_page_template' );
                }

                //save client Client Circles for Portal Page
                if ( isset( $_POST['circles'] ) && '' != $_POST['circles'] ) {
                    if( $_POST['circles'] == 'all' ) {
                        $selected_circles = $wpc_client->get_group_ids();
                    } else {
                        $selected_circles = explode( ',', $_POST['circles'] );
                    }

                    if ( is_array( $selected_circles ) && count( $selected_circles ) ) {
                        update_post_meta( $post_id, 'groups_id', $selected_circles );
                    } else {
                        update_post_meta( $post_id, 'groups_id', '' );
                    }
                } else {
                    update_post_meta( $post_id, 'groups_id', '' );
                }

                // send updates to client
                if ( isset( $_POST['send_update'] ) ) {

                    if( isset( $_POST['clients'] ) && $_POST['clients'] == 'all' ) {
                        $user_ids = $wpc_client->get_client_ids();
                    } else {
                        $user_ids = ( isset( $_POST['clients'] ) ) ? explode( ',', $_POST['clients'] ) : array();
                    }
                    if( isset( $_POST['circles'] ) && $_POST['circles'] == 'all' ) {
                        $groups_id = $wpc_client->get_group_ids();
                    } else {
                        $groups_id = ( isset( $_POST['circles'] ) ) ? explode( ',', $_POST['circles'] ) : array();
                    }

                    //get clients from Client Circles
                    if ( is_array( $groups_id ) && 0 < count( $groups_id ) )
                        foreach( $groups_id as $group_id ) {
                            $user_ids = array_merge( $user_ids, $wpc_client->get_group_clients_id( $group_id ) );
                        }

                    $user_ids = array_unique( $user_ids );

                    //send update email to selected clients
                    foreach ( $user_ids as $user_id ) {

                        $userdata   = (array) get_userdata( $user_id );
                        $link       = get_permalink( $post_id );

                        //get email template

                        $headers = "From: " . get_option( 'sender_name' ) . " <" . get_option( 'sender_email' ) . "> \r\n";
                        $headers .= "Reply-To: " . ( get_option( 'wpc_reply_email' ) ) ? get_option( 'wpc_reply_email' ) : get_option( 'admin_email' ) . "\r\n";
                        $headers .= "MIME-Version: 1.0\r\n";
                        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                        $args = array( 'client_id' => $user_id, 'page_id' => $link, 'page_title' => get_the_title( $post_id ) );

                        $wpc_templates['emails']['client_page_updated']['subject'] = 'Your Portal Page has been updated';

                        $wpc_templates['emails']['client_page_updated']['body'] = '<p>Hello {contact_name},</p>
                        <p>Your Portal Page, {page_title} has been updated | <a href="{page_id}">Click HERE to visit</a></p>
                        <p>Thanks, and please contact us if you experience any difficulties,</p>
                        <p>YOUR COMPANY NAME HERE</p>';

                        $subject = $wpc_client->replace_placeholders( $wpc_templates['emails']['client_page_updated']['subject'], $args, 'portal_page_updated' );
                        $subject = htmlentities( $subject, ENT_QUOTES, 'UTF-8' );

                        $message = $wpc_client->replace_placeholders( $wpc_templates['emails']['client_page_updated']['body'], $args, 'portal_page_updated' );

                        wp_mail( $userdata['data']->user_email, $subject, $message, $headers );
                    }
                }

            } elseif ( 'hubpage' == $post->post_type ) {

                //update hubpage file template
                if ( isset( $_POST['hubpage_template'] ) && 'default' != $_POST['hubpage_template'] ) {
                    update_post_meta( $post_id, '_wp_page_template', $_POST['hubpage_template'] );
                } else {
                    delete_post_meta( $post_id, '_wp_page_template' );
                }

            }

        }
    }

    add_action('save_post', 'wpc_client_save_meta');


    /*
    *  Add new columns to clientspage Posttype
    */
    function wpc_client_my_columns( $columns ) {
        $columns['clients'] = 'Clients';
        $columns['groups']  = 'Client Circles';

        unset($columns['date']);
        $columns['date']    = 'Date';

        return $columns;
    }

    add_filter('manage_edit-clientspage_columns', 'wpc_client_my_columns');


    /*
    *  Add values for new columns of clientspage Posttype
    */
    function wpc_client_my_show_columns( $column_name ) {
        global $post, $wpc_client;
        if ( $column_name == 'clients' ) {
            $users = get_post_meta( $post->ID, 'user_ids', true );

            if( is_array( $users ) && 0 < count( $users ) ) {
                echo '<div class="scroll_data">';
        //show all clients
                foreach ( $users as $key => $value ) {
                    $data = get_user_meta( $value, 'wpc_cl_business_name' );
                    if ( isset( $data[0] ) )
                        echo $data[0] . '<br/>';
                }
                echo '</div>';
            }
        }

        if ( $column_name == 'groups' ) {
            $groups_id = get_post_meta( $post->ID, 'groups_id', true );
            if( is_array( $groups_id ) && 0 < count( $groups_id ) ) {
                echo '<div class="scroll_data">';
                foreach ( $groups_id as $group_id ) {
                    $group = $wpc_client->get_group( $group_id );
                    echo $group['group_name'] . '<br/>';
                }
                echo '</div>';
            }
        }

    }

    add_action('manage_clientspage_posts_custom_column', 'wpc_client_my_show_columns');

    ////// meta box ends //////


    ///////////////////// custom login screen ////////////////////

    include 'custom-logins.php';

    add_action('admin_init', 'wpc_client_custom_login_init');
    add_action('login_head', 'wpc_client_bm_custom_login');
    add_filter('login_headerurl', 'wpc_client_logo_url');
    add_filter('login_headertitle', 'wpc_client_logo_title');

    ///////////////// exclude pages //////////////////
    // Full filesystem path to this dir
    include 'exclude-pages.php';

    /////////// login / logout redirects ///////////////
    include_once 'login-redirect.php';

//    register_activation_hook($wpc_client->plugin_dir . 'wp-client.php', 'wpc_client_rul_install');

    //register_activation_hook( $wpc_client->plugin_dir . 'wp-client.php', array( &$wpc_client, 'wp_password_generator_generate' ) );

    register_uninstall_hook($wpc_client->plugin_dir . 'wp-client-lite.php', 'wpc_client_rul_uninstall');


//
//    if(!$rul_use_redirect_controller) {
//        add_filter('login_redirect', 'wpc_client_redirect_wrapper', 10, 3);
//    }

//    add_action('wp_logout', array('wpc_rulLogoutFunctionCollection', 'logout_redirect'), 10);


    function wpc_client_hide_add_new_custom_type() {
        global $menu, $submenu;

        if ( isset( $submenu['wpclients'] ) ) {

            //temp menu for hide in future
            $GLOBALS['wpclients_temp_menu'] = array();


            $submenu['wpclients'][0][0] = "Clients";

            //for hide menu change array key in function - wpc_client_hide_admin_submenu

            $wpc_menus['wpclients']             = $submenu['wpclients'][0];
            $wpc_menus['wpclients_managers']    = $submenu['wpclients'][1];
            $wpc_menus['wpclients_groups']      = $submenu['wpclients'][5];

            $wpc_menus['separator_1']           = array( '- - - - - - - - - -', 'manage_options', '#', '' );

            $wpc_menus['hub_pages']             = array( 'HUB Pages', 'manage_options', 'edit.php?post_type=hubpage' );
            $wpc_menus['client_pages']          = array( 'Portal Pages', 'manage_options', 'edit.php?post_type=clientspage' );
            $wpc_menus['add_client_page']       = $submenu['wpclients'][2];
            $wpc_menus['wpclients_files']       = $submenu['wpclients'][4];
            $wpc_menus['messages']              = $submenu['wpclients'][10];

            $wpc_menus['separator_3']           = array( '- - - - - - - - - -', 'manage_options', '#', '' );

            $wpc_menus['wpclients_templates']   = $submenu['wpclients'][3];
            $wpc_menus['wpclients_settings']    = $submenu['wpclients'][6];
            $wpc_menus['wpclients_help']        = $submenu['wpclients'][7];


            //hidden menu
            $wpc_menus['xyris_login_logout']    = $submenu['wpclients'][8];
            $wpc_menus['custom_login_admin']    = $submenu['wpclients'][9];


            unset($submenu['edit.php?post_type=clientspage'][10]);
            unset($submenu['edit.php?post_type=hubpage'][10]);

            foreach( $wpc_menus as $wpc_menu ) {
                $temp[] = $wpc_menu;
            }


            unset($submenu['wpclients']);

            $submenu['wpclients'] = $temp;

            $GLOBALS['wpclients_temp_menu'] = $submenu['wpclients'];

        }


        end($menu);

        while(prev($menu)) {
            $value = explode(' ', $menu[key($menu)][0]);

            if(($value[0] == "Portal" && $value[1] == "Pages") || ($value[0] == "Hub" && $value[1] == "Pages"))
                unset($menu[key($menu)]);
        }
    }


    function wpc_client_hide_buttons() {
        global $pagenow;

        if(is_admin()) {
            if($pagenow == 'edit.php' && isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'clientspage') {
                //echo "<style type=\"text/css\">.add-new-h2{display: none;}</style>";
            }
        }
    }


    function wpc_client_permissions_admin_redirect() {
        $result = stripos($_SERVER['REQUEST_URI'], 'post-new.php?post_type=clientspage');

        if($result !== false)
            wp_redirect(get_option('siteurl') . '/wp-admin/index.php?permissions_error=true');
    }


    function wpc_client_permissions_admin_notice() {
        // use the class "error" for red notices, and "update" for yellow notices
        echo "<div id='permissions-warning' class='error fade'><p><strong>" . __( 'You do not have permission to access that page.', WPC_CLIENT_TEXT_DOMAIN ) . "</strong></p></div>";
    }


    function wpc_client_permissions_show_notice() {
        if(isset($_GET['permissions_error']))
            add_action('admin_notices', 'wpc_client_permissions_admin_notice');

    }

    add_action('admin_menu', 'wpc_client_hide_add_new_custom_type');
    add_action('admin_head', 'wpc_client_hide_buttons');
    add_action('admin_menu', 'wpc_client_permissions_admin_redirect');
    add_action('admin_init', 'wpc_client_permissions_show_notice');

    /*
    * Register post types
    */
    function wpc_client_create_post_type() {

        $portal_page_base = wpc_client_get_slug( 'portal_page_id', false, false );
        if ( '' == $portal_page_base ) {
            $portal_page_base = 'portal/portal-page';
        }

        //Clientpage (Portal page) post type
        $labels = array(
            'name'                  => _x('Portal Pages', 'post type general name'),
            'singular_name'         => _x('Portal Page', 'post type singular name'),
            //'add_new' => _x('Add New', 'Services item'),
            //'add_new_item'        => __('Add New Service'),
            'edit_item'             => __('Edit Portal Page Item'),
            //'new_item'            => __('New Service Item'),
            'view_item'             => __('View Portal Page Item'),
            'search_items'          => __('Search Portal Page'),
            'not_found'             => __('Nothing found'),
            'not_found_in_trash'    => __('Nothing found in Trash'),
            'parent_item_colon'     => ''
        );

        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'query_var'             => true,
            'rewrite'               => false,
            'capability_type'       => 'clientpage',
            'capabilities'          => array( 'edit_posts' => 'edit_published_clientpages' ),
            'map_meta_cap'          => true,
            'hierarchical'          => true,
            'exclude_from_search'   => true,
            'menu_position'         => 145,
            'supports'              => array('title', 'editor', 'thumbnail', 'meta'),
            'rewrite'               => array( 'slug' => $portal_page_base, 'with_front' => false, 'pages' => false, ),
        );

        register_post_type('clientspage', $args);


        //HUB post type
        $labels = array(
            'name'                  => _x('Hub Pages', 'post type general name'),
            'singular_name'         => _x('Hub Page', 'post type singular name'),
            //'add_new'             => _x('Add New', 'Services item'),
            //'add_new_item'        => __('Add New Service'),
            'edit_item'             => __('Edit Hub Page Item'),
            //'new_item'            => __('New Service Item'),
            'view_item'             => __('View Hub Page Item'),
            'search_items'          => __('Search Hub Page'),
            'not_found'             => __('Nothing found'),
            'not_found_in_trash'    => __('Nothing found in Trash'),
            'parent_item_colon'     => ''
        );

        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'query_var'             => true,
            'rewrite'               => false,
            'capability_type'       => 'post',
            'hierarchical'          => true,
            'exclude_from_search'   => true,
            'menu_position'         => 145,
            'supports'              => array('title', 'editor', 'thumbnail', 'meta')
        );

        register_post_type('hubpage', $args);
    }

    add_action('init', 'wpc_client_create_post_type');


    function wp_client_redirect($url) {

        //return buffer if it started
        if ( false !== ob_get_contents() )
            ob_end_flush();

        echo '<script type="text/javascript" language="javascript">self.location.href="' . $url . '";</script>';
        exit;
    }

    add_action('wp_client_redirect', 'wp_client_redirect', 10, 1);


    /*
    * Add HUB page link to menu
    */
    function wpc_client_add_blog_cats_to_menu( $items, $args = '' ) {

        $show_link  = get_option( 'wpc_show_link' );

        if ( $show_link == "yes" ) {
            if ( is_user_logged_in() && !current_user_can( 'manage_options' ) ) {

                if ( current_user_can( 'wpc_client_staff' ) )
                    $client_id = get_user_meta( get_current_user_id(), 'parent_client_id', true );
                else
                    $client_id = get_current_user_id();

                $hub_id = get_user_meta( $client_id, 'wpc_cl_hubpage_id', true );

                $page       = get_post( $hub_id );
                $link       = get_permalink( $page->ID );
                $link_text  = get_option( 'wpc_link_text' );

                if( get_option( 'permalink_structure' ) ) {
                    return $items . '<li><a href="' . wpc_client_get_slug( 'hub_page_id' ) . '">' . $link_text . '</a></li>';
                } else {
                    return $items . '<li><a href="' . $link .  '">' . $link_text . '</a></li>';
                }
            }
        }

        return $items;
    }


    /*
    * Set custom menu
    */
    function wpc_client_custom_menu(  $args ) {
        $wpc_settings = get_option( 'wpc_settings' );

        if ( isset( $wpc_settings['show_custom_menu'] ) && 'yes' == $wpc_settings['show_custom_menu'] ) {
            if ( is_user_logged_in() && ( current_user_can( 'wpc_client' ) || current_user_can( 'wpc_client_staff' ) ) ) {
                //only for clients and staff
                if ( '' != $args['theme_location'] && isset( $wpc_settings['custom_menu_logged_in'][$args['theme_location']] ) && '' != $wpc_settings['custom_menu_logged_in'][$args['theme_location']] ) {
                    $menu = get_term( $wpc_settings['custom_menu_logged_in'][$args['theme_location']], 'nav_menu' );
                    $args['menu']  = $menu->name;
                }
            }
            elseif ( '' != $args['theme_location'] && isset( $wpc_settings['custom_menu_logged_out'][$args['theme_location']] ) && '' != $wpc_settings['custom_menu_logged_out'][$args['theme_location']] ) {
                $menu = get_term( $wpc_settings['custom_menu_logged_out'][$args['theme_location']], 'nav_menu' );
                $args['menu']  = $menu->name;
            }
        }

        return $args;

    }


    add_filter( 'wp_list_pages', 'wpc_client_add_blog_cats_to_menu', 1, 2 );
    add_filter( 'wp_nav_menu_items', 'wpc_client_add_blog_cats_to_menu', 1, 2 );
    add_filter( 'wp_nav_menu_args', 'wpc_client_custom_menu', 99 );


    function wpc_client_hide_that_stuff() {
        if ( 'hubpage' == get_post_type() || 'clientspage' == get_post_type() ) {
            echo '<style type="text/css">.add-new-h2 {display:none;} #wp-admin-bar-new-clientspage {display:none;} #wp-admin-bar-new-hubpage{display:none;}</style>';
        }
    }

    add_action('admin_head', 'wpc_client_hide_that_stuff');


    function wp_client_css_js() {
        global $wpc_client, $parent_file, $wp_query;

        if ( 'hubpage' == get_post_type() || 'clientspage' == get_post_type() ) {
            wp_register_style( 'wp-client-style', $wpc_client->plugin_url . 'css/style.css' );
            wp_enqueue_style( 'wp-client-style' );
        }

        if ( isset( $parent_file ) && 'wpclients' == $parent_file ) {
            wp_enqueue_script('jquery');

            wp_register_style( 'wp-client-style', $wpc_client->plugin_url . 'css/style.css' );
            wp_enqueue_style( 'wp-client-style' );

            wp_register_style( 'wpc-fancybox-style', $wpc_client->plugin_url . 'js/fancybox/jquery.fancybox.css' );
            wp_enqueue_style( 'wpc-fancybox-style' );
            wp_register_script( 'wpc-fancybox-js', $wpc_client->plugin_url . 'js/fancybox/jquery.fancybox.pack.js' );
            wp_enqueue_script( 'wpc-fancybox-js' );

            wp_enqueue_script( 'jquery-ui-sortable' );

            $wpc_settings = get_option( 'wpc_settings' );
        }

        if( 'clientspage' == get_post_type() && isset( $_GET['post'] ) && is_numeric( $_GET['post'] ) && isset( $_GET['action'] ) && 'edit' == $_GET['action'] ) {
            wp_register_style( 'wpc-fancybox-style', $wpc_client->plugin_url . 'js/fancybox/jquery.fancybox.css' );
            wp_enqueue_style( 'wpc-fancybox-style' );
            wp_register_script( 'wpc-fancybox-js', $wpc_client->plugin_url . 'js/fancybox/jquery.fancybox.pack.js' );
            wp_enqueue_script( 'wpc-fancybox-js' );
            wp_register_script( 'wpc-assign-popup-js', $wpc_client->plugin_url . 'js/assign-popup.js' );
            wp_enqueue_script( 'wpc-assign-popup-js' );
        }

        if ( isset( $_GET['page'] ) ) {
            switch( $_GET['page'] ) {
                case 'wpclients_settings':
                {
                    wp_register_style( 'wp-client-style', $wpc_client->plugin_url . 'css/style.css' );
                    wp_enqueue_style( 'wp-client-style' );
                    wp_register_style( 'wpc-fancybox-style', $wpc_client->plugin_url . 'js/fancybox/jquery.fancybox.css' );
                    wp_enqueue_style( 'wpc-fancybox-style' );
                    wp_register_script( 'wpc-fancybox-js', $wpc_client->plugin_url . 'js/fancybox/jquery.fancybox.pack.js' );
                    wp_enqueue_script( 'wpc-fancybox-js' );
                    wp_register_style( 'wpc-chosen-style', $wpc_client->plugin_url . 'js/chosen/chosen.css' );
                    wp_enqueue_style( 'wpc-chosen-style' );
                    wp_register_script( 'wpc-chosen-js', $wpc_client->plugin_url . 'js/chosen/chosen.jquery.min.js' );
                    wp_enqueue_script( 'wpc-chosen-js' );
                    break;
                }
                case 'add_client_page':
                case 'wpclients_groups':
                {
                    wp_register_style( 'wpc-fancybox-style', $wpc_client->plugin_url . 'js/fancybox/jquery.fancybox.css' );
                    wp_enqueue_style( 'wpc-fancybox-style' );
                    wp_register_script( 'wpc-fancybox-js', $wpc_client->plugin_url . 'js/fancybox/jquery.fancybox.pack.js' );
                    wp_enqueue_script( 'wpc-fancybox-js' );
                    wp_register_script( 'wpc-assign-popup-js', $wpc_client->plugin_url . 'js/assign-popup.js' );
                    wp_enqueue_script( 'wpc-assign-popup-js' );
                    break;
                }
                case 'wpclients':
                {
                    wp_enqueue_script( 'jquery-base64', $wpc_client->plugin_url . 'js/jquery.base64.min.js', array( 'jquery' ) );

                    wp_register_style( 'wpc-fancybox-style', $wpc_client->plugin_url . 'js/fancybox/jquery.fancybox.css' );
                    wp_enqueue_style( 'wpc-fancybox-style' );
                    wp_register_script( 'wpc-fancybox-js', $wpc_client->plugin_url . 'js/fancybox/jquery.fancybox.pack.js' );
                    wp_enqueue_script( 'wpc-fancybox-js' );
                    wp_register_script( 'wpc-assign-popup-js', $wpc_client->plugin_url . 'js/assign-popup.js' );
                    wp_enqueue_script( 'wpc-assign-popup-js' );
                    break;
                }
            }
        }

    }


    add_action( 'admin_enqueue_scripts', 'wp_client_css_js', 99 );


    function wp_client_wp_css_js() {
        global $wpc_client, $wp_query, $post;

        if ( !get_option( 'wpc_disable_jquery' ) ) {
            wp_enqueue_script('jquery');
        }

    }


    add_action( 'wp_enqueue_scripts', 'wp_client_wp_css_js', 99 );


    /*
    * Hide admin submenu from list of menu
    */
    function wpc_client_hide_admin_submenu() {
        global $menu, $submenu, $parent_file, $current_user, $wpc_client ;

        if ( isset( $submenu['wpclients'] ) ) {
            if ( current_user_can( 'administrator' ) ) {

                //remove(hide) menu from admin menubar
                $n = count( $submenu['wpclients'] );
                for ( $i = $n - 1; $i >= $n - 2; $i = $i - 1 ) {
                    if ( isset( $submenu['wpclients'][$i] ) )
                        unset( $submenu['wpclients'][$i] );
                }

                if ( isset( $_GET['page'] ) && 'custom_login_admin' == $_GET['page'] )
                    $submenu['wpclients'][count( $submenu['wpclients'] ) - 2][2] = 'custom_login_admin';

                if ( isset( $_GET['page'] ) && 'xyris-login-logout' == $_GET['page'] )
                    $submenu['wpclients'][count( $submenu['wpclients'] ) - 2][2] = 'xyris-login-logout';


                if ( $wpc_client->plugin['hide_help_menu'] ) {
                    $i = count( $submenu['wpclients'] ) - 1;
                    if ( $i ) {
                        unset( $submenu['wpclients'][$i] );
                    }
                }


                if ( ( isset( $_GET['post_type'] ) && ( 'hubpage' == $_GET['post_type'] || 'clientspage' == $_GET['post_type'] ) )
                        || 'edit.php?post_type=hubpage' == $parent_file
                        || 'edit.php?post_type=clientspage' == $parent_file ) {

                    add_filter( 'parent_file', 'wpc_client_change_parent_file', 200 );
                }

            }
        }
    }

    add_filter( 'admin_body_class', 'wpc_client_hide_admin_submenu' );


    /*
    * Return admin submenu variable for display pages
    */
    function wpc_client_return_admin_submenu() {
        global $submenu;
        if ( isset( $GLOBALS['wpclients_temp_menu'] ) )
            $submenu['wpclients'] = $GLOBALS['wpclients_temp_menu'];
    }


    /*
    * Return admin submenu variable for display pages
    */
    function wpc_client_change_parent_file( $parent_file ) {
        global $pagenow;
        $pagenow = 'admin.php';
        $parent_file = 'wpclients';
        return $parent_file;
    }

    add_action( 'in_admin_header', 'wpc_client_return_admin_submenu' );


    /*
    * Filter for full-width for Portal pages (may not work for some themes)
    */
    function wpc_client_body_class_for_clientpages( $classes ) {
        global $post;

        if ( is_single() && 'clientspage' == $post->post_type ) {
            $page_template = get_post_meta( $post->ID, '_wp_page_template', true );

            if ( !$page_template || '__use_same_as_portal_page' == $page_template ) {
                $wpc_settings = get_option( 'wpc_settings' );
                if ( isset( $wpc_settings['pages']['portal_page_id'] ) && 0 < $wpc_settings['pages']['portal_page_id'] ) {
                    $page_template = get_post_meta( $wpc_settings['pages']['portal_page_id'], '_wp_page_template', true );
                }
            }

            if ( 'page-templates/full-width.php' == $page_template )
                $classes[] = 'full-width';

        }

        return $classes;

    }

    add_filter( 'body_class', 'wpc_client_body_class_for_clientpages', 99 );

?>