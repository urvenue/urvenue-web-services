window.uws_itinerary = window.uws_itinerary || {};
var uws_itinerary_dp;

uwsDOMReady(function(){
    if(document.querySelector(".uwsitinerarydp")){
        const uvitemdpelem = document.querySelector(".uwsitinerarydp");

        const uvmindate = uvitemdpelem.getAttribute("data-date");
        const uvmaxdate = uvitemdpelem.getAttribute("data-maxdate");
        const uvninvmonths = uvitemdpelem.getAttribute("data-ninvmonths") / 1;
        const uvinitinvmonths = (window.innerWidth > 1270) ? 1 : uvninvmonths;

        uws_itinerary_dp = new Litepicker({
            element: uvitemdpelem,
            minDate: uvmindate,
            maxDate: uvmaxdate,
            inlineMode: 1,
            singleMode: 1,
            showTooltip: 0,
            firstDay: 0,
            startDate: uvmindate,
            numberOfMonths: uvinitinvmonths,
            setup: function(n) {
                n.on("selected", function(n, t){
                    const uvseldate = n.format('YYYY-MM-DD');
                    console.log(uvseldate);
                })
            }
        });
    }

    if(document.querySelector(".uws-itinerary")){
        uwsInitItinerary();
    }

    document.body.addEventListener('click', function(e){
        if(e.pointerType){//no action if keyboard was used
            const uvactivetooltips = document.querySelectorAll(".uws-ititemtooltip.uwsactive");

            Array.prototype.forEach.call(uvactivetooltips, function(el, i){
                if(!el.matches(":hover")){
                    el.classList.remove("uwsactive");
                    document.body.classList.remove("uwstooltipactive");
                }
            });
        }
    });

    window.addEventListener("scroll", function(){
        const uvactivetooltips = document.querySelectorAll(".uws-ititemtooltip.uwsactive");
        Array.prototype.forEach.call(uvactivetooltips, function(el, i){
            const uvtooltipoffset = el.getAttribute("data-offset") / 1;

            if((window.pageYOffset - uvtooltipoffset) > 100 || (window.pageYOffset - uvtooltipoffset) < -100){
                el.classList.remove("uwsactive");
                document.body.classList.remove("uwstooltipactive");
            }
        });
    });

    window.addEventListener("resize", function(){
        const uvitemdpelem = document.querySelector(".uwsitinerarydp");
        const uvninvmonths = uvitemdpelem.getAttribute("data-ninvmonths") / 1;
        const uvinitinvmonths = (window.innerWidth > 1270) ? 1 : uvninvmonths;

        uws_itinerary_dp.setOptions({numberOfMonths: uvinitinvmonths});
    });
});

//Initialize Itinerary
function uwsInitItinerary(){
    let uvinititinerary = uws_proxy + "&uvaction=uwspx_itineraryinit";
    
    // @egt [UWS-7297]
    if(typeof uwsitineraryvars !== "undefined" && uwsitineraryvars.targetNonce) {
        uvinititinerary = uvinititinerary + "&uws_nonce=" + encodeURIComponent(uwsitineraryvars.targetNonce);
    }

    let uvrequest = new XMLHttpRequest();
    uvrequest.open('GET', uvinititinerary, true);
    uvrequest.onload = function(){
        if (this.status >= 200 && this.status < 400){
            let uvresponse = this.response;
            uvresponse = JSON.parse(uvresponse);

            uwsItineraryAddVarsToGlobal(uvresponse);
        } else {
            console.log("UVJS Error: Server returned an error");
        }
    };
    uvrequest.onerror = function(){
        console.log("UVJS Error: Request Error");
    };
    uvrequest.send();
}

//Process itinerary response
function uwsItineraryAddVarsToGlobal(uvresponse){

    if(typeof(uvresponse.tooltips) != "undefined")
        uws_itinerary.tooltips = uvresponse.tooltips;
}

//Open Item tooltip
uwsClickListener(".uwsjs-show-ititem-info", function(e){
    e.preventDefault();
    const uvititem = this.getAttribute("data-ititemid");
    const uvtargettooltip = uws_itinerary.tooltips[uvititem];
    const uvelemrect = this.getBoundingClientRect();

    if(!document.querySelector(".uws-ititemtooltip-" + uvititem))
        document.body.insertAdjacentHTML("beforeend", uvtargettooltip);
    
    const uvtooltip = document.querySelector(".uws-ititemtooltip-" + uvititem);
    const uvtooltiprect = uvtooltip.getBoundingClientRect();
    const uvwindowwidth = window.innerWidth;
    const uvwindowheight = window.innerHeight;

    let uvtop = uvelemrect.top + (uvelemrect.height - uvtooltiprect.height) / 2;
    let uvleft = uvelemrect.left + uvelemrect.width + 10;

    if(uvtop < 0){
        uvtop = 0;
    } else if (uvtop + uvtooltiprect.height > uvwindowheight){
        uvtop = uvwindowheight - uvtooltiprect.height;
    }

    if(uvleft + uvtooltiprect.width > uvwindowwidth) {
        uvleft = uvelemrect.left - uvtooltiprect.width - 10;
    }

    //adjust if overflow
    if(uvleft < 10)
        uvleft = uvwindowwidth - uvtooltiprect.width - 10;
        //uvleft = 10;
    else if((uvleft + uvtooltiprect.width) > uvwindowwidth)
        uvleft = uvwindowwidth - uvtooltiprect.width - 10;


    uvtooltip.style.left = `${uvleft}px`;
    uvtooltip.style.top = `${uvtop}px`;
    uvtooltip.setAttribute("data-offset", window.pageYOffset);
    uvtooltip.classList.add("uwsactive");
    document.body.classList.add("uwstooltipactive");

    //Create overlay div
    if(!document.querySelector(".uws-tooltip-moboverlay")){
        let uvtooltipoverlay = document.createElement("div");
        uvtooltipoverlay.classList.add("uws-tooltip-moboverlay");
        document.body.appendChild(uvtooltipoverlay);
    }
});

//Close ititem tooltip
uwsClickListener(".uwsjs-close-ititemtooltip", function(e){
    e.preventDefault();

    this.closest(".uws-ititemtooltip").classList.remove("uwsactive");
    document.body.classList.remove("uwstooltipactive");
});