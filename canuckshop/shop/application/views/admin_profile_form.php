<?php echo validation_errors(); ?>
<?php echo form_open('accounts/admin_profile'); ?>

<h5>New User Name</h5>
<input type="text" name="username" value="<?php echo set_value('username', $username); ?>" size="50" maxlength="50" />(3-50 length)

<h5>New Password</h5>
<input type="password" name="password" value="<?php echo set_value('password', ''); ?>" size="50" maxlength="15" />(4-15 length)

<h5>Confirm Password</h5>
<input type="password" name="confirm_password" value="<?php echo set_value('confirm_password', ''); ?>" size="50" maxlength="15" />

<div>
<input type="submit" value="Update Profile" />
</div>

