<?php

global $wpc_client;

$data['login_url']  = '';

$data['labels']['username'] = __( 'Username', WPC_CLIENT_TEXT_DOMAIN );
$data['labels']['password'] = __( 'Password', WPC_CLIENT_TEXT_DOMAIN );
$data['labels']['remember'] = __( 'Remember Me', WPC_CLIENT_TEXT_DOMAIN );
$data['somefields']         = '<input type="hidden" name="wpc_login" value="login_form">';

$data['error_msg'] = '';
if ( isset( $GLOBALS['wpclient_login_msg'] ) && '' != $GLOBALS['wpclient_login_msg'] )
    $data['error_msg'] = $GLOBALS['wpclient_login_msg'];

    ob_start();
        ?>
            <form method="post" action="<?php echo ( !empty( $data['login_url'] ) ) ? $data['login_url'] : '' ?>" id="loginform" name="loginform">

                <?php echo ( !empty( $data['somefields'] ) ) ? $data['somefields'] : '' ?>

                <?php
                if ( !empty( $data['error_msg'] ) ) {
                ?>
                    <div id="login_error">$data['error_msg']</div>
                <?php
                }
                ?>
                <p>
                    <label for="user_login"><?php echo ( !empty( $data['labels']['username'] ) ) ? $data['labels']['username'] : '' ?><br>
                    <input type="text" tabindex="10" size="20" value="" class="input" id="user_login" name="log"></label>
                </p>
                <p>
                    <label for="user_pass"><?php echo ( !empty( $data['labels']['password'] ) ) ? $data['labels']['password'] : '' ?><br>
                    <input type="password" tabindex="20" size="20" value="" class="input" id="user_pass" name="pwd"></label>
                </p>
                <p class="forgetmenot"><label for="rememberme"><input type="checkbox" tabindex="90" value="forever" id="rememberme" name="rememberme"><?php echo ( !empty( $data['labels']['remember'] ) ) ? $data['labels']['remember'] : '' ?></label></p>
                <p class="submit">
                    <input type="submit" tabindex="100" value="Log In" class="button-primary" id="wp-submit" name="wp-submit">
                    <input type="hidden" value="" name="redirect_to">
                </p>
            </form>
        <?php
        $out2 = ob_get_contents();
    ob_end_clean();

return $out2;
?>