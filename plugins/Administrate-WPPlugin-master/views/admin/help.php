<h3><?php _e('Help', 'administrate'); ?></h3>

<?php _e('
<p>To use our Wordpress Plugin with your live data you\'ll need to create an API User within the Administrate Application.</p>
<ol>
	<li>Navigate to Settings -> Users</li>
	<li>Click on the API Users tabs and select \'New API User\'</li> 
	<li>Enter the fields required and assign both of the special roles to this user</li>
</ol>
<p>Within the plugin you\'ll be asked for the Administrate API Settings, enter the following:</p>
<ul>
	<li>Live Subdomain:  This is the identifier in your Administrate domain i.e. \'demo\' is the identifier in http://demo.administrate.co</li>
	<li>Username:  As defined when creating the API user in the Administrate Application</li>
	<li>Password:  As defined when creating the API user in the Administrate Application</li>
</ul>
', 'administrate'); ?>

<p><?php _e('<strong>Note:</strong> Administrate in many cases cannot troubleshoot issues caused by upgrades in less than 24 hours, so the best bet if a problem occurs during upgrade is to "Roll Back" the site to the previous backup.', 'administrate'); ?><p>

<h3><?php _e('Widgets', 'administrate'); ?></h3>

<?php _e('
<h4>Course Menu</h4>
<p>To automatically show your course categories in your website\'s menu, follow these directions:</p>
<ol>
	<li>Create a page in Wordpress that will be your main course listing page.</li>
	<li>If your theme is not using a custom menu on your website, create a new custom menu under "Appearance" ... "Menus."</li>
	<li>Add whatever pages you wish to your menu, including the page you created as your course listing page.</li>
	<li>Go the "Courses" tab in the Administrate plugin.</li>
	<li>Select the page you created in the "Course Information Page" drop-down.</li>
	<li>Select what type of submenu you\'d like to display in the "Course Submenu" drop-down. <strong>Warning:</strong> Only choose the "All Courses" option if you offer 10 or less courses, otherwise you may experience slow page load times.</li>
	<li>Scroll down to the bottom of the page and click the "Save Changes" button.</li>
</ol>
<p>Your custom menu on your website will now automatically display a submenu of the type you selected. When a user clicks on a link in the submenu, he/she will be taken to the appropriate course / category information page. Please contact your theme developer for support if your custom menu is not automatically displayed.</p>

<h4>Event Registration Page</h4>
<p>To allow your customers to register for your upcoming events, follow these directions.</p>
<ol>
	<li>Create a page in Wordpress that will be your event registration page.</li>
	<li>Go the "Registration" tab in the Administrate plugin.</li>
	<li>Select the page you created in the "Event Registration Page" drop-down.</li>
	<li>Scroll down to the bottom of the page and click the "Save Changes" button.</li>
</ol>
<p>The event registration page will now allow users to register for your upcoming events.</p>

<h4>Event Registration Menu</h4>
<p>To automatically show your upcoming events in your website\'s menu, follow these directions. <strong>Warning:</strong> Only enable this submenu if you have 10 or less upcoming events, otherwise you may experience slow page load times.</p>
<ol>
	<li>Create a page in Wordpress that will be your event registration page.</li>
	<li>If your theme is not using a custom menu on your website, create a new custom menu under "Appearance" ... "Menus."</li>
	<li>Add whatever pages you wish to your menu, including the page you created as your event registration page.</li>
	<li>Go the "Registration" tab in the Administrate plugin.</li>
	<li>Select the page you created in the "Event Registration Page" drop-down.</li>
	<li>Check the checkbox below the drop-down labeled "Automatically show events under event registration page in menu."</li>
	<li>Scroll down to the bottom of the page and click the "Save Changes" button.</li>
</ol>
<p>Your custom menu on your website will now automatically display a submenu. When a user clicks on a link in the submenu, he/she will be taken to the appropriate event registration page. Please contact your theme developer for support if your custom menu is not automatically displayed.</p>

<h4>Category List</h4>
<p>The Category List widget will automatically display on the page you designate as your "Course Information Page" on the "Courses" tab.</p>
<p>If you wish to display a list of your course categories elsewhere, use the shortcode:</p>
<code>[administrate_category_list show_subcategories="true|false"]</code>
<p><strong>Note:</strong> You must define a "Course Information Page" on the "Courses" tab so that the links in the Category List widget will point to the right place.</p>

<h4>Subcategory List</h4>
<p>The Subcategory List widget will automatically display on the page you designate as your "Course Information Page" on the "Courses" tab.</p>
<p>If you wish to display a list of your course subcategories elsewhere, use the shortcode:</p>
<code>[administrate_subcategory_list category="category ID"]</code>
<p><strong>Note:</strong> You must define a "Course Information Page" on the "Courses" tab so that the links in the Subcategory List widget will point to the right place.</p>

<h4>Category</h4>
<p>The Category widget will automatically display on the page you designate as your "Course Information Page" on the "Courses" tab.</p>
<p>If you wish to display a single category elsewhere, use the shortcode:</p>
<code>[administrate_category category="category ID" show_codes="true|false"]</code>
<p><strong>Note:</strong> You must define a "Course Information Page" on the "Courses" tab so that the links in the Category widget will point to the right place. The <strong>show_codes</strong> attribute indicates whether course names should include their course codes.</p>

<h4>Subcategory</h4>
<p>The Subcategory widget will automatically display on the page you designate as your "Course Information Page" on the "Courses" tab.</p>
<p>If you wish to display a single subcategory elsewhere, use the shortcode:</p>
<code>[administrate_subcategory subcategory="subcategory ID" show_codes="true|false"]</code>
<p><strong>Note:</strong> You must define a "Course Information Page" on the "Courses" tab so that the links in the Subcategory widget will point to the right place. The <strong>show_codes</strong> attribute indicates whether course names should include their course codes.</p>

<h4>Course List</h4>
<p>The Course List widget will automatically display on the page you designate as your "Course Information Page" on the "Courses" tab.</p>
<p>If you wish to display a list of your courses elsewhere, use the shortcode:</p>
<code>[administrate_course_list category="category ID" subcategory="subcategory ID" show_codes="true|false"]</code>
<p><strong>Note:</strong> You must define a "Course Information Page" on the "Courses" tab so that the links in the Course List widget will point to the right place. You must specify either the <strong>category</strong> or <strong>subcategory</strong> attribute. The <strong>show_codes</strong> attribute indicates whether course names should include their course codes.</p>

<h4>Course</h4>
<p>The Course widget will automatically display on the page you designate as your "Course Information Page" on the "Courses" tab.</p>
<p>If you wish to display a single course elsewhere, use the shortcode:</p>
<code>[administrate_course course="course code" show_code="true|false"]</code>
<p><strong>Note:</strong> You must define an "Event Registration Page" on the "Registration" tab so that the links in the Course widget will point to the right place.</p>

<h4>Event List</h4>
<p>If you wish to display a list of your upcoming events, use the shortcode:</p>
<code>[administrate_event_list show_dates="true|false" show_codes="true|false" show_locations="true|false" num_months="1-12" category="category ID" course="course code" location="location name" group_by="none|course|category|location" group_title_pre="text to prepend" group_title_post="text to append"]</code>
<p><strong>Note:</strong> You must define an "Event Registration Page" on the "Registration" tab so that the links in the Event List widget will point to the right place.
<p>You may use the following attributes:</p>
<ul>
	<li><strong>show_dates</strong> indicates whether to show the event date(s).</li>
	<li><strong>show_codes</strong> indicates whether to show the course code in front of the course title.</li>
	<li><strong>show_locations</strong> indicates whether to show the event location.</li>
	<li><strong>num_months</strong> indicates how many months in the future to show.</li>
	<li><strong>category</strong> limits the events to the specified category ID.</li>
	<li><strong>course</strong> limits the events to the specified course code.</li>
	<li><strong>location</strong> limits the events to the specified location name.</li>
	<li><strong>group_by</strong> groups the events by the specified method and adds a title to each group.</li>
	<li><strong>group_title_pre</strong> prepends the specified string to the beginning of all group titles.</li>
	<li><strong>group_title_post</strong> appends the specified string to the end of all group titles.</li>
</ul>

<h4>Event Table</h4>
<p>The Event Table widget will automatically display on the page you designate as your "Course Information Page" on the "Courses" tab.</p>
<p>If you wish to display an event table elsewhere, use the shortcode:</p>
<code>[administrate_event_table show_prices="true|false" num_months="1-12" course="course code" show_categories="true|false" show_names="true|false" show_codes="true|false"]</code>
<p><strong>Note:</strong> You must define an "Event Registration Page" on the "Registration" tab so that the links in the Event Table widget will point to the right place.</p>
<p>You may use the following attributes:</p>
<ul>
	<li><strong>show_prices</strong> indicates whether or not to show the event prices in the table.</li>
	<li><strong>num_months</strong> indicates how many months in the future to show by default. Note that users will be able to change the view from 1-12 months.</li>
	<li><strong>course</strong> limits the events to the course code specified.</li>
	<li><strong>show_categories</strong> indicates whether or not to allow the user to filter events by course category.</li>
	<li><strong>show_names</strong> indicates whether or not to show the course names in the table.</li>
	<li><strong>show_codes</strong> indicates whether or not to show the course codes in the table.</li>
</ul>

<h4>Event Slider</h4>
<p>To use the event slider widget, use the shortcode:</p>
<code>[administrate_event_slider show_dates="true|false" show_locations="true|false" show_codes="true|false" show_places="true|false" items_per_group="1+" num_months="1-12" category="category ID" course="course code" location="location name"]</code>
<p>You may use the following attributes:</p>
<ul>
	<li><strong>show_dates</strong> indicates whether to show the event date(s).</li>
	<li><strong>show_locations</strong> indicates whether to show the event location.</li>
	<li><strong>show_codes</strong> indicates whether to show the course code in front of the course title.</li>
	<li><strong>show_places</strong> indicates whether to show the places remaining for the event.</li>
	<li><strong>items_per_group</strong> indicates how many events to show at a time. Defaults to 4.</li>
	<li><strong>num_months</strong> indicates how many months in the future to show.</li>
	<li><strong>category</strong> limits the events to the specified category ID.</li>
	<li><strong>course</strong> limits the events to the specified course code.</li>
	<li><strong>location</strong> limits the events to the specified location name.</li>
</ul>
', 'administrate'); ?>

<br><br>
<?php $txt = $this->plugin->parse_readme($this->plugin->get_path('/readme.txt')); ?>
<h3><?php _e('Frequently Asked Questions', 'administrate'); ?></h3>
<?php $txt = $this->plugin->parse_readme($this->plugin->get_path('/readme.txt')); ?>
<?php foreach ($txt[0]['items'] as $item) { ?>
	<?php if (is_array($item) && isset($item['title']) && ($item['title'] == 'Frequently Asked Questions')) { ?>
		<?php foreach ($item['items'] as $faq) { ?>
			<?php if (isset($faq['title'])) { ?>
				<h4><?= $faq['title']; ?></h4>
				<?php foreach ($faq['items'] as $line) { ?>
					<?= $line; ?><br>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php } ?>
