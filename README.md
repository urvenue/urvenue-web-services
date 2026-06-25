# wp-urvenue-web-plugin
Repository to Update the Plugin: Urvenue Web Services 
With the Wordpress.org rules

=== UrVenue Web Services ===

Contributors: urvenue
Tags: hospitality, nightlife, tickets, booking, ticketing, events, calendar, urvenue, reservations
Requires at least: 5.0.0
Tested up to: 7.0
Stable tag: 1.2.6
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Effortlessly integrate UrVenue events into your website. Empower users to book inventory directly from UrVenue.com.

== Description ==

UrVenue Web Services plugin allows you to access to access the services from urvenue.com, add to you website:

* "Events" add to a event calendar, agenda and list to you website
* "Event Page" create a page for each of your events
* "Add your UrVenue Inventory" add the urvenue inventory, allow users to purchase you experiences

== Frequently Asked Questions ==

= What do I need to start using UrVenue Web Services Pluign =

You need a UrVenue account, with you account get an `API key` and a `Microcode`, if you don't have one yet contact urvenue

= How can I get a UrVenue account =

Constact with our sales services to request a demo: https://www.urvenue.com/request-demo/

== External Services ==

This plugin connects to external services to retrieve and display events, inventory, availability, and booking information for your venue.

= UrVenue UvTix API =

This plugin communicates with the UrVenue UvTix API to fetch events, inventory items, availability calendars, and cart/booking data. All core plugin functionality depends on this service.

**Data sent:**

* API key, source code, and source location (venue credentials configured in the plugin settings).
* Requested resource identifiers (event codes, inventory item codes, venue codes, cart tokens).
* Date ranges and filter parameters selected by the site visitor.
* Data is sent on every page load that includes an UrVenue shortcode and every visitor interaction (date selection, item selection, cart operations).

**Service provider:** UrVenue, Inc.

* Privacy Policy: https://urvenue.me/privacy-policy
* Terms of Service: https://www.urvenue.com/terms-of-service/

= WP Engine Cache API (optional) =

When the WP Engine cache purge integration is enabled from the plugin's admin panel, this plugin sends a cache-clear request to the WP Engine API after the plugin's own feed cache is purged.

**Data sent:**

* WP Engine install ID and API token (configured by the site administrator in the plugin settings).
* A purge request is only sent when an administrator manually triggers a cache clear from the plugin's Cache admin panel.

**Service provider:** WP Engine, Inc.

* Privacy Policy: https://wpengine.com/legal/privacy/
* Terms of Service: https://wpengine.com/terms-of-service/

== Changelog ==

= 1.2.6 =
* Added full external services disclosure in readme to comply with WordPress.org plugin guidelines.
* Added full external services disclosure in readme to comply with WordPress.org plugin guidelines.
* Security improvements and bug fixes.

= 1.2.5 =
* Security improvements and bug fixes.

= 1.2.4 =
* Bug fixes and security patch.

= 1.2.3 =
* Updates for WP security

= 1.2.2 =
* Remove eval

= 1.2.1 =
* Library upgrade.

= 1.2.0 =
* Contributors update.

= 1.0.52 =
* Transparent price.

= 1.0.5 =
* Hybrid Widget - Look for Specific Ecozone

= 1.0 =
* Initial version, events system and inventory.

== Upgrade Notice ==

= 1.0 =
* Just released.