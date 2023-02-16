<?php
$ml = new AWCFE_Ml();
$class = '';
if ($ml->is_active() && $ml->is_default_lan() === false) {
    $class = 'awcfe_ml_not_default';
}
?>
<div class="<?php echo $class; ?>" id="<?php echo AWCFE_TOKEN; ?>_ui_root">
  <div class="root_loader"><p>Loading User Interface...</p></div>
</div>
