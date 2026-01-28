var uws_inqvalidator = [];
var uws_inq_dp_date;
var loadedmonths = [];
var uws_datp = uwsFirstDayMonth();

uwsDOMReady(function () {
    const uvinquiryforms = document.querySelectorAll(".uwsjs-inquiryform");
    Array.prototype.forEach.call(uvinquiryforms, function (el, i) {
        uwsInitInquiryForm(el);
    });

    const uvinqdatebtn = document.querySelector("#uwsinqddate");
    const uvopendays = uvinqdatebtn?.classList.contains("uwsopendays") ? 1 : 0;

    if (uvinqdatebtn) { //init datepicker
        const uvmindate = uvinqdatebtn.getAttribute("data-date");

        uws_inq_dp_date = new Litepicker({
            element: document.querySelector(".uws-inq-dp-date"),
            minDate: uvmindate,
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

                    document.querySelector(".uws-inquiryform-cont .uwsdy-inq-caldate").value = uvseldate;
                    uvinqdatebtn.querySelector(".uwsdy-inq-ddate").innerHTML = uvddate
                    document.querySelector(".uws-isdateinput .uwsinputerror").innerHTML = "";
                });

                n.on('render:month', (month, date) => {
                    const uvseldate = date.format('YYYY-MM-DD');
                    uws_datp = uvseldate;
                });

                if (uvopendays) {
                    n.on('change:month', (date) => {
                        const uvcalendarelem = document.querySelector('.uws-inq-dp-date');
                        if (uvcalendarelem) {
                            const uvdatesloader = document.createElement('div');
                            uvdatesloader.className = 'uws-inquiry-loader ';

                            const uvload = document.createElement('div');
                            uvload.className = 'uws-loader-uvicon';
                            uvdatesloader.appendChild(uvload);

                            uvcalendarelem.appendChild(uvdatesloader);
                        }
                        const uvcheckdate = date.format('YYYY-MM-DD');
                        uwsInqueryUpdateMonth(uvcheckdate);
                    });
                }
            }
        });

        if (uvopendays)
            uwsInqueryUpdateMonth(uws_datp);

    }

    const uwsSelectVenue = document.querySelector('.uwsjs-inquiryform #unvinqvenue');
    if (document.querySelector('.uwsjs-inquiryform #unvinqvenue')) {
        if (uwsSelectVenue.value == "") {
            document.querySelector('.uwsjs-inquiryform .uws-inquiry-dpinput').classList.add('uv-date-disable');
        }
        uwsSelectVenue.addEventListener('change', (event) => {
            if (uwsSelectVenue.value == "") {
                document.querySelector(' .uwsjs-inquiryform .uws-inquiry-dpinput').classList.add('uv-date-disable');
            } else {
                document.querySelector('.uwsjs-inquiryform .uws-inquiry-dpinput').classList.remove('uv-date-disable');
                setTimeout(() => {
                    const calendarcontainer = document.querySelector('.uws-inq-dp-date');
                    if (calendarcontainer) {
                        // Create the new div element
                        const uvdatesloader = document.createElement('div');
                        uvdatesloader.className = 'uws-inquiry-loader ';

                        const uvload = document.createElement('div');
                        uvload.className = 'uws-loader-uvicon';
                        uvdatesloader.appendChild(uvload);

                        calendarcontainer.appendChild(uvdatesloader);
                    }
                    uwsInqueryUpdateMonth(uws_datp);
                }, 100); // Delay in milliseconds
            }
        });
    } else {
        document.querySelector('.uwsjs-inquiryform .uws-inquiry-dpinput').classList.remove('uv-date-disable');
    }
});

uwsChangeListener(".uwsjs-inq-selectvenue", function (e) {
    const uvselectedopt = this.options[this.selectedIndex];
    const uvvenuecode = uvselectedopt.getAttribute("data-venuecode");
    const uvmanageentid = uvselectedopt.getAttribute("data-manageentid");
    const uvvenueid = uvvenuecode.replace("VEN", "");
    const uvform = this.closest("form");

    if (uvform.querySelector(".uws-leadtypeselector")) {
        uvform.querySelector(".uws-leadtypeselector").classList.remove("uwsactive");
        uvform.querySelector(".uwsdy-leadtypeselector").innerHTML = "";
    }

    uvform.querySelector(".uwsdy-inq-leadtype").value = "";
    uvform.querySelector(".uwsdy-inq-venueid").value = uvvenueid;
    uvform.querySelector(".uwsdy-inq-manageentid").value = uvmanageentid;

    const uvleadtypesproxy = uws_proxy + "&uvaction=uwspx_getinquiryleadtypes&venuecode=" + uvvenuecode + "&manageentid=" + uvmanageentid;

    let uvrequest = new XMLHttpRequest();
    uvrequest.open('GET', uvleadtypesproxy, true);
    uvrequest.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            let uvresponse = this.response;
            uvresponse = JSON.parse(uvresponse);

            if (typeof (uvresponse.leadtypes) == "object") {
                if (uvresponse.leadtypes.length > 1) { //only create select if there is more than one lead type
                    let uvleadtypeselector = `<select id="uwsinqleadtype" class="uwsjs-inq-updateleadtype" name="inqleadtype" required>`;
                    uvleadtypeselector += `<option value="">Select Lead Type</option>`;
                    uvresponse.leadtypes.forEach((el) => {
                        uvleadtypeselector += `<option value="${el.id}">${el.name}</option>`;
                    });

                    uvleadtypeselector += `</select>`;

                    if (uvform.querySelector(".uws-leadtypeselector")) {
                        uvform.querySelector(".uws-leadtypeselector").classList.add("uwsactive");
                        uvform.querySelector(".uwsdy-leadtypeselector").innerHTML = uvleadtypeselector;
                    }
                } else if (uvresponse.leadtypes.length == 1) {
                    uvform.querySelector(".uwsdy-inq-leadtype").value = uvresponse.leadtypes[0].id;
                }
            }

            uws_inqvalidator[0].destroy();
            uws_inqvalidator[0] = new Pristine(uvform, {
                classTo: "uws-inputcont",
                errorTextParent: "uws-inputcont",
                errorClass: "uwshaserror",
                errorTextClass: "uwsinputerror"
            });
        } else {
            console.log("UVJS Error: Server returned an error");
        }
    };
    uvrequest.onerror = function () {
        console.log("UVJS Error: Request Error");
    };
    uvrequest.send();
});

uwsChangeListener(".uwsjs-inq-updateleadtype", function (e) {
    const uvform = this.closest(".uws-inquiryform");
    uvform.querySelector(".uwsdy-inq-leadtype").value = this.value;
});

function uwsInitInquiryForm(uvform) {

    if (uvform.querySelector('input[name="redirect_to"]')) {
        var redirectToField = uvform.querySelector('input[name="redirect_to"]');
        var redirectpath = redirectToField ? redirectToField.value : null;
    }

    uws_inqvalidator[0] = new Pristine(uvform, {
        classTo: "uws-inputcont",
        errorTextParent: "uws-inputcont",
        errorClass: "uwshaserror",
        errorTextClass: "uwsinputerror"
    });

    uvform.addEventListener("submit", function (e) {
        e.preventDefault();
        const uvformvalid = uws_inqvalidator[0].validate();
        let uvdateisvalid = 0;

        if (uvform.querySelector(".uwsdy-inq-caldate").value != "") {
            uvdateisvalid = 1;
            uvform.querySelector(".uws-isdateinput .uwsinputerror").innerHTML = "";
        } else if (uvform.querySelector(".uws-isdateinput"))
            uvform.querySelector(".uws-isdateinput .uwsinputerror").innerHTML = "Please select a date";

        if (uvformvalid && uvdateisvalid) {
            uvform.closest(".uws-inquiryform-cont").classList.add("uwsloading");

            const uvformproxy = uws_proxy + "&uvaction=uwspx_sendinquiry";
            let uvformdata = new FormData(uvform);

            if (uvformdata.get('fname') && uvformdata.get('lname')) {
                const partyname = uvformdata.get('fname') + ' ' + uvformdata.get('lname');
                uvformdata.set('partyname', partyname);
                uvformdata.delete('fname');
                uvformdata.delete('lname');
            }

            let uvrequest = new XMLHttpRequest();
            uvrequest.open('POST', uvformproxy, true);
            uvrequest.onload = function () {
                if (this.status >= 200 && this.status < 400) {
                    let uvresponse = this.response;
                    uvresponse = JSON.parse(uvresponse);

                    if (uvresponse.msg) {
                        uvform.closest(".uws-inquiryform-cont").querySelector(".uwsdy-inqmessage").innerHTML = uvresponse.msg;
                        uvform.closest(".uws-inquiryform-cont").classList.add("uwssubmitted");
                        uvform.closest(".uws-inquiryform-cont").classList.remove("uwsloading");

                        if (uvform.querySelector('input[name="redirect_to"]')) {
                            if (redirectpath != "") {
                                setTimeout(function () {
                                    window.location.href = redirectpath;
                                }, 1000);
                            }
                        }
                    }
                } else {
                    console.log("UVJS Error: Server returned an error");
                }
            };
            uvrequest.onerror = function () {
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


function uwsInqueryUpdateMonth(uvcheckdate) {

    var venueidnumbers = document.querySelector('.uwsdy-inq-venueid');
    var venueidnumbers = venueidnumbers.value;
    const uvvenuecode = "VEN" + venueidnumbers;

    let disabledselcteddays = uwsgetDisabledDays(loadedmonths, uws_datp, uvvenuecode);

    if (disabledselcteddays) {
        uws_inq_dp_date.setLockDays(disabledselcteddays);
        const loader_animation = document.querySelector('.uws-inq-dp-date');
        if (loader_animation) {
            const uvloaderelement = loader_animation.querySelector('.uws-inquiry-loader');
            if (uvloaderelement) {
                loader_animation.removeChild(uvloaderelement);
            }
        }

    } else {

        let uvcloseddatesproxy = uws_proxy + "&uvaction=uwspx_closeddates";
        uvcloseddatesproxy = uvcloseddatesproxy + "&date=" + uws_datp + "&venuecode=" + uvvenuecode;
        let uvrequest = new XMLHttpRequest();
        uvrequest.open('GET', uvcloseddatesproxy, true);
        uvrequest.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                let uvresponse = this.response;
                uvresponse = JSON.parse(uvresponse);

                if (uvresponse['availabilityinfo']['closeddates']) {
                    uws_inq_dp_date.setLockDays(uvresponse['availabilityinfo']['closeddates']);

                    const loader_animation = document.querySelector('.uws-inq-dp-date');
                    if (loader_animation) {
                        const uvloaderelement = loader_animation.querySelector('.uws-inquiry-loader');
                        if (uvloaderelement) {
                            loader_animation.removeChild(uvloaderelement);
                        }
                    }

                    // Check if the venue exists in loadedmonths
                    let venueExist = loadedmonths.find(item => item.venueid === uvvenuecode);

                    let disabledDaysData = {
                        month: uvcheckdate,
                        disabled_days: {
                            data: uvresponse['availabilityinfo']['closeddates']
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
                        loadedmonths.push(newVenue);
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

function uwsFirstDayMonth() {
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

function uwsgetDisabledDays(loadedmonths, month, venueid) {
    for (let venue of loadedmonths) {
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