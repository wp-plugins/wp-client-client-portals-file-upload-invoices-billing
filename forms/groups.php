<?php
global $wpdb;

$groups = $this->get_groups();

//Display status message
if ( isset( $_GET['updated'] ) ) {
    ?><div id="message" class="updated fade"><p><?php echo urldecode( $_GET['dmsg'] ); ?></p></div><?php
}

?>

<script type="text/javascript">
    var site_url = '<?php echo site_url();?>';

    jQuery( document ).ready( function() {

        //Show/hide new Client Circle form
        jQuery( '#slide_new_form_panel' ).click( function() {
            jQuery( '#new_form_panel' ).slideToggle( 'slow' );
            jQuery( this ).toggleClass( 'active' );
            return false;
        });


        //Add Client Circle action
        jQuery( "#add_group" ).click( function() {

            jQuery( '#group_name' ).parent().parent().attr( 'class', '' );

            if ( "" == jQuery( "#group_name" ).val() ) {
                jQuery( '#group_name' ).parent().parent().attr( 'class', 'wpc_error' );
                return false;
            }

            jQuery( '#wpc_action' ).val( 'create_group' );
            jQuery( '#create_group' ).submit();
        });


        var group_name          = "";
        var group_auto_select   = "";
        var group_auto_add_clients   = "";


        jQuery.fn.editGroup = function ( id, action ) {
            if ( action == 'edit' ) {
                if( jQuery('#edit_group input[name=group_name]').length ) {
                    return;
                }
                group_name = jQuery( '#group_name_block_' + id ).html();
                group_name = group_name.replace(/(^\s+)|(\s+$)/g, "");

                jQuery( '#group_name_block_' + id ).html( '<input type="text" name="group_name" size="30" id="edit_group_name"  value="' + group_name + '" /><input type="hidden" name="group_id" value="' + id + '" />' );

                group_auto_select = jQuery( '#auto_select_block_' + id ).html();
                group_auto_select = group_auto_select.replace(/(^\s+)|(\s+$)/g, "");

                group_auto_add_clients = jQuery( '#auto_add_clients_block_' + id ).html();
                group_auto_add_clients = group_auto_add_clients.replace(/(^\s+)|(\s+$)/g, "");

                if ( 'Yes' == group_auto_select )
                    jQuery( '#auto_select_block_' + id ).html( '<input type="checkbox" name="auto_select" id="edit_auto_select" value="1" checked="checked" />' );
                else
                    jQuery( '#auto_select_block_' + id ).html( '<input type="checkbox" name="auto_select" id="edit_auto_select" value="1" />' );

                if ( 'Yes' == group_auto_add_clients )
                    jQuery( '#auto_add_clients_block_' + id ).html( '<input type="checkbox" name="auto_add_clients" id="edit_auto_add_clients" value="1" checked="checked" />' );
                else
                    jQuery( '#auto_add_clients_block_' + id ).html( '<input type="checkbox" name="auto_add_clients" id="edit_auto_add_clients" value="1" />' );


                //jQuery( '#edit_group input[type="button"]' ).attr( 'disabled', true );

                jQuery( this ).attr('onclick', "jQuery(this).editGroup( "+ id +", 'close' );" );
                jQuery( this ).html('<?php _e( 'Close', WPC_CLIENT_TEXT_DOMAIN ) ?>');

                jQuery( '#save_block_' + id ).html( '<a onClick="jQuery(this).saveGroup();"><?php _e( 'Save', WPC_CLIENT_TEXT_DOMAIN ) ?></a>' );

                return;
            }

            if ( action == 'close' ) {
                jQuery( '#group_name_block_' + id ).html( group_name );
                jQuery( '#auto_select_block_' + id ).html( group_auto_select );
                jQuery( '#auto_add_clients_block_' + id ).html( group_auto_add_clients );

                jQuery( this ).html('<?php _e( 'Edit', WPC_CLIENT_TEXT_DOMAIN ) ?>');
                jQuery( this ).attr('onclick', "jQuery(this).editGroup( "+ id +", 'edit' );" );

                 jQuery( '#save_block_' + id ).html( '' );

                return;
            }


        };


        jQuery.fn.saveGroup = function ( ) {

            jQuery( '#edit_group_name' ).parent().parent().attr( 'class', '' );

            if ( '' == jQuery( '#edit_group_name' ).val() ) {
                jQuery( '#edit_group_name' ).parent().parent().attr( 'class', 'wpc_error' );
                return false;
            }

            jQuery( '#wpc_action2' ).val( 'edit_group' );
            jQuery( '#edit_group' ).submit();
        };


        jQuery.fn.deleteGroup = function ( id ) {
            jQuery( '#wpc_action2' ).val( 'delete_group' );
            jQuery( '#group_id' ).val( id );
            jQuery( '#edit_group' ).submit();
        };

    });
</script>


<div class="wrap">

    <?php echo $this->get_plugin_logo_block() ?>

    <h2><?php _e( 'Client Circles', WPC_CLIENT_TEXT_DOMAIN ) ?>:</h2>

    <div id="slide_new_form_panel">
        <h3><?php _e( 'Create New Client Circle', WPC_CLIENT_TEXT_DOMAIN ) ?> <span class="arrow"></span></h3>
    </div>

    <div id="new_form_panel">
        <form method="post" action="" name="create_group" id="create_group" >
            <input type="hidden" name="wpc_action" id="wpc_action" value="" />

            <table class="form-table">
                <tr>
                    <td>
                        <?php _e( 'Client Circle Name', WPC_CLIENT_TEXT_DOMAIN ) ?>:<span class="required">*</span>
                        <input type="text" class="input" name="group_name" id="group_name" value="" size="30" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            <input type="checkbox" name="auto_select" id="auto_select" value="1" /> <?php _e( 'Auto-Select this Client Circle on the Add Portal Page', WPC_CLIENT_TEXT_DOMAIN ) ?>
                        </label>
                    </td>
                </tr>
                 <tr>
                    <td>
                        <label>
                            <input type="checkbox" name="auto_add_clients" id="auto_add_clients" value="1" /> <?php _e( 'Auto Add new Clients to Circle', WPC_CLIENT_TEXT_DOMAIN ) ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            <input type="checkbox" name="assign_all" id="assign_all" value="1" /> <?php _e( 'Assign all existing Clients', WPC_CLIENT_TEXT_DOMAIN ) ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="button" name="add_group" id="add_group" value="<?php _e( 'Add Client Circle', WPC_CLIENT_TEXT_DOMAIN ) ?>" />
                    </td>
                </tr>
            </table>

        </form>
    </div>


    <h3><?php _e( 'List of Client Circles', WPC_CLIENT_TEXT_DOMAIN ) ?>:</h3>
    <form method="post" action="" name="edit_group" id="edit_group" >
        <input type="hidden" name="wpc_action" id="wpc_action2" value="" />
        <input type="hidden" name="group_id" id="group_id" value="" />
        <table width="700px" class="widefat post fixed" style="width:95%;">
            <thead>
                <tr>
                    <th><?php _e( 'Client Circle Na', WPC_CLIENT_TEXT_DOMAIN ) ?>me</th>
                    <th><?php _e( 'Auto-Select', WPC_CLIENT_TEXT_DOMAIN ) ?></th>
                    <th><?php _e( 'Auto-Add Clients', WPC_CLIENT_TEXT_DOMAIN ) ?></th>
                    <th><?php _e( 'Assign Clients', WPC_CLIENT_TEXT_DOMAIN ) ?></th>
                    <th width="200px"><?php _e( 'Actions', WPC_CLIENT_TEXT_DOMAIN ) ?></th>
                </tr>
            </thead>
        <?php
        $i = 0;
        if ( $groups )
            foreach( $groups as $group ) {
                if ( $i % 2 == 0 )
                    echo "<tr class='alternate'>";
                else
                    echo "<tr class='' >";

                $i++;
        ?>
                <td style="vertical-align: middle;">
                    <span id="group_name_block_<?php echo $group['group_id'];?>">
                        <?php echo $group['group_name']; ?>
                    </span>
                </td>
                <td style="vertical-align: middle;">
                    <span id="auto_select_block_<?php echo $group['group_id'];?>">
                        <?php
                        if ( "1" == $group['auto_select'] )
                            echo 'Yes';
                        else
                            echo 'No';
                        ?>
                    </span>
                </td>
                <td style="vertical-align: middle;">
                    <span id="auto_add_clients_block_<?php echo $group['group_id'];?>">
                        <?php
                        if ( "1" == $group['auto_add_clients'] )
                            echo 'Yes';
                        else
                            echo 'No';
                        ?>
                    </span>
                </td>
                <td style="vertical-align: middle;" class="action_links">
                    <span class="edit"><a href="#popup_block2" rel="clients<?php echo $group['group_id'];?>" class="fancybox_link" title="assign clients to '<?php echo $group['group_name'] ?>'" ><?php _e( 'Assign', WPC_CLIENT_TEXT_DOMAIN ) ?></a></span>&nbsp;&nbsp;&nbsp;<span class="edit action_links" id="counter_clients<?php echo $group['group_id'];?>">(<?php echo $group['clients_count'];?>)</span>
                    <?php $clients_id = $this->get_group_clients_id( $group['group_id'] ); ?>
                    <input type="hidden" name="<?php echo $group['group_id'];?>" id="clients<?php echo $group['group_id'];?>" class="change_clients" value="<?php echo implode(',',$clients_id);?>" />
                </td>
                <td style="vertical-align: middle;" class="action_links">
                    <span class="action_links"><a href="javascript:void(0);" id="edit_button_<?php echo $group['group_id'];?>" onclick="jQuery(this).editGroup( <?php echo $group['group_id'];?>, 'edit' );" ><?php _e( 'Edit', WPC_CLIENT_TEXT_DOMAIN ) ?></a></span>
                    <span id="save_block_<?php echo $group['group_id'];?>"></span>
                    <span class="action_links"><a href="javascript:void(0);" class="group_delete" onclick="jQuery(this).deleteGroup( <?php echo $group['group_id'];?> );"><?php _e( 'Delete', WPC_CLIENT_TEXT_DOMAIN ) ?></a></span>
                </td>
            </tr>
        <?php
            }
        ?>
        </table>
        <?php
        $current_page = isset( $_GET['page'] ) ? $_GET['page'] : '';
        $this->get_assign_clients_popup( $current_page );
        ?>
    </form>

</div><!--/wrap-->