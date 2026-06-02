var uws_dp_date, uws_event_dp;
var uws_calcell_pop;
window.uws_event = window.uws_event || {};
window.uws_event_dp_cache = window.uws_event_dp_cache || {}
window.uws_events = window.uws_events || {};
window.uws_events_dp_cache = window.uws_events_dp_cache || {}

var uws_data_filter_performer = "";
var uws_data_filter_location = "";

uwsDOMReady(function () {
    uwsInitEvents();
});

//Events menu filter
uwsClickListener(".uwsjs-events-changeview", function (e) {
    e.preventDefault();

    if (!this.classList.contains("uvsactive")) {
        const uvmenusibs = this.closest(".uwsviews").querySelectorAll("li a");
        const uvtheview = this.getAttribute("data-view");
        const uvthiselem = this;
        let uvactivehtml = "";

        Array.prototype.forEach.call(uvmenusibs, function (el, i) {
            if (el.getAttribute("data-view") && uvtheview == el.getAttribute("data-view")) {
                el.classList.add("uvsactive");
                el.closest("li").classList.add("uwscurrent");

                if (uvthiselem.closest(".uws-events").querySelector(".uws-events-view-" + el.getAttribute("data-view"))) {
                    uvthiselem.closest(".uws-events").querySelector(".uws-events-view-" + el.getAttribute("data-view")).classList.add("uvsactive");

                    uvactivehtml = el.innerHTML;
                }
            }
            else {
                el.classList.remove("uvsactive");
                el.closest("li").classList.remove("uwscurrent");

                if (uvthiselem.closest(".uws-events").querySelector(".uws-events-view-" + el.getAttribute("data-view")))
                    uvthiselem.closest(".uws-events").querySelector(".uws-events-view-" + el.getAttribute("data-view")).classList.remove("uvsactive");
            }
        });

        if (uvactivehtml)
            uvthiselem.closest(".uwsviews").querySelector(".uws-dropdown-cont .uwsdy-dropvalue").innerHTML = uvactivehtml;
    }
});

//Select month from dropdown
uwsClickListener(".uwsjs-events-selectmonth", function (e) {
    e.preventDefault();

    const uvdate = this.getAttribute("data-date");
    document.querySelector(".uws-events").setAttribute("data-filter-date", uvdate);

    uwsEventsUpdateURL("date", uvdate);
    uwsEventsFilter();
});

//Select venue from dropdown
uwsClickListener(".uwsjs-events-selectvenue", function (e) {
    e.preventDefault();

    uwsCleanPerformersFilter();

    let uvvenue = this.getAttribute("data-venue");
    document.querySelector(".uws-events").setAttribute("data-filter-venue", uvvenue);

    const uvallvenueskey = this.closest(".uws-events").querySelector(".uwseventsvenueselall").getAttribute("data-venue");
    if (uvallvenueskey == uvvenue)
        uvvenue = "";

    uwsEventsUpdateURL("venue", uvvenue);
    uwsEventsFilter();
});

//Select performer from dropdown
uwsClickListener(".uwsjs-events-selectperformer", function (e) {
    e.preventDefault();

    let uvperformercode = this.getAttribute("data-performercode");

    const uvallartistskey = this.closest(".uws-events").querySelector(".uwseventsartistsselall").getAttribute("data-performercode");
    if (uvallartistskey == uvperformercode)
        uvperformercode = "";

    uwsEventsUpdateURL("performer", uvperformercode);
    uwsEventsFilterPerformer(uvperformercode);
});

// Date picker filter
uwsClickListener(".uws-disableddates #uwsfilterdate", function (e) {
    e.preventDefault();

    const uvdpcontainer = this.closest(".uwshasdrop");
    const uvdp = this;

    if (this.closest(".uwshasdrop").classList.contains("uwsactive")) {
        const uvdpfromdate = uvdp.getAttribute("data-fromdate");
        const uvdptodate = uvdp.getAttribute("data-todate");
        const uvdpvenuecode = uvdp.getAttribute("data-venuecode");

        const uvcachekey = `${uvdpfromdate}_${uvdptodate}`;
        if (window.uws_events_dp_cache && window.uws_events_dp_cache[uvcachekey]) {
            uwsLoadDPEventsCache(uvcachekey, uws_dp_date);
        } else {
            uwsLoadDPEvents(uvdpcontainer, uvdpfromdate, uvdptodate, uvdpvenuecode, uws_dp_date);
        }
    }
});

//Filter range click
uwsClickListener("#uwsfilterrange", function () {
    const uvdpmonths = (window.innerWidth > 800) ? 2 : 1;
    uws_dp_date.setOptions({ numberOfColumns: uvdpmonths, numberOfMonths: uvdpmonths });
});

//Next on month arrows select
uwsClickListener(".uwsjs-events-nextmonth:not(.uwsdisabled)", function (e) {
    e.preventDefault();

    const uvcurdate = this.closest(".uwsmonthsstepsbtns").getAttribute("data-currentdate");
    const uvmonthsstring = this.closest(".uwsmonthsstepsbtns").getAttribute("data-months");
    const uvmonthsstringarr = uvmonthsstring.split(",");
    let uvcurkey = uvmonthsstringarr.indexOf(uvcurdate);

    if (uvcurkey < uvmonthsstringarr.length) {
        uvcurkey = uvcurkey + 1;
        const uvnextcurdate = uvmonthsstringarr[uvcurkey];

        let uvobjdate = uvnextcurdate.replace(/-/g, '/');
        uvobjdate = new Date(uvobjdate);
        const uvnextcurddate = uws_fullmonths[uvobjdate.getMonth()] + " " + uvobjdate.getFullYear();

        this.closest(".uwsmonthsstepsbtns").setAttribute("data-currentdate", uvnextcurdate);
        this.closest(".uws-events").setAttribute("data-filter-date", uvnextcurdate);
        uwsEventsUpdateURL("date", uvnextcurdate);
        this.closest(".uwsmonthssteps").querySelector(".uwsdy-eventsmonth").innerHTML = uvnextcurddate;
    }

    if (uvcurkey >= uvmonthsstringarr.length - 1)
        this.classList.add("uwsdisabled");

    this.closest(".uwsmonthsstepsbtns").querySelector(".uwsjs-events-prevmonth").classList.remove("uwsdisabled");
    uwsEventsFilter();
});

//Prev on month arrows select
uwsClickListener(".uwsjs-events-prevmonth:not(.uwsdisabled)", function (e) {
    e.preventDefault();

    const uvcurdate = this.closest(".uwsmonthsstepsbtns").getAttribute("data-currentdate");
    const uvmonthsstring = this.closest(".uwsmonthsstepsbtns").getAttribute("data-months");
    const uvmonthsstringarr = uvmonthsstring.split(",");
    let uvcurkey = uvmonthsstringarr.indexOf(uvcurdate);

    if (uvcurkey > 0) {
        uvcurkey = uvcurkey - 1;
        const uvnextcurdate = uvmonthsstringarr[uvcurkey];

        let uvobjdate = uvnextcurdate.replace(/-/g, '/');
        uvobjdate = new Date(uvobjdate);
        const uvnextcurddate = uws_fullmonths[uvobjdate.getMonth()] + " " + uvobjdate.getFullYear();

        this.closest(".uwsmonthsstepsbtns").setAttribute("data-currentdate", uvnextcurdate);
        this.closest(".uws-events").setAttribute("data-filter-date", uvnextcurdate);
        uwsEventsUpdateURL("date", uvnextcurdate);
        this.closest(".uwsmonthssteps").querySelector(".uwsdy-eventsmonth").innerHTML = uvnextcurddate;
    }

    if (uvcurkey < 1)
        this.classList.add("uwsdisabled");

    this.closest(".uwsmonthsstepsbtns").querySelector(".uwsjs-events-nextmonth").classList.remove("uwsdisabled");
    uwsEventsFilter();
});

//Load more events
uwsClickListener(".uwsjs-events-loadmore", function (e) {
    e.preventDefault();

    uwsEventsLoadMore();
});

//Init Events
function uwsInitEvents() {
    uws_calcell_pop = uwsCreatePop("uws-calcell-pop");

    if (document.querySelector("#uwsfilterdate")) {//if filter is datepicker
        const uvmindate = document.querySelector("#uwsfilterdate").getAttribute("data-date");
        const uvmaxdate = document.querySelector(".uws-events").getAttribute("data-filter-maxdate");
        const uvdpcontainer = document.querySelector(".uws-events-dpinput");
        const uvadddisableddates = (uvdpcontainer.classList.contains("uws-disableddates")) ? 1 : 0;

        uws_dp_date = new Litepicker({
            element: document.querySelector(".uws-dp-filterdate"),
            minDate: uvmindate,
            maxDate: uvmaxdate,
            inlineMode: 1,
            singleMode: 1,
            showTooltip: 0,
            firstDay: 0,
            startDate: uvmindate,
            setup: function (n) {
                n.on("selected", function (n, t) {
                    const uvseldate = n.format('YYYY-MM-DD');
                    const uvddate = uws_dp_abdates[n.getMonth()] + " " + n.getDate() + ", " + n.getFullYear();
                    this.ui.closest(".uwshasdrop").classList.remove("uwsactive");
                    document.querySelector(".uws-events").setAttribute("data-filter-date", uvseldate);
                    document.querySelector("#uwsfilterdate").innerHTML = uvddate;

                    uwsEventsUpdateURL("date", uvseldate);
                    uwsEventsFilter();
                })
            }
        });

        if (uvadddisableddates && uvdpcontainer) {
            const uvvenuecode = uvdpcontainer.querySelector("#uwsfilterdate").getAttribute("data-venuecode");
            uws_dp_date.on('render:month', (month, date) => {
                const uvseldate = date.format('YYYY-MM-DD');
                uws_events.dpcurrentmont = uvseldate;
            });
            uws_dp_date.on('change:month', (date, calendarIdx) => {
                const uvseldate = date.format('YYYY-MM-DD');

                let uvenddate = new Date(date.getFullYear(), date.getMonth() + 1, 0);
                uvenddate = uvenddate.getFullYear() + '-' +
                    String(uvenddate.getMonth() + 1).padStart(2, '0') + '-' +
                    String(uvenddate.getDate()).padStart(2, '0');

                const uveventdpfilter = uvdpcontainer.querySelector("#uwsfilterdate");
                uveventdpfilter.setAttribute("data-date", uvseldate);
                uveventdpfilter.setAttribute("data-fromdate", uvseldate);
                uveventdpfilter.setAttribute("data-todate", uvenddate);

                const uvcachekey = `${uvseldate}_${uvenddate}`;

                if (window.uws_events_dp_cache && window.uws_events_dp_cache[uvcachekey]) {
                    uwsLoadDPEventsCache(uvcachekey, uws_dp_date);
                } else {
                    uwsLoadDPEvents(uvdpcontainer, uvseldate, uvenddate, uvvenuecode, uws_dp_date);
                }
            });
            uws_dp_date.on('render:day', (dayElement, date) => {
                const uvdateformatted = date.format('YYYY-MM-DD');

                // Disable days without events
                if (!uws_events.events || !uws_events.events[uvdateformatted]) {
                    dayElement.classList.add('uws-day-disabled', 'is-locked');
                    dayElement.setAttribute('aria-disabled', 'true');
                    dayElement.tabIndex = -1;
                }
            });
        }
    }

    if (document.querySelector("#uwseventfilterdate")) { // event datepicker
        const uveventdpelem = document.querySelector(".uws-dp-eventfilterdate");
        const uvdpcontainer = uveventdpelem.closest(".uwshasdrop");
        const uveventdpfilter = document.querySelector("#uwseventfilterdate");
        let uvmindate = uveventdpfilter.getAttribute("data-mindate");
        // If the min date has already passed, use today
        if (uvmindate && new Date(uvmindate) < new Date(new Date().toISOString().slice(0, 10))) {
            uvmindate = new Date().toISOString().slice(0, 10);
        }
        const uvinitdate = uveventdpfilter.getAttribute("data-date");
        const uvmaxdate = uveventdpfilter.getAttribute("data-maxdate");
        const uvmaxfilterdate = uveventdpfilter.getAttribute("date-filter-maxdate");
        const uvvenuecode = uveventdpfilter.getAttribute("data-venuecode");
        const uvdplang = "en";

        uws_event_dp = new Litepicker({
            element: uveventdpelem,
            minDate: uvmindate,
            maxDate: uvmaxdate,
            inlineMode: 1,
            singleMode: 1,
            showTooltip: 0,
            firstDay: 0,
            startDate: uvinitdate,
            lang: uvdplang,
            setup: function (n) {
                n.on("selected", function (n, t) {
                    const uvseldate = n.format('YYYY-MM-DD');
                    const uvddate = n.toLocaleString(uvdplang, { month: 'short' }) + " " + n.getDate() + ", " + n.getFullYear();

                    this.ui.closest(".uwshasdrop").classList.remove("uwsactive");
                    uveventdpfilter.setAttribute("data-date", uvseldate);
                }),
                    n.on('render:month', (month, date) => {
                        const uvseldate = date.format('YYYY-MM-DD');
                        uws_event.dpcurrentmont = uvseldate;
                    }),
                    n.on('change:month', (date, calendarIdx) => {
                        const uvseldate = date.format('YYYY-MM-DD');

                        let uvenddate = new Date(date.getFullYear(), date.getMonth() + 1, 0);
                        uvenddate = uvenddate.getFullYear() + '-' +
                            String(uvenddate.getMonth() + 1).padStart(2, '0') + '-' +
                            String(uvenddate.getDate()).padStart(2, '0');

                        uveventdpfilter.setAttribute("data-date", uvseldate);
                        uveventdpfilter.setAttribute("data-fromdate", uvseldate);
                        uveventdpfilter.setAttribute("data-todate", uvenddate);

                        const uvcachekey = `${uvseldate}_${uvenddate}`;

                        if (window.uws_event_dp_cache && window.uws_event_dp_cache[uvcachekey]) {
                            uwsLoadDPEventCache(uvcachekey);
                        } else {
                            uwsLoadDPEvent(uvdpcontainer, uvseldate, uvenddate, uvvenuecode);
                        }
                    }),
                    n.on('render:day', (dayElement, date) => {
                        const formattedDate = date.format('YYYY-MM-DD');

                        if (uws_event.events && uws_event.events[formattedDate]) {
                            const uvevents = uws_event.events[formattedDate];
                            const uvmultievt = (uvevents.length > 1) ? 1 : 0;
                            const uvevtspan = uvevents.map(() => `<span class="uws-evt-count"></span>`).join('');
                            const uveventcodes = (uvmultievt) && uvevents.map(ev => ev.eventcode).join(',');

                            const uvlinks = uvevents.map(uvevent => {
                                return `<div class="uwsdplink-container">
                                            <a class="uwsdplink${uvmultievt ? ' uwsjs-gotoevt' : ''}" 
                                                href="${uvmultievt ? 'javascript:void(0);' : uvevent.eventurl}" 
                                                data-multievt="${uvmultievt ? 1 : 0}" 
                                                data-eventcode="${uvmultievt ? uveventcodes : uvevent.eventcode}"
                                                data-eventdate="${uvevent.eventdate}"
                                                data-eventddate="${uvevent.eventddate}">
                                                ${uvevtspan}
                                            </a>
                                        </div>`;
                            }).join('');
                            dayElement.innerHTML = `${dayElement.innerHTML}${uvlinks}`;
                        } else {
                            // Disable days without events
                            dayElement.classList.add('uws-day-disabled', 'is-locked');
                            dayElement.style.pointerEvents = 'none';
                            dayElement.style.opacity = '0.5';
                            dayElement.setAttribute('aria-disabled', 'true');
                            dayElement.tabIndex = -1;
                        }
                    });
            }
        });
    }

    if (document.querySelector("#uwsfilterrange")) {//if filter is range picker
        const uvmindate = document.querySelector("#uwsfilterrange").getAttribute("data-date");
        const uvenddate = document.querySelector("#uwsfilterrange").getAttribute("data-enddate");
        const uvmaxdate = document.querySelector(".uws-events").getAttribute("data-filter-maxdate");
        const uvdplang = (document.querySelector("#uwsfilterrange").getAttribute("data-lang")) ? document.querySelector("#uwsfilterrange").getAttribute("data-lang") : "en";
        const uvdpmonths = (window.innerWidth > 800) ? 2 : 1;

        uws_dp_date = new Litepicker({
            element: document.querySelector(".uws-dp-filterdaterange"),
            numberOfColumns: uvdpmonths,
            numberOfMonths: uvdpmonths,
            minDate: uvmindate,
            maxDate: uvmaxdate,
            inlineMode: 1,
            singleMode: 0,
            showTooltip: 1,
            startDate: uvmindate,
            endDate: uvenddate,
            lang: uvdplang,
            setup: function (n) {
                n.on("selected", function (n, t) {
                    const uvseldate = n.format('YYYY-MM-DD');
                    const uvddate = n.toLocaleString(uvdplang, { month: 'short' }) + " " + n.getDate();
                    this.ui.closest(".uwshasdrop").classList.remove("uwsactive");
                    document.querySelector(".uws-events").setAttribute("data-filter-date", uvseldate);

                    const uvselenddate = t.format("YYYY-MM-DD");
                    document.querySelector(".uws-events").setAttribute("data-filter-enddate", uvselenddate);
                    const uvendddate = n.toLocaleString(uvdplang, { month: 'short' }) + " " + t.getDate() + ", " + t.getFullYear();

                    document.querySelector("#uwsfilterrange").innerHTML = uvddate + " - " + uvendddate;
                    setTimeout(function () {
                        uws_dp_date.ui.closest(".uws-dropdown").querySelector(".uws-dp-filterdaterange-label").innerHTML = "Select Range";
                    }, 300);

                    uwsEventsUpdateURL("enddate", uvselenddate);
                    uwsEventsUpdateURL("date", uvseldate);
                    uwsEventsFilter();
                }),
                    n.on('preselect', function (n, t) {
                        const uvddate = n.toLocaleString(uvdplang, { month: 'short' }) + " " + n.getDate();

                        this.ui.closest(".uws-dropdown").querySelector(".uws-dp-filterdaterange-label").innerHTML = uvddate + " - Select End Date";
                    })
            }
        });
    }

    if (document.querySelector(".uws-events-views")) {
        const uvcalcellwidth = document.querySelector(".uws-events-views").offsetWidth / 7;
        document.documentElement.style.setProperty('--uws-cal-cell-minheight', `${uvcalcellwidth}px`);
        window.addEventListener('resize', () => {
            const uvcalcellwidth = document.querySelector(".uws-events-views").offsetWidth / 7;
            document.documentElement.style.setProperty('--uws-cal-cell-minheight', `${uvcalcellwidth}px`);
        });
    }

    uwsUpdateEventsCountClass();
    if (document.querySelector(".uws-events") && document.querySelector(".uws-events").classList.contains("uws-events-count-0"))
        uwsEventsLoadMore();

    //Check if there is a default performer
    if (document.querySelector(".uws-events") && document.querySelector(".uws-events").getAttribute("data-initperfomer")) {
        const uvcontrolsloader = document.querySelector(".uws-events").querySelector(".uws-events-controls");

        if (uvcontrolsloader) uvcontrolsloader.classList.add("uwsloading");

        setTimeout(() => {
            document.querySelector(".uws-events .uwsperformersel .uwsjs-events-selectperformer[data-performercode='" + document.querySelector(".uws-events").getAttribute("data-initperfomer") + "']").click();
            if (uvcontrolsloader) uvcontrolsloader.classList.remove("uwsloading");
        }, 350);

        uwsEventsUpdatePerformers();
    }
    uwsInitDrops();
}

/**
 * Determines the event link type for UVEvents.
 *
 * This function checks if the global `UVEvents` object is defined and if its `options.eventlinktype`
 * property is set to "popup-event-booking". If both conditions are met, it returns the event link type;
 * otherwise, it returns an empty string.
 *
 * @returns {string} The event link type if it is "popup-event-booking", otherwise an empty string.
 */
function uwsEventsPopURL() {
    return (typeof UVEvents !== 'undefined' && UVEvents?.options?.eventlinktype === "popup-event-booking") ? UVEvents.options.eventlinktype : "";
}

/**
 * Gets the views configuration from UVEvents options.
 *
 * This function checks if the global `UVEvents` object is defined and returns the views
 * configuration if available.
 *
 * @returns {string} The views configuration as string, or null if not available.
 */
function uwsEventsGetViews() {
    return (typeof UVEvents !== 'undefined' && UVEvents?.options?.views && UVEvents.options.views.length > 0) ? UVEvents.options.views : null;
}

/**
 * Gets the default view configuration from UVEvents options.
 *
 * This function checks if the global `UVEvents` object is defined and returns the default view
 * configuration if available.
 *
 * @returns {string} The default view configuration, or null if not available.
 */
function uwsEventsGetDefaultView() {
    return (typeof UVEvents !== 'undefined' && UVEvents?.options?.defaultview) ? UVEvents.options.defaultview : null;
}

//Apply Events Filters
function uwsEventsFilter() {
    const uveventsstage = document.querySelector(".uws-events");
    const uvactionselem = uveventsstage.querySelector(".uws-events-actions");
    const uvdate = uveventsstage.getAttribute("data-filter-date");
    const uvenddate = uveventsstage.getAttribute("data-filter-enddate");
    const uvvenue = uveventsstage.getAttribute("data-filter-venue");
    const uvmaxdate = uveventsstage.getAttribute("data-filter-maxdate");
    const uvbtnlabel = uveventsstage.getAttribute("data-buttonlabel");

    // Get views and defaultview from UVEvents options first, then fallback to data attributes or URL
    const uvviews = uwsEventsGetViews();
    const uvdefaultview = uwsEventsGetDefaultView();

    uveventsstage.querySelector(".uws-events-controls").classList.add("uwsloading");
    let uveventsload = uws_proxy + "&uvaction=uwspx_loadevents";
    const uveventsurl = uwsEventsPopURL();

    uveventsload = uveventsload + "&date=" + uvdate + "&venue=" + uvvenue + "&enddate=" + uvenddate + "&eventlinktype=" + uveventsurl + "&btnlabel=" + uvbtnlabel;

    // Add views and defaultview parameters if they exist
    if (uvviews)
        uveventsload = uveventsload + "&views=" + uvviews;

    if (uvdefaultview)
        uveventsload = uveventsload + "&defaultview=" + uvdefaultview;

    //add microcode if is in uwsinventory object (for no lib integrations)
    if (typeof (uws_inventory.microcode) != "undefined" && uws_inventory.microcode)
        uveventsload = uveventsload + "&microcode=" + uws_inventory.microcode;

    // @egt [UWS-7297]
    if(typeof uwseventsvars !== "undefined" && uwseventsvars.targetNonce) {
        uveventsload = uveventsload + "&uws_nonce=" + encodeURIComponent(uwseventsvars.targetNonce);
    }

    let uvrequest = new XMLHttpRequest();
    uvrequest.open('GET', uveventsload, true);
    uvrequest.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            let uvresponse = this.response;
            uvresponse = JSON.parse(uvresponse);

            //add html to views
            const uveventsviews = uveventsstage.querySelectorAll(".uws-events-view");
            Array.prototype.forEach.call(uveventsviews, function (el, i) {
                const uvviewkey = el.getAttribute("data-viewkey");

                if (typeof (uvresponse[uvviewkey]) != "undefined") {
                    const uvviewselector = (uvviewkey == "calendar") ? ".uws-events-calendar" : ".uws-events-view-" + uvviewkey + " > div";
                    uveventsstage.querySelector(uvviewselector).innerHTML = uvresponse[uvviewkey];
                }
            });

            uwsUpdateEventsCountClass();
            uwsEventsUpdatePerformers();

            uvactionselem.querySelector(".uwsjs-events-loadmore").setAttribute("data-load-date", uvresponse["nextloaddate"]);
            if (uvmaxdate <= uvresponse["todate"])
                uvactionselem.classList.remove("uwsactive");
            else {
                uvactionselem.classList.add("uwsactive");
                uvactionselem.classList.remove("uwsmsgactive");
            }

            //remove loader
            uveventsstage.querySelector(".uws-events-controls").classList.remove("uwsloading");
        } else {
            console.log("UVJS Error: Server returned an error");
        }
    };
    uvrequest.onerror = function () {
        console.log("UVJS Error: Request Error");
    };
    uvrequest.send();
}

//Toggle style to hide performers in events @egt
function uwsEventsFilterPerformer(uvperformercode) {
    const uvhead = document.querySelector("head");
    
    var uvstyles = document.querySelector(".uv-toggle-visibility");
    var uvreplacetarget;
    var uvcss = "";

    if(!uvstyles && uvhead){
        uvhead.insertAdjacentHTML("beforeend", "<style class='uv-toggle-visibility'></style>"); 
        uvstyles = document.querySelector(".uv-toggle-visibility");  
    }

    if(uvstyles) {
        uvreplacetarget = uvstyles.innerHTML;

        if (uvperformercode && uvperformercode !== "all") {
            uws_data_filter_performer = ".uws-performer-" + uvperformercode;
            
            uvcss = `
                .uws-event-list-item{
                    display: none;
                }
                .uws-agenda-default .uws-event-list-item${uws_data_filter_performer}${uws_data_filter_location}{
                    display: block;
                }
                .uws-list-default .uws-event-list-item${uws_data_filter_performer}${uws_data_filter_location}{
                    display: block;
                }
            `;
            
            uvreplacetarget = uvreplacetarget.replace(uvreplacetarget, uvcss);
        } else {
            uws_data_filter_performer = "";
            uvreplacetarget = uvreplacetarget.replace(uvreplacetarget, uvcss);
        }

        uvstyles.innerHTML = uvreplacetarget;
    }
}

//Cleans the performer filter when using the other filters @egt
function uwsCleanPerformersFilter() {
    var uvtarget = document.querySelector("a#uwsfilterperformer span.uwsdy-dropvalue");
    if(uvtarget) uvtarget.innerHTML = "All Artists";

    uwsEventsUpdateURL("performer", "");
    uwsEventsFilterPerformer("all");
}

function uwsEventsUpdatePerformers() {
    const uvPerformers = Array.from(document.querySelectorAll('[class*="uws-performer-"]'));
    const uvPerformersSel = document.querySelector(".uwsperformersel");

    if (uvPerformers.length > 0 && uvPerformersSel) {
        uvPerformersSel.querySelectorAll("li button").forEach((performerSel) => {
            const performerCode = performerSel.getAttribute("data-performercode");

            if (performerCode === "all") {
                performerSel.classList.remove("uws-performer-hidden");
                return;
            }

            if (!uvPerformers.some((performer) => performer.classList.contains(`uws-performer-${performerCode}`))) {
                performerSel.classList.add("uws-performer-hidden");
            } else if (performerSel.classList.contains("uws-performer-hidden")) {
                performerSel.classList.remove("uws-performer-hidden");
            }
        });
    }
}

//Update Events URL
function uwsEventsUpdateURL(uvkey, uvvalue) {
    const uveventselem = document.querySelector(".uws-events");
    if (uveventselem && uveventselem.getAttribute("data-update-url") == "1") {
        uwsAddParameterURI(uvkey, uvvalue);
    }
}

//Load more events
function uwsEventsLoadMore() {
    const uveventsstage = document.querySelector(".uws-events");
    const uvactionselem = uveventsstage.querySelector(".uws-events-actions");
    const uvdate = uvactionselem.querySelector(".uwsjs-events-loadmore").getAttribute("data-load-date");
    const uvvenue = uveventsstage.getAttribute("data-filter-venue");
    const uvmaxdate = uveventsstage.getAttribute("data-filter-maxdate");
    const uvbtnlabel = uveventsstage.getAttribute("data-buttonlabel");

    // Get views and defaultview from UVEvents options first, then fallback to data attributes or URL
    const uvviews = uwsEventsGetViews();
    const uvdefaultview = uwsEventsGetDefaultView();

    const uveventsurl = uwsEventsPopURL();

    uvactionselem.classList.add("uwsloading");
    let uveventsload = uws_proxy + "&uvaction=uwspx_loadevents";

    uveventsload = uveventsload + "&date=" + uvdate + "&venue=" + uvvenue + "&nopredates=1" + "&eventlinktype=" + uveventsurl + "&btnlabel=" + uvbtnlabel;

    // Add views and defaultview parameters if they exist
    if (uvviews)
        uveventsload = uveventsload + "&views=" + uvviews;

    if (uvdefaultview)
        uveventsload = uveventsload + "&defaultview=" + uvdefaultview;

    //add microcode if is in uwsinventory object (for no lib integrations)
    if (typeof (uws_inventory.microcode) != "undefined" && uws_inventory.microcode)
        uveventsload = uveventsload + "&microcode=" + uws_inventory.microcode;

    // @egt [UWS-7297]
    if(typeof uwseventsvars !== "undefined" && uwseventsvars.targetNonce) {
        uveventsload = uveventsload + "&uws_nonce=" + encodeURIComponent(uwseventsvars.targetNonce);
    }

    let uvrequest = new XMLHttpRequest();
    uvrequest.open('GET', uveventsload, true);
    uvrequest.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            let uvresponse = this.response;
            uvresponse = JSON.parse(uvresponse);

            //add html to views
            const uveventsviews = uveventsstage.querySelectorAll(".uws-events-view");
            Array.prototype.forEach.call(uveventsviews, function (el, i) {
                const uvviewkey = el.getAttribute("data-viewkey");

                if (typeof (uvresponse[uvviewkey]) != "undefined") {
                    const uvviewselector = (uvviewkey == "calendar") ? ".uws-events-calendar" : ".uws-events-view-" + uvviewkey + " > div";

                    //remove fillcells on calendar
                    if (uvviewkey == "calendar") {
                        const uvfillcells = uveventsstage.querySelector(uvviewselector).querySelectorAll(".uwsfillcell");

                        Array.prototype.forEach.call(uvfillcells, function (el, i) {
                            el.remove();
                        });
                    }

                    // Remove no content element and create proper container if needed
                    const uvnocontentelem = uveventsstage.querySelector(".uws-nocontent");
                    if (uvnocontentelem) {
                        uvnocontentelem.remove();

                        if (uvviewkey !== "calendar") {
                            const uvviewcontainerclass = (uvviewkey == "agenda") ? "uws-agenda-default" : "uws-list-default";
                            const uvparentelem = uveventsstage.querySelector(".uws-events-view-" + uvviewkey);

                            // Only create container if it doesn't exist
                            if (uvparentelem && !uvparentelem.querySelector("." + uvviewcontainerclass)) {
                                uvparentelem.innerHTML = `<div class="${uvviewcontainerclass}"></div>`;
                            }
                        }
                    }

                    const uvtargetelement = uveventsstage.querySelector(uvviewselector);
                    if (uvtargetelement) {
                        uvtargetelement.insertAdjacentHTML("beforeend", uvresponse[uvviewkey]);
                    }
                }
            });

            uwsUpdateEventsCountClass();
            uwsEventsUpdatePerformers();
            uvactionselem.querySelector(".uwsjs-events-loadmore").setAttribute("data-load-date", uvresponse["nextloaddate"]);

            if (uvmaxdate <= uvresponse["todate"]) {
                if (!uvresponse["nevents"]) {
                    uvactionselem.classList.add("uwsmsgactive");
                }
                else
                    uvactionselem.classList.remove("uwsactive");
            }
            else {
                uvactionselem.classList.add("uwsactive");
                uvactionselem.classList.remove("uwsmsgactive");
            }

            //remove loader
            if (uvresponse["nevents"] || uvmaxdate <= uvresponse["todate"])
                uvactionselem.classList.remove("uwsloading");
            else if (!uvresponse["nevents"])//if not event load more again
                uwsEventsLoadMore();
        } else {
            console.log("UVJS Error: Server returned an error");
        }
    };
    uvrequest.onerror = function () {
        console.log("UVJS Error: Request Error");
    };
    uvrequest.send();
}

//Update number of events class
function uwsUpdateEventsCountClass() {
    const uveventsstage = document.querySelector(".uws-events");
    let uveventscount = 0;

    if (uveventsstage) {
        let uvtargetview = (uveventsstage.querySelector(".uws-events-view-agenda")) ? uveventsstage.querySelector(".uws-events-view-agenda") : uveventsstage.querySelector(".uws-events-view-list");

        if (uvtargetview) {
            const uveventselems = uvtargetview.querySelectorAll(".uws-event-item");
            uveventscount = (uveventselems) ? uveventselems.length : uveventscount;
        }

        for (let i = uveventsstage.classList.length - 1; i >= 0; i--) {
            const className = uveventsstage.classList[i];
            if (className.startsWith('uws-events-count-')) {
                uveventsstage.classList.remove(className);
            }
        }

        uveventsstage.classList.add("uws-events-count-" + uveventscount);
    }
}

//Load dynamic events if exist on page
function uwsLoadDynamicEvents() {
    let uveventcodes = "";
    let uvtemplates = "";
    const uvdynaeventselems = document.querySelectorAll(".uwsdynamicevent.uwstoload");
    Array.prototype.forEach.call(uvdynaeventselems, function (el, i) {
        uveventcodes = uveventcodes + el.getAttribute("data-eventcode") + ",";
        uvtemplates = uvtemplates + el.getAttribute("data-template") + ",";
        el.classList.remove("uwstoload");
        el.classList.add("uwsloading");
    });

    if (uveventcodes && uvtemplates) {
        uveventcodes = uveventcodes.slice(0, -1);

        let uvdynaeventsurl = uws_proxy + "&uvaction=uwspx_loaddynamicevents&eventcodes=" + uveventcodes + "&templates=" + uvtemplates;
        
        // @egt [UWS-7297]
        if(typeof uwseventsvars !== "undefined" && uwseventsvars.targetNonce) {
            uvdynaeventsurl = uvdynaeventsurl + "&uws_nonce=" + encodeURIComponent(uwseventsvars.targetNonce);
        }
        
        let uvrequest = new XMLHttpRequest();
        uvrequest.open('GET', uvdynaeventsurl, true);
        uvrequest.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                let uvresponse = this.response;
                uvresponse = JSON.parse(uvresponse);

                Array.prototype.forEach.call(uvdynaeventselems, function (el, i) {
                    const uveventcode = el.getAttribute("data-eventcode");
                    const uvtemplate = el.getAttribute("data-template");

                    if (uvresponse && uvresponse[uveventcode] && uvresponse[uveventcode][uvtemplate])
                        el.querySelector(".uvdy-theevent").innerHTML = uvresponse[uveventcode][uvtemplate]["html"];

                    el.classList.remove("uwsloading");
                });
            } else {
                console.log("UVJS Error: Server returned an error");
            }
        };
        uvrequest.onerror = function () {
            console.log("UVJS Error: Request Error");
        };
        uvrequest.send();
    }
}

//Open mobile calendar cell
uwsClickListener(".uwsjs-open-calcelldate", function (e) {
    e.preventDefault();

    const uvcellelem = this.closest(".uws-cal-date");

    if (uvcellelem) {
        const uvcellclone = uvcellelem.cloneNode(true);
        const uvpopchargetarget = uws_calcell_pop.querySelector(".uws-pop-charge");
        uvpopchargetarget.innerHTML = "";
        uvpopchargetarget.appendChild(uvcellclone);

        uws_calcell_pop.classList.add("uws-calcell-pop");
        uwsFadePopup(uws_calcell_pop);
    }
});

// Show event popup selection
uwsClickListener(".uwsjs-gotoevt", function (e) {
    e.preventDefault();

    const uvthiselem = this;
    const uvdpelem = this.closest(".uws-event-dpinput");
    const uveventcode = uvthiselem.getAttribute("data-eventcode");
    const uveventdate = uvthiselem.getAttribute("data-eventdate");
    const uveventddate = uvthiselem.getAttribute("data-eventddate");
    const uveventpoptheme = (uvdpelem && uvdpelem.getAttribute("data-poptheme")) ? uvdpelem.getAttribute("data-poptheme") : "uws-light";
    let uvevtselpopHTML = "";

    if (uws_event.events && uws_event.events[uveventdate]) {
        const uvevents = uws_event.events[uveventdate];
        const uvpopchargetarget = uws_calcell_pop.querySelector(".uws-pop-charge");
        uvpopchargetarget.innerHTML = "";

        uws_calcell_pop.classList.add("uws-calcell-pop", "uws-pop-events", `${uveventpoptheme}`);

        if (uvevents) {
            const uvevtselpopheader = `<div class='uwsevtselheader'>
                                            <div class='uwsevtselheader-title'>
                                                ${uvevents.length} events for ${uveventddate}
                                            </div>
                                            <div class='uwsevtselheader-subtitle'>
                                                Select an event to view more details
                                            </div>
                                        </div>`;

            const uvevtselpopbody = `<div class='uwsevtselbody'>${uvevents.map(event => `
                                        <a href='${event.eventurl}' class='uwsevtselitem-link uwsevtselitem'>
                                            <div class='uwsevtselitem-inner'>
                                                <div class='uwsevtselitem-flyer'>
                                                    <img src='${event.eventflyer}' alt='${event.eventname} - Flyer' class='uwsflyercont uwsimgloading' 
                                                    onload="this.classList.add('uwsloaded');"
                                                    onerror="this.classList.add('uwsnoimg')">
                                                </div>
                                                <div class='uwsevtselitem-content'>
                                                    <div class='uwsevtselitem-time'>${event.eventddate}${event.eventstarttime}</div>
                                                    <div class='uwsevtselitem-title'>${event.eventname}</div>
                                                </div>
                                            </div>
                                        </a>
                                    `).join('')}</div>`;
            uvevtselpopHTML = `${uvevtselpopheader}${uvevtselpopbody}<span class="uws-btn uws-btn-s uws-btn-close uwsjs-closeeventspop uwsjs-closepop-force">Close</span>`;
            uvpopchargetarget.innerHTML = uvevtselpopHTML;
            uwsFadePopup(uws_calcell_pop);
        }
    }
});

uwsClickListener(".uwsjs-closeeventspop", function (e) {
    e.preventDefault();

    const uvevtpop = this.closest(".uws-calcell-pop");
    const uvdp = document.querySelector(".uwsjs-show-evtdp");

    if (uvevtpop && uvevtpop.classList.contains("uws-calcell-pop") && uvevtpop.classList.contains("uws-pop-events"))
        if (uvdp) uvdp.click();
});

uwsClickListener(".uwsjs-trigger-evtdp", function (e) {
    e.preventDefault();

    if (e.target.classList.contains("uwsdplink")) {
        const uvthiselem = e.target;
        const uvmultievt = uvthiselem.getAttribute("data-multievt");

        if (uvmultievt == "1")
            uvthiselem.click();
        else
            window.location.href = uvthiselem.href;
    } else if (!e.target.closest(".uwsjs-show-evtdp")) {
        const showBtn = this.querySelector(".uwsjs-show-evtdp");
        if (showBtn) showBtn.click();

    }
});

// When datepicker is active
uwsClickListener(".uwsjs-show-evtdp", function (e) {
    e.preventDefault();

    const uvdpcontainer = this.closest(".uwshasdrop");
    const uvdp = this.closest(".uwshasdrop").querySelector(".uwseventdp");

    if (this.closest(".uwshasdrop").classList.contains("uwsactive")) {
        const uvdpfromdate = uvdp.getAttribute("data-fromdate");
        const uvdptodate = uvdp.getAttribute("data-todate");
        const uvdpvenuecode = uvdp.getAttribute("data-venuecode");

        const uvcachekey = `${uvdpfromdate}_${uvdptodate}`;
        if (window.uws_event_dp_cache && window.uws_event_dp_cache[uvcachekey]) {
            uwsLoadDPEventCache(uvcachekey);
        } else {
            uwsLoadDPEvent(uvdpcontainer, uvdpfromdate, uvdptodate, uvdpvenuecode);
        }
    }
});

// Toggle description in bookpopup
uwsClickListener(".uwsjs-descr-toggle", function () {
    const uvdescr = this.closest(".uwseventdescr")?.querySelector(".uwsdescr");
    if (uvdescr) uvdescr.classList.toggle("uwsdescropened");
});

function uwsLoadDPEvent(uvelem, uvdate, uvenddate, uvvenuecode) {
    let uveventsdata = {};
    let uvdynaeventsurl = uws_proxy + "&uvaction=uwspx_loadeventsdp&date=" + uvdate + "&enddate=" + uvenddate + "&venue=" + uvvenuecode;
    const uvcachekey = `${uvdate}_${uvenddate}`;

    uvelem.classList.add("uwsloading");

    // @egt [UWS-7297]
    if(typeof uwseventsvars !== "undefined" && uwseventsvars.targetNonce) {
        uvdynaeventsurl = uvdynaeventsurl + "&uws_nonce=" + encodeURIComponent(uwseventsvars.targetNonce);
    }

    let uvrequest = new XMLHttpRequest();
    uvrequest.open('GET', uvdynaeventsurl, true);
    uvrequest.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            let uvresponse = this.response;
            uvresponse = JSON.parse(uvresponse);

            if (uvresponse["uv"]["success"]["status"] === "success" && uvresponse["uv"]["data"]) {
                uveventsdata = uvresponse["uv"]["data"];
                uws_event.inventory = uveventsdata["inventory"];
                uws_event.events = uveventsdata["events"];

                // Create or update the cache
                window.uws_event_dp_cache[uvcachekey] = {
                    inventory: uveventsdata["inventory"],
                    events: uveventsdata["events"]
                };

                if (uws_event_dp) uws_event_dp.render();

                setTimeout(() => {
                    uvelem.classList.remove("uwsloading");
                }, 300);
            } else {
                uvelem.classList.remove("uwsloading");

                window.uws_event_dp_cache[uvcachekey] = {
                    inventory: {},
                    events: {}
                };
                return;
            }
        } else {
            console.log("UVJS Error: Server returned an error");
        }
    };
    uvrequest.onerror = function () {
        console.log("UVJS Error: Request Error");
    };
    uvrequest.send();
}

// Load the events data from the cache
function uwsLoadDPEventCache(uvcachekey) {
    if (!window.uws_event_dp_cache || !window.uws_event_dp_cache[uvcachekey]) {
        // If not in cache, fetch and cache it
        uwsLoadDPEvent(
            uws_event_dp.ui.closest(".uwshasdrop"),
            uws_event_dp.dpcurrentmont,
            uws_event_dp.ui.getAttribute("data-maxdate"),
            uws_event_dp.ui.getAttribute("data-venuecode")
        );
        return;
    }

    // Load cached data into global uws_event object
    uws_event.inventory = window.uws_event_dp_cache[uvcachekey].inventory;
    uws_event.events = window.uws_event_dp_cache[uvcachekey].events;

    if (uws_event_dp) uws_event_dp.render();
}

function uwsLoadDPEvents(uvelem, uvdate, uvenddate, uvvenuecode, uvdp = null) {
    let uveventsdata = {};
    let uvdynaeventsurl = uws_proxy + "&uvaction=uwspx_loadeventsdp&date=" + uvdate + "&enddate=" + uvenddate + "&venue=" + uvvenuecode;
    const uvcachekey = `${uvdate}_${uvenddate}`;

    uvelem.classList.add("uwsloading");
    
    // @egt [UWS-7297]
    if(typeof uwseventsvars !== "undefined" && uwseventsvars.targetNonce) {
        uvdynaeventsurl = uvdynaeventsurl + "&uws_nonce=" + encodeURIComponent(uwseventsvars.targetNonce);
    }

    let uvrequest = new XMLHttpRequest();
    uvrequest.open('GET', uvdynaeventsurl, true);
    uvrequest.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            let uvresponse = this.response;
            uvresponse = JSON.parse(uvresponse);

            if (uvresponse["uv"]["success"]["status"] === "success" && uvresponse["uv"]["data"]) {
                uveventsdata = uvresponse["uv"]["data"];
                uws_events.inventory = uveventsdata["inventory"];
                uws_events.events = uveventsdata["events"];

                // Create or update the cache
                window.uws_events_dp_cache[uvcachekey] = {
                    inventory: uveventsdata["inventory"],
                    events: uveventsdata["events"]
                };

                if (uvdp) uvdp.render();

                setTimeout(() => {
                    uvelem.classList.remove("uwsloading");
                }, 300);
            } else {
                uvelem.classList.remove("uwsloading");

                window.uws_events_dp_cache[uvcachekey] = {
                    inventory: {},
                    events: {}
                };
                return;
            }
        } else {
            console.log("UVJS Error: Server returned an error");
        }
    };
    uvrequest.onerror = function () {
        console.log("UVJS Error: Request Error");
    };
    uvrequest.send();
}

// Load the events data from the cache
function uwsLoadDPEventsCache(uvcachekey, uvdp) {
    if (!window.uws_events_dp_cache || !window.uws_events_dp_cache[uvcachekey]) {
        // If not in cache, fetch and cache it
        uwsLoadDPEvents(
            uws_events_dp.ui.closest(".uwshasdrop"),
            uws_events_dp.dpcurrentmont,
            uws_events_dp.ui.getAttribute("data-maxdate"),
            uws_events_dp.ui.getAttribute("data-venuecode"),
            uvdp
        );
        return;
    }

    // Load cached data into global uws_events object
    uws_events.inventory = window.uws_events_dp_cache[uvcachekey].inventory;
    uws_events.events = window.uws_events_dp_cache[uvcachekey].events;

    if (uvdp) uvdp.render();
}