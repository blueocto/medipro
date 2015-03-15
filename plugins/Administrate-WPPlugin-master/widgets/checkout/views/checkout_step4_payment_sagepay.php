<!--  End the previous form -->
</form>

<!--  Start the SagePay form -->
<form action="<?= $this->_get_api_url(); ?>" method="post" id="<?= $this->widget->add_namespace(array('payment', 'processor', 'sagepay'), '-'); ?>">
	<input type="hidden" name="VPSProtocol" value="2.23">
	<input type="hidden" name="TxType" value="PAYMENT">
	<input type="hidden" name="Vendor" value="<?= $vendor; ?>">
	<input type="hidden" name="Crypt" value="<?= $encryptedStr; ?>">
	<input type="submit" value="<?php _e('Place Order', 'administrate'); ?>" class="<?= $this->plugin->add_namespace('btn', '-'); ?>">