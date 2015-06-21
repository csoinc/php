<?php echo sprintf('<a href="%s/uniformclients/select/%d/%d" title="Back"><img src="%s/images/buttons/button_back.gif" /></a>', WEB_CONTEXT, $clients_start, 0, WEB_CONTEXT); ?>&nbsp;&nbsp;<br>
<?php echo validation_errors(); ?>
<?php echo form_open('clientorders/confirm_client/'.$clients_start.'/'.$clientid); ?>
<b>Client #:&nbsp;</b><?php echo $client->clientid; ?> 
<table class="zebraTable">
<tr class="rowEven"><th colspan="4">Client Information</th><tr>

<tr class="rowOdd"><td>Name</td><td><input type="text" name="contact" value="<?php echo $client->contact; ?>" size="30" maxlength="50" ></td>
<td>Email</td><td><input type="text" name="email" value="<?php echo $client->email; ?>" size="30" maxlength="50"></td></tr>

<tr class="rowEven"><td>Telephone</td><td><input id="telephone" type="text" name="telephone" value="<?php echo $client->telephone; ?>" size="30" maxlength="50"></td>
<td>Other Phone</td><td><input id="cellphone" type="text" name="cellphone" value="<?php echo $client->cellphone; ?>" size="30" maxlength="50"></td></tr>

<tr class="rowOdd"><td>Date In</td><td><input type="text" name="orderdate" id="orderdate" value="<?php echo date("Y-m-d"); ?>" size="30" maxlength="30"></td>
<td>Date Needed</td><td><input type="text" name="requireddate" id="requireddate" value="<?php echo date("Y-m-d"); ?>" size="30" maxlength="30"></td></tr>

<tr class="rowEven"><td>School/Team</td><td colspan="3"><input type="text" name="name" value="<?php echo $client->name.' '.$client->team; ?>" size="120" maxlength="200"></td>

<tr class="rowOdd"><td>Payment</td><td><input type="text" name="payment" value="" size="30" maxlength="50"></td>
<td>Expire Date</td><td><input type="text" name="expdate" value="" size="20" maxlength="50"></td></tr>

<tr class="rowEven"><td>Address</td><td colspan="3"><input type="text" name="address" value="<?php echo $client->street.' '.$client->city.' '.$client->province; ?>" size="80" maxlength="100">
&nbsp;Zip Code&nbsp;<input type="text" name="zipcode" value="<?php echo $client->zipcode; ?>" size="10" maxlength="10"></td></tr>

<tr class="rowOdd"><td>ShippingAddr</td><td colspan="3"><input type="text" name="shippingaddr" value="<?php echo $client->street.' '.$client->city.' '.$client->province; ?>" size="80" maxlength="100">
&nbsp;Zip Code&nbsp;<input type="text" name="shippingzip" value="<?php echo $client->zipcode; ?>" size="10" maxlength="10"></td></tr>

<tr class="rowEven"><td>Comment</td><td colspan="3"><input type="text" name="comments" value="" size="120" maxlength="200">

</td></tr>

</table>
<input type="submit" value="Confirm & Continue" title="Confirm & Continue" /><br>
<?php echo form_close(); ?>
