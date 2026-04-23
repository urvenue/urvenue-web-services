=== UrVenue Web Services ===

Contributors: UrVenue, UvWebServices
Tags: events, booking, calendar, tickets, hospitality
Requires at least: 5.0
Tested up to: 6.9
Stable tag: 1.2.3
Requires PHP: 7.0
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

= External Service =

This plugin connects to external services to provide event, inventory, checkout and optional cache purge functionality.

1) UrVenue / UvTix API
Service: UrVenue API (UvTix)
URL: https://uvtix.com/api/
Purpose: Retrieve venue/event/inventory data used by the plugin.
Data sent: Request parameters such as venue identifiers, source codes, and API key (if configured).
Data received: Venue/event/inventory data in JSON format.

2) UrVenue Microsite API
Service: UrVenue Microsite API
URL: https://{envicode}.urvenue.me/
Purpose: Retrieve microsite user/venue data used by the plugin.
Data sent: API key (if configured), sourcecode, sourceloc, and request parameters.
Data received: JSON payload with microsite data.

3) Checkout / Booking service (Booketing / UrVenue checkout)
Service: Booking/Checkout
URL: https://booketing.com/
Purpose: Redirect users to cart/checkout/payment/success pages.
Data sent: Query parameters such as cart code, sourcecode, sourceloc, manageentid, resellerid, providerid, language, and optional environment parameters.
Data received: Checkout pages rendered by the external service.

4) Google Maps (link generation)
Service: Google Maps Search
URL: https://www.google.com/maps/search/?api=1
Purpose: Generate a Google Maps link for venue locations.
Data sent: Query parameter containing venue name/address.
Data received: Map search results displayed by Google Maps.

5) WP Engine Cache Purge (optional)
Service: WP Engine API
URL: https://api.wpengineapi.com/
Purpose: Purge cache for WP Engine installs (only if the site is hosted on WP Engine and the feature is enabled/configured).
Data sent: Install ID and authentication headers configured by the site administrator.
Data received: API response confirming purge status.

This plugin connects to the **UrVenue API** (https://api.urvenue.com/) to retrieve event data, venue information, and inventory. All event and booking data is fetched from and processed through UrVenue servers.

* UrVenue website: [https://www.urvenue.com/](https://www.urvenue.com/)
* Terms of Service: [https://www.urvenue.com/legal/terms-conditions/](https://www.urvenue.com/legal/terms-conditions/)
* Privacy Policy: [https://www.urvenue.com/privacy-policy/](https://www.urvenue.com/privacy-policy/)



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
* **jQuery Validation Plugin** v1.21.5 - [Source Code](https://github.com/jquery-validation/jquery-validation/tree/1.21.0) - MIT License
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
