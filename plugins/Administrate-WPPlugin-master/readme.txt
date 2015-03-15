=== Plugin Name ===
Contributors: Administrate
Donate link: http://www.GetAdministrate.com/
Tags: Administrate
Requires at least: 3.3.1
Tested up to: 3.3.1
Stable tag: trunk

Use this plugin to link events from Administrate to your Wordpress website, accept registrations, and process payments.  Administrate is online Business Management Software for Training Providers and Training Departments designed to help them manage instructors, courses, events, invoicing, reporting, marketing, and sales.

== Description ==

[http://www.GetAdministrate.com/ Administrate] is online Business Management Software for Training Providers and Training Departments designed to help them manage instructors, courses, events, invoicing, reporting, marketing, and sales.  Use this plugin to link events from Administrate to your Wordpress website, accept registrations, and process payments.

== Installation ==

= Base installation =

PLEASE BACK UP YOUR SITE PRIOR TO INSTALLING
Before installing this plugin, we always recommend you do a complete backup of your database and website files.  Your website hosting provider can instruct you how to do this, and should be providing daily backups of your site.  

NOTE: Administrate in many cases cannot troubleshoot issues caused by installation in less than 24 hours, so the best bet if a problem occurs during installatino is to "Roll Back" the site to the previous backup.

Requirements:
* This plugin is free to download and experiment with using the provided demo credentials.  To link this plugin to your Administrate instance, you MUST have an Administrate customer account with the Wordpress Plugin Website Integration option activated. Please visit http://www.getadministrate.com/ for more details.
* You must have a Wordpress powered website to which you have administrative access to install this plugin.  It is also highly recommended that be able to install plugins via the web interface and be able to upgrade plugins via this interface as well.
* To accept payments by PayPal, you must follow the instructions below to set-up your API user credentials and auto return configuration.
* Your web host must support SOAP within their PHP install (http://www.php.net/manual/en/soap.installation.php). This is usually fine, but check with your hosting company if you are unsure.

Installing via the Wordpress Admin Interface (Recommended):
* Navigate to the Plugins menu within the WordPress Administrative area, and click "Add New".
* Click the "Upload" link, then choose the plugin zip file, and click "Install Now"

Installing via FTP:
* Upload the folder "administrate" to the "/wp-content/plugins/" directory and activate the plugin through the "Plugins" menu in WordPress.

= Upgrading =

PLEASE BACK UP YOUR SITE PRIOR TO UPGRADING
Before upgrading the plugin, we always recommend you do a complete backup of your database and website files.  Your website hosting provider can instruct you how to do this, and should be providing daily backups of your site.  NOTE: Administrate in many cases cannot troubleshoot issues caused by upgrades in less than 24 hours, so the best bet if a problem occurs during upgrade is to "Roll Back" the site to the previous backup.

Automatic Upgrades (Recommended):

1. Login to your Wordpress Admin area and choose the "Installed Plugins" option underneath your Plugins menu.
2. On your list of plugins, find the Administrate plugin.  If an upgrade is available you'll have the option to click "Update Now" to automatically upgrade the plugin.

Manual Upgrades:

When carrying out a manual upgrade of this plugin, it is important that you follow this procedure:

1. Within your Wordpress control panel, navigate to "Settings" > "Administrate" and note down the settings you have used.
2. Navigate to "Plugins" > "Installed Plugins" and "Deactivate" the Administrate plugin. Note the version number of the plugin.
3. Upload the new version of the plugin to your Wordpress install, either using FTP/SFTP or via the Wordpress control panel at "/wp-admin/plugin-install.php?tab=upload".
4. Navigate to "Plugins" > "Installed Plugins" and check that the version number of the plugin has changed to the new version.
5. "Activate" the Administrate plugin.
6. Navigate to "Settings" > "Administrate" and re-enter the settings you noted in step 1.
7. Visit your web site to check that the plugin is working as expected.

== Configuration ==

Please complete the following steps to get your Administrate plugin working.

1. Within your Wordpress control panel, navigate to "Pages" > "Add New" and create a new that you intend to contain the event registration process.

2. Next, go to the "Registration" tab in the Administrate plugin and select the page you created in the drop-down labeled "Event Registration Page." Remember to scroll down to the bottom of the page and click "Save Changes." This page will display the 4 stages of the checkout process underneath whatever content you have on that page.

3. On the front-end of the site, navigate to the page you have just created. You will see that the default state of this page is to show an event calendar for the next twelve months with month and course category filters at the top. Remember that you are currently using the Administrate demo data feed, so the courses you currently see won't be yours, which we will now sort out.

4. Within your Wordpress control panel, navigate to the "API Credentials" tab under the Administrate plugin. Set the "Service Mode," "API Subdomain," "Username," and "Password" to those supplied to you by your Administrate account manager. Remember to scroll down to the bottom of the page and click "Save Changes."

5. On the front-end of the site, navigate back to (or refresh if you're still on) the page you have just created and you will see that it now displays *your* events for the next twelve months.

6. That's it, you're done. You can use the page you've created as the events list for your visitors, and/or if you prefer you can include simpler events lists on other pages - please see the "Frequently Asked Questions" section of this document for assistance. You may also want to explore the other options on the Adminstrate plugin control panel. If you want to accept payment by PayPal, you need to change the allowable payment types and enter your PayPal API details as explained below in the "Frequently Asked Questions" section of this document.

== Frequently Asked Questions ==

= How do I add PayPal as a payment option? =

Firstly, on the WordPress Administrate plugin settings page change allowable payment types to allow payment by credit card and ensure the the online payment processor is set to PayPal.

Secondly, make sure you have set "Auto Return" to "ON" within your PayPal account. Navigate to "Profile" > "My Selling Preferences". And click "update" on the "Website Preferences" line, which may be found within the "Selling Online" section. Set auto-return to On and the return URL to your web site address. Remember to click "Save" at the bottom of the page to make these change permanent.

Next, you need to add your PayPal API user credentials on . To find the API details log in to PayPal and select "Profile" > "My Selling Preferences" from the top menu. Click "update" on the "API Access" line, which may be found within the "Selling Online" section. Select option 2 "View API Signature" to generate (or show if you have previously generated) your API credentials. If you've not generated your API credentials before, select "Request API signature" and click "Agree and Submit" to generate your credentials. Copy and paste each of these from PayPal into the relevant section at the bottom of the WordPress plugin control panel:

1. API Username
2. API Password
3. Signature

= How do I change the look and feel of the plugin screens? =

You can use CSS to change the look and feel of the plugin from within your wordpress theme.  CSS will allow you to radically reposition elements, change colors, fonts, etc, and these changes will be saved within your Wordpress theme so upgrades to the plugin won't destroy your visual customisations.  We recommend checking the plugin pages after each upgrade just to be sure we haven't added anything that you may now need to style.

= What if I want to reword anything? =

The wording of error messages can be controlled from the WordPress Administrate plugin settings page. If you want to reword the content of the templates within the 4-stage checkout process, please create your own custom template designs as detailed above.

== Screenshots ==

None.

== Changelog ==

= 3.6.3 =
* Resolved an issue where site URL's were generated incorrectly.
* Resolved an issue where the event table would not load on certain Windows hosts.
* Resolved an issue where course category listing would display incorrectly if 'hide courses with no scheduled events' option was ticked.

= 3.6.2 =
* Added a 'pre-flight check' when taking bookings, to ensure that sold-out courses cannot be booked if the cache is stale.
* Resolved an issue where the plugin running on a windows host would generate URL's incorrectly.

= 3.6.1 =
* Resolved an issue where clicking 'back' from a payment processor would allow you to proceed to book without payment.

= 3.6.0 =
* New feature! You can now enable 'delegate notes' in the registration tab to allow people to enter notes about both about individual delegates and their booking as a whole.

= 3.5.15 =
* Resolve an issue where plugin would require more complex permalink structure to correctly identify SEO URL's.

= 3.5.14 =
* Fix an issue where course URL's may not be generated correctly.

= 3.5.13 =
* Resolve an issue where the plugin would incorrectly report that jQuery was out of date.

= 3.5.12 =
* Resolved an issue where the orders page may fail to load.

= 3.5.11 =
* Internal code tidy-up and various tweaks.
* Resolve a caching issue where filters may be ignored on event tables.
* Event table should now *not* display lots of whitespace if there are only a few events.

= 3.5.10 =
* Only Wordpress 'Administrators' should now see the Administrate plugin settings.

= 3.5.9 =
* Resolved an issue where PHP warnings may be displayed on individual course pages.

= 3.5.8 =
* Resolved an issue where duplicate orders may be placed when receiving payment via PayPal.
* Ensured that if anything fails at the payment stage, the order is marked as 'pending' within the plugin and not passed through to Administrate.
* Tidied up some warnings which were displayed within the plugin admin page.

= 3.5.7 =
* Fixed an issue where clients with specific wordpress setups would have incorrectly generated URL's.
* Fixed an issue where the logs/orders page was not being sorted correctly.

= 3.5.6 =
* Enabled accordion-style payment selector during checkout for clients using IE9.

= 3.5.5 =
* Add email validation to the email fields on the order process.
* Fix an issue where incorrect emails would lead to orders being unable to proceed with validation within Administrate.

= 3.5.4 =
* Add pagination to the order logs screen, it should be easier to filter and navigate the orders which your customers have placed.
* Fixed an issue where on certain server configurations, a blank screen would be shown on the hand-back from a SagePay payment.
* Fixed an issue where course URL structure settings could cause course pages to show a 404 error.

= 3.5.3 =
* Addresses an issue where plugin URL's could be generated without a preceeding slash.

= 3.5.2 =
* Internal fixes.

= 3.5.1 =
* Performance improvement for course calendars, particularly where a lot of event share the same course.
* Fix issue where after a recent update PayPal and SagePay may get stuck in 'test' mode.

= 3.5.0 =
* Add support for displaying event time information - you can enable this in the 'Events' tab.
* Fix issue where event list would show the same information (title, date, etc.) for every single event.
* Fix 'locations' display for event list.

= 3.4.1 =
* Fixed a further issue where duplicate orders could be created from Paypal orders.
* Fixed issue where payments by credit card might not receive an email when "Send the user an email when paying by credit card." option is set.
* Updated URL for SagePay when in simulator mode.

= 3.4.0 =
* Prevents an issue where orders may become duplicated when submitted via PayPal.
* Fixed an issue where SagePay would redirect to a blank screen after payment.
* Allowed for SagePay and PayPal to be put into 'test' and 'sandbox' mode respectively.
* Allowed for the disabling of duplication checking on attendee emails when more than one delegate is to be registered to a course.

= 3.3.0 =
* Fix issue where cache could become corrupted and match API calls incorrectly with their results.
* Fix potential issues with API parameters which are not arrays and can be blank.
* Add thorough API parameter validation.
* Performance fixes for pages which utilise getCourseByCode call heavily.

= 3.2.2 =
* Fixed SEO URL bugs.

= 3.2.1 =
* Fixed cache issue.
* Fixed subcategory course listings.

= 3.2.0 =
* Added SagePay payment processing.
* Fixed SEO URL bugs.
* Hide events table on course pages that have no events to display.

= 3.1.3 =
* Added debug option and admin tab.
* Fixed order log color coding.
* Removed 'Book Now' button from event slider for sold out events.

= 3.1.2 =
* Fixed bug that prevented multiple events on the same day from showing.
* Added rich text editor for HTML textareas in the admin.
* Fixed PayPal cancel return URL bug.
* Fixed bug with adding new delegates.
* Added check for PHP SOAP client, display error if not present.
* Improved jQuery / jQuery compatibility to go back to jQuery 1.4.1 / jQuery UI 1.8.

= 3.1.1 =
* Added option to select how many attendees can register at one time.
* Fixed net/gross pricing bug on step 1 of checkout widget.
* Added option to treat event with zero number of places remaining as sold out.
* Cleanup of error & warning messages into consistent interface.
* Fixed admin Javascript error after full domain input addition in 3.1.0.

= 3.1.0 =

Events Table
* Change "PRICE" column heading to "PRICE PER PERSON".
* Change currency code to currency symbol and put it in front of price (i.e., 1 GBP becomes £ 1). Several options on the "Pricing" tab to accomplish any number of ways of displaying price.
* Add "All Prices are Exclusive of VAT" tagline under table.
* Add paging mechanism for table.
* Fixed duplicate location name in location filter drop down.
	
Course Registration
* Add the course date to the line item beside price & # attendees on first tab. 
* Highlight the course line item better on the first tab.
* Add "excludes VAT" to price on first tab.
* Add option for default country selected on the second tab.
* Add a checkbox that user much check on T&C third tab.
* Add summary of course date, price, tax, and invoice total to fourth (payment) tab. 

Course Pages
* Add SEO URLs, meta keywords & description fields for all courses / subcategories / categories.
* Added ability to hide any course / subcategory / category.
	
= 3.0.11 =
* Added activation test on every admin page load to ensure activation actually occurred (seems to be a bug in WP 3.5.2).
* Fixed course prices show option.

= 3.0.10 =
* Improved performance.
* Added option to show prices in events tables.

= 3.0.9 =
* Removed # places debug code.

= 3.0.7 =
* Fixed event price API call bug.
* Only show course page content on category listing page.
* Added Category + Course submenu option.
* Fixed # places remaining bug.

= 3.0.6 =
* Fixed event table Javascript category filter issue.
* Added year option to event dates.
* Fixed number of places in event API.

= 3.0.5 =
* Fixed registration bugs.

= 3.0.4 =
* Added event slider widget.
* Added option to only show categories & courses that have events.
* Fixed checkout for IE9 and below.
* Fixed API user / delegate creation bug.

= 3.0.3 =
* Fixed for PayPal's [not-so] IPN.

= 3.0.2 =
* Added API data reference tab in plugin admin.
* Added conversion tracking snippet settings field under "Checkout" tab.
* Added filters and grouping to administrate_event_list shortcode.

= 3.0.1 =
* Added customizable cache time & manual cache purge & build.
* Changed cache results database field to MEDIUMTEXT to make it big enough for large result sets.
* Added check for supported PHP versions.
* Preserved line breaks on Terms of Use.
* Removed currency selector when only 1 currency present.
* Fixed new order bug where order failed for new users in API system.

= 3.0 =
* New tabbed plugin administration area.
* New widgets, including course submenus and pages.
* Enhanced event registration.

== Upgrade Notice ==

= 3.0 =
You must install version 3.0+ manually. Please backup your website and database, then deactive and delete version 2.x before uploading version 3.x. Version 3.x will attempt to migrate your data from version 2.x automatically.
