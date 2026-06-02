var uws_mgs_pop;
var uws_gloader;
var uws_dp_abdates = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
var uws_fullmonths = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
var uws_dp_abweekdays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
var uws_dropsinit = 0;
var uws_debounce_timer = 0;
window.uws_front_lang = window.uws_front_lang || "";

uwsDOMReady(function () {
    uwsInitDrops();
    uws_gloader = uwsAddGLoader();
    uws_mgs_pop = uwsCreatePop("uws-msg-pop");
});

//Init Dropdowns
function uwsInitDrops() {
    const uvdropparents = document.getElementsByClassName("uws-dropdown-cont");

    Array.prototype.forEach.call(uvdropparents, function (el, i) {
        if (!el.classList.contains("uwshasdrop")) {
            el.classList.add("uwshasdrop");

            const uvdroptriggerelement = el.getElementsByClassName("uwsjs-trigger-dropdown");

            uvdroptriggerelement[0] && uvdroptriggerelement[0].addEventListener("click", function (e) {
                e.preventDefault();

                if (this.closest(".uwshasdrop").classList.contains("uwsactive"))
                    this.closest(".uwshasdrop").classList.remove("uwsactive");
                else
                    this.closest(".uwshasdrop").classList.add("uwsactive")
            });

            const uvdropelems = el.querySelectorAll(".uws-dropdown a, .uws-dropdown li");
            Array.prototype.forEach.call(uvdropelems, function (el, i) {
                el.addEventListener("click", function (e) {
                    this.closest(".uwshasdrop").classList.remove("uwsactive");

                    if (this.querySelector("a, button") && this.closest(".uwshasdrop").querySelector(".uwsjs-trigger-dropdown .uwsdy-dropvalue"))
                        this.closest(".uwshasdrop").querySelector(".uwsjs-trigger-dropdown .uwsdy-dropvalue").innerHTML = this.querySelector("a, button").innerHTML;

                    setTimeout(function () {
                        const uvsibelems = el.closest("ul").querySelectorAll("li");
                        Array.prototype.forEach.call(uvsibelems, function (el, i) {
                            el.classList.remove("uwscurrent");
                        });
                        el.closest("li").classList.add("uwscurrent");
                    }, 300);
                });
            });
        }
    });

    if (uvdropparents && !uws_dropsinit) {//Close dropdown on body click
        document.body.addEventListener('click', function (e) {
            if (e.pointerType || typeof (e.pointerType) == "undefined") {//no action if keyboard was used
                const uvactivedrops = document.querySelectorAll(".uwshasdrop.uwsactive");

                Array.prototype.forEach.call(uvactivedrops, function (el, i) {
                    // if (!el.matches(":hover")) // Old: unreliable on mobile and with DevTools open
                    if (!el.contains(e.target)) // Check if click was outside the container
                        el.classList.remove("uwsactive");
                });
            }
        });
    }

    uws_dropsinit = 1;
}

//Add Global Loader Element
function uwsAddGLoader() {
    let uvloaderelem = "";
    if (!document.querySelector("#uws-gloader")) {
        uvloaderelem = document.createElement("div");
        uvloaderelem.id = "uws-gloader";
        uvloaderelem.classList.add("uws-gloader-cont");

        let uvloadericonelem = document.createElement("div");
        uvloadericonelem.classList.add("uws-loader-uvicon");

        let uvloadermsg = document.createElement("div");
        uvloadermsg.classList.add("uwsloadermsg");

        uvloaderelem.appendChild(uvloadericonelem);
        uvloaderelem.appendChild(uvloadermsg);
        document.body.appendChild(uvloaderelem);
    }
    else
        uvloaderelem = document.querySelector("#uws-gloader");

    return uvloaderelem;
}

//Show global loader
function uwsShowGLoader(uvmsg) {
    if (typeof (uvmsg) == "undefined")
        uvmsg = "";

    uws_gloader.querySelector(".uwsloadermsg").innerHTML = uvmsg;
    uws_gloader.classList.add("visible");
}

//Hide global loader
function uwsHideGLoader() {
    uws_gloader.classList.remove("visible");
}

//Content Views
uwsClickListener(".uwsjs-view-change", function (e) {
    e.preventDefault();

    if (!this.classList.contains("uwsactive")) {
        const uvmenusibs = this.closest(".uws-contviewssel").querySelectorAll("li a");
        const uvtheview = this.getAttribute("data-view");
        let uvactivehtml = "";

        Array.prototype.forEach.call(uvmenusibs, function (el, i) {
            if (el.getAttribute("data-view") && uvtheview == el.getAttribute("data-view")) {
                el.classList.add("uwsactive");
                el.closest("li").classList.add("uwscurrent");

                if (document.querySelector(".uws-contview-" + el.getAttribute("data-view"))) {
                    document.querySelector(".uws-contview-" + el.getAttribute("data-view")).classList.add("uwsactive");

                    uvactivehtml = el.innerHTML;
                }
            }
            else {
                el.classList.remove("uwsactive");
                el.closest("li").classList.remove("uwscurrent");

                if (document.querySelector(".uws-contview-" + el.getAttribute("data-view")))
                    document.querySelector(".uws-contview-" + el.getAttribute("data-view")).classList.remove("uwsactive");
            }
        });
    }
});

//Close popup image from inner button
uwsClickListener(".uwsjs-closepop-force", function (e) {
    e.preventDefault();

    const uvpopelem = this.closest(".uws-pop-cont");
    if (uvpopelem)
        uwsHidePopup(uvpopelem, true);
});

//Open image in pop up
uwsClickListener(".uwsjs-show-image", function (e) {
    e.preventDefault();

    const uvimgurl = this.getAttribute("href");
    const uvpoptitle = (this.getAttribute("data-pop-title")) ? this.getAttribute("data-pop-title") : "";
    const uvpoptitlehtml = (uvpoptitle) ? "<div class='uwstitle'>" + uvpoptitle + "</div>" : "";
    const uvpophtml = uvpoptitlehtml + "<div class='uwsimage'><img class='uwsimgloading uwsloaded' src='" + uvimgurl + "' alt='" + uvpoptitle + " - Flyer' onload='uwsHideGLoader();uwsFadePopup(uws_mgs_pop);'></div>";

    uws_mgs_pop.classList.add("uws-pop-image");
    uwsClearPopup(uws_mgs_pop, uvpophtml);
    uwsShowGLoader();
});

//Share on Facebook
uwsClickListener(".uwsjs-fbshare", function (e) {
    e.preventDefault();

    const uvshareurl = encodeURIComponent(this.getAttribute("data-shareurl"));
    window.open('https://www.facebook.com/sharer.php?u=' + uvshareurl, 'Like', 'toolbar=0, status=0, width=650, height=450');
});

//Share on Twitter
uwsClickListener(".uwsjs-twshare", function (e) {
    e.preventDefault();

    const uvshareurl = encodeURIComponent(this.getAttribute("data-shareurl"));
    window.open('https://twitter.com/intent/tweet?text=' + uvshareurl, 'Tweet', 'toolbar=0, status=0, width=650, height=450');
});

//Copy URL
uwsClickListener(".uwsjs-copytext", function (e) {
    e.preventDefault();

    const uvshareurl = this.getAttribute("data-copytext");
    const uvsharelinktarget = this;

    navigator.clipboard.writeText(uvshareurl).then(
        () => {
            uvsharelinktarget.classList.add("uwsactive");

            setTimeout(function () {
                uvsharelinktarget.classList.remove("uwsactive");
            }, 2000);
        },
        () => {
            console.log("not copied");
        }
    );
});

/*Collapse Toggle Global*/
uwsClickListener(".uwsjs-toggle-collapse", function (e) {
    e.preventDefault();

    if (this.closest(".uws-togglecoll").classList.contains("uwsactive")) {
        this.closest(".uws-togglecoll").classList.remove("uwsactive");
        this.closest(".uws-togglecoll").querySelector(".uws-togglecoll-body").style.maxHeight = "0px";
    }
    else {
        let uveventitemlistheight = this.closest(".uws-togglecoll").querySelector(".uws-togglecoll-inner").clientHeight;
        uveventitemlistheight += 50;

        this.closest(".uws-togglecoll").querySelector(".uws-togglecoll-body").style.maxHeight = uveventitemlistheight + "px";
        this.closest(".uws-togglecoll").classList.add("uwsactive");
    }

    setTimeout(function () {
        uwsInvListScrollActions();
    }, 310);
});

/*Views smooth switch*/
function uwsSwitchViewSibling(uvviewin, uvviewout) {
    const uvcontainer = uvviewout.closest(".uwsviewscontainer");
    const uvinitheight = uvviewout.clientHeight;
    uvcontainer.style.height = uvinitheight + "px";

    //prepare style to animation
    uvcontainer.classList.add("uwsviewstoanim");
    uvviewout.classList.add("uwsviewinfix");
    uvviewin.classList.add("uwsviewtoin");
    uvviewin.classList.remove("uwsviewhidden");
    uvviewout.classList.remove("uwsviewshown");
    if(uvviewin.classList.contains("uwshideontransition"))
        uvcontainer.classList.add("uwsblockcontainerview");

    //animate between views
    setTimeout(function () {
        const uvnewheight = uvviewin.clientHeight;
        uvcontainer.style.height = uvnewheight + "px";
        uvviewout.classList.add("uwsviewhidden");
        uvviewin.classList.remove("uwsviewdispnone");
        if(!uvviewin.classList.contains("uwshideontransition"))
            uvviewin.classList.add("uwsviewshown");

        setTimeout(function () {
            uvviewout.classList.add("uwsviewdispnone");
            uvcontainer.classList.remove("uwsviewstoanim");
            uvviewout.classList.remove("uwsviewinfix");
            uvviewin.classList.remove("uwsviewtoin");
            if(uvviewin.classList.contains("uwshideontransition")){
                uvviewin.classList.add("uwsviewshown");
                uvcontainer.classList.remove("uwsblockcontainerview");
            }
            uvcontainer.style.height = "auto";
        }, 500);
    }, 10);
}

//Wait for DOM function
function uwsDOMReady(fn) {
    if (document.readyState != 'loading') {
        fn();
    } else {
        document.addEventListener('DOMContentLoaded', fn);
    }
}
//Click Listener
function uwsClickListener(uvselector, uvhandler) {
    document.addEventListener("click", function (e) {
        for (var target = e.target; target && target != this; target = target.parentNode) {
            if (target.matches(uvselector)) {
                uvhandler.call(target, e);
                break;
            }
        }
    }, false);
}
//Change Listener
function uwsChangeListener(uvselector, uvhandler) {
    document.addEventListener("change", function (e) {
        for (var target = e.target; target && target != this; target = target.parentNode) {
            if (target.matches(uvselector)) {
                uvhandler.call(target, e);
                break;
            }
        }
    }, false);
}

/*Popups Functions*/
function uwsCreatePop(uvpopname) {
    let uvpopelem = document.createElement("div");
    uvpopelem.id = uvpopname;
    uvpopelem.classList.add("uws-pop-cont", "uws-integration");

    let uvpopelemtab = document.createElement("div");
    uvpopelemtab.classList.add("uws-pop-cont-tab");

    let uvpopelemcell = document.createElement("div");
    uvpopelemcell.classList.add("uws-pop-cont-cell");

    let uvpopelembox = document.createElement("div");
    uvpopelembox.classList.add("uws-pop-box");

    let uvpopelemclose = document.createElement("a");
    uvpopelemclose.classList.add("uws-closepop", "uwsjs-closepop");

    let uvpopelemcloselabel = document.createElement("span");
    uvpopelemcloselabel.classList.add("uws-closepop-label");
    uvpopelemcloselabel.innerHTML = uwsFrontLang("close");
    uvpopelemclose.appendChild(uvpopelemcloselabel);

    let uvpopelemcharge = document.createElement("div");
    uvpopelemcharge.classList.add("uws-pop-charge");

    uvpopelembox.appendChild(uvpopelemclose);
    uvpopelembox.appendChild(uvpopelemcharge);
    uvpopelemcell.appendChild(uvpopelembox);
    uvpopelemtab.appendChild(uvpopelemcell);
    uvpopelem.appendChild(uvpopelemtab);
    document.body.appendChild(uvpopelem);

    uvpopelemclose.addEventListener("click", function () {
        uwsHidePopup(uvpopelem, true);
    });
    uvpopelem.addEventListener("click", function (e) {
        uwsHidePopup(uvpopelem, false, e);
    });

    return uvpopelem;
}
function uwsFadePopup(uvpoptarget) {
    uvpoptarget.classList.add("visible");
    document.getElementsByTagName('html')[0].classList.add("uws-pop-open");
}
function uwsHidePopup(uvpoptarget, uvpopforceclose, e) {
    uvpopforceclose = (uvpopforceclose != undefined) ? uvpopforceclose : false;

    let uvpopbox = uvpoptarget.querySelectorAll(".uws-pop-box");
    uvpopbox = uvpopbox[0];

    // const uvclickedoutside = !uvpopbox.matches(":hover"); // Old: unreliable on mobile and with DevTools open
    const uvclickedoutside = !e || !uvpopbox.contains(e.target);
    if ((uvpopforceclose) || (uvclickedoutside && !uvpoptarget.classList.contains("uws-noareaclose"))) {
        uvpoptarget.classList.remove("visible");
        setTimeout(function () {
            let uvpopnoclear = (uvpoptarget.classList.contains("uwsnoclear")) ? 1 : 0;

            uvpoptarget.className = "uws-pop-cont uws-integration";

            //Check if there are popups opened
            let uvopenedpopups = 0;
            let uvpopupselems = document.querySelectorAll(".uws-pop-cont");

            Array.prototype.forEach.call(uvpopupselems, function (el, i) {
                if (el.classList.contains("visible"))
                    uvopenedpopups++;
            });

            if (uvopenedpopups == 0)
                document.getElementsByTagName('html')[0].classList.remove("uws-pop-open");

            if (!uvpopnoclear)
                uwsClearPopup(uvpoptarget);
            else {
                uvpoptarget.classList.add("uwsnoclear");
                setTimeout(function () {
                    uvpoptarget.classList.add("uwsnoclear");
                }, 10);
            }

            //Callback
            const uvpopclosecallback = uvpoptarget.getAttribute("data-closecallback");
            uvpoptarget.removeAttribute("data-closecallback");
            if (typeof window[uvpopclosecallback] === "function") {
                window[uvpopclosecallback]();
            }
        }, 300);
    }
}
function uwsClearPopup(uvpoptarget, uvpopcontent) {
    uvpopcontent = (uvpopcontent != undefined) ? uvpopcontent : "";

    let uvpopchargetarget = uvpoptarget.querySelectorAll(".uws-pop-charge");
    uvpopchargetarget = uvpopchargetarget[0];

    if (uvpopchargetarget != undefined) {
        uvpopchargetarget.innerHTML = "";
        uvpopchargetarget.insertAdjacentHTML("beforeend", uvpopcontent);
    }
}

/*allow only numbers on phone*/
function uwsPhoneInputFromat(event) {
    var inputField = event.target;
    var newValue = inputField.value.replace(/[^0-9]/g, '');
    inputField.value = newValue;
}

//Set cookie
function uwsSetCookie(uvname, uvvalue, uvdays) {
    var uvexpires = "";
    if (uvdays) {
        var uvdate = new Date();
        uvdate.setTime(uvdate.getTime() + (uvdays * 24 * 60 * 60 * 1000));
        uvexpires = "; expires=" + uvdate.toUTCString();
    }
    document.cookie = uvname + "=" + (uvvalue || "") + uvexpires + "; path=/";
}
//Get cookie
function uwsGetCookie(uvname) {
    var uvnameEQ = uvname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(uvnameEQ) == 0) return c.substring(uvnameEQ.length, c.length);
    }
    return "";
}

/*Add or edit parameters on the url*/
function uwsAddParameterURI(key, value) {
    let uri = window.location.href;
    let re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    let separator = uri.indexOf('?') !== -1 ? "&" : "?";

    if (!value) {
        uri = uri.replace(new RegExp("([?&])" + key + "=[^&]*", "i"), '');
        uri = uri.replace(/\/&/, '/?');
    } else if (uri.match(re)) {
        uri = uri.replace(re, '$1' + key + "=" + encodeURIComponent(value) + '$2');
    } else {
        uri = uri + separator + key + "=" + encodeURIComponent(value);
    }

    window.history.replaceState("", "", uri);
}

/*Get lang string*/
function uwsFrontLang(uvkey) {
    let uvtext = uvkey;

    if (typeof (uws_front_lang[uvkey]) != "undefined")
        uvtext = uws_front_lang[uvkey];

    return uvtext;
}

function uwsDebounce(uvfunc, uvdelay) {
    clearTimeout(uws_debounce_timer);
    uws_debounce_timer = setTimeout(() => uvfunc.apply(this, arguments), uvdelay);
}
//Loading images: with class .uwsimgloading and add class .uwsloaded when they are loaded (for fade in effect)
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.uwsimgloading').forEach(function (img) {
    if (img.complete) {
      img.classList.add('uwsloaded');
      return;
    }

    img.addEventListener('load', function () {
      img.classList.add('uwsloaded');
    });
  });
});
