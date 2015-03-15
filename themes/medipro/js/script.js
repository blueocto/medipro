// Avoid `console` errors in browsers that lack a console.
(function(){var e;var d=function(){};var b=["assert","clear","count","debug","dir","dirxml","error","exception","group","groupCollapsed","groupEnd","info","log","markTimeline","profile","profileEnd","table","time","timeEnd","timeStamp","trace","warn"];var c=b.length;var a=(window.console=window.console||{});while(c--){e=b[c];if(!a[e]){a[e]=d}}}());

// The slide panels for Equipment categories
var MP = MP || {};
MP.events = MP.events || {};
MP.events = $.extend(MP.events, {
	click__btn_coursedate : function(e) {
        var $me = $(this),
            $variant = $('#course-date-time'),
            $start = $('.selected_start'),
            $end = $('.selected_end');

        $variant.val($me.data('variant')).trigger('change');
        $start.find('span').html($me.data('start'));
        $end.find('span').html($me.data('end'));

        e.preventDefault();
    },

    /**
	 * Function to show the details of a course
	 * @param  {Event} e
	 * @return {void}
	 */
	click__btn_viewcourses : function(e) {
		// Grab some elements in vars, setting classes as we go
		var $me = $(this),
			$myWrapper = $me.parents('.course_item_ovrvw'),
			$myDescription = $myWrapper.find('.course_cat_desc'),
			$myCourses = $myWrapper.find('.course_extd_innr'),
			$myRow = $me.parents('.row');

		// Close any course panes that are already open
		MP.events.hide__openpanes()
			.done(function() {
				MP.events.reset__rows()
					.done(function() {
						$me.addClass('is-hidden');
						$myWrapper.addClass('is-active childmoved');
						$myDescription.show();

						// Run the course DOM copy & animation
						MP.events.show__courses($myRow, $myCourses);
					});
			});
		// Whoa Nelly!
		e.preventDefault();
	},
	/**
	 * When close [x] buttons are pressed
	 * @param  {Event} e
	 * @return {void}
	 */
	click__close : function(e) {
		MP.events.hide__openpanes()
			.done(function() {
				MP.events.reset__rows();
			});
		e.preventDefault();
	},
	/**
	 * General call to hide any panes that are open
	 * @param  {jQuery} $panes (optional)
	 * @return {Promise}
	 */
	hide__openpanes : function($panes) {
		var dfd = new $.Deferred();
		if (! !! $panes) {
			var $all_viewpanes = $('.courses-viewpane');
			$panes = $all_viewpanes.filter('.is-active');
		}
		if (!! $panes.length) {
			$panes.slideUp('normal', function() {
				$panes
					.empty()
					.removeClass('is-active')
					.addClass('is-hidden');
				dfd.resolve('Finished hiding');
			});
		} else {
			dfd.resolve('No open panes');
		}
		return dfd.promise();
	},
	/**
	 * Initialisation function, binds events, etc. on page load
	 * @return {void}
	 */
	init : function() {
		$('.course_list')
			.on('click', '.btn_viewcourses', MP.events.click__btn_viewcourses);
		$('.courses-viewpane')
			.on('click', '.close', MP.events.click__close);
        $('.crs_dts_tbl')
            .on('click', '.btn_coursedate', MP.events.click__btn_coursedate);
	},
	/**
	 * Reset any currently active title blocks in the grid rows
	 * @return {Promise}
	 */
	reset__rows : function() {
		var dfd = new $.Deferred();
		var $active_blocks = $('.course_item_ovrvw.is-active').removeClass('is-active childmoved');
		if (!! $active_blocks.length) {
			$active_blocks.find('.course_cat_desc').hide(),
			$active_blocks.find('.btn_viewcourses').removeClass('is-hidden');
			dfd.resolve('Rows reset');
		} else {
			dfd.resolve('No rows to reset');
		}
		return dfd.promise();
	},
	/**
	 * Animate the showing of courses, replacing and hiding already open courses
	 * as we go.
	 * @param  {object} $row     The wrapper of a row of course headers
	 * @param  {object} $courses The HTML to copy into the view pane
	 * @return {void}
	 */
	show__courses : function($row, $courses) {
		var $all_viewpanes = $('.courses-viewpane'),
			$my_viewpane = $row.find('.courses-viewpane');
		// Add the content to our view pane, open it and set the state
		$my_viewpane
			.html($courses)
			.slideDown('normal', function() {
				$my_viewpane
					.removeClass('is-hidden')
					.addClass('is-active');
			});
	}
});

$(document).ready(function(){

	MP.events.init();

	// Google Maps in Contact expandy
	function initialize() {
		var myLatlng = new google.maps.LatLng(54.463685, -1.177408);
		var mapOptions = {
			zoom: 15,
			center: myLatlng
		}
		var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
		var marker = new google.maps.Marker({
			position: myLatlng,
			map: map
		});
	}
	// Google Maps on Equipment product page
	function initialize() {
		var myLatlng = new google.maps.LatLng(54.463685, -1.177408);
		var mapOptions = {
			zoom: 15,
			center: myLatlng
		}
		var map = new google.maps.Map(document.getElementById('map'), mapOptions);
		var marker = new google.maps.Marker({
			position: myLatlng,
			map: map
		});
	}
	google.maps.event.addDomListener(window, 'load', initialize);

	// Contact Details accordian
	$('#contactDetails').hide();
	$('.btn_expand_contact').click(function(){
		$('.btn_expand_contact').toggleClass('opened');
		$('#contactDetails').slideToggle(function(){
			if($('#contactDetails').is(":visible")){
				$('.btn_expand_contact').text("Hide");
			} else {
				$('.btn_expand_contact').text("Contact Details");
			};
		});
		google.maps.event.trigger(map, 'resize'); //important to prevent grey areas on may
		return false;
	});

	// Basket accordian
	$('#basketOverview').hide();
	$('.btn_basket').click(function(){
		$('#basketOverview').slideToggle();
		return false;
	});

	// Slide Panels on Courses category
	$(".row .course_item_ovrvw:nth-of-type(3)").css('margin-right','0');
	$(".row .course_item_ovrvw:nth-of-type(3)").after('<div class="courses-viewpane is-hidden"></div>');

	// Tabs on the Equipment Product page
	// tabbed content
	// http://www.entheosweb.com/tutorials/css/tabs.asp
	$(".tab_content").hide();
	$(".tab_content:first").show();

	/* if in tab mode */
	$("#tabsTop li").click(function() {
		$(".tab_content").hide();
		var activeTab = $(this).attr("rel");
		$("#"+activeTab).fadeIn();
		$("#tabsTop li").removeClass("active");
		$(this).addClass("active");
		$(".tab_drawer_heading").removeClass("d_active");
		$(".tab_drawer_heading[rel^='"+activeTab+"']").addClass("d_active");
	});
	/* if in drawer mode */
	$(".tab_drawer_heading").click(function() {
		$(".tab_content").hide();
		var d_activeTab = $(this).attr("rel");
		$("#"+d_activeTab).fadeIn();
		$(".tab_drawer_heading").removeClass("d_active");
		$(this).addClass("d_active");
		$("#tabsTop li").removeClass("active");
		$("#tabsTop li[rel^='"+d_activeTab+"']").addClass("active");
	});

	// Extra class "tab_last" to add border to right side of last tab
	$('ul.tabs li').last().addClass("tab_last");

	// Lightbox
	$('#colorboxAccount').colorbox({
		width: 630,
		height: 410,
		scrolling: false
	});


	// Put HTML5 placeholders inside inputs
	$('input, textarea').placeholder();

	// external links
	$("a[rel='external']").addClass("external").attr('title', function() { return this.title + ' (Opens in New Window)' });
	$('body').on('click', "a[rel='external']", function() { window.open(this.href); return false; });

});

// Google Analytics
(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;e=o.createElement(i);r=o.getElementsByTagName(i)[0];e.src='//www.google-analytics.com/analytics.js';r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));ga('create','UA-XXXXX-X');ga('send','pageview');

// Sharing buttons
// Facebook
(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=166113533579481"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));
// Twitter
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
// Google+
window.___gcfg = {lang: 'en-GB'}; (function() { var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true; po.src = 'https://apis.google.com/js/platform.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s); })();
