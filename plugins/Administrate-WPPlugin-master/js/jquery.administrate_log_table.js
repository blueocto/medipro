(function($) {
  
	// The jQuery.aj namespace will automatically be created if it doesn't exist
	$.widget('administrate.administrate_log_table', {
	
		// These options will be used as defaults
		options: { 
			table:				'table',
			filters:			'form',
			statusOptions:		'#administrate-order-status',
			daysOptions:		'#administrate-order-days',
			eventOptions:		'#administrate-order-event',
			paymentOptions:		'#administrate-order-payment',
			noRows:				'.administrate-no-rows',
			btn:				'.administrate-btn',
			oddClass:			'administrate-alt'
		},
		
		//  Initialize widget
		_create: function() {
			
			//  Save references to objects
			this.table			=	this.element.find(this.options.table);
			this.filters		=	this.element.find(this.options.filters);
			this.statusOptions	=	$(this.options.statusOptions);
			this.daysOptions	=	$(this.options.daysOptions);
			this.eventOptions	=	$(this.options.eventOptions);
			this.paymentOptions =	$(this.options.paymentOptions);
			this.noRows 		=	this.element.find(this.options.noRows);
			
			//  Save reference to filters form and orders
			this.orders = this.table.find('tbody').children();
			
			//  Do the initial filter
			this._filter_table();
		
		},
		
		//  Restripe orders table
		_restripe_table: function() {
			var odd = true;
			for (var i = 0, numEvents = this.orders.length; i < numEvents; ++i) {
				var order = $(this.orders[i]);
				if (order.is(':visible')) {
					if (odd) {
						order.addClass(this.options.oddClass);
					} else {
						order.removeClass(this.options.oddClass);
					}
					odd = !odd;	
				}
			}
		},
		
		//  Filter orders table
		_filter_table: function() {
		
			//  Set the filter status
			var filterStatus = this.statusOptions.val();
			
			//  Set the filter days
			var filterDays = parseInt(this.daysOptions.val());
			
			//  Set the filter event
			var filterEvent = this.eventOptions.val();
			
			//  Set the filter payment
			var filterPayment = this.paymentOptions.val();
			
			//  Loop through orders
			var numShown = 0;
			for (var i = 0, numEvents = this.orders.length; i < numEvents; ++i) {
			
				//  Save the order and attributes
				var order = $(this.orders[i]);
				var orderStatus = order.attr('data-status');
				var orderDays = parseInt(order.attr('data-days-ago'));
				var orderEvent = order.attr('data-event');
				var orderPayment = order.attr('data-payment');
				
				//  Show the order only if all criteria are met; otherwise hide
				if (
					((filterStatus.length == 0) || (orderStatus == filterStatus)) &&
					//(orderDays <= filterDays) &&
					((filterEvent.length == 0) || (orderEvent == filterEvent)) && 
					((filterPayment.length == 0) || (orderPayment == filterPayment))
				) {
					order.show();
					++numShown;	
				} else {
					order.hide();	
				}
				
			}
			
			//  Restripe the modified table
			this._restripe_table();
			
			//  If no results were shown, display message
			if (numShown == 0) {
				this.noRows.show();	
			} else {
				this.noRows.hide();
			}
			
		},
		
		// Use the destroy method to reverse everything your plugin has applied
		destroy: function() {

			//  Call parent destroy
			$.Widget.prototype.destroy.call(this);
		
		}

	});
	
})(jQuery);