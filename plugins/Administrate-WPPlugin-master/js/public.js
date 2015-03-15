(function($){
	
	//  SETTINGS
	var prefix = 'administrate-';
	var checkoutWidgetId = prefix+'checkout';
	var eventTableWidgetId = prefix+'event-table';
	var eventSliderWidgetClass = prefix+'event-slider';
	var currencySelectorWidgetClass = prefix+'event-price-currencies';
	
	//  Add 'js' class to body
	$(document.body).addClass(prefix+'js');
	
	//  Initialize the checkout widget if it exists
	var checkoutWidget = $('#'+checkoutWidgetId);
	if (checkoutWidget) {
		checkoutWidget.administrate_checkout();
	}
	
	//  Initialize the event table widget if it exists
	var eventTableWidget = $('#'+eventTableWidgetId);
	if (eventTableWidget) {
		eventTableWidget.administrate_event_table();	
	}
	
	//  Initialize the event table widget if it exists
	var eventSliderWidgets = $('.'+eventSliderWidgetClass);
	$.each(eventSliderWidgets, function(i, slider) {
		$(slider).administrate_event_slider();
	});
	
	//  Initialize the currency selector widgets if present
	var currencySelectorWidgets = $('.'+currencySelectorWidgetClass);
	$.each(currencySelectorWidgets, function(i, el) {
		$(el).administrate_event_price();
	});
	
	//  Launch cache flusher if flag is set
	if (typeof administrateCacheFlusher !== 'undefined') {
		$.get(administrateCacheFlusher, { administrate_flush_cache: true });
	}
	
})(jQuery);
