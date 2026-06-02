
uwsDOMReady(function(){
    uwsInitAPIReqs();
});

function uwsInitAPIReqs(){
    const uvapireqselems = document.querySelectorAll(".uwsapi-missing-req:not(.uwshasapireq)");
    Array.prototype.forEach.call(uvapireqselems, function(el, i){
        const uvaddelems = "<div class='uwsapireqoverlay'></div><a href='#' class='uwsapijs-showapireqdets uwsapireqaction' aria-label='Show API requirements details'><span>Show API requirements details</span>?</a>";

        el.insertAdjacentHTML("beforeend", uvaddelems);

        el.classList.add("uwshasapireq");
    });

    var uvlinks = document.getElementsByTagName("a");
    for(var i = 0; i < uvlinks.length; i++){
        var uvhref = uvlinks[i].getAttribute("href");
        if(uvhref && uvhref.indexOf("apireq") == -1){
            uvlinks[i].setAttribute("href", uvhref + (uvhref.indexOf("?") > -1 ? "&" : "?") + "apireq=1");
        }
    }

    uws_proxy = uws_proxy + "&apireq=1";
}

function uvhookItemPopOpened(uvitem){
    uwsInitAPIReqs();
}
function uvhookExpDateLoaded(uvresponse){
    uwsInitAPIReqs();
}
function uvhookItemOTTimesLoaded(uvresponse){
    uwsInitAPIReqs();
}

uwsClickListener(".uwsapijs-showapireqdets", function(e){
    e.preventDefault();

    const uvtitle = this.closest(".uwshasapireq").getAttribute("data-apimr-title");
    const uvdecr = this.closest(".uwshasapireq").getAttribute("data-apimr-descr");
    const uvpophtml = "<div class='uwstitle'>" + uvtitle + "</div><div class='uwsmessage'>" + uvdecr + "</div>";

    uws_mgs_pop.classList.add("uws-pop-reqdetsmsg");
    uwsClearPopup(uws_mgs_pop, uvpophtml);
    uwsFadePopup(uws_mgs_pop);
});