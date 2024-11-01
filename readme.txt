=== WP InfusionSoft ===
Plugin Name: WP InfusionSoft
Tags: infusionsoft, infusionsoft web form, web forms, contact forms, infusion forms, infusion web forms, infusion contact forms
Requires at least: 2.7.1
Tested up to: 3.0
Stable tag: 1.0.0
Contributors: Taylor Lovett
Download link: http://www.taylorlovett.com/wordpress-plugins/
Author: Taylor Lovett
Author URI: http://www.taylorlovett.com

== Description ==
WP Infusionsoft is a plugin for handling web forms created by the popular email marketing site InfusionSoft.

== Installation ==
1. Upload to /wp-content/plugins
2. Activate the plugin from your Wordpress Admin Panel

== Configuring and Using the Plugin ==
1. In the Wordpress Admin Panel, under the settings tab click WP Infusionsoft
2. Enter your forms information in the Create A New Form section
Form Name - similar to a post slug, is not visible to users, must be unique - for identification purposes only
Form Title - Shows up above the form and is visible to site visitors
Submit Button Text - The text that shows up on top of the forms submit button
Hidden Code - When you create a web form in Infusionsoft, the code contains three lines of hidden input fields.
For example:
<input type="hidden" name="infusion_xid" value="00e202db635fbfe7b2a1f5c190f07aa4" id="infusion_xid" />
<input type="hidden" name="infusion_type" value="CustomFormWeb" id="infusion_type" />
<input type="hidden" name="infusion_name" value="PCIBLOG inpost" id="infusion_name" />
It is important you paste all three lines of hidden input fields in this field or your form will not work.
Add Name, Add Phone, Add Address - Choose which input fields you want your form to show.
3. Click Create Form

== Showing Your Form in Pages and Posts ==
After creating a form, you form will show up in the Manage Forms area. Copy your forms "Code to Show Form in Blog" (i.e. [infusion form=4] ) and paste that in your post or page to display your infusion soft web form. 

== WP Infusionsoft Sidebar Widget ==
In the widget section under Appearance you can drag the "Infusionsoft Optin" widget in to your sidebar.
In the widget options, the title is the same as Form Title in "Creating a Form" as well as the Hidden Code and Submit Button Text. Check Add Name, Add Phone, and Add Address depending on which input fields you want your infusion web form to show.


== Questions, Troubleshooting, Bug Reports ==
Email me at admin@taylorlovett.com

== Changelog ==
1.0.0: First version released! Bare with us while we fix initial bug reports!

== Frequently Asked Questions ==
None yet! Email questions to admin@taylorlovett.com

== Upgrade Notice ==
An upgrade will occur in the middle of August 2010

== Screenshots ==
Screenshots will be uploaded to http://taylorlovett.com/wordpress-plugins