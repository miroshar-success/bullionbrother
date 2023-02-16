<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="woolentor_extension_tabs" class="woolentor-admin-main-tab-pane">
    <div class="woolentor-admin-main-tab-pane-inner">
        <?php Woolentor_Extension_Manager::instance()->render_html(); ?>
    </div>
</div>