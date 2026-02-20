# Report UWS-7415 — WP Plugin Store Review Fixes

**Ticket:** UWS-7415
**Revisión:** WordPress Plugin Directory submission review
**Fecha inicio:** 2026-02-19
**Rama:** axl

---

## Clasificación de cambios

| Tipo | Descripción | Función usada |
|------|-------------|---------------|
| **A** | Variable en atributo HTML (class, value, data-*, name) | `esc_attr()` |
| **A-URL** | URL en atributo action/href/src | `esc_url()` |
| **A-TEXT** | Texto plano entre etiquetas HTML | `esc_html()` |
| **B** | HTML generado por `uvs_get_adminfieldhtml()` (inputs, selects, switches) | `wp_kses( $var, uvs_allowed_admin_html() )` |
| **C** | Bloque HTML grande generado por función de frontend | `wp_kses_post( $var )` |

---

## Fase 1 — Variables y opciones sin escapar al hacer echo (211 incidencias)

**Referencia oficial:** https://developer.wordpress.org/apis/security/escaping/
**Estado:** ✅ Completado

---

### uvcore/system/uvs-admin-functions.php

**Impacto:** Crítico — arregla ~100+ incidencias en cascada en los 18 archivos admin que consumen estas funciones.

| Cambio | Tipo | Detalle |
|--------|------|---------|
| Nueva función `uvs_allowed_admin_html()` | — | Helper que define los tags HTML permitidos para `wp_kses()` en formularios admin (input, select, option, div, button, span) |
| `uvs_get_adminfieldhtml()` — valores interpolados | **A** | `esc_attr()` aplicado a: `$uvsinputvalue`, `$uvsinputname`, `$uvsinputaddclass`, `$uvsinputtype`, `$uvsinputidattr` antes de interpolar en strings HTML |
| `uvs_get_fieldvalueshtml()` — opciones de select | **A** | `esc_attr()` en `$uvsslvalueval` (atributo value), `esc_html()` en `$uvsslvaluelabel` (texto) |
| `uvs_get_wppages()` — opciones de páginas WP | **A / A-TEXT** | `esc_attr()` en `$uvpageid`, `esc_html()` en títulos de página |
| `uvs_uverror()` — echo directo | **A-TEXT** | `esc_html( $uvserror )` |

---

### uvcore/system/admin/admin-box.php

| Línea | Variable | Tipo | Fix |
|-------|----------|------|-----|
| 9 | `$uvsboxpanelclass` — atributo class | **A** | `esc_attr()` |
| 10 | `$uvs_admin_lib["loads"]["adminsave"]` — action de form | **A-URL** | `esc_url()` |
| 13 | `$uws_core_version` — texto en div | **A-TEXT** | `esc_html()` |
| 23–47 | `$uvs_admin_optstabs_state['key']` — atributos class del menú nav (13 ocurrencias) | **A** | `esc_attr()` |
| 52 | `$uvs_core_lib["system"]["path"]` — value de input hidden | **A** | `esc_attr()` |
| 53 | `$uvs_url` — value de input hidden | **A** | `esc_attr()` |
| 54 | `$uvs_core_lib["system"]["library"]` — value de input hidden | **A** | `esc_attr()` |

---

### uvcore/system/admin/admin-cache.php

| Línea | Variable | Tipo | Fix |
|-------|----------|------|-----|
| 12 | `$uvs_admin_optstabs_state['cache']` — class | **A** | `esc_attr()` |
| 33, 36 | `$uvcacheendpoint` — data-endpoint | **A** | `esc_attr()` |
| 43 | `$uvsinpendpoint` — HTML de campo form | **B** | `wp_kses( $var, uvs_allowed_admin_html() )` |
| 56 | `$uvswpeinstid` — HTML de campo form | **B** | `wp_kses( $var, uvs_allowed_admin_html() )` |
| 63 | `$uvsinpwpeusername` — HTML de campo form | **B** | `wp_kses( $var, uvs_allowed_admin_html() )` |
| 70 | `$uvsinpwpepassword` — HTML de campo form | **B** | `wp_kses( $var, uvs_allowed_admin_html() )` |
| 84 | `$uvsinpapikey` — HTML de campo form | **B** | `wp_kses( $var, uvs_allowed_admin_html() )` |

---

### uvcore/system/admin/admin-map.php

| Tipo | Cant. | Fix |
|------|-------|-----|
| **A** | 1 | `esc_attr()` en `$uvs_admin_optstabs_state['map']` |
| **B** | 3 | `wp_kses()` en campos HTML de `uvs_get_adminfieldhtml()` |

---

### uvcore/system/admin/admin-flyers.php

| Tipo | Cant. | Fix |
|------|-------|-----|
| **A** | 1 | `esc_attr()` en `$uvs_admin_optstabs_state['flyers']` |
| **B** | 41 | `wp_kses()` en campos HTML de `uvs_get_adminfieldhtml()` y bloques de flyer HTML |

---

### uvcore/system/admin/admin-ui-color-palette.php

| Tipo | Cant. | Fix |
|------|-------|-----|
| **A** | 1 | `esc_attr()` en `$uvs_admin_optstabs_state['ui-color-palette']` |
| **B** | 4 | `wp_kses()` en campos HTML de `uvs_get_adminfieldhtml()` |

---

### uvcore/system/admin/admin-events-global.php

| Tipo | Cant. | Fix |
|------|-------|-----|
| **A** | 1 | `esc_attr()` en `$uvs_admin_optstabs_state['events-global']` |
| **B** | 13 | `wp_kses()` en campos HTML de `uvs_get_adminfieldhtml()` |

---

### uvcore/system/admin/admin-events-calendar.php

| Tipo | Cant. | Fix |
|------|-------|-----|
| **A** | 3 | `esc_attr()` en class y data attributes |
| **B** | 1 | `wp_kses()` en campo HTML de `uvs_get_adminfieldhtml()` |

---

### uvcore/system/admin/admin-events-agenda.php

| Tipo | Cant. | Fix |
|------|-------|-----|
| **A** | 1 | `esc_attr()` en `$uvs_admin_optstabs_state['events-agenda']` |
| **B** | 1 | `wp_kses()` en campo HTML de `uvs_get_adminfieldhtml()` |

---

### uvcore/system/admin/admin-events-slider.php

| Tipo | Cant. | Fix |
|------|-------|-----|
| **A** | 1 | `esc_attr()` en `$uvs_admin_optstabs_state['events-slider']` |
| **B** | 4 | `wp_kses()` en campos HTML de `uvs_get_adminfieldhtml()` |

---

### uvcore/system/admin/admin-events-event.php

| Tipo | Cant. | Fix |
|------|-------|-----|
| **A** | 1 | `esc_attr()` en `$uvs_admin_optstabs_state['events-event']` |
| **B** | 6 | `wp_kses()` en campos HTML de `uvs_get_adminfieldhtml()` |

---

### uvcore/system/admin/admin-pages.php

| Tipo | Cant. | Fix |
|------|-------|-----|
| **A** | 1 | `esc_attr()` en `$uvs_admin_optstabs_state['pages']` |
| **B** | 10 | `wp_kses()` en campos HTML y textos con tags `<small>/<strong>` |

---

### uvcore/system/admin/admin-venues.php

| Tipo | Cant. | Fix |
|------|-------|-----|
| **A** | 1 | `esc_attr()` en `$uvs_admin_optstabs_state['venues']` |
| **A-URL** | 1 | `esc_url()` en data-checkurl |
| **B** | 1 | `wp_kses()` en `$uvsvenueslisthtml` |

---

### uvcore/system/admin/admin-inventory.php

| Tipo | Cant. | Fix |
|------|-------|-----|
| **A** | 1 | `esc_attr()` en `$uvs_admin_optstabs_state['inventory']` |
| **B** | 2 | `wp_kses()` en campos HTML de `uvs_get_adminfieldhtml()` |

---

### uvcore/system/admin/admin-notifications.php

| Tipo | Cant. | Fix |
|------|-------|-----|
| **A** | 1 | `esc_attr()` en `$uvs_admin_optstabs_state['notifications']` |
| **B** | 3 | `wp_kses()` en campos HTML de `uvs_get_adminfieldhtml()` |

---

### uvcore/system/admin/admin-seo.php

| Tipo | Cant. | Fix |
|------|-------|-----|
| **A** | 1 | `esc_attr()` en `$uvs_admin_optstabs_state['seo']` |
| **B** | 5 | `wp_kses()` en campos HTML de `uvs_get_adminfieldhtml()` |

---

### uvcore/system/admin/admin-status.php

| Tipo | Cant. | Fix |
|------|-------|-----|
| **A** | 1 | `esc_attr()` en `$uvs_admin_optstabs_state['status']` |
| **A-TEXT** | 4 | `esc_html()` en `$uws_core_version`, `$uvs_url`, `$uvcorelayer`, `$uvsphpversion` |
| **B** | 2 | `wp_kses()` en `$uvsfeedsiswrhtml`, `$uvslibiswrhtml` |

---

### uvcore/system/admin/admin-dashboard.php

| Tipo | Cant. | Fix |
|------|-------|-----|
| **A** | 1 | `esc_attr()` en `$uvs_admin_optstabs_state['dashboard']` |

---

### uvcore/system/admin/admin-apiconfig.php

| Tipo | Cant. | Fix |
|------|-------|-----|
| **A-URL** | 1 | `esc_url()` en data-checkapiconfig |

---

### uvcore/system/admin/admin-api.php

| Tipo | Cant. | Fix |
|------|-------|-----|
| **A** | 1 | `esc_attr()` en `$uvs_admin_optstabs_state['api']` |
| **B** | 5 | `wp_kses()` en campos HTML de `uvs_get_adminfieldhtml()` |

---

### uvcore/includes/experiences-functions.php

| Línea | Variable | Tipo | Fix |
|-------|----------|------|-----|
| 73 | `$uvexperienceshtml` | **C** | `wp_kses_post()` |
| 110 | `$uvexperienceshtml` | **C** | `wp_kses_post()` |

---

### uvcore/includes/reservations-functions.php

| Línea | Variable | Tipo | Fix |
|-------|----------|------|-----|
| 159–163 | `$uwsinqformhtml` + divs hardcoded | **C** | `wp_kses_post()` en todas las líneas echo del bloque |

---

### uvcore/includes/calendar-functions.php

| Línea | Variable | Tipo | Fix |
|-------|----------|------|-----|
| 21 | `$uveventshtml` | **C** | `wp_kses_post()` |

---

### uvcore/includes/events-functions.php

| Línea | Variable | Tipo | Fix |
|-------|----------|------|-----|
| 60 | `$uveventshtml` | **C** | `wp_kses_post()` |
| 2070 | `$uveventhtml` | **C** | `wp_kses_post()` |

---

### uvcore/includes/packages-functions.php

| Línea | Variable | Tipo | Fix |
|-------|----------|------|-----|
| 9 | `$uvpackageshtml` | **C** | `wp_kses_post()` |

---

### uvcore/includes/map-functions.php

| Línea | Variable | Tipo | Fix |
|-------|----------|------|-----|
| 18 | `$uvmaphtml` | **C** | `wp_kses_post()` |

---

### uvcore/includes/itinerary-functions.php

| Línea | Variable | Tipo | Fix |
|-------|----------|------|-----|
| 42 | `$uvitinerary` | **C** | `wp_kses_post()` |

---

### uvcore/includes/inventory-functions.php

| Línea | Variable | Tipo | Fix |
|-------|----------|------|-----|
| 2344 | `$uvitemhaderhtml` | **C** | `wp_kses_post()` |
| 2418 | `$uvinvitempagehtml` | **C** | `wp_kses_post()` |
| 3995 | `$uvbookingcalendar` | **C** | `wp_kses_post()` |
| 4030 | `$uvglobaltypewidget` | **C** | `wp_kses_post()` |

---

### uvcore/setup.php

| Línea | Variable | Tipo | Fix |
|-------|----------|------|-----|
| 194 | `$uvpathclass` — class attr | **A** | `esc_attr()` |
| 196, 204 | `$uvs_uvcorepath` — value attr | **A** | `esc_attr()` |
| 200 | `$uvs_uvcoreurl` — value attr | **A** | `esc_attr()` |
| 202 | `$uvlibraryclass` — class attr | **A** | `esc_attr()` |
| 209 | `$uverrorshtml` — HTML block | **C** | `wp_kses_post()` |
| 213 | `$uvmanualwritehtml` — HTML block | **C** | `wp_kses_post()` |
| 234 | `$uvurlscript` — HTML block | **C** | `wp_kses_post()` |

---

### uvwp/admin/admin-page.php

| Línea | Variable | Tipo | Fix |
|-------|----------|------|-----|
| 35 | `$uvs_url` — src de imagen | **A-URL** | `esc_url()` |

---

## Totales Fase 1

| Tipo | Total cambios |
|------|--------------|
| A (esc_attr) | ~90 |
| A-URL (esc_url) | ~5 |
| A-TEXT (esc_html) | ~10 |
| B (wp_kses admin) | ~110 |
| C (wp_kses_post frontend) | ~20 |
| **Total** | **~235** |

---

---

## Fase 2 — `json_encode` → `wp_json_encode` (43 incidencias)

**Referencia oficial:** https://developer.wordpress.org/apis/security/escaping/
**Estado:** ✅ Completado

`wp_json_encode()` es el reemplazo de WordPress para `json_encode()`. Maneja errores de codificación de forma más segura y es el estándar requerido por el plugin directory.

### Clasificación de casos

| Sub-tipo | Descripción | Cant. |
|----------|-------------|-------|
| **D** | Asignación simple: `$var = json_encode($data)` | 38 |
| **D-ECHO** | Echo directo: `echo json_encode($data)` | 2 |
| **D-INLINE** | Inline en concatenación de string (script tag) | 1 |
| **D-FLAGS** | Con flags válidos (`JSON_UNESCAPED_SLASHES` etc.) | 1 |
| **D-BUG** | Con flag incorrecto `true` (bug eliminado) | 1 |

### Archivos modificados

#### uvcore/setup.php
| Línea | Detalle | Sub-tipo |
|-------|---------|----------|
| 37 | `$uvs_lib = json_encode($uvs_lib)` — guardar config en disco | **D** |
| 101 | `$uvs_lib = json_encode($uvs_lib)` — init WordPress | **D** |

#### uvwp/admin/admin-page.php
| Línea | Detalle | Sub-tipo |
|-------|---------|----------|
| 57 | `echo "..." . json_encode($uvs_core_lib) . "..."` — inline en script tag | **D-INLINE** |

#### uvcore/includes/uvcore-cleancache.php
| Línea | Detalle | Sub-tipo |
|-------|---------|----------|
| 52 | `$fields = json_encode(array(...))` — body de request HTTP | **D** |
| 144 | `$uvdata = json_encode($uvresponsemsg)` — respuesta API | **D** |
| 186 | `echo(json_encode($uvresponsemsg))` — echo directo | **D-ECHO** |

#### uvcore/includes/uvcore-notifications.php
| Línea | Detalle | Sub-tipo |
|-------|---------|----------|
| 116 | `json_encode($uvnoticedetails, JSON_UNESCAPED_SLASHES\|JSON_UNESCAPED_UNICODE\|JSON_PRETTY_PRINT)` — flags preservados | **D-FLAGS** |
| 143 | `'body' => json_encode($uvpayload)` — body de request HTTP | **D** |
| 135 | *(ya estaba comentado — no se tocó)* | — |

#### uvcore/includes/events-functions.php
| Línea | Detalle | Sub-tipo |
|-------|---------|----------|
| 212 | `$uveventsschemajson = json_encode($uveventsschema)` — schema markup | **D** |
| 2022 | `$uvgeteventschemajson = json_encode($uvgeteventschema)` — schema markup | **D** |

#### uvcore/includes/security-functions.php
| Línea | Detalle | Sub-tipo |
|-------|---------|----------|
| 107 | `is_array($data) ? json_encode($data) : $bodyRaw` — procesamiento interno | **D** |

#### uvcore/includes/inventory-functions.php
| Línea | Detalle | Sub-tipo |
|-------|---------|----------|
| 3334 | `json_encode($uvbk4timeslot, true)` → `wp_json_encode($uvbk4timeslot)` — se eliminó el `true` (bug: se casteaba a `JSON_HEX_TAG = 1`, no era intencional) | **D-BUG** |

#### uvcore/includes/uvcore-functions.php
| Línea | Detalle | Sub-tipo |
|-------|---------|----------|
| 426 | `$uvproxiesjson = json_encode($uvproxies)` | **D** |

#### uvcore/includes/uvcore-feeds.php
| Línea | Detalle | Sub-tipo |
|-------|---------|----------|
| 180 | `$uvfeedsinfofilejson = json_encode($uvfeedsinfofilearray)` | **D** |

#### uvcore/loads/ — 29 archivos
Todos con patrón `$uvreturnjson = json_encode($uvreturn)`:

| Archivo | Línea |
|---------|-------|
| `cart-drop.php` | 17 |
| `uws-events-load.php` | 41 |
| `inventory-item-inquireform-pro.php` | 48 |
| `uws-inventory-globaltype.php` | 97 |
| `uws-inventoryitem-pop.php` | 57 |
| `uws-inventory-addonvenues.php` | 35 |
| `closeddates-load.php` | 21 |
| `inquiry-send.php` | 52 |
| `uvs-checkapiconfig-load.php` | 114 |
| `itinerary-init.php` | 12 |
| `mastercode-by-masteritemcode.php` | 22 |
| `inquiry-getleadtypes.php` | 21 |
| `inventory-item-getbottles.php` | 19 |
| `inventory-item-getinfo.php` | 12 |
| `inventory-item-getottimes.php` | 22 |
| `inventory-item-gettimes.php` | 64 |
| `map-load.php` | 91 |
| `experiences-load.php` | 19 |
| `noinventorydates-load.php` | 25 |
| `cartv2/cart-deleteitem.php` | 59 |
| `inventory-item-getbk4times.php` | 22 |
| `dynamicevents-load.php` | 27 |
| `cartv1/cart-deleteitem.php` | 29 |
| `uvs-adminsave-pro.php` | 22 |
| `inventory-getcartbreakdown.php` | 130 |
| `uws-eventsdp-load.php` | 80 |
| `uws-inventory-init.php` | 52 |
| `inventory-item-inquireform.php` | 96 |
| `cartv1/cart-additem.php` | 211 |
| `cartv2/cart-additem.php` | 143 |

### Totales Fase 2

| Sub-tipo | Total |
|----------|-------|
| D (asignación simple) | 38 |
| D-ECHO (echo directo) | 2 |
| D-INLINE (script tag) | 1 |
| D-FLAGS (con flags) | 1 |
| D-BUG (flag incorrecto eliminado) | 1 |
| **Total** | **43** |

---

---

## Fase 3 — HTML escaping heurístico (verificación + casos faltantes)

**Estado:** ✅ Completado

### Verificación de casos citados por el revisor
Los 4 ejemplos citados en el ticket ya habían sido cubiertos en la Fase 1:

| Archivo | Línea | Estado |
|---------|-------|--------|
| `uvcore/includes/experiences-functions.php:73` | `echo $uvexperienceshtml` | ✅ Cubierto en Fase 1 con `wp_kses_post()` |
| `uvcore/includes/reservations-functions.php:154` | `echo $uwsinqformhtml` | ✅ Cubierto en Fase 1 con `wp_kses_post()` |
| `uvcore/system/admin/admin-flyers.php:179` | `echo $uvsslflyerlocdivhtml` | ✅ Cubierto en Fase 1 con `wp_kses()` |
| `uvcore/system/admin/admin-flyers.php:245` | `echo $uvssrflyerlocdivhtml` | ✅ Cubierto en Fase 1 con `wp_kses()` |

### Casos adicionales encontrados en la verificación

#### Grupo 1 — Admin files inactivos (código en repo, no incluido actualmente)

| Archivo | Línea | Variable | Fix |
|---------|-------|----------|-----|
| `admin-events-list.php` | 11 | `$uvs_admin_optstabs_state['events-list']` — class attr | `esc_attr()` |
| `admin-events-list.php` | 18 | `$uvseventslisttype` — HTML form field | `wp_kses( $var, uvs_allowed_admin_html() )` |
| `admin-events-list.php` | 24 | `$uvseventslistmaxevents` — HTML form field | `wp_kses( $var, uvs_allowed_admin_html() )` |
| `admin-artists-artistpage.php` | 7 | `$uvs_admin_optstabs_state['artists-artistpage']` — class attr | `esc_attr()` |
| `admin-artists-artistpage.php` | 14 | `$uvsartistspageurl` — HTML form field | `wp_kses( $var, uvs_allowed_admin_html() )` |
| `admin-artists-artistpage.php` | 21 | `$uvsartistsimagetype` — HTML form field | `wp_kses( $var, uvs_allowed_admin_html() )` |
| `admin-artists-artistpage.php` | 27 | `$uvsartistsimageratio` — HTML form field | `wp_kses( $var, uvs_allowed_admin_html() )` |
| `admin-artists-artistslist.php` | 11 | `$uvs_admin_optstabs_state['artists-list']` — class attr | `esc_attr()` |
| `admin-artists-artistslist.php` | 18 | `$uvsartistslistview` — HTML form field | `wp_kses( $var, uvs_allowed_admin_html() )` |
| `admin-artists-artistslist.php` | 24 | `$uvsartistsbuttonlabel` — HTML form field | `wp_kses( $var, uvs_allowed_admin_html() )` |

#### Grupo 2 — Frontend activo

| Archivo | Línea | Variable | Fix | Nota |
|---------|-------|----------|-----|------|
| `uvwp/includes/uvwp-shortcodes-experiences.php` | 27 | `$uvexperiencesfilter` — HTML date picker | `wp_kses_post()` | |
| `uvwp/includes/uvwp-options.php` | 114 | `$uvcssvars` dentro de `<style>` | `wp_strip_all_tags()` | CSS output — no existe función WP específica para CSS; `wp_strip_all_tags()` previene inyección HTML/script mientras preserva el CSS. CSS viene de config del plugin, no de input de usuario. |
| `uvwp/includes/uvwp-options.php` | 123 | `$uvfooterproxy` — script footer | `wp_kses( $var, array() )` | `uws_get_proxy_script()` retorna siempre `""` — el proxy ya se registra internamente vía `wp_add_inline_script()`. El echo era dead code. |

#### Grupo 3 — 🔴 XSS real con input de usuario (crítico)

| Archivo | Línea | Variable | Fix |
|---------|-------|----------|-----|
| `uvcore/loads/uvs-veaidinfo-load.php` | 64 | `$uvsve` en mensaje de error — **viene de `$_REQUEST["uvsve"]`** directamente | `esc_html( $uvsve )` |
| `uvcore/loads/uvs-veaidinfo-load.php` | 61 | `$uvsvenueinfoinfohtml` — HTML complejo con datos de API | `wp_kses( $var, uvs_allowed_admin_html() )` |

---

## Resumen general del ticket UWS-7415

| Fase | Incidencias reportadas | Incidencias reales | Estado |
|------|----------------------|-------------------|--------|
| Fase 1 — Escaping de variables | 211 | ~235 | ✅ Completado |
| Fase 2 — `json_encode` → `wp_json_encode` | 28 | 43 | ✅ Completado |
| Fase 3 — HTML escaping heurístico | 20 | 20 (4 ya en F1 + 16 adicionales) | ✅ Completado |
| **Total cambios aplicados** | | **~298** | ✅ |
