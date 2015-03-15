(function( $ ){
	
	//  SETTINGS
	var pluginId = 'AdministratePlugin';
	var paymentInvoiceId = 'payment_by_invoice';
	var paymentCCId = 'payment_by_cc';
	var invoiceFieldsSelector = '#invoice_instructions, #invoice_send_email';
	var ccFieldsSelector = '#payment_processor, #paypal_user, #paypal_password, #paypal_signature, #authorizenet_user, #authorizenet_key, #cc_send_email';
	var ccProviderId = '#payment_processor';
	var paypalFieldsSelector = '#paypal_user, #paypal_password, #paypal_signature, #paypal_mode';
	var sagepayFieldsSelector = '#sagepay_vendor_username, #sagepay_encryption_password, #sagepay_email_message, #sagepay_mode';
	var authorizenetFieldsSelector = '#authorizenet_user, #authorizenet_key';
	var serviceModeSelector = '#mode';
	var apiLiveFieldsSelector = '#domain, #user, #password';
	var logTableSelector = '#administrate-order-logs';
	var confirmNoSave = 'There are unsaved changes on this page.';
	
	//  Only proceed if the plugin element exists
	var plugin = $('#' + pluginId);
	if (plugin) {
	
		//  PROPERTIES
		var paymentInvoice = $('#'+paymentInvoiceId);
		var paymentCC = $('#'+paymentCCId);
		var invoiceFields = $(invoiceFieldsSelector);
		var ccFields = $(ccFieldsSelector);
		var ccProvider = $(ccProviderId);
		var ccProviders = {
			PayPal:			$(paypalFieldsSelector),
			SagePay:		$(sagepayFieldsSelector),
			AuthorizeNet:	$(authorizenetFieldsSelector)
		};
		var serviceMode = $(serviceModeSelector);
		var apiLiveFields = $(apiLiveFieldsSelector);
		var logTable = $(logTableSelector);
	
		//  Attach onchange event to all form inputs
		var fieldsChanged = false;
		var saveClicked = false;
		plugin.find('input,select,textarea').change(setFormState);
		plugin.find('input[type=submit]').click(setSaveClicked);
		$(window).on('beforeunload', checkFormState);
		
		//  Initialize log table if it exists
		if (logTable) {
			logTable.administrate_log_table();	
		}
		
		//  Handle payment invoice checkbox
		paymentInvoice.change(handlePaymentInvoice);
		
		//  Handle payment CC checkbox
		paymentCC.change(handlePaymentCC);
		
		//  Handle payment provider select
		ccProvider.change(handleCCProvider);
		
		//  Handle service mode change
		serviceMode.change(handleServiceMode);
		
		//  Set the initial states
		handlePaymentInvoice();
		handlePaymentCC();
		handleServiceMode();
		
		//  Handle invoice checkbox
		function handlePaymentInvoice() {
			if (paymentInvoice.attr('checked')) {
				showFields(invoiceFields);
			} else {
				hideFields(invoiceFields);
			}	
		}
		
		//  Handle credit card check box
		function handlePaymentCC() {
			if (paymentCC.attr('checked')) {
				showFields(ccFields);
				handleCCProvider();
			} else {
				hideFields(ccFields);
			}	
		}
		
		//  Handle the payment provider
		function handleCCProvider() {
			$.each(ccProviders, function(provider, fields) {
				if (provider == ccProvider.val()) {
					showFields(fields);
				} else {
					hideFields(fields);	
				}
			});
		}
		
		//  Handle the service mode
		function handleServiceMode() {
			if (serviceMode.val() == 'demo') {
				hideFields(apiLiveFields);
			} else {
				showFields(apiLiveFields);	
			}
		}
		
		//  Show fields in collection
		function showFields(fields) {
			$.each(fields, function(i, field) {
				$(field).parent().parent().show('fast');
			});
		}
		
		//  Hide fields in collection
		function hideFields(fields) {
			$.each(fields, function(i, field) {
				$(field).parent().parent().hide('fast');
			});
		}
		
		//  Set form state
		function setFormState() {
			fieldsChanged = true;
		}
		
		//  Set save clicked
		function setSaveClicked() {
			saveClicked = true;	
		}
		
		//  Check form state
		function checkFormState() {
			if (fieldsChanged && !saveClicked) {
				return confirmNoSave;
			}
		}
	
	}

})(jQuery);
