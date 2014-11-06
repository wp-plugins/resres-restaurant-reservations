=== ResRes ===
Contributors: deftdev
Donate link: http://deftdev.com/
Tags: restaurant, reservation, booking, menu, 
Requires at least: 3.7.1
Tested up to: 4.0
Stable tag: 1.0.8.f
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A restaurant reservation and menu plugin from deftDEV

== Description ==

ResRes is a fully featured restaurant reservation and menu system. Create custom menus to show off your dishes, and allow your customers to easily reserve a table online.


####Features

* Easily add a reservation form to your WordPress site via a shortcode.
* The form will automatically block out days and times your restaurant is closed to stop people from booking the wrong times.
* Set certain weekdays as closed on the calendar and also set specific days (such as Christmas day) closed as well.
* A capacity system, allows you to limit the number of bookings per hour.
* Customisable customer and admin emails.
* Built in tags to easily add info to emails such as reservation date, party size, etc.
* Opening times & location shortcode.
* Themeroller styled forms ( Smoothness only )
* Menu creation is a breeze thanks to using WordPress Custom Post Types.
* Each dish can be added to a section named whatever you like (Starters, Main Courses, etc).
* Each section can be re-ordered via a drag and drop interface or via the shortcode settings.
* Comes with the default template: two column layout.

####Premium Version

**[ResRes Premium](http://deftdev.com/downloads/resres "deftDEV")**

* The built in admin reservation page, allows you to easily see how many people have booked on any given day. 
* You can also mark customers as cancelled or arrived. 
* Allow users to select what section of your restaurant they would like to sit in.
* Use the built in numeric captcha system or use reCAPTCHA .
* Cancellation form available.
* Themeroller styled forms ( All the default jQuery UI Themeroller styles )
* If you need more than one menu, the shortcodes allow for different sections and event templates to be shown. 
* 6 more templates available - list, chalkboard, grid, simple, accordion and accordion columns.
* Easily assign allergen information or wine type (dry, full bodied, etc) to a dish in text or icon form. 
* Easily add chili icons to denote spicy heat! 
* MailChimp integration.

####Support

Please make sure you read the [documentation](http://www.deftdev.com/document/resres-documentation/).

Further support can be found in the [support forum](http://www.deftdev.com/support/) (registration required)

Feature requests and bug reports can be requested/reported via this [form](http://www.deftdev.com/feature-requests-bug-reports/).

== Installation ==

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'resres'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `resres.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `resres.zip`
2. Extract the `resres` directory to your computer
3. Upload the `resres` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard


== Frequently Asked Questions ==

= Is the plugin responsive? =

Yes it is!

= Where can I get support? =

Support is provided on deftdev.com. While we do check the WP.org support forum, it is infrequent, so you are best going direct to deftdev.com

= What's the difference between the free version and the Pro version? =

More features! The **[PRO](http://deftdev.com/downloads/resres "deftDEV")** version has more templates, and admin reservation monitor, manual reservations, cancellation system, captcha systems and more!

= Can I use this with [insert name here] theme? =

While we can't guarantee compatibility with every theme out there (there are a lot of them you know!), if it follows WordPress guidelines then it should work with no or minor issues. The only thing that you may need to do is tweak the CSS to match your theme.

= How does the plugin inform me of a reservation? =

It will email you. With the **[PRO](http://deftdev.com/downloads/resres "deftDEV")** version you will also have access to the admin reservation screen to see who has reserved, cancelled and arrived on any given day.

== Screenshots ==

1. The reservation form
2. A partial look at the default menu template
3. Opening times and location
4. One of the settings pages
== Changelog ==

= 1.0.8.f =
* FEATURE: Added ability to disable/enable the default capthca system.
* FIX: minor notification error fixes.

= 1.0.7.f =
* FEATURE: New Spanish language files added, courtesy of Andrew Kurtis @ WebHostingHub.com

= 1.0.6.f =
* TWEAK: Summer Sale notification
* TWEAK: Minor internationalisation fixes.

= 1.0.5.f =
* FIX: More fixes to the time selector.
* TWEAK: CSS fixes for the menu template to improve responsiveness.
* TWEAK: Added translation function for datepicker.

= 1.0.4.f =
* HOTFIX: The previous fix messed up the time selection, that is now fixed.

= 1.0.3.f =
* FIX: resolved some undefined index notices

= 1.0.2.f =
* FEATURE: added ability to select specific days to disable in the calendar, e.g. Christmas day.
* TWEAK: added support for mobile devices to disable the keypad when date and timepickers are selected.
* TWEAK: opening times shortcode CSS tweaks.
* FIX: Added a fix where people in a negative timezone (e.g. UTC -5) had their disabled days moved back a day.

= 1.0.1.f =
* FIX: stopped themeroller style from disappearing when form options saved.

= 1.0.0.f =
* Initial public release.


== Upgrade Notice ==