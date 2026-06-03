=== UrVenue Web Services ===

Contributors: UrVenue, UvWebServices
Tags: events, booking, calendar, tickets, hospitality
Requires at least: 5.0
Tested up to: 7.0
Stable tag: 1.2.5
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Integrate UrVenue events and inventory into your WordPress site. Display event calendars, venue maps, and enable direct booking.

== Description ==

UrVenue Web Services connects your WordPress site with the [UrVenue](https://www.urvenue.com/) platform, allowing you to display and manage hospitality events, venue maps, and bookable inventory directly on your website.

= Features =

* **Events Calendar** - Display your events in calendar, agenda, or list view with date range filtering and venue selection.
* **Event Pages** - Automatically generate individual pages for each event with full details, images, and booking options.
* **Venue Map** - Interactive venue map with zoom, pan, and clickable sections for inventory selection.
* **Inventory Integration** - Allow visitors to browse and purchase experiences, packages, and memberships directly from your site.
* **Reservations** - Enable table and bottle service reservations through your website.
* **Guest Itinerary** - Provide guests with a personalized itinerary view of their bookings.

= Available Shortcodes =

* `[uws_events]` - Events calendar, agenda, and list views.
* `[uws_event]` - Single event page with details and inventory.
* `[uws_map]` - Interactive venue map with inventory.
* `[uws_inventory_item_page]` - Individual inventory item page.
* `[uws_reservations]` - Reservations form.
* `[uws_packages]` - Packages listing with booking.
* `[uws_guest_itinerary]` - Guest itinerary view.

= External Services =

This plugin connects to external services to provide event, inventory, checkout, reservation, and optional cache-purge functionality. Below is a full disclosure of each service, what data is sent, when it is sent, and links to the service's terms of service and privacy policy.

1) UrVenue / UvTix API
Service: UrVenue API (UvTix)
URL: https://uvtix.com/api/ and https://{envicode}.urvenue.me/
Purpose: Retrieve venue, event, and inventory data displayed by the plugin shortcodes; manage cart and checkout operations; submit inquiry forms.
Data sent: API key, source code (sourcecode), source location (sourceloc), venue/item identifiers, and — for inquiry form submissions — visitor-entered contact information (name, email, phone, opt-in flag).
When: On every page where a plugin shortcode is active, and when visitors interact with cart or inquiry forms.
Terms of Service: https://www.urvenue.com/legal/terms-conditions/
Privacy Policy: https://www.urvenue.com/privacy-policy/

2) Booking / Checkout service (Booketing)
Service: Booketing — UrVenue's hosted checkout platform
URL: https://booketing.com/
Purpose: Redirect visitors to the cart, checkout, payment, and booking-confirmation pages hosted by Booketing.
Data sent: Cart code, sourcecode, sourceloc, manageentid, resellerid, providerid, language, and optional environment parameters — transmitted as URL query parameters during the redirect.
When: When a visitor proceeds to checkout from an inventory or reservation item.
Terms of Service: https://booketing.com/terms/
Privacy Policy: https://booketing.com/privacy/

3) SevenRooms reservation widget
Service: SevenRooms
URL: https://www.sevenrooms.com/reservations/
Purpose: Display an embedded reservation iframe for venues that use SevenRooms as their reservation provider.
Data sent: Venue identifier (SevenRooms venue ID) and the selected reservation date, transmitted as URL parameters when the iframe loads in the visitor's browser directly from SevenRooms servers.
When: Only when a visitor interacts with a venue item configured to use SevenRooms as its reservation vendor.
Terms of Service: https://sevenrooms.com/terms-of-service/
Privacy Policy: https://sevenrooms.com/privacy-policy/

4) OpenTable reservation widget
Service: OpenTable
URL: https://www.opentable.com/restref/client/
Purpose: Display an embedded reservation iframe for venues that use OpenTable as their reservation provider.
Data sent: Restaurant identifier (rid/restref), default party size, selected date and time, and display preferences (language, color scheme) — transmitted as URL parameters when the iframe loads in the visitor's browser directly from OpenTable servers.
When: Only when a visitor interacts with a venue item configured to use OpenTable as its reservation vendor.
Terms of Service: https://www.opentable.com/legal/terms-and-conditions
Privacy Policy: https://www.opentable.com/legal/privacy-policy

5) Google Maps and Google Calendar (link generation only)
Service: Google Maps / Google Calendar
URLs: https://www.google.com/maps/search/?api=1 and https://www.google.com/calendar/event
Purpose: Generate a "Get Directions" link to a venue on Google Maps, and an "Add to Google Calendar" link for event pages. No request is made to Google unless the visitor clicks one of these links.
Data sent: For Maps — venue name/address as a URL query parameter. For Calendar — event name, start/end dates, and venue location as URL parameters.
When: When a visitor clicks the Google Maps or Google Calendar link on a venue or event page.
Terms of Service: https://policies.google.com/terms
Privacy Policy: https://policies.google.com/privacy

6) WP Engine Cache Purge (optional)
Service: WP Engine API
URL: https://api.wpengineapi.com/
Purpose: Purge the page cache for sites hosted on WP Engine. This feature is only active when the site is hosted on WP Engine and the administrator has enabled and configured it in the plugin settings.
Data sent: WP Engine install ID and administrator-configured API credentials (username and password) transmitted in the Authorization header; cache type (page/object/CDN) in the request body.
When: Triggered by an administrator action or automatically after a feed refresh — only if the WP Engine cache integration is configured.
Terms of Service: https://wpengine.com/legal/terms-of-service/
Privacy Policy: https://wpengine.com/legal/privacy/

7) Webhook notifications (optional, site-administrator-configured)
Service: User-defined webhook endpoint (e.g., Slack, Microsoft Teams, or a custom service)
URL: Configured by the site administrator in the plugin settings.
Purpose: Send alert notifications to the webhook URL when plugin alert conditions occur (e.g., empty inventory feed).
Data sent: Alert message text and optional error or API response details, sent via HTTP POST to the administrator-provided URL.
When: Only if notifications are enabled and a webhook URL is configured; throttled to one request per alert type every 30 minutes.
Note: This service is entirely controlled by the site administrator. The plugin developer has no visibility into the destination or data transmitted.

8) Facebook Pixel event tracking (conditional — requires site-level setup by site owner)
Service: Meta / Facebook
URL: https://www.facebook.com/
Purpose: Push ecommerce events (AddToCart) to a Facebook Pixel already installed on the site, enabling the site owner to track inventory interactions in Meta Ads Manager.
Data sent: Ecommerce event name ("AddToCart"), item ID, item name, price (USD), and quantity — relayed to the Facebook Pixel script already present on the page.
When: When a visitor adds an item to the cart. This only fires if the site owner has independently installed the Facebook Pixel on their site; the plugin does not load or inject the Facebook Pixel script itself.
Terms of Service: https://www.facebook.com/legal/terms
Privacy Policy: https://www.facebook.com/privacy/policy/

---

This plugin connects to the UrVenue platform to retrieve event data, venue information, and inventory. All event and booking data is fetched from and processed through UrVenue servers.

* UrVenue website: https://www.urvenue.com/
* Terms of Service: https://www.urvenue.com/legal/terms-conditions/
* Privacy Policy: https://www.urvenue.com/privacy-policy/



== Installation ==

1. Upload the `wp-urvenue-webservices` folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Go to the UrVenue settings page and enter your **API Key** and **Microcode** provided by UrVenue.
4. Create pages and assign them in the UrVenue settings for events, single event, map, and inventory item pages.
5. Add the shortcodes to your pages (e.g., `[uws_events]` for the events page).

== Source Code and Build Information ==

This plugin does not use any build tools (such as webpack, gulp, or composer) to generate its production code. All custom JavaScript and CSS files included in this plugin are human-readable, non-minified source code located in the `uvcore/assets/js/` and `uvcore/assets/css/` directories.

The full source code for this plugin is publicly available at:
https://github.com/urvenue/wp-urvenue-web-plugin

= Third-Party Libraries =

This plugin includes the following third-party libraries in their minified/compressed form. The original source code for each library is available at the linked repositories:

* **jQuery** v3.3.1 - [Source Code](https://github.com/jquery/jquery/tree/3.3.1) - MIT License
* **Flatpickr** v4.6.6 - [Source Code](https://github.com/flatpickr/flatpickr/tree/v4.6.6) - MIT License
* **Hammer.JS** v2.0.8 - [Source Code](https://github.com/hammerjs/hammer.js/tree/v2.0.8) - MIT License
* **jQuery Validation Plugin** v1.22.1 - [Source Code](https://github.com/jquery-validation/jquery-validation/tree/1.22.1) - MIT License
* **Litepicker** v2.0.12 - [Source Code](https://github.com/wakirin/Litepicker/tree/2.0.12) - MIT License
* **svg-pan-zoom** v3.6.1 - [Source Code](https://github.com/ariutta/svg-pan-zoom/tree/3.6.1) - BSD-2-Clause License
* **noUiSlider** v15.6.1 - [Source Code](https://github.com/leongersen/noUiSlider/tree/15.6.1) - MIT License
* **perfect-scrollbar** v1.5.3 - [Source Code](https://github.com/mdbootstrap/perfect-scrollbar/tree/1.5.3) - MIT License
* **Pristine.js** (form validation) - [Source Code](https://github.com/sha256/Pristine) - MIT License

No build steps are required to use or modify this plugin. Simply edit the source files directly.

== Frequently Asked Questions ==

= What do I need to start using UrVenue Web Services? =

You need a UrVenue account. With your account you will receive an `API Key` and a `Microcode` to configure the plugin.

= How can I get a UrVenue account? =

Contact our sales team to request a demo: [https://www.urvenue.com/request-demo/](https://www.urvenue.com/request-demo/)

= What event display formats are available? =

The plugin supports three display formats: Calendar (monthly grid view), Agenda (sequential list), and List (detailed cards with images).

= Can visitors purchase tickets directly from my site? =

Yes, the plugin integrates with the UrVenue inventory system, allowing visitors to browse and purchase experiences, packages, and reservations directly from your WordPress site.

== Screenshots ==

1. Events displayed in calendar view with date filtering and venue selection.
2. Events displayed in list view with event details and booking buttons.
3. Single event page with full details and inventory options.
4. Interactive venue map with zoom and pan controls.

== Changelog ==
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
* Hybrid Widget - Look for specific ecozone.

= 1.0 =
* Initial version, events system and inventory.

== Upgrade Notice ==

= 1.2.1 =
Updated third-party libraries.

= 1.0 =
Initial release.
