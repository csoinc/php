<?php echo validation_errors(); ?>
<?php echo form_open('uniformclients/search/0'); ?>
<input type="text" name="clients_what" value="<?php echo set_value('clients_what', $clients_what); ?>" size="40" maxlength="50" />
<input type="submit" value="Search Clients" title="Search Clients" />
<?php echo form_close(); ?>

<?php echo form_open('uniformclients/update/'.$clients_start.'/'.$clients_edit); ?>
<?php if (isset($clients_table)) echo $clients_table; ?>
<?php echo form_close(); ?>

<?php echo $this->pagination->create_links(); ?>

<?php echo form_open('uniformclients/insert/'.$clients_start.'/'.$clients_edit); ?>

<table class="zebraTable">
<tr class="rowEven"><th colspan="4">New Client Information</th><tr>

<tr class="rowOdd"><td>Contact:</td><td><input type="text" name="contact" value="<?php echo set_value('contact', ''); ?>" size="30" maxlength="50" ></td>
<td>Email:</td><td><input type="text" name="email" value="<?php echo set_value('email', ''); ?>" size="30" maxlength="100"></td></tr>

<tr class="rowEven"><td>Telephone:</td><td colspan="3"><input id="telephone" type="text" name="telephone" value="<?php echo set_value('telephone', ''); ?>" size="30" maxlength="45">(###-###-####)&nbsp;&nbsp;&nbsp;&nbsp;
Cell Phone:&nbsp;&nbsp;<input id="cellphone" type="text" name="cellphone" value="<?php echo set_value('cellphone', ''); ?>" size="30" maxlength="45">&nbsp;&nbsp;
Fax:&nbsp;&nbsp;<input id="fax" type="text" name="fax" value="<?php echo set_value('fax', ''); ?>" size="30" maxlength="45"></td></tr>

<tr class="rowOdd"><td>School:</td><td colspan="3"><input type="text" name="name" value="<?php echo set_value('name', ''); ?>" size="60" maxlength="100"></td></tr>
<tr class="rowEven"><td>Team:</td><td colspan="3"><input type="text" name="team" value="<?php echo set_value('team', ''); ?>" size="60" maxlength="100"></td></tr>

<tr class="rowOdd"><td>Street:</td><td colspan="3"><input type="text" name="street" value="<?php echo set_value('street', ''); ?>" size="60" maxlength="100" ></td></tr>
<tr class="rowEven"><td>City:</td><td colspan="3"><input type="text" name="city" value="<?php echo set_value('city',''); ?>" size="30" maxlength="45">&nbsp;&nbsp;
Province:&nbsp;&nbsp;<input type="text" name="province" value="<?php echo set_value('province', ''); ?>" size="30" maxlength="45">&nbsp;&nbsp;
Zip Code:&nbsp;&nbsp;<input type="text" name="zipcode" value="<?php echo set_value('zipcode',''); ?>" size="30" maxlength="45"></td></tr>

</table>

<img src="<?php echo WEB_CONTEXT; ?>/images/icons/icon_save.gif" />&nbsp;<input type="submit" value="Add New Client" title="Add New Client" />
<?php echo form_close(); ?>
