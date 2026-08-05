var uvs_popup;
var uvs_pendchanges = false;
var uvs_admboxactmsgst = 0;

function uvsAdminInit() {
	document.body.insertAdjacentHTML("beforeend", "<div id='uvs-pop-up' class='uvs-pop-cont'><div class='uvs-pop-box'><a class='uvs-closepop uvsjs-closepop' href='javascript:;'><span class='uvs-hide'>Close</span><i class='uv-icon-cancel'></i></a><div class='uvs-pop-charge'></div></div></div>");

	uvs_popup = document.querySelector("#uvs-pop-up");

	const uvsadminform = document.querySelector("#uvs-uvcoreadmin-form");

	if (uvsadminform) {
		uvsadminform.addEventListener("submit", function (e) {
			e.preventDefault();

			if (!this.checkValidity()) {
				this.reportValidity();
				return;
			}

			let uvvenuearevalid = 1;
			const uvnewvenueselems = document.querySelectorAll(".uvs-admin-venueinf-vc-new");
			Array.prototype.forEach.call(uvnewvenueselems, function (el, i) {
				if (!el.querySelector(".uvsjs-spread-venuekey").value)
					uvvenuearevalid = 0;
			});

			if (uvvenuearevalid) {
				const uvsadminformobj = this;
				const uvsadmininfo = uvsSerializeFields(uvsadminformobj.querySelectorAll(".uvsjson"));
				const uvsadminaction = uvsadminformobj.getAttribute("action");

				const uvsactionsbtnset = uvsadminformobj.querySelector(".uvs-adminbox-actions .uvs-adminbox-actions-btnset:last-child");

				if (uvsactionsbtnset) {
					uvsactionsbtnset.classList.add("active");
					uvsQueryAll(".uvs-btn", uvsactionsbtnset).forEach(function (el) {
						el.disabled = true;
					});
				}

				let uvrequest = new XMLHttpRequest();
				uvrequest.open("POST", uvsadminaction, true);
				uvrequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
				uvrequest.onload = function () {
					if (this.status >= 200 && this.status < 400) {
						if (this.response == "saved") {
							setTimeout(function () {
								if (uvsactionsbtnset) {
									uvsactionsbtnset.classList.remove("active");
									uvsQueryAll(".uvs-btn", uvsactionsbtnset).forEach(function (el) {
										el.disabled = false;
									});
								}

								uvAdminBoxActionMessage(uvsadminformobj, "Changes Saved");
								uvs_pendchanges = 0;
							}, 1500);

							if (uvnewvenueselems.length) {
								uvs_pendchanges = 0;
								location.reload();
							}
						}
					}
				};
				uvrequest.onerror = function () {
					console.log("UVJS Error: Request Error");
				};
				uvrequest.send(uvsadmininfo);
			}
			else {
				alert("Venue key is required");
			}
		});
	}

	if (document.querySelector(".uvsjs-datepicker"))
		uvsInitDatepicker();

	//init colors
	uvsInitColorFields();

	//go to anchor tab
	if (window.location.hash) {
		const uvshashtab = document.querySelector(".uvs-adminbox-mainmenu a[href='" + window.location.hash + "']");
		if (uvshashtab) uvshashtab.click();
	}
}

//Check configuration
uvsClickListener(".uvsjs-checkapiconfig", function () {
	const uvcheckapiconfigloader = this.closest(".uvs-admin-apiconfig-actions").querySelector(".uv-loader-uvicon");
	const uvapikey = document.querySelector("#apiconfig-apikey").value;
	const uvmicrocode = document.querySelector("#apiconfig-microcode").value;
	const uverrorbox = this.closest(".uvs-admin-apiconfigcont").querySelector(".uvs-errorbox");
	const uvactbutton = this;

	if (uvapikey.length < 5)
		document.querySelector("#apiconfig-apikey").classList.add("uvs-error");
	else
		document.querySelector("#apiconfig-apikey").classList.remove("uvs-error");

	if (uvmicrocode.length < 2)
		document.querySelector("#apiconfig-microcode").classList.add("uvs-error");
	else
		document.querySelector("#apiconfig-microcode").classList.remove("uvs-error");

	//validated
	if (uvapikey.length >= 5 && uvmicrocode.length >= 2) {
		uvactbutton.classList.add("uvdisabled");
		uvcheckapiconfigloader.classList.add("uvactive");
		uverrorbox.classList.remove("uvactive");

		let uvcheckapiconfigurl = this.getAttribute("data-checkapiconfig");
		uvcheckapiconfigurl += "&apikey=" + uvapikey + "&microcode=" + uvmicrocode;

		let uvrequest = new XMLHttpRequest();
		uvrequest.open('GET', uvcheckapiconfigurl, true);
		uvrequest.onload = function () {
			if (this.status >= 200 && this.status < 400) {
				// Success!
				let uvresponse = this.response;
				uvresponse = JSON.parse(uvresponse);

				if (uvresponse["status"] == "error") {
					uverrorbox.querySelector(".uvsdy-apiconfigerror").innerHTML = uvresponse["error-msg"];
					uverrorbox.classList.add("uvactive");
				}
				else if (uvresponse["status"] == "success") {
					const uvvenueshtml = uvresponse["venueshtml"];

					document.querySelector("#uvs-admin-venuesinfo").innerHTML = uvvenueshtml;
					document.querySelector(".uvs-boxpanel-admin").classList.remove("uvapiconfig");

					document.querySelector("#uvinputapikey").value = uvapikey;
					document.querySelector("#uvinputmicrocode").value = uvmicrocode;

					document.querySelector(".uvs-menu-isvenues").click();
					uvs_pendchanges = 1;
				}

				uvcheckapiconfigloader.classList.remove("uvactive");
				uvactbutton.classList.remove("uvdisabled");
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

//view list add optacity if it's not enabled
uvsClickListener(".uvsjs-listorderviewitemenable", function () {
	const uvsswitchelem = this.closest(".uvs-switch-ui");

	if (uvsswitchelem.classList.contains("uvs-on")) {
		this.closest(".uvs-admin-listorderandview-item").classList.add("uvdisabled");
	}
	else {
		this.closest(".uvs-admin-listorderandview-item").classList.remove("uvdisabled");
	}
});

uvsClickListener(".uvsjs-copyendpoint", function (e) {
	e.preventDefault();

	const uvcopybtn = this;

	if (uvcopybtn.dataset.endpoint) {
		navigator.clipboard.writeText(uvcopybtn.dataset.endpoint).then(function () {
			uvcopybtn.innerHTML = "Copied!";
		});

		setTimeout(() => {
			uvcopybtn.innerHTML = "Copy Endpoint";
		}, 1200);
	}
});

// Generate Clear cache Endpoint
uvsClickListener(".uvsendpoint", function (e) {
	e.preventDefault();
	let uvsendpointerror = false;
	const uvsendpointbutton = this;
	const uvsendpointinp = this.closest("#uvs-admin-cache").querySelector("#uvinputcacheendpoint");
	const uvsendpointloader = this.closest("#uvs-admin-cache").querySelector(".uv-loader-uvicon");

	const uvcheckpoint = this.closest("#uvs-admin-cache").querySelector(".uvcheckpoint");

	const uvscurrentURL = window.location.hostname;

	let uvsendpointURL = `https://${uvscurrentURL}/apis/uvclearcache/`;

	uvsendpointloader.classList.add("active");
	uvsendpointbutton.classList.add("uvdisabled");

	setTimeout(() => {
		const uvsendpointinputs = document.querySelectorAll("#uvs-admin-cache input:not(#uvinputcacheendpoint)");

		let uvsendpointdata = {};

		uvsendpointinputs.forEach(function (uvinput) {
			if (uvinput.name === "cache[cacheapikey]") {
				uvsendpointdata.cacheapikey = uvinput.value;
			} else {
				uvsendpointdata[uvinput.name] = uvinput.value;
			}
		});

		// if any input is empty, show error
		uvsendpointinputs.forEach(function (uvinput) {
			if (!uvinput.value) {
				uvsendpointerror = true;
				uvinput.classList.add("uvs-error");
				alert("Please fill all fields");
				return;
			} else {
				uvinput.classList.remove("uvs-error");
			}
		});

		if (!uvsendpointerror && uvsendpointinp && uvcheckpoint) {
			uvsendpointURL += `?apikey=${uvsendpointdata.cacheapikey}`;

			uvsendpointinp.value = uvsendpointURL;
			uvcheckpoint.setAttribute("href", uvsendpointURL);
			uvcheckpoint.classList.add("active");

		}
		uvsendpointloader.classList.remove("active");
		uvsendpointbutton.classList.remove("uvdisabled");
	}, 400);

});

// Remotely Clear Cache
uvsClickListener(".uvsjs-clearcache", function (e) {

	e.preventDefault();

	const uvcachebutton = this;
	const uvloaderdiv = "<div class='uv-loader-uvicon uv-loader-uvwp'></div>";
	let uvcacheloader = (document.body.querySelector(".uv-loader-uvwp")) ? document.body.querySelector(".uv-loader-uvwp") : "";

	if (!uvcacheloader) {
		document.body.insertAdjacentHTML('beforeend', uvloaderdiv);
		uvcacheloader = document.body.querySelector(".uv-loader-uvwp");
	}

	const uvcacheurl = (uvcachebutton.dataset.endpoint) ? uvcachebutton.dataset.endpoint : uvcachebutton.querySelector("a").getAttribute("href");

	//validated
	if (uvcacheurl) {
		uvcacheloader.classList.add("active");

		let uvrequest = new XMLHttpRequest();
		uvrequest.open('GET', uvcacheurl, true);
		uvrequest.onload = function () {
			uvcacheloader.classList.remove("active");

			let uvresponse = this.response;
			uvresponse = JSON.parse(uvresponse);

			if (uvresponse["uv"]["success"]) {
				let uvcachedata = uvresponse["uv"]["success"];
				const uvcachestatus = uvcachedata["status"].toUpperCase();

				if (uvs_popup) uvs_popup.classList.add("uvs-pop-cache");

				uvsDisplayMsg(uvcachedata["message"], uvcachestatus, "CLOSE", 400);

				setTimeout(() => {
					uvsHidePopup(uvs_popup);
				}, 12000);
			}
		};
		uvrequest.onerror = function () {
			console.log("UVJS Error: Request Error");
		};
		uvrequest.send();
	}
});

//view list change default item
uvsClickListener(".uvsjs-listorderviewitemdef", function () {
	const uvsswitchelem = this.closest(".uvs-switch-ui");

	if (!uvsswitchelem.classList.contains("uvs-on")) {
		const uvsswitchelems = this.closest(".uvs-admin-listorderandview").querySelectorAll(".uvs-listorderviewdefswich");
		Array.prototype.forEach.call(uvsswitchelems, function (el, i) {
			if (uvsswitchelem != el) {
				el.classList.remove("uvs-on");
				const uvsswitchoffvalue = el.querySelector("input").getAttribute("data-value-off");
				el.querySelector("input").value = uvsswitchoffvalue;
			}
		});
	}
});

//view list move elem up and change order
uvsClickListener(".uvsjs-moveorderup", function () {
	const uvthisitem = this.closest(".uvs-admin-listorderandview-item");
	const uvprevitem = uvthisitem.previousSibling;

	if (uvprevitem)
		uvthisitem.parentNode.insertBefore(uvthisitem, uvprevitem);

	uvsUpadeInputsOrder(uvthisitem.closest(".uvs-admin-listorderandview"));
});

//view list move elem down and change order
uvsClickListener(".uvsjs-moveorderdown", function () {
	const uvthisitem = this.closest(".uvs-admin-listorderandview-item");
	const uvnextitem = uvthisitem.nextSibling;

	if (uvnextitem)
		uvthisitem.parentNode.insertBefore(uvnextitem, uvthisitem);

	uvsUpadeInputsOrder(uvthisitem.closest(".uvs-admin-listorderandview"));
});

// Clean initial date input
uvsClickListener(".uvsjs-clearinitialdatefield", function (e) {
	e.preventDefault();

	const input = document.querySelector(
	  "input[name='events[global-initaldate]']"
	);
	if (input) {
	  input.value = "";

	  // Clear selected days in flatpickr
	  const selectedDays = document.querySelectorAll(
		".flatpickr-days .dayContainer .flatpickr-day.selected"
	  );
	  selectedDays.forEach((day) => day.classList.remove("selected"));
	}
  });

//Update order inputs on view list
function uvsUpadeInputsOrder(uvnodeparent) {
	const uvlistelems = uvnodeparent.querySelectorAll(".uvs-admin-listorderandview-item");
	let uvselemscount = 1;

	Array.prototype.forEach.call(uvlistelems, function (el, i) {
		el.querySelector(".uvsinputorder").value = uvselemscount;

		uvselemscount++;
	});
}

uvsChangeListener(".uvsjs-copytoinput", function () {
	if (this.dataset.target != undefined) {
		const uvsnewinput = this.value + this.dataset.addafter;

		uvsQueryAll(this.dataset.target).forEach(function (el) {
			el.value = uvsnewinput;
		});
	}
});
uvsClickListener(".uvsjs-copycliptoclip", function () {
	const uvscliptarget = document.querySelector(this.dataset.target);

	if (!uvscliptarget)
		return;

	uvscliptarget.focus();
	uvscliptarget.select();

	const uvscopyclip = document.execCommand('copy');
	if (uvscopyclip) {
		this.innerHTML = "Copied";
		this.setAttribute("disabled", "disabled");
	}
	else {
		alert("Sorry, I can't copy it. Copy manually");
		this.parentNode.style.display = "none";
	}
});
uvsClickListener(".uvs-adminbox-mainmenu li a", function (e) {
	//e.preventDefault();

	uvsQueryAll(".uvs-adminbox-mainmenu li a").forEach(function (el) {
		el.classList.remove("active");
	});
	this.classList.add("active");

	let uvstarget = this.getAttribute("href");
	uvstarget = uvstarget.replace("#", "");

	uvsQueryAll(".uvs-admin-opt-section").forEach(function (el) {
		el.classList.remove("active");
	});

	const uvssection = document.querySelector("#uvs-admin-" + uvstarget);
	if (uvssection) uvssection.classList.add("active");
});
uvsClickListener(".uvsjs-checkvenueid", function () {
	const uvsloadtarget = this.dataset.loadertarget;
	const uvscheckurl = this.dataset.checkurl;
	const uvsinputveaid = document.querySelector("#veaid").value;
	const uvsvenuesmsg = document.querySelector(".uvs-admin-venuesmsg");

	if (document.querySelectorAll(".uvs-admin-venueinf-vc-" + uvsinputveaid).length > 0) {
		uvsvenuesmsg.innerHTML = "";
		uvsvenuesmsg.insertAdjacentHTML("beforeend", "<div class='uvs-admin-errormsg'>This Venue is already added</div>");
	}
	else if (uvsinputveaid.length > 3 && /^\d+$/.test(uvsinputveaid)) {
		uvsQueryAll(uvsloadtarget).forEach(function (el) {
			el.classList.add("active");
		});
		document.querySelector("#veaid").classList.remove("uvs-error");

		const uvsnvenues = document.querySelectorAll("#uvs-admin-venuesinfo .uvs-admin-venueinf").length;

		const uvscheckparams = new URLSearchParams({
			uvsve: uvsinputveaid,
			uvsnv: uvsnvenues,
		});
		const uvscheckfullurl = uvscheckurl + ((uvscheckurl.indexOf("?") > -1) ? "&" : "?") + uvscheckparams.toString();

		let uvrequest = new XMLHttpRequest();
		uvrequest.open('GET', uvscheckfullurl, true);
		uvrequest.onload = function () {
			if (this.status >= 200 && this.status < 400) {
				const uvresponse = this.response;

				uvsQueryAll(uvsloadtarget).forEach(function (el) {
					el.classList.remove("active");
				});

				if (uvresponse.includes("uvs-admin-venueinf")) {
					document.querySelector("#uvs-admin-venuesinfo").insertAdjacentHTML("beforeend", uvresponse);

					uvsQueryAll(".uvs-admin-errormsg", uvsvenuesmsg).forEach(function (el) {
						el.remove();
					});

					document.querySelector("#veaid").value = "";
				}
				else if (uvresponse.includes("uvs-admin-errormsg")) {
					uvsvenuesmsg.innerHTML = "";
					uvsvenuesmsg.insertAdjacentHTML("beforeend", uvresponse);
				}
			}
		};
		uvrequest.onerror = function () {
			console.log("UVJS Error: Request Error");
		};
		uvrequest.send();
	}
	else
		document.querySelector("#veaid").classList.add("uvs-error");
});
uvsClickListener(".uvsjs-removevenue", function () {
	this.closest(".uvs-admin-venueinf").remove();
	uvs_pendchanges = true;
});
uvsClickListener(".uvsjs-addflyerset", function () {
	const uvsflyersetcont = document.querySelector(this.dataset.target);

	if (!uvsflyersetcont)
		return;

	const uvsflyersetlast = uvsflyersetcont.querySelector(".uvs-infolist-groupnoti:last-child");
	const uvsflyersetfirst = uvsflyersetcont.querySelector(".uvs-infolist-groupnoti:first-child");
	const uvsflyersetany = uvsflyersetcont.querySelector(".uvs-infolist-groupnoti");

	if (!uvsflyersetlast || !uvsflyersetfirst || !uvsflyersetany)
		return;

	const uvsflyersetlastkey = uvsflyersetlast.dataset.nflyerset;

	const uvsflyersetnewkey = (uvsflyersetlastkey / 1) + 1;

	let uvsflyertypeshtml = uvsflyersetfirst.querySelector("select.uvsflyertype").innerHTML;
	let uvsflyerratiohtml = uvsflyersetfirst.querySelector("select.uvsflyerratio").innerHTML;

	const uvsflyerloc = uvsflyersetany.dataset.flyerloc;

	uvsflyertypeshtml = uvsflyertypeshtml.replace("selected", "");
	uvsflyerratiohtml = uvsflyerratiohtml.replace("selected", "");

	const newflyerelement = "<div class='uvs-infolist-groupnoti' data-nflyerset='" + uvsflyersetnewkey + "' data-flyerloc='" + uvsflyerloc + "'><div class='uvs-infolist-item'><div class='uvsname'>Flyer Type:</div><div class='uvsvalue'><select class='uvsjson uvsflyertype' name='flyers[" + uvsflyerloc + "][" + uvsflyersetnewkey + "][type]'>" + uvsflyertypeshtml + "</select></div></div><div class='uvs-infolist-item'><div class='uvsname'>Flyer Ratio:</div><div class='uvsvalue'><select class='uvsjson uvsflyerration' name='flyers[" + uvsflyerloc + "][" + uvsflyersetnewkey + "][ratio]'>" + uvsflyerratiohtml + "</select></div></div><div class='actions'><a class='uvsjs-removeflyer' href='javascript:;'>Remove</a></div></div>";

	uvsflyersetcont.insertAdjacentHTML("beforeend", newflyerelement);

	uvs_pendchanges = true;
});

uvsClickListener(".uvsjs-addnewvenue", function () {
	const uvsnewvenuecont = document.querySelector(this.dataset.target);

	if (!uvsnewvenuecont)
		return;

	const uvsnewvenuehtml = `<div class="uvs-admin-venueinf uvs-admin-venueinf-vc-new"><input class="uvsjson venueprimary" type="hidden" name="" data-inputname="venues[{venuekey}][isprimary]" value=""><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Venue KEY:</div><div class="uvsvalue"><input class="uvsjson uvsjs-spread-venuekey" type="text" name="" value="" data-inputname="venues[{venuekey}][venuekey]"></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Venue Logo:</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][logourl]" value=""></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Venue Name:</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][venuename]" value=""></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Venue Name Alias:</div><div class="uvsvalue"><input type="text" name="" data-inputname="venues[{venuekey}][venuealias]" value="" class="uvsjson"></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Use Alias as Venue Name:</div><div class="uvsvalue"><div class="uvs-switch-ui "><button class="uvsjs-trigger-switch" type="button"><span class="uvs-lb-on">Yes</span><span class="uvs-lb-off">No</span></button><input class="uvsjson" type="hidden" name="" data-inputname="venues[{venuekey}][venueforcealias]" value="" data-value-on="1" data-value-off=""></div></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Hide Events:</div><div class="uvsvalue"><div class="uvs-switch-ui "><button class="uvsjs-trigger-switch" type="button"><span class="uvs-lb-on">Yes</span><span class="uvs-lb-off">No</span></button><input class="uvsjson" type="hidden" name="" data-inputname="venues[{venuekey}][venuehideinevents]" value="" data-value-on="1" data-value-off=""></div></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Venue Code (VENXXXX):</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][venuecode]" value=""></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Manageentid:</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][manageentid]" value=""></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Providerid:</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][providerid]" value=""></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Resellerid:</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][resellerid]" value=""></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Venue ID:</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][urvenueid]" value=""></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Client ID:</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][clientid]" value=""></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">UrVenue Server:</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][uvserver]" value=""></div></div><div class="actions"><a class="uvsjs-removevenue" href="javascript:;">Remove</a></div></div>`;

	uvsnewvenuecont.insertAdjacentHTML("beforeend", uvsnewvenuehtml);

	uvs_pendchanges = true;
});

uvsChangeListener(".uvsjs-spread-venuekey", function () {
	const uvvenuekey = this.value;
	const uvjsoninputelems = this.closest(".uvs-admin-venueinf").querySelectorAll(".uvsjson");

	Array.prototype.forEach.call(uvjsoninputelems, function (el, i) {
		let uvinputname = el.getAttribute("data-inputname");
		uvinputname = uvinputname.replace("{venuekey}", uvvenuekey);
		el.setAttribute("name", uvinputname);
	});
});

uvsChangeListener(".uvsjson", function () {
	uvs_pendchanges = true;
});
uvsClickListener(".uvsjs-removeflyer", function () {
	this.closest(".uvs-infolist-groupnoti").remove();
	uvs_pendchanges = true;
});
uvsClickListener(".uvsjs-triggervenueprimary", function () {
	if (!this.classList.contains("active")) {
		uvsQueryAll("#uvs-admin-venuesinfo .uvs-admin-venueinf").forEach(function (uvsvenueitemtarget) {
			uvsQueryAll(".uvsjs-triggervenueprimary", uvsvenueitemtarget).forEach(function (el) {
				el.classList.remove("active");
				el.innerHTML = "Make Primary";
			});
			uvsQueryAll("input.venueprimary", uvsvenueitemtarget).forEach(function (el) {
				el.value = 0;
			});
		});

		const uvsvenuecont = this.closest(".uvs-admin-venueinf");

		uvsQueryAll(".uvsjs-triggervenueprimary", uvsvenuecont).forEach(function (el) {
			el.classList.add("active");
			el.innerHTML = "Is Primary";
		});
		uvsQueryAll("input.venueprimary", uvsvenuecont).forEach(function (el) {
			el.value = 1;
		});

		uvs_pendchanges = true;
	}
});
uvsClickListener(".uvs-switch-ui", function () {
	const uvsswitchinput = this.querySelector("input");

	if (!uvsswitchinput)
		return;

	if (this.classList.contains("uvs-on")) {
		this.classList.remove("uvs-on");
		uvsswitchinput.value = uvsswitchinput.dataset.valueOff;
	}
	else {
		this.classList.add("uvs-on");
		uvsswitchinput.value = uvsswitchinput.dataset.valueOn;
	}

	uvsswitchinput.dispatchEvent(new Event("change", { bubbles: true }));
});
uvsChangeListener(".uvsjs-controlfieldview", function () {
	const uvscvtarget = this.dataset.target;
	const uvscvshowon = this.dataset.showon;
	const uvscvhideon = this.dataset.hideon;

	if (this.value == uvscvshowon)
		uvsQueryAll(uvscvtarget).forEach(function (el) {
			el.style.display = "flex";
		});
	else if (this.value == uvscvhideon)
		uvsQueryAll(uvscvtarget).forEach(function (el) {
			el.style.display = "none";
		});
});
uvsClickListener(".uvsjs-gotoadminoptpage", function () {
	const uvsgototarget = this.dataset.target;

	uvsQueryAll(".uvs-adminbox-mainmenu a[href='" + uvsgototarget + "']").forEach(function (el) {
		el.click();
	});
});
uvsClickListener(".uvs-admin-iconboxlist a", function (e) {
	//e.preventDefault();

	const uvsgototarget = this.getAttribute("href");

	uvsQueryAll(".uvs-adminbox-mainmenu a[href='" + uvsgototarget + "']").forEach(function (el) {
		el.click();
	});
});


function uvsInitDatepicker() {
	var uvsdpmindate = new Date();

	flatpickr(".uvsjs-datepicker", {
		"minDate": uvsdpmindate,
		"monthSelectorType": "static",
		"position": "above",
		"yearSelectorType": "static"
	});
};
function uvAdminBoxActionMessage(uvsadminformobj, uvsactionsmessage) {
	clearTimeout(uvs_admboxactmsgst);

	const uvsactionsstatus = uvsadminformobj.querySelector(".uvs-adminbox-actions .uvs-adminbox-actions-status");

	if (!uvsactionsstatus)
		return;

	uvsactionsstatus.innerHTML = uvsactionsmessage;
	uvsactionsstatus.classList.add("active");

	uvs_admboxactmsgst = setTimeout(function () {
		uvsactionsstatus.classList.remove("active");
	}, 4000);
}



/*POPUPS ACTIONS*/
uvsClickListener(".uvs-pop-cont", function () {
	uvsHidePopup(this);
});
uvsClickListener(".uvsjs-closepop", function () {
	uvsHidePopup(this.closest(".uvs-pop-cont"), true);
});
/***************/

/*POPUPS FUNCTIONS*/
function uvsClearPopup(uvspoptarget, uvspopcontent) {
	uvspopcontent = (uvspopcontent != undefined) ? uvspopcontent : "";

	const uvspopcharge = uvspoptarget.querySelector(".uvs-pop-charge");
	if (uvspopcharge) uvspopcharge.innerHTML = uvspopcontent;
}
function uvsExpandPopup(uvspoptarget, uvspopexpand) {
	const uvspopbox = uvspoptarget.querySelector(".uvs-pop-box");
	if (uvspopbox) uvspopbox.style.maxWidth = (typeof uvspopexpand == "number") ? uvspopexpand + "px" : uvspopexpand;
}
function uvsFadePopup(uvspoptarget) {
	document.documentElement.classList.add("uvs-pop-open");
	uvspoptarget.classList.add("visible");
}
function uvsHidePopup(uvspoptarget, uvspopforceclose) {
	uvspopforceclose = (uvspopforceclose != undefined) ? uvspopforceclose : false;

	if (!uvspoptarget)
		return;

	const uvspopbox = uvspoptarget.querySelector(".uvs-pop-box");

	if ((uvspopforceclose) || ((uvspopbox) && (!uvspopbox.matches(":hover")))) {
		if (uvspoptarget.classList.contains("clearonclose"))
			uvsClearPopup(uvspoptarget);

		uvspoptarget.className = "uvs-pop-cont";
		document.documentElement.classList.remove("uvs-pop-open");

		setTimeout(function () {
			if (uvspopbox) uvspopbox.style.maxWidth = "";
		}, 300);
	}
}

function uvsDisplayMsg(uvsmsg, uvsmsgtitle, uvsmsgbutton, uvsmsgpopexpand) {
	if (uvsmsgtitle == undefined)
		uvsmsgtitle = "Message";
	if (uvsmsgbutton == undefined)
		uvsmsgbutton = "OK";
	if (uvsmsgpopexpand == undefined)
		uvsmsgpopexpand = 400;

	if (uvsmsgbutton != "hidden")
		var uvsmsgbutton = "<button class='uvsjs-closepop uvs-btn uvs-btn-100 uvs-btn-s'>" + uvsmsgbutton + "</button>";
	else
		var uvsmsgbutton = "";

	uvsExpandPopup(uvs_popup, uvsmsgpopexpand);
	uvsClearPopup(uvs_popup, "<div class='uvs-popheader'><h3>" + uvsmsgtitle + "</h3></div><div class='uvs-popbody'>" + uvsmsg + uvsmsgbutton + "</div>");
	uvsFadePopup(uvs_popup);
}
/***************/

/*COLOR FIELDS*/
function uvsInitColorFields() {
	const uvcolorfields = document.querySelectorAll(".uvs-color-field");

	Array.prototype.forEach.call(uvcolorfields, function (uvcolorfield) {
		if (uvcolorfield.dataset.uvcolorready)
			return;

		uvcolorfield.dataset.uvcolorready = "1";

		const uvcolorcont = document.createElement("div");
		uvcolorcont.className = "uvs-colorpicker";

		const uvcolorswatch = document.createElement("input");
		uvcolorswatch.type = "color";
		uvcolorswatch.className = "uvs-colorpicker-swatch";
		uvcolorswatch.setAttribute("aria-label", "Select color");
		uvcolorswatch.value = uvsNormalizeHex(uvcolorfield.value) || "#000000";

		uvcolorfield.parentNode.insertBefore(uvcolorcont, uvcolorfield);
		uvcolorcont.appendChild(uvcolorswatch);
		uvcolorcont.appendChild(uvcolorfield);

		//live feedback while the native picker is open
		uvcolorswatch.addEventListener("input", function () {
			uvcolorfield.value = uvcolorswatch.value;
		});

		//committed selection: let the field own the change event
		uvcolorswatch.addEventListener("change", function () {
			uvcolorfield.value = uvcolorswatch.value;
			uvcolorfield.dispatchEvent(new Event("change", { bubbles: true }));
		});

		uvcolorfield.addEventListener("change", function () {
			//an empty field means "no custom color", so let it through untouched
			if (!uvcolorfield.value.trim())
				return;

			const uvcolorhex = uvsNormalizeHex(uvcolorfield.value);

			//typed value is not a hex color: fall back to the last valid one
			if (!uvcolorhex) {
				uvcolorfield.value = uvcolorswatch.value;
				return;
			}

			uvcolorfield.value = uvcolorhex;
			uvcolorswatch.value = uvcolorhex;

			uvsColorFieldChange.call(uvcolorfield, uvcolorhex);
		});
	});
}

//accepts "#abc", "abc", "#aabbcc" or "aabbcc" and returns "#aabbcc"
function uvsNormalizeHex(uvcolorvalue) {
	if (typeof uvcolorvalue != "string")
		return "";

	let uvcolorhex = uvcolorvalue.trim().replace("#", "");

	if (/^[0-9a-fA-F]{3}$/.test(uvcolorhex))
		uvcolorhex = uvcolorhex[0] + uvcolorhex[0] + uvcolorhex[1] + uvcolorhex[1] + uvcolorhex[2] + uvcolorhex[2];

	if (!/^[0-9a-fA-F]{6}$/.test(uvcolorhex))
		return "";

	return "#" + uvcolorhex.toLowerCase();
}

function uvsColorFieldChange(uvselcolor) {
	if (this.classList.contains("uvsjs-choosecolor")) {
		if (uvs_popup) uvs_popup.classList.add("uvs-pop-ui");

		const uvthemesellook = this.closest(".uvs-infolist-item").previousElementSibling.querySelector("select.uvsjson");
		const uvthemesel = (uvthemesellook) ? uvthemesellook.value : this.closest("#uvs-admin-ui-color-palette").querySelector(".uvsjson").value;
		const uvthemeloader = this.closest(".uvs-infolist-item").querySelector(".uv-loader-uvicon");
		const uvrecom = "We highly recommend to update the <b>Theme UI</b> color to match the selected Accent Color.";

		uvthemeloader.classList.add("active");

		if (uvthemesel == "dark") {
			if (uvsGetContrast(uvselcolor, "#111111") < 4.5) {
				setTimeout(() => {
					uvsDisplayMsg(uvrecom, "Recommendation", "OK", 400);
				}, 1200);
			}

			setTimeout(() => {
				uvthemeloader.classList.remove("active");
			}, 1200);
		} else {
			if (uvsGetContrast(uvselcolor, "#ffffff") < 4.5) {
				setTimeout(() => {
					uvsDisplayMsg(uvrecom, "Recommendation", "OK", 400);
				}, 1200);
			}

			setTimeout(() => {
				uvthemeloader.classList.remove("active");
			}, 1200);
		}
	}

	setTimeout(() => {
		uvsHidePopup(uvs_popup);
	}, 10000);
}
/***************/

/*** Before Leave ***/
window.onbeforeunload = function () {
	if (uvs_pendchanges) {
		return "Changes you made may not be saved";
	} else {
		return;
	}
};

function uvsQueryAll(uvselector, uvcontext) {
	if (!uvselector)
		return [];

	return Array.prototype.slice.call((uvcontext || document).querySelectorAll(uvselector));
}

//serializes a set of fields into an application/x-www-form-urlencoded string
function uvsSerializeFields(uvfieldelems) {
	const uvfieldparams = new URLSearchParams();

	Array.prototype.forEach.call(uvfieldelems, function (el) {
		if (!el.name || el.disabled)
			return;

		if (el.type == "checkbox" || el.type == "radio") {
			if (el.checked)
				uvfieldparams.append(el.name, el.value);
		}
		else if (el.tagName == "SELECT" && el.multiple) {
			Array.prototype.forEach.call(el.selectedOptions, function (uvoption) {
				uvfieldparams.append(el.name, uvoption.value);
			});
		}
		else
			uvfieldparams.append(el.name, el.value);
	});

	return uvfieldparams.toString();
}

function uvsClickListener(uvselector, uvhandler) {
	document.addEventListener("click", function (e) {
		for (var target = e.target; target && target != this; target = target.parentNode) {
			if (target.matches && target.matches(uvselector)) {
				uvhandler.call(target, e);
				break;
			}
		}
	}, false);
}

function uvsChangeListener(uvselector, uvhandler) {
	document.addEventListener("change", function (e) {
		for (var target = e.target; target && target != this; target = target.parentNode) {
			if (target.matches && target.matches(uvselector)) {
				uvhandler.call(target, e);
				break;
			}
		}
	}, false);
}

// Get contrast color (WCAG 2.0)
function uvsGetContrast(uvcolor1, uvcolor2) {
	var uvr1 = parseInt(uvcolor1.substr(1, 2), 16);
	var uvg1 = parseInt(uvcolor1.substr(3, 2), 16);
	var uvb1 = parseInt(uvcolor1.substr(5, 2), 16);

	var uvr2 = parseInt(uvcolor2.substr(1, 2), 16);
	var uvg2 = parseInt(uvcolor2.substr(3, 2), 16);
	var uvb2 = parseInt(uvcolor2.substr(5, 2), 16);

	var uvlum1 = 0.2126 * Math.pow(uvr1 / 255, 2.2) + 0.7152 * Math.pow(uvg1 / 255, 2.2) + 0.0722 * Math.pow(uvb1 / 255, 2.2);
	var uvlum2 = 0.2126 * Math.pow(uvr2 / 255, 2.2) + 0.7152 * Math.pow(uvg2 / 255, 2.2) + 0.0722 * Math.pow(uvb2 / 255, 2.2);

	if (uvlum1 > uvlum2) {
		return (uvlum1 + 0.05) / (uvlum2 + 0.05);
	} else {
		return (uvlum2 + 0.05) / (uvlum1 + 0.05);
	}
}

//kept at the end of the file so every delegated listener above is already registered
if (document.readyState != "loading")
	uvsAdminInit();
else
	document.addEventListener("DOMContentLoaded", uvsAdminInit);
