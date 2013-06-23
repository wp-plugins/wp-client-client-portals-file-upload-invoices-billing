<?php

global $wpc_client;

$data['logout_url']  = $wpc_client->get_logout_url();
$data['labels']['logout'] = __( 'LOGOUT', WPC_CLIENT_TEXT_DOMAIN );


ob_start();
?>
    <a href="<?php echo ( !empty( $data['logout_url'] ) ) ? $data['logout_url'] : '' ?>">
    <?php echo ( !empty( $data['labels']['logout'] ) ) ? $data['labels']['logout'] : '' ?></a>
<?php
    $out2 = ob_get_contents();
ob_end_clean();

return $out2;
?>