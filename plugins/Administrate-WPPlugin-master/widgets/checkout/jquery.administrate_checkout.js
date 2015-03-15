(function($) {
  
	// The jQuery.aj namespace will automatically be created if it doesn't exist
	$.widget('administrate.administrate_checkout', {
	
		// These options will be used as defaults
		options: { 
			tabsNav:				'.ui-tabs-nav',
			activeTabClass:			'ui-state-active',
			copyToAttendee:			'#administrate-checkout-buyer-is-attendee',
			steps:					'.administrate-checkout-step',
			stepIdPrefix:			'#administrate-checkout-step-',
			form:					'form',
			nextCheckoutStep:		'#administrate-checkout-next-step',
			paymentAccordion:		'.administrate-checkout-accordion',
			hiddenClass:			'administrate-hidden',
			buyerFirstName:			'#administrate-checkout-buyer-first-name',
			buyerLastName:			'#administrate-checkout-buyer-last-name',
			buyerEmail:				'#administrate-checkout-buyer-email',
			attendeeFirstName:		'#administrate-checkout-attendee-first-name-1',
			attendeeLastName:		'#administrate-checkout-attendee-last-name-1',
			attendeeEmail:			'#administrate-checkout-attendee-email-1',
			loaderClass:			'administrate-spinner',
			jsonFlag:				'<!-- ADMINISTRATE_JSON_START -->'
		},
		
		//  Initialize widget
		_create: function() {

			//  Add copy to attendee onclick
			this.element.find(this.options.copyToAttendee).live('change', $.proxy(this._copy_buyer_to_attendee, this));
		
			// Don't initialize this for IE8 and below
			// The accordion plugin does *NOT* work on IE < 9 in jQuery < 1.8,
			// the page will remain functional however, it just won't accordion up,
			// and will show both CC and Invoice payment options if available.
			if (!$.browser.msie || ($.browser.version >= 9)) {
		
				//  Save references to objects
				this.tabsNav			=	this.element.find(this.options.tabsNav);
				this.individualPurchase	=	this.element.find(this.options.individualPurchase);
				this.steps				=	this.element.find(this.options.steps);
				this.form				=	this.element.find(this.options.form);
				this.nextCheckoutStep	=	this.element.find(this.options.nextCheckoutStep);
				
				//  Add ajaxify flag to form
				this.form.append('<input type="hidden" name="ajax" value="1">');
				
				//  Ajaxify checkout form
				this.form.submit($.proxy(this._submit_step, this));
				
				//  Create the loader DIV
				this.spinner = $('<div class="'+this.options.loaderClass+'"></div>');
				this.element.append(this.spinner);
				this.spinner.hide();
				
				//  Before we initialize the tabs, make sure the non-JS hrefs are not set (if the user arrived here directly)
				var activeTab = 0;
				$.each(this.tabsNav.children(), $.proxy(function(i, tab) {
					var num = i + 1;
					var tab = $(tab);
					var lnk = tab.find("a");
					lnk.attr('href', this.options.stepIdPrefix+num);
					if (tab.hasClass(this.options.activeTabClass)) {
						activeTab = i;	
					}
				}, this));
				
				//  Figure out what tabs to disable initially
				var disabledTabs = [];
				for (var i = activeTab+1, numTabs = this.tabsNav.children().length; i < numTabs; ++i) {
					disabledTabs.push(i);
				}
				
				//  Initialize checkout tabs
				this.element.tabs({
					
					active:		activeTab,
					disabled:	disabledTabs,
					activate:	$.proxy(this._set_next_step, this),
					
					// for jQuery UI <= 1.8
					select:		$.proxy(this._set_next_step, this)	
				
				});
				
				//  Initialize the payment accordion
				this._init_payment_accordion();
			
			}

			// On initialisation, always ensure our 'next step' is 2 - attendee details.
			this.nextCheckoutStep.val(2);

		},
		
		//  Copy the buyer to the first attendee
		_copy_buyer_to_attendee: function() {
			if (this.element.find(this.options.copyToAttendee).is(":checked")) {
				$(this.options.attendeeFirstName).val($(this.options.buyerFirstName).val());
				$(this.options.attendeeLastName).val($(this.options.buyerLastName).val());
				$(this.options.attendeeEmail).val($(this.options.buyerEmail).val());
			} else {
				$(this.options.attendeeFirstName).val('');
				$(this.options.attendeeLastName).val('');
				$(this.options.attendeeEmail).val('');
			}
			return true;
		},
		
		//  Initialize payment accordion
		_init_payment_accordion: function() {
			
			//  Initialize the payment accordion if it exists
			var paymentOptions = this.element.find(this.options.paymentAccordion);
			if (paymentOptions.length > 0) {
				paymentOptions.accordion({
					
					header:			'h3',
					icons:			false,
					active:			false,
					collapsible:	true,
					heightStyle:	'content',
					activate:		$.proxy(this._select_payment_option, this),
					
					// for jQuery UI <= 1.8
					autoHeight:		false,	
					fillSpace:		false,
					change:			$.proxy(this._select_payment_option, this)
					
				});
			}
			
		},
		
		//  Select payment option
		_select_payment_option: function(e, ui) {
			ui.newHeader.find('input').attr('checked', true);
		},
		
		//  Update the step
		_update_step: function(num, content) {
		
			//  Set the tab index
			var index = num-1;
			
			//  Update the phase HTML
			var step = $(this.steps[index]);
			step.html(content);
			
			//  Initialize payment accordion
			this._init_payment_accordion();
			
			//  Select checkout tab -- we have to do it this way because the 'enable' method was causing issues on some webkit browsers
			var disabledTabs = [];
			for (var i = index+1, numTabs = this.steps.length; i < numTabs; ++i) {
				disabledTabs.push(i);
			}
			this.element.tabs('option', 'disabled', disabledTabs);
			this.element.tabs('option', 'active', index);
			if (this.element.tabs('option', 'selected') !== null) {
				this.element.tabs('option', 'selected', index);
			}
			
			//  Scroll to top of tabs
			var currentScrollTop = $(document.body).scrollTop();
			var tabsTop = this.element.offset().top;
			if (currentScrollTop > tabsTop) {
				$(document.body).scrollTop(tabsTop);
			}

		},
		
		//  Set the next step
		_set_next_step: function(e, ui) {
			var nextStep = 1;
			if (ui) {
				var activeTab = this.element.tabs('option', 'active');
				nextStep = activeTab + 2;
			}
			this.nextCheckoutStep.val(nextStep);
		},
		
		//  Submit the current step
		_submit_step: function() {
			
			//  Load the spinner
			this.spinner.fadeIn();
			
			var fields = this.form.serialize();
			var nextStep = parseInt(this.nextCheckoutStep.val());
			if (nextStep < 5) {
				$.post(this.form.attr('action'), this.form.serialize(), $.proxy(function(data) {
					var data = data.substr(data.indexOf(this.options.jsonFlag)+this.options.jsonFlag.length);
					//console.log(data);
					data = $.parseJSON(data);
					this._update_step(data.step, data.content);
					this.spinner.fadeOut();
				}, this));
				return false;
			}
			
		},
		
		// Use the destroy method to reverse everything your plugin has applied
		destroy: function() {
			
			//  Remove spinner
			this.spinner.remove();
			
			//  Call parent destroy
			$.Widget.prototype.destroy.call(this);
		
		}

	});
	
})(jQuery);
