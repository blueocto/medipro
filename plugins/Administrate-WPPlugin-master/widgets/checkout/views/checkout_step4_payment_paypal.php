<!--  End the previous form -->
</form>

<!--  Start the PayPal form -->
<?php
	if($this->plugin->get_option('paypal_mode', 'checkout') == 'Sandbox') {
		$btn_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	} else {
		$btn_url = 'https://www.paypal.com/cgi-bin/webscr';
	}
?>
<form action="<?=$btn_url;?>" method="post" id="<?= $this->widget->add_namespace(array('payment', 'processor', 'paypal'), '-'); ?>">
<?= stripslashes($WEBSITECODE); ?>
