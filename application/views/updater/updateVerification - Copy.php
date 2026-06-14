<?php
$wl = getWhiteLabel();
$system_logo = '';
if($wl){
    if($wl->site_name){
        $site_name = $wl->site_name;
    }
    if($wl->footer){
        $footer = $wl->footer;
    }
    if($wl->system_logo){
        $system_logo = base_url()."images/".$wl->system_logo;
    }
}
//get company information
$getCompanyInfo = getCompanyInfo();
?>
<html>
<head>
    <title>Update Verification || <?php echo escape_output($site_name)?></title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">

    <!-- jQuery 3 -->
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Bootstrap 3.3.7 -->
    <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</head>
<body>
<input type="hidden" value="<?=escape_output($status)?>" id="status_value">
<input type="hidden" value="<?=escape_output(base_url())?>" id="base_url_custom_update">
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h3 class="text-center response_text" style="color:<?php echo escape_output($color)?>;"><?php echo escape_output($txt_return) ?></h3>
        <p class="redirect_text"><b>Redirecting... <span class="counter">5</span></b></p>
    </div>
    <div class="col-md-4 col-md-offset-4 form_div">
        <h3 class="text-center">Purchase Verification</h3>
        <?php echo form_open(base_url() . 'Update/updateVerification', $arrayName = array('id' => 'update_verification')) ?>
        <div class="control-group letf_margin">
            <label class="control-label" for="username">Envato Username</label>
            <div class="controls">
                <input  id="username" type="text" name="username" class="input-large form-control txt_w_3" required="required" data-error="Username is required" value="<?=set_value('username')?>" placeholder="Username" />
            </div>
        </div>
        <div class="control-group letf_margin">
            <label class="control-label" for="purchase_code">Purchase Code</label>
            <div class="controls">
                <input id="purchase_code" type="text" name="purchase_code" class="input-large form-control txt_w_3" required="required" data-error="Purchase Code is required" value="<?=set_value('purchase_code')?>" placeholder="Purchase Code" />
            </div>
            <!-- modified -->
            <input id="owner" type="hidden" name="owner" class="input-large" value="doorsoftco"  />
            <input id="owner" type="hidden" name="base_url" class="input-large" value="<?php echo base_url()?>"  />
        </div>
        <br>
        <div class="bottom txt_w_2">
            <input type="submit" name="submit" class="btn btn-primary button_1"  value="Verify"/>
            <a class="btn btn-primary" href="<?php echo base_url() ?>Dashboard/dashboard">
                <?php echo lang('back'); ?>
            </a>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script src="<?php echo base_url(); ?>frequent_changing/js/update_verification.js"></script>
</body>
</html>
