<style type='text/css'>

    .navigation .alignleft, .navigation .alignright {
        display:none;
    }

</style>

<div class="wpc_client_client_pages">

    <?php
    if ( !empty( $data['message'] ) ) {
    ?>
        <span id="message" class="updated fade"><?php echo $message ?></span><br />
    <?php
    }

    if ( !empty( $data['add_staff_url'] ) ) {
    ?>
        <strong><a href="<?php echo $data['add_staff_url'] ?>"><?php echo  $data['add_staff_text'] ?></a></strong><br />
    <?php
    }

    if ( !empty( $data['staff_directory_url'] ) ) {
    ?>
       <strong><a href="<?php $data['staff_directory_url'] ?>"><?php echo $data['staff_directory_text'] ?></a></strong>
       <br /><br /><br />
    <?php
    }

    if ( !empty( $data['pages'] ) ) {
        foreach( $data['pages'] as $page ) {
        ?>
            <a href="<?php echo $page['url'] ?>"><?php echo $page['title'] ?></a>
            <?php
            if( !empty( $page['edit_link'] ) ) {
            ?>
                <a href="<?php echo $page['edit_link'] ?>" >Edit</a>
            <?php
            }
            ?>
            <br />
        <?php
        }
    }
    ?>
</div>