<?php echo form_open('accounts/register/'); ?>

<table class="zebraTable">
<tr class="rowEven"><th colspan="4">Customer Information</th><tr>
<tr class="rowOdd"><td>Contact</td><td><input type="text" name="contact" value="<?php echo set_value('contact'); ?>" size="30" maxlength="50" />
<img src="<?php echo WEB_IMAGES_PATH; ?>/icons/icon_star.gif" /><?php echo form_error('contact'); ?></td>
<td>Telephone (###-###-####)</td><td><input id="telephone" type="text" name="telephone" value="<?php echo set_value('telephone'); ?>" size="30" maxlength="50" />
<img src="<?php echo WEB_IMAGES_PATH; ?>/icons/icon_star.gif" /><?php echo form_error('telephone'); ?></td></tr>

<tr class="rowEven"><td>Other Phone (###-###-####)</td><td><input id="cellphone" type="text" name="cellphone" value="<?php echo set_value('cellphone'); ?>" size="30" maxlength="50" />
<?php echo form_error('cellphone'); ?></td>
<td>Fax (###-###-####)</td><td><input id="fax" type="text" name="fax" value="<?php echo set_value('fax'); ?>" size="30" maxlength="50" />
<?php echo form_error('fax'); ?></td></tr>

<tr class="rowOdd"><td>Email</td><td><input type="text" name="email" value="<?php echo set_value('email'); ?>" size="30" maxlength="50" />
<img src="<?php echo WEB_IMAGES_PATH; ?>/icons/icon_star.gif" /><?php echo form_error('email'); ?></td>
<td>Confirm Email</td><td><input type="text" name="confirm_email" value="<?php echo set_value('confirm_email'); ?>" size="30" maxlength="50" />
<img src="<?php echo WEB_IMAGES_PATH; ?>/icons/icon_star.gif" /><?php echo form_error('confirm_email'); ?></td></tr>

<tr class="rowEven"><td>Password</td><td><input type="password" name="password" value="<?php echo set_value('password'); ?>" size="30" maxlength="15" />
<img src="<?php echo WEB_IMAGES_PATH; ?>/icons/icon_star.gif" /><?php echo form_error('password'); ?></td>
<td>Confirm Password</td><td><input type="password" name="confirm_password" value="<?php echo set_value('confirm_password'); ?>" size="30" maxlength="15" />
<img src="<?php echo WEB_IMAGES_PATH; ?>/icons/icon_star.gif" /><?php echo form_error('confirm_password'); ?></td></tr>

<tr class="rowOdd"><td>School</td><td colspan="3"><input type="text" name="name" value="<?php echo set_value('name'); ?>" size="80" maxlength="100" />
<?php echo form_error('name'); ?></td></tr>
<tr class="rowEven"><td>Team</td><td colspan="3"><input type="text" name="team" value="<?php echo set_value('team'); ?>" size="80" maxlength="100" />
<?php echo form_error('team'); ?></td></tr>

<tr class="rowOdd"><td>Address (# street unit #)</td><td colspan="3"><input type="text" name="street" value="<?php echo set_value('street'); ?>" size="80" maxlength="100" />
<img src="<?php echo WEB_IMAGES_PATH; ?>/icons/icon_star.gif" /><?php echo form_error('street'); ?></td></tr>

<tr class="rowEven"><td>City</td><td colspan="3"><input type="text" name="city" value="<?php echo set_value('city'); ?>" size="30" maxlength="50" />
<img src="<?php echo WEB_IMAGES_PATH; ?>/icons/icon_star.gif" />&nbsp;&nbsp;&nbsp;&nbsp; Province&nbsp;&nbsp;<input type="text" name="province" value="<?php echo set_value('province'); ?>" size="15" maxlength="40" />
<img src="<?php echo WEB_IMAGES_PATH; ?>/icons/icon_star.gif" />&nbsp;&nbsp;&nbsp;&nbsp; Zipcode&nbsp;&nbsp;<input type="text" name="zipcode" value="<?php echo set_value('zipcode'); ?>" size="10" maxlength="40" />
<img src="<?php echo WEB_IMAGES_PATH; ?>/icons/icon_star.gif" /><?php echo form_error('city'); ?><?php echo form_error('province'); ?><?php echo form_error('zipcode'); ?></td></tr>

</table>
<div>
<img src="<?php echo WEB_IMAGES_PATH; ?>/icons/icon_save.gif" /><input type="submit" value="Create Account" />
</div>

