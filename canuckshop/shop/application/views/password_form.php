<?php echo validation_errors(); ?>
<?php echo form_open('accounts/forgot_password'); ?>
<h5>Email Address</h5>
<input type="text" name="email" value="<?php echo set_value('email'); ?>" size="50" maxlength="50" />
<div>
<input type="submit" value="Retrieve Password" />
</div>

