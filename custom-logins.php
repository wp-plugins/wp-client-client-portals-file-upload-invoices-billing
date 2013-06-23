<?php

define ('CL_GROUP', 'custom_login');
define ('CL_PAGE', 'custom_login_admin');
define ('CL_SECTION', 'custom_login_section');
define ('CL_OPTIONS', 'custom_login_options');

$cl_options = array ();


function wpc_client_custom_login_get_options () {
	global $cl_options;

	if (empty ($cl_options)) {
		$cl_options = get_option (CL_OPTIONS);
	}

	return $cl_options;
}


function wpc_client_logo_url() {
    $cl_options = wpc_client_custom_login_get_options();

    if ( !isset( $cl_options['cl_enable'] ) || 'yes' == $cl_options['cl_enable'] ) {

        //logo link
        if (!empty ( $cl_options['cl_logo_link'] ) ) {
            return  $cl_options['cl_logo_link'];
        }
    }

}


function wpc_client_logo_title() {
    $cl_options = wpc_client_custom_login_get_options();

    if ( !isset( $cl_options['cl_enable'] ) || 'yes' == $cl_options['cl_enable'] ) {

        //logo text
        if (!empty ( $cl_options['cl_logo_title'] ) ) {
            return  $cl_options['cl_logo_title'];
        }
    }

}


function wpc_client_bm_custom_login () {
    global $wpc_client;

	$cl_options = wpc_client_custom_login_get_options ();

    if ( !isset( $cl_options['cl_enable'] ) || 'yes' == $cl_options['cl_enable'] ) {

	    // default wordpress mu plugin path
	    $pluginPath = '/wp-content/mu-plugins/';

	    // is it wordpress mu or wordpress normal?
	    if (!is_dir ($pluginPath)) {
		    $pluginPath = '/wp-content/plugins/';
	    }

	    $pluginUrl = $wpc_client->plugin_url . 'css/custom-login.css';

	    // output styles
	    echo '<link rel="stylesheet" type="text/css" href="' . $pluginUrl . '" />';

	    echo '<style>';

	    $background = $cl_options['cl_background'];

	    if (!empty ($background)) {

            ?>

	        #login {
		        background:url(<?php echo $background; ?>) top center no-repeat;
                padding: 114px 0px 0px 0px !important;
	        }

            <?php

	    }

	    // text colour
	    if (!empty ($cl_options['cl_color'])) {

            ?>

	        #login,
	        #login label {
		        color:#<?php echo $cl_options['cl_color']; ?>;
	        }

            <?php

	    }

	    // text colour
	    if (!empty ($cl_options['cl_backgroundColor'])) {

            ?>

	            html,
	            body.login {
		            background:#<?php echo $cl_options['cl_backgroundColor']; ?> !important;
	            }

            <?php

	    }

	    // text colour
	    if (!empty ($cl_options['cl_linkColor'])) {

        ?>

	        .login #login a {
		        color:#<?php echo $cl_options['cl_linkColor']; ?> !important;
	        }

        <?php

	    }

	    echo '</style>';

    }

}

function wpc_client_custom_login_options () {

    ?>

    <style>
	    .wrap {
		    position:relative;
	    }

	    .cl_notice {
		    padding:10px 20px;
		    -moz-border-radius:3px;
		    -webkit-border-radius:3px;
		    border-radius:3px;
		    background:lightyellow;
		    border:1px solid #e6db55;
		    margin:10px 5px 10px 0;
	    }

	    .cl_notice h3 {
		    margin-top:5px;
		    padding-top:0;
	    }

	    .cl_notice li {
		    list-style-type:disc;
		    margin-left:20px;
	    }

        .wrap input[type=text] {
            width:400px;
        }

        .wrap input[type=password] {
            width:400px;
        }
    </style>

    <div class='wrap'>


        <div class="clear"></div>

        <?php
        global $wpc_client;

        echo $wpc_client->get_plugin_logo_block();
        ?>

        <div class="icon32" id="icon-options-general"></div>
        <h2><?php printf( __( '%s Settings', WPC_CLIENT_TEXT_DOMAIN ), $wpc_client->plugin['title'] ) ?></h2>

        <p><?php printf( __( 'From here you can manage a variety of options for the %s plugin.', WPC_CLIENT_TEXT_DOMAIN ), $wpc_client->plugin['title'] ) ?></p>
        <div id="container23">
            <ul class="menu">
                <li id="general"><a href="admin.php?page=wpclients_settings" ><?php _e( 'General', WPC_CLIENT_TEXT_DOMAIN ) ?></a></li>
                <li id="b_info"><a href="admin.php?page=wpclients_settings&tab=b_info" ><?php _e( 'Business Info', WPC_CLIENT_TEXT_DOMAIN ) ?></a></li>
                <li id="pages"><a href="admin.php?page=wpclients_settings&tab=pages" ><?php _e( 'Pages', WPC_CLIENT_TEXT_DOMAIN ) ?></a></li>
                <li id="capabilities"><a href="admin.php?page=wpclients_settings&tab=capabilities" ><?php _e( 'Capabilities', WPC_CLIENT_TEXT_DOMAIN ) ?></a></li>
                <li id="clogin" class="active"><a href="admin.php?page=custom_login_admin" ><?php _e( 'Custom Login', WPC_CLIENT_TEXT_DOMAIN ) ?></a></li>
                <li id="redirects"><a href="admin.php?page=xyris-login-logout" ><?php _e( 'Login/Logout Redirects', WPC_CLIENT_TEXT_DOMAIN ) ?></a></li>
                <li id="skins"><a href="admin.php?page=wpclients_settings&tab=skins" ><?php _e( 'Skins', WPC_CLIENT_TEXT_DOMAIN ) ?></a></li>
                <li id="alerts"><a href="admin.php?page=wpclients_settings&tab=alerts" ><?php _e( 'Login Alerts', WPC_CLIENT_TEXT_DOMAIN ) ?></a></li>
                <li id="addons"><a href="admin.php?page=wpclients_settings&tab=addons" ><?php _e( 'Addons', WPC_CLIENT_TEXT_DOMAIN ) ?></a></li>
                <?php if ( !$wpc_client->plugin['hide_about_tab'] ) {?>
                <li id="about"><a href="admin.php?page=wpclients_settings&tab=about" ><?php _e( 'About', WPC_CLIENT_TEXT_DOMAIN ) ?></a></li>
                <?php } ?>
            </ul>
            <span class="clear"></span>
            <div class="content23 news">

	            <form action="options.php" method="post">

                    <div class="postbox">
                        <h3 class='hndle'><span><?php _e( 'Custom Login Screen Settings', WPC_CLIENT_TEXT_DOMAIN ) ?></span></h3>
                        <div class="inside">
                        <?php
                            settings_fields (CL_GROUP);
                            do_settings_sections (CL_PAGE);
                        ?>
                        </div>
                    </div>

		            <p class="submit">
			            <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" class="button-primary" />
		            </p>

	            </form>

        </div>
    </div>

<?php
}


function wpc_client_custom_login_init () {

	$vars = wpc_client_custom_login_get_options ();

	register_setting(CL_GROUP, CL_OPTIONS, 'wpc_client_custom_login_validate');

	add_settings_section(CL_SECTION, '', 'wpc_client_custom_login_section_validate', CL_PAGE);


    add_settings_field(
        'cl_enable',
        __('Use Custom Login Settings:', WPC_CLIENT_TEXT_DOMAIN),
        'wpc_client_form_text',
        CL_PAGE,
        CL_SECTION,
        array (
            'id'            => 'cl_enable',
            'type'          => 'select',
            'value'         => $vars,
            'options'       => array(
                'yes' => 'Yes',
                'no' => 'No',
            ),
        )
    );

    add_settings_field(
        'cl_logo_link',
        __('Logo Link:', WPC_CLIENT_TEXT_DOMAIN),
        'wpc_client_form_text',
        CL_PAGE,
        CL_SECTION,
        array (
            'id' => 'cl_logo_link',
            'value' => $vars,
            'description' => '',
        )
    );

    add_settings_field(
        'cl_logo_title',
        __('Logo Title:', WPC_CLIENT_TEXT_DOMAIN),
        'wpc_client_form_text',
        CL_PAGE,
        CL_SECTION,
        array (
            'id' => 'cl_logo_title',
            'value' => $vars,
            'description' => '',
        )
    );

    add_settings_field(
        'cl_background',
        __('Background Image Url:', WPC_CLIENT_TEXT_DOMAIN),
        'wpc_client_form_text',
        CL_PAGE,
        CL_SECTION,
        array (
            'id' => 'cl_background',
            'value' => $vars,
            'description' => __('URL path to image to use for background (sized 312px wide, and around 600px tall so that it can be cropped). You can upload your image with the media uploader', WPC_CLIENT_TEXT_DOMAIN),
        )
    );

	add_settings_field (
		'cl_backgroundColor',
		__('Page Background Color:', WPC_CLIENT_TEXT_DOMAIN),
		'wpc_client_form_text',
		CL_PAGE,
		CL_SECTION,
		array (
			'id' => 'cl_backgroundColor',
			'value' => $vars,
			'description' => __('6 digit hex color code', WPC_CLIENT_TEXT_DOMAIN),
		)
	);

	add_settings_field (
		'cl_color',
		__('Text Color:', WPC_CLIENT_TEXT_DOMAIN),
		'wpc_client_form_text',
		CL_PAGE,
		CL_SECTION,
		array (
			'id' => 'cl_color',
			'value' => $vars,
			'description' => __('6 digit hex color code', WPC_CLIENT_TEXT_DOMAIN),
		)
	);

	add_settings_field (
		'cl_linkColor',
		__('Text Link Color:', WPC_CLIENT_TEXT_DOMAIN),
		'wpc_client_form_text',
		CL_PAGE,
		CL_SECTION,
		array (
			'id' => 'cl_linkColor',
			'value' => $vars,
			'description' => __('6 digit hex color code', WPC_CLIENT_TEXT_DOMAIN),
		)
	);

}


function wpc_client_custom_login_validate ($fields) {

	// colour validation
	$fields['cl_color'] = str_replace ('#', '', $fields['cl_color']);

	//$fields['cl_color'] = str_pad ('f', 6, $fields['cl_color'], STR_PAD_RIGHT);

	$fields['cl_color'] = substr ($fields['cl_color'], 0, 6);

	// background colour validation
	$fields['cl_backgroundColor'] = str_replace ('#', '', $fields['cl_backgroundColor']);

	//$fields['cl_backgroundColor'] = str_pad ('f', 6, $fields['cl_backgroundColor'], STR_PAD_RIGHT);

	$fields['cl_backgroundColor'] = substr ($fields['cl_backgroundColor'], 0, 6);

	// colour validation
	$fields['cl_linkColor'] = str_replace ('#', '', $fields['cl_linkColor']);

	//$fields['cl_linkColor'] = str_pad ('f', 6, $fields['cl_linkColor'], STR_PAD_RIGHT);

	$fields['cl_linkColor'] = substr ($fields['cl_linkColor'], 0, 6);

	// clean image urls
	$fields['cl_background'] = esc_url_raw ($fields['cl_background']);

	return $fields;
}


function wpc_client_custom_login_section_validate ($fields) {
	return $fields;
}


function wpc_client_form_text ($args) {

	// defaults
	$id             = '';
	$value          = '';
	$description    = '';

	// set values
	if (!empty ($args['value'][$args['id']])) {
		$value = $args['value'][$args['id']];
	} else {
		if (!empty ($args['default'])) {
			$value = $args['default'];
		}
	}

	if (!empty ($args['description'])) {
		$description = $args['description'];
	}

	$id = $args['id'];


    if ( isset( $args['type'] ) && 'select' == $args['type'] ) {

        echo '<select id="' . $id .'" name="' . CL_OPTIONS . '[' . $id . ']" >';
        foreach ( $args['options'] as $key => $val ) {
            if ( $key == $value )
                echo '<option value="' . $key .'" selected >' . $val .'</option>';
            else
                echo '<option value="' . $key .'">' . $val .'</option>';
        }

        echo '</select>';

    } else {
       echo '<input type="text" id="' . $id . '" name="' . CL_OPTIONS . '[' . $id . ']" value="' . $value . '" class="regular-text"/>';
    }

    if (!empty ($description)) {
        echo '<br /><span class="description">' . $description . '</span>';
    }

}
