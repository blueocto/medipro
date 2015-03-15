(function($) {
  
	// The jQuery.administrate namespace will automatically be created if it doesn't exist
	$.widget('administrate.administrate_event_slider', {
	
		// These options will be used as defaults
		options: { 
			scrollerSelector:	'.administrate-event-slider-slides ul',
			navSelector:		'.administrate-event-slider-nav',
			previousSelector:	'.prev',
			nextSelector:		'.next',
			transitionTime:		500,
			pollInterval:		250,
			itemsPerGroup:		4
		},
		
		//  Initialize widget
		_create: function() {
			
			//  Set properties
			this.currentGroup = 0;
			this.currentPos = 0;
		
			//  Get references
			this.body = $(document.body);
			this.scroller = $(this.element.find(this.options.scrollerSelector));
			this.nav = $(this.element.find(this.options.navSelector));
			this.prev = $(this.nav.find(this.options.previousSelector + ' a'));
			this.next = $(this.nav.find(this.options.nextSelector + ' a'));
			this.items = this.scroller.children();
			for (var i = 0, numItems = this.items.length; i < numItems; ++i) {
				this.items[i] = $(this.items[i]);	
			}
			this.itemWidth = this.items[0].outerWidth();
			this.scrollerWidth = this.scroller.outerWidth();
			
			//  Set the number of items per group
			/*var itemsPerGroup = this.element.data('items-per-group');
			if (itemsPerGroup) {
				this.options.itemsPerGroup = itemsPerGroup;	
			}*/
			this.options.itemsPerGroup = Math.floor((this.scrollerWidth + 10) / this.itemWidth);
			
			//  Set the number of groups
			this.numGroups = Math.ceil(this.items.length / this.options.itemsPerGroup);
			
			//  Set event handlers on navigation
			this.prev.on('click', $.proxy(this.goToPreviousGroup, this));
			this.next.on('click', $.proxy(this.goToNextGroup, this));
			
			//  Attach scroll event to scroller
			//this.pollScrollerChange();
			//setInterval($.proxy(this.pollScrollerChange, this), this.options.pollInterval);
			
			//  Set initial nav state
			this.toggleNav();
			
		},
		
		//  Go to previous group
		goToPreviousGroup: function(e) {
			this.goToGroup(this.currentGroup - 1);
			e.preventDefault();
		},
		
		//  Go to next group
		goToNextGroup: function(e) {
			this.goToGroup(this.currentGroup + 1);
			e.preventDefault();
		},
		
		//  Go to a group
		goToGroup: function(num) {
			if (num > (this.numGroups-1)) {
				num = this.numGroups - 1;	
			} else if (num < 0) {
				num = 0;
			}
			var scrollToEl = (this.options.itemsPerGroup * num);
			var left = 0;
			for (var i = 0; i < scrollToEl; ++i) {
				left += this.items[i].outerWidth();	
			}
			this.scroller.stop().animate({
				scrollLeft: left
			}, this.options.transitionTime);
			this.currentGroup = num;
			this.toggleNav();
		},
		
		//  Handle a scroll
		pollScrollerChange: function() {
			var pos = this.scroller.scrollLeft(); 
			if (pos != this.currentPos) {
				for (var i = this.numGroups-1; i >= 0; --i) {
					var groupPos = (this.scrollerWidth * i) - 1;
					if (pos >= groupPos) {
						this.currentGroup = i * 1;
						break;	
					}
				}
				this.currentPos = pos;
				this.toggleNav();
			}
		},
		
		//  Toggle the navigation
		toggleNav: function() {
			if (this.currentGroup == 0) {
				this.prev.parent().fadeOut(this.options.transitionTime);
			} else {
				this.prev.parent().fadeIn(this.options.transitionTime);
			}
			if (this.currentGroup >= (this.numGroups-1)) {
				this.next.parent().fadeOut(this.options.transitionTime);
			} else {
				this.next.parent().fadeIn(this.options.transitionTime);
			}
		},
		
		// Use the destroy method to reverse everything your plugin has applied
		destroy: function() {

			//  Call parent destroy
			$.Widget.prototype.destroy.call(this);
		
		}

	});
	
})(jQuery);
