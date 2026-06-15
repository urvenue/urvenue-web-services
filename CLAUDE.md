# CLAUDE.md — UrVenue Web Services Plugin

Plugin WordPress que actúa como **bridge** entre el CMS y la plataforma UrVenue (UvTix API). No almacena datos de negocio localmente: su responsabilidad es configuración, caché de feeds, renderizado via shortcodes, y seguridad de la capa WP.

**Versión actual:** 1.2.6  
**PHP mínimo:** 7.4  
**Plugin root:** `wp-content/plugins/urvenue-web-services/`

---

## Estructura del repositorio

```
urvenue-web-services/           ← raíz del repo
├── CLAUDE.md
├── README.md
└── wp-content/plugins/urvenue-web-services/
    ├── urvenue-web-services.php   ← entry point
    ├── phpcs.xml                  ← PHPCS config (WordPress coding standards)
    ├── uvcore/                    ← framework core (agnóstico de WP)
    └── uvwp/                      ← capa de integración WordPress
```

---

## Árbol del plugin

```
urvenue-web-services.php          ← define globals, include los 3 inits
uvcore/
├── init-uvcore.php               ← carga urvenue_ws_core_lib desde wp_options o JSON
├── includes/                     ← módulos de funciones (uno por feature)
│   ├── uvcore-hooks.php          ← sistema de hooks custom
│   ├── uvcore-functions.php      ← utilidades globales, enqueue helpers
│   ├── uvcore-feeds.php          ← caché de feeds (get/set/invalidate)
│   ├── uvcore-cleancache.php     ← purga de caché
│   ├── uvcore-notifications.php  ← sistema de alertas/webhooks
│   ├── security-functions.php    ← WAF inline (77 patrones regex)
│   ├── events-functions.php
│   ├── calendar-functions.php
│   ├── inventory-functions.php
│   ├── map-functions.php
│   ├── venues-functions.php
│   ├── reservations-functions.php
│   ├── packages-functions.php
│   ├── itinerary-functions.php
│   ├── experiences-functions.php
│   └── lang-functions.php
├── libs/                         ← capa de servicios/configuración
│   ├── uv-feeds-lib.php          ← definición de 60+ endpoints API y TTLs
│   ├── uv-proxy-lib.php          ← mapeo uvaction → archivo handler
│   ├── uv-defaults-lib.php       ← valores por defecto de toda la config
│   ├── ui-lib.php                ← componentes UI y variables de tema
│   └── uvs-admin-lib.php         ← helpers del panel admin
├── loads/                        ← handlers AJAX (uno por acción del proxy)
│   ├── uws-events-load.php
│   ├── uws-eventsdp-load.php
│   ├── uws-inventory-init.php
│   ├── uws-inventory-globaltype.php
│   ├── uws-inventoryitem-pop.php
│   ├── uws-inventory-addonvenues.php
│   ├── inventory-item-getinfo.php
│   ├── inventory-item-gettimes.php
│   ├── inventory-item-getbk4times.php
│   ├── inventory-item-getottimes.php
│   ├── inventory-item-getbottles.php
│   ├── inventory-item-inquireform.php
│   ├── inventory-item-inquireform-pro.php
│   ├── inventory-getcartbreakdown.php
│   ├── inventory-cart-additem.php
│   ├── inventory-cart-deleteitem.php
│   ├── cartv1/cart-additem.php, cart-deleteitem.php
│   ├── cartv2/cart-additem.php, cart-deleteitem.php
│   ├── cart-drop.php
│   ├── map-load.php
│   ├── closeddates-load.php
│   ├── noinventorydates-load.php
│   ├── dynamicevents-load.php
│   ├── experiences-load.php
│   ├── itinerary-init.php
│   ├── inquiry-getleadtypes.php
│   ├── inquiry-send.php
│   ├── mastercode-by-masteritemcode.php
│   ├── uvs-adminsave-pro.php
│   ├── uvs-checkapiconfig-load.php
│   └── uvs-veaidinfo-load.php
├── system/
│   ├── uvs-admin-init.php        ← inicialización temprana del contexto admin
│   ├── uvs-admin-functions.php   ← helpers para admin
│   └── admin/                    ← 23 módulos de paneles admin
│       ├── admin-box.php         ← contenedor principal (carga tabs)
│       ├── admin-dashboard.php
│       ├── admin-api.php
│       ├── admin-apiconfig.php
│       ├── admin-events-global.php
│       ├── admin-events-calendar.php
│       ├── admin-events-agenda.php
│       ├── admin-events-slider.php
│       ├── admin-events-event.php
│       ├── admin-events-list.php
│       ├── admin-pages.php
│       ├── admin-venues.php
│       ├── admin-inventory.php
│       ├── admin-notifications.php
│       ├── admin-map.php
│       ├── admin-seo.php
│       ├── admin-cache.php
│       ├── admin-status.php
│       ├── admin-ui-color-palette.php
│       └── admin-flyers.php
└── assets/
    ├── css/  ← uwscore.css, events.css, uwsinventory.css, map.css, ...
    ├── js/   ← uwscore.js, events.js, uwsinventory.js, map.js, admin.js, ...
    ├── fonts/
    └── images/
uvwp/
├── includes/
│   ├── init-uvwp.php                    ← incluye los 3 archivos del módulo
│   ├── uvwp-options.php                 ← todos los hooks WP (enqueue, AJAX, rewrite, SEO)
│   ├── uvwp-functions.php               ← helpers específicos de WP
│   ├── uvwp-shortcodes.php              ← shortcodes principales
│   ├── uvwp-shortcodes-experiences.php
│   └── uvwp-shortcodes-guests.php
└── assets/css/                          ← estilos del panel admin WP
```

---

## Flujo de una solicitud AJAX

```
1. Browser JS
   POST /wp-admin/admin-ajax.php
   action=urvenue_ws_proxy & uvaction=<acción> & targetNonce=<nonce>

2. WordPress hook wp_ajax_urvenue_ws_proxy
   → uvwp/includes/uvwp-options.php :: urvenue_ws_proxy()

3. uvcore/uvcore.proxy.php (dispatcher)
   a. urvenue_ws_security_check_params_injection()  ← WAF inline
   b. Lee $_REQUEST["uvaction"] (sanitizado)
   c. Mapea a loads/<handler>.php via uv-proxy-lib.php

4. loads/<handler>.php
   a. urvenue_ws_check_nonce("<nonce_key>")  ← verifica nonce WP
   b. Sanitiza y extrae parámetros
   c. Llama función en includes/<módulo>-functions.php

5. uvcore-feeds.php
   Hit caché → devuelve JSON guardado
   Miss caché → llama UrVenue API → guarda → devuelve

6. UrVenue UvTix API
   https://{env}.urvenue.me/v1/{endpoint}?apikey=...&sourcecode=...&sourceloc=...
```

---

## Seguridad — capas

| Capa       | Dónde              | Qué hace |
|------------|--------------------|----------|
| WAF inline | `uvcore.proxy.php` | 77 regex contra SQLi, XSS, LFI en GET+POST+body |
| Nonce WP   | Cada `loads/*.php` | `urvenue_ws_check_nonce()` antes de procesar |
| Capability check | Todos los admin handlers | Requiere `manage_options` |
| Sanitización | Cada handler | `sanitize_text_field()`, `intval()`, etc. |

**Nonces registrados:**
- `urvenue_ws_events`
- `urvenue_ws_inventory`
- `urvenue_ws_map`
- `urvenue_ws_reservations`
- `urvenue_ws_experiences`
- `urvenue_ws_itinerary`
- `urvenue_ws_packages`
- `uvsp_adminsave_action` (admin save)
- `urvenue_ws_setup_action` (setup wizard)

---

## Convenciones de código

- **Prefijo universal:** `urvenue_ws_` en todas las funciones, hooks y variables globales públicas.
- Los nombres anteriores `uws_*` están deprecados; al modificar código existente, usar el prefijo nuevo.
- **Comentarios de tickets** en el código usan el formato `// Axl UWS-XXXX`.
- Estándar de código: WordPress Coding Standards (PHPCS, ver `phpcs.xml`).
- Sin namespaces PHP ni autoloader — carga procedural con `include_once`.

---

## Variables globales clave

```php
$urvenue_ws_corepath       // Ruta absoluta a uvcore/
$urvenue_ws_coreurl        // URL a uvcore/
$urvenue_ws_uvwp_path      // Ruta absoluta a uvwp/
$urvenue_ws_uvwp_url       // URL a uvwp/
$urvenue_ws_today          // Fecha actual (GMT-5h para evitar ocultar eventos al mediodía)
$urvenue_ws_core_lib       // Array maestro de configuración (cargado desde wp_options)
$urvenue_ws_url            // URL base de la API UrVenue
$urvenue_ws_feeds_debug    // Flag de debug (1 = activo)
$urvenue_ws_assetsversion  // Versión de assets para cache busting ("1.2.6")
```

---

## Configuración (`urvenue_ws_core_lib`)

Almacenada en `wp_options` como JSON bajo la clave `urvenue_ws_uvcore_lib`.  
Fallback a `uvcore/uvcore.lib.json` y luego a `uv-defaults-lib.php`.

```php
[
  "system"        => [ "apikey", "sourcecode", "sourceloc", "apiurl", "debug", ... ],
  "events"        => [ "global-updateurl", "eventspage-dateselector", ... ],
  "inventory"     => [ "namefields", ... ],
  "pages"         => [ "events", "singleevent", "map", "itempage", ... ],
  "ui"            => [ "uitheme", "accentcolor", "primarycolor", "secondarycolor", ... ],
  "seo"           => [ "enabletags", ... ],
  "eventpagesmap" => [ "{venuecode}" => [ "singleevent" => page_id, "map" => page_id, ... ] ]
]
```

---

## Módulos funcionales

| Módulo | Functions file | Admin panel | Shortcode principal |
|--------|----------------|-------------|---------------------|
| Events | `events-functions.php` | `admin-events-*.php` | `[urvenue_ws_events]` |
| Calendar | `calendar-functions.php` | `admin-events-calendar.php` | — |
| Inventory | `inventory-functions.php` | `admin-inventory.php` | `[urvenue_ws_inventory_item_page]` |
| Map | `map-functions.php` | `admin-map.php` | `[urvenue_ws_map]` |
| Reservations | `reservations-functions.php` | — | `[urvenue_ws_inquiry]` |
| Packages | `packages-functions.php` | — | `[urvenue_ws_packages]` |
| Itinerary | `itinerary-functions.php` | — | `[urvenue_ws_itinerary]` |
| Experiences | `experiences-functions.php` | — | `[urvenue_ws_experiences]` |
| Venues | `venues-functions.php` | `admin-venues.php` | — |
| Lang | `lang-functions.php` | — | — |

---

## Shortcodes disponibles

```
[urvenue_ws_events]                    venues, venuesinfilter, nevents, button_label, view, date, enddate
[urvenue_ws_events_list]               vista agenda/list/calendar
[urvenue_ws_event]                     página de evento individual
[urvenue_ws_inventory_item_header]     header de ítem con imagen
[urvenue_ws_inventory_item_page]       página completa de ítem con booking
[urvenue_ws_map]                       venuecode, eventcode, hide_venue_selection
[urvenue_ws_inventorywidget]           venueid, startdate, nextdays, ecozone, globaltype, displaybutton, onlyweekdays, mixecozones
[urvenue_ws_inquiry]                   venues, redirect_to, namefields, opendays
[urvenue_ws_packages]                  venuecode, fromdate, todate
[urvenue_ws_itinerary]                 itinerario de reservas del guest
[urvenue_ws_experiences]               catálogo de experiencias
```

---

## Caché de feeds

| TTL | Endpoints |
|-----|-----------|
| **60s** | `inventory`, `inventoryitem`, `inventorymap`, `mapinventory`, `cart-get`, `venueday` |
| **1h** | `eventvenues`, `availability`, `digital-menu`, `inquiry-leadtypes`, `ecozonedetails`, `marketevents`, `marketeventvenues`, `inventorylist-events` |
| **24h** | `inventorylist-venues`, `packagesinventory`, `packagesiteminfo` |
| **Sin caché** | `cart-create`, `cart-update`, `cart-delete`, `inquiry-send`, `cartv2-*`, `ot-itemtimes`, `bk4-itemtimes` |

**Purga:** botón en admin panel → `uvcore-cleancache.php`. Opcional: integración WP Engine API.

---

## Integraciones externas

| Servicio | Propósito | Mecanismo |
|----------|-----------|-----------|
| **UrVenue UvTix API** | Datos de eventos, inventario, cart | REST (apikey + sourcecode + sourceloc) |
| **Booketing** | Checkout/pago | Redirect con cart code |
| **SevenRooms** | Reservaciones | Iframe embed |
| **OpenTable** | Reservaciones gastronómicas | Iframe embed |
| **WP Engine API** | Purga de caché | REST API opcional (admin-habilitado) |
| **Yoast SEO** | Meta tags, sitemap | Filtros `wpseo_*` |
| **Rank Math** | Meta tags | Filtros `rank_math/*` |
| **Google Analytics 4** | Tracking de eventos | `hooks-ga4dl.js` |

**URL base API:**
```
https://{env}.urvenue.me/v1/{endpoint}
```
Entornos: `api` (prod), `apistaging`, `apiuat`

---

## Frontend (assets)

**JS principales:**
- `uwscore.js` — AJAX proxy wrapper, event dispatcher, caché cliente, utilidades UI
- `uwsinventory.js` — selección de ítems, gestión de carrito
- `events.js` — interacciones de calendario, filtros de fecha
- `map.js` — mapa interactivo con zoom/pan
- `admin.js` — panel admin
- `hooks-ga4dl.js` — GA4 data layer

**Librerías third-party incluidas:**
- jQuery Validate, Flatpickr, Litepicker, NoUISlider, Hammer.js, Perfect Scrollbar, Pristine

**CSS theming:**
Variables CSS custom properties inyectadas en `<head>` via `wp_head`:
```css
--uws-main-color, --uws-primary-color, --uws-secondary-color,
--uws-subtle-color, --uws-accentcolorcust, --uws-accentcoloropac, ...
```
Configurables desde Admin → UI Color Palette.

---

## Rewrite rules (URLs amigables)

```
/{events-page}/eve-{eventcode}/      ← página de evento
/{map-page}/eve-{eventcode}/         ← mapa con evento seleccionado
/{item-page}/mc{mastercode}/         ← página de ítem de inventario
```
Query vars registradas: `eventcode`, `mastercode`

---

## Agregar una nueva acción AJAX (proxy)

1. Crear handler en `uvcore/loads/mi-accion.php`
   - Verificar nonce: `urvenue_ws_check_nonce("urvenue_ws_<modulo>")`
   - Sanitizar parámetros, llamar función de `includes/`
2. Registrar el mapeo en `uvcore/libs/uv-proxy-lib.php`
   - Agregar: `"uwspx_mi_accion" => "mi-accion.php"`
3. En JS, llamar via el proxy de `uwscore.js` con `uvaction: "uwspx_mi_accion"`

---

## Agregar un nuevo endpoint API

1. Definir en `uvcore/libs/uv-feeds-lib.php`
   - Agregar entrada al array de endpoints con URL, params y TTL de caché
2. Usar `urvenue_ws_get_feed("<endpoint_key>", $params)` desde el handler

---

## Base de datos

**Sin tablas custom.** Todo se almacena en `wp_options`:

| Option key | Contenido |
|------------|-----------|
| `urvenue_ws_uvcore_lib` | JSON con toda la configuración del plugin |
| `urvenue_ws_cacheword` | Token de purga de caché |
| `urvenue_ws_flush_pending` | Flag para flush de rewrite rules |
| `urvenue_ws_feeds_debug` | Flag de modo debug |

---

## Debug

Activar desde Admin → System Status → Debug Mode, o definir la constante:
```php
define('URVENUE_WS_DEBUG', true);
```
Con debug activo, `$urvenue_ws_feeds_debug = 1` fuerza bypass del caché y loguea respuestas de API.
