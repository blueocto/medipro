(function($) {
  
	// The jQuery.aj namespace will automatically be created if it doesn't exist
	$.widget('administrate.administrate_event_price', {
	
		// These options will be used as defaults
		options: { 
			prices:			'.administrate-event-price-prices',
			currencies:		'.administrate-event-price-currencies',
			selectedClass:	'administrate-selected'
		},
		
		//  Initialize widget
		_create: function() {
		
			//  Save references to objects
			this.prices		=	$(this.options.prices);
			this.currencies	=	$(this.options.currencies);
			
			//  Attach onchange to currency selector
			this.element.change($.proxy(this._change_currency, this));
			
			//  Set the current currency
			this.currentCurrency = false;
			this._change_currency();
			
			//  If there is only 1 currency, hide the select box
			if (this.element.children().length < 2) {
				this.element.replaceWith('<span class="' + this.options.currencies.substr(1) + '">' + this.element.val() + '</span>');
				//this.element.hide();	
			}
		
		},
		
		//  Change the currency
		_change_currency: function() {
			
			//  Get the selected currency
			var newCurrency = this.element.val();
			
			//  Only proceed if a new currency was selected
			if (newCurrency != this.currentCurrency) {
			
				//  Loop through pricing lists
				$.each(this.prices, $.proxy(function(i, el) {
					
					//  Deselect previous currency and select new one
					var el = $(el);
					el.find('li.'+this.currentCurrency).removeClass(this.options.selectedClass);
					el.find('li.'+newCurrency).addClass(this.options.selectedClass);
					
				}, this));
				
				//  Loop through currency selector and update them
				$.each(this.currencies, function(i, el) {
					$(el).val(newCurrency);
				});
				
				//  Save the new currency
				this.currentCurrency = newCurrency;
			
			}
			
		},
		
		// Use the destroy method to reverse everything your plugin has applied
		destroy: function() {

			//  Call parent destroy
			$.Widget.prototype.destroy.call(this);
		
		}

	});
	
})(jQuery);
