var uws_package_pop, uws_pkg_dp;

window.uws_packages = window.uws_packages || {};

uwsClickListener(".uwsjs-openpackage", function (e) {
    e.preventDefault();
    const uvelem = this;
    uwsOpenPackagePopup(uvelem);
});

/* Single Button to display the popup of a single package*/
uwsClickListener(".uwsjs-opensinglepackages", function (e) {
    e.preventDefault();
    const uvelem = this;
    uwsOpenPackagePopup(uvelem);
});

uwsClickListener(".uwsjs-open-date-uvmasteritemcode", function (e) {
    e.preventDefault();

    const uvelem = this;
    const uvdate = uvelem.getAttribute("data-date");
    const masteritemcode = uvelem.getAttribute("data-masteritemcode");
    const venuecode = uvelem.getAttribute("data-venuecode");

    uwsShowGLoader();

    let uvloadurl = uws_proxy + "&uvaction=uwspx_mastercodebymasteritemcode&masteritemcode=" + masteritemcode + "&venuecode=" + venuecode + "&date=" + uvdate;

    // @egt [UWS-7297]
    if(typeof uwspackagesvars !== "undefined" && uwspackagesvars.targetNonce) {
        uvloadurl = uvloadurl + "&uws_nonce=" + encodeURIComponent(uwspackagesvars.targetNonce);
    }

    let uvrequest = new XMLHttpRequest();
    uvrequest.open('GET', uvloadurl, true);
    uvrequest.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            let uvresponse = this.response;
            uvresponse = JSON.parse(uvresponse);

            if (uvresponse.mastercode) {
                uwsInvShowItemPop(uvresponse.mastercode);
                uwsHidePopup(uws_package_pop, 1);
            }
            else {
                uvelem.closest(".uws-package-popcont").classList.add("uwshaserror");
                uwsHideGLoader();
            }
        } else {
            console.log("UVJS Error: Server returned an error");
        }
    };
    uvrequest.onerror = function () {
        console.log("UVJS Error: Request Error");
    };
    uvrequest.send();
});

uwsClickListener(".uwsjs-packpop-changedate", function (e) {
    e.preventDefault();

    this.closest(".uws-package-popcont").classList.remove("uwshaserror");
});

function uwsOpenPackagePopup(uvelem) {
    if (!uws_package_pop)//create pop if it doesn't exist
        uws_package_pop = uwsCreatePop("uws-package-pop");

    const sourceactionButton = (uvelem.getAttribute("data-sourceaction") == "button") ? true : false;

    let uvmasteritemcode = uvmindate = uvmaxdate = uvdate = uvvenuecode = uvglobaltype = "";

    if (sourceactionButton) {
        uvmasteritemcode = uvelem.getAttribute("data-masteritemcode");
        uvmindate = uvelem.getAttribute("data-mindate");
        uvmaxdate = uvelem.getAttribute("data-maxdate");
        uvdate = uvelem.getAttribute("data-date");
        uvvenuecode = uvelem.getAttribute("data-venuecode");
        uvglobaltype = uvelem.getAttribute("data-globaltype");
    } else {
        uvmasteritemcode = uvelem.getAttribute("data-masteritemcode");
        uvmindate = uvelem.closest(".uwsispackageslist").getAttribute("data-mindate");
        uvmaxdate = uvelem.closest(".uwsispackageslist").getAttribute("data-maxdate");
        uvdate = uvelem.closest(".uwsispackageslist").getAttribute("data-date");
        uvvenuecode = uvelem.closest(".uwsispackageslist").getAttribute("data-venuecode");
        uvglobaltype = uvelem.getAttribute("data-globaltype");
    }


    const uvpopcont = `
        <div class="uws-package-popcont">
            <div class='uws-loader-uvicon'></div>
            <div class="uwstitle">Select a Date</div>
            <div class="uws-packagepop-dpcont">
                <div class="uwsjs-package-dp uws-litepickerlarge"></div>
                <div class="uws-packageppop-notfound-msg">
                    <div class="uws-packageppop-notfound-msg-inner">
                        <span>We're sorry, but the selected package is not available on your chosen date. Please select another date.</span>
                        <a href="#uws-change-packagepop-date" class="uwsjs-packpop-changedate uws-btn uws-btn-s uws-noitems-btn"><i class="uwsicon-calendar-empty"></i> <span>Select a different date</span></a>
                    </div>
                </div>
            </div>
            <div class="uwsactions">
                <a class="uws-btn uws-btn-p uwsjs-open-date-uvmasteritemcode" data-masteritemcode="${uvmasteritemcode}" data-date="${uvdate}" data-venuecode="${uvvenuecode}" href="#book package"><span>Book Now</span></a>
            </div>
        </div>
    `;

    uwsClearPopup(uws_package_pop, uvpopcont);
    const uvpkcaldp = uws_package_pop.querySelector(".uwsjs-package-dp");

    uws_pkg_dp = new Litepicker({
        element: uvpkcaldp,
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
                uws_package_pop.querySelector(".uwsjs-open-date-uvmasteritemcode").setAttribute("data-date", uvseldate);
            }),
                n.on('render:day', (day, date) => {
                    day.innerHTML = `<span>${day.innerHTML}</span>`;
                }),
                n.on('render:month', (month, date) => {
                    const uvseldate = date.format('YYYY-MM-DD');
                    uws_inventory.dpcurrentmont = uvseldate;
                });
            n.on('change:month', (date, calendarIdx) => {
                const uvseldate = date.format('YYYY-MM-DD');
                uwsinvGetPackDisDates(uvseldate, uvvenuecode, uvglobaltype);
            })
        }
    });

    setTimeout(() => {
        uwsFadePopup(uws_package_pop);
    }, 250);

    uwsinvGetPackDisDates(uvdate, uvvenuecode, uvglobaltype);
}


/**
 * Retrieves packages calendar display dates.
 * 
 * @param {string} uvdate - The date.
 * @param {string} uvvenuecode - The venue code.
 * @param {string} uvglobaltype - The globaltype.
 */
function uwsinvGetPackDisDates(uvdate, uvvenuecode, uvglobaltype) {
    const uvpackcalstage = uws_pkg_dp.ui.closest(".uws-package-popcont");
    const uvdpmonth = (typeof (uws_inventory.dpcurrentmont) != "undefined") ? uws_inventory.dpcurrentmont : "";
    const uvmonthcloseddates = (uvdpmonth && typeof (uws_inventory.noinventorydates) == "object" && typeof (uws_inventory.noinventorydates["date:" + uvdpmonth]) != "undefined") ? uws_inventory.noinventorydates["date:" + uvdpmonth] : "";

    if (uvmonthcloseddates) {
        uws_pkg_dp.setLockDays(uvmonthcloseddates);
    } else {
        uvpackcalstage.classList.add("uwsloading");

        let uvnoinventorydatesproxy = uws_proxy + "&uvaction=uwspx_noinventorydates";
        uvnoinventorydatesproxy = uvnoinventorydatesproxy + "&date=" + uvdate + "&venuecode=" + uvvenuecode + "&globaltype=" + uvglobaltype;

        // @egt [UWS-7297]
        if(typeof uwspackagesvars !== "undefined" && uwspackagesvars.targetNonce) {
            uvnoinventorydatesproxy = uvnoinventorydatesproxy + "&uws_nonce=" + encodeURIComponent(uwspackagesvars.targetNonce);
        }

        let uvrequest = new XMLHttpRequest();
        uvrequest.open('GET', uvnoinventorydatesproxy, true);
        uvrequest.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                let uvresponse = this.response;
                uvresponse = JSON.parse(uvresponse);

                if (typeof (uvresponse) == "object")
                    uwsPackCalendarAddVars(uvresponse);

                const uvloadedmonthcloseddate = (uvdpmonth && typeof (uws_inventory.noinventorydates) == "object" && typeof (uws_inventory.noinventorydates["date:" + uvdpmonth]) != "undefined") ? uws_inventory.noinventorydates["date:" + uvdpmonth] : "";

                if (uvloadedmonthcloseddate)
                    uws_pkg_dp.setLockDays(uvloadedmonthcloseddate);

                uvpackcalstage.classList.remove("uwsloading");
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
 * Adds variables to the packages calendar.
 * @param {Object} uvresponse - The response object containing availability information.
 */
function uwsPackCalendarAddVars(uvresponse) {
    if (typeof (uvresponse.availabilityinfo) == "object" && typeof (uvresponse.availabilityinfo.monthdate) != "undefined" && typeof (uvresponse.availabilityinfo.noinventorydates) != "undefined") {
        window.uws_inventory.noinventorydates = window.uws_inventory.noinventorydates || {};
        uws_inventory.noinventorydates["date:" + uvresponse.availabilityinfo.monthdate] = uvresponse.availabilityinfo.noinventorydates;
    }
}