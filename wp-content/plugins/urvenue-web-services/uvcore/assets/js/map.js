//Requires: uwscore.js, uwsinventory.js

var uws_mapsel_pop;
var uws_maplist_scroll;
var uws_map_dp;
window.uws_map = window.uws_map || {};

uwsUpdateViewportVars();
window.addEventListener('resize', () => {
    uwsUpdateViewportVars();
});

uwsDOMReady(function () {
    uwsInitMap();
});

function uwsInitMap() {
    const uvmapload = document.querySelector(".uwsjs-loadmap");
    if (uvmapload)
        uwsMapLoad(uvmapload);

    if (document.querySelector("#uwsmapfilterdate")) {//filter is datepicker
        const uvmindate = document.querySelector(".uws-map").getAttribute("data-mindate");
        const uvinitdate = document.querySelector("#uwsmapfilterdate").getAttribute("data-date");
        const uvmaxdate = document.querySelector(".uws-map").getAttribute("date-filter-maxdate");
        const uvdplang = (document.querySelector(".uws-map").getAttribute("data-lang")) ? document.querySelector(".uws-map").getAttribute("data-lang") : "en";

        uws_map_dp = new Litepicker({
            element: document.querySelector(".uws-dp-mapfilterdate"),
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
                    document.querySelector(".uws-map").setAttribute("data-date", uvseldate);
                    document.querySelector("#uwsmapfilterdate").innerHTML = uvddate;

                    uwsMapLoad(uvmapload);
                }),
                    n.on('render:month', (month, date) => {
                        const uvseldate = date.format('YYYY-MM-DD');
                        uws_map.dpcurrentmont = uvseldate;
                    }),
                    n.on('change:month', (date, calendarIdx) => {
                        uwsMapDPUpdateMonth();
                    })
            }
        });
    }

    document.body.addEventListener('click', function (e) {
        uwsMapHideAllTooltips();
    });

    window.addEventListener("resize", function () {
        uwsMapHideAllTooltips();
        uwsCleanMapClasses();
    });

    window.addEventListener("scroll", function () {
        const uvactivetooltips = document.querySelectorAll(".uws-mapitem-tooltip.uwsactive");
        Array.prototype.forEach.call(uvactivetooltips, function (el, i) {
            const uvtooltipoffset = el.getAttribute("data-offset") / 1;

            if ((window.pageYOffset - uvtooltipoffset) > 100 || (window.pageYOffset - uvtooltipoffset) < -100) {
                uwsCloseMapTooltip(el);
            }
        });
    });

    uwsUpdateViewportVars();
}

function uwsMapLoad(uvmapelem) {
    uwsMapCloseItemBox();

    if (typeof (uvmapelem) == "undefined")
        uvmapelem = document.querySelector(".uwsjs-loadmap");

    uvmapelem.classList.remove("uwsprepare", "uwsloaded");
    uvmapelem.classList.add("uwsloading");
    const uvdate = uvmapelem.getAttribute("data-date");
    const uvvenuecode = uvmapelem.getAttribute("data-venuecode");
    const uvecozone = uvmapelem.getAttribute("data-ecozone");
    const uvforcelisttype = uvmapelem.getAttribute("data-forcelisttype");
    const uvnogroupings = uvmapelem.getAttribute("data-nogroupings");
    const uvhomename = (uvmapelem.getAttribute("data-homename")) ? uvmapelem.getAttribute("data-homename") : "";
    const uvhomeecozone = (uvmapelem.getAttribute("data-homeecozone")) ? uvmapelem.getAttribute("data-homeecozone") : "";

    //uvmapelem.innerHTML = uvinithtml;
    const uvmapload = uvmapelem.querySelector(".uws-map-load");

    const uvreturntempl = (typeof (uws_map.templates) != "undefined") ? 0 : 1;
    const uvreturnlang = (typeof (uws_front_lang) == "object") ? 0 : 1;
    let uvmaploadurl = uws_proxy + "&uvaction=uwspx_map";
    uvmaploadurl = uvmaploadurl + "&date=" + uvdate + "&venuecode=" + uvvenuecode + "&ecozone=" + uvecozone + "&returntempl=" + uvreturntempl + "&cartcode=" + uwsInvGetCartCookie() + "&forcelisttype=" + uvforcelisttype + "&nogroupings=" + uvnogroupings + "&homename=" + uvhomename + "&homeecozone=" + uvhomeecozone + "&returnlang=" + uvreturnlang;

    //add manageentid if is in uwsinventory object (for no lib integrations)
    if (typeof (uws_inventory.manageentid) != "undefined" && uws_inventory.manageentid)
        uvmaploadurl = uvmaploadurl + "&manageentid=" + uws_inventory.manageentid;

    //add microcode if is in uwsinventory object (for no lib integrations)
    if (typeof (uws_inventory.microcode) != "undefined" && uws_inventory.microcode)
        uvmaploadurl = uvmaploadurl + "&microcode=" + uws_inventory.microcode;

    // add aux theme if set
    if (typeof (uws_map.theme_aux) != "undefined" && uws_map.theme_aux)
        uvmaploadurl = uvmaploadurl + "&theme=" + uws_map.theme_aux;

    // add aux poptheme if set
    if (typeof (uws_map.poptheme_aux) != "undefined" && uws_map.poptheme_aux)
        uvmaploadurl = uvmaploadurl + "&poptheme=" + uws_map.poptheme_aux;

    // @egt [UWS-7297]
    if(typeof urvenue_ws_map_vars !== "undefined" && urvenue_ws_map_vars.targetNonce) {
        uvmaploadurl = uvmaploadurl + "&uws_nonce=" + encodeURIComponent(urvenue_ws_map_vars.targetNonce);
    }

    let uvrequest = new XMLHttpRequest();
    uvrequest.open('GET', uvmaploadurl, true);
    uvrequest.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            let uvresponse = this.response;
            uvresponse = JSON.parse(uvresponse);

            if (typeof (uvresponse.stagehtml) != "undefined") {
                let uvstagehtml = uvresponse.stagehtml;
                uvstagehtml = uvstagehtml.replace(/{mapgraph}/g, uvresponse.mapgraph);
                uvmapload.innerHTML = uvstagehtml;

                //Check if has backecozone
                if (typeof (uvresponse.ecozoneback) != "undefined" && uvresponse.ecozoneback) {
                    uvmapelem.classList.add("uwshasecozoneback");
                    uvmapload.querySelector(".uws-map-stage").insertAdjacentHTML("afterbegin", uvresponse.ecozoneback);
                }
                else
                    uvmapelem.classList.remove("uwshasecozoneback");

                uvmapelem.classList.remove("uwsloading");
                uvmapelem.classList.add("uwsprepare");
                setTimeout(function () {
                    uvmapelem.classList.add("uwsloaded");
                }, 100);
            }

            if (typeof (uvresponse.eventinfo) != "undefined") {
                if (uvmapelem.querySelector(".uwsdy-map-eventinfo")) {
                    const uvmapevinfoelems = uvmapelem.querySelectorAll(".uwsdy-map-eventinfo");
                    Array.prototype.forEach.call(uvmapevinfoelems, function (el, i) {
                        el.innerHTML = uvresponse.eventinfo;
                    });
                }

                if (uvresponse.eventinfo)
                    uvmapelem.classList.add("uwshaseventinfo");
                else
                    uvmapelem.classList.remove("uwshaseventinfo");
            }

            if (typeof (uvresponse.ecozonesel) != "undefined" && uvresponse.ecozonesel) {
                uvmapelem.querySelector(".uws-map-controls").classList.add("uwshasecozonesel");
                uvmapelem.querySelector(".uwsecozonesel").innerHTML = uvresponse.ecozonesel;
            }
            else {
                uvmapelem.querySelector(".uws-map-controls").classList.remove("uwshasecozonesel");
                uvmapelem.querySelector(".uwsecozonesel").innerHTML = "";
            }

            if (typeof (uvresponse.isecozonelist) != "undefined" && uvresponse.isecozonelist)
                uvmapelem.classList.add("uwsisecozonesel");
            else
                uvmapelem.classList.remove("uwsisecozonesel");


            if (typeof (uvresponse.selsstring) != "undefined") {
                if (uvmapelem.querySelector(".uwsdy-mapselstring")) {
                    uvmapelem.querySelector(".uwsdy-mapselstring").innerHTML = uvresponse.selsstring;

                    if (uvmapelem.querySelector(".uws-map-controls .uwsfilters .uwsecozonesel .uwsjs-trigger-dropdown"))
                        uvmapelem.querySelector(".uwsdy-mapselstring > span").innerHTML = uvmapelem.querySelector(".uws-map-controls .uwsfilters .uwsecozonesel .uwsjs-trigger-dropdown > span").innerHTML;
                }

                uvmapelem.classList.remove("uwscontrolsactive");
            }

            if (typeof (uvresponse.lang) == "object")
                uws_front_lang = uvresponse.lang;

            if (typeof (uvresponse) == "object")
                uwsMapAddVars(uvresponse);

            uwsInitMapStage(uvmapelem);

            if (typeof (uvresponse) == "object" && typeof (uvresponse.items) == "object" && typeof (uvhookMapLoaded) == "function")
                uvhookMapLoaded(uvresponse);
        } else {
            console.log("UVJS Error: Server returned an error");
        }
    };
    uvrequest.onerror = function () {
        console.log("UVJS Error: Request Error");
    };
    uvrequest.send();
}

/*Add info to global map var*/
function uwsMapAddVars(uvresponse) {
    if (typeof (uvresponse.seatingtype) != "undefined")
        uws_map.seatingtype = uvresponse.seatingtype;
    if (typeof (uvresponse.listtype) != "undefined")
        uws_map.listtype = uvresponse.listtype;
    if (typeof (uvresponse.locations) != "undefined")
        uws_map.locations = uvresponse.locations;
    if (typeof (uvresponse.secitems) != "undefined")
        uws_map.secitems = uvresponse.secitems;
    if (typeof (uvresponse.seclocs) != "undefined")
        uws_map.seclocs = uvresponse.seclocs;
    if (typeof (uvresponse.sections) != "undefined")
        uws_map.sections = uvresponse.sections;
    if (typeof (uvresponse.items) != "undefined")
        uws_map.items = uvresponse.items;
    if (typeof (uvresponse.templates) != "undefined")
        uws_map.templates = uvresponse.templates;
    if (typeof (uvresponse.cart) != "undefined")
        uws_inventory.cart = uvresponse.cart;
    if (typeof (uvresponse.availabilityinfo) == "object" && typeof (uvresponse.availabilityinfo.monthdate) != "undefined" && typeof (uvresponse.availabilityinfo.noinventorydates) != "undefined") {
        window.uws_map.noinventorydates = window.uws_map.noinventorydates || {};
        uws_map.noinventorydates["date:" + uvresponse.availabilityinfo.monthdate] = uvresponse.availabilityinfo.noinventorydates;
    }
    if (typeof (uvresponse.theme) != "undefined")
        uws_map.theme = uvresponse.theme;

    if (typeof (uvresponse.poptheme) != "undefined")
        uws_map.poptheme = uvresponse.poptheme;

    if (typeof (uvresponse.isecozonelist) != "undefined")
        uws_map.isecozonelist = uvresponse.isecozonelist;
    else
        uws_map.isecozonelist = 0;
}

/**/
function uwsMapDPUpdateMonth() {
    const uvdpmonth = (typeof (uws_map.dpcurrentmont) != "undefined") ? uws_map.dpcurrentmont : "";
    const uvmonthcloseddates = (uvdpmonth && typeof (uws_map.noinventorydates) == "object" && typeof (uws_map.noinventorydates["date:" + uvdpmonth]) != "undefined") ? uws_map.noinventorydates["date:" + uvdpmonth] : "";

    if (uvmonthcloseddates) {
        uws_map_dp.setLockDays(uvmonthcloseddates);
    }
    else {
        uws_map_dp.ui.closest(".uws-map-dpinput").classList.add("uwsloading");
        const uvmapelem = document.querySelector(".uwsjs-loadmap");
        const uvvenuecode = uvmapelem.getAttribute("data-venuecode");
        const uvecozone = uvmapelem.getAttribute("data-ecozone");
        const uvhomeecozone = (uvmapelem.getAttribute("data-homeecozone")) ? uvmapelem.getAttribute("data-homeecozone") : "";
        const uvaddmixeco = (uvhomeecozone || uvmapelem.querySelector(".uwsecozonessellist")) ? "&mixecozones=1" : "";

        let uvnoinventorydatesproxy = uws_proxy + "&uvaction=uwspx_noinventorydates";
        uvnoinventorydatesproxy = uvnoinventorydatesproxy + "&date=" + uws_map.dpcurrentmont + "&venuecode=" + uvvenuecode + "&ecozone=" + uvecozone + uvaddmixeco;

        // @egt [UWS-7297]
        if(typeof urvenue_ws_map_vars !== "undefined" && urvenue_ws_map_vars.targetNonce) {
            uvnoinventorydatesproxy = uvnoinventorydatesproxy + "&uws_nonce=" + encodeURIComponent(urvenue_ws_map_vars.targetNonce);
        }

        let uvrequest = new XMLHttpRequest();
        uvrequest.open('GET', uvnoinventorydatesproxy, true);
        uvrequest.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                let uvresponse = this.response;
                uvresponse = JSON.parse(uvresponse);

                if (typeof (uvresponse) == "object")
                    uwsMapAddVars(uvresponse);

                const uvloadedmonthcloseddate = (uvdpmonth && typeof (uws_map.noinventorydates) == "object" && typeof (uws_map.noinventorydates["date:" + uvdpmonth]) != "undefined") ? uws_map.noinventorydates["date:" + uvdpmonth] : "";

                if (uvloadedmonthcloseddate)
                    uws_map_dp.setLockDays(uvloadedmonthcloseddate);

                uws_map_dp.ui.closest(".uws-map-dpinput").classList.remove("uwsloading");
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

/*Init map stage*/
function uwsInitMapStage(uvmapelem) {
    if (typeof (uvmapelem) == "undefined")
        uvmapelem = document.querySelector(".uwsjs-loadmap");

    const uvmaptooltipselems = document.querySelectorAll(".uws-mapitem-tooltip");
    Array.prototype.forEach.call(uvmaptooltipselems, function (el, i) {
        el.remove();
    });

    /*if(uvmapelem.querySelector(".uws-map-list"))
        uws_maplist_scroll = new PerfectScrollbar(uvmapelem.querySelector(".uws-map-list"));*/

    uwsMapGraphClasses(uvmapelem);
    uwsMapMouseEvents(uvmapelem);

    if (typeof (uws_pansvgzoom) == "object") {
        uws_pansvgzoom.destroy();
        uws_pansvgzoom = "";
    }

    if (uvmapelem.querySelector(".uws-map-graph svg"))
        uwsAddMapZoom();

    uwsMapAddListScroll(uvmapelem);
    uwsInitDrops();
    uwsMapDPUpdateMonth();
}

/*Add scroll events on the list*/
function uwsMapAddListScroll(uvmapelem) {
    const uvmaplist = uvmapelem.querySelector(".uws-map-list");

    if (uvmaplist) {
        uvmaplist.addEventListener("scroll", function (e) {
            uwsMapUpdateMapListScroll(uvmaplist);
        });
        uwsMapUpdateMapListScroll(uvmaplist);

        if (!uvmaplist.querySelector(".uwsmaplistfill")) {
            const uvmaplistfill = document.createElement("div");
            uvmaplistfill.classList.add("uwsmaplistfill");

            uvmaplist.querySelector(".uws-map-list-cont").appendChild(uvmaplistfill);
            uwsUpdateListFill(uvmaplist);
        }
    }
}

/*Update map list fill*/
function uwsUpdateListFill(uvmaplist) {
    if (uvmaplist) {
        const uvlistelems = uvmaplist.querySelectorAll(".uws-map-list-elem");
        const uvlistelemheight = (uvlistelems[0]) ? uvlistelems[0].offsetHeight : 0;
        uvmaplist.querySelector(".uwsmaplistfill").style.height = `${uvmaplist.offsetHeight - uvlistelemheight - 10}px`;
    }
}

/*Update map list scroll status*/
function uwsMapUpdateMapListScroll(uvmaplist) {
    const uvforcelisttype = document.querySelector(".uws-map").getAttribute("data-forcelisttype");

    if (uvmaplist && uvforcelisttype != "booktypes" && window.innerWidth <= 700) {
        const uvlistelems = uvmaplist.querySelectorAll(".uws-map-list-elem");
        let uvselelem = "";

        Array.prototype.forEach.call(uvlistelems, function (el, i) {
            if (!uvselelem && (el.offsetTop - uvmaplist.scrollTop) > -8)
                uvselelem = el;
            else
                el.classList.remove("uwshigh");
        });

        if (uvselelem && !uvselelem.classList.contains("uwshigh") && uvmaplist.closest(".uws-map").classList.contains("uws-map-view-list")) {
            uvselelem.classList.add("uwshigh");
            setTimeout(function () {
                const uvseccode = uvselelem.getAttribute("data-seccode");
                const uvmapsecelems = uvselelem.closest(".uws-map-stage").querySelectorAll(".uws-map-graph .uwshasitem.sec_" + uvseccode);

                if (uvmapsecelems && uvmapsecelems[0])
                    uwsMapShowTooltip(uvmapsecelems[0]);
            }, 100);
        }
    }
}

/*Clean map classes*/
function uwsCleanMapClasses() {
    if (window.innerWidth > 700) {
        const uvlistelems = document.querySelectorAll(".uws-map-list .uws-map-list-elem");
        Array.prototype.forEach.call(uvlistelems, function (el, i) {
            el.classList.remove("uwshigh");
        });
    }
    else {
        uwsMapUpdateMapListScroll(document.querySelector(".uws-map-list"));
        uwsUpdateListFill(document.querySelector(".uws-map-list"));
    }
}

/*Add map hover events*/
function uwsMapMouseEvents(uvmapelem) {
    //Map Graph Hover
    const uvmapelems = uvmapelem.querySelectorAll(".uws-map-graph svg .uwshasitem");
    Array.prototype.forEach.call(uvmapelems, function (el, i) {
        el.addEventListener("mouseover", uwsMapElemHover, false);
        el.addEventListener("mouseout", uwsMapElemHoverOut, false);
        el.addEventListener("click", uwsMapElemClick);
    });

    //List Elems Hover
    const uvmaplistelems = uvmapelem.querySelectorAll(".uws-map-list-elem");
    Array.prototype.forEach.call(uvmaplistelems, function (el, i) {
        el.addEventListener("mouseover", uwsMapListElemHover, false);
        el.addEventListener("mouseout", uwsMapListElemHoverOut, false);
    });
}

/*Map List elem is hovered*/
function uwsMapListElemHover() {
    const uvseccode = this.getAttribute("data-seccode");
    const uvlocid = (this.getAttribute("data-loccode") && this.getAttribute("data-loccode") != "null") ? this.getAttribute("data-loccode").replace("LOC", "") : "";
    const uvtargetselector = (uvlocid) ? ".uws-map-graph .uwshasitem.loc_" + uvlocid : ".uws-map-graph .uwshasitem.sec_" + uvseccode;
    const uvmapsecelems = this.closest(".uws-map-stage").querySelectorAll(uvtargetselector);
    Array.prototype.forEach.call(uvmapsecelems, function (el, i) {
        el.classList.add("uwshigh");
    });

    if (uvmapsecelems && uvmapsecelems[0])
        uwsMapShowTooltip(uvmapsecelems[0]);
}

/*Map List elem is hovered out*/
function uwsMapListElemHoverOut() {
    const uvseccode = this.getAttribute("data-seccode");
    const uvlocid = (this.getAttribute("data-loccode") && this.getAttribute("data-loccode") != "null") ? this.getAttribute("data-loccode").replace("LOC", "") : "";
    const uvtargetselector = (uvlocid) ? ".uws-map-graph .uwshasitem.loc_" + uvlocid : ".uws-map-graph .uwshasitem.sec_" + uvseccode;
    const uvmapsecelems = this.closest(".uws-map-stage").querySelectorAll(uvtargetselector);
    Array.prototype.forEach.call(uvmapsecelems, function (el, i) {
        el.classList.remove("uwshigh");
    });

    if (uvmapsecelems && uvmapsecelems[0]) {
        uwsMapShowTooltip(uvmapsecelems[0]);
        uwsMapTryHideTooltip(uvmapsecelems[0]);
    }
}

/*Map graph elem is hovered*/
function uwsMapElemHover() {
    const uvseccode = this.getAttribute("data-seccode");

    if (uws_map.seatingtype == "table") {
        this.classList.add("uwshigh");
    }
    else {
        const uvsecelems = this.closest("svg").querySelectorAll(".uwshasitem.sec_" + uvseccode);
        Array.prototype.forEach.call(uvsecelems, function (el, i) {
            el.classList.add("uwshigh");
        });
    }

    const uvseclistelems = this.closest(".uws-map-stage").querySelectorAll(".uws-map-list .uws-map-list-sec-" + uvseccode);
    Array.prototype.forEach.call(uvseclistelems, function (el, i) {
        el.classList.add("uwshigh");
    });

    uwsMapShowTooltip(this);
    if (this.classList.contains("uwsiteminactive")) return;
}

/*Map graph elem is hovered out*/
function uwsMapElemHoverOut() {
    const uvseccode = this.getAttribute("data-seccode");
    const uvsecelems = this.closest("svg").querySelectorAll(".uwshasitem.sec_" + uvseccode);
    Array.prototype.forEach.call(uvsecelems, function (el, i) {
        el.classList.remove("uwshigh");
    });

    const uvseclistelems = this.closest(".uws-map-stage").querySelectorAll(".uws-map-list .uws-map-list-sec-" + uvseccode);
    Array.prototype.forEach.call(uvseclistelems, function (el, i) {
        el.classList.remove("uwshigh");
    });

    uwsMapTryHideTooltip(this);
}

/*Map graph elem is clicked*/
function uwsMapElemClick() {
    const uvseccode = this.getAttribute("data-seccode");
    const uvsecitems = uwsMapGetItemsBySecid(uvseccode);

    if (this.classList.contains("uwsiteminactive")) return;

    if (uvsecitems.length > 1)//different items in section
        uwsMapSelItemPop(uvsecitems, uvseccode);
    else {
        const uvmascode = uvsecitems[0];
        const uvitem = uws_map.items[uvmascode];

        if (uvitem) {
            const uvmastercode = uvitem.mastercode;
            const uvitempresels = (uws_map.seatingtype == "table") ? { "sectionid": uvseccode.replace("SEC", ""), "locationid": uwsMapGetLocidbyClasses(this.classList.value.split(" ")) } : "";
            uwsMapShowItemBox(uvmastercode, uvitempresels);
        }
    }
}

//Show popup with items options
function uwsMapSelItemPop(uvsecitems, uvseccode = "") {
    if (!uws_mapsel_pop)//create pop if it doesn't exist
        uws_mapsel_pop = uwsCreatePop("uws-mapitsellist-pop");

    let uvmapselpop = uws_map.templates["map-itemslist-sel-pop"];
    let uvmapsellist = "";

    const uvpoptheme = (uws_map.poptheme) ? uws_map.poptheme : "uws-light";
    uws_mapsel_pop.classList.add(uvpoptheme);

    uvsecitems.forEach((el) => {
        const uvitem = uws_map.items[el];

        const uvmapselitem = uwsinvReplaceItemVars(uvitem, uws_map.templates["map-itemslist-sel-item"]);
        uvmapsellist += uvmapselitem;
    });

    const uvsecname = uws_map.sections[uvseccode].name;
    uvmapselpop = uvmapselpop.replace(/{sectionname}/g, uvsecname);
    uvmapselpop = uvmapselpop.replace(/{selitems}/g, uvmapsellist);

    uwsClearPopup(uws_mapsel_pop, uvmapselpop);
    setTimeout(function () { uwsFadePopup(uws_mapsel_pop); }, 100);
}

/*Add Classes to map graph*/
function uwsMapGraphClasses(uvmapelem) {
    const uvmapelems = uvmapelem.querySelectorAll(".uws-map-graph svg .haslocs");

    Array.prototype.forEach.call(uvmapelems, function (el, i) {
        const uvmapelemlocid = uwsMapGetLocidbyClasses(el.classList.value.split(" "));
        const uvmapelemsecid = uwsMapGetSecidByLocid(uvmapelemlocid);
        const uvmapelemitems = uwsMapGetItemsBySecid(uvmapelemsecid);

        const uvmapitemsexist = uwsMapItemsExist(uvmapelemitems);
        const uvmapitemsinactive = (uvmapitemsexist) ? uwsMapItemsInactive(uvmapelemitems) : 0;

        if (uvmapelemlocid && uvmapelemsecid && uvmapelemitems && uvmapitemsexist) {
            el.classList.add("uwshasitem");

            if (uvmapitemsinactive)
                el.classList.add("uwsiteminactive");
        }
        else
            el.classList.add("uwsnoitem");

        if (uvmapelemsecid) {
            el.setAttribute("data-seccode", uvmapelemsecid);
            el.classList.add("sec_" + uvmapelemsecid);
        }
    });
}

/*Check if sec items exist*/
function uwsMapItemsExist(uvmapelemitems) {
    let uvmapitemsexist = 0;

    if (uvmapelemitems) {
        uvmapelemitems.forEach(function (el) {
            if (uws_map && uws_map.items && uws_map.items[el] !== undefined)
                uvmapitemsexist = 1;
        });
    }

    return uvmapitemsexist;
}

/*Check if sec items is available */
function uwsMapItemsInactive(uvmapelemitems) {
    let uvmapitemsinactive = 0;

    if (uvmapelemitems) {
        uvmapelemitems.forEach(function (el) {
            if (uws_map.items[el].inactive === 1)
                uvmapitemsinactive = 1;
        });
    }

    return uvmapitemsinactive;
}

/*Search location for the item*/
function uwsMapGetLocidbyClasses(uvmapelemclasses) {
    let uvmapelemlocid = "";

    Array.prototype.forEach.call(uvmapelemclasses, function (el, i) {
        if (el.includes("loc_"))
            uvmapelemlocid = el.replace("loc_", "");
    });

    return uvmapelemlocid;
}

/*Get Sectionid by locationid*/
function uwsMapGetSecidByLocid(uvlocationid) {
    let uvsecid = "";

    if (typeof (uvlocationid) != "undefined") {
        if (typeof (uws_map["locations"]) != undefined && typeof (uws_map["locations"]["LOC" + uvlocationid]) != "undefined")
            uvsecid = "SEC" + uws_map["locations"]["LOC" + uvlocationid]["sectionid"];
    }

    return uvsecid;
}

/*Get items mastercodes by sectionid*/
function uwsMapGetItemsBySecid(uvsecid) {
    let uvitems = "";

    if (typeof (uvsecid) != "undefined" && uvsecid) {
        if (typeof (uws_map["secitems"]) != undefined && typeof (uws_map["secitems"][uvsecid]) != "undefined")
            uvitems = uws_map["secitems"][uvsecid];
    }

    return uvitems;
}

/*Close map tooltip*/
function uwsCloseMapTooltip(uvtooltip) {
    /*if (!uvtooltip.matches(":hover")) {*/
    uvtooltip.classList.remove("uwsactive", "uwsclosebomb");
    uvtooltip.classList.add("uwsjustclosed");

    const uvseccode = uvtooltip.getAttribute("data-seccode");
    const uvtooltiprelelems = document.querySelectorAll(".uws-map-list-sec-" + uvseccode);

    Array.prototype.forEach.call(uvtooltiprelelems, function (el, i) {
        el.classList.remove("uwshastooltipactive");
    });

    const uvmapsecelems = document.querySelector(".uws-map-stage").querySelectorAll(".uws-map-graph .uwshasitem.sec_" + uvseccode);
    Array.prototype.forEach.call(uvmapsecelems, function (el, i) {
        el.classList.remove("uwshighper");
    });

    setTimeout(function () {
        uvtooltip.classList.remove("uwsjustclosed");
    }, 10);
    /*}*/
}

/*Close all svg elems tooltips*/
function uwsMapHideAllTooltips() {
    const uvactivetooltips = document.querySelectorAll(".uws-mapitem-tooltip.uwsactive");
    Array.prototype.forEach.call(uvactivetooltips, function (el, i) {
        uwsCloseMapTooltip(el);
    });
}

/*Add hide tooltip bomb*/
function uwsMapTryHideTooltip(uvelem) {
    const uvseccode = uvelem.getAttribute("data-seccode");
    //const uvsecitems = uwsMapGetItemsBySecid(uvseccode);
    //const uvmastercode = uvsecitems[0];
    const uvtooltip = document.querySelector(".uws-mapitem-tooltip-" + uvseccode);

    if (uvtooltip) {
        uvtooltip.classList.add("uwsclosebomb");
        setTimeout(function () {
            if (uvtooltip.classList.contains("uwsclosebomb"))
                uwsCloseMapTooltip(uvtooltip);
        }, 100);
        //}, 1500);
    }
}

/*Mouseout map tooltip*/
function uwsMapTooltipMouseout() {
    const uvelem = this;
    uvelem.classList.add("uwsclosebomb");
    setTimeout(function () {
        if (uvelem.classList.contains("uwsclosebomb"))
            uwsCloseMapTooltip(uvelem);
    }, 100);
    //}, 1500);
}

/*Show tooltip on svg element*/
function uwsMapShowTooltip(uvelem) {
    const uvseccode = uvelem.getAttribute("data-seccode");
    const uvsecitems = uwsMapGetItemsBySecid(uvseccode);
    let uvtooltiphtml = uvmastercode = "";
    let uvtheme = (uws_map.theme) ? uws_map.theme : "uws-light";

    if (uvsecitems.length > 1) {
        const uvsecname = uws_map.sections[uvseccode].name;
        uvtooltiphtml = uws_map.templates["map-multiitem-tooltip-cont"];
        uvtooltiphtml = uvtooltiphtml.replace(/{secname}/g, uvsecname);
        let uvmultiitemitem = uws_map.templates["map-multiitem-tooltip-item"];
        let uvminguests = uvmaxguests = uvcurrencysymbol = uvstartingatprice = uvpaynow = "";

        uvsecitems.forEach(function (el) {
            const uvitem = uws_map.items[el];
            const uvitemcapacity = (uvitem.capacity) ? Number(uvitem.capacity) : 0;

            uvminguests = (uvminguests == "" || uvitemcapacity < uvminguests) ? uvitemcapacity : uvminguests;
            uvmaxguests = (uvmaxguests == "" || uvitemcapacity > uvmaxguests) ? uvitemcapacity : uvmaxguests;
            uvcurrencysymbol = uvitem.currency_symbol;
            uvstartingatprice = (uvstartingatprice == "" || uvitem.listprice < uvstartingatprice) ? uvitem.listprice : uvstartingatprice;
            uvpaynow = (uvpaynow == "" || uvitem.paynow < uvpaynow) ? uvitem.paynow : uvpaynow;
            //const uvmultiitemitemtempl = uws_map.templates["map-multiitem-tooltip-item"];
            //uvmultiitemlist += uwsinvReplaceItemVars(uvitem, uvmultiitemitemtempl);
        });

        const uvitemcapacitylabel = (uvmaxguests > 1) ? uwsFrontLang("guests") : uwsFrontLang("guest");

        const uvguestlabel = (uvminguests == uvmaxguests) ? uvminguests : uvminguests + " - " + uvmaxguests;
        //const uvpricingdisp = "Starting At";
        uvpricingdisp = "";
        //const uvfrontprice = (uvstartingatprice) ? uwsFrontformatMoney(uvstartingatprice, 1) : uvitem.listzero;
        const uvfrontprice = "";
        const uvpaynowprice = (uvpaynow) ? uwsFrontformatMoney(uvpaynow, 1) : "";
        //const uvpaynowtypeclass = (uvpaynow) ? "" : "uwspricelistzero";
        const uvpaynowtypeclass = "uwspricelistzero";
        uvpaynow = "";
        const uvitempaynowdiv = (uvpaynow) ? `<div class='uwspriceitem'><div class='uwspricing'>Pay Now</div><div class='uwsprice ${uvpaynowtypeclass}' data-symbol="${uvcurrencysymbol}">${uvpaynowprice}</div></div>` : "";

        uvmultiitemitem = uvmultiitemitem.replace(/{itemname}/g, uvsecname);
        uvmultiitemitem = uvmultiitemitem.replace(/{itemcapacity}/g, uvguestlabel);
        uvmultiitemitem = uvmultiitemitem.replace(/{itemcapacitylabel}/g, uvitemcapacitylabel);
        uvmultiitemitem = uvmultiitemitem.replace(/{frontprice}/g, uvfrontprice);
        uvmultiitemitem = uvmultiitemitem.replace(/{pricingdisplay}/g, uvpricingdisp);
        uvmultiitemitem = uvmultiitemitem.replace(/{currencysymbol}/g, uvcurrencysymbol);
        uvmultiitemitem = uvmultiitemitem.replace(/{itempaynowdiv}/g, uvitempaynowdiv);
        uvtooltiphtml = uvtooltiphtml.replace(/{mapitemstooltiplist}/g, uvmultiitemitem);
    }
    else {
        uvmastercode = uvsecitems[0];
        const uvitem = uws_map.items[uvmastercode];
        const uvtooltiptemplate = uws_map.templates["map-item-tooltip"];
        uvtooltiphtml = uwsinvReplaceItemVars(uvitem, uvtooltiptemplate);
    }

    if (uvtooltiphtml) {
        uwsMapHideAllTooltips();
        uvtooltiphtml = uvtooltiphtml.replace(/{seccode}/g, uvseccode);
        uvtooltiphtml = uvtooltiphtml.replace(/{theme}/g, uvtheme);

        if (!document.querySelector(".uws-mapitem-tooltip-" + uvseccode)) {
            document.body.insertAdjacentHTML("beforeend", uvtooltiphtml);
            document.querySelector(".uws-mapitem-tooltip-" + uvseccode).addEventListener("mouseout", uwsMapTooltipMouseout, false);
        }

        const uvelemrect = uvelem.getBoundingClientRect();
        const uvelemeleftwadjust = uvelemrect.left + (uvelemrect.width / 2);
        const uvtooltip = document.querySelector(".uws-mapitem-tooltip-" + uvseccode);
        let uvtooltipleftclass = "uwsposleft";
        let uvtooltiptopclass = "uwspostop";

        if (uvelemeleftwadjust < window.innerWidth / 2)
            uvtooltip.style.left = `${uvelemeleftwadjust - 27}px`;
        else {
            uvtooltip.style.left = `${uvelemeleftwadjust - uvtooltip.offsetWidth + 27}px`;
            uvtooltipleftclass = "uwspostright";
        }

        if (uvelemrect.top > uvtooltip.offsetHeight + 200)
            uvtooltip.style.top = `${uvelemrect.top - uvtooltip.offsetHeight - 15}px`;
        else {
            uvtooltip.style.top = `${uvelemrect.bottom + 15}px`;
            uvtooltiptopclass = "uwsposbottom"
        }

        uvtooltip.classList.remove("uwspostop", "uwsposleft", "uwsposbottom", "uwspostright", "uwsclosebomb");
        uvtooltip.classList.add("uwsactive", uvtooltipleftclass, uvtooltiptopclass);

        //add high light to map elements
        if (uws_map.seatingtype != "table") {
            const uvmapsecelems = uvelem.closest(".uws-map-stage").querySelectorAll(".uws-map-graph .uwshasitem.sec_" + uvseccode);
            Array.prototype.forEach.call(uvmapsecelems, function (el, i) {
                el.classList.add("uwshighper");
            });
        }
    }
}

/*Show item box*/
function uwsMapShowItemBox(uvmastercode, uvitempresels = "") {
    const uvitem = uws_map.items[uvmastercode];
    const uvmap = document.querySelector(".uws-map");
    const uvmapcontrols = uvmap.querySelector(".uws-map-controls");

    if (uvitem) {
        if (window.innerWidth <= 600) {
            const uvmastercode = uvitem.mastercode;
            uwsInvShowItemPop(uvmastercode, uvitempresels);
        }
        else {
            const uvitemboxtemplate = uws_map.templates["map-item-box"];

            if (typeof (uvitempresels) == "object") {
                if (uvitempresels.sectionid)
                    uvitem.selectedsectionid = uvitempresels.sectionid;
                if (uvitempresels.locationid)
                    uvitem.selectedlocationid = uvitempresels.locationid;
            }

            let uvitemboxhtml = uwsinvReplaceItemVars(uvitem, uvitemboxtemplate);

            if (!uvmap.querySelector(".uws-map-item-box")) {
                const uvitemboxcontainer = document.createElement("div");
                uvitemboxcontainer.classList.add("uws-map-item-box", "uwsjs-map-item-box-load", "uwsactive");
                uvitemboxcontainer.innerHTML = uvitemboxhtml;

                if (uvmapcontrols) {
                    uvmapcontrols.insertAdjacentElement("afterend", uvitemboxcontainer);
                } else {
                    uvmap.appendChild(uvitemboxcontainer);
                }
            }

            const uvitemboxcontelem = document.querySelector(".uws-map-item-box");

            if (!uvitemboxcontelem.querySelector(".uws-inv-item-" + uvitem.mastercode)) {
                uvitemboxcontelem.classList.remove("uwsactive");
                uvitemboxcontelem.innerHTML = uvitemboxhtml;

                uwsMapUpdateItemBoxUI();

                uvitemboxcontelem.classList.add("uwsactive");
            }
        }
    }
}

/*Update item UI*/
function uwsMapUpdateItemBoxUI() {
    const uvcartitems = (typeof (uws_inventory.cart) != "undefined" && typeof (uws_inventory.cart.cartitems) != "undefined") ? uws_inventory.cart.cartitems : "";
    const uvmapelem = document.querySelector(".uwsjs-loadmap");

    //Check btns states
    const uvitemslistelems = uvmapelem.querySelectorAll(".uwsinv-item");
    Array.prototype.forEach.call(uvitemslistelems, function (el, i) {
        const uvmastercode = el.getAttribute("data-mastercode");
        const uvnitemsadded = uwsInvGetItemInCartCount(uvcartitems, uvmastercode);

        if (el.querySelector(".uwsactions .uwsinvitembtncont")) {
            const uvbtnelem = el.querySelector(".uwsactions .uwsinvitembtncont");

            //Clear Item State
            uvbtnelem.innerHTML = uvbtnelem.getAttribute("data-keepcont") || uvbtnelem.innerHTML;
            uvbtnelem.setAttribute("data-keepcont", "");
            uvbtnelem.querySelector(".uwsjs-inv-item-select").classList.remove("uwsadded", "uwsdisabled");

            if (uvnitemsadded) { //Item is in cart {uvnitemsadded} contains the times it appears
                let uvbtnconttpl = uws_map.templates["item-added-btn-content"];
                const uvitem = (typeof (uws_map.items) != "undefined" && typeof (uws_map.items[uvmastercode]) != "undefined") ? uws_map.items[uvmastercode] : "";
                const uvtotalstock = (uvitem && typeof (uvitem.totalstock) != "undefined") ? uvitem.totalstock : "";
                const uvglobaltype = (uvitem && typeof (uvitem.globaltype) != "undefined") ? uvitem.globaltype : "";
                const uvmascode = (uvitem && typeof (uvitem.masteritemcode) != "undefined") ? uvitem.masteritemcode : "";
                const uvbtnkeepcont = (uvbtnelem.getAttribute("data-keepcont")) ? uvbtnelem.getAttribute("data-keepcont") : uvbtnelem.innerHTML;

                uvbtnconttpl = uvbtnconttpl.replace(/{itemcartcount}/g, uvnitemsadded);
                uvbtnconttpl = uvbtnconttpl.replace(/{mascode}/g, uvmascode);
                uvbtnconttpl = uvbtnconttpl.replace(/{mastercode}/g, uvmastercode);
                uvbtnconttpl = uvbtnconttpl.replace(/{actionclass}/g, "uwsjs-inv-cart-removemastercode");

                uvbtnelem.setAttribute("data-keepcont", uvbtnkeepcont);
                uvbtnelem.querySelector(".uwsjs-inv-item-select").classList.add("uwsadded");
                uvbtnelem.insertAdjacentHTML("beforeend", uvbtnconttpl);

                if (uvtotalstock <= uvnitemsadded || uvglobaltype == "admission")
                    uvbtnelem.querySelector(".uwsjs-inv-item-select").classList.add("uwsdisabled");
            }
        }

        /*if(el.querySelector(".uwsactions .uwsaddanother"))
            el.querySelector(".uwsactions .uwsaddanother").remove();
        
        if(uvnitemsadded){ //Item is in cart {uvnitemsadded} contains the times it appears
            if(el.querySelector(".uwsactions .uwsjs-inv-item-select")){
                const uvbtnelem = el.querySelector(".uwsactions .uwsjs-inv-item-select");
                let uvbtnconttpl = uws_map.templates["item-added-btn-content"];
                const uvbtnkeepcont = (uvbtnelem.getAttribute("data-keepcont")) ? uvbtnelem.getAttribute("data-keepcont") : uvbtnelem.innerHTML;

                uvbtnconttpl = uvbtnconttpl.replace(/{itemcartcount}/g, uvnitemsadded);

                uvbtnelem.setAttribute("data-keepcont", uvbtnkeepcont);
                uvbtnelem.classList.add("uwsadded");
                uvbtnelem.innerHTML = uvbtnconttpl;

                //"item-add-another"
                //Check if item is addable again
                const uvitem = (typeof(uws_map.items) != "undefined" && typeof(uws_map.items[uvmastercode]) != "undefined") ? uws_map.items[uvmastercode] : "";
                const uvlocstock = (uvitem && typeof (uvitem.locstock) != "undefined") ? uvitem.locstock : "";
                const uvglobaltype = (uvitem && typeof (uvitem.globaltype) != "undefined") ? uvitem.globaltype : "";
                const uvmascode = (uvitem && typeof (uvitem.masteritemcode) != "undefined") ? uvitem.masteritemcode : "";
                if(uvglobaltype == "seating" && uvlocstock > uvnitemsadded){
                    let uvaddanothertpl = uws_map.templates["item-add-another"];
                    uvaddanothertpl = uvaddanothertpl.replace(/{mascode}/g, uvmascode);
                    uvaddanothertpl = uvaddanothertpl.replace(/{mastercode}/g, uvmastercode);
                    uvaddanothertpl = uvaddanothertpl.replace(/{actionclass}/g, "uwsjs-inv-item-select");
                    uvbtnelem.insertAdjacentHTML("afterend", uvaddanothertpl);
                }
            }
        }
        else{
            if(el.querySelector(".uwsactions .uwsjs-inv-item-select")){
                const uvbtnelem = el.querySelector(".uwsactions .uwsjs-inv-item-select");
                const uvbtnkeepcont = (uvbtnelem.getAttribute("data-keepcont")) ? uvbtnelem.getAttribute("data-keepcont") : uvbtnelem.innerHTML;

                uvbtnelem.setAttribute("data-keepcont", "");
                uvbtnelem.classList.remove("uwsadded");
                uvbtnelem.innerHTML = uvbtnkeepcont;
            }
        }*/
    });
}

/*Update viewport vars*/
function uwsUpdateViewportVars() {
    //Mobile Viewport CSS Fix
    let vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--uwsvh', `${vh}px`);
    //Viewport - view cap
    const uvviewcap = document.querySelector(".uwsjs-viewcap");
    const uvvhnocap = (uvviewcap) ? (vh * 100) - uvviewcap.offsetHeight : vh * 100;
    document.documentElement.style.setProperty('--uwshnocap', `${uvvhnocap}px`);
}

/*Click on map item*/
function uwsMapCloseItemBox() {
    const uvitemboxelem = document.querySelector(".uws-map-item-box");

    if (uvitemboxelem) {
        uvitemboxelem.classList.remove("uwsactive");
        uvitemboxelem.innerHTML = "";
    }
}

/*Select Section*/
uwsClickListener(".uwsjs-map-section-select", function (e) {
    e.preventDefault();

    const uvseccode = this.getAttribute("data-seccode");
    const uvsecitems = uwsMapGetItemsBySecid(uvseccode);
    uwsMapSelItemPop(uvsecitems, uvseccode);
});

/*Blur list groups buttons*/
uwsClickListener(".uws-map-listgroup .uwsjs-toggle-collapse", function () {
    this.blur();
});

/*Show item popup from item selection popup*/
uwsClickListener(".uwsjs-map-popselitem", function (e) {
    e.preventDefault();

    const uvmastercode = this.getAttribute("data-mastercode");
    if (uvmastercode)
        uwsMapShowItemBox(uvmastercode);

    uwsHidePopup(uws_mapsel_pop, true);
});

/*Show tooltip*/
uwsClickListener(".uwsjs-open-section-listtooltip", function (e) {
    e.preventDefault();

    if (this.classList.contains("uwsinactive")) return;

    const uvseccode = this.getAttribute("data-seccode");
    const uvloccode = (this.getAttribute("data-loccode") && this.getAttribute("data-loccode") != "null") ? this.getAttribute("data-loccode") : "";
    const uvsecitems = uwsMapGetItemsBySecid(uvseccode);
    const uvtheme = (uws_map.theme) ? uws_map.theme : "uws-light";

    if (uvsecitems.length > 1) {
        uwsMapSelItemPop(uvsecitems, uvseccode);
    }
    else {
        const uvmastercode = uvsecitems[0];
        const uvitem = uws_map.items[uvmastercode];

        const uvactmastercode = uvitem.mastercode;
        uwsMapShowItemBox(uvactmastercode);

        /*if (window.innerWidth <= 700) {
            const uvactmastercode = uvitem.mastercode;
            uwsMapShowItemBox(uvactmastercode);
        }
        else {
            const uvtooltiptemplate = uws_map.templates["map-list-item-tooltip"];
            let uvtooltiphtml = uwsinvReplaceItemVars(uvitem, uvtooltiptemplate);

            if (uvtooltiphtml) {
                uvtooltiphtml = uvtooltiphtml.replace(/{seccode}/g, uvseccode);
                uvtooltiphtml = uvtooltiphtml.replace(/{theme}/g, uvtheme);
                uvtooltiphtml = uvtooltiphtml.replace(/{loccode}/g, uvloccode);

                if (!document.querySelector(".uws-maplistitem-tooltip-" + uvseccode))
                    document.body.insertAdjacentHTML("beforeend", uvtooltiphtml);

                const uvtooltip = document.querySelector(".uws-maplistitem-tooltip-" + uvseccode);

                if (!uvtooltip.classList.contains("uwsjustclosed")) {
                    //Calculate tooltip position
                    const uvelemrect = this.getBoundingClientRect();
                    const uvelemwidth = this.offsetWidth;
                    const uvcrolltop = window.pageYOffset || document.documentElement.scrollTop;

                    uvtooltip.style.width = `${uvelemwidth}px`;
                    uvtooltip.style.left = `${uvelemrect.left}px`;
                    uvtooltip.setAttribute("data-offset", window.pageYOffset);

                    if (uvelemrect.top > uvtooltip.offsetHeight + 40)
                        uvtooltip.style.top = `${uvelemrect.top - uvtooltip.offsetHeight + uvcrolltop}px`;
                    else
                        uvtooltip.style.top = `${uvelemrect.bottom + uvcrolltop}px`;

                    uvtooltip.classList.add("uwsactive");
                    this.classList.add("uwshastooltipactive");

                    //add high light to map elements
                    const uvlocid = (uvloccode) ? uvloccode.replace("LOC", "") : "";
                    const uvtargetselector = (uvlocid) ? ".uws-map-graph .uwshasitem.loc_" + uvlocid : ".uws-map-graph .uwshasitem.sec_" + uvseccode;
                    const uvmapsecelems = this.closest(".uws-map-stage").querySelectorAll(uvtargetselector);
                    Array.prototype.forEach.call(uvmapsecelems, function (el, i) {
                        el.classList.add("uwshighper");
                    });

                    this.blur();
                }
            }
        }*/
    }
});

/*Select Ecozone*/
uwsClickListener(".uwsjs-select-invmap-ecozone", function (e) {
    e.preventDefault();

    const uvecozone = this.getAttribute("data-ecozone");
    const uvmap = this.closest(".uws-map");
    const uvecozonename = this.getAttribute("data-ecozonename");

    if (uvmap) {
        const uvhomeecozone = uvmap.getAttribute("data-ecozone");
        uvmap.setAttribute("data-ecozone", uvecozone);
        uvmap.setAttribute("data-homename", uvecozonename);
        uvmap.setAttribute("data-homeecozone", uvhomeecozone);
        uwsMapLoad(uvmap);
    }
});

/*Ecozones Back*/
uwsClickListener(".uwsjs-map-ecozone-back", function (e) {
    e.preventDefault();

    const uvecozone = this.getAttribute("data-ecozone");
    const uvmap = this.closest(".uws-map");

    if (uvmap) {
        uvmap.setAttribute("data-ecozone", uvecozone);
        uvmap.setAttribute("data-homename", "");
        uvmap.setAttribute("data-homeecozone", "");
        uwsMapLoad(uvmap);
    }
});

uwsClickListener(".uwsjs-map-item-box-close", function (e) {
    e.preventDefault();

    uwsMapCloseItemBox();
});

/*Change Venue*/
uwsClickListener(".uwsjs-map-selectvenue", function (e) {
    const uvvenuecode = this.getAttribute("data-venuecode");
    const uvmapelem = this.closest(".uws-map");

    uvmapelem.setAttribute("data-venuecode", uvvenuecode);
    uwsMapLoad(uvmapelem);
});

uwsClickListener(".uwsjs-map-showcontrols", function (e) {
    e.preventDefault();

    this.closest(".uws-map").classList.add("uwscontrolsactive");
});

uwsClickListener(".uwsjs-map-hidecontrols", function (e) {
    e.preventDefault();

    this.closest(".uws-map").classList.remove("uwscontrolsactive");
});

uwsClickListener(".uwsjs-changemapview", function (e) {
    e.preventDefault();

    this.closest(".uwsviews").querySelector(".uwscurrent").classList.remove("uwscurrent");
    this.closest("li").classList.add("uwscurrent");

    const uvmamviews = this.closest(".uwsviews").querySelectorAll(".uwsjs-changemapview");
    Array.prototype.forEach.call(uvmamviews, function (el, i) {
        el.closest(".uws-map").classList.remove(el.getAttribute("data-view"));
    });

    this.closest(".uws-map").classList.add(this.getAttribute("data-view"));
    uwsPanResize();

    uwsMapUpdateMapListScroll(this.closest(".uws-map").querySelector(".uws-map-list"));
    uwsUpdateListFill(this.closest(".uws-map").querySelector(".uws-map-list"));
});

uwsClickListener(".uwsjs-map-selectecozone", function (e) {
    const uvecozone = this.getAttribute("data-ecozone");
    const uvnogroupings = this.getAttribute("data-nogroupings");
    const uvmap = this.closest(".uws-map");

    uvmap.setAttribute("data-ecozone", uvecozone);
    uvmap.setAttribute("data-nogroupings", uvnogroupings);

    if (uvmap)
        uwsMapLoad(uvmap);
});

uwsClickListener(".uwsjs-map-zoomin", function (e) {
    e.preventDefault();

    uwsMapZoomIn();
});

uwsClickListener(".uwsjs-map-zoomout", function (e) {
    e.preventDefault();

    uwsMapZoomOut();
});