//Requires: uwscore.js

var uws_invitem_pop, uws_bkcal_dp, uws_invsel_pop, uws_invbreak_pop, uws_invbreaksel_pop;
var uws_inventory_instance = 0;
var uws_inventory_cookiename = window.uws_inventory_cookiename || "uws_gcart";
uws_conf_inventory_cartcode = window.uws_conf_inventory_cartcode || "";
window.uws_inventory = window.uws_inventory || {};
var uws_itembottles = uws_bottles_selected = {};
var uws_has_bottles_selected = uws_total_bottles = 0;

var uws_gt_datp = uwsglobalitemFirstDayMonth();
var uwsloadedmonths = [];
var uws_gp_date;

uwsDOMReady(function () {
    const uvinventoryblocks = document.querySelectorAll(".uwsjs-loadeventinventory");
    Array.prototype.forEach.call(uvinventoryblocks, function (el, i) {
        uwsinvInitBlock(el);
    });

    uwsInitInventoryWidgets();

    const uvbookingcalendar = document.querySelector(".uws-booking-calendar");

    // Integration with calendar
    if (uvbookingcalendar) {
        uwsinvInitBKCalendar(uvbookingcalendar);

        if (uvbookingcalendar.querySelector(".uwsbookingcalinvcont").getAttribute("data-eventcode"))
            uvbookingcalendar.querySelector(".uwsjs-bkcal-bookdate").click();
    }

    window.addEventListener("resize", function () {
        uwsInvListScrollActions();
    });
    document.addEventListener("scroll", e => {
        uwsInvListScrollActions();
    });

    uwsCheckCartDrops();
});

/*Initial load of inventory*/
function uwsinventoryinitwidget(uvinvblock) {

    if (uvinvblock) {
        uws_inventory_instance++;

        var uvecozone = uvinvblock.getAttribute("data-ecozone").toString().padStart(3, '0');
        var wgpickedate = uvinvblock.getAttribute("data-date").replace(/-/g, '');
        var venuecode = uvinvblock.getAttribute("data-venuecode").replace("VEN", "EVE");
        var globaltype = uvinvblock.getAttribute("data-globaltype");
        var uvbooktypename = (uvinvblock.getAttribute("data-booktypename")) ? "&booktypename=" + uvinvblock.getAttribute("data-booktypename") : "";
        var uvaddmixeco = (uvinvblock.getAttribute("data-mixecozones")) ? "&mixecozones=" + uvinvblock.getAttribute("data-mixecozones") : "";
        const uvshoweventsdropdown = (uvinvblock.getAttribute("data-showeventsdropdown")) ? uvinvblock.getAttribute("data-showeventsdropdown") : 0;

        var errortitle = (uvinvblock.getAttribute("data-errortitle") != "") ? uvinvblock.getAttribute("data-errortitle") : "Something Went Wrong";
        var errorcontent = (uvinvblock.getAttribute("data-errorcontent") != "") ? uvinvblock.getAttribute("data-errorcontent") : "Check back later for updates.";

        uvinvblock.setAttribute("data-eventcode", venuecode + uvecozone + wgpickedate);

        const uveventcode = uvinvblock.getAttribute("data-eventcode");
        const uvinithtml = "<div class='uws-integration uws-inventory-stage uwsdy-cartactive-class uws-inventory-stage-" + uws_inventory_instance + " uwsloading' data-instance='" + uws_inventory_instance + "'><div class='uws-inventoryloader'><div class='uwsloadingmsg'><div class='uws-loader-uvicon'></div><div class='uwsloadingtxt'>Loading Experiences...</div></div><div class='uwsloadingbkt'></div><div class='uwsloadingbitem'></div><div class='uwsloadingbitem'></div><div class='uwsloadingbkt'></div></div><div class='uws-inventory-load'></div></div>";

        uvinvblock.innerHTML = uvinithtml;

        const uvinvstage = document.querySelector(".uws-inventory-stage-" + uws_inventory_instance);
        const uvintegration = (document.querySelector(".uws-booking-calendar")) ? `&integration=calendar` : '';

        const uvreturntempl = 1; //(typeof (uws_inventory.templates) != "undefined") ? 0 : 1;
        let uvinventoryload = uws_proxy + "&uvaction=uwspx_inventoryglobaltype";
        uvinventoryload = uvinventoryload + "&eventcode=" + uveventcode + "&cartcode=" + uwsInvGetCartCookie() + uvintegration + "&returntempl=" + uvreturntempl + "&globaltype=" + globaltype + "&showeventsdropdown=" + uvshoweventsdropdown + uvbooktypename + uvaddmixeco;

        // Add-On Venues
        const uvaddonvenueinv = (document.querySelector(".uwsjs-loadaddonvenue-widget")) ? 1 : 0;

        if (uvaddonvenueinv) {
            const uvaddonvenueelem = document.querySelector(".uwsjs-loadaddonvenue-widget");
            const uvaddonvenueparent = uvaddonvenueelem.closest(".uwsjs-loadeventinventory") || uvaddonvenueelem.closest(".uwsjs-loadeventinventorywidget");
            const uvhomeeventcode = (uvaddonvenueparent.getAttribute("data-homeeventcode")) ? uvaddonvenueparent.getAttribute("data-homeeventcode") : uveventcode;
            const uvmainvenuecode = uvaddonvenueparent.getAttribute("data-mainvenuecode");
            const uvvenuecode = uvaddonvenueparent.getAttribute("data-venuecode");
            const uvmicrocode = uvaddonvenueparent.getAttribute("data-microcode");
            const uvdate = uvaddonvenueparent.getAttribute("data-date");
            const uvmanagementid = uvaddonvenueparent.getAttribute("data-managementid");
            const uvglobaltype = uvaddonvenueparent.getAttribute("data-globaltype");
            const uvaddmixeco = (uvaddonvenueparent.getAttribute("data-mixecozones")) ? uvaddonvenueparent.getAttribute("data-mixecozones") : 0;

            uvinventoryload += "&addonvenues=1" + "&homeeventcode=" + uvhomeeventcode + "&mainvenuecode=" + uvmainvenuecode + "&venuecode=" + uvvenuecode + "&microcode=" + uvmicrocode + "&date=" + uvdate + "&managementid=" + uvmanagementid + "&globaltype=" + uvglobaltype + "&mixecozones=" + uvaddmixeco;
        }

        // @egt [UWS-7297]
        if(typeof urvenue_ws_inventory_vars !== "undefined" && urvenue_ws_inventory_vars.targetNonce) {
            uvinventoryload = uvinventoryload + "&uws_nonce=" + encodeURIComponent(urvenue_ws_inventory_vars.targetNonce);
        }

        let uvrequest = new XMLHttpRequest();
        uvrequest.open('GET', uvinventoryload, true);
        uvrequest.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                let uvresponse = this.response;
                uvresponse = JSON.parse(uvresponse);

                if (typeof (uvresponse.eventsel) != "undefined" && uvresponse.eventsel) {
                    uvinvblock.closest(".uws-inventory-widget").querySelector(".uws-invwidget-filters").classList.add("uwshaseventsel");
                    if (uvinvblock.closest(".uws-inventory-widget").querySelector(".uwseventsel")) {
                        uvinvblock.closest(".uws-inventory-widget").querySelector(".uwseventsel").innerHTML = uvresponse.eventsel;
                    }
                    uwsInitDrops();
                }
                else {
                    uvinvblock.closest(".uws-inventory-widget").querySelector(".uws-invwidget-filters").classList.remove("uwshaseventsel");
                    if (uvinvblock.closest(".uws-inventory-widget").querySelector(".uwseventsel")) {
                        uvinvblock.closest(".uws-inventory-widget").querySelector(".uwseventsel").innerHTML = "";
                    }
                }

                uwsinvListProcessResponse(uvinvstage, uvresponse);

                if (uvinvblock.querySelector('.uws-inventory-list-noitmes .uwstitle')) {
                    uvinvblock.querySelector('.uws-inventory-list-noitmes .uwstitle').innerHTML = '<i class="uwsicon-warning-empty"></i>' + errortitle;
                    uvinvblock.querySelector('.uws-inventory-list-noitmes .uwstext').textContent = errorcontent;
                }

                if (typeof (uvhookInventoryListLoaded) == "function")
                    uvhookInventoryListLoaded(uvresponse);
            } else {
                //console.log("UVJS Error: Server returned an error");
                const uvsverror = `
                    <div class='uws-inventory-list-noitmes'>
                        <div class='uwstitle'><i class='uwsicon-warning-empty shamana'></i> `+ errortitle + `</div>
                        <div class='uwstext'>`+ errorcontent + `</div>
                    </div>
                `;

                uvinvstage.querySelector(".uws-inventory-load").innerHTML = uvsverror;

                if (uvinvstage.querySelector(".uws-inventory-load").querySelectorAll(".uws-booktype-item").length == 1)
                    uvinvstage.querySelector(".uws-inventory-load").querySelector(".uws-booktype-item").classList.add("uwsactive");

                uvinvstage.classList.remove("uwsloading");
            }
        };
        uvrequest.onerror = function () {
            console.log("UVJS Error: Request Error");
        };
        uvrequest.send();
    }
}

/*Select Venue on Inventory Widget*/
uwsClickListener(".uwsjs-inventorywidget-selectevent", function (e) {
    const uveventcode = this.getAttribute("data-eventcode");
    const uvdate = this.getAttribute("data-date");
    let uvecozone = this.getAttribute("data-ecozone");
    uvecozone = uvecozone.replace("ECZ", "");
    const uvblock = this.closest(".uws-inventory-widget ").querySelector(".uwsjs-loadeventinventorywidget");
    uvblock.setAttribute("data-eventcode", uveventcode);
    uvblock.setAttribute("data-date", uvdate);
    uvblock.setAttribute("data-ecozone", uvecozone);
    uwsinventoryinitwidget(uvblock);
});

/**
 * Loads the item inquiry form for a given inventory item.
 * 
 * If the form exists, updates it with the current party size and switches the view.
 * Otherwise, dynamically creates and fetches the form content from the server.
 * 
 * @param {Object} uvitem - The inventory item for the inquiry form.
 */
function uwsLoadItemInquireForm(uvitem) {
    const uvitempopcontentelem = uws_invitem_pop.querySelector(".uws-itempop-content");
    const uvguests = (uws_inventory.popitemsels.guests) ? uws_inventory.popitemsels.guests : 1;

    if (uvitempopcontentelem.querySelector(".uwsiteminqform")) {//if form already exist
        if (uvitempopcontentelem.querySelector(".uwsiteminqform #uwsinqpartysize"))
            uvitempopcontentelem.querySelector(".uwsiteminqform #uwsinqpartysize").value = uvguests;

        uwsSwitchViewSibling(uvitempopcontentelem.querySelector(".uws-itempop-selscreen.uwsiteminqform"), uvitempopcontentelem.querySelector(".uws-itempop-main"));
    }
    else {
        let uvselscreenelem = document.createElement("div");
        uvselscreenelem.classList.add("uws-itempop-selscreen", "uwsiteminqform");

        uvitempopcontentelem.appendChild(uvselscreenelem);
        uvitempopcontentelem.classList.add("uwsloading");

        //load inquire
        let uvloadinqitem = uws_inventory.proxies["item-inquireform"];
        uvloadinqitem = uvloadinqitem + uwsInvGetItemCartVars(uvitem);

        // @egt [UWS-7297]
        if(typeof urvenue_ws_inventory_vars !== "undefined" && urvenue_ws_inventory_vars.targetNonce) {
            uvloadinqitem = uvloadinqitem + "&uws_nonce=" + encodeURIComponent(urvenue_ws_inventory_vars.targetNonce);
        }

        let uvrequest = new XMLHttpRequest();
        uvrequest.open('GET', uvloadinqitem, true);
        uvrequest.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                let uvresponse = this.response;
                uvresponse = JSON.parse(uvresponse);

                if (typeof (uvresponse.html) != "undefined") {
                    uvselscreenelem.innerHTML = uvresponse.html;
                    uwsInitItemInquireForm(uvselscreenelem);
                }
                uvitempopcontentelem.classList.remove("uwsloading");

                uwsSwitchViewSibling(uvitempopcontentelem.querySelector(".uws-itempop-selscreen.uwsiteminqform"), uvitempopcontentelem.querySelector(".uws-itempop-main"));

                if (typeof (uvhookItemInquireLoaded) == "function" && typeof (uvresponse) != "undefined")
                    uvhookItemInquireLoaded(uvitem);
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

/*Select Add-On Venue on List*/
uwsClickListener(".uwsjs-select-invlist-venue", function (e) {
    e.preventDefault();

    const uvdate = this.getAttribute("data-date");
    const uvmainvenuecode = this.closest(".uws-booktype-nodetype-addonvenue").getAttribute("data-venuecode");
    const uvvenuecode = this.getAttribute("data-venuecode");
    const uvmicrocode = this.getAttribute("data-microcode");
    const uvmanageentid = this.getAttribute("data-managementid");
    const uvaddmixeco = this.getAttribute("data-mixecozones");
    const uvaddonvenues = this.getAttribute("data-addonvenues");

    let uvglobaltype = (this.getAttribute("data-globaltype")) ? this.getAttribute("data-globaltype") : "";
    if (uvglobaltype === "urvenue") uvglobaltype = "";

    const uvloadinvelem = this.closest(".uwsjs-loadeventinventory") || this.closest(".uwsjs-loadeventinventorywidget");

    if (uvloadinvelem) {
        const uvhomeeventcode = uvloadinvelem.getAttribute("data-eventcode");
        uvloadinvelem.setAttribute("data-homeeventcode", uvhomeeventcode);
        uvloadinvelem.setAttribute("data-homename", "");
        uvloadinvelem.setAttribute("data-eventcode", uvhomeeventcode);
        uvloadinvelem.setAttribute("data-date", uvdate);
        uvloadinvelem.setAttribute("data-mainvenuecode", uvmainvenuecode);
        uvloadinvelem.setAttribute("data-venuecode", uvvenuecode);
        uvloadinvelem.setAttribute("data-microcode", uvmicrocode);
        uvloadinvelem.setAttribute("data-managementid", uvmanageentid);
        uvloadinvelem.setAttribute("data-mixecozones", uvaddmixeco);
        uvloadinvelem.setAttribute("data-globaltype", uvglobaltype);
        uvloadinvelem.setAttribute("data-addonvenues", uvaddonvenues);

        uwsinvInitAddOnVenueBlock(uvloadinvelem);
    }
});

/* Add-On Venue List Back to Home*/
uwsClickListener(".uwsjs-list-addonvenues-back", function (e) {
    e.preventDefault();

    const uveventcode = this.getAttribute("data-eventcode");
    const uvloadinvelem = this.closest(".uwsjs-loadeventinventory") || this.closest(".uwsjs-loadeventinventorywidget");

    if (uvloadinvelem) {
        const uvhomeeventcode = "";

        if (uvloadinvelem.classList.contains("uwsjs-loadaddonvenue-widget") && uvloadinvelem.classList.contains("uwsaddonvenue-widget"))
            uvloadinvelem.classList.remove("uwsjs-loadaddonvenue-widget", "uwsaddonvenue-widget");

        uvloadinvelem.setAttribute("data-homeeventcode", uvhomeeventcode);
        uvloadinvelem.setAttribute("data-homename", "");
        uvloadinvelem.setAttribute("data-eventcode", uveventcode);

        uvloadinvelem.removeAttribute("data-mainvenuecode");
        uvloadinvelem.removeAttribute("data-venuecode");
        uvloadinvelem.removeAttribute("data-microcode");
        uvloadinvelem.removeAttribute("data-managementid");
        uvloadinvelem.removeAttribute("data-mixecozones");
        uvloadinvelem.removeAttribute("data-date");
        uvloadinvelem.removeAttribute("data-enddate");
        uvloadinvelem.removeAttribute("data-globaltype");
        uvloadinvelem.removeAttribute("data-addonvenues");

        uwsinvInitBlock(uvloadinvelem);
    }
});

/*Add scope item to cart*/
uwsClickListener(".uwsjs-item-addtocart", function (e) {
    e.preventDefault();

    if (uwsinvItemSelsValid(uws_inventory.popitem, uws_inventory.popitemsels)) {
        uws_inventory.popitem.selectedforcenew = this.getAttribute("data-forcenew") || "";

        uwsInvAddItemToCart();
    }
});

/*Remove current cart and add scope item to cart*/
uwsClickListener(".uwsjs-item-addtonewcart", function (e) {
    e.preventDefault();

    if (uws_inventory && uws_inventory.cart && uws_inventory.cart.cartmanagementid)
        uws_inventory.cart.cartmanagementid = "";

    //uwsSetCookie(uws_inventory_cookiename, "", 7);
    uwsInvSetCartCookie("");
    uwsShowGLoader();

    if (uwsinvItemSelsValid(uws_inventory.popitem, uws_inventory.popitemsels))
        uwsInvAddItemToCart();
});

/*Select Ecozone on List*/
uwsClickListener(".uwsjs-select-invlist-ecozone", function (e) {
    e.preventDefault();

    const uveventcode = this.getAttribute("data-eventcode");
    const uvecozonename = this.getAttribute("data-ecozonename");
    const uvloadinvelem = this.closest(".uwsjs-loadeventinventory") || this.closest(".uwsjs-loadeventinventorywidget");

    if (uvloadinvelem) {
        const uvhomeeventcode = uvloadinvelem.getAttribute("data-eventcode");
        uvloadinvelem.setAttribute("data-homeeventcode", uvhomeeventcode);
        uvloadinvelem.setAttribute("data-homename", uvecozonename);
        uvloadinvelem.setAttribute("data-eventcode", uveventcode);
        uwsinvInitBlock(uvloadinvelem);
    }
});

/*Ecozone List Back to Home*/
uwsClickListener(".uwsjs-list-ecozone-back", function (e) {
    e.preventDefault();

    const uveventcode = this.getAttribute("data-eventcode");
    const uvloadinvelem = this.closest(".uwsjs-loadeventinventory") || this.closest(".uwsjs-loadeventinventorywidget");

    if (uvloadinvelem) {
        const uvhomeeventcode = "";
        uvloadinvelem.setAttribute("data-homeeventcode", uvhomeeventcode);
        uvloadinvelem.setAttribute("data-homename", "");
        uvloadinvelem.setAttribute("data-eventcode", uveventcode);
        uwsinvInitBlock(uvloadinvelem);
    }
});

/*Add scope item to cart*/
uwsClickListener(".uwsjs-item-addtocart-andcheck", function (e) {
    e.preventDefault();

    if (uwsinvItemSelsValid(uws_inventory.popitem, uws_inventory.popitemsels)) {
        uwsinvSetPopitemSelection("gotocheck", 1);
        uwsInvAddItemToCart();
    }
});

/*Cart drop side check*/
uwsClickListener(".uwsjs-sidecheckthis", function (e) {
    e.preventDefault();

    const uvifrurl = this.getAttribute("href");
    if (uvifrurl) {
        uvcheckout.setOptions({
            gocheckurl: uvifrurl,
        });
        uvcheckout.gocheck();
    }
});

/*Toggle Booktypes Containers*/
uwsClickListener(".uwsjs-booktypetoggle", function (e) {
    e.preventDefault();

    if (this.closest(".uws-booktype-item").classList.contains("uwsactive")) {
        this.closest(".uws-booktype-item").classList.remove("uwsactive");
        this.closest(".uws-booktype-item").querySelector(".uws-bootypelist-body").style.maxHeight = "0px";
    }
    else {
        let uveventitemlistheight = this.closest(".uws-booktype-item").querySelector(".uws-bootypelist-inner").clientHeight;
        uveventitemlistheight += 50;

        this.closest(".uws-booktype-item").querySelector(".uws-bootypelist-body").style.maxHeight = uveventitemlistheight + "px";
        this.closest(".uws-booktype-item").classList.add("uwsactive");
    }

    //Calculate parent new height
    if (this.closest(".uws-bootypelist-inner")) {
        let uvparentheight = 50;
        const uvchildrenelems = this.closest(".uws-bootypelist-inner").querySelectorAll(".uws-booktype-item.uwsactive");
        const uvchildrenbtnelems = this.closest(".uws-bootypelist-inner").querySelectorAll(".uwsjs-booktypetoggle");

        Array.prototype.forEach.call(uvchildrenelems, function (el, i) {
            let uvchilitemheight = el.querySelector(".uws-bootypelist-inner").clientHeight;
            uvchilitemheight += 50;
            uvparentheight += uvchilitemheight;
        });

        Array.prototype.forEach.call(uvchildrenbtnelems, function (el, i) {
            let uvchilbtnitemheight = el.clientHeight;
            uvchilbtnitemheight += 50;
            uvparentheight += uvchilbtnitemheight;
        });

        this.closest(".uws-bootypelist-body").style.maxHeight = uvparentheight + "px";
    }

    setTimeout(function () {
        uwsInvListScrollActions();
    }, 310);
});

/*Show item 360 tourlink */
uwsClickListener(".uwsjs-inv-item-showtourlink", function (e) {
    const uvtourview = this.getAttribute("data-view");

    if (uvtourview) {
        uwsShowGLoader();

        let uviframe = document.createElement("iframe");
        uviframe.className = 'uv-360-tour';
        uviframe.marginwidth = '0';
        uviframe.marginheight = '0';
        uviframe.align = 'middle';
        uviframe.border = '0';
        uviframe.frameborder = '0';
        uviframe.allowtransparency = 'true';
        uviframe.allowfullscreen = 'true';
        uviframe.webkitallowfullscreen = 'true';
        uviframe.mozallowfullscreen = 'true';
        uviframe.oallowfullscreen = 'true';
        uviframe.msallowfullscreen = 'true';
        uviframe.scrolling = 'no';
        uviframe.src = uvtourview;
        uviframe.onload = function () {
            $('.uv-360-loading').removeClass('uv-360-loading');
        };
        let uviframehtml = uviframe.outerHTML;

        uws_mgs_pop.classList.add("uws-pop-tourlink");

        setTimeout(() => {
            uwsHideGLoader();

            uwsClearPopup(uws_mgs_pop, uviframehtml);
            uwsFadePopup(uws_mgs_pop);
        }, 300);
    }
});

/*Show item info*/
uwsClickListener(".uwsjs-inv-item-showinfo", function (e) {
    const uvmastercode = this.getAttribute("data-mastercode");

    if (uvmastercode) {
        const uvisfrompop = (this.closest(".uws-inventory-item-pop")) ? 1 : 0;
        uwsInvLoadShowItemInfoPop(uvmastercode, uvisfrompop);
    }
});

/*Show ecoitem info*/
uwsClickListener(".uwsjs-inv-ecoitem-showinfo", function (e) {
    e.preventDefault();

    const uvmascode = this.getAttribute("data-mascode");
    const uvecoitems = uws_inventory.plainecolist[uvmascode];

    if (Object.keys(uvecoitems).length > 1) {
        uwsInvSelItemPop(uvecoitems);
    }
    else if (Object.keys(uvecoitems).length == 1) {
        const uvmastercode = uvecoitems[Object.keys(uvecoitems)[0]];

        uwsInvLoadShowItemInfoPop(uvmastercode);
        //uwsInvShowItemInfoPop(uvmastercode);
    }
});

/*Show Inventory Item Pop*/
uwsClickListener(".uwsjs-inv-item-select", function (e) {
    e.preventDefault();

    const uvmastercode = this.getAttribute("data-mastercode");
    if (uvmastercode) {
        if (typeof (uws_mgs_pop) == "object")
            uwsHidePopup(uws_mgs_pop, 1);
        if (typeof (uws_invsel_pop) == "object")
            uwsHidePopup(uws_invsel_pop, 1);
        const uvseccode = (this.getAttribute("data-seccode")) ? this.getAttribute("data-seccode") : "";
        const uvloccode = (this.getAttribute("data-loccode") && this.getAttribute("data-loccode") != "null") ? this.getAttribute("data-loccode") : "";
        let uvitempresels = (uvloccode && uvloccode) ? { "sectionid": uvseccode.replace("SEC", ""), "locationid": uvloccode.replace("LOC", "") } : {};

        const uvforcenew = (this.getAttribute("data-forcenew")) ? this.getAttribute("data-forcenew") : "";
        if (uvforcenew)
            uvitempresels = { "forcenew": uvforcenew };

        uwsInvShowItemPop(uvmastercode, uvitempresels);
    }
});

/*Remove Item By Mastercode*/
uwsClickListener(".uwsjs-inv-cart-removemastercode", function (e) {
    e.preventDefault();

    const uvmastercode = this.getAttribute("data-mastercode");
    uwsInvRemoveCartItemByMastercode(uvmastercode);
});

/*Remove Cart Items*/
uwsClickListener(".uwsjs-cart-clearall", function (e) {
    e.preventDefault();

    uwsInvRemoveCartAllItems();
});

/*Show ecoitem selection*/
uwsClickListener(".uwsjs-inv-ecoitem-select", function (e) {
    e.preventDefault();

    const uvmascode = this.getAttribute("data-mascode");
    const uvecoitems = uws_inventory.plainecolist[uvmascode];

    if (Object.keys(uvecoitems).length > 1) {
        uwsInvSelItemPop(uvecoitems);
    }
    else if (Object.keys(uvecoitems).length == 1) {
        const uvmastercode = uvecoitems[Object.keys(uvecoitems)[0]];

        let uvitempresels = "";
        const uvforcenew = (this.getAttribute("data-forcenew")) ? this.getAttribute("data-forcenew") : "";
        if (uvforcenew)
            uvitempresels = { "forcenew": uvforcenew };

        uwsInvShowItemPop(uvmastercode, uvitempresels);
    }
});

/* Show ecoitem price breakdown */
uwsClickListener(".uwsjs-inv-ecoitem-pricing", function (e) {
    e.preventDefault();

    const uvmascode = this.getAttribute("data-mascode");
    const uvecoitems = uws_inventory.plainecolist[uvmascode];

    if (Object.keys(uvecoitems).length > 1) {
        uwsInvSelItemPop(uvecoitems, "pricingbreakdown");
    }
    else if (Object.keys(uvecoitems).length == 1) {
        const uvmastercode = uvecoitems[Object.keys(uvecoitems)[0]];
        //uwsInvShowItemPop(uvmastercode, "pricingbreakdown");
        uwsInvShowItemListBreakdown(uvmastercode);
    }
});

/* Show item price breakdown */
uwsClickListener(".uwsjs-inv-item-pricing", function (e) {
    e.preventDefault();

    const uvmastercode = this.getAttribute("data-mastercode");
    uwsInvShowItemListBreakdown(uvmastercode);
    //uwsInvShowItemPop(uvmastercode, "pricingbreakdown");
});

/*Show (load if needed) Item Inquire Form*/
uwsClickListener(".uwsjs-item-inquire", function (e) {
    e.preventDefault();

    const uvitem = uws_inventory.popitem;

    uwsLoadItemInquireForm(uvitem);
});

/*Show (load if needed) OpenTable Selection*/
uwsClickListener(".uwsjs-show-otselect", function (e) {
    e.preventDefault();
    const uvitem = uws_inventory.popitem;
    const uvitempopcontentelem = this.closest(".uwsinv-item").querySelector(".uws-itempop-content");

    if (uvitempopcontentelem.querySelector(".uwsottimeslist")) {//if times already exist
        uwsSwitchViewSibling(uvitempopcontentelem.querySelector(".uws-itempop-selscreen.uwsselottime"), uvitempopcontentelem.querySelector(".uws-itempop-main"));
    }
    else {//Times are not loaded
        let uvselscreenelem = document.createElement("div");
        uvselscreenelem.classList.add("uws-itempop-selscreen", "uwsselottime");

        uvitempopcontentelem.appendChild(uvselscreenelem);
        uvitempopcontentelem.classList.add("uwsloading");

        //load ot time
        let uvloadottimes = uws_inventory.proxies["item-getottimes"];
        uvloadottimes = uvloadottimes + uwsInvGetItemCartVars(uvitem) + "&otid=" + uws_inventory.popitem.info.opentable.otid + "&resatt=" + uws_inventory.popitem.info.opentable.resatt;

        // @egt [UWS-7297]
        if(typeof urvenue_ws_inventory_vars !== "undefined" && urvenue_ws_inventory_vars.targetNonce) {
            uvloadottimes = uvloadottimes + "&uws_nonce=" + encodeURIComponent(urvenue_ws_inventory_vars.targetNonce);
        }

        let uvrequest = new XMLHttpRequest();
        uvrequest.open('GET', uvloadottimes, true);
        uvrequest.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                let uvresponse = this.response;
                uvresponse = JSON.parse(uvresponse);

                if (typeof (uvresponse.html) != "undefined") {
                    uvselscreenelem.innerHTML = uvresponse.html;
                }
                uvitempopcontentelem.classList.remove("uwsloading");

                uwsSwitchViewSibling(uvitempopcontentelem.querySelector(".uws-itempop-selscreen.uwsselottime"), uvitempopcontentelem.querySelector(".uws-itempop-main"));

                if (typeof (uvhookItemOTTimesLoaded) == "function" && typeof (uvresponse) != "undefined")
                    uvhookItemOTTimesLoaded(uvresponse);
            } else {
                console.log("UVJS Error: Server returned an error");
            }
        };
        uvrequest.onerror = function () {
            console.log("UVJS Error: Request Error");
        };
        uvrequest.send();
    }
});

uwsClickListener(".uwsjs-show-bk4select", function (e) {
    e.preventDefault();
    const uvitem = uws_inventory.popitem;
    const uvitempopcontentelem = this.closest(".uwsinv-item").querySelector(".uws-itempop-content");

    if (uvitempopcontentelem.querySelector(".uwsottimeslist")) {//if times already exist
        uwsSwitchViewSibling(uvitempopcontentelem.querySelector(".uws-itempop-selscreen.uwsselottime"), uvitempopcontentelem.querySelector(".uws-itempop-main"));
    }
    else {//Times are not loaded
        let uvselscreenelem = document.createElement("div");
        uvselscreenelem.classList.add("uws-itempop-selscreen", "uwsselottime");

        uvitempopcontentelem.appendChild(uvselscreenelem);
        uvitempopcontentelem.classList.add("uwsloading");

        //load ot time
        let uvloadottimes = uws_inventory.proxies["item-getbk4times"];
        uvloadottimes = uvloadottimes + uwsInvGetItemCartVars(uvitem) + "&ext_datajson=" + uws_inventory.popitem.info.ext_datajson;

        // @egt [UWS-7297]
        if(typeof urvenue_ws_inventory_vars !== "undefined" && urvenue_ws_inventory_vars.targetNonce) {
            uvloadottimes = uvloadottimes + "&uws_nonce=" + encodeURIComponent(urvenue_ws_inventory_vars.targetNonce);
        }

        let uvrequest = new XMLHttpRequest();
        uvrequest.open('GET', uvloadottimes, true);
        uvrequest.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                let uvresponse = this.response;
                uvresponse = JSON.parse(uvresponse);

                if (typeof (uvresponse.html) != "undefined") {
                    uvselscreenelem.innerHTML = uvresponse.html;
                }
                uvitempopcontentelem.classList.remove("uwsloading");

                uwsSwitchViewSibling(uvitempopcontentelem.querySelector(".uws-itempop-selscreen.uwsselottime"), uvitempopcontentelem.querySelector(".uws-itempop-main"));

                if (typeof (uvhookItemOTTimesLoaded) == "function" && typeof (uvresponse) != "undefined")
                    uvhookItemOTTimesLoaded(uvresponse);
            } else {
                console.log("UVJS Error: Server returned an error");
            }
        };
        uvrequest.onerror = function () {
            console.log("UVJS Error: Request Error");
        };
        uvrequest.send();
    }
});

/*Show (load if needed) Item Bottles Selection*/
uwsClickListener(".uwsjs-show-bottleselect", function (e) {
    e.preventDefault();

    const uvbottlesselelem = this.closest(".uwsinv-item").querySelector(".uws-bottle-text");
    const uvbottlestotalelem = this.closest(".uwsinv-item").querySelector(".uwsdy-bottlestotal");

    const uvitem = uws_inventory.popitem;
    const uvitempopcontentelem = this.closest(".uwsinv-item").querySelector(".uws-itempop-content");

    const uvvenueid = (uvitem.info.venuecode) ? uvitem.info.venuecode.replace("VEN", "") : uws_inventory.popitem.info.venuecode.replace("VEN", "");
    const uvcurrencysymbol = (uvitem.info.currency_symbol) ? uvitem.info.currency_symbol : uws_inventory.popitem.info.currency_symbol;

    if (uvitempopcontentelem.querySelector(".uws-bottles-list")) {//if bottles already exist
        uwsSwitchViewSibling(uvitempopcontentelem.querySelector(".uws-itempop-selscreen.uwsselbottle"), uvitempopcontentelem.querySelector(".uws-itempop-main"));

        if (uvbottlesselelem) {
            uvbottlesselelem.innerHTML = "";
            if (uvbottlestotalelem) uvbottlestotalelem.innerHTML = "";
        }
    }
    else {//Bottles are not loaded
        let uvselscreenelem = document.createElement("div");
        uvselscreenelem.classList.add("uws-itempop-selscreen", "uwsselbottle");

        uvitempopcontentelem.appendChild(uvselscreenelem);
        uvitempopcontentelem.classList.add("uwsloading");

        //load bottles proxy
        let uvloadbottles = uws_inventory.proxies["item-getbottles"];
        uvloadbottles = uvloadbottles + uwsInvGetItemCartVars(uvitem) + "&venueid=" + uvvenueid + "&currencysymbol=" + uvcurrencysymbol;

        // @egt [UWS-7297]
        if(typeof urvenue_ws_inventory_vars !== "undefined" && urvenue_ws_inventory_vars.targetNonce) {
            uvloadbottles = uvloadbottles + "&uws_nonce=" + encodeURIComponent(urvenue_ws_inventory_vars.targetNonce);
        }

        let uvrequest = new XMLHttpRequest();
        uvrequest.open('GET', uvloadbottles, true);
        uvrequest.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                let uvresponse = this.response;
                uvresponse = JSON.parse(uvresponse);

                if (typeof (uvresponse.html) != "undefined") {
                    uvselscreenelem.innerHTML = uvresponse.html;
                }
                if (typeof (uvresponse.menubottles) != "undefined") {
                    uws_itembottles = uvresponse.menubottles;
                }

                // add saved bottles from cookie
                if (!uws_has_bottles_selected) uwsAddSavedBottles();

                uvitempopcontentelem.classList.remove("uwsloading");

                uwsSwitchViewSibling(uvitempopcontentelem.querySelector(".uws-itempop-selscreen.uwsselbottle"), uvitempopcontentelem.querySelector(".uws-itempop-main"));

                if (typeof (uvhookItemBottlesLoaded) == "function" && typeof (uvresponse) != "undefined")
                    uvhookItemBottlesLoaded(uvresponse);
            } else {
                console.log("UVJS Error: Server returned an error");
            }
        };
        uvrequest.onerror = function () {
            console.log("UVJS Error: Request Error");
        };
        uvrequest.send();
    }
});

/*
Show (load if needed) Select Times Selection (not opentable)
(updated @egt it now sends each time with guests so we only get the times with those slots)
*/
uwsClickListener(".uwsjs-show-timeselect", function (e) {
    e.preventDefault();
    const uvitem = uws_inventory.popitem;
    const uvitempopcontentelem = this.closest(".uwsinv-item").querySelector(".uws-itempop-content");

    //@egt get timeslots each time so we show them based on the guests selected and stock 
    let uvselscreenelem;

    if (!uvitempopcontentelem.querySelector(".uwsottimeslist")) {
        uvselscreenelem = document.createElement("div");
        uvselscreenelem.classList.add("uws-itempop-selscreen", "uwsselottime");

        uvitempopcontentelem.appendChild(uvselscreenelem);
    } else
        uvselscreenelem = uvitempopcontentelem.querySelector(".uws-itempop-selscreen.uwsselottime");

    uvitempopcontentelem.classList.add("uwsloading");

    //load ot time
    let uvloadottimes = uws_inventory.proxies["item-gettimes"];
    uvloadottimes = uvloadottimes + uwsInvGetItemCartVars(uvitem) + "&guests=" + uws_inventory.popitemsels.guests;

    // @egt [UWS-7297]
    if(typeof urvenue_ws_inventory_vars !== "undefined" && urvenue_ws_inventory_vars.targetNonce) {
        uvloadottimes = uvloadottimes + "&uws_nonce=" + encodeURIComponent(urvenue_ws_inventory_vars.targetNonce);
    }

    let uvrequest = new XMLHttpRequest();
    uvrequest.open('GET', uvloadottimes, true);
    uvrequest.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            let uvresponse = this.response;
            uvresponse = JSON.parse(uvresponse);

            if (typeof (uvresponse.html) != "undefined") {
                uvselscreenelem.innerHTML = uvresponse.html;
            }
            uvitempopcontentelem.classList.remove("uwsloading");

            uwsSwitchViewSibling(uvitempopcontentelem.querySelector(".uws-itempop-selscreen.uwsselottime"), uvitempopcontentelem.querySelector(".uws-itempop-main"));

            if (typeof (uvhookItemTimesLoaded) == "function" && typeof (uvresponse) != "undefined")
                uvhookItemTimesLoaded(uvresponse);
        } else {
            console.log("UVJS Error: Server returned an error");
        }
    };

    uvrequest.onerror = function () {
        console.log("UVJS Error: Request Error");
    };

    uvrequest.send();
});

/*Show main view on views container*/
uwsClickListener(".uwsjs-viewshowmain", function () {
    const uvcontainer = this.closest(".uwsviewscontainer");

    uwsSwitchViewSibling(uvcontainer.querySelector(".uwsviewhidden"), uvcontainer.querySelector(".uwsviewshown"));
});

/*Select OT time (Updated @egt to add slots) */
uwsClickListener(".uwsjs-selectottime", function (e) {
    e.preventDefault();

    //add time to button label
    const uvdtime = this.getAttribute("data-dtime");
    this.closest(".uwsinv-item").querySelector(".uwsdy-otdtime").innerHTML = uvdtime;

    const uvslots = this.getAttribute("data-slots");
    const uvslotstarget = this.closest(".uwsinv-item").querySelector(".uwsdy-otdtime");
    // const uvtargetid = this.getAttribute("data-time");

    uvslotstarget.innerHTML = uvdtime;

    if (uvslots) {
        uvslotstarget.setAttribute("data-slots", uvslots);
        uvslotstarget.classList.add("uv-has-slots");
        // uvslotstarget.setAttribute("data-time", uvtargetid);
    }

    //show main content
    const uvcontainer = this.closest(".uwsviewscontainer");
    uwsSwitchViewSibling(uvcontainer.querySelector(".uwsviewhidden"), uvcontainer.querySelector(".uwsviewshown"));

    uwsinvSetPopitemSelection("time", this.getAttribute("data-time"));

    if (this.getAttribute("data-type"))
        uwsinvSetPopitemSelection("timetype", this.getAttribute("data-type"));
    if (this.getAttribute("data-category"))
        uwsinvSetPopitemSelection("timecategory", this.getAttribute("data-category"));

    uwsinvUpdateItemPop(this.closest(".uwsinv-item"));
});

/*Select BK4 Time (Updated @egt to add slots) */
uwsClickListener(".uwsjs-selectbk4time", function (e) {
    e.preventDefault();

    //add time to button label
    const uvdtime = this.getAttribute("data-dtime");
    this.closest(".uwsinv-item").querySelector(".uwsdy-otdtime").innerHTML = uvdtime;

    const uvslots = this.getAttribute("data-slots");
    const uvslotstarget = this.closest(".uwsinv-item").querySelector(".uwsdy-otdtime");
    // const uvtargetid = this.getAttribute("data-time");

    uvslotstarget.innerHTML = uvdtime;

    if (uvslots) {
        uvslotstarget.setAttribute("data-slots", uvslots);
        uvslotstarget.classList.add("uv-has-slots");
        // uvslotstarget.setAttribute("data-time", uvtargetid);
    }

    //show main content
    const uvcontainer = this.closest(".uwsviewscontainer");
    uwsSwitchViewSibling(uvcontainer.querySelector(".uwsviewhidden"), uvcontainer.querySelector(".uwsviewshown"));

    uwsinvSetPopitemSelection("time", this.getAttribute("data-time"));

    if (this.getAttribute("data-bk4data"))
        uwsinvSetPopitemSelection("bk4data", this.getAttribute("data-bk4data"));

    uwsinvUpdateItemPop(this.closest(".uwsinv-item"));
});

//@egt Resets the time once the guest count goes up in order to check the slots / stock and fix overbooking for times
function uwsResetTimeSel(uvEl) {
    const uvcleantime = document.querySelector(".uwsdy-otdtime.uws-selbtn.uws-selectable");

    if (uvcleantime && uvcleantime.getAttribute("data-slots") && uvcleantime.getAttribute("data-slots") != "") {
        if (parseInt(uws_inventory.popitemsels.guests) > parseInt(uvcleantime.getAttribute("data-slots"))) {
            uvcleantime.setAttribute("data-slots", "");

            if (uvcleantime.innerHTML != "Select Time")
                uvcleantime.innerHTML = "Select Time";

            if (uvcleantime.classList.contains("uv-has-slots"))
                uvcleantime.classList.remove("uv-has-slots");

            uws_inventory.popitemsels.time = "";

            uwsinvUpdateItemPop(this.closest(".uwsinv-item"));
        }
    }
}

/*Update Guests*/
uwsChangeListener(".uwsjs-inv-updateguests", function (e) {
    e.preventDefault();

    uwsinvSetPopitemSelection("guests", this.value);
    uwsinvUpdateItemPop(this.closest(".uwsinv-item"));
});

/*Booking calendar go to book*/
uwsClickListener(".uwsjs-bkcal-bookdate", function (e) {
    e.preventDefault();

    const uvbookingcalelem = this.closest(".uws-booking-calendar");
    const uvloadinvelem = this.closest(".uws-booking-calendar").querySelector(".uwsbookingcalinvcont");

    if (uvloadinvelem) {
        uvbookingcalelem.classList.add("uwsbookactive");
        const uvvenuecode = uvbookingcalelem.getAttribute("data-venuecode");
        const uvdate = uvbookingcalelem.getAttribute("data-date");
        const uvecozone = uvbookingcalelem.getAttribute("data-ecozone");
        const uveventcode = "EVE" + uvvenuecode.replace("VEN", "") + uvecozone.replace("ECZ", "") + uvdate.replace(/-/g, "");

        uvloadinvelem.setAttribute("data-eventcode", uveventcode);
        uwsinvInitBlock(uvloadinvelem);
    }
});

/*Change date on booking calendar*/
uwsClickListener(".uwsjs-bkcal-changedate", function (e) {
    e.preventDefault();

    const uvbookingcalelem = this.closest(".uws-booking-calendar");
    uvbookingcalelem.classList.remove("uwsbookactive");
});

/*Validate Item Inquire Form*/
function uwsInitItemInquireForm(uvitemelem) {
    const uvform = uvitemelem.querySelector(".uwsjs-invitem-inquireform");
    const uvformvalidate = new Pristine(uvform, { classTo: "uws-inputcont", errorTextParent: "uws-inputcont", errorClass: "uwshaserror", errorTextClass: "uwsinputerror" });

    uvform.addEventListener("submit", function (e) {
        e.preventDefault();
        const uvformvalid = uvformvalidate.validate();

        if (uvformvalid) {
            var uvformproxy = uws_inventory.proxies["item-inquireform-pro"];
            let uvformdata = new FormData(uvform);

            if (uvformdata.get('fname') && uvformdata.get('lname')) {
                const partyname = uvformdata.get('fname') + ' ' + uvformdata.get('lname');
                uvformdata.set('partyname', partyname);
                uvformdata.delete('fname');
                uvformdata.delete('lname');
            }

            if (uws_invitem_pop.querySelector(".uwsinv-item.uws-inventory-item-pop"))
                uws_invitem_pop.querySelector(".uwsinv-item.uws-inventory-item-pop").querySelector(".uws-itempop-content").classList.add("uwsloading");

            // @egt [UWS-7297]
            if(typeof urvenue_ws_inventory_vars !== "undefined" && urvenue_ws_inventory_vars.targetNonce) {
                uvformproxy = uvformproxy + "&uws_nonce=" + encodeURIComponent(urvenue_ws_inventory_vars.targetNonce);
            }

            let uvrequest = new XMLHttpRequest();
            uvrequest.open('POST', uvformproxy, true);
            uvrequest.onload = function () {
                if (this.status >= 200 && this.status < 400) {
                    let uvresponse = this.response;
                    uvresponse = JSON.parse(uvresponse);

                    if (typeof (uvresponse.html) != "undefined") {
                        if (uws_invitem_pop.querySelector(".uws-cart-itemadded.uwsviewshown"))
                            uws_invitem_pop.querySelector(".uws-cart-itemadded.uwsviewshown").remove();

                        uws_invitem_pop.querySelector(".uwsinv-item.uws-inventory-item-pop").insertAdjacentHTML("beforeend", uvresponse.html);
                        uws_invitem_pop.querySelector(".uwsinv-item.uws-inventory-item-pop").querySelector(".uws-itempop-content").classList.remove("uwsloading");

                        uwsSwitchViewSibling(uws_invitem_pop.querySelector(".uws-cart-itemadded.uwsviewshown"), uws_invitem_pop.querySelector(".uws-inventory-item-pop-inner"));
                    }
                } else {
                    console.log("UVJS Error: Server returned an error");
                }
            };
            uvrequest.onerror = function () {
                console.log("UVJS Error: Request Error");
            };
            uvrequest.send(uvformdata);
        }
        else {
            const uverrorinput = uvitemelem.querySelector(".uwshaserror");
            if (uverrorinput)
                uverrorinput.querySelector("input").focus();
        }
    });
}

/*Check if there are cart drops*/
function uwsCheckCartDrops() {
    const uvcartdropelems = document.querySelectorAll(".uwsjs-cartdrop:not(.uwscartdroptarget)");
    Array.prototype.forEach.call(uvcartdropelems, function (el, i) {
        el.classList.add("uwscartdroptarget", "uws-dropdown-cont");
        el.querySelector(":scope > a").classList.add("uwsjs-trigger-dropdown");

        let uvcartcountelem = document.createElement("span");
        uvcartcountelem.classList.add("uwsdy-cartcount", "uws-count");
        el.querySelector(":scope > a").appendChild(uvcartcountelem);

        let uvcartdropcont = document.createElement("div");
        uvcartdropcont.classList.add("uws-dropdown");
        el.appendChild(uvcartdropcont);
    });
    if (document.querySelectorAll(".uwscartdroptarget, .uwsdynacarttarget")) {
        uwsInitDrops();
        uwsInitCartDrop();
    }
}

/*Initialize Cart Drop*/
function uwsInitCartDrop() {
    const uvcartdroptargetelems = document.querySelectorAll(".uwscartdroptarget, .uwsdynacarttarget");
    let uvispreloaded = 1;
    Array.prototype.forEach.call(uvcartdroptargetelems, function (el, i) {
        if (!el.classList.contains("uwscartpreloaded")) {
            el.classList.add("uwsloading");
            uvispreloaded = 0;
        }
    });

    if (document.querySelector(".uws-inventory-bookbtns"))
        uvispreloaded = 0;

    if (!uvispreloaded) {
        let uvinitcarturl = uws_proxy + "&uvaction=uwspx_cartdrop";
        uvinitcarturl = uvinitcarturl + "&cartcode=" + uwsInvGetCartCookie();

        //add manageentid if is in uwsinventory object (for no lib integrations)
        if (typeof (uws_inventory.manageentid) != "undefined" && uws_inventory.manageentid)
            uvinitcarturl = uvinitcarturl + "&managementid=" + uws_inventory.manageentid;

        //add microcode if is in uwsinventory object (for no lib integrations)
        if (typeof (uws_inventory.microcode) != "undefined" && uws_inventory.microcode)
            uvinitcarturl = uvinitcarturl + "&microcode=" + uws_inventory.microcode;

        // @egt [UWS-7297]
        if(typeof urvenue_ws_inventory_vars !== "undefined" && urvenue_ws_inventory_vars.targetNonce) {
            uvinitcarturl = uvinitcarturl + "&uws_nonce=" + encodeURIComponent(urvenue_ws_inventory_vars.targetNonce);
        }

        let uvrequest = new XMLHttpRequest();
        uvrequest.open('GET', uvinitcarturl, true);
        uvrequest.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                let uvresponse = this.response;
                uvresponse = JSON.parse(uvresponse);

                uwsinvAddVarsToGlobal(uvresponse);
                uwsinvUpdateDropCart();
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

//Update cart drop based on info on global inventory object
function uwsinvUpdateDropCart() {
    let uvcartcount = "";

    //Update cart count
    if (typeof (uws_inventory.cart) != "undefined" && typeof (uws_inventory.cart.cartitems) == "object") {
        uvcartcount = Object.keys(uws_inventory.cart.cartitems).length;
    }
    if (!uvcartcount && typeof (uws_inventory.cart) != "undefined" && typeof (uws_inventory.cart.cartcount) != "undefined")
        uvcartcount = (uws_inventory.cart.cartcount != "0") ? uws_inventory.cart.cartcount : "";

    const uvcartcountelems = document.querySelectorAll(".uwsdy-cartcount");
    Array.prototype.forEach.call(uvcartcountelems, function (el, i) {
        el.innerHTML = uvcartcount;
    });
    const uvcartactiveclasselems = document.querySelectorAll(".uwsdy-cartactive-class");
    Array.prototype.forEach.call(uvcartactiveclasselems, function (el, i) {
        if (uvcartcount)
            el.classList.add("uwscartactive");
        else
            el.classList.remove("uwscartactive");
    });

    //Update checkout buttons
    if (typeof (uws_inventory.cart) != "undefined" && uws_inventory.cart["checkout-carturl"]) {
        const uvcarturlelems = document.querySelectorAll(".uwsdy-carturl");
        Array.prototype.forEach.call(uvcarturlelems, function (el, i) {
            el.setAttribute("href", uws_inventory.cart["checkout-carturl"]);
        });

        const uvcheckurlelems = document.querySelectorAll(".uwsdy-checkouturl");
        Array.prototype.forEach.call(uvcheckurlelems, function (el, i) {
            el.setAttribute("href", uws_inventory.cart["checkout-checkurl"]);
        });
    }

    //Update cart drop
    if (typeof (uws_inventory.cart) != "undefined" && uws_inventory.cart.cartdrophtml) {
        const uvcartdroptargetelems = document.querySelectorAll(".uwscartdroptarget, .uwsdynacarttarget");
        Array.prototype.forEach.call(uvcartdroptargetelems, function (el, i) {
            const uvcartcontelem = (el.classList.contains("uwsdynacarttarget")) ? el.querySelector(".uwsdy-dynacart") : el.querySelector(".uws-dropdown");

            if (uvcartcontelem) {
                uvcartcontelem.innerHTML = uws_inventory.cart.cartdrophtml;
                el.classList.remove("uwsloading");
            }
        });
    }
}

/*Show Inventory Item Pop*/
function uwsInvShowItemPop(uvmastercode, uvitempresels) {
    if (!uws_invitem_pop)//create pop if it doesn't exist
        uws_invitem_pop = uwsCreatePop("uws-invitem-pop");

    if (typeof (uws_inventory.itempop) == "object" && typeof (uws_inventory.itempop.popitemloaderfn) == "function")
        uws_inventory.itempop.popitemloaderfn();
    else
        uwsShowGLoader();

    if (typeof (uws_inventory.itempop) == "object" && typeof (uws_inventory.itempop.popitem) == "object")
        uws_invitem_pop = uws_inventory.itempop.popitem;

    const uvreturnprox = (typeof (uws_inventory.proxies) != "undefined") ? 0 : 1;
    let uvinventoryload = uws_proxy + "&uvaction=uwspx_inventoryitempop";
    uvinventoryload = uvinventoryload + "&mastercode=" + uvmastercode + "&returnprox=" + uvreturnprox;

    if (typeof (uvitempresels) == "object") {
        if (uvitempresels.sectionid)
            uvinventoryload += "&sectionid=" + uvitempresels.sectionid;
        if (uvitempresels.locationid)
            uvinventoryload += "&locationid=" + uvitempresels.locationid;
        if (uvitempresels.forcenew)
            uvinventoryload += "&forcenew=" + uvitempresels.forcenew;
    } else if (typeof (uvitempresels) == "string" && uvitempresels == "pricingbreakdown") {
        uvinventoryload += "&pricingbreakdown=1";
        uws_invitem_pop.classList.add("uws-itembreakdown-pop");
    }

    //clean global popitem vars
    uws_inventory.popitem = "";
    uws_inventory.popitemsels = "";

    // @egt [UWS-7297]
    if(typeof urvenue_ws_inventory_vars !== "undefined" && urvenue_ws_inventory_vars.targetNonce) {
        uvinventoryload = uvinventoryload + "&uws_nonce=" + encodeURIComponent(urvenue_ws_inventory_vars.targetNonce);
    }

    let uvrequest = new XMLHttpRequest();
    uvrequest.open('GET', uvinventoryload, true);
    uvrequest.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            let uvresponse = this.response;
            uvresponse = JSON.parse(uvresponse);

            if (typeof (uws_inventory.itempop) == "object" && typeof (uws_inventory.itempop.popshowfn) == "function")
                uws_inventory.itempop.popshowfn();
            else
                uwsHideGLoader();

            uwsinvAddVarsToGlobal(uvresponse);

            if (typeof (uvresponse.html) != "undefined") {
                if (typeof (uws_inventory.itempop) == "object" && typeof (uws_inventory.itempop.popitemloc) == "object") {
                    uws_inventory.itempop.popitemloc.innerHTML = "";
                    uws_inventory.itempop.popitemloc.insertAdjacentHTML("beforeend", uvresponse.html);
                }
                else
                    uwsClearPopup(uws_invitem_pop, uvresponse.html);

                if (typeof (uvresponse["popitem-module"]) != "undefined" && uvresponse["popitem-module"] == "membership")
                    uwsmemInitInvItemMemberships(uvresponse);
                else
                    uwsInitInvItem(uws_invitem_pop.querySelector(".uwsinv-item.uws-inventory-item-pop"));

                // if bottles in cookie then update bottle selection text
                const uvsavedbottles = uwsGetCookie("uv_itembottles");
                const uvsavedbottlessel = uwsGetCookie("uv_bottlestext");
                const uvsavedbottlestot = uwsGetCookie("uv_bottlestotal");
                if (uvsavedbottles && uvsavedbottlessel && uws_invitem_pop.querySelector(".uwsdy-bottle")) {
                    uws_invitem_pop.querySelector(".uwsinv-item").classList.add("uwsbottleselected");
                    uws_invitem_pop.querySelector(".uwsdy-bottle").innerHTML = "Selected";

                    const uvbottlesselelem = uws_invitem_pop.querySelector(".uws-bottle-selection .uws-bottle-text");
                    const uvbottlestotalelem = uws_invitem_pop.querySelector(".uws-bottle-selection .uwsdy-bottlestotal");
                    const uvsavedbottlesseldec = decodeURIComponent(uvsavedbottlessel.replace(/\+/g, ' '));
                    uvbottlesselelem.innerHTML = uwsFormatBottlesText(uvsavedbottlesseldec);

                    if (uvbottlestotalelem) uvbottlestotalelem.innerHTML = uvsavedbottlestot;
                }

                uws_invitem_pop.setAttribute("data-closecallback", "uwsInvItemPopClosed");
                uwsFadePopup(uws_invitem_pop);

                if (typeof (uvhookItemPopOpened) == "function" && typeof (uvresponse.popitem) != "undefined")
                    uvhookItemPopOpened(uvresponse.popitem);

                const uvonlyinquire = (uvresponse.popitem.info?.paytypes?.length <= 0) ? 1 : 0;
                if (uvonlyinquire)
                    uwsLoadItemInquireForm(uvresponse.popitem);
            }
        } else {
            //console.log("UVJS Error: Server returned an error");

            const uvsverror = `
                <div class='uws-sverror-cont'>
                    <div class='uwstitle'><i class='uwsicon-warning-empty'></i> Something Went Wrong</div>
                    <div class='uwstext'>Check back later for updates.</div>
                </div>
            `;

            uwsClearPopup(uws_invitem_pop, uvsverror);
            uwsFadePopup(uws_invitem_pop);
            uwsHideGLoader();
        }
    };
    uvrequest.onerror = function () {
        console.log("UVJS Error: Request Error");
    };
    uvrequest.send();
}

// Format bottles text from cookie
function uwsFormatBottlesText(uvbottles) {
    let uvtext = "";

    if (uvbottles) {
        uvtext = uvbottles.replace(/^"|"$/g, '')
            .split("<br>")
            .filter(item => item.trim())
            .map(item => item.split(" x ")[0] + "x " + item.split(":")[0].split(" x ")[1].trim())
            .join(" / ");
    }
    return uvtext;
}

/*Call back function for popupclose*/
function uwsInvItemPopClosed() {
    //console.log("popup was closed");
    if (typeof (uvhookInvItemPopClosed) == "function")
        uvhookInvItemPopClosed(uws_inventory.popitem);
}

/*Load item info and show pop*/
function uwsInvLoadShowItemInfoPop(uvmastercode) {
    if (uvmastercode) {
        uwsShowGLoader();

        let uvitemload = uws_proxy + "&uvaction=uwspx_inventoryiteminfo";
        uvitemload = uvitemload + "&mastercode=" + uvmastercode;

        // @egt [UWS-7297]
        if(typeof urvenue_ws_inventory_vars !== "undefined" && urvenue_ws_inventory_vars.targetNonce) {
            uvitemload = uvitemload + "&uws_nonce=" + encodeURIComponent(urvenue_ws_inventory_vars.targetNonce);
        }

        let uvrequest = new XMLHttpRequest();
        uvrequest.open('GET', uvitemload, true);
        uvrequest.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                let uvresponse = this.response;
                uvresponse = JSON.parse(uvresponse);

                if (typeof (uvresponse.item) != "undefined") {
                    uvitem = uvresponse.item.info;
                    uwsInvShowItemInfoPop(uvitem);
                }

                uwsHideGLoader();
            }
        };
        uvrequest.onerror = function () {
            console.log("UVJS Error: Request Error");
        };
        uvrequest.send();
    }
}
/*Show item info pop*/
function uwsInvShowItemInfoPop(uvmastercode, uvisfrompop) {
    let uvitem = "";

    if (uvisfrompop)
        uvitem = uws_inventory.popitem.info;
    else if (typeof (uvmastercode) == "object")
        uvitem = uvmastercode;
    else
        uvitem = uws_inventory.items[uvmastercode];

    if (uvitem) {
        let uvitemdescr = (uvitem.itemdescr) ? uvitem.itemdescr : "";
        uvitemdescr = (!uvitemdescr && uvitem.descr) ? uvitem.descr : uvitemdescr;
        const uvtitlehtml = (uvitem.itemname) ? "<div class='uwstitle'>" + uvitem.itemname + "</div>" : "";
        const uvhighlighthtml = (uvitem.highlight) ? "<div class='uwshighlight'>" + uvitem.highlight + "</div>" : "";
        const uvdescrhtml = (uvitemdescr) ? "<div class='uwsdescr'>" + uvitemdescr + "</div>" : "";
        const uvimageurl = (typeof (uvitem.images) === "object") ? Object.values(uvitem.images)[0][0].folder + "/500SC0/" + Object.values(uvitem.images)[0][0].file : "";
        const uvaddimage = (uvimageurl) ? "<div class='uwsimage'><img src='" + uvimageurl + "' alt='Item Image'></div>" : "";
        const uvaddselbtn = (uvisfrompop) ? "" : `<button href='#uvinv-select' class='uws-btn uws-btn-p uwsjs-inv-item-select' data-mastercode='${uvitem.mastercode}'><span>Book</span></button>`;
        const uvpopinfoclass = (uvisfrompop) ? "uwsiffrompop" : "";

        let uvpophtml = "<div class='uws-pop-slightheader'>" + uvtitlehtml + "</div>";
        uvpophtml += "<div class='uws-pop-infobody'>" + uvaddimage + uvhighlighthtml + uvdescrhtml + "</div>";
        uvpophtml += "<div class='uws-pop-actionchose " + uvpopinfoclass + "'><button class='uws-btn uws-btn-s uwsjs-closepop-force'><span>Close</span></button>" + uvaddselbtn + "</div>";

        uws_mgs_pop.classList.add("uws-pop-inviteminfo");
        uwsClearPopup(uws_mgs_pop, uvpophtml);
        uwsFadePopup(uws_mgs_pop);
    }
}

function uwsInvAddItemToCart() {
    let uvinvaddtocartpx = uws_inventory.proxies["cart-additem"];
    uvinvaddtocartpx = uvinvaddtocartpx + "&cartcode=" + uwsInvGetCartCookie() + uwsInvGetItemCartVars();
    let uvsavedbottlesuri = "";

    //add microcode if is in uwsinventory object (for no lib integrations)
    if (typeof (uws_inventory.microcode) != "undefined" && uws_inventory.microcode)
        uvinvaddtocartpx = uvinvaddtocartpx + "&microcode=" + uws_inventory.microcode;

    if (uws_invitem_pop.querySelector(".uwsinv-item.uws-inventory-item-pop"))
        uws_invitem_pop.querySelector(".uwsinv-item.uws-inventory-item-pop").querySelector(".uws-itempop-content").classList.add("uwsloading");

    // @egt [UWS-7297]
    if(typeof urvenue_ws_inventory_vars !== "undefined" && urvenue_ws_inventory_vars.targetNonce) {
        uvinvaddtocartpx = uvinvaddtocartpx + "&uws_nonce=" + encodeURIComponent(urvenue_ws_inventory_vars.targetNonce);
    }

    let uvrequest = new XMLHttpRequest();
    uvrequest.open('GET', uvinvaddtocartpx, true);
    uvrequest.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            let uvresponse = this.response;
            uvresponse = JSON.parse(uvresponse);

            // add bottles from cookie
            const uvsavedbottles = uwsGetCookie("uv_itembottles");
            const uvsavedbottlessel = uwsGetCookie("uv_bottlestext");
            const uvsavedbottlestot = uwsGetCookie("uv_bottlestotal");

            if (uvsavedbottles && uvsavedbottlessel && uvsavedbottlestot)
                uvsavedbottlesuri = "&itembottles=" + encodeURIComponent(uvsavedbottles) + "&bottlestext=" + encodeURIComponent(uvsavedbottlessel) + "&bottlestotal=" + encodeURIComponent(uvsavedbottlestot);

            if (typeof (uvresponse.recreate) != undefined && uvresponse.recreate) {
                //uwsSetCookie(uws_inventory_cookiename, "", 7);
                uwsInvSetCartCookie("");
                uwsInvAddItemToCart();
            }
            else {
                if (typeof (uvresponse.html) != "undefined") {
                    if (typeof (uvresponse.redirect) == "undefined" || !uvresponse.redirect) {
                        if (uvresponse.closepopup) {
                            uwsHidePopup(uws_invitem_pop, 1);
                            uwsHideGLoader();
                        }
                        else {
                            if (uws_invitem_pop.querySelector(".uws-cart-itemadded.uwsviewshown"))
                                uws_invitem_pop.querySelector(".uws-cart-itemadded.uwsviewshown").remove();

                            uws_invitem_pop.querySelector(".uwsinv-item.uws-inventory-item-pop").insertAdjacentHTML("beforeend", uvresponse.html);
                            uws_invitem_pop.querySelector(".uwsinv-item.uws-inventory-item-pop").querySelector(".uws-itempop-content").classList.remove("uwsloading");
                            //uws_invitem_pop.querySelector(".uwsinv-item").classList.add("uwshideheader");

                            let uvcartbtn = uws_invitem_pop.querySelector(".uws-cart-itemadded .uwsjs-go-cart");
                            let uvcheckbtn = uws_invitem_pop.querySelector(".uws-cart-itemadded .uwscartscreenfooter .uws-btn-s");

                            if (uvcartbtn && uvcheckbtn && uvsavedbottles && uvsavedbottlessel && uvsavedbottlestot) {
                                uvcartbtn = uvcartbtn.setAttribute("href", uvcartbtn.getAttribute("href") + uvsavedbottlesuri);
                                uvcheckbtn = uvcheckbtn.setAttribute("href", uvcheckbtn.getAttribute("href") + uvsavedbottlesuri);
                            }

                            uwsSwitchViewSibling(uws_invitem_pop.querySelector(".uws-cart-itemadded.uwsviewshown"), uws_invitem_pop.querySelector(".uws-inventory-item-pop-inner"));
                        }

                        uwsHideGLoader();
                    }
                }
                if (typeof (uvresponse.carturl) != "undefined") {
                    const uvcarturlelems = document.querySelectorAll(".uwsdy-carturl");
                    Array.prototype.forEach.call(uvcarturlelems, function (el, i) {

                        if (uvsavedbottles && uvsavedbottlessel && uvsavedbottlestot)
                            uvresponse.carturl = uvresponse.carturl + uvsavedbottlesuri;

                        el.setAttribute("href", uvresponse.carturl);
                    });
                }
                if (typeof (uvresponse.checkurl) != "undefined") {
                    const uvcarturlelems = document.querySelectorAll(".uwsdy-checkouturl");
                    Array.prototype.forEach.call(uvcarturlelems, function (el, i) {

                        if (uvsavedbottles && uvsavedbottlessel && uvsavedbottlestot)
                            uvresponse.checkurl = uvresponse.checkurl + uvsavedbottlesuri;

                        el.setAttribute("href", uvresponse.checkurl);
                    });
                }

                uwsinvAddVarsToGlobal(uvresponse);

                let uvinvstage = document.querySelector(".uws-inventory-stage");
                if (uvinvstage)
                    uwsinvUpdateUIStates(uvinvstage);
                else if (document.querySelector(".uws-map .uws-map-item-box"))
                    uwsMapUpdateItemBoxUI();

                uwsinvUpdateDropCart();

                if (typeof (uvresponse.cartcode)) {
                    if (typeof (uvhookInvItemAdded) == "function")
                        uvhookInvItemAdded(uws_inventory.popitem, uws_inventory.popitemsels, uvresponse);

                    if (typeof (uvhookInvCartEdited) == "function")
                        uvhookInvCartEdited(uvresponse);
                }

                if (typeof (uvresponse.redirect) != "undefined") {
                    if (typeof (uvhookGoCheckout) == "function") {
                        uvhookGoCheckout(uvresponse);
                    } else {
                        uvsourceloc = uvGetSourceLoc();
                        uvcheckoutURL = (uvsourceloc) ? uvresponse.redirect.replace(/sourceloc=[^&]*/, `sourceloc=${uvsourceloc}`) : uvresponse.redirect;

                        if (uvsavedbottles && uvsavedbottlessel && uvsavedbottlestot)
                            uvcheckoutURL = uvcheckoutURL + uvsavedbottlesuri;

                        window.location = uvcheckoutURL;
                    }
                }

                //if open check
                if (typeof (uvresponse.opencheck) != "undefined" && typeof (uvresponse.issidecheck) != "undefined" && uvresponse.issidecheck) {
                    if (typeof (uvcheckout) != "undefined") {
                        uwsHidePopup(uws_invitem_pop, 1);
                        uvcheckout.setOptions({
                            gocheckurl: uvresponse.opencheck,
                        });
                        uvcheckout.gocheck();
                    }
                    else
                        console.log("no uvcheckout included on page");
                }
            }
        } else {
            //console.log("UVJS Error: Server returned an error");
            const uvsverror = `
                <div class='uws-sverror-cont'>
                    <div class='uwstitle'><i class='uwsicon-warning-empty'></i> Something Went Wrong</div>
                    <div class='uwstext'>Please try again later.</div>
                </div>
            `;

            uws_invitem_pop.querySelector(".uwsinv-item.uws-inventory-item-pop").insertAdjacentHTML("beforeend", uvsverror);
            uws_invitem_pop.querySelector(".uwsinv-item.uws-inventory-item-pop").querySelector(".uws-itempop-content").classList.remove("uwsloading");
            uwsSwitchViewSibling(uws_invitem_pop.querySelector(".uws-sverror-cont"), uws_invitem_pop.querySelector(".uws-inventory-item-pop-inner"));
        }
    };
    uvrequest.onerror = function () {
        console.log("UVJS Error: Request Error");
    };
    uvrequest.send();
}

/*Returns variables to add item to cart*/
function uwsInvGetItemCartVars(uvitem, uvitemsels) {
    if (typeof (uvitem) == "undefined")
        uvitem = uws_inventory.popitem;
    if (typeof (uvitemsels) == "undefined")
        uvitemsels = uws_inventory.popitemsels;

    let uvitemsvars = "&mastercode=" + uvitem.info.mastercode + "&itemcode=" + uvitem.info.itemcode + "&caldate=" + uvitem.info.caldate + "&venuecode=" + uvitem.info.venuecode + "&ecozone=" + uvitem.info.ecocode + "&itemname=" + encodeURIComponent(uvitem.info.itemname) + "&globaltype=" + uvitem.info.globaltype;

    if (uvitem.info.ext_datajson)
        uvitemsvars += "&masterbk4data=" + uvitem.info.ext_datajson;

    //Add Selection Vars Here
    if (uvitemsels.paytype)
        uvitemsvars += "&paytype=" + uvitemsels.paytype;
    if (uvitemsels.guests)
        uvitemsvars += "&guests=" + uvitemsels.guests;
    if (uvitemsels.time)
        uvitemsvars += "&time=" + uvitemsels.time;
    if (uvitemsels.selprice)
        uvitemsvars += "&subtotalagree=" + uvitemsels.selprice;
    if (uvitemsels.duration)
        uvitemsvars += "&duration=" + uvitemsels.duration;
    if (uvitemsels.gotocheck)
        uvitemsvars += "&gotocheck=" + uvitemsels.gotocheck;
    if (uws_inventory && uws_inventory.cart && uws_inventory.cart.cartmanagementid)
        uvitemsvars += "&cartmanagementid=" + uws_inventory.cart.cartmanagementid;
    if (uws_inventory && uws_inventory.manageentid)
        uvitemsvars += "&manageentid=" + uws_inventory.manageentid;
    if (uvitem.selectedsectionid)
        uvitemsvars += "&sectionid=" + uvitem.selectedsectionid;
    if (uvitem.selectedlocationid)
        uvitemsvars += "&locationid=" + uvitem.selectedlocationid;
    if (uvitem.selectedforcenew)
        uvitemsvars += "&forcenew=" + uvitem.selectedforcenew;
    if (uvitemsels.timetype)
        uvitemsvars += "&timetype=" + uvitemsels.timetype;
    if (uvitemsels.timecategory)
        uvitemsvars += "&timecategory=" + uvitemsels.timecategory;
    if (uvitemsels.bk4data)
        uvitemsvars += "&bk4data=" + uvitemsels.bk4data;
    if (typeof (uws_addcart_checkoutinfo) != "undefined" && uws_addcart_checkoutinfo)
        uvitemsvars += "&checkoutinfo=" + encodeURIComponent(uws_addcart_checkoutinfo);
    if (typeof (uws_addcart_extraparams) != "undefined" && uws_addcart_extraparams)
        uvitemsvars += uws_addcart_extraparams;

    uvitemsels.gotocheck = 0;

    return uvitemsvars;
}

/*Initialice Item Add Events and Plugins Needed*/
function uwsInitInvItem(uvinvitemelem) {
    uwsInitDrops();
    uwsinvAddItemSels();
    uwsinvInitItemDuration(uvinvitemelem);
    uwsinvInitGuestsCheck(uvinvitemelem);
    uwsinvAddItemPaytypes(uvinvitemelem);
    uwsinvUpdateItemPop(uvinvitemelem);
    uwsinvUpdateCartItemPop(uvinvitemelem);
}

/*Add paytypes options to item*/
function uwsinvAddItemPaytypes(uvinvitemelem) {
    let uvpaytypeslist = "";
    let uvnpaytypes = 0;
    const uvitem = uws_inventory.popitem;
    const uvpaytypes = uvitem.info.paytypes;

    uvpaytypes.forEach(function (el) {
        if (typeof (uvitem.library.paytypes[el]) == "object") {
            const uvpaytypename = uvitem.library.paytypes[el].name;
            const uvpaytypepay = uvitem.library.paytypes[el].pay;
            const uvactiveclass = (uws_inventory.popitemsels.paytype == el) ? "uwsactive" : "";

            uvpaytypeslist += `<a href='#uws-select-paytype-${uvpaytypepay}' class='uws-btn uwsjs-item-update-paytype ${uvactiveclass}' data-paytype='${el}'><span class='uwsradiobullet'></span><div class='uwspaytypename'>${uvpaytypename}</div><div class='uwsprice uwsdy-paytype-${el}' data-symbol="${uvitem.info.currency_symbol}"></div></a>`;

            uvnpaytypes++;
        }
    });
    const uvpaytypeshtml = `<div class='uws-pay-prices-inner uws-npaytypes-${uvnpaytypes}'><div class='uwslabel'>Select Pay Type</div><div class='uwspayoptscont'>${uvpaytypeslist}</div><div>`;

    uwsinvUpdateDyElem(uvinvitemelem, "pay-prices", uvpaytypeshtml);
}

/*Add initial item sels*/
function uwsinvAddItemSels() {
    const uvitem = uws_inventory.popitem;
    const uvmasteritemcode = uvitem.info.masteritemcode;
    const uvqtydefault = uvitem.elements[uvmasteritemcode].header.qtydefault;

    window.uws_inventory.popitemsels = window.uws_inventory.popitemsels || {};
    uwsinvSetPopitemSelection("guests", uvqtydefault);
    uwsinvSetPopitemSelection("time", "");
    uwsinvSetPopitemSelection("duration", "");
    uwsinvSetPopitemSelection("paytype", uvitem.header.paytypedefault);
}

/*Init guest update checks*/
function uwsinvInitGuestsCheck(uvinvitemelem) {
    if (uws_inventory.popitem && uws_inventory.popitem.info && uws_inventory.popitem.info.globaltype == "virtual2") {
        const uvguestsinput = uvinvitemelem.querySelector(".uwsjs-inv-updateguests");

        uvguestsinput.addEventListener('input', uwsinvValidateGuestsSelection);
        uvguestsinput.addEventListener('blur', uwsinvValidateGuestsSelection);
        uvguestsinput.addEventListener('blur', uwsinvBlurCheckGuests);

        uwsinvLoadCartBreakdowns(uvinvitemelem);
    }
}

/*Check if upload cart breakdowns*/
function uwsinvCheckCartBdUpdate() {
    const uvinvitemelem = document.querySelector(".uwsinv-item.uws-inventory-item-pop");

    if (uvinvitemelem.querySelector(".uwspricescont").getAttribute("data-guests") != uws_inventory.popitemsels.guests)
        uwsinvLoadCartBreakdowns(uvinvitemelem);
}

/*Load Cart Brekdowns*/
function uwsinvLoadCartBreakdowns(uvinvitemelem) {
    uvinvitemelem.querySelector(".uws-itempop-content").classList.add("uwsloading");

    let uvgetcartbreakdown = uws_inventory.proxies["get-cart-breakdown"];
    const uvitem = uws_inventory.popitem;
    uvgetcartbreakdown = uvgetcartbreakdown + uwsInvGetItemCartVars(uvitem);

    let uvrequest = new XMLHttpRequest();
    uvrequest.open('GET', uvgetcartbreakdown, true);
    uvrequest.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            let uvresponse = this.response;
            uvresponse = JSON.parse(uvresponse);

            let uvagreeprices = uvpricebreakdown = uvpricesbreaklist = uvbreaktotal = "";
            const uvcursymbol = "$";

            if (uvresponse.itemsbasecomponents) {
                Object.keys(uvresponse.itemsbasecomponents).forEach(function (key) {
                    const el = uvresponse.itemsbasecomponents[key];
                    const uvdprice = uwsFrontformatMoney(el.totalbase, 1);

                    uvagreeprices += `<div class="uwspricecont"><div class="uwslabel">${el.pricingdisplay}</div><div class="uwsprice" data-symbol="${uvcursymbol}">${uwsFrontformatMoney(el.totalbase, 1)}</div></div>`;
                });
            }

            if (uvresponse.totals && uvresponse.totals.paybreakdown) {
                Object.entries(uvresponse.totals.paybreakdown).forEach(([uvkey, el]) => {
                    if (uvkey != "total") {
                        uvpricesbreaklist = `<div class='uwsbreakitem uwsbreakitem-${uvkey}'><span class='uwsname'>${el.displayname}</span><span class='uwsprice' data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(el.value, 1)}</span></div>`;
                    }
                    else
                        uvbreaktotal = el;
                });

                uvpricebreakdown = `
                    <div class="uws-togglecoll">
                        <a class="uwsjs-toggle-collapse" href='#open-item-price-details'>
                            <div class="uwsname"><span>Details</span> <i class="uwsicon-right-open"></i></div>
                            <div class="uwsbkpricecont"><span class="uwsname">${uvbreaktotal.displayname}</span><div class="uwsprice" data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvbreaktotal.value, 1)}</div></div>
                        </a>

                        <div class="uws-togglecoll-body">
                            <div class="uws-togglecoll-inner">
                                ${uvpricesbreaklist}
                                <div class='uwsbreakitem uwsbreakitem-total'><span class='uwsname'>${uvbreaktotal.displayname}</span><span class='uwsprice' data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvbreaktotal.value, 1)}</span></div>
                            </div>
                        </div>
                    </div>
                `;
            }

            uwsinvUpdateDyElem(uvinvitemelem, "agreement-prices", uvagreeprices);
            uwsinvUpdateDyElem(uvinvitemelem, "price-breakdown", uvpricebreakdown);

            uvinvitemelem.querySelector(".uwspricescont").setAttribute("data-guests", uws_inventory.popitemsels.guests);

            uvinvitemelem.querySelector(".uws-itempop-content").classList.remove("uwsloading");
        } else {
            console.log("UVJS Error: Server returned an error");
        }
    };
    uvrequest.onerror = function () {
        console.log("UVJS Error: Request Error");
    };
    uvrequest.send();
}

/*Show Item List Breakdown*/
function uwsInvShowItemListBreakdown(uvmicrocode) {
    if (uvmicrocode) {
        let uvitem = (typeof (uws_inventory.items) != "undefined" && typeof (uws_inventory.items[uvmicrocode]) != "undefined") ? uws_inventory.items[uvmicrocode] : "";

        if (!uvitem && typeof (uws_map.items) != "undefined" && typeof (uws_map.items[uvmicrocode]) != "undefined")
            uvitem = uws_map.items[uvmicrocode];

        if (uvitem && typeof (uvitem.breakdown_labels) == "object") {

            let uvpricesbreakdown = "";

            if (!uws_invitem_pop)//create pop if it doesn't exist
                uws_invitem_pop = uwsCreatePop("uws-invitem-pop");

            if (typeof (uws_inventory.itempop) == "object" && typeof (uws_inventory.itempop.popitem) == "object")
                uws_invitem_pop = uws_inventory.itempop.popitem;

            Object.entries(uvitem.breakdown_labels).forEach(([uvkey, uvvalue]) => {
                const uvbreakdownsection = uvitem.breakdowns[uvkey];
                const uvcursymbol = uvitem.currency_symbol;

                if (uvbreakdownsection) {
                    const uvpricetotal = uvbreakdownsection.total.amount;
                    const uvtotallabel = uvbreakdownsection.total.name;
                    let uvpricesbreaklist = "";

                    Object.entries(uvbreakdownsection).forEach(([uvkey, uvvalue]) => {
                        if (uvkey != "input" && uvvalue != "0" && uvkey != "total") {
                            uvpricesbreaklist += `<div class='uwsbreakitem uwsbreakitem-${uvkey}'><span class='uwsname'>${uvvalue.name}</span><span class='uwsprice' data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvvalue.amount, 1)}</span></div>`;
                        }
                    });

                    uvpricesbreakdown += `
                        <div class="uws-togglecoll uwsactive">
                            <a class="uwsjs-toggle-collapse" href='#open-item-price-details'>
                                <div class="uwsname"><span>${uvvalue}</span></div>
                                <div class="uwsbkpricecont"><div class="uwsprice" data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvpricetotal, 1)}</div><i class="uwsicon-right-open"></i></div>
                            </a>
        
                            <div class="uws-togglecoll-body">
                                <div class="uws-togglecoll-inner">
                                    ${uvpricesbreaklist}
                                    <div class='uwsbreakitem uwsbreakitem-total'><span class='uwsname'>${uvtotallabel}</span><span class='uwsprice' data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvpricetotal, 1)}</span></div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            });

            if (uvpricesbreakdown) {
                uvpricesbreakdown = `
                    <div class="uws-inventory-item-pop uwsinv-pricingbreakdown-pop">
                        <div class="uws-itempop-header">
                            <div class="uws-itempop-header-inner">
                                <div class="uwsname">${uvitem.itemname}</div>
                            </div>
                        </div>
                        <div class="uws-itempop-body">
                            <div class="uwspricesbreakdown">${uvpricesbreakdown}</div>
                        </div>
                    </div>
                `;
                uws_invitem_pop.classList.add("uws-itembreakdown-pop");
                uwsClearPopup(uws_invitem_pop, uvpricesbreakdown);

                setTimeout(function () { uwsFadePopup(uws_invitem_pop); }, 100);
            }
        }
    }
}

/*Check Guests Selection Update*/
function uwsinvValidateGuestsSelection() {
    const uvmin = parseInt(this.min);
    const uvmax = parseInt(this.max);
    let uvvalue = parseInt(this.value);

    if (uvvalue < uvmin) {
        this.value = uvmin;
    } else if (uvvalue > uvmax) {
        this.value = uvmax;
    }

    const uvevent = new Event('change', { bubbles: true });
    this.dispatchEvent(uvevent);
    uwsDebounce(uwsinvCheckCartBdUpdate, 3000);
}
function uwsinvBlurCheckGuests() {
    const uvvalue = parseInt(this.value);

    if (!uvvalue)
        this.value = parseInt(this.min);

    uwsinvCheckCartBdUpdate();
}


/*Init duration timeline*/
function uwsinvInitItemDuration(uvinvitemelem) {
    const uvdurcont = uvinvitemelem.querySelector(".uws-item-durationsel-cont");
    if (uvdurcont) {
        uws_invitem_pop.classList.add("uws-noareaclose");//popup avoid close on external click

        const uvnslots = uvdurcont.getAttribute("data-nslots") / 1;
        const uvinitslots = uvdurcont.getAttribute("data-initslots") / 1;
        const uvfrequency = uvdurcont.getAttribute("data-frequency") / 1;
        const uvtimeline = uvdurcont.querySelector(".uwstimeline");

        noUiSlider.create(uvtimeline, {
            start: [0, uvinitslots],
            connect: true,
            step: 1,
            margin: 1,
            range: {
                'min': 0,
                'max': uvnslots
            }
        });

        //Actions when the slider changes
        uvtimeline.noUiSlider.on('update', function (uvvalues, uvhandle) {
            const uvstartslot = uvvalues[0] / 1;
            const uvendslot = uvvalues[1] / 1;
            const uvnslots = uvendslot - uvstartslot;
            const uvitemminutes = uvnslots * (uvfrequency / 1);
            const uvslotselems = uvtimeline.children;
            const uvstartslotelem = uvslotselems.item(uvstartslot);
            const uvstartslottime = uvstartslotelem.getAttribute("data-time");

            uwsinvSetPopitemSelection("time", uvstartslottime);
            uwsinvSetPopitemSelection("duration", uvitemminutes);
            uwsinvUpdateItemPop(uvinvitemelem);

            uwsinvUpdateItemDurationRange(uvtimeline);
        });

        //Actions when the slider move ends
        uvtimeline.noUiSlider.on('end', function (uvvalues, uvhandle) {
            uwsinvFindAdjDuration(uws_inventory.popitem, uws_inventory.popitemsels);
        });
    }
}

/*Update duration min and max*/
function uwsinvUpdateItemDurationRange(uvtimeline) {
    const uvitem = uws_inventory.popitem;
    const uvitemsels = uws_inventory.popitemsels;
    const uvinvitemelem = uws_invitem_pop.querySelector(".uwsinv-item.uws-inventory-item-pop")
    const uvdurcont = uvinvitemelem.querySelector(".uws-item-durationsel-cont");
    const uvfrequency = uvdurcont.getAttribute("data-frequency") / 1;

    if (uvitemsels.time) {
        const uvmasteritemcode = uvitem.info.masteritemcode;
        const uvitemelements = uvitem.elements[uvmasteritemcode];

        if (typeof (uvitemelements.shifts["SHT" + uvitemsels.time]) == "object") {
            const uvfrecuency = uvfrequency / 1;
            const uvdurobjs = uvitemelements.shifts["SHT" + uvitemsels.time];
            const uvminslots = Object.keys(uvdurobjs)[0].replace("DUR", "") / 1 / uvfrecuency;
            //only min duration can be set
            //const uvmaxslots = Object.keys(uvdurobjs).pop().replace("DUR", "") / 1 / uvfrecuency;

            if (uvtimeline.noUiSlider.options.margin != uvminslots) {
                uvtimeline.noUiSlider.updateOptions({
                    margin: uvminslots,
                    /*limit: uvmaxslots*/
                }, false);
            }
        }
    }
}

/*Calculate Inventory Item Price*/
function uwsinvCalcPrice(uvitem, uvitemsels) {
    if (typeof (uvitem) == "undefined")
        uvitem = uws_inventory.popitem;
    if (typeof (uvitemsels) == "undefined")
        uvitemsels = uws_inventory.popitemsels;

    let uvtheprice = "";

    const uvprice = uvitem.price / 1;
    const uvduration = (uvitemsels.duration) ? uvitemsels.duration : 0;
    const uvfrequency = (uvitem.frequency) ? uvitem.frequency : 60;
    let uvntimeslots = 0;

    if (uvduration && uvfrequency)
        uvntimeslots = uvduration / uvfrequency;

    uvtheprice = (uvntimeslots) ? uvprice * uvntimeslots : uvprice;

    return uvtheprice;
}

/*Update all dynamic variables of itempop*/
function uwsinvUpdateItemPop(uvinvitemelem) {
    const uvitem = uws_inventory.popitem;
    const uvpaytypes = uvitem.info.paytypes;
    const uvbreakdownslabels = uvitem.library.breakdowns;
    const uvitemcurshift = uwsinvGetItemCurShift(uvitem, uws_inventory.popitemsels);
    const uvitemagreeprices = uwsinvGetItemAgreePrices(uvitem, uws_inventory.popitemsels);
    const uvpricesbreakdown = uwsinvGetItemPricesBreakdown(uvitem, uws_inventory.popitemsels);

    //add breakdown prices
    if (uws_inventory.popitem.info.globaltype != "virtual2")
        uwsinvUpdateDyElem(uvinvitemelem, "price-breakdown", uvpricesbreakdown);

    //agree prices
    if (uws_inventory.popitem.info.globaltype != "virtual2")
        uwsinvUpdateDyElem(uvinvitemelem, "agreement-prices", uvitemagreeprices);

    //paytypes prices and prices breakdown
    uvpaytypes.forEach(function (el) {
        if (typeof (uvitem.library.paytypes[el]) == "object") {
            const uvpaytypepay = uvitem.library.paytypes[el].pay;
            const uvpaybreakdowns = uvitemcurshift.breakdowns;

            if (typeof (uvpaybreakdowns) == "object") {
                const uvpaytypeprice = (uvpaytypepay != "0" && typeof (uvpaybreakdowns[uvpaytypepay].reseller["q" + uws_inventory.popitemsels.guests]) != "undefined" && uvpaybreakdowns[uvpaytypepay].reseller["q" + uws_inventory.popitemsels.guests]) ? uvpaybreakdowns[uvpaytypepay].reseller["q" + uws_inventory.popitemsels.guests].subtotal : uvpaytypepay;
                const uvpaydtypeprice = uwsFrontformatMoney(uvpaytypeprice, 1);

                uwsinvUpdateDyElem(uvinvitemelem, "paytype-" + el, uvpaydtypeprice);

                if (uws_inventory.popitemsels.paytype == el) {//current selected
                    if (uvpaytypeprice && uvpaytypeprice != "0")
                        uwsinvUpdateDyElem(uvinvitemelem, "addtocart-price", `(<span class='uwsprice' data-symbol='${uvitem.info.currency_symbol}'>${uvpaydtypeprice}</span>)`);
                    else
                        uwsinvUpdateDyElem(uvinvitemelem, "addtocart-price", "");

                    uwsinvSetPopitemSelection("selprice", uvpaytypeprice);
                }
            }
            else
                uwsinvSetPopitemSelection("selprice", "");
        }
    });

    //durations
    if (uws_inventory.popitemsels) {
        if (uws_inventory.popitemsels.duration)
            uwsinvUpdateDyElem(uvinvitemelem, "dduration", uwsFormatDuration(uws_inventory.popitemsels.duration));
        if (uws_inventory.popitemsels.time && uws_inventory.popitemsels.duration) {
            const uvendtime = uwsTimeAddMinutes(uws_inventory.popitemsels.time, uws_inventory.popitemsels.duration);
            uwsinvUpdateDyElem(uvinvitemelem, "ddurationrange", uwsFormatTime(uws_inventory.popitemsels.time) + " - " + uwsFormatTime(uvendtime));
        }
    }

    //btns helper text
    if (uws_inventory.popitemsels.paytype == "waitlist")
        uwsinvItemAddBtnsHelperTxt(uvinvitemelem, "(Join Waitlist)");
    else
        uwsinvItemAddBtnsHelperTxt(uvinvitemelem, "");

    if (uvinvitemelem.querySelector(".uws-itempop-footer .uwsactions"))
        if (uwsinvItemSelsValid(uvitem, uws_inventory.popitemsels))
            uvinvitemelem.querySelector(".uws-itempop-footer .uwsactions").classList.remove("uwsdisabled");
        else
            uvinvitemelem.querySelector(".uws-itempop-footer .uwsactions").classList.add("uwsdisabled");
}

/*Update Item Pop States Related With Cart*/
function uwsinvUpdateCartItemPop(uvinvitemelem) {
    const uvitem = uws_inventory.popitem;
    const uvcartitems = (typeof (uws_inventory.cart) != "undefined" && typeof (uws_inventory.cart.cartitems) != "undefined") ? uws_inventory.cart.cartitems : "";
    const uvnitemsadded = uwsInvGetItemInCartCount(uvcartitems, uvitem.info.mastercode);
    const uvtotalstock = (uvitem && typeof (uvitem.info.totalstock) != "undefined") ? uvitem.info.totalstock / 1 : "";
    const uvglobaltype = (uvitem && typeof (uvitem.info.globaltype) != "undefined") ? uvitem.info.globaltype : "";
    const uvaddcartelem = uvinvitemelem.querySelector(".uwsjs-item-addtocart");


    if (uvnitemsadded && uvglobaltype != "admission" && uvnitemsadded && uvtotalstock > uvnitemsadded) { //Can be added again
        uvaddcartelem.innerHTML = `<span>Add Another</span><span class="uws-count">${uvnitemsadded}</span>`;
        uvaddcartelem.setAttribute("data-forcenew", 1);

        const uvaddcheckbtn = uvinvitemelem.querySelector(".uwsjs-item-addtocart-andcheck");
        uvaddcheckbtn.setAttribute("data-forcenew", 1);
    }
    else if (uvnitemsadded)
        uvaddcartelem.innerHTML = `<span>Update</span><span class="uws-count">${uvnitemsadded}</span>`;
}

/*Get Item Prices Break Down*/
function uwsinvGetItemPricesBreakdown(uvitem, uvitemsels, uvforcepaytype = "") {
    if (!uvitemsels.paytype || uvitemsels.paytype == "inquire" || uvitem.library.paytypes[uvitemsels.paytype].pay == "0") return "";

    uvitemcurshift = uwsinvGetItemCurShift(uvitem, uvitemsels);
    const uvshiftpaytype = (uvforcepaytype) ? uvforcepaytype : uvitem.library.paytypes[uvitemsels.paytype].pay;
    const uvshiftpaytypelabel = (uvshiftpaytype == "agree") ? "Spend Agreement" : uvitem.library.paytypes[uvitemsels.paytype].name;
    let uvpricesbreakdown = "";

    if (typeof (uvitemcurshift.breakdowns) == "object") {
        const uvpayprices = uvitemcurshift.breakdowns[uvshiftpaytype].internal["q" + uvitemsels.guests];
        const uvcursymbol = uvitem.info.currency_symbol;
        const uvbreackdownslabels = uvitem.library.breakdowns;

        if (uvpayprices) {
            const uvpricetotal = uvpayprices.total;
            const uvtotallabel = (typeof (uvbreackdownslabels["total"]) != "undefined") ? uvbreackdownslabels["total"] : "Total";
            let uvpricesbreaklist = "";
            /*const uvbreakdownlabelkey = (uvshiftpaytype != "deposit" || uvshiftpaytype != "agree") ? "fullprepay" : "deposit";
            const uvbreakdownlabel = ((typeof (uvitem.breakdown_labels) == "object")) ? uvitem.breakdown_labels[uvbreakdownlabelkey] : "Pay Now";*/

            Object.entries(uvpayprices).forEach(([uvkey, uvvalue]) => {
                if (uvkey != "input" && uvvalue != "0" && uvkey != "total") {
                    const uvbreakdownlabel = (typeof (uvbreackdownslabels[uvkey]) != "undefined") ? uvbreackdownslabels[uvkey] : uvkey;
                    uvpricesbreaklist += `<div class='uwsbreakitem uwsbreakitem-${uvkey}'><span class='uwsname'>${uvbreakdownlabel}</span><span class='uwsprice' data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvvalue, 1)}</span></div>`;
                }
            });

            uvpricesbreakdown = `
                <div class="uws-togglecoll uwsactive">
                    <a class="uwsjs-toggle-collapse" href='#open-item-price-details'>
                        <div class="uwsname"><span>${uvshiftpaytypelabel}</span></div>
                        <div class="uwsbkpricecont"><div class="uwsprice" data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvpricetotal, 1)}</div><i class="uwsicon-right-open"></i></div>
                    </a>

                    <div class="uws-togglecoll-body">
                        <div class="uws-togglecoll-inner">
                            ${uvpricesbreaklist}
                            <div class='uwsbreakitem uwsbreakitem-total'><span class='uwsname'>${uvtotallabel}</span><span class='uwsprice' data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvpricetotal, 1)}</span></div>
                        </div>
                    </div>
                </div>
            `;

            if (uvshiftpaytype == "deposit")
                uvpricesbreakdown = uvpricesbreakdown + uwsinvGetItemPricesBreakdown(uvitem, uvitemsels, "agree");
        }
    }

    /*if (typeof (uvitemcurshift.breakdowns) == "object") {
        if(typeof (uvitem.breakdown_labels) == "object"){
            Object.entries(uvitem.breakdown_labels).forEach(([uvkey, uvvalue]) => {
                const uvpayprices = uvitemcurshift.breakdowns[uvkey].internal["q" + uvitemsels.guests];
                const uvcursymbol = uvitem.info.currency_symbol;
                const uvbreackdownslabels = uvitem.library.breakdowns;

                if (uvpayprices) {
                    const uvpricetotal = uvpayprices.total;
                    const uvtotallabel = (typeof (uvbreackdownslabels["total"]) != "undefined") ? uvbreackdownslabels["total"] : "Total";
                    let uvpricesbreaklist = "";
        
                    Object.entries(uvpayprices).forEach(([uvkey, uvvalue]) => {
                        if (uvkey != "input" && uvvalue != "0" && uvkey != "total") {
                            const uvbreakdownlabel = (typeof (uvbreackdownslabels[uvkey]) != "undefined") ? uvbreackdownslabels[uvkey] : uvkey;
                            uvpricesbreaklist += `<div class='uwsbreakitem uwsbreakitem-${uvkey}'><span class='uwsname'>${uvbreakdownlabel}</span><span class='uwsprice' data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvvalue, 1)}</span></div>`;
                        }
                    });
        
                    uvpricesbreakdown += `
                        <div class="uws-togglecoll uwsactive">
                            <a class="uwsjs-toggle-collapse" href='#open-item-price-details'>
                                <div class="uwsname"><span>${uvvalue}</span></div>
                                <div class="uwsbkpricecont"><div class="uwsprice" data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvpricetotal, 1)}</div><i class="uwsicon-right-open"></i></div>
                            </a>
        
                            <div class="uws-togglecoll-body">
                                <div class="uws-togglecoll-inner">
                                    ${uvpricesbreaklist}
                                    <div class='uwsbreakitem uwsbreakitem-total'><span class='uwsname'>${uvtotallabel}</span><span class='uwsprice' data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvpricetotal, 1)}</span></div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            });
        }
    }*/

    return uvpricesbreakdown;
}

/*Add helper text to inventory item popup action btns*/
function uwsinvItemAddBtnsHelperTxt(uvinvitemelem, uvhelpertext) {
    if (uvhelpertext) {
        if (uvinvitemelem.querySelector(".uws-itempop-footer .uwsjs-item-addtocart")) {
            if (!uvinvitemelem.querySelector(".uws-itempop-footer .uwsjs-item-addtocart .uwshelpertext"))
                uvinvitemelem.querySelector(".uws-itempop-footer .uwsjs-item-addtocart").insertAdjacentHTML("beforeend", `<span class='uwshelpertext'>${uvhelpertext}</span>`);

            uvinvitemelem.querySelector(".uws-itempop-footer .uwsjs-item-addtocart .uwshelpertext").innerHTML = uvhelpertext;
        }

        if (uvinvitemelem.querySelector(".uws-itempop-footer .uwsjs-item-addtocart-andcheck")) {
            if (!uvinvitemelem.querySelector(".uws-itempop-footer .uwsjs-item-addtocart-andcheck .uwshelpertext"))
                uvinvitemelem.querySelector(".uws-itempop-footer .uwsjs-item-addtocart-andcheck").insertAdjacentHTML("beforeend", `<span class='uwshelpertext'>${uvhelpertext}</span>`);

            uvinvitemelem.querySelector(".uws-itempop-footer .uwsjs-item-addtocart-andcheck .uwshelpertext").innerHTML = uvhelpertext;
        }
    }
    else {
        if (uvinvitemelem.querySelector(".uws-itempop-footer .uwsjs-item-addtocart"))
            if (uvinvitemelem.querySelector(".uws-itempop-footer .uwsjs-item-addtocart .uwshelpertext"))
                uvinvitemelem.querySelector(".uws-itempop-footer .uwsjs-item-addtocart .uwshelpertext").remove();
        if (uvinvitemelem.querySelector(".uws-itempop-footer .uwsjs-item-addtocart-andcheck"))
            if (uvinvitemelem.querySelector(".uws-itempop-footer .uwsjs-item-addtocart-andcheck .uwshelpertext"))
                uvinvitemelem.querySelector(".uws-itempop-footer .uwsjs-item-addtocart-andcheck .uwshelpertext").remove();
    }
}

/*Check if popitem is valid*/
function uwsinvItemSelsValid(uvitem, uvitemsels) {
    let uvitemisvalid = 1;
    const uvinvitemelem = document.querySelector(".uwsinv-item.uws-inventory-item-pop");

    //Check if requires time
    if (uvitem.header.timemode == "TimeSlot" && typeof (uvitem.shifts["SHT0"].all_times) == "object" && !uvitemsels.time)
        uvitemisvalid = 0;

    //Check if is book4time
    if (uvitem.info.vendor == "book4time" && !uvitemsels.time)
        uvitemisvalid = 0;

    //Check if requires duration and duration is in range
    if (uvitem.header.timemode == "TimeDuration" && typeof (uvitem.shifts["SHT0"].all_times) == "object") {
        const uvmasteritemcode = uvitem.info.masteritemcode;
        const uvitemelements = uvitem.elements[uvmasteritemcode];
        const uvallslotsvalid = uwsinvCheckValidSlotsOnDuration(uvitem, uvitemsels);
        let uvdurationerrormsg = "";

        if (typeof (uvitemelements.shifts["SHT" + uvitemsels.time]) != "object") {
            uvitemisvalid = 0;
            uvdurationerrormsg = "Try a different time.";
        }
        else if ((typeof (uvitemelements.shifts["SHT" + uvitemsels.time]["DUR" + uvitemsels.duration]) != "object")) {
            uvitemisvalid = 0;
            uvdurationerrormsg = "Time not available. Please select a different duration.";
        }
        else if (!uvallslotsvalid) {
            uvitemisvalid = 0;
            uvdurationerrormsg = "Time not available. Please select a different time slot.";
        }

        if (!uvitemisvalid) {
            uwsinvUpdateDyElem(uvinvitemelem, "duration-error", uvdurationerrormsg);
            uvinvitemelem.classList.add("uwshasdurationerror");
        }
        else {
            uwsinvUpdateDyElem(uvinvitemelem, "duration-error", uvdurationerrormsg);
            uvinvitemelem.classList.remove("uwshasdurationerror");
        }
    }

    // Check if requires bottle selection
    if (uws_invitem_pop.querySelector(".uwsdy-bottle") && (uwsGetCookie("uv_itembottles") || uws_has_bottles_selected))
        uvitemisvalid = 1;
    else if (uws_invitem_pop.querySelector(".uwsdy-bottle") && !uws_has_bottles_selected)
        uvitemisvalid = 0;

    return uvitemisvalid;
}

/*Check if dureation selected has invalid time slots*/
function uwsinvCheckValidSlotsOnDuration(uvitem, uvitemsels) {
    let uvvalid = 1;

    const uvinvitemelem = uws_invitem_pop.querySelector(".uwsinv-item.uws-inventory-item-pop")
    const uvdurcont = uvinvitemelem.querySelector(".uws-item-durationsel-cont");
    const uvfrequency = uvdurcont.getAttribute("data-frequency") / 1;

    const uvselectedslots = uvitemsels.duration / uvfrequency;
    const uvavslots = uvitem.slots;
    const uvalltimeskeys = Object.keys(uvavslots);
    const uvstart = uvalltimeskeys.indexOf("SHT" + uvitemsels.time);
    const uvselslots = uvalltimeskeys.slice(uvstart, uvstart + uvselectedslots);

    uvvalid = (uvselslots.some(slot => uvavslots[slot] === -1)) ? 0 : uvvalid;

    return uvvalid;
}

/*When duration fails check if there is a duration available*/
function uwsinvFindAdjDuration(uvitem, uvitemsels) {

    //Check if requires duration and duration is in range
    if (uvitem.header.timemode == "TimeDuration" && typeof (uvitem.shifts["SHT0"].all_times) == "object") {
        const uvinvitemelem = document.querySelector(".uwsinv-item.uws-inventory-item-pop");
        const uvmasteritemcode = uvitem.info.masteritemcode;
        const uvitemelements = uvitem.elements[uvmasteritemcode];

        if (typeof (uvitemelements.shifts["SHT" + uvitemsels.time]) == "object" && typeof (uvitemelements.shifts["SHT" + uvitemsels.time]["DUR" + uvitemsels.duration]) != "object") {
            const uvfrecuency = uvinvitemelem.querySelector(".uws-item-durationsel-cont").getAttribute("data-frequency") / 1;
            const uvnextduration = uvitemsels.duration + uvfrecuency;
            const uvprevduration = uvitemsels.duration - uvfrecuency;

            if (typeof (uvitemelements.shifts["SHT" + uvitemsels.time]["DUR" + uvnextduration]) == "object") {//next duration is available
                const uvtimeline = uvinvitemelem.querySelector(".uwstimeline");
                let uvcurslider = uvtimeline.noUiSlider.get();
                uvcurslider[1] = (uvcurslider[1] / 1) + 1;

                setTimeout(function () {
                    uvtimeline.noUiSlider.set(uvcurslider);
                }, 300);
            }
            else if (typeof (uvitemelements.shifts["SHT" + uvitemsels.time]["DUR" + uvprevduration]) == "object") {//prev duration is available
                const uvtimeline = uvinvitemelem.querySelector(".uwstimeline");
                let uvcurslider = uvtimeline.noUiSlider.get();
                uvcurslider[0] = (uvcurslider[0] / 1) - 1;

                setTimeout(function () {
                    uvtimeline.noUiSlider.set(uvcurslider);
                }, 300);
            }
        }
    }
}

/*Get agreemet prices for item*/
function uwsinvGetItemAgreePrices(uvitem, uvitemsels) {
    let uvprices = "";
    const uvitemcurshift = uwsinvGetItemCurShift(uvitem, uvitemsels);
    const uvitemagreetype = uvitem.library.paytypes[uvitemsels.paytype].agree;

    if (typeof (uvitemcurshift.components) == "object") {
        uvitemcurshift.components.forEach(function (el) {
            const uvpricingname = (uvitem.library.pricings[el.pricing]) ? uvitem.library.pricings[el.pricing] : "";
            const uvdprice = (uvitemagreetype && uvitemagreetype != "0" && typeof (el[uvitemagreetype]["q" + uvitemsels.guests]) != "undefined") ? uwsFrontformatMoney(el[uvitemagreetype]["q" + uvitemsels.guests].subtotals, 1) : "0";

            if (!(uvitemsels.paytype != "paytype" && uvdprice == "0"))
                uvprices += `<div class="uwspricecont"><div class="uwslabel">${uvpricingname}</div><div class="uwsprice" data-symbol="${uvitem.info.currency_symbol}">${uvdprice}</div></div>`;
        });
    }

    return uvprices;
}

/*Get Currrent Shift*/
function uwsinvGetItemCurShift(uvitem, uvitemsels) {
    const uvmasteritemcode = uvitem.info.masteritemcode;
    const uvitemelements = uvitem.elements[uvmasteritemcode];

    let uvcursht = (uvitemsels.time) ? "SHT" + uvitemsels.time : "SHT0";
    const uvcurdur = (uvitemsels.duration) ? "DUR" + uvitemsels.duration : "DUR0";

    let uvcurshift = ""

    if (typeof (uvitemelements.shifts[uvcursht]) != "object")//if time shift is not there take SHT0
        uvcursht = "SHT0";

    if (typeof (uvitemelements.shifts[uvcursht][uvcurdur]) == "object") {//if duration shift is not there take first object
        uvcurshift = uvitemelements.shifts[uvcursht][uvcurdur];
    }
    else {
        const uvfirstkey = Object.keys(uvitemelements.shifts[uvcursht])[0];
        uvcurshift = uvitemelements.shifts[uvcursht][uvfirstkey];
    }

    return uvcurshift;
}

/*Update dynamic variables on inventory item*/
function uwsinvUpdateDyElem(uvinvitemelem, uvvarname, uvvalue) {
    if (uvinvitemelem) {
        const uvelems = uvinvitemelem.querySelectorAll(".uwsdy-" + uvvarname);

        Array.prototype.forEach.call(uvelems, function (el, i) {
            el.innerHTML = uvvalue;
        });
    }
}

//Show popup with items options
function uwsInvSelItemPop(uvecoitems) {
    if (!uws_invsel_pop)//create pop if it doesn't exist
        uws_invsel_pop = uwsCreatePop("uws-invitsellist-pop");

    let uvinvselpop = uws_inventory.templates["inventory-itemslist-sel-pop"];
    let uvinvsellist = "";

    Object.keys(uvecoitems).forEach((el) => {
        const uvitem = uws_inventory.items[uvecoitems[el]];

        const uvinvselitem = uwsinvReplaceItemVars(uvitem, uws_inventory.templates["inventory-itemslist-sel-item"]);
        uvinvsellist += uvinvselitem;
    });

    uvinvselpop = uvinvselpop.replace(/{poptitle}/g, "Item Selection");
    uvinvselpop = uvinvselpop.replace(/{selitems}/g, uvinvsellist);

    uwsClearPopup(uws_invsel_pop, uvinvselpop);
    setTimeout(function () { uwsFadePopup(uws_invsel_pop); }, 100);
}

//Show popup with items pricing breakdown
// function uwsInvBreakItemPop(uvmastercode, uvitempresels) {
//     if (!uws_invbreak_pop)//create pop if it doesn't exist
//         uws_invbreak_pop = uwsCreatePop("uws-invitembreakdown-pop");

//     if (typeof (uws_inventory.itempop) == "object" && typeof (uws_inventory.itempop.popitemloaderfn) == "function")
//         uws_inventory.itempop.popitemloaderfn();
//     else
//         uwsShowGLoader();

//     if (typeof (uws_inventory.itempop) == "object" && typeof (uws_inventory.itempop.popitem) == "object")
//         uws_invbreak_pop = uws_inventory.itempop.popitem;

//     const uvreturnprox = (typeof (uws_inventory.proxies) != "undefined") ? 0 : 1;
//     let uvinventoryload = uws_proxy + "&uvaction=uwspx_inventoryitempop";
//     uvinventoryload = uvinventoryload + "&mastercode=" + uvmastercode + "&returnprox=" + uvreturnprox;

//     if (typeof (uvitempresels) == "object") {
//         if (uvitempresels.sectionid)
//             uvinventoryload += "&sectionid=" + uvitempresels.sectionid;
//         if (uvitempresels.locationid)
//             uvinventoryload += "&locationid=" + uvitempresels.locationid;
//     }

//     //clean global popitem vars
//     uws_inventory.popitem = "";
//     uws_inventory.popitemsels = "";

//     let uvrequest = new XMLHttpRequest();
//     uvrequest.open('GET', uvinventoryload, true);
//     uvrequest.onload = function () {
//         if (this.status >= 200 && this.status < 400) {
//             let uvresponse = this.response;
//             uvresponse = JSON.parse(uvresponse);

//             if (typeof (uws_inventory.itempop) == "object" && typeof (uws_inventory.itempop.popshowfn) == "function")
//                 uws_inventory.itempop.popshowfn();
//             else
//                 uwsHideGLoader();


//             uwsinvAddVarsToGlobal(uvresponse);

//             if (typeof (uvresponse.html) != "undefined") {
//                 if (typeof (uws_inventory.itempop) == "object" && typeof (uws_inventory.itempop.popitemloc) == "object") {
//                     uws_inventory.itempop.popitemloc.innerHTML = "";
//                     uws_inventory.itempop.popitemloc.insertAdjacentHTML("beforeend", uvresponse.html);
//                 }
//                 else
//                     uwsClearPopup(uws_invbreak_pop, uvresponse.html);

//                 if (typeof (uvresponse["popitem-module"]) != "undefined" && uvresponse["popitem-module"] == "membership")
//                     uwsmemInitInvItemMemberships(uvresponse);
//                 else
//                     uwsInitInvItem(uws_invbreak_pop.querySelector(".uwsinv-item.uws-inventory-item-pop"));

//                 uws_invbreak_pop.setAttribute("data-closecallback", "uwsInvItemPopClosed");
//                 uwsFadePopup(uws_invbreak_pop);

//                 if (typeof (uvhookItemPopOpened) == "function" && typeof (uvresponse.popitem) != "undefined")
//                     uvhookItemPopOpened(uvresponse.popitem);
//             }
//         } else {
//             //console.log("UVJS Error: Server returned an error");

//             const uvsverror = `
//                 <div class='uws-sverror-cont'>
//                     <div class='uwstitle'><i class='uwsicon-warning-empty'></i> Something Went Wrong</div>
//                     <div class='uwstext'>Check back later for updates.</div>
//                 </div>
//             `;

//             uwsClearPopup(uws_invbreak_pop, uvsverror);
//             uwsFadePopup(uws_invbreak_pop);
//             uwsHideGLoader();
//         }
//     };
//     uvrequest.onerror = function () {
//         console.log("UVJS Error: Request Error");
//     };
//     uvrequest.send();
// }

/*Replace item vars*/
function uwsinvReplaceItemVars(uvitem, uvtempl) {
    let uvinvitem = "";

    if (typeof (uvitem) == "object" && uvtempl) {
        uvinvitem = uvtempl;

        const uvmascode = uvitem.masteritemcode;
        const uvitemcapacitylabel = (uvitem.capacity > 1) ? uwsFrontLang("guests") : uwsFrontLang("guest");
        const uvpricingdisp = uwsFrontLang(uvitem.pricingdisplay);
        const uvshowprice = (uvitem.listprice && uvitem.listprice / 1) ? uvitem.listprice / 1 : "";
        const uvpaybase = (uvitem.paybase) ? uvitem.paybase : "";
        const uvinactive = (uvitem.inactive && uvitem.inactive === 1) ? "uwsinactive" : "";
        const uvfrontprice = (uvinactive)
            ? "Unavailable"
            : (uvshowprice ? uwsFrontformatMoney(uvshowprice, 0) : uvitem.listzero);
        const uvpricetypeclass = (uvshowprice) ? "" : "uwspricelistzero";
        const uvpaynowprice = (uvpaybase) ? uwsFrontformatMoney(uvpaybase, 0) : "";
        const uvpaynowcontclass = (uvpaybase) ? "" : "uwsnopaynow";
        const uvcurrencysymbol = uvitem.currency_symbol;
        const uvitempaynowdiv = (uvpaybase) ? `<div class="uwspaynow"><span>${uvitem.basedisplay}</span> <span class="uwsprice ${uvpricetypeclass}" data-symbol="${uvcurrencysymbol}">${uvpaynowprice}</span></div>` : "";
        const uvbadge = (uvitem.badge) ? uvitem.badge : "";
        const uvitemguestbubble = (uvitem.capacity) ? `<div class="uwsbubble"><i class="uwsicon-itemguests"></i> <span>${uvitem.capacity}</span></div>` : "";
        const uvitemtimebubble = (uvitem.timelabel) ? `<div class="uwsbubble"><i class="uwsicon-itemclock"></i> <span>${uvitem.timelabel}</span></div>` : "";
        const uvitemdisclaimer = (uvitem.disclaimer) ? `<div class="uwsitemdisclaimer"><span class="uwsasteric">*</span><span>${uvitem.disclaimer}</span></div>` : "";
        const uvitempricing = `<a class="uwsjs-inv-item-pricing uwspricebreakdown uwspricing" href="#uwsinv-show-breakdown-${uvitem.mastercode}" aria-label="Pricing Breakdown ${uvitem.itemname}" data-mastercode="${uvitem.mastercode}"><i class="uwsicon-iteminfo"></i><span>${uvitem.pricingdisplay}</span></a>`;
        const uvitemhigh = (uvitem.highlight) ? uvitem.highlight : "";
        const uvitemmoreinfo = (uvitem.descr || (uvitemhigh && typeof (uvitem.itemimages) == "object")) ? ` <a href="javascript:;" class="uwsjs-inv-item-showinfo" data-mastercode="${uvitem.mastercode}" aria-label='View More Info'><span>More Info</span></a>` : "";
        const uviteminfodiv = (uvitemhigh || uvitemmoreinfo) ? `<div class="uwsextrainfo">${uvitemhigh}${uvitemmoreinfo}</div>` : "";
        const uvselbtnlabel = (uvitem.label) ? uvitem.label : "Book";
        const uvaddseccode = (typeof (uvitem.selectedsectionid) != "undefined") ? `data-seccode="${uvitem.selectedsectionid}"` : "";
        const uvaddloccode = (typeof (uvitem.selectedlocationid) != "undefined") ? `data-loccode="${uvitem.selectedlocationid}"` : "";
        //const uvaddforcenew = (typeof(uvitem.forcenew) != "undefined") ? `data-forcenew="${uvitem.selectedforcenew}"` : "";
        //const uvitemselectbtn = `<a class="uwsjs-inv-item-select uws-btn uws-btn-p" href="#uwsinv-select-item-${uvmascode}" aria-label="Select ${uvitem.itemname}" data-mastercode='${uvitem.mastercode}' ${uvaddseccode} ${uvaddloccode}><span>${uvselbtnlabel}</span></a>`;
        const uvitemselectbtn = `<div class='uwsinvitembtncont'><a class="uwsjs-inv-item-select uws-btn uws-btn-p" href="#uwsinv-select-item-${uvmascode}" aria-label="Select ${uvitem.itemname}" data-mastercode='${uvitem.mastercode}' ${uvaddseccode} ${uvaddloccode}><span>${uvselbtnlabel}</span></a></div>`;

        uvinvitem = uvinvitem.replace(/{mastercode}/g, uvitem.mastercode);
        uvinvitem = uvinvitem.replace(/{mascode}/g, uvmascode);
        uvinvitem = uvinvitem.replace(/{itemname}/g, uvitem.itemname);
        uvinvitem = uvinvitem.replace(/{itemcapacity}/g, uvitem.capacity);
        uvinvitem = uvinvitem.replace(/{itemcapacitylabel}/g, uvitemcapacitylabel);
        uvinvitem = uvinvitem.replace(/{itemhighlight}/g, uvitem.highlight);
        uvinvitem = uvinvitem.replace(/{frontprice}/g, uvfrontprice);
        uvinvitem = uvinvitem.replace(/{pricingdisplay}/g, uvpricingdisp);
        uvinvitem = uvinvitem.replace(/{currencysymbol}/g, uvitem.currency_symbol);
        uvinvitem = uvinvitem.replace(/{paytype}/g, uvitem.paytype);
        uvinvitem = uvinvitem.replace(/{pricetypeclass}/g, uvpricetypeclass);
        uvinvitem = uvinvitem.replace(/{paynowprice}/g, uvpaynowprice);
        uvinvitem = uvinvitem.replace(/{paynowcontclass}/g, uvpaynowcontclass);
        uvinvitem = uvinvitem.replace(/{itempaynowdiv}/g, uvitempaynowdiv);
        uvinvitem = uvinvitem.replace(/{itembadge}/g, uvbadge);
        uvinvitem = uvinvitem.replace(/{itemguestbubble}/g, uvitemguestbubble);
        uvinvitem = uvinvitem.replace(/{itemtimebubble}/g, uvitemtimebubble);
        uvinvitem = uvinvitem.replace(/{itemdisclaimer}/g, uvitemdisclaimer);
        uvinvitem = uvinvitem.replace(/{itempricing}/g, uvitempricing);
        uvinvitem = uvinvitem.replace(/{iteminfodiv}/g, uviteminfodiv);
        uvinvitem = uvinvitem.replace(/{itemselectbtn}/g, uvitemselectbtn);
        uvinvitem = uvinvitem.replace(/{globaltype}/g, uvitem.globaltype);
        uvinvitem = uvinvitem.replace(/{inactive}/g, uvinactive);
    }

    return uvinvitem;
}

/*Add minutes to a time and return the new time*/
function uwsTimeAddMinutes(uvtime, uvminutes) {
    uvtimeminutes = ((uvtime.substring(1, 3) / 1) * 60) + (uvtime.substring(3, 5) / 1);
    const uvallminutes = uvtimeminutes + uvminutes;

    let uvhours = Math.floor(uvallminutes / 60) % 24;
    uvhours = (uvhours < 10) ? "0" + uvhours : uvhours;
    let uvtheminutes = uvallminutes % 60;
    uvtheminutes = (uvtheminutes < 10) ? "0" + uvtheminutes : uvtheminutes;

    return uvtime.substring(0, 1) + uvhours + uvtheminutes;
}

/*Format from minutes to duration*/
function uwsFormatDuration(uvduration) {
    let uvdurationstr = "";

    const uvminutes = uvduration % 60;
    let uvhours = uvduration / 60;
    uvhours = Math.floor(uvhours);

    if (uvhours) {
        uvhourslabel = (uvhours == 1) ? "Hour" : "Hours";
        uvdurationstr = uvhours + " " + uvhourslabel;
    }

    if (uvdurationstr && uvhours)
        uvdurationstr += " ";

    if (uvminutes)
        uvdurationstr += uvminutes + "m";

    return uvdurationstr;
}

/*Format uvtime*/
function uwsFormatTime(uvtime) {
    let timeampm = "";

    timevar = uvtime.toString();
    let timehour = uvtime.substring(1, 3);
    let timemin = uvtime.substring(3, 5);
    let ampm = "am";

    if (parseInt(timehour) >= 12) {
        timehour = timehour - 12;
        ampm = "pm";
    }

    timehour = (timehour == 0) ? "12" : timehour;

    timeampm = parseInt(timehour) + ":" + timemin + "" + ampm;

    return timeampm;
}

/*Format Money*/
function uwsFrontformatMoney(uvmoney, uvremovezerocents) {
    uvmoney = uvmoney / 1;
    uvmoney = uvmoney.uwsFormatMoney(2, ',', '.');
    uvmoney = uvmoney.replace(/\.(\d+)/, ".<span>$1</span>");

    if (uvremovezerocents)
        uvmoney = uvmoney.replace(".<span>00</span>", "");

    return uvmoney;
}

/*Update popitem selections*/
function uwsinvSetPopitemSelection(uvselname, uvselvalue) {
    if (typeof (uws_inventory.popitemsels) == "undefined")
        uws_inventory.popitemsels = {};

    uws_inventory.popitemsels[uvselname] = uvselvalue;
}

/*Set Cartcode Cookie*/
function uwsInvSetCartCookie(uvcartcode) {
    uwsSetCookie(uws_inventory_cookiename, uvcartcode, 7);
    uws_conf_inventory_cartcode = uvcartcode;
}

/*Get Cartcode Cookie*/
function uwsInvGetCartCookie() {
    let uvcartcode = uwsGetCookie(uws_inventory_cookiename);
    if (!uvcartcode && uws_conf_inventory_cartcode)
        uvcartcode = uws_conf_inventory_cartcode;

    return uvcartcode;
}

/*Initial load of inventory*/
function uwsinvInitBlock(uvinvblock) {
    if (uvinvblock) {
        uws_inventory_instance++;
        const uveventcode = uvinvblock.getAttribute("data-eventcode");
        const uvhomeeventcode = (uvinvblock.getAttribute("data-homeeventcode")) ? uvinvblock.getAttribute("data-homeeventcode") : "";
        const uvhomename = (uvinvblock.getAttribute("data-homename")) ? uvinvblock.getAttribute("data-homename") : "";
        const uvinithtml = "<div class='uws-integration uws-inventory-stage uwsdy-cartactive-class uws-inventory-stage-" + uws_inventory_instance + " uwsloading' data-instance='" + uws_inventory_instance + "'><div class='uws-inventoryloader'><div class='uwsloadingmsg'><div class='uws-loader-uvicon'></div><div class='uwsloadingtxt'>Loading Experiences...</div></div><div class='uwsloadingbkt'></div><div class='uwsloadingbitem'></div><div class='uwsloadingbitem'></div><div class='uwsloadingbkt'></div></div><div class='uws-inventory-load'></div></div>";

        uvinvblock.innerHTML = uvinithtml;

        const uvinvstage = document.querySelector(".uws-inventory-stage-" + uws_inventory_instance);
        const uvintegration = (document.querySelector(".uws-booking-calendar")) ? `&integration=calendar` : '';

        const uvreturntempl = 1; //(typeof (uws_inventory.templates) != "undefined") ? 0 : 1;
        let uvinventoryload = uws_proxy + "&uvaction=uwspx_inventoryinit";
        uvinventoryload = uvinventoryload + "&eventcode=" + uveventcode + "&cartcode=" + uwsInvGetCartCookie() + uvintegration + "&homeeventcode=" + uvhomeeventcode + "&homename=" + uvhomename + "&returntempl=" + uvreturntempl;

         // @egt [UWS-7297]
        if(typeof urvenue_ws_inventory_vars !== "undefined" && urvenue_ws_inventory_vars.targetNonce) {
            uvinventoryload = uvinventoryload + "&uws_nonce=" + encodeURIComponent(urvenue_ws_inventory_vars.targetNonce);
        }

        let uvrequest = new XMLHttpRequest();
        uvrequest.open('GET', uvinventoryload, true);
        uvrequest.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                let uvresponse = this.response;
                uvresponse = JSON.parse(uvresponse);

                uwsinvListProcessResponse(uvinvstage, uvresponse);

                if (typeof (uvhookInventoryListLoaded) == "function") {
                    uvhookInventoryListLoaded(uvresponse);
                }

                if (typeof sevenroomsids !== 'undefined' && Array.isArray(sevenroomsids)) {
                    sevenroomsInventory_list();
                }

                if (typeof opentablearray !== 'undefined' && Array.isArray(opentablearray)) {
                    opentableInventory_list();
                }
            } else {
                //console.log("UVJS Error: Server returned an error");

                const uvsverror = `
                    <div class='uws-inventory-list-noitmes'>
                        <div class='uwstitle'><i class='uwsicon-warning-empty'></i> Something Went Wrong</div>
                        <div class='uwstext'>Check back later for updates.</div>
                    </div>
                `;

                uvinvstage.querySelector(".uws-inventory-load").innerHTML = uvsverror;

                if (uvinvstage.querySelector(".uws-inventory-load").querySelectorAll(".uws-booktype-item").length == 1)
                    uvinvstage.querySelector(".uws-inventory-load").querySelector(".uws-booktype-item").classList.add("uwsactive");

                uvinvstage.classList.remove("uwsloading");
            }
        };
        uvrequest.onerror = function () {
            console.log("UVJS Error: Request Error");
        };
        uvrequest.send();
    }
}

/*Init Add-On Venues - Load of inventory*/
function uwsinvInitAddOnVenueBlock(uvinvblock) {
    if (uvinvblock) {
        uws_inventory_instance++;
        const uvhomeeventcode = (uvinvblock.getAttribute("data-homeeventcode")) ? uvinvblock.getAttribute("data-homeeventcode") : "";
        const uvhomename = (uvinvblock.getAttribute("data-homename")) ? uvinvblock.getAttribute("data-homename") : "";

        const uvinithtml = "<div class='uws-integration uws-inventory-stage uwsdy-cartactive-class uws-inventory-stage-" + uws_inventory_instance + " uwsloading' data-instance='" + uws_inventory_instance + "'><div class='uws-inventoryloader'><div class='uwsloadingmsg'><div class='uws-loader-uvicon'></div><div class='uwsloadingtxt'>Loading Experiences...</div></div><div class='uwsloadingbkt'></div><div class='uwsloadingbitem'></div><div class='uwsloadingbitem'></div><div class='uwsloadingbkt'></div></div><div class='uws-inventory-load'></div></div>";

        uvinvblock.innerHTML = uvinithtml;

        const uvinvstage = document.querySelector(".uws-inventory-stage-" + uws_inventory_instance);
        const uvhybridwidget = document.querySelector(".uws-inventory-widget");
        const uvintegration = (document.querySelector(".uws-booking-calendar")) ? `&integration=calendar` : '';

        const uvreturntempl = 1;
        let uvinventoryload = uws_proxy + "&uvaction=uwspx_inventoryaddonvenues";

        const uvmainvenuecode = uvinvblock.getAttribute("data-mainvenuecode");
        const uvvenuecode = uvinvblock.getAttribute("data-venuecode");
        const uvdate = uvinvblock.getAttribute("data-date");
        const uvmanagementid = uvinvblock.getAttribute("data-managementid");
        const uvglobaltype = uvinvblock.getAttribute("data-globaltype");
        const uvaddmixeco = (uvinvblock.getAttribute("data-mixecozones")) ? uvinvblock.getAttribute("data-mixecozones") : 0;
        const uvissnippetinteg = uwsIsSnippetIntegration();

        uvinventoryload = uvinventoryload + "&cartcode=" + uwsInvGetCartCookie() + uvintegration + "&homeeventcode=" + uvhomeeventcode + "&homename=" + uvhomename + "&returntempl=" + uvreturntempl + "&mainvenuecode=" + uvmainvenuecode + "&venuecode=" + uvvenuecode + "&date=" + uvdate + "&managementid=" + uvmanagementid + "&globaltype=" + uvglobaltype + "&mixecozones=" + uvaddmixeco;

        // @egt [UWS-7297]
        if(typeof urvenue_ws_inventory_vars !== "undefined" && urvenue_ws_inventory_vars.targetNonce) {
            uvinventoryload = uvinventoryload + "&uws_nonce=" + encodeURIComponent(urvenue_ws_inventory_vars.targetNonce);
        }

        let uvrequest = new XMLHttpRequest();
        uvrequest.open('GET', uvinventoryload, true);
        uvrequest.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                let uvresponse = this.response;
                uvresponse = JSON.parse(uvresponse);

                uwsinvListProcessResponse(uvinvstage, uvresponse);

                if (typeof (uvhookInventoryListLoaded) == "function") {
                    uvhookInventoryListLoaded(uvresponse);
                }

                uvinvstage.classList.add("uwsjs-loadaddonvenue-widget", "uwsaddonvenue-widget");
                if (uvissnippetinteg || uvhybridwidget) uvinvblock.classList.add("uwsjs-loadaddonvenue-widget", "uwsaddonvenue-widget");
                uvinvstage.innerHTML = uvresponse.markup;

                if (uvissnippetinteg || uvhybridwidget)
                    uwsinventoryinitwidget(uvinvblock);
                else
                    uwsInitInventoryWidgets();

            } else {
                const uvsverror = `
                    <div class='uws-inventory-list-noitmes'>
                        <div class='uwstitle'><i class='uwsicon-warning-empty'></i> Something Went Wrong</div>
                        <div class='uwstext'>Check back later for updates.</div>
                    </div>
                `;

                uvinvstage.querySelector(".uws-inventory-load").innerHTML = uvsverror;

                if (uvinvstage.querySelector(".uws-inventory-load").querySelectorAll(".uws-booktype-item").length == 1)
                    uvinvstage.querySelector(".uws-inventory-load").querySelector(".uws-booktype-item").classList.add("uwsactive");

                uvinvstage.classList.remove("uwsloading");
            }
        };
        uvrequest.onerror = function () {
            console.log("UVJS Error: Request Error");
        };
        uvrequest.send();
    }
}


/**
 * Checks if the global variable `UV` is defined to determine snippet integration.
 *
 * @returns {boolean} Returns true if `UV` is defined, otherwise returns false.
 */
function uwsIsSnippetIntegration() {
    return typeof UV !== 'undefined';
}

/*Init Booking Calendar*/
function uwsinvInitBKCalendar(uvbkcalstage) {
    const uvbkcaldp = uvbkcalstage.querySelector(".uwsjs-booking-calendar-dp");
    if (uvbkcaldp) {
        const uvmindate = uvbkcalstage.getAttribute("data-mindate");
        const uvdate = uvbkcalstage.getAttribute("data-date");
        const uvmaxdate = uvbkcalstage.getAttribute("data-maxdate");
        const uvvenuecode = uvbkcalstage.getAttribute("data-venuecode");
        const uvecozone = uvbkcalstage.getAttribute("data-ecozone");

        uws_bkcal_dp = new Litepicker({
            element: uvbkcaldp,
            minDate: uvmindate,
            maxDate: uvmaxdate,
            inlineMode: 1,
            singleMode: 1,
            showTooltip: 0,
            firstDay: 0,
            startDate: uvdate,
            setup: function (n) {
                n.on("selected", function (n, t) {
                    const uvseldate = n.format('YYYY-MM-DD');
                    const uvselsdate = n.format('YYYYMMDD');
                    //const uvddate = uws_dp_abdates[n.getMonth()] + " " + n.getDate() + ", " + n.getFullYear();
                    uvbkcalstage.setAttribute("data-date", uvseldate);

                    const uveventcode = "EVE" + uvvenuecode.replace("VEN", "") + uvecozone.replace("ECZ", "") + uvselsdate;
                    const uvdyeventscont = document.querySelectorAll(".uvdy-eventcontent");
                    Array.prototype.forEach.call(uvdyeventscont, function (el, i) {
                        el.setAttribute("data-eventcode", uveventcode);

                        if (el.classList.contains("uwsloadonselect"))
                            el.classList.add("uwstoload");
                    });

                    uwsLoadDynamicEvents();
                }),
                    n.on('render:day', (day, date) => {
                        day.innerHTML = `<span>${day.innerHTML}</span>`;
                    }),
                    n.on('render:month', (month, date) => {
                        const uvseldate = date.format('YYYY-MM-DD');
                        uws_inventory.dpcurrentmont = uvseldate;
                    }),
                    n.on('change:month', (date, calendarIdx) => {
                        const uvseldate = date.format('YYYY-MM-DD');
                        uwsinvGetBKCalendarDisDates(uvbkcalstage, uvseldate, uvvenuecode, uvecozone);
                    });
            }
        });

        uwsinvGetBKCalendarDisDates(uvbkcalstage, uvdate, uvvenuecode, uvecozone);
    }
}

/**
 * Retrieves booking calendar display dates.
 * 
 * @param {Element} uvbkcalstage - The booking calendar stage element.
 * @param {string} uvdate - The date.
 * @param {string} uvvenuecode - The venue code.
 * @param {string} uvecozone - The ecozone.
 */
function uwsinvGetBKCalendarDisDates(uvbkcalstage, uvdate, uvvenuecode, uvecozone) {
    const uvbkcaldp_cont = uvbkcalstage.querySelector(".uws-bookingcal-dpcont");
    const uvdpmonth = (typeof (uws_inventory.dpcurrentmont) != "undefined") ? uws_inventory.dpcurrentmont : "";
    const uvmonthcloseddates = (uvdpmonth && typeof (uws_inventory.noinventorydates) == "object" && typeof (uws_inventory.noinventorydates["date:" + uvdpmonth]) != "undefined") ? uws_inventory.noinventorydates["date:" + uvdpmonth] : "";

    if (uvmonthcloseddates) {
        uws_bkcal_dp.setLockDays(uvmonthcloseddates);
    } else {
        uvbkcaldp_cont.classList.add("uwsloading");

        let uvnoinventorydatesproxy = uws_proxy + "&uvaction=uwspx_noinventorydates";
        uvnoinventorydatesproxy = uvnoinventorydatesproxy + "&date=" + uvdate + "&venuecode=" + uvvenuecode + "&ecozone=" + uvecozone;

        // @egt [UWS-7297]
        if(typeof urvenue_ws_inventory_vars !== "undefined" && urvenue_ws_inventory_vars.targetNonce) {
            uvnoinventorydatesproxy = uvnoinventorydatesproxy + "&uws_nonce=" + encodeURIComponent(urvenue_ws_inventory_vars.targetNonce);
        }

        let uvrequest = new XMLHttpRequest();
        uvrequest.open('GET', uvnoinventorydatesproxy, true);
        uvrequest.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                let uvresponse = this.response;
                uvresponse = JSON.parse(uvresponse);

                if (typeof (uvresponse) == "object")
                    uwsbkCalendarAddVars(uvresponse);

                const uvloadedmonthcloseddate = (uvdpmonth && typeof (uws_inventory.noinventorydates) == "object" && typeof (uws_inventory.noinventorydates["date:" + uvdpmonth]) != "undefined") ? uws_inventory.noinventorydates["date:" + uvdpmonth] : "";

                if (uvloadedmonthcloseddate)
                    uws_bkcal_dp.setLockDays(uvloadedmonthcloseddate);

                uvbkcaldp_cont.classList.remove("uwsloading");
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


/**
 * Adds variables to the uwsinventory calendar.
 * @param {Object} uvresponse - The response object containing availability information.x
 */
function uwsbkCalendarAddVars(uvresponse) {
    if (typeof (uvresponse.availabilityinfo) == "object" && typeof (uvresponse.availabilityinfo.monthdate) != "undefined" && typeof (uvresponse.availabilityinfo.noinventorydates) != "undefined") {
        window.uws_inventory.noinventorydates = window.uws_inventory.noinventorydates || {};
        uws_inventory.noinventorydates["date:" + uvresponse.availabilityinfo.monthdate] = uvresponse.availabilityinfo.noinventorydates;
    }
}

/*Update paytype*/
uwsClickListener(".uwsjs-item-update-paytype", function (e) {
    e.preventDefault();

    const uvpaytypesbtns = this.closest(".uwspayoptscont").querySelectorAll(".uwsjs-item-update-paytype");
    Array.prototype.forEach.call(uvpaytypesbtns, function (el, i) {
        el.classList.remove("uwsactive");
    });
    this.classList.add("uwsactive");

    uwsinvSetPopitemSelection("paytype", this.getAttribute("data-paytype"))
    uwsinvUpdateItemPop(this.closest(".uwsinv-item"));
});

/*Remove from cart*/
uwsClickListener(".uwsjs-removecartitem", function (e) {
    e.preventDefault();

    const uvelem = this;

    const uvcartdroptargetelems = document.querySelectorAll(".uwscartdroptarget, .uwsdynacarttarget");
    Array.prototype.forEach.call(uvcartdroptargetelems, function (el, i) {
        el.classList.add("uwsloading");
    });

    const uvmastercode = (this.closest(".uws-cart-item")) ? this.closest(".uws-cart-item").getAttribute("data-mastercode") : "";
    let uvdeletecarturl = uws_proxy + "&uvaction=uwspx_cartdeleteitem";
    let uvcartcode = this.getAttribute("data-cartcode") || uwsInvGetCartCookie();
    uvdeletecarturl = uvdeletecarturl + "&cartcode=" + uvcartcode + "&itemcartcode=" + this.getAttribute("data-itemcartcode") + "&mastercode=" + uvmastercode;

    //add manageentid if is in uwsinventory object (for no lib integrations)
    if (typeof (uws_inventory.manageentid) != "undefined" && uws_inventory.manageentid)
        uvdeletecarturl = uvdeletecarturl + "&managementid=" + uws_inventory.manageentid;

    //add microcode if is in uwsinventory object (for no lib integrations)
    if (typeof (uws_inventory.microcode) != "undefined" && uws_inventory.microcode)
        uvdeletecarturl = uvdeletecarturl + "&microcode=" + uws_inventory.microcode;

    // @egt [UWS-7297]
    if(typeof urvenue_ws_inventory_vars !== "undefined" && urvenue_ws_inventory_vars.targetNonce) {
        uvdeletecarturl = uvdeletecarturl + "&uws_nonce=" + encodeURIComponent(urvenue_ws_inventory_vars.targetNonce);
    }

    let uvrequest = new XMLHttpRequest();
    uvrequest.open('GET', uvdeletecarturl, true);
    uvrequest.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            let uvresponse = this.response;
            uvresponse = JSON.parse(uvresponse);

            uwsinvAddVarsToGlobal(uvresponse);
            uwsinvUpdateDropCart();

            if (typeof (uvhookItemRemoved) == "function")
                uvhookItemRemoved(uvresponse, uvelem);
        } else {
            console.log("UVJS Error: Server returned an error");
        }
    };
    uvrequest.onerror = function () {
        console.log("UVJS Error: Request Error");
    };
    uvrequest.send();
});

/*Update time*/
uwsClickListener(".uwsjs-item-update-time", function (e) {
    e.preventDefault();

    uwsinvSetPopitemSelection("time", this.getAttribute("data-time"));
    uwsinvUpdateItemPop(this.closest(".uwsinv-item"));
});

/*Num sel control minus*/
uwsClickListener(".uwsjs-selnum-minus", function (e) {
    e.preventDefault();

    const uvinputtarget = this.closest(".uwsselnum").querySelector('input');

    if (uvinputtarget.getAttribute("data-values")) {//if there are specific values steps
        const uvvalues = uvinputtarget.getAttribute("data-values").split(",");
        const uvvalueindex = uvvalues.indexOf(uvinputtarget.value);
        const uvnewvalueindex = uvvalueindex - 1;

        if (uvnewvalueindex > -1)
            uvinputtarget.value = uvvalues[uvnewvalueindex];
    }
    else {//if there are only min and max values
        const uvnewcount = (uvinputtarget.value > uvinputtarget.getAttribute("min")) ? uvinputtarget.value - 1 : uvinputtarget.value;
        uvinputtarget.value = uvnewcount;
    }

    //Check if it buttons should be disabled
    if (uvinputtarget.value > uvinputtarget.getAttribute("min") / 1)
        this.closest(".uwsselnum").querySelector(".uwsjs-selnum-minus").classList.remove("uwsdisabled");
    else
        this.closest(".uwsselnum").querySelector(".uwsjs-selnum-minus").classList.add("uwsdisabled");

    if (uvinputtarget.value < uvinputtarget.getAttribute("max") / 1)
        this.closest(".uwsselnum").querySelector(".uwsjs-selnum-plus").classList.remove("uwsdisabled");

    const uvevent = new Event('change', { bubbles: true });
    uvinputtarget.dispatchEvent(uvevent);
});

/*Num sel control plus (@egt added reset for the time) */
uwsClickListener(".uwsjs-selnum-plus", function (e) {
    e.preventDefault();

    const uvinputtarget = this.closest(".uwsselnum").querySelector('input');

    if (uvinputtarget.getAttribute("data-values")) {//if there are specific values steps
        const uvvalues = uvinputtarget.getAttribute("data-values").split(",");
        const uvvalueindex = uvvalues.indexOf(uvinputtarget.value);
        const uvnewvalueindex = uvvalueindex + 1;

        if (uvnewvalueindex < uvvalues.length)
            uvinputtarget.value = uvvalues[uvnewvalueindex];
    }
    else {//if there are only min and max values
        const uvnewcount = (uvinputtarget.value < uvinputtarget.getAttribute("max") / 1) ? (uvinputtarget.value / 1) + 1 : uvinputtarget.value;
        uvinputtarget.value = uvnewcount;
    }

    //Check if it buttons should be disabled
    if (uvinputtarget.value < uvinputtarget.getAttribute("max") / 1)
        this.closest(".uwsselnum").querySelector(".uwsjs-selnum-plus").classList.remove("uwsdisabled");
    else
        this.closest(".uwsselnum").querySelector(".uwsjs-selnum-plus").classList.add("uwsdisabled");

    if (uvinputtarget.value > uvinputtarget.getAttribute("min") / 1)
        this.closest(".uwsselnum").querySelector(".uwsjs-selnum-minus").classList.remove("uwsdisabled");

    const uvevent = new Event('change', { bubbles: true });
    uvinputtarget.dispatchEvent(uvevent);

    uwsResetTimeSel.call(this, e);
});

uwsChangeListener(".uwsjs-updatebottle", function (e) {
    e.preventDefault();

    const uvbottlegroup = this.closest(".uws-bottlelist-item");
    const uvbottleitem = this.closest(".uws-inventory-item");
    const uvbottlesumm = this.closest(".uwsdy-bottlessumm");

    uvbottlegroup.classList.toggle("uwsadded", this.value / 1 > 0);
    uvbottleitem.classList.toggle("uwsadded", this.value / 1 > 0);

    uwsUpdateBottleContainersCount(uvbottlegroup);

    if (uvbottlesumm) uwsUpdateBottleCalcs();
});

uwsClickListener(".uwsjs-additembottles", function (e) {
    e.preventDefault();

    const uvinvbottlepop = this.closest(".uwsinv-item");
    const uvbottlepopitems = document.querySelectorAll(".uwsselbottle .uws-inventory-item");
    const uvbottlesselelem = uvinvbottlepop.querySelector(".uws-bottle-text");
    const uvbottlestotal = (uvinvbottlepop.querySelector(".uwsdy-bottleselinfo .uwsdy-bottlestotal")) ? uvinvbottlepop.querySelector(".uwsdy-bottleselinfo .uwsdy-bottlestotal").innerHTML : 0;
    const uvbottlestotalelem = uvinvbottlepop.querySelector(".uws-bottle-selection .uwsdy-bottlestotal");

    const uvsavedbottlessel = uwsGetSelectedBottles(uvbottlepopitems);

    if (uws_has_bottles_selected) {
        uvinvbottlepop.classList.add("uwsbottleselected");
        uvinvbottlepop.querySelector(".uwsdy-bottle").innerHTML = "Selected";

        if (uvsavedbottlessel) {
            uvbottlesselelem.innerHTML = uvsavedbottlessel;
            if (uvbottlestotal) {
                uvbottlestotalelem.innerHTML = uvbottlestotal;
                uws_total_bottles = uvbottlestotal / 1;
            }
        }

        uwsCreateBottlesCookies();
    } else {
        uwsSetCookie("uv_itembottles", "", -1);
        uwsSetCookie("uv_bottlestext", "", -1);
        uwsSetCookie("uvsavedbottlestot", "", -1);

        uvinvbottlepop.classList.remove("uwsbottleselected");
        uvinvbottlepop.querySelector(".uwsdy-bottle").innerHTML = "Select Bottles";
        uvbottlesselelem.innerHTML = "";
        if (uvbottlestotalelem) uvbottlestotalelem.innerHTML = "";
    }

    const uvcontainer = this.closest(".uwsviewscontainer");
    uwsSwitchViewSibling(uvcontainer.querySelector(".uwsviewhidden"), uvcontainer.querySelector(".uwsviewshown"));

    uwsinvUpdateItemPop(uvinvbottlepop);
    uwsinvUpdateDropCart();
});

// Get selected bottles
function uwsGetSelectedBottles(uvbottlepopitems) {
    let uvtotalselected = 0;
    uws_bottles_selected = {};

    Array.prototype.forEach.call(uvbottlepopitems, function (el) {
        const uvnbottles = el.querySelector(".uwsbottlessel").value / 1;
        const uvitemid = el.getAttribute("data-itemid");

        if (uvnbottles > 0) {
            const bottleName = uws_itembottles["MI" + uvitemid]["name"];
            uws_bottles_selected[bottleName] = uvnbottles;

            uvtotalselected++;
        }
    });

    let uvsavedbottlessel = Object.entries(uws_bottles_selected)
        .map(([name, count]) => `${count}x ${name}`)
        .join(" / ");

    uws_has_bottles_selected = (uvtotalselected > 0) ? 1 : 0;

    return uvsavedbottlessel;
}

//Create update bottle cookies
function uwsCreateBottlesCookies() {
    const uvbottlepopitems = document.querySelectorAll(
        ".uwsselbottle .uws-inventory-item"
    );
    let uvbottleslist = {};
    let uvbottletext = {};
    let uvsavedbottlestot = (uws_total_bottles) ? uws_total_bottles : 0;

    Array.prototype.forEach.call(uvbottlepopitems, function (el) {
        const uvnbottles = el.querySelector(".uwsbottlessel").value / 1;
        const uvitemid = el.getAttribute("data-itemid");
        const uvitemname = uws_itembottles["MI" + uvitemid]["name"];
        const uvitemprice = uws_itembottles["MI" + uvitemid]["pricenum"];

        if (uvnbottles > 0) {
            uvbottleslist["MI" + uvitemid] = uvnbottles;
            uvbottletext[uvitemid] = {
                name: uvitemname,
                subtotal: uvnbottles * uvitemprice,
            };
        }
    });

    // Remove items with 0 quantity
    Object.keys(uvbottleslist).forEach((key) => {
        if (uvbottleslist[key] === 0) {
            delete uvbottleslist[key];
            delete uvbottletext[key.replace("MI", "")];
        }
    });

    let uvbottletextFinal = Object.entries(uvbottletext)
        .map(([id, data]) => {
            const quantity = uvbottleslist["MI" + id];
            const subtotal = data.subtotal > 0 ? `: ${data.subtotal}` : "";
            return `${quantity} x ${data.name}${subtotal}`;
        })
        .join("<br>");

    uwsSetCookie("uv_itembottles", JSON.stringify(uvbottleslist), 7);
    uwsSetCookie("uv_bottlestext", JSON.stringify(uvbottletextFinal), 7);
    uwsSetCookie("uv_bottlestotal", uvsavedbottlestot, 7);
}

//Get Saved bottles from cookie and addthem
function uwsAddSavedBottles() {
    let uvcurrentbottles = uwsGetCookie("uv_itembottles");

    if (!uvcurrentbottles) return;

    uvcurrentbottles = decodeURIComponent(uvcurrentbottles.replace(/\+/g, ' '));
    uvcurrentbottles = JSON.parse(uvcurrentbottles);

    if (typeof uvcurrentbottles === "object") {
        const uvbottlepopitems = document.querySelectorAll(
            ".uwsselbottle .uws-inventory-item"
        );

        Array.prototype.forEach.call(uvbottlepopitems, function (el) {
            const itemId = "MI" + el.getAttribute("data-itemid");

            if (uvcurrentbottles[itemId]) {
                el.querySelector(".uwsbottlessel").value = uvcurrentbottles[itemId];

                const uvevent = new Event("change", { bubbles: true });
                el.querySelector(".uwsbottlessel").dispatchEvent(uvevent);

                el.classList.add("uwsadded");
            }
        });
    }
}

//Update cart count on bottle groups
function uwsUpdateBottleContainersCount(uvbottleitem) {
    let uvtotbottles = 0;
    const uvbottlegroup = uvbottleitem.querySelectorAll(".uws-inventory-item");

    Array.prototype.forEach.call(uvbottlegroup, function (el, i) {
        const uvbottleselelems = el.querySelector(".uwsbottlessel").value / 1;
        uvtotbottles = uvtotbottles + uvbottleselelems;
    });

    uvbottleitem.querySelector(".uwscartcount").innerHTML = uvtotbottles;

    if (uvtotbottles)
        uvbottleitem.classList.add("uwshasbottles");
    else
        uvbottleitem.classList.remove("uwshasbottles");
}

//Bottles totals
function uwsUpdateBottleCalcs() {
    let uvtotalminspend = document.querySelector(".uwsselbottle .uwsdy-globalminspend").getAttribute("data-minspend") / 1;
    const uvbottletotal = uwsGetBottlesTotal();
    uws_total_bottles = uvbottletotal;

    document.querySelector(".uwsselbottle .uwsdy-bottlestotal").innerHTML = uvbottletotal;

    if (uvbottletotal < uvtotalminspend) {
        document
            .querySelector(".uwsselbottle .uwsdy-bottlestotal")
            .classList.add("uwslowerror");
        document
            .querySelector(".uwsselbottle .uwsactions .uws-btn-p")
            .classList.add("uwsdisabled");
    } else {
        document
            .querySelector(".uwsselbottle .uwsdy-bottlestotal")
            .classList.remove("uwslowerror");
        document
            .querySelector(".uwsselbottle .uwsactions .uws-btn-p")
            .classList.remove("uwsdisabled");
    }
}

//Get bottle totals
function uwsGetBottlesTotal() {
    const uvbottlepopitems = document.querySelectorAll(
        ".uwsselbottle .uws-inventory-item"
    );
    let uvbottlestotal = 0;

    Array.prototype.forEach.call(uvbottlepopitems, function (el, i) {
        const uvnbottles = el.querySelector(".uwsbottlessel").value / 1;

        if (
            uvnbottles > 0 &&
            uws_itembottles["MI" + el.getAttribute("data-itemid")]
        ) {
            const uvbotitem =
                uws_itembottles["MI" + el.getAttribute("data-itemid")];
            const uvbotitemsubtot = uvnbottles * uvbotitem["pricenum"];

            uvbottlestotal = uvbottlestotal + uvbotitemsubtot;
            el.classList.add("uwsadded");
        } else {
            el.classList.remove("uwsadded");
        }
    });

    return uvbottlestotal;
}

/*Proccess Inventory List Response initial and dynamic*/
function uwsinvListProcessResponse(uvinvstage, uvresponse) {
    uws_inventory.items = "";

    const uvinventoryblocks = document.querySelectorAll(".uwsjs-loadeventinventory");
    if (uvinventoryblocks.length < 2)
        uws_inventory.plainecolist = "";

    uwsinvAddVarsToGlobal(uvresponse);

    if (typeof (uvresponse.html) != "undefined") {
        uvinvstage.querySelector(".uws-inventory-load").innerHTML = uvresponse.html;

        if (uvinvstage.querySelector(".uws-inventory-load").querySelectorAll(".uws-booktype-item").length == 1)
            uvinvstage.querySelector(".uws-inventory-load").querySelector(".uws-booktype-item").classList.add("uwsactive");
    }

    uwsinvUpdateDropCart();
    uwsinvUpdateUIStates(uvinvstage);

    uvinvstage.classList.remove("uwsloading");
}

/*Add info to global inventory var*/
function uwsinvAddVarsToGlobal(uvresponse) {
    if (typeof (uvresponse.items) != "undefined")
        uws_inventory.items = uvresponse.items;

    if (typeof (uvresponse.plainecolist) != "undefined") {
        const uvinventoryblocks = document.querySelectorAll(".uwsjs-loadeventinventory");

        if (uvinventoryblocks.length > 1)
            uws_inventory.plainecolist = uwsDeepMerge(uws_inventory.plainecolist || {}, uvresponse.plainecolist);
        else
            uws_inventory.plainecolist = uvresponse.plainecolist;
    }

    if (typeof (uvresponse.popitem) != "undefined")
        uws_inventory.popitem = uvresponse.popitem;

    if (typeof (uvresponse.proxies) != "undefined")
        uws_inventory.proxies = uvresponse.proxies;

    if (typeof (uvresponse.templates) != "undefined")
        uws_inventory.templates = uvresponse.templates;

    if (typeof (uvresponse.cart) != "undefined")
        uws_inventory.cart = uvresponse.cart;

    if (typeof (uvresponse.manageentid) != "undefined")
        uws_inventory.manageentid = uvresponse.manageentid;

    if (typeof (uvresponse.issidecheck) != "undefined")
        uws_inventory.issidecheck = uvresponse.issidecheck;

    if (typeof (uvresponse.cartcode) != "undefined") {
        uws_inventory.cartcode = uvresponse.cartcode;
        //uwsSetCookie(uws_inventory_cookiename, uvresponse.cartcode, 7);
        uwsInvSetCartCookie(uvresponse.cartcode);
    }
}

function uwsDeepMerge(uvtarget, uvsource) {
    for (const uvkey in uvsource) {
        if (uvsource[uvkey] instanceof Object && uvkey in uvtarget) {
            Object.assign(uvsource[uvkey], uwsDeepMerge(uvtarget[uvkey], uvsource[uvkey]));
        }
    }

    return Object.assign(uvtarget || {}, uvsource);
}

/*Update inventory UI States, cart count, items in cart*/
function uwsinvUpdateUIStates(uvinvstage) {
    const uvcartitems = (typeof (uws_inventory.cart) != "undefined" && typeof (uws_inventory.cart.cartitems) != "undefined") ? uws_inventory.cart.cartitems : "";

    let uvcartcount = (uvcartitems) ? Object.keys(uvcartitems).length : 0;
    if (!uvcartcount && typeof (uws_inventory.cart) != "undefined" && typeof (uws_inventory.cart.cartcount) != "undefined")
        uvcartcount = (uws_inventory.cart.cartcount != "0") ? uws_inventory.cart.cartcount : "";

    const uvcartcountelems = uvinvstage.querySelectorAll(".uwsdy-cartcount");
    Array.prototype.forEach.call(uvcartcountelems, function (el, i) {
        el.innerHTML = uvcartcount;
    });

    //Check btns states
    const uvitemslistelems = uvinvstage.querySelectorAll(".uwsinv-item");
    Array.prototype.forEach.call(uvitemslistelems, function (el, i) {
        const uvmascode = el.getAttribute("data-mastercode");
        const uvecoitems = (typeof (uws_inventory.plainecolist) != "undefined" && typeof (uws_inventory.plainecolist[uvmascode]) != "undefined") ? uws_inventory.plainecolist[uvmascode] : {};

        if (Object.keys(uvecoitems).length == 1) {
            const uvmastercode = uvecoitems[Object.keys(uvecoitems)[0]];
            const uvnitemsadded = uwsInvGetItemInCartCount(uvcartitems, uvmastercode);

            if (el.querySelector(".uwsactions .uwsinvitembtncont")) {
                const uvbtnelem = el.querySelector(".uwsactions .uwsinvitembtncont");

                //Clear Item State
                uvbtnelem.innerHTML = uvbtnelem.getAttribute("data-keepcont") || uvbtnelem.innerHTML;
                uvbtnelem.setAttribute("data-keepcont", "");
                uvbtnelem.querySelector(".uwsjs-inv-ecoitem-select").classList.remove("uwsadded", "uwsdisabled");

                if (uvnitemsadded) { //Item is in cart {uvnitemsadded} contains the times it appears
                    let uvbtnconttpl = uws_inventory.templates["item-added-btn-content"];
                    const uvitem = (typeof (uws_inventory.items) != "undefined" && typeof (uws_inventory.items[uvmastercode]) != "undefined") ? uws_inventory.items[uvmastercode] : "";
                    const uvtotalstock = (uvitem && typeof (uvitem.totalstock) != "undefined") ? uvitem.totalstock : "";
                    const uvglobaltype = (uvitem && typeof (uvitem.globaltype) != "undefined") ? uvitem.globaltype : "";
                    const uvmascode = (uvitem && typeof (uvitem.masteritemcode) != "undefined") ? uvitem.masteritemcode : "";
                    const uvbtnkeepcont = (uvbtnelem.getAttribute("data-keepcont")) ? uvbtnelem.getAttribute("data-keepcont") : uvbtnelem.innerHTML;

                    uvbtnconttpl = uvbtnconttpl.replace(/{itemcartcount}/g, uvnitemsadded);
                    uvbtnconttpl = uvbtnconttpl.replace(/{mascode}/g, uvmascode);
                    uvbtnconttpl = uvbtnconttpl.replace(/{mastercode}/g, uvmastercode);
                    uvbtnconttpl = uvbtnconttpl.replace(/{actionclass}/g, "uwsjs-inv-cart-removemastercode");

                    uvbtnelem.setAttribute("data-keepcont", uvbtnkeepcont);
                    uvbtnelem.querySelector(".uwsjs-inv-ecoitem-select").classList.add("uwsadded");
                    uvbtnelem.insertAdjacentHTML("beforeend", uvbtnconttpl);

                    if (uvtotalstock <= uvnitemsadded || uvglobaltype == "admission")
                        uvbtnelem.querySelector(".uwsjs-inv-ecoitem-select").classList.add("uwsdisabled");
                }
            }
        }
    });

    uwsInvListScrollActions();
}

/*Remove item from cart by mastercode*/
function uwsInvRemoveCartItemByMastercode(uvmastercode) {
    if (uvmastercode) {
        const uvcartitems = (typeof (uws_inventory.cart) != "undefined" && typeof (uws_inventory.cart.cartitems) != "undefined") ? uws_inventory.cart.cartitems : "";
        let uvitemsincart = [];

        if (uvcartitems) {
            Object.keys(uvcartitems).forEach((key) => {
                const uvcartitem = uvcartitems[key];
                if (uvcartitem.mastercode == uvmastercode) {
                    uvitemsincart.push(uvcartitem);
                }
            });

            if (uvitemsincart) {
                let uvitemcartcodes = "";
                Object.keys(uvitemsincart).forEach((key) => {
                    const uvthiscartitem = uvitemsincart[key];
                    uvitemcartcodes += `${uvthiscartitem.itemcartcode},`;
                });

                const uvitemsremove = { itemcartcode: uvitemcartcodes, mastercode: uvmastercode };

                uwsInvRemoveCartItem(uvitemsremove);
            }
        }
    }
}

/*Remove all items from cart*/
function uwsInvRemoveCartAllItems() {
    const uvcartitems = (typeof (uws_inventory.cart) != "undefined" && typeof (uws_inventory.cart.cartitems) != "undefined") ? uws_inventory.cart.cartitems : "";

    if (uvcartitems) {
        let uvitemcartcodes = "";
        Object.keys(uvcartitems).forEach((key) => {
            const uvthiscartitem = uvcartitems[key];
            uvitemcartcodes += `${uvthiscartitem.itemcartcode},`;
        });

        const uvitemsremove = { itemcartcode: uvitemcartcodes, mastercode: "" };

        uwsInvRemoveCartItem(uvitemsremove);
    }
}

function uwsInvRemoveCartItem(uvcartitem) {
    if (uvcartitem) {
        const uvcartdroptargetelems = document.querySelectorAll(".uwscartdroptarget, .uwsdynacarttarget");
        Array.prototype.forEach.call(uvcartdroptargetelems, function (el, i) {
            el.classList.add("uwsloading");
        });

        const uvmastercode = uvcartitem.mastercode
        let uvdeletecarturl = uws_proxy + "&uvaction=uwspx_cartdeleteitem";
        uvdeletecarturl = uvdeletecarturl + "&cartcode=" + uwsInvGetCartCookie() + "&itemcartcode=" + uvcartitem.itemcartcode + "&mastercode=" + uvmastercode;

        //add manageentid if is in uwsinventory object (for no lib integrations)
        if (typeof (uws_inventory.manageentid) != "undefined" && uws_inventory.manageentid)
            uvdeletecarturl = uvdeletecarturl + "&managementid=" + uws_inventory.manageentid;

        //add microcode if is in uwsinventory object (for no lib integrations)
        if (typeof (uws_inventory.microcode) != "undefined" && uws_inventory.microcode)
            uvdeletecarturl = uvdeletecarturl + "&microcode=" + uws_inventory.microcode;

        // @egt [UWS-7297]
        if(typeof urvenue_ws_inventory_vars !== "undefined" && urvenue_ws_inventory_vars.targetNonce) {
            uvdeletecarturl = uvdeletecarturl + "&uws_nonce=" + encodeURIComponent(urvenue_ws_inventory_vars.targetNonce);
        }

        uwsShowGLoader();

        let uvrequest = new XMLHttpRequest();
        uvrequest.open('GET', uvdeletecarturl, true);
        uvrequest.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                let uvresponse = this.response;
                uvresponse = JSON.parse(uvresponse);

                uwsinvAddVarsToGlobal(uvresponse);
                uwsinvUpdateDropCart();

                let uvinvstage = document.querySelector(".uws-inventory-stage");
                if (uvinvstage)
                    uwsinvUpdateUIStates(uvinvstage);
                else if (document.querySelector(".uws-map .uws-map-item-box"))
                    uwsMapUpdateItemBoxUI();

                uwsHideGLoader();

                if (typeof (uvhookItemRemoved) == "function")
                    uvhookItemRemoved(uvresponse);

                if (typeof (uvhookInvCartEdited) == "function")
                    uvhookInvCartEdited(uvresponse);
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

/*Get number of items with mastercode in cart*/
function uwsInvGetItemInCartCount(uvcartitems, uvmastercode) {
    let uvnitemsadded = 0;

    if (uvcartitems && uvmastercode) {
        Object.keys(uvcartitems).forEach((key) => {
            const uvcartitem = uvcartitems[key];
            if (uvcartitem.mastercode == uvmastercode) {
                uvnitemsadded++;
            }
        });
    }

    return uvnitemsadded;
}

/*Calculation when page is scrolled, checkout floating buttons*/
function uwsInvListScrollActions() {
    if (document.querySelector(".uws-inventory-listcont")) {
        const uvlistviewportoffset = document.querySelector(".uws-inventory-listcont").getBoundingClientRect();
        const uvinvstage = document.querySelector(".uws-inventory-stage");

        if (uvlistviewportoffset.top > (window.innerHeight - 300))
            uvinvstage.classList.remove("uwstopedge");
        else
            uvinvstage.classList.add("uwstopedge");

        if (uvlistviewportoffset.bottom > window.innerHeight)
            uvinvstage.classList.add("uwsbottomedge");
        else
            uvinvstage.classList.remove("uwsbottomedge");

        if (uvinvstage.querySelector(".uws-inventory-bookbtns-body")) {
            uvinvstage.querySelector(".uws-inventory-bookbtns-body").style.width = uvlistviewportoffset.width + "px";
            uvinvstage.querySelector(".uws-inventory-bookbtns-body").style.left = uvlistviewportoffset.x + "px";
        }
    }
}

Number.prototype.uwsFormatMoney = function (decPlaces, thouSeparator, decSeparator) {
    var n = this,
        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
        decSeparator = decSeparator == undefined ? "." : decSeparator,
        thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
        sign = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;

    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};

/**
 * Retrieves the reference location from the URL parameters.
 * @returns {string} The source location value if it exists in the URL parameters, otherwise undefined.
 */
function uvGetSourceLoc() {
    const uvcurrentURL = window.location.href;
    const url = new URL(uvcurrentURL);
    const uvparams = new URLSearchParams(url.search);

    if (uvparams.has('ref')) return uvparams.get('ref');
}


function uwsglobalitemFirstDayMonth() {
    // Get the current date
    var currentDate = new Date();

    // Create a new date object for the first day of the current month
    var firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);

    // Format the date in YYYY-MM-DD
    var year = firstDayOfMonth.getFullYear();
    var month = (firstDayOfMonth.getMonth() + 1).toString().padStart(2, '0');
    var day = firstDayOfMonth.getDate().toString().padStart(2, '0');

    return `${year}-${month}-${day}`;
}

function uwsGTWgetDisabledDays(uwsloadedmonths, month, venueid) {
    for (let venue of uwsloadedmonths) {
        if (venue.venueid === venueid) {
            for (let disabledDay of venue.data.additional_disabled_days) {
                if (disabledDay.month === month) {
                    return disabledDay.disabled_days.data;
                }
            }
        }
    }
    return null;
}

function uwsInitInventoryWidgets() {
    const uvwidgetinventory = document.querySelectorAll(".uwsjs-loadeventinventorywidget");

    Array.prototype.forEach.call(uvwidgetinventory, function (el) {
        uwsinventoryinitwidget(el);
        uwsClickListener(".uws-to-show-button", function () {
            document.querySelector(".uws-inventory-widget").classList.remove("uws-hide-inventory");
            document.querySelector(".uws-to-show-button").classList.add("uws-hide");
        });
    });

    if (document.querySelector("#uwsgpdatepicker")) {
        let uvmindate = document.querySelector("#uwsgpdatepicker").getAttribute("data-mindate");
        const uvmaxdate = document.querySelector("#uwsgpdatepicker").getAttribute("data-maxdate");
        let uvdate = document.querySelector("#uwsgpdatepicker").getAttribute("data-date");

        const globalWidget = document.querySelector('.uwsjs-loadeventinventorywidget');
        const weekdaysAttr = globalWidget.getAttribute("data-weekdays");
        const allWeekdays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

        let allowedWeekdaysIndexes = null;
        if (weekdaysAttr && weekdaysAttr !== "All") {
            allowedWeekdaysIndexes = weekdaysAttr.split(',')
                .map(day => allWeekdays.indexOf(day.trim()))
                .filter(index => index >= 0);
        }

        // Correctly parse date as YYYY-MM-DD (avoids timezone issues)
        function parseISODate(dateStr) {
            const parts = dateStr.split('-');
            return new Date(parts[0], parts[1] - 1, parts[2]);
        }

        // Correctly format date as YYYY-MM-DD
        function formatISODate(dateObj) {
            return dateObj.toISOString().split('T')[0];
        }

        function getNextAllowedDate(fromDateStr) {
            let date = parseISODate(fromDateStr);
            let safetyCounter = 0;

            while (allowedWeekdaysIndexes && !allowedWeekdaysIndexes.includes(date.getDay())) {
                date.setDate(date.getDate() + 1);
                safetyCounter++;
                if (safetyCounter > 7) break; // Prevent infinite loops
            }

            return date;
        }

        // Ensure minDate and startDate are correct upfront
        const safeMinDate = formatISODate(getNextAllowedDate(uvmindate));
        const safeStartDate = formatISODate(getNextAllowedDate(uvdate));

        uws_gp_date = new Litepicker({
            element: document.querySelector(".uws-gp-datepicker"),
            minDate: safeMinDate,
            maxDate: uvmaxdate,
            inlineMode: true,
            singleMode: true,
            showTooltip: false,
            firstDay: 0,
            startDate: safeStartDate,

            lockDaysFilter: (date) => {
                if (!allowedWeekdaysIndexes) return false;
                return !allowedWeekdaysIndexes.includes(date.getDay());
            },

            setup: function (picker) {
                picker.on("selected", function (selectedDate) {
                    const uvseldate = selectedDate.format('YYYY-MM-DD');
                    const uvddate = uws_dp_abdates[selectedDate.getMonth()] + " " + selectedDate.getDate() + ", " + selectedDate.getFullYear();
                    this.ui.closest(".uwshasdrop").classList.remove("uwsactive");
                    document.querySelector("#uwsgpdatepicker").innerHTML = uvddate;

                    const uvwidgetinventory = document.querySelectorAll(".uwsjs-loadeventinventorywidget");
                    Array.prototype.forEach.call(uvwidgetinventory, function (el) {
                        el.setAttribute("data-date", uvseldate);
                        uwsinventoryinitwidget(el);
                    });
                });

                picker.on('render:month', (month, date) => {
                    uws_gt_datp = date.format('YYYY-MM-DD');
                });

                picker.on('change:month', (date) => {
                    const calendarcontainer = document.querySelector('.uws-gp-datepicker');
                    if (calendarcontainer) {
                        const uvdatesloader = document.createElement('div');
                        uvdatesloader.className = 'uws-inquiry-loader';
                        const uvload = document.createElement('div');
                        uvload.className = 'uws-loader-uvicon';
                        uvdatesloader.appendChild(uvload);
                        calendarcontainer.appendChild(uvdatesloader);
                    }
                    const uvcheckdate = date.format('YYYY-MM-DD');
                    uwsGTFUpdateMonth(uvcheckdate);
                });
            }
        });

        uwsGTFUpdateMonth(uws_gt_datp);
    }

    uwsCheckCartDrops();
}

function uwsGTFUpdateMonth(uvcheckdate) {
    var uws_globaltype_widget = document.querySelector('.uwsjs-loadeventinventorywidget');
    const uvvenuecode = uws_globaltype_widget.getAttribute("data-venuecode");
    const uvaddmixeco = (uws_globaltype_widget.getAttribute("data-mixecozones")) ? "&mixecozones=" + uws_globaltype_widget.getAttribute("data-mixecozones") : "";
    const uvecozone = uws_globaltype_widget.getAttribute("data-ecozone");

    let disabledselcteddays = uwsGTWgetDisabledDays(uwsloadedmonths, uws_gt_datp, uvvenuecode);

    if (disabledselcteddays) {
        uws_gp_date.setLockDays(disabledselcteddays);
        const loader_animation = document.querySelector('.uws-gp-datepicker');
        if (loader_animation) {
            const uvloaderelement = loader_animation.querySelector('.uws-inquiry-loader');
            if (uvloaderelement) {
                loader_animation.removeChild(uvloaderelement);
            }
        }

    } else {
        let uvnoinventorydatesproxy = uws_proxy + "&uvaction=uwspx_noinventorydates";
        uvnoinventorydatesproxy = uvnoinventorydatesproxy + "&date=" + uws_gt_datp + "&venuecode=" + uvvenuecode + uvaddmixeco + "&ecozone=" + uvecozone;

        // @egt [UWS-7297]
        if(typeof urvenue_ws_inventory_vars !== "undefined" && urvenue_ws_inventory_vars.targetNonce) {
            uvnoinventorydatesproxy = uvnoinventorydatesproxy + "&uws_nonce=" + encodeURIComponent(urvenue_ws_inventory_vars.targetNonce);
        }

        let uvrequest = new XMLHttpRequest();
        uvrequest.open('GET', uvnoinventorydatesproxy, true);
        uvrequest.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                let uvresponse = this.response;
                uvresponse = JSON.parse(uvresponse);

                if (uvresponse['availabilityinfo']['noinventorydates']) {
                    uws_gp_date.setLockDays(uvresponse['availabilityinfo']['noinventorydates']);

                    const loader_animation = document.querySelector('.uws-gp-datepicker');
                    if (loader_animation) {
                        const uvloaderelement = loader_animation.querySelector('.uws-inquiry-loader');
                        if (uvloaderelement) {
                            loader_animation.removeChild(uvloaderelement);
                        }
                    }

                    // Check if the venue exists in loadedmonths
                    let venueExist = uwsloadedmonths.find(item => item.venueid === uvvenuecode);

                    let disabledDaysData = {
                        month: uvcheckdate,
                        disabled_days: {
                            data: uvresponse['availabilityinfo']['noinventorydates']
                        }
                    };

                    if (!venueExist) {
                        // If venue does not exist, add it with the disabled days data
                        let newVenue = {
                            venueid: uvvenuecode,
                            data: {
                                additional_disabled_days: [disabledDaysData]
                            }
                        };
                        uwsloadedmonths.push(newVenue);
                    } else {
                        // If venue exists, add the disabled days data to the existing venue
                        venueExist.data.additional_disabled_days.push(disabledDaysData);
                    }
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
}


/* Inventory Seven Room/open table Inventory*/

function sevenroomsInventory_list() {
    // Check if sevenroomsids is defined
    if (typeof sevenroomsids === 'undefined') {
        console.error('sevenroomsids is not defined');
        return;
    }
    document.querySelectorAll('.uws-inventory-list').forEach(function (element) {

        // Initialize the formatted reservation date
        let reservationDate = null;

        // Extract the event code from the URL path or query parameters
        const url = new URL(window.location.href);
        let eventCode = null;

        // Check for event code in the path (e.g., /event/EVE...)
        const pathMatch = url.pathname.match(/\/event\/(EVE\d{18})/);
        if (pathMatch) {
            eventCode = pathMatch[1];
        } else {
            // Check for event code in the query parameters (e.g., ?eventcode=EVE...)
            eventCode = url.searchParams.get('eventcode');
        }

        // If event code is found, extract the date from it
        if (eventCode) {
            const rawDate = eventCode.slice(-8); // Last 8 digits contain the date
            reservationDate = `${rawDate.substring(0, 4)}-${rawDate.substring(4, 6)}-${rawDate.substring(6, 8)}`;
        } else {
            console.error('Event code not found in URL.');
            return; // Exit if no valid event code is found
        }

        // Create a new div with class "seven-rooms-section"
        var newDiv = document.createElement('div');
        newDiv.classList.add('seven-rooms-section');

        // Check if uv_dl_microcode is defined, if not set it to an empty string
        if (typeof uv_dl_microcode === 'undefined') {
            uv_dl_microcode = "";
        }

        // Create the main structure for the uwsBooktypeDiv
        var uwsBooktypeDiv = document.createElement('div');
        uwsBooktypeDiv.classList.add('uws-booktype', 'uws-booktype-item', 'uws-booktype-nodetype-Booktype');

        // Create anchor for toggling
        var booktypeToggle = document.createElement('a');
        booktypeToggle.classList.add('uwsjs-booktypetoggle');
        booktypeToggle.setAttribute('href', '#uvsevenrooms');

        // Create span for SVG and name
        var nameSpanCont = document.createElement('span');
        nameSpanCont.classList.add('uwsbooktypenamenamecont');

        // Correct SVG code
        var svgElement = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svgElement.setAttribute("width", "24");
        svgElement.setAttribute("height", "24");
        svgElement.setAttribute("viewBox", "0 0 24 24");
        svgElement.setAttribute("fill", "none");

        var pathElement = document.createElementNS("http://www.w3.org/2000/svg", "path");
        pathElement.setAttribute("d", "M6.75001 8.25002V3.75002C6.75001 3.55111 6.82902 3.36034 6.96968 3.21969C7.11033 3.07904 7.30109 3.00002 7.50001 3.00002C7.69892 3.00002 7.88968 3.07904 8.03034 3.21969C8.17099 3.36034 8.25001 3.55111 8.25001 3.75002V8.25002C8.25001 8.44894 8.17099 8.6397 8.03034 8.78035C7.88968 8.92101 7.69892 9.00002 7.50001 9.00002C7.30109 9.00002 7.11033 8.92101 6.96968 8.78035C6.82902 8.6397 6.75001 8.44894 6.75001 8.25002ZM20.25 3.75002V21C20.25 21.1989 20.171 21.3897 20.0303 21.5304C19.8897 21.671 19.6989 21.75 19.5 21.75C19.3011 21.75 19.1103 21.671 18.9697 21.5304C18.829 21.3897 18.75 21.1989 18.75 21V16.5H14.25C14.0511 16.5 13.8603 16.421 13.7197 16.2804C13.579 16.1397 13.5 15.9489 13.5 15.75C13.5348 13.9535 13.7618 12.1658 14.1769 10.4175C15.0938 6.62159 16.8319 4.07721 19.2047 3.06096C19.3187 3.01211 19.4431 2.99231 19.5667 3.00334C19.6903 3.01438 19.8092 3.0559 19.9128 3.12418C20.0164 3.19247 20.1014 3.28539 20.1603 3.39461C20.2191 3.50383 20.2499 3.62595 20.25 3.75002ZM18.75 5.05315C15.7341 7.35659 15.1434 12.9675 15.0281 15H18.75V5.05315ZM11.2397 3.62721C11.225 3.52873 11.1909 3.43416 11.1392 3.34902C11.0876 3.26387 11.0196 3.18986 10.939 3.1313C10.8585 3.07274 10.7671 3.03079 10.6702 3.00792C10.5733 2.98504 10.4728 2.98169 10.3746 2.99806C10.2764 3.01443 10.1824 3.05019 10.0982 3.10326C10.0139 3.15633 9.94108 3.22564 9.8839 3.30715C9.82672 3.38867 9.78634 3.48075 9.76513 3.57804C9.74391 3.67532 9.74228 3.77585 9.76032 3.87377L10.5 8.30909C10.5 9.10473 10.1839 9.8678 9.62133 10.4304C9.05872 10.993 8.29566 11.3091 7.50001 11.3091C6.70436 11.3091 5.9413 10.993 5.37869 10.4304C4.81608 9.8678 4.50001 9.10473 4.50001 8.30909L5.23876 3.87377C5.2568 3.77585 5.25517 3.67532 5.23395 3.57804C5.21273 3.48075 5.17236 3.38867 5.11518 3.30715C5.058 3.22564 4.98516 3.15633 4.90091 3.10326C4.81666 3.05019 4.72269 3.01443 4.62448 2.99806C4.52626 2.98169 4.42577 2.98504 4.32886 3.00792C4.23196 3.03079 4.14058 3.07274 4.06005 3.1313C3.97952 3.18986 3.91147 3.26387 3.85984 3.34902C3.80822 3.43416 3.77407 3.52873 3.75938 3.62721L3.00938 8.12721C3.00297 8.16783 2.99983 8.2089 3.00001 8.25002C3.00151 9.31297 3.37868 10.3412 4.06488 11.153C4.75109 11.9647 5.70215 12.5078 6.75001 12.6863V21C6.75001 21.1989 6.82902 21.3897 6.96968 21.5304C7.11033 21.671 7.30109 21.75 7.50001 21.75C7.69892 21.75 7.88968 21.671 8.03034 21.5304C8.17099 21.3897 8.25001 21.1989 8.25001 21V12.6863C9.29787 12.5078 10.2489 11.9647 10.9351 11.153C11.6213 10.3412 11.9985 9.31297 12 8.25002C11.9999 8.20888 11.9964 8.1678 11.9897 8.12721L11.2397 3.62721Z");
        pathElement.setAttribute("fill", "black");
        svgElement.appendChild(pathElement);

        // Create the span for the name
        var nameSpan = document.createElement('span');
        nameSpan.classList.add('uwsbooktypename');
        nameSpan.textContent = "Restaurants"; // Replace "Restaurant Dining" with "Restaurants"

        // Append the SVG and nameSpan to the nameSpanCont
        nameSpanCont.appendChild(svgElement);
        nameSpanCont.appendChild(nameSpan);

        // Append the nameSpanCont to the booktypeToggle
        booktypeToggle.appendChild(nameSpanCont);

        // Add down arrow icon
        var arrowIcon = document.createElement('i');
        arrowIcon.classList.add('uwsicon-down-open');
        booktypeToggle.appendChild(arrowIcon);

        // Append the toggle to the main div
        uwsBooktypeDiv.appendChild(booktypeToggle);

        // Create the hidden body container
        var booktypeBody = document.createElement('div');
        booktypeBody.classList.add('uws-bootypelist-body');
        booktypeBody.setAttribute('style', 'max-height: 0px;');

        // Create inner body
        var innerBody = document.createElement('div');
        innerBody.classList.add('uws-bootypelist-inner');

        // Create a single uws-invitems-list where all items will be appended
        var itemList = document.createElement('div');
        itemList.classList.add('uws-invitems-list');

        // Iterate over the sevenroomsids to create multiple inventory items
        sevenroomsids.forEach(function (item) {
            // Create individual inventory item
            var invItem = document.createElement('div');
            invItem.classList.add('uwsinv-item', 'uws-inventory-item');

            // Create info div
            var infoDiv = document.createElement('div');
            infoDiv.classList.add('uwsinfo');

            // Create the name div
            var nameDiv = document.createElement('div');
            nameDiv.classList.add('uwsname');
            nameDiv.textContent = item.sevenroomsdisplayname; // Set name based on sevenroomsdisplayname

            // Append nameDiv to infoDiv
            infoDiv.appendChild(nameDiv);

            // Create action div
            var actionDiv = document.createElement('div');
            actionDiv.classList.add('uwsactions');

            // Create the select button (which will trigger the popup)
            var selectButton = document.createElement('a');
            selectButton.classList.add('uwsjs-inv-item-select', 'uws-btn', 'uws-btn-s');
            selectButton.setAttribute('href', '#uwsinv-select-MRRDYMWHN0SLGTMFE');
            selectButton.setAttribute('ref', uv_dl_microcode); // Set the ref attribute with the value of uv_dl_microcode
            selectButton.setAttribute('aria-label', 'Select');
            selectButton.innerHTML = '<span>Select</span>';

            // Append selectButton to actionDiv
            actionDiv.appendChild(selectButton);

            // Append infoDiv and actionDiv to invItem
            invItem.appendChild(infoDiv);
            invItem.appendChild(actionDiv);

            // Append invItem to itemList
            itemList.appendChild(invItem);

            // Add click event to trigger the popup on select button click
            selectButton.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default anchor behavior

                // Create overlay
                var overlay = document.createElement('div');
                overlay.classList.add('overlay');

                // Create a popup div
                var popup = document.createElement('div');
                popup.classList.add('popup');

                // Create a close button for the popup
                var closeButton = document.createElement('button');
                closeButton.innerHTML = 'X';
                closeButton.classList.add('popup-close-btn');

                // Add close event to remove the popup and overlay
                closeButton.addEventListener('click', function () {
                    document.body.removeChild(popup);
                    document.body.removeChild(overlay);
                });

                // Create the iframe dynamically
                var iframe = document.createElement('iframe');
                iframe.setAttribute('title', item.sevenroomsdisplayname + ' Reservation');
                iframe.setAttribute('alt', item.sevenroomsdisplayname + ' Reservation');
                iframe.setAttribute('src', `https://www.sevenrooms.com/reservations/` + item.sevenroomsid + `/?default_date=${encodeURIComponent(reservationDate)}`);
                iframe.classList.add('popup-iframe');

                // Append the close button and iframe to the popup
                popup.appendChild(closeButton);
                popup.appendChild(iframe);

                // Append the overlay and popup to the body
                document.body.appendChild(overlay);
                document.body.appendChild(popup);
            });
        });

        // Append itemList to innerBody
        innerBody.appendChild(itemList);

        // Append innerBody to booktypeBody
        booktypeBody.appendChild(innerBody);

        // Append booktypeBody to uwsBooktypeDiv
        uwsBooktypeDiv.appendChild(booktypeBody);

        // Append the main div to the newly created div section
        newDiv.appendChild(uwsBooktypeDiv);

        // Append the new div at the end of the .uws-inventory-list element
        element.appendChild(newDiv);
    });
}

function opentableInventory_list() {
    // Check if opentablearray is defined
    if (typeof opentablearray === 'undefined') {
        console.error('opentablearray is not defined');
        return;
    }

    document.querySelectorAll('.uws-inventory-list').forEach(function (element) {
        // Create a new div with class "ot-section"
        var otSection = document.createElement('div');
        otSection.classList.add('ot-section');

        // Create the main structure for the booking type div
        var otBooktypeDiv = document.createElement('div');
        otBooktypeDiv.classList.add('uws-booktype', 'uws-booktype-item', 'uws-booktype-nodetype-Booktype');

        // Create the toggle anchor
        var booktypeToggle = document.createElement('a');
        booktypeToggle.classList.add('uwsjs-booktypetoggle');
        booktypeToggle.setAttribute('href', '#opentable');

        // Create the span for the SVG and name
        var nameSpanCont = document.createElement('span');
        nameSpanCont.classList.add('uwsbooktypenamenamecont');
        nameSpanCont.innerHTML = `
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M6.75001 8.25002V3.75002C6.75001 3.55111 6.82902 3.36034 6.96968 3.21969C7.11033 3.07904 7.30109 3.00002 7.50001 3.00002C7.69892 3.00002 7.88968 3.07904 8.03034 3.21969C8.17099 3.36034 8.25001 3.55111 8.25001 3.75002V8.25002C8.25001 8.44894 8.17099 8.6397 8.03034 8.78035C7.88968 8.92101 7.69892 9.00002 7.50001 9.00002C7.30109 9.00002 7.11033 8.92101 6.96968 8.78035C6.82902 8.6397 6.75001 8.44894 6.75001 8.25002ZM20.25 3.75002V21C20.25 21.1989 20.171 21.3897 20.0303 21.5304C19.8897 21.671 19.6989 21.75 19.5 21.75C19.3011 21.75 19.1103 21.671 18.9697 21.5304C18.829 21.3897 18.75 21.1989 18.75 21V16.5H14.25C14.0511 16.5 13.8603 16.421 13.7197 16.2804C13.579 16.1397 13.5 15.9489 13.5 15.75C13.5348 13.9535 13.7618 12.1658 14.1769 10.4175C15.0938 6.62159 16.8319 4.07721 19.2047 3.06096C19.3187 3.01211 19.4431 2.99231 19.5667 3.00334C19.6903 3.01438 19.8092 3.0559 19.9128 3.12418C20.0164 3.19247 20.1014 3.28539 20.1603 3.39461C20.2191 3.50383 20.2499 3.62595 20.25 3.75002ZM18.75 5.05315C15.7341 7.35659 15.1434 12.9675 15.0281 15H18.75V5.05315ZM11.2397 3.62721C11.225 3.52873 11.1909 3.43416 11.1392 3.34902C11.0876 3.26387 11.0196 3.18986 10.939 3.1313C10.8585 3.07274 10.7671 3.03079 10.6702 3.00792C10.5733 2.98504 10.4728 2.98169 10.3746 2.99806C10.2764 3.01443 10.1824 3.05019 10.0982 3.10326C10.0139 3.15633 9.94108 3.22564 9.8839 3.30715C9.82672 3.38867 9.78634 3.48075 9.76513 3.57804C9.74391 3.67532 9.74228 3.77585 9.76032 3.87377L10.5 8.30909C10.5 9.10473 10.1839 9.8678 9.62133 10.4304C9.05872 10.993 8.29566 11.3091 7.50001 11.3091C6.70436 11.3091 5.9413 10.993 5.37869 10.4304C4.81608 9.8678 4.50001 9.10473 4.50001 8.30909L5.23876 3.87377C5.2568 3.77585 5.25517 3.67532 5.23395 3.57804C5.21273 3.48075 5.17236 3.38867 5.11518 3.30715C5.058 3.22564 4.98516 3.15633 4.90091 3.10326C4.81666 3.05019 4.72269 3.01443 4.62448 2.99806C4.52626 2.98169 4.42577 2.98504 4.32886 3.00792C4.23196 3.03079 4.14058 3.07274 4.06005 3.1313C3.97952 3.18986 3.91147 3.26387 3.85984 3.34902C3.80822 3.43416 3.77407 3.52873 3.75938 3.62721L3.00938 8.12721C3.00297 8.16783 2.99983 8.2089 3.00001 8.25002C3.00151 9.31297 3.37868 10.3412 4.06488 11.153C4.75109 11.9647 5.70215 12.5078 6.75001 12.6863V21C6.75001 21.1989 6.82902 21.3897 6.96968 21.5304C7.11033 21.671 7.30109 21.75 7.50001 21.75C7.69892 21.75 7.88968 21.671 8.03034 21.5304C8.17099 21.3897 8.25001 21.1989 8.25001 21V12.6863C9.29787 12.5078 10.2489 11.9647 10.9351 11.153C11.6213 10.3412 11.9985 9.31297 12 8.25002C11.9999 8.20888 11.9964 8.1678 11.9897 8.12721L11.2397 3.62721Z" fill="black"></path>
            </svg>
            <span class="uwsbooktypename">Restaurants</span>
        `;

        // Append nameSpanCont to the toggle anchor
        booktypeToggle.appendChild(nameSpanCont);
        booktypeToggle.innerHTML += `<i class="uwsicon-down-open"></i>`;

        // Append the toggle anchor to the main div
        otBooktypeDiv.appendChild(booktypeToggle);

        // Create the container for the list of items
        var booktypeBody = document.createElement('div');
        booktypeBody.classList.add('uws-bootypelist-body');

        var innerBody = document.createElement('div');
        innerBody.classList.add('uws-bootypelist-inner');

        var itemList = document.createElement('div');
        itemList.classList.add('uws-invitems-list');

        // Iterate over opentablearray to create each item
        opentablearray.forEach(function (item) {
            var invItem = document.createElement('div');
            invItem.classList.add('uwsinv-item', 'uws-inventory-item');

            var infoDiv = document.createElement('div');
            infoDiv.classList.add('uwsinfo');

            var nameDiv = document.createElement('div');
            nameDiv.classList.add('uwsname');
            nameDiv.textContent = item.otdisplayname;

            infoDiv.appendChild(nameDiv);
            invItem.appendChild(infoDiv);

            var actionDiv = document.createElement('div');
            actionDiv.classList.add('uwsactions');

            var selectButton = document.createElement('a');
            selectButton.classList.add('uwsjs-inv-item-select', 'uws-btn', 'uws-btn-s');
            selectButton.href = '#';
            selectButton.innerHTML = '<span>Select</span>';

            // Add event listener for popup
            selectButton.addEventListener('click', function (e) {
                e.preventDefault();
                openPopup(item.rid, item.otdisplayname);
            });

            actionDiv.appendChild(selectButton);
            invItem.appendChild(actionDiv);

            itemList.appendChild(invItem);
        });

        innerBody.appendChild(itemList);
        booktypeBody.appendChild(innerBody);
        otBooktypeDiv.appendChild(booktypeBody);
        otSection.appendChild(otBooktypeDiv);
        element.appendChild(otSection);
    });
}

function openPopup(rid, displayName) {
    // Initialize the formatted reservation date
    let reservationDate = null;

    // Extract the event code from the URL path or query parameters
    const url = new URL(window.location.href);
    let eventCode = null;

    // Check for event code in the path (e.g., /event/EVE...)
    const pathMatch = url.pathname.match(/\/event\/(EVE\d{18})/);
    if (pathMatch) {
        eventCode = pathMatch[1];
    } else {
        // Check for event code in the query parameters (e.g., ?eventcode=EVE...)
        eventCode = url.searchParams.get('eventcode');
    }

    // If event code is found, extract the date from it
    if (eventCode) {
        const rawDate = eventCode.slice(-8); // Last 8 digits contain the date
        reservationDate = `${rawDate.substring(0, 4)}-${rawDate.substring(4, 6)}-${rawDate.substring(6, 8)}`;
    } else {
        console.error('Event code not found in URL.');
        return; // Exit if no valid event code is found
    }

    // Construct the OpenTable iframe URL with the extracted date
    const iframeUrl = `https://www.opentable.com/restref/client/?rid=${rid}&restref=${rid}&lang=en-US&color=1&r3uid=cfe&dark=false&partysize=2&datetime=${encodeURIComponent(reservationDate)}T00%3A00`;

    // Create overlay to block interactions outside the popup
    const overlay = document.createElement('div');
    overlay.classList.add('overlay');

    // Create popup container
    const popup = document.createElement('div');
    popup.classList.add('popup', 'opentablepop');

    // Create close button
    const closeButton = document.createElement('button');
    closeButton.innerHTML = 'X';
    closeButton.classList.add('popup-close-btn');

    // Close popup and remove overlay when the close button is clicked
    closeButton.addEventListener('click', function () {
        document.body.removeChild(popup);
        document.body.removeChild(overlay);
    });

    // Create the iframe with the constructed URL
    const iframe = document.createElement('iframe');
    iframe.setAttribute('title', `${displayName} Reservation`);
    iframe.setAttribute('alt', `${displayName} Reservation`);
    iframe.setAttribute('src', iframeUrl);
    iframe.classList.add('popup-iframe');

    // Append close button and iframe to the popup
    popup.appendChild(closeButton);
    popup.appendChild(iframe);

    // Append overlay and popup to the body
    document.body.appendChild(overlay);
    document.body.appendChild(popup);
}