(function($) {
  
	// The jQuery.aj namespace will automatically be created if it doesn't exist
	$.widget('administrate.administrate_event_table', {
	
		// These options will be used as defaults
		options: { 
			table:				'table',
			filters:			'form',
			categoryOptions:	'#administrate-event-table-category',
			monthOptions:		'#administrate-event-table-month',
			locationOptions:	'#administrate-event-table-location',
			pager:				'#administrate-event-table-pager',
			noRows:				'.administrate-no-rows',
			btn:				'.administrate-btn',
			oddClass:			'administrate-alt',
			pagerFirst:			'.administrate-event-table-pager-first',
			pagerPrevious:		'.administrate-event-table-pager-previous',
			pagerNext:			'.administrate-event-table-pager-next',
			pagerLast:			'.administrate-event-table-pager-last',
			pagerStatus:		'.administrate-event-table-pager-status',
			pagerDisabledClass:	'administrate-event-table-pager-disabled',
			filteredRowClass:	'administrate-event-table-filtered'
		},
		
		//  Initialize widget
		_create: function() {
		
			//  Save references to objects
			this.table				=	this.element.find(this.options.table);
			this.filters			=	this.element.find(this.options.filters);
			this.categoryOptions	=	$(this.options.categoryOptions);
			this.monthOptions		=	$(this.options.monthOptions);
			this.locationOptions	=	$(this.options.locationOptions);
			this.noRows 			=	this.element.find(this.options.noRows);
			this.pager				= 	$(this.options.pager);
			
			//  Save reference to filters form and events
			this.events = this.table.find("tbody").children();
			
			//  Only proceed if there are any events in the table
			if (this.events.length > 0) {
			
				//  Add a metaDate parser
				$.tablesorter.addParser({ 
					id: 'startDate', 
					is: function(s) { 
						return false; 
					}, 
					format: function(s, table, cell, cellIndex) { 
						return parseInt($(cell).attr('data-start-date'));
					},
					type: 'numeric' 
				});
				
				//  Initialize table sorter
				this.table.tablesorter({
					widgets: 		['zebra', 'filter'],
					widgetOptions:	{
						zebra: 				['', this.options.oddClass],
						filter_filteredRow:	this.options.filteredRowClass
					}
				});
				
				//  If the pager element exists, initialize it
				if ((this.pager.length > 0) && (this.table.find('tbody').find('tr').length > 0)) {
					this.pager.show();
					this.table.tablesorterPager({
						container:		this.pager,
						fixedHeight:	false,
						size:			parseInt(this.table.attr("data-group-size")),
						output:			'{startRow} - {endRow} / {filteredRows}',
						cssFirst:		this.options.pagerFirst,
						cssPrev:		this.options.pagerPrevious,
						cssNext:		this.options.pagerNext,
						cssLast:		this.options.pagerLast,
						cssPageDisplay:	this.options.pagerStatus,
						cssDisabled:	this.options.pagerDisabledClass
					});
				}
				
				//  Activate the category filter
				this.categoryOptions.change($.proxy(this._filter_table, this));
					
				//  Activate the month filter
				this.monthOptions.change($.proxy(this._filter_table, this));
				
				//  Activate the location filter
				this.locationOptions.change($.proxy(this._filter_table, this));
				
				//  Hide the "Go" button since this is all JS-ified
				this.filters.find(this.options.btn).hide();
				
				//  Do the initial filter
				this._filter_table();
			
			//  Otherwise just hide tne entire widget
			} else {
				this.element.hide();
				/*this.filters.hide();
				this.table.hide();
				this.pager.hide();*/
			}
		
		},
		
		//  Filter events table
		_filter_table: function() {
		
			//  Figure out the filter category
			var filterCategory = false;
			var filterSubcategory = false;
			var selectedValue = this.categoryOptions.val();
			if (selectedValue && (selectedValue.length > 0)) {
				var categories = selectedValue.split(":");
				if (parseInt(categories[1]) === 0) {
					filterCategory = categories[0];	
				} else {
					filterSubcategory = categories[1];	
				}
			}
			
			//  Set the filter months
			var filterMonths = parseInt(this.monthOptions.val());
			
			//  Set the filter location
			var filterLocation = this.locationOptions.val();
			
			//  Loop through events
			var numShown = 0;
			for (var i = 0, numEvents = this.events.length; i < numEvents; ++i) {
			
				//  Save the event and attributes
				var event = $(this.events[i]);
				var eventCategories = event.attr("data-categories").split(",");
				var eventSubcategories = event.attr("data-subcategories").split(",");
				var eventMonths = parseInt(event.attr("data-num-months"));
				var eventLocation = event.attr("data-location");
				
				//  Show the event only if all criteria are met; otherwise hide
				if (
					((!filterCategory && !filterSubcategory) || ($.inArray(filterCategory, eventCategories) > -1) || ($.inArray(filterSubcategory, eventSubcategories) > -1)) && 
					(eventMonths <= filterMonths) &&
					((filterLocation.length == 0) || (eventLocation == filterLocation))
				) {
					event.removeClass(this.options.filteredRowClass);
					event.show();
					++numShown;
				} else {
					event.addClass(this.options.filteredRowClass);
					event.hide();	
				}
				
			}
			
			//  If no results were shown, display message
			if (numShown == 0) {
				this.noRows.show();	
			} else {
				this.noRows.hide();
			}
			
			//  Force tablesorter to reset
			if (this.pager.length > 0) {
				this.table.trigger('pageSet', 0);
			}
			
		},
		
		// Use the destroy method to reverse everything your plugin has applied
		destroy: function() {

			//  Call parent destroy
			$.Widget.prototype.destroy.call(this);
		
		}

	});
	
})(jQuery);
