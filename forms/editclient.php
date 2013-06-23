<?php

//check auth
if ( !current_user_can( 'wpc_edit_clients' ) && !current_user_can( 'administrator' ) ) {
    wp_redirect( get_admin_url() . 'admin.php?page=wpclients' );
}

extract($_REQUEST);

$error = '';

if ( isset( $btnAdd ) ) {
	// validate at php side
	if ( empty( $contact_name ) ) // empty username
		$error .= __('A Contact Name is required.<br/>', WPC_CLIENT_TEXT_DOMAIN);

    if ( empty( $contact_email ) ) // empty email
        $error .= __('A email is required.<br/>', WPC_CLIENT_TEXT_DOMAIN);

	if ( email_exists( $contact_email ) ) {
        if ( $ID != get_user_by( 'email', $contact_email )->ID ) {
            // email already exist
            $error .= __( 'Email address already uses.<br/>', WPC_CLIENT_TEXT_DOMAIN );
        }
    }

	if ( isset($update_password) && ( empty( $contact_password ) || empty( $contact_password2 ) ) ) {
			if ( empty( $contact_password ) ) // password
				$error .= __("Sorry, password is required.<br/>", WPC_CLIENT_TEXT_DOMAIN);
			elseif ( empty( $contact_password2 ) ) // confirm password
				$error .= __("Sorry, confirm password is required.<br/>", WPC_CLIENT_TEXT_DOMAIN);
			elseif ( $contact_password != $contact_password2 )
				$error .= __("Sorry, Passwords are not matched! .<br/>", WPC_CLIENT_TEXT_DOMAIN);
	}


	if ( empty( $error ) ) {

		$userdata = array(
			'ID'                => esc_attr($ID),
			'user_pass'         => $contact_password2,
            'user_login'        => esc_attr( get_userdata($ID)->get( 'user_login' ) ),
			'nickname'          => esc_attr( trim( $contact_name ) ),
			'user_email'        => esc_attr( $contact_email ),
//			'first_name'        => esc_attr( trim( $business_name ) ),
            'contact_phone'     => esc_attr( $contact_phone ),
            'send_password'     => ( isset( $send_password ) && '1' == $send_password ) ? '1' : '0',
            'update_password'   => ( isset( $update_password ) && '1' == $send_password ) ? '1' : '0',
            'admin_manager'     => esc_attr( $admin_manager ),

		);

        //set custom fields
        if ( isset( $custom_fields ) )
            $userdata['custom_fields'] = $custom_fields;

		if ( !isset( $update_password ) ) {
			unset( $userdata['user_pass'] );
		}

        //for time limited clients
        if ( defined( 'WPC_CLIENT_ADDON_TIME_LIMITED_CLIENTS' ) ) {
            $userdata['expiration_date'] = esc_attr( $expiration_date );
        }

		do_action('wp_clients_update', $userdata );
		do_action('wp_client_redirect', get_admin_url(). 'admin.php?page=wpclients&msg=u');
		exit;
	}
}

global $wpdb;

$client = get_userdata( $id );


//get managers
$args = array(
    'role'      => 'wpc_manager',
    'orderby'   => 'user_login',
    'order'     => 'ASC',
    'fields'    => array( 'ID','user_login' ),

);

$managers               = get_users( $args );
$current_manager_id     = get_user_meta( $id, 'admin_manager', true );
$business_name          = get_user_meta( $id, 'wpc_cl_business_name', true );

?>

<style type="text/css">

    .wrap input[type=text] {
        width:400px;
    }

    .wrap input[type=password] {
        width:400px;
    }

</style>

<div class='wrap'>

    <?php echo $this->get_plugin_logo_block() ?>

    <div class="clear"></div>

    <div id="container23">
        <ul class="menu">
                <?php echo $this->gen_tabs_menu( 'clients' ) ?>
        </ul>

        <span class="clear"></span>
        <div class="content23 news">

            <div class="icon32" id="icon-edit"><br></div>
            <h2><?php _e( 'Edit Client', WPC_CLIENT_TEXT_DOMAIN ) ?>:</h2>

            <div id="message" class="updated fade" <?php echo ( empty( $error ) )? 'style="display: none;" ' : '' ?> ><?php echo $error; ?></div>

            <form action="" method="post">
                <input type="hidden" name="wpc_action" value="client_edit" />
                <input type="hidden" name="contact_username" value="<?php echo $client->user_login?>" />
                <input type="hidden" name="ID" value="<?php echo $id;?>" />
                <table class="form-table">
                    <tr>
                        <td>
    	                    <label for="business_name"><?php _e( 'Business Name', WPC_CLIENT_TEXT_DOMAIN ) ?> <?php _e( "(can't be changed)", WPC_CLIENT_TEXT_DOMAIN ) ?>: </label> <br/>
                            <input type="text" id="business_name" disabled="disabled" value="<?php if ( $error ) echo esc_html( $_REQUEST['business_name'] ); else echo $business_name; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
    	                    <label for="contact_name"><?php _e( 'Contact Name', WPC_CLIENT_TEXT_DOMAIN ) ?>:</label> <br/>
                            <input type="text" id="contact_name" name="contact_name" value="<?php if ( $error ) echo esc_html( $_REQUEST['contact_name'] ); else echo $client->nickname; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
    	                    <label for="contact_email"><?php _e( 'Email', WPC_CLIENT_TEXT_DOMAIN ) ?>:</label> <br/>
                            <input type="text" id="contact_email" name="contact_email" value="<?php if ( $error ) echo esc_html( $_REQUEST['contact_email'] ); else echo $client->user_email ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
    	                    <label for="contact_phone"><?php _e( 'Phone', WPC_CLIENT_TEXT_DOMAIN ) ?>:</label> <br/>
                            <input type="text" id="contact_phone" name="contact_phone" value="<?php if ( $error ) echo esc_html( $_REQUEST['contact_phone'] ); else echo $client->wp_contact_phone?>" />
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <hr />
                            <label for="contact_username"><?php _e( 'Username', WPC_CLIENT_TEXT_DOMAIN ) ?> <?php _e( "(can't be changed)", WPC_CLIENT_TEXT_DOMAIN ) ?>:</label> <br/>
                            <input type="text" disabled="disabled" value="<?php if ( $error ) echo esc_html( $_REQUEST['contact_username'] ); else echo $client->user_login?>" />
                        </td>
                    </tr>
                    <?php
                    //for time limited clients
                    if ( defined( 'WPC_CLIENT_ADDON_TIME_LIMITED_CLIENTS' ) ) {
                        $wpc_expiration_date = get_user_meta( $id, 'wpc_expiration_date', true );
                        if ( false == $wpc_expiration_date ) {
                            $wpc_expiration_date = '';
                        } else {
                            $wpc_expiration_date = date( 'm/d/Y', $wpc_expiration_date );
                        }
                    ?>
                    <tr>
                        <td>
                            <label>
                                <?php _e( 'Expiration Date:', WPC_CLIENT_TEXT_DOMAIN ) ?>
                                <br />
                                <input type="text" style="width: 75px" id="expiration_date" name="expiration_date" value="<?php echo ( $error ) ? esc_html( $_REQUEST['business_name'] ) : $wpc_expiration_date ?>"/>
                            </label>
                                    <a href="javascript:;" class="wpc_set_expiration_date" rel="<?php echo date( 'm/d/Y', ( time() + 3600*24*15 ) ) ?>">15 <?php _e( 'Day(s)', WPC_CLIENT_TEXT_DOMAIN ) ?></a>
                                    |
                                    <a href="javascript:;" class="wpc_set_expiration_date" rel="<?php echo date( 'm/d/Y', ( time() + 3600*24*30 ) ) ?>">30 <?php _e( 'Day(s)', WPC_CLIENT_TEXT_DOMAIN ) ?></a>
                                    |
                                    <a href="javascript:;" class="wpc_set_expiration_date" rel="<?php echo date( 'm/d/Y', ( time() + 3600*24*90 ) ) ?>">90 <?php _e( 'Day(s)', WPC_CLIENT_TEXT_DOMAIN ) ?></a>
                                    |
                                    <a href="javascript:;" class="wpc_set_expiration_date" rel="<?php echo date( 'm/d/Y', ( time() + 3600*24*182 ) ) ?>">6 <?php _e( 'Months', WPC_CLIENT_TEXT_DOMAIN ) ?></a>
                                    |
                                    <a href="javascript:;" class="wpc_set_expiration_date" rel="<?php echo date( 'm/d/Y', ( time() + 3600*24*365 ) ) ?>">1 <?php _e( 'Year', WPC_CLIENT_TEXT_DOMAIN ) ?></a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td>
                            <label for="admin_manager"><?php _e( 'Admin Manager', WPC_CLIENT_TEXT_DOMAIN ) ?>:</label> <br/>
                            <select name="admin_manager" id="admin_manager">
                                <option value="0"><?php _e( 'None', WPC_CLIENT_TEXT_DOMAIN ) ?></option>
                                <?php
                                if ( is_array( $managers ) && 0 < count( $managers ) ) {
                                    foreach( $managers as $manager ) {
                                        if ( $manager->ID == $current_manager_id )
                                            $selected = 'selected';
                                        else
                                            $selected = '';

                                        echo '<option value="' . $manager->ID . '" ' . $selected . ' >' . $manager->user_login . ' </option>';
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php
                                $client_groups = $this->get_client_groups_id( $id );
                            ?>
                            <label><?php _e( 'Client Circles', WPC_CLIENT_TEXT_DOMAIN ) ?>:</label> <br/>
                            <span class="edit"><a href="#circles_popup_block" rel="circles" class="fancybox_link" title="assign Client Circles" ><?php _e( 'Select Client Circles', WPC_CLIENT_TEXT_DOMAIN ) ?></a></span>&nbsp;&nbsp;&nbsp;<span class="edit" id="counter_circles">(<?php echo count($client_groups);?>)</span>
                            <input type="hidden" name="circles" id="circles" value="<?php echo implode( ',', $client_groups ); ?>" />

                        </td>
                    </tr>
                    <tr>
                        <td>
    	                    <label for="contact_password"><?php _e( 'Password', WPC_CLIENT_TEXT_DOMAIN ) ?>:</label> <br/>
                            <input type="password" id="contact_password" name="contact_password" value="<?php if ( $error ) echo esc_html( $_REQUEST['contact_password'] ); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
    	                    <label for="contact_password2"><?php _e( 'Confirm Password', WPC_CLIENT_TEXT_DOMAIN ) ?>:</label> <br/>
                            <input type="password" id="contact_password2" name="contact_password2" value="<?php if ( $error ) echo esc_html( $_REQUEST['contact_password2'] ); ?>" />
                        </td>
                    </tr>
			        <tr>
                        <td>
                            <input type="checkbox" id="update_password" name="update_password" value="1" /> <label for="update_password"><?php _e( 'Update Password? Checking this box will change the password', WPC_CLIENT_TEXT_DOMAIN ) ?></label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" id="send_password" name="send_password" value="1" /><label for="send_password"> <?php _e( 'Send this password to the updated user by email', WPC_CLIENT_TEXT_DOMAIN ) ?></label>
                            <hr />
                        </td>
                    </tr>
                    <tr>
                        <td>
		                    <input type='submit' name='btnAdd' id="btnAdd" class='button-primary' value='<?php _e( 'Update Client', WPC_CLIENT_TEXT_DOMAIN ) ?>' />
		                    &nbsp; &nbsp; &nbsp;
		                    <input type='reset' name='btnreset' class='button-secondary' value='<?php _e( 'Reset Form', WPC_CLIENT_TEXT_DOMAIN ) ?>' />
                        </td>
                    </tr>
                </table>

            </form>

        </div>
    </div>
</div>
<?php
    $current_page = isset( $_GET['page'] ) ? $_GET['page'] : '';
    $this->get_assign_circles_popup( $current_page );
?>

<script type="text/javascript" language="javascript">
    var site_url = '<?php echo site_url();?>';

    jQuery( document ).ready( function( $ ) {

	    <?php echo ( empty( $error ) )? '$( "#message" ).hide();' : '' ?>

	    $( "#btnAdd" ).live ( 'click', function() {

		    var msg = '';

		    var emailReg = /^([\w-+\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

//		    if ( $( "#business_name" ).val() == '' ) {
//			    msg += "<?php _e( 'Business Name required.', WPC_CLIENT_TEXT_DOMAIN ) ?><br/>";
//		    }

		    if ( $( "#contact_name" ).val() == '' ) {
			    msg += "<?php _e( 'Contact Name required.', WPC_CLIENT_TEXT_DOMAIN ) ?><br/>";
		    }

		    if ( $( "#contact_email" ).val() == '' ) {
			    msg += "<?php _e( 'Email required.', WPC_CLIENT_TEXT_DOMAIN ) ?><br/>";
		    } else if ( !emailReg.test( $( "#contact_email" ).val() ) ) {
			    msg += "<?php _e( 'Invalid Email.', WPC_CLIENT_TEXT_DOMAIN ) ?><br/>";
		    }

		    if ( $( "#update_password" ).is(':checked') ) {
			    if ( $( "#contact_password" ).val() == '' ) {
				    msg += "<?php _e( 'Password required.', WPC_CLIENT_TEXT_DOMAIN ) ?><br/>";
			    } else if ( $( "#contact_password2" ).val() == '' ) {
				    msg += "<?php _e( 'Confirm Password required.', WPC_CLIENT_TEXT_DOMAIN ) ?><br/>";
			    } else if ( $( "#contact_password" ).val() != $( "#contact_password2" ).val() ) {
				    msg += "<?php _e( 'Passwords are not matched.', WPC_CLIENT_TEXT_DOMAIN ) ?><br/>";
			    }
		    }

		    if ( msg != '' ) {
			    $( "#message" ).html( msg );
			    $( "#message" ).show();
			    return false;
		    }
	    });


        <?php
        //for time limited clients
        if ( defined( 'WPC_CLIENT_ADDON_TIME_LIMITED_CLIENTS' ) ) {
        ?>

        //data piker
        jQuery( '#expiration_date' ).datepicker({
            dateFormat : 'mm/dd/yy'
        });


        //Set pre-set expiration date
        jQuery( '.wpc_set_expiration_date' ).click( function() {
            jQuery( '#expiration_date' ).val( jQuery( this ).attr( 'rel' ) );
        });

        <?php
        }
        ?>

    });

</script>