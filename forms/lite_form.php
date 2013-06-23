<style type="text/css">

.wrap input[type=text] {
    width:400px;
}

.wrap input[type=password] {
    width:400px;
}

.wrap textarea {
    width:400px;
}

.screenshots_text {
    float: left;
    margin: 10px 20px 0 0;
}

.screenshots {
    margin: 0 auto;
    width: 100%;
    height: 200px;
}

.gallery {
    margin: 0 auto;
    float: left;
    width: 950px;
}

.image {
    margin: 5px;
    padding: 10px;
    width: 150px;
    height: 75px;
    border: 1px solid #aaa;
}

.image:hover {
    margin: 5px;
    padding: 10px;
    width: 150px;
    height: 75px;
    border: 1px solid #000;
}

.actions a {
    float: left;
    margin-top: 7px;
    margin-bottom: 3px;
}

</style>

<script type="text/javascript">
jQuery(document).ready(function() {

    jQuery(".fancybox").fancybox({
        openEffect    : 'none',
        closeEffect    : 'none'
    });
});
</script>

<div class='wrap'>

    <?php echo $this->get_plugin_logo_block() ?>

    <div class="clear"></div>

        <?php

        if( isset( $_GET['page'] ) && !empty( $_GET['page'] ) ) {

            if( 'wpclients_messages' == $_GET['page'] )  {

                ?>
                <h2><?php _e( 'Messages:', WPC_CLIENT_TEXT_DOMAIN ) ?></h2>
                <br />
                <span><?php _e( $this->advertising_message, WPC_CLIENT_TEXT_DOMAIN ) ?></span>
                <hr>
                <div class="screenshots">
                    <span class="screenshots_text"><?php _e( 'Screenshots:', WPC_CLIENT_TEXT_DOMAIN ) ?></span>
                    <div class="gallery">
                    <?php
                        foreach ( $this->screenshots[$_GET['page']] as $filename ) { ?>
                            <a class="fancybox" rel="<?php echo $_GET['page'] ?>" href="<?php echo $this->screenshots_path . $filename ?>"><img class="image" src="<?php echo $this->screenshots_path . $filename ?>" alt=""></a> <?php
                        }
                    ?>
                    </div>
                </div>
                <?php

            } else {
                if( 'wpclients_settings' == $_GET['page'] ) {
                ?>
                    <div class="clear"></div>

                    <div class="icon32" id="icon-options-general"></div>
                    <h2><?php printf( __( '%s Settings', WPC_CLIENT_TEXT_DOMAIN ), $this->plugin['title'] ) ?></h2>

                    <p><?php printf( __( 'From here you can manage a variety of options for the %s plugin.', WPC_CLIENT_TEXT_DOMAIN ), $this->plugin['title'] ) ?></p>

                <?php
                }
                ?>
                 <div id="container23">
                    <ul class="menu">
                         <?php
                         switch( $_GET['page'] ) {
                             case 'wpclients':
                                echo $this->gen_tabs_menu( 'clients' );
                                $template_text = 'clients_page';
                                break;
                             case 'wpclients_managers':
                                echo $this->gen_tabs_menu( 'managers' );
                                $template_text = 'managers';
                                break;
                             case 'wpclients_files':
                                echo $this->gen_tabs_menu( 'file_sharing' );
                                $template_text = 'files';
                                break;
                             case 'wpclients_templates':
                                echo $this->gen_tabs_menu( 'templates' );
                                $template_text = 'templates';
                                break;
                             case 'wpclients_settings':
                                echo $this->gen_tabs_menu( 'settings' );
                                $template_text = 'settings';
                                break;
                         }
                         ?>
                    </ul>
                    <span class="clear"></span>
                    <div class="content23 news">

                        <hr>
                        <span><?php _e( $this->advertising_message, WPC_CLIENT_TEXT_DOMAIN ) ?></span>
                        <hr>
                        <?php
                        if( 'addons' != $_GET['tab'] ) {
                            ?>
                                <div class="screenshots">
                                    <span class="screenshots_text"><?php _e( 'Screenshots:', WPC_CLIENT_TEXT_DOMAIN ) ?></span>
                                    <div class="gallery">
                                    <?php
                                        $tab = ( !isset( $_GET['tab'] ) ) ? 'general' : $_GET['tab'];
                                        foreach ( $this->screenshots[$_GET['page']][$tab] as $filename ) { ?>
                                            <a class="fancybox" rel="<?php echo $_GET['page'] . '_' . $tab ?>" href="<?php echo $this->screenshots_path . $filename ?>"><img class="image" src="<?php echo $this->screenshots_path . $filename ?>" alt=""></a> <?php
                                        }
                                    ?>
                                    </div>
                                </div>
                            <?php
                        } else {
                          ?>
                            <table cellspacing="0" class="widefat fixed">
                                <thead>
                                    <tr>
                                        <th class="manage-column column-name" scope="col" width="20%">Addon Name</th>
                                        <th class="manage-column column-name" scope="col">Description</th>
                                    </tr>
                                </thead>

                                <tfoot>
                                    <tr>
                                        <th class="manage-column column-name" scope="col" width="20%">Addon Name</th>
                                        <th class="manage-column column-name" scope="col">Description</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr valign="middle" class="alternate" id="plugin-feedback_wizard">
                                        <td class="column-name">
                                            <strong>Feedback Wizard</strong>
                                            <div class="actions">
                                                <a class="fancybox" rel="addon1" href="<?php echo $this->screenshots_path ?>addon-fw-01.jpg">Screenshots</a>
                                            </div>
                                        </td>
                                        <td class="column-c" valign="bottom" align="justify">
                                            <div class="wpc_addon_description">
                                            The Project Feedback Wizard is essentially a unique, professional, secure &amp; efficient method whereby the administrator of the site can bundle together a specific set of images, documents, files or links - and effectively present to a client a simple and easy to follow process that allows them to provide formalized and focused feedback.                                        </div>
                                        </td>
                                    </tr>
                                    <tr valign="middle" class="alternate" id="plugin-invoicing">
                                        <td class="column-name">
                                            <strong>Estimates/Invoices</strong>
                                            <div class="actions">
                                                <a class="fancybox" rel="addon2" href="<?php echo $this->screenshots_path ?>addon-ei-01.jpg">Screenshots</a>
                                            </div>
                                        </td>
                                        <td class="column-c" valign="bottom" align="justify">
                                            <div class="wpc_addon_description">
                                            Easily create estimates and invoices that your clients can pay online using the provided payment gateways. You can display invoices on your website, send in PDF format via email, or print out and send in traditional snail mail.                                        </div>
                                        </td>
                                    </tr>
                                    <tr valign="middle" class="alternate" id="plugin-paid_registration">
                                        <td class="column-name">
                                            <strong>Paid Registration</strong>
                                            <div class="actions">
                                                <a class="fancybox" rel="addon3" href="<?php echo $this->screenshots_path ?>addon-pr-01.jpg">Screenshots</a>
                                            </div>
                                        </td>
                                        <td class="column-c" valign="bottom" align="justify">
                                            <div class="wpc_addon_description">
                                            Configure the self registration system to only give clients access after they have paid using one of the provided payment gateways.                                        </div>
                                        </td>
                                    </tr>
                                    <tr valign="middle" class="alternate" id="plugin-time_limited_clients">
                                        <td class="column-name">
                                            <strong>Time Limited Clients</strong>
                                             <div class="actions">
                                                <a class="fancybox" rel="addon4" href="<?php echo $this->screenshots_path ?>addon-tl-01.png">Screenshots</a>
                                                <a class="fancybox" rel="addon4" href="<?php echo $this->screenshots_path ?>addon-tl-02.png"></a>
                                            </div>
                                        </td>
                                        <td class="column-c" valign="bottom" align="justify">
                                            <div class="wpc_addon_description">
                                            Easily set an expiration date for each individual client after which that clients login will no longer allow access. Their credentials are still in place, but they receive a customizable error notification explaining that their login has expired.                                        </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                          <?php
                        } ?>

                    </div>
                 </div>
                 <?php
            }

        }
        ?>

</div>
