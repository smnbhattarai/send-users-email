=== Send Users Email ===
Contributors: metalfreek
Donate link: https://www.buymeacoffee.com/smnbhattarai
Tags: email, email users, email roles, send email
Requires at least: 5.7
Tested up to: 6.0
Requires PHP: 7.3
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Send user email plugin helps administrators to send email to their system users either by selecting individual or roles.

== Description ==

Send user email allows you to easily send email by selecting individual user(s) or send bulk messages using role(s).

This plugin has a very simple interface with minimal features so that you can just send simple emails without having to fiddle around with tons of settings.

This plugin uses `wp_mail` function to send emails. Any other E-Mail plugin that tap on `wp_mail` functions works with this plugin.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plugin files to the `/wp-content/plugins/send-users-email` directory, or upload plugin zip by going to Upload Plugin section at /wp-admin/plugin-install.php
2. Activate the plugin through the 'Plugins' screen in WordPress admin

== Frequently Asked Questions ==

= Can I select individual users? =

Absolutely. Go to `Email Users` page of plugin and select user you want to send email to.

= Can I choose multiple roles? =

Yes. You are able to choose one or many roles at a time and send email.

= When is email send? =

Emails are processed immediately and there is no delay. However, depending on your hosting or mail service provides, there might be slight delay in delivery.

= I have many users in my system and many are not getting the emails? =

Since, processing is happening immediately, low `max execution time` in PHP setting might terminate the process. Try increasing the max execution time.

= I have an issue/question/suggestion/request? =

Please post your issue/question/suggestion/request to support forum. I will try and address it as soon as possible.

== Screenshots ==

1. Admin dashboard providing basic overview of users in the system.
2. Send email to individual users
3. Send email by selecting roles

== Changelog ==

= 1.0.3 =
* Added ability for users to style email template
* minor bug fixes

= 1.0.2 =
* Username placeholder added to email template
* Email From/Reply-To settings added

= 1.0.1 =
* Settings bug fix and style changes

= 1.0.0 =
* Initial release