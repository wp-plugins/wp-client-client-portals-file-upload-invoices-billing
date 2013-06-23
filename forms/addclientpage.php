<?php

global $wpdb;

$client_page_name   = ( isset( $_POST['client_page_name'] ) ) ? $_POST['client_page_name'] : '';
$selected_page_name = ( isset( $_POST['selected_page_name'] ) ) ? $_POST['selected_page_name'] : '';
$users              = ( isset( $_POST['clients'] ) ) ? $_POST['clients'] : '';
if( $users == 'all' ) {
    $users = $this->get_client_ids();
} else {
    $users = explode( ',', $users );
}
$groups_id          = ( isset( $_POST['circles'] ) ) ? $_POST['circles'] : '';
if( $groups_id == 'all' ) {
    $groups_id = $this->get_group_ids();
} else {
    $groups_id = explode( ',', $groups_id );
}
//$page_name_id       = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '" . $selected_page_name . "'");
$page_name_id       = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s",$selected_page_name) );
$error              = '';

if ( $selected_page_name == 'wp-default' ) {
    $template_content = get_option( 'client_template' );
    $template_content = html_entity_decode( $template_content );
} else {
    //$template_content = $wpdb->get_var( "SELECT post_content FROM $wpdb->posts WHERE post_name = '" . $selected_page_name . "'" );
	$template_content = $wpdb->get_var( $wpdb->prepare("SELECT post_content FROM $wpdb->posts WHERE post_name = %s",$selected_page_name) );
}

//save Portal Page
if ( isset( $_POST['create_clientpage'] ) ) {
    if ( $client_page_name != '' ) {

        //???
       // $sql = "SELECT template FROM {$wpdb->prefix}wpc_client_clients_page WHERE template='" . $page_name_id . "'";

 	   // $query = mysql_query($sql);

		    //	$already_exisits= mysql_num_rows($query);

    //	 		if($already_exisits!=0)

    //			{

    //				$message="Page Already Exists ! Please Edit Clients Page to Assign New Users";

    //			}

    //			else

    //			{


        // Create post object
        $my_post = array(
            'post_title'        => esc_html( $client_page_name ),
            'post_content'      => $template_content,
            'post_status'       => 'publish',
            'post_author'       => 1,
            'post_type'         => 'clientspage',
            'comment_status'    => 'closed'
        );

        // Insert the post into the database
        $client_page_id = wp_insert_post( $my_post );

        update_post_meta( $client_page_id, 'user_ids', $users );

        //update clientpage file template
        if ( isset( $_POST['clientpage_template'] ) && 'default' != $_POST['clientpage_template'] ) {
            update_post_meta( $client_page_id, '_wp_page_template', $_POST['clientpage_template'] );
        }

        //save Client Circles for Portal Page
        if ( 0 < count( $groups_id ) )
            update_post_meta( $client_page_id, 'groups_id', $groups_id );
        else
            update_post_meta( $client_page_id, 'groups_id', null );

		//$sql_query = "INSERT INTO {$wpdb->prefix}wpc_client_clients_page SET pagename ='" . $client_page_name . "',template='" . $page_name_id . "',users='" . implode( ',', $users ) . "'";

        //mysql_query( $sql_query );

        //	}

		$wpdb->insert(
			"{$wpdb->prefix}wpc_client_clients_page",
			array(
				'pagename' => $client_page_name,
				'template' => $page_name_id,
				'users' => implode( ',', $users )
			)
		);

        do_action( 'wp_client_redirect', get_admin_url(). 'admin.php?page=add_client_page&msg=a' );
        exit;
    } else {
        $error .= __( 'You must enter Portal Page Title.<br/>', WPC_CLIENT_TEXT_DOMAIN );
    }

}
?>

<style type="text/css">
    .wrap input[type=text] {
        width:200px;
    }
</style>

<div class='wrap'>

    <?php echo $this->get_plugin_logo_block() ?>

    <div class="clear"></div>

    <?php
    if ( isset( $_GET['msg'] ) ) {
        switch( $_GET['msg'] ) {
            case 'a':
                echo '<div id="message" class="updated fade"><p>' . __( 'Portal Page is added.', WPC_CLIENT_TEXT_DOMAIN ) . '</p></div>';
                break;
        }
    }
    ?>

    <div class="icon32" id="icon-edit"><br></div>
    <h2>Add Portal Page:</h2>
	<hr />

    <div id="message" class="updated fade" <?php echo ( empty( $error ) )? 'style="display: none;" ' : '' ?> ><?php echo $error; ?></div>

    <form action="admin.php?page=add_client_page" method="post">
        <table>
            <tr>
                <td style="border-right:#666 solid 2px; width:220px; height:400px; vertical-align:top;">
                    <p>
    	                <label for="client_page_name"><?php _e( 'Portal Page Title', WPC_CLIENT_TEXT_DOMAIN ) ?>:</label> <br/>
                        <input type="text" id="client_page_name" name="client_page_name" value="<?php echo esc_html( $client_page_name ) ?>" />
                    </p>
                    <p>
                        <label for="selected_page_name"><?php _e( 'Page Content', WPC_CLIENT_TEXT_DOMAIN ) ?>:</label> <br/>
                        <select name="selected_page_name">
                            <option value="wp-default"><?php _e( 'Default Portal Page Template', WPC_CLIENT_TEXT_DOMAIN ) ?></option>
                            <?php
                            global $post;

                            $args = array(
                                'post_type'         => 'page',
                                'posts_per_page'    => -1,
                            );

                            $myposts = get_posts( $args );
                            foreach( $myposts as $post ) :
                                setup_postdata( $post );
                                ?>
                                <option><?php echo ucwords( $post->post_name ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p>
                    <?php
                    if ( 0 != count( get_page_templates() ) ) {
                    ?>
                        <label for="selected_page_name"><?php _e( 'Template', WPC_CLIENT_TEXT_DOMAIN ) ?>:</label> <br/>
                        <label class="screen-reader-text" for="clientpage_template"><?php _e( 'Portal Page Template', WPC_CLIENT_TEXT_DOMAIN ) ?></label>
                        <select name="clientpage_template" id="clientpage_template">
                            <option value='default'><?php _e( 'Default Template', WPC_CLIENT_TEXT_DOMAIN ); ?></option>
                            <?php page_template_dropdown( false ); ?>
                        </select>
                    <?php
                    } else {
                        _e( "Didn't find any page templates", WPC_CLIENT_TEXT_DOMAIN );
                    }
                   ?>
                    </p>
                </td>

               <td style="vertical-align:top; width:500px; padding-left:10px;">
                   <br />

                   <strong><?php _e( 'Select Clients who will have permissions for this Portal Page', WPC_CLIENT_TEXT_DOMAIN ) ?></strong>
                   <br />
                   <span style="color: #800000; font-size: x-small;"><em><?php _e( 'This can be changed later in the editing interface for the appropriate Portal Page', WPC_CLIENT_TEXT_DOMAIN ) ?></em></span>
                   <br />
                   <span class="edit"><a href="#popup_block2" rel="clients" class="fancybox_link" title="assign clients" ><?php _e( 'Assign Clients', WPC_CLIENT_TEXT_DOMAIN ) ?></a></span>&nbsp;&nbsp;&nbsp;<span class="edit" id="counter_clients">(0)</span>
                   <input type="hidden" name="clients" id="clients" value="<?php echo implode( ',', $users ); ?>" />
                    <br />
                    <br />

                    <strong><?php _e( 'Select Client Circles who will have permissions for this Portal Page', WPC_CLIENT_TEXT_DOMAIN ) ?></strong>
                    <br />
                    <span style="color: #800000; font-size: x-small;"><em><?php _e( 'This can be changed later in the editing interface for the appropriate Portal Page', WPC_CLIENT_TEXT_DOMAIN ) ?></em></span>
                    <br />
                    <span class="edit"><a href="#circles_popup_block" rel="circles" class="fancybox_link" title="assign Client Circles" ><?php _e( 'Assign Client Circles', WPC_CLIENT_TEXT_DOMAIN ) ?></a></span>&nbsp;&nbsp;&nbsp;<span class="edit" id="counter_circles">(0)</span>
                    <input type="hidden" name="circles" id="circles" value="<?php echo implode( ',', $groups_id ); ?>" />
                </td>
            </tr>
            <tr>
                <td>
	                <hr /><br />
                    <input type="submit" name="create_clientpage" id="submit" class='button-primary' value="<?php _e( 'Create New Portal Page', WPC_CLIENT_TEXT_DOMAIN ) ?>"  />
                </td>
                <td>
                </td>
            </tr>
        </table>
       <?php
            $current_page = isset( $_GET['page'] ) ? $_GET['page'] : '';
            $this->get_assign_clients_popup( $current_page );
            $this->get_assign_circles_popup( $current_page );
       ?>
     </form>
</div>



<script type="text/javascript">
    var site_url = '<?php echo site_url();?>';

    jQuery(document).ready(function(){

        //submit message
        jQuery( "#submit" ).click( function() {
            if ( ''== jQuery( "#client_page_name" ).val() ) {
                jQuery( '#client_page_name' ).parent().attr( 'class', 'wpc_error' );
                jQuery( '#client_page_name' ).focus();
                return false;
            }
            return true;
        });

    });
</script>
