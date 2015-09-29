=== Lively Chat Support ===
Version: 1.0.28
URI: http://www.livelychatsupport.com
Contributors: dallas22ca
Author: Dallas Read
Author URI: http://www.DallasRead.com
Donate link: Purchase an addon!
Tags: free live chat, live chat, live support, online chat, customer service
Requires at least: 3.6
Tested up to: 3.9.1
Stable tag: 1.0.28
License: MIT

The best FREE live chat support for your WP website (no 3rd party dependencies)!

== Description ==

**Lively Chat Support puts the power of connecting with visitors in the palm of your hand. You get:**

* Extremely customizable live chat for your visitors to interact with.
* "Offline Mode" works as a lead-capturing machine while you sleep. You can even customize your thank you message (maybe offer a freebie for signing up).
* [Great customer service.](http://wordpress.org/support/view/plugin-reviews/lively-chat-support)
* Boost your conversion rates with one of our premium addons!

For Lively's features, most companies would charge at least 20 to 30 dollars per month. Lively Chat Support is FREE forever, but we offer a few premium addons to help you get more out of your live chat:

* **Multiple Agents: [Buy Now for $40](http://www.livelychatsupport.com/buy-multi)** - put the power of multiple customer service reps to work for you!
* **Chat from your phone: [Buy Now for $40](http://www.livelychatsupport.com/buy-sms)** - perfect for the small business owner. Through SMS (text messages), you'll be able to stay in touch with the visitors on your website.
* **Triggers: [Buy Now for $40](http://www.livelychatsupport.com/buy-triggers)** - start conversations based on your specified criteria. For example, if they've been on your checkout page too long, ask them "Do you need help checking out?"
* **Surveys: [Buy Now for $40](http://www.livelychatsupport.com/buy-surveys)** - quick and unintrusive, surveys are a great way to learn more about your visitors (who love giving their input!).
* **Cobrowsing: [Preorder for $129](http://livelychatsupport.com/pre-cobrowse)** - without installing anything, your visitors will be able to share their screen with you - safely and securely.

> **Live Demos**

> * [TheUltimateLock.com](http://www.theultimatelock.com)
> * [KeithMarshall.ca](http://keithmarshall.ca)

**DEALS (Big Savings)**

* Want to buy all 4 Premium Addons with fast, reliable tech support? Right now, we're offering an incredible deal - [Buy Now for $99](http://www.livelychatsupport.com/buy-4)!
* Don't have cash and want to try out all 4 Premium Addons? We're offering a huge cost savings - [Buy All 4 Addons for $6.99 a month](http://www.livelychatsupport.com/buy-monthly)!

== Installation ==

1. Visit the Plugins page. Click "Add New", search for "Lively Chat Support", and click install. "Activate" Lively Chat Support.
1. On the left sidebar, click on "Lively Chat Support".
1. Enter your name and email (so we know where to send the offline messages).
1. Visit the settings page to customize your chatbox!

== Frequently Asked Questions ==

= Is Lively Chat Support completely free? =
Lively is FREE to use, but there are a few premium addons. They are:

* **Multiple Agents: [Buy Now for $40](http://www.livelychatsupport.com/buy-multi)** - put the power of multiple customer service reps to work for you!
* **Chat from your phone: [Buy Now for $40](http://www.livelychatsupport.com/buy-sms)** - perfect for the small business owner. Through SMS (text messages), you'll be able to stay in touch with the visitors on your website.
* **Triggers: [Buy Now for $40](http://www.livelychatsupport.com/buy-triggers)** - start conversations based on your specified criteria. For example, if they've been on your checkout page too long, ask them "Do you need help checking out?"
* **Surveys: [Buy Now for $40](http://www.livelychatsupport.com/buy-surveys)** - quick and unintrusive, surveys are a great way to learn more about your visitors (who love giving their input!).
* **Cobrowsing: [Preorder for $129](http://livelychatsupport.com/pre-cobrowse)** - without installing anything, your visitors will be able to share their screen with you - safely and securely.

= When is Cobrowsing available? =
We're working on it... with enough pre-sales, we'll be able to release it this Summer 2014!

= Lively isn't showing up on my site? =
* Are you using a caching plugin? If so, clear the cache.
* Is wp_footer() found anywhere in your theme's files? It should be in footer.php.

= Why are the timestamps in my chatbox wrong? =
You probably haven't set your timezone. You can do so in the General settings page of Wordpress.

= For SMS, how do I handle multiple conversations? =
Each message you receive begins with a small token and a colon (eg. 4k2:). To respond to a specific conversation, type "4k2: YOUR MESSAGE HERE". If you don't include this token in your response, we'll just send it back to the customer that last sent you a message.

= Why doesn't my SMS doesn't work? =
* Ensure your phone numbers include the country code (1 for North America, 44 for UK, etc.)
* The Wordpress ajax requests require access to the back end of your site. Ensure remote IPs aren't blocked for your back end, or you won't be able to send or receive SMS.

== Screenshots ==

1. A great looking chatbox that is easy to tailor to your brand.
2. Admin view is clean and easy to operate. 
3. Customize your chatbox with your brand's colours, and upload your own Call To Action images.
4. Set your chat to always be online, always be offline, or never show. In version 1.0, we'll be introducing a new way to schedule your online hours.
5. Manage who your visitors will be chatting with.
6. Offline mode functions as the perfect lead generation form.
7. Schedule the hours you are available (pairs perfectly with our SMS Addon).

== Feature Suggestions ==

Here's some scheduled features to be added:

* Louder, constant bell in the admin
* Detect which agents are online automatically
* Don't require email address before starting a conversation.
* Button for visitors to "Introduce Yourself"
* Automatic first message from agent
* Have a peek at audio files playing for new messages, new visitors, etc. (esp cross-browser)
* alert to repeat (say once every 10-20 seconds) until a reply is sent
* Store Offline Mode form submissions

== Changelog ==

= 1.0.28 =
* Cache support for button text whether in online or offline mode.
* New bell!

= 1.0.27 =
* Bug fix where fields weren't saving if they were blank strings.

= 1.0.26 =
* Scheduling bug squashed.

= 1.0.25 =
* SMS bug squashed.

= 1.0.24 =
* Bug when registering the plugin.

= 1.0.23 =
* Updating issue when not administrator.

= 1.0.22 =
* Cleared a few errors that were showing up if your theme had error reporting turned on.
* Fixed an issue where some themes were (inappropriately) hijacking links, causing Lively to not function.

= 1.0.20 =
* The Golden IE Fix: Removed 2 trailing commas in JS that were causing Lively to break in IE.
* To combat memory issues, Lively stores all its configuration in one options hash (1000 less cache hits and more than 50% less get_options() calls)! To upgrade, you just need to install the latest version of the plugin - we update your old data to the new format automatically.
* We're preselling Screen Sharing! To be released sometime this summer... :D
* SMS is now supported in "Online" mode (instead of just "Office Hours").
* Empty triggers no longer validate - body text is required.
* Emails are now using wp_mail() function instead of mail().
* New header for the plugin page on wordpress.org!

= 1.0.19 =
* Fixed an issue where previously sent messages would trigger a "ding" on page load.

= 1.0.18 =
* Removed whitespace appearing at the top of the chat on mobile devices.
* Instructions update for SMS.
* Removed extra slashes in Survey's "Thank You" field.
* Lithuanian translation added.
* Monthly plans added for addons ($6.99/month).

= 1.0.17 =
* Just bumping stable tag (sorry about all the updates!).

= 1.0.13 =
* Nearly useless update.

= 1.0.12 =
* Mobile styling bug fix where chatbox title had white space above it.
* Bug fixed where W3 Total Cache was not getting cleared.

= 1.0.11 =
* Chatbox visibility - an easy way to show Lively on certain pages - no shortcode necessary!
* Flushing caches when changes are made (supports W3 Total Cache and WP Super Cache)
* Deleting history in chatbox should keep the 
* Fixed a bug where quick, consecutive messages would fall through the cracks.
* Caching support for Online/Offline/Hours features
* Added Danish translation!

= 1.0.10 =
* menu item shows for all users, so agents don't have to be admins to chat.
* Slashes appearing in chatbox message (Don\'t)
* Twilio sign up link fixed.
* Help PDF added to the assets folder (Thanks Sharon!).
* Profile hooks removed on lively-chat-support.php.
* Updated SMS documentation.
* Visitor conversations are now print friendly!
* Remote HTTPS requests are allowed (some users were unable to connect to the Twilio API for SMS Addon)

= 1.0.9 =
* Twilio credentials fixed.
* FreeIP failures caught.

= 1.0.8 =
* Translations added.
* Curl requests now using WP HTTP.
* Swapped left/right CSS for chatbox position.
* Cleaned up agent interface.

= 1.0.5 =
* MULTI AGENT ADDON IS AVAILABLE!
* Show LivelyChat only on certain pages.
* New "Schedule" tab allows you to say who's online and when.
* Push finish date if clicking on unread convo.
* Green dot appears on a conversation when you receive a new message (no refresh necessary).
* Message Template for convos (generate on the fly instead of clone)... causing issues with some other plugins.
* jQuery scope more obvious and effective so there are not conflicts with your theme.
* Header min-height reset to be compatible with your theme.

= 1.0.4 =
* Fix strange DB multiple primary key error.

= 1.0.3 =
* Fix logout button (somehow didn't update from 1.0.1).

= 1.0.2 =
* Brought back the "Online via SMS" option for those that have purchased the SMS Addon.

= 1.0.1 =
* We've Hit 1,000 Downloads!
* Pre-Order the all new Multi Agent feature through the link in the top right of the plugin page (will be released October 15)
* Schedule your online hours (with screenshot!)
* Sort convos by date!
* Fix chatbox text colour issue
* Apostrophes for chatbox titles and messages
* Visitor logout
* Delete specific convo and delete all conversation history
* Trim twilio credentials and activation code for SMS Addon (so that they work even with an extra space on the end or beginning)
* Option to remove "Powered By Lively Chat" (but we'd prefer if you didn't ;])
* Database tables are charsetted and collated to support unicode (utf8)

= 1.0.0 =
* Caching support for triggers, surveys, and loading messages.
* jQuery noConflict used to prevent JS issues.

= 0.9.9 =
* Survey's addon released! Get specific information from your customers with the most unintrusive tool in town!

= 0.9.8 =
* All chat registrations are emailed to the admin (set on the Settings page).
* Prepping for Triggers addon.

= 0.9.7 =
* Preparing plugin for Addons plus a few other bug fixes.

= 0.9.6 =
* Fixed a problem with the offline form not submitting. Added an option on the settings page to change the email address the form response is sent to.

= 0.9.1, 0.9.2, 0.9.3, 0.9.4, 0.9.5 =
* Working on the plugin screenshots

= 0.9 =
* Lively Chat Support is released into the wild.