var uws_mem_validator;

uwsClickListener(".uwsjs-select-prim-membership", function(e){
    e.preventDefault();
}); 

uwsClickListener(".uwsjs-add-secondarymembership", function(e){
    e.preventDefault();

    const uvsubscode = this.getAttribute("data-subscode");
    uwsmemAddSecondaryMembership(uvsubscode);
});

uwsClickListener(".uwsjs-remove-secondarymembership", function(e){
    e.preventDefault();

    const uvsubscode = this.getAttribute("data-subscode");
    const uvremoveelem = this.closest(".uwssubmembershipinputs");

    uvremoveelem.remove();


    const uvitemsels = uws_inventory.popitemsels;
    const uvitem = uws_inventory.popitem;
    const uvprimmembership = uvitem.elements[uvitemsels.primmasteritemcode];
    const uvallsubscriptions = uvprimmembership.subscriptions;
    const uvsub = uvallsubscriptions[uvsubscode];
    const uvmaxsubs = (uvsub.max_qty) ? uvsub.max_qty / 1 : 1;

    const uvcurrentmeminputs = document.querySelectorAll(".uwsdy-secondary-memberships-" + uvsubscode + " .uwssubmembershipinputs");

    if(uvcurrentmeminputs.length >= uvmaxsubs)
        document.querySelector(".uws-secondary-membership-" + uvsubscode).classList.add("uwsfull");
    else
        document.querySelector(".uws-secondary-membership-" + uvsubscode).classList.remove("uwsfull");

    uwsmemReBuildMembershipForm(uws_invitem_pop.querySelector('.uwsinv-item'));
});

function uwsmemInitInvItemMemberships(uvitempopresponse){
    uws_invitem_pop.classList.add('uws-memberships-pop');
    uvinvitemelem = uws_invitem_pop.querySelector('.uwsinv-item');

    uwsmemAddPrimMemeberships(uvinvitemelem);
    uwsmemAddItemSels();
    uwsmemAddSecondaryMemberships(uvinvitemelem);
    uwsmemInitMembershipForm(uvinvitemelem);
    uwsmemUpdateMembershipFormVars(uvinvitemelem);
    uwsmemUpdateItemPop(uvinvitemelem);
}

function uwsmemAddPrimMemeberships(uvinvitemelem){
    const uvprimmemberships = uws_inventory.popitem.elements;
    const uvprimmembershiptemplate = uws_inventory.templates["membership-primmembership-sel-item"];
    let uvprimemberships = "";

    Object.keys(uvprimmemberships).forEach(function (key) {
        const el = uvprimmemberships[key];
        let uvprimmemitem = uvprimmembershiptemplate;
        const uvmastercode = el.info.mastercode;
        const uvitemname = el.info.itemname;
        const uvitemlabel = el.info.label;

        uvprimmemitem = uvprimmemitem.replace(/{mastercode}/g, uvmastercode);
        uvprimmemitem = uvprimmemitem.replace(/{membershipname}/g, uvitemname);
        uvprimmemitem = uvprimmemitem.replace(/{membershiplabel}/g, uvitemlabel);

        uvprimemberships += uvprimmemitem;
    });

    uwsinvUpdateDyElem(uvinvitemelem, "memberships-primary", uvprimemberships);
    //uvprimmembershiptemplate = uvprimmembershiptemplate.replace(/{mastercode}/g, uvpaynowcontclass);
}

function uwsmemAddItemSels(){
    const uvprimmemberships = uws_inventory.popitem.elements;
    let uvprimarymembership = "";
    let uvqtydefault = 1;
    let uvmastercode = "";
    let uvmasteritemcode = "";

    Object.keys(uvprimmemberships).forEach(function (key) {
        if(!uvprimarymembership){
            uvprimarymembership = uvprimmemberships[key];
            uvmasteritemcode = key;
        }
    });

    if(uvprimarymembership){
        uvqtydefault = uvprimarymembership.header.qtydefault;
        uvmastercode = uvprimarymembership.info.mastercode;
    }

    window.uws_inventory.popitemsels = window.uws_inventory.popitemsels || {};
    uwsinvSetPopitemSelection("guests", uvqtydefault);
    uwsinvSetPopitemSelection("time", "");
    uwsinvSetPopitemSelection("duration", "");
    uwsinvSetPopitemSelection("paytype", "prepay");
    uwsinvSetPopitemSelection("primmastercode", uvmastercode);
    uwsinvSetPopitemSelection("primmasteritemcode", uvmasteritemcode);
}

function uwsmemUpdateItemPop(uvinvitemelem){
    const uvpricesbreakdown = uwsmemGetItemPricesBreakdown();
    const uvitemsels = uws_inventory.popitemsels;
    const uvitem = uws_inventory.popitem;
    const uvprimmembership = uvitem.elements[uvitemsels.primmasteritemcode];
    const uvsubsprimary = uvprimmembership.subscriptions.primary;

    uwsinvUpdateDyElem(uvinvitemelem, "primary-membership-prices", uvpricesbreakdown);
    uwsinvUpdateDyElem(uvinvitemelem, "primary-membership-name", uvsubsprimary.membershipname);
}

function uwsmemGetItemPricesBreakdown(){
    let uvpricesbreakdown = "";
    let uvpricesbreaklist = "";
    const uvitemsels = uws_inventory.popitemsels;
    const uvitem = uws_inventory.popitem;
    const uvprimmembership = uvitem.elements[uvitemsels.primmasteritemcode];
    const uvinitalpaylabel = uvprimmembership.header.title;
    const uvcursymbol = uvprimmembership.info.currency_symbol;
    const uvinitialprice = uvprimmembership.info.listprice;
    const uvbreakdown = uvprimmembership.info.breakdown;
    const uvbreakdownslabels = uws_inventory.popitem.library.breakdowns;
    let uvbreaktotal = "";
    let uvbreaklabel = "";

    if((uvbreakdown !== null)){
        Object.entries(uvbreakdown).forEach(([uvkey, uvvalue]) => {
            if (uvkey != "input" && uvvalue != "0" && uvkey != "total") {
                const uvbreakdownlabel = (typeof (uvbreakdownslabels[uvkey]) != "undefined") ? uvbreakdownslabels[uvkey] : uvkey;
                uvpricesbreaklist += `<div class='uwsbreakitem uwsbreakitem-${uvkey}'><span class='uwsname'>${uvbreakdownlabel}</span><span class='uwsprice' data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvvalue, 1)}</span></div>`;
            }
            else if (uvkey == "total") {
                uvbreaktotal = uvvalue;
                uvbreaklabel = (typeof (uvbreakdownslabels[uvkey]) != "undefined") ? uvbreakdownslabels[uvkey] : uvkey;
            }
        });
    }

    uvpricesbreakdown = `
        <div class="uws-togglecoll">
            <a class="uwsjs-toggle-collapse" href='#open-item-price-details'>
                <div class="uwsname"><span>Details</span> <i class="uwsicon-right-open"></i></div>
                <div class="uwsbkpricecont"><span class="uwsname">${uvinitalpaylabel}</span><div class="uwsprice" data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvinitialprice, 1)}</div></div>
            </a>

            <div class="uws-togglecoll-body">
                <div class="uws-togglecoll-inner">
                    ${uvpricesbreaklist}
                    <div class='uwsbreakitem uwsbreakitem-total'><span class='uwsname'>${uvbreaklabel}</span><span class='uwsprice' data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvbreaktotal, 1)}</span></div>
                </div>
            </div>
        </div>
    `;

    const uvsubsprimary = uvprimmembership.subscriptions.primary;
    const uvsubsprimarylistprice = uvsubsprimary.listprice;
    const uvsubsprimarypaylabel = uvsubsprimary.feename;
    const uvsubsprimarybreakdown = uvsubsprimary.breakdowns.internal.prepay;
    let uvsubprimbreaktotal = "";
    let uvsubprimbreaklabel = "";
    let uvsubprimpricesbreaklist = "";

    if(uvsubsprimarybreakdown !== null){
        Object.entries(uvsubsprimarybreakdown).forEach(([uvkey, uvvalue]) => {
            if (uvkey != "input" && uvvalue != "0" && uvkey != "total") {
                const uvbreakdownlabel = (typeof (uvbreakdownslabels[uvkey]) != "undefined") ? uvbreakdownslabels[uvkey] : uvkey;
                uvsubprimpricesbreaklist += `<div class='uwsbreakitem uwsbreakitem-${uvkey}'><span class='uwsname'>${uvbreakdownlabel}</span><span class='uwsprice' data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvvalue, 1)}</span></div>`;
            }
            else if (uvkey == "total") {
                uvsubprimbreaktotal = uvvalue;
                uvsubprimbreaklabel = (typeof (uvbreakdownslabels[uvkey]) != "undefined") ? uvbreakdownslabels[uvkey] : uvkey;
            }
        });
    }

    uvpricesbreakdown += `
        <div class="uwsprimmemname uwsprimmemnamenext">Later</div>
        <div class="uws-togglecoll">
            <a class="uwsjs-toggle-collapse" href='#open-item-price-details'>
                <div class="uwsname"><span>Details</span> <i class="uwsicon-right-open"></i></div>
                <div class="uwsbkpricecont"><span class="uwsname">${uvsubsprimarypaylabel}</span><div class="uwsprice" data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvsubsprimarylistprice, 1)}</div></div>
            </a>

            <div class="uws-togglecoll-body">
                <div class="uws-togglecoll-inner">
                    ${uvsubprimpricesbreaklist}
                    <div class='uwsbreakitem uwsbreakitem-total'><span class='uwsname'>${uvsubprimbreaklabel}</span><span class='uwsprice' data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvsubprimbreaktotal, 1)}</span></div>
                </div>
            </div>
        </div>
    `;

    return uvpricesbreakdown;
}

function uwsmemAddSecondaryMemberships(uvinvitemelem){
    let uvsecondarymemberships = "";
    const uvitemsels = uws_inventory.popitemsels;
    const uvitem = uws_inventory.popitem;
    const uvprimmembership = uvitem.elements[uvitemsels.primmasteritemcode];
    const uvallsubscriptions = uvprimmembership.subscriptions;
    const uvcursymbol = uvprimmembership.info.currency_symbol;

    Object.entries(uvallsubscriptions).forEach(([uvkey, uvvalue]) => {
        if(uvkey != "primary" && uvkey != "junior"){
            const uvsecprice = (uvvalue.listprice) ? uvvalue.listprice : uvvalue.recurring_fee_amount;
            const uvseclabel = (uvvalue.feename) ? uvvalue.feename : uvvalue.recurring_fee_name;

            uvsecondarymemberships += `
                <div class="uws-secondary-membership uws-secondary-membership-${uvkey}" data-mastercode="${uvvalue.mastercode}" data-code="${uvkey}">
                    <div class="uwsdy-secondary-memberships-${uvkey} uwssecmembershipinputs"></div>

                    <div class="uws-secmembership-addbox">
                        <div class="uwsname">${uvvalue.membershipname}</div>
                        <button class="uws-btn uws-btn-s uws-btn-100 uwsjs-add-secondarymembership" data-subscode="${uvkey}"><span>Add Membership</span><div class='uwspriceinfo'>+<span class="uwsprice" data-symbol="${uvcursymbol}">${uwsFrontformatMoney(uvsecprice, 1)}</span> / <span>${uvseclabel}</span></div></button>
                    </div>
                </div>
            `;
        }
    });

    uwsinvUpdateDyElem(uvinvitemelem, "secondary-memberships", uvsecondarymemberships);
}

function uwsmemAddSecondaryMembership(uvsubscode){
    const uvitemsels = uws_inventory.popitemsels;
    const uvitem = uws_inventory.popitem;
    const uvprimmembership = uvitem.elements[uvitemsels.primmasteritemcode];
    const uvallsubscriptions = uvprimmembership.subscriptions;
    const uvsub = uvallsubscriptions[uvsubscode];
    const uvcursymbol = uvprimmembership.info.currency_symbol;
    const uvmaxsubs = (uvsub.max_qty) ? uvsub.max_qty / 1 : 1;
    const uvsecprice = (uvsub.listprice) ? uvsub.listprice : uvsub.recurring_fee_amount;
    const uvseclabel = (uvsub.feename) ? uvsub.feename : uvsub.recurring_fee_name;

    const uvcurrentmeminputs = document.querySelectorAll(".uwsdy-secondary-memberships-" + uvsubscode + " .uwssubmembershipinputs");

    if(uvcurrentmeminputs.length < uvmaxsubs){
        let uvthisinstance = uvcurrentmeminputs.length;
        const uvsecsubbreakdown = uvsub.breakdowns.internal.prepay;

        for (let i = 0; i < 100; i++) {
            if(!document.querySelector(".uwsdy-secondary-memberships-" + uvsubscode + " .uwssubmembershipinputs.uwsins-" + i)){
                uvthisinstance = i;
                break;
            }
        }

        const uvbreakdownslabels = uws_inventory.popitem.library.breakdowns;
        let uvpricesbreackdown = "";
        let uvsubsecpricesbreaklist = "";
        let uvsubsecmbreaktotal = "";
        let uvsubsecmbreaklabel = "";

        if(uvsecsubbreakdown !== null){
            Object.entries(uvsecsubbreakdown).forEach(([uvkey, uvvalue]) => {
                if (uvkey != "input" && uvvalue != "0" && uvkey != "total") {
                    const uvbreakdownlabel = (typeof (uvbreakdownslabels[uvkey]) != "undefined") ? uvbreakdownslabels[uvkey] : uvkey;
                    uvsubsecpricesbreaklist += `<div class='uwsbreakitem uwsbreakitem-${uvkey}'><span class='uwsname'>${uvbreakdownlabel}</span><span class='uwsprice' data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvvalue, 1)}</span></div>`;
                }
                else if (uvkey == "total") {
                    uvsubsecmbreaktotal = uvvalue;
                    uvsubsecmbreaklabel = (typeof (uvbreakdownslabels[uvkey]) != "undefined") ? uvbreakdownslabels[uvkey] : uvkey;
                }
            });
        }

        uvpricesbreackdown += `
            <div class='uwspricesbreakdown'>
                <div class="uws-togglecoll">
                    <a class="uwsjs-toggle-collapse" href='#open-item-price-details'>
                        <div class="uwsname"><span>Details</span> <i class="uwsicon-right-open"></i></div>
                        <div class="uwsbkpricecont"><span class="uwsname">${uvseclabel}</span><div class="uwsprice" data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvsecprice, 1)}</div></div>
                    </a>

                    <div class="uws-togglecoll-body">
                        <div class="uws-togglecoll-inner">
                            ${uvsubsecpricesbreaklist}
                            <div class='uwsbreakitem uwsbreakitem-total'><span class='uwsname'>${uvsubsecmbreaklabel}</span><span class='uwsprice' data-symbol='${uvcursymbol}'>${uwsFrontformatMoney(uvsubsecmbreaktotal, 1)}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const uvsebmembershipinputs = `
            <div class="uwssubmembershipinputs uwsins-${uvthisinstance}" data-instance="${uvthisinstance}">
                <div class="uwssubmemheader">
                    <div class="uwsinfo">
                        <div class="uwsname">${uvsub.membershipname}</div>
                        <div class="uwspriceinfo">
                            +<span class="uwsprice" data-symbol="${uvcursymbol}">${uwsFrontformatMoney(uvsecprice, 1)}</span> / <span>${uvseclabel}</span>
                        </div>
                    </div>

                    <a href="javascript:;" class="uwsjs-remove-secondarymembership" data-subscode="${uvsubscode}">Remove</a>
                </div>
                ${uvpricesbreackdown}
                <div class="uws-inputcont">
                    <label for="uwsprimmembername">Full Name *</label>
                    <input id="uwsprimmembername" class="uwsinputdy-name-primarymembership" name="subinfo[${uvsub.mastercode}][${uvthisinstance}][fullname]" type="text" value="" required>
                </div>
                <div class="uws-inputcont">
                    <label for="uwsprimmemberemail">Email *</label>
                    <input id="uwsprimmemberemail" class="uwsinputdy-name-primarymembership uwsvaluniqeemail" name="subinfo[${uvsub.mastercode}][${uvthisinstance}][email]" type="email" required>
                </div>
                <input type="hidden" name="subinfo[${uvsub.mastercode}][${uvthisinstance}][type]" value="${uvsubscode}">
                <input type="hidden" name="subinfo[${uvsub.mastercode}][${uvthisinstance}][listprice]" value="${uvsub.listprice}">
                <input type="hidden" name="subinfo[${uvsub.mastercode}][${uvthisinstance}][membershipname]" value="${uvsub.membershipname}">
            </div>
        `;

        uvinvitemelem = uws_invitem_pop.querySelector('.uwsinv-item');
        //uwsinvUpdateDyElem(uvinvitemelem, "secondary-memberships-" + uvsubscode, uvsebmembershipinputs);
        document.querySelector(".uwsdy-secondary-memberships-" + uvsubscode).insertAdjacentHTML('beforeend', uvsebmembershipinputs);

        uwsmemReBuildMembershipForm(uvinvitemelem);
    }

    const uvcurrentmeminputsnew = document.querySelectorAll(".uwsdy-secondary-memberships-" + uvsubscode + " .uwssubmembershipinputs");

    if(uvcurrentmeminputsnew.length >= uvmaxsubs)
        document.querySelector(".uws-secondary-membership-" + uvsubscode).classList.add("uwsfull");
    else
        document.querySelector(".uws-secondary-membership-" + uvsubscode).classList.remove("uwsfull");
}

function uwsmemInitMembershipForm(uvinvitemelem){
    const uvform = uvinvitemelem.querySelector(".uws-membership-form");
    uwsmemReBuildMembershipForm(uvinvitemelem);

    uvform.addEventListener("submit", function(e) {
        e.preventDefault();
        const uvformvalid = uws_mem_validator.validate();

        if (uvformvalid) {
            const uvformproxy = uws_inventory.proxies["cart-additem"];
            let uvformdata = new FormData(uvform);
            uvinvitemelem.classList.add("uwsloading");

            let uvrequest = new XMLHttpRequest();
            uvrequest.open('POST', uvformproxy, true);
            uvrequest.onload = function() {
                if (this.status >= 200 && this.status < 400) {
                    let uvresponse = this.response;
                    uvresponse = JSON.parse(uvresponse);

                    if (typeof (uvresponse.cartcode)) {
                        if (typeof (uvhookInvItemAdded) == "function")
                            uvhookInvItemAdded(uws_inventory.popitem, uws_inventory.popitemsels, uvresponse);
    
                        if (typeof (uvhookInvCartEdited) == "function")
                            uvhookInvCartEdited(uvresponse);
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
                } else {
                    console.log("UVJS Error: Server returned an error");
                }
            };
            uvrequest.onerror = function() {
                console.log("UVJS Error: Request Error");
            };
            uvrequest.send(uvformdata);
        } else {
            const uverrorinput = uvform.querySelector(".uwshaserror");
            if (uverrorinput)
                uverrorinput.querySelector("input, select, textarea").focus();
        }
    });
}

function uwsmemReBuildMembershipForm(uvinvitemelem){
    const uvform = uvinvitemelem.querySelector(".uws-membership-form");
    if(uws_mem_validator) uws_mem_validator.destroy();
    uws_mem_validator = new Pristine(uvform, {
        classTo: "uws-inputcont",
        errorTextParent: "uws-inputcont",
        errorClass: "uwshaserror",
        errorTextClass: "uwsinputerror",
    });

    const uvemailinputs = uvform.querySelectorAll('.uwsvaluniqeemail');

    uvemailinputs.forEach((uvinput) => {
        uws_mem_validator.addValidator(
            uvinput,
            function(value) {
                const emailValues = Array.from(uvemailinputs).map(input => input.value.trim());
                const emailSet = new Set(emailValues);
                const isUnique = emailValues.length === emailSet.size;
                return isUnique;
            },
            "Emails must be unique",
            2,
            true
        );
    });
}

function uwsmemUpdateMembershipFormVars(uvinvitemelem){
    const uvitemsels = uws_inventory.popitemsels;
    const uvitem = uws_inventory.popitem;
    const uvprimmembership = uvitem.elements[uvitemsels.primmasteritemcode];
    const uvsubsprimary = uvprimmembership.subscriptions.primary;
    
    uvinvitemelem.querySelector(".uwsinputdy-value-guests").value = uvitemsels.guests;
    uvinvitemelem.querySelector(".uwsinputdy-value-paytype").value = uvitemsels.paytype;
    uvinvitemelem.querySelector(".uwsinputdy-value-primmastercode").value = uvprimmembership.info.mastercode;
    uvinvitemelem.querySelector(".uwsinputdy-value-itemcode").value = "";
    uvinvitemelem.querySelector(".uwsinputdy-value-caldate").value = uvitem.info.caldate;
    uvinvitemelem.querySelector(".uwsinputdy-value-venuecode").value = uvprimmembership.info.venuecode;
    uvinvitemelem.querySelector(".uwsinputdy-value-ecozone").value = uvprimmembership.info.ecocode;
    uvinvitemelem.querySelector(".uwsinputdy-value-itemname").value = uvprimmembership.info.itemname;
    uvinvitemelem.querySelector(".uwsinputdy-value-listprice").value = uvprimmembership.info.listprice;
    uvinvitemelem.querySelector(".uwsinputdy-value-listprice").value = uvprimmembership.info.listprice;
    uvinvitemelem.querySelector(".uwsinputdy-value-subtotalagree").value = uvprimmembership.info.listprice;
    uvinvitemelem.querySelector(".uwsinputdy-value-membername").value = uvsubsprimary.membershipname;

    const uvinputnamedyelems = uvinvitemelem.querySelectorAll(".uwsinputdy-name-primarymembership");
    uvinputnamedyelems.forEach(function(el){
        let uvcurrentname = el.getAttribute("name");
        let uvnewname = uvcurrentname.replace("{primary_membership}", uvprimmembership.info.mastercode);
        el.setAttribute("name", uvnewname);
    });
}