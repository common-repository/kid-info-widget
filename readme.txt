=== Kid Info Widget ===
Contributors: Mike van Vendeloo
Donate link: 
Tags: baby, child, kid, age, sidebar, widget, weight, length
Requires at least: 2.8
Tested up to: 3.6.0
Stable tag: 0.9

Customizable widget with information about your child. Show baby's age, date of birth, weight, length etc. Supports multiple instances.

== Description ==

Customizable widget with information about your child. Show baby's age, date of birth, weight, length etc. Supports multiple instances. The age is shown in weeks and in number of years, months and days.

The options in the widget are:

* *Name* is the widget's title. 

* *Content* is a free form field where you can add HTML code to display some extra information about your child. For instance what his or her latest achievement is.

* *Photo_url* is an url (including http://) pointing to the location of a (thumbnail) image to be used to show in the widget.
* *Photo_width* indicates the width to be used for the thumbnail image. Default this is 75 pixels. Depending on the width of your sidebar you can increase that size to your wishes.

* *Date_of_Birth* specifies the date we should count distance from. Since this plugin simply takes the difference between 'now' and the specified date, it can
be used for counting down (for dates in the future), or counting up (for dates in the past). It's all up to you.

* *Birth_weight* is a field to store the weight of the child in grams at birth.
* *Birth_length* is a field to store the length in centimeters of the child at birth.

* *Current_weight* is a field to store the weight of the child at this moment.
* *Current_length* is a field to store the length in centimeters of the child currently.
 
* *Customstyle* is a field to indicate a customstyle class as an addition to the default kidinfo class. This can be used to distinguish the styles of two or more widgets on the same page (for instance use blue colors for a boy widget and pink colors for a girls widget.

You can have multiple instances of this widget. There are several optional fields that can be enabled or disabled, these are: showing the age in weeks, birth length and weight and current length and weight.

== Installation ==

The plugin consists of a single .php file, so it's very easy:

1. Upload `kid-info-widget.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Start using it on the 'Widgets' submenu of 'Appearance'

== Frequently Asked Questions ==

1. Can I add my own styling to the content field?
Yes, you can put HTML in the content field, so you can use tags like <b>, &lt;p&gt; etc. You can even put in a table there if you would want to.

2. How to use the dutch translations?
Configure Wordpress in config.php to use the Dutch (or any other) locale. Do this by adding the following to the config.php file:
define('WPLANG', 'nl_NL');

3. How can I customize the styling?
Go to the plugins editor and select the Kid Info Widget. Then select the kid-info-widget.css and add your custom styling there.

== Screenshots ==

No screenshot yet.

== Changelog ==
= 0.9 =
* Added separate styling for photo an information area.
* Fixed calculation of age.
* Fixed some styling/html issues.
* Added lastupdated date to show when the last time was when the widgets data was changed.

= 0.8 =
* Added option to optionally not show the age in weeks.
* Added example custom style class to customize the styling for multiple kids with different colors and/or fonts.
* Fixed some translations.
* Fixed admin page to show the previously entered custom style.

= 0.7 =
* Fixed translation in dutch (renamed to nl_NL) and pot file for other translations.
* Added input field to specify the width of the thumbnail used.

= 0.6 =
* Added stylesheet for better layout control.
* Added customstyle to override styles when using multiple widgets.
* Added translation in dutch and pot file for other translations.

= 0.5 = 
* Added more information like weight and length to the options.
* Made the information about weight and length optional to show.

= 0.1 = 
* Initial release

== Upgrade Notice ==
Upgrading is seamless.