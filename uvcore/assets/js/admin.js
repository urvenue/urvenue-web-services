var uvs_popup;
var uvs_pendchanges = false;
var uvs_admboxactmsgst = 0;

jQuery(document).ready(function () {
	jQuery("body").append("<div id='uvs-pop-up' class='uvs-pop-cont'><div class='uvs-pop-box'><a class='uvs-closepop uvsjs-closepop' href='javascript:;'><span class='uvs-hide'>Close</span><i class='uv-icon-cancel'></i></a><div class='uvs-pop-charge'></div></div></div>");

	uvs_popup = jQuery("#uvs-pop-up");

	if (jQuery("#uvs-uvcoreadmin-form").length) {
		jQuery("#uvs-uvcoreadmin-form").validate({
			submitHandler: function (form) {
				let uvvenuearevalid = 1;
				const uvnewvenueselems = document.querySelectorAll(".uvs-admin-venueinf-vc-new");
				Array.prototype.forEach.call(uvnewvenueselems, function (el, i) {
					if (!el.querySelector(".uvsjs-spread-venuekey").value)
						uvvenuearevalid = 0;
				});


				if (uvvenuearevalid) {
					var uvsadminformobj = jQuery(form);
					var uvsadmininfo = uvsadminformobj.find(".uvsjson").serialize();
					var uvsadminaction = uvsadminformobj.attr("action");

					uvsadminformobj.find(".uvs-adminbox-actions .uvs-adminbox-actions-btnset:last-child").addClass("active");
					uvsadminformobj.find(".uvs-adminbox-actions .uvs-adminbox-actions-btnset:last-child .uvs-btn").attr("disabled", true);

					jQuery.ajax({
						url: uvsadminaction,
						data: uvsadmininfo,
						type: "POST",
					})
						.done(function (uvsresponse) {
							if (uvsresponse == "saved") {
								setTimeout(function () {
									uvsadminformobj.find(".uvs-adminbox-actions .uvs-adminbox-actions-btnset:last-child").removeClass("active");
									uvsadminformobj.find(".uvs-adminbox-actions .uvs-adminbox-actions-btnset:last-child .uvs-btn").attr("disabled", false);

									uvAdminBoxActionMessage(uvsadminformobj, "Changes Saved");
									uvs_pendchanges = 0;
								}, 1500);

								if (uvnewvenueselems.length) {
									uvs_pendchanges = 0;
									location.reload();
								}
							}
						});
				}
				else {
					alert("Venue key is required");
				}
			}
		});
	}

	if (document.querySelector(".uvsjs-datepicker"))
		uvsInitDatepicker();

	//init colors
	jQuery('.uvs-color-field').wpColorPicker({
		change: function (event, ui) {
			if (this.classList.contains("uvsjs-choosecolor")) {
				if (uvs_popup && uvs_popup[0]) uvs_popup[0].classList.add("uvs-pop-ui");

				const uvselcolor = ui.color.toString();

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
		},
	});

	//go to anchor tab
	if (window.location.hash) {
		jQuery(".uvs-adminbox-mainmenu a[href='" + window.location.hash + "']").click();
	}
});

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

				if (uvs_popup && uvs_popup[0]) uvs_popup[0].classList.add("uvs-pop-cache");

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

jQuery(document).on("change", ".uvsjs-copytoinput", function () {
	if (jQuery(this).data("target") != undefined) {
		var uvsnewinput = jQuery(this).val() + jQuery(this).data("addafter");

		jQuery(jQuery(this).data("target")).val(uvsnewinput);
	}
});
jQuery(document).on("click", ".uvsjs-copycliptoclip", function () {
	var uvscliptarget = jQuery(this).data("target");

	jQuery(uvscliptarget).focus();
	jQuery(uvscliptarget).select();

	var uvscopyclip = document.execCommand('copy');
	if (uvscopyclip) {
		jQuery(this).html("Copied");
		jQuery(this).attr("disabled", "disabled");
	}
	else {
		alert("Sorry, I can't copy it. Copy manually");
		jQuery(this).parent().hide();
	}
});
jQuery(document).on("click", ".uvs-adminbox-mainmenu li a", function (e) {
	//e.preventDefault();

	jQuery(".uvs-adminbox-mainmenu li a").removeClass("active");
	jQuery(this).addClass("active");

	var uvstarget = jQuery(this).attr("href");
	uvstarget = uvstarget.replace("#", "");

	jQuery(".uvs-admin-opt-section").removeClass("active");
	jQuery("#uvs-admin-" + uvstarget).addClass("active");
});
jQuery(document).on("click", ".uvsjs-checkvenueid", function () {
	var uvsloadtarget = jQuery(this).data("loadertarget");
	var uvscheckurl = jQuery(this).data("checkurl");
	var uvsinputveaid = jQuery("#veaid").val();

	if (jQuery(".uvs-admin-venueinf-vc-" + uvsinputveaid).length > 0) {
		jQuery(".uvs-admin-venuesmsg").html("");
		jQuery(".uvs-admin-venuesmsg").append("<div class='uvs-admin-errormsg'>This Venue is already added</div>");
	}
	else if (uvsinputveaid.length > 3 && /^\d+$/.test(uvsinputveaid)) {
		jQuery(uvsloadtarget).addClass("active");
		jQuery("#veaid").removeClass("uvs-error");

		var uvsnvenues = jQuery("#uvs-admin-venuesinfo .uvs-admin-venueinf").length;

		jQuery.get(uvscheckurl, {
			uvsve: uvsinputveaid,
			uvsnv: uvsnvenues,
		})
			.done(function (uvresponse) {
				jQuery(uvsloadtarget).removeClass("active");

				if (uvresponse.includes("uvs-admin-venueinf")) {
					jQuery("#uvs-admin-venuesinfo").append(uvresponse);
					jQuery(".uvs-admin-venuesmsg").find(".uvs-admin-errormsg").remove();
					jQuery("#veaid").val("");
				}
				else if (uvresponse.includes("uvs-admin-errormsg")) {
					jQuery(".uvs-admin-venuesmsg").html("");
					jQuery(".uvs-admin-venuesmsg").append(uvresponse);
				}
			});
	}
	else
		jQuery("#veaid").addClass("uvs-error");
});
jQuery(document).on("click", ".uvsjs-removevenue", function () {
	jQuery(this).closest(".uvs-admin-venueinf").remove();
	uvs_pendchanges = true;
});
jQuery(document).on("click", ".uvsjs-addflyerset", function () {
	var uvsflyersettarget = jQuery(this).data("target");
	var uvsflyersetlastkey = jQuery(uvsflyersettarget).find(".uvs-infolist-groupnoti:last-child").data("nflyerset");

	var uvsflyersetnewkey = (uvsflyersetlastkey / 1) + 1;

	var uvsflyertypeshtml = jQuery(uvsflyersettarget).find(".uvs-infolist-groupnoti:first-child select.uvsflyertype").html();
	var uvsflyerratiohtml = jQuery(uvsflyersettarget).find(".uvs-infolist-groupnoti:first-child select.uvsflyerratio").html();

	var uvsflyerloc = jQuery(uvsflyersettarget).find(".uvs-infolist-groupnoti").data("flyerloc");

	uvsflyertypeshtml = uvsflyertypeshtml.replace("selected", "");
	uvsflyerratiohtml = uvsflyerratiohtml.replace("selected", "");

	var newflyerelement = "<div class='uvs-infolist-groupnoti' data-nflyerset='" + uvsflyersetnewkey + "' data-flyerloc='" + uvsflyerloc + "'><div class='uvs-infolist-item'><div class='uvsname'>Flyer Type:</div><div class='uvsvalue'><select class='uvsjson uvsflyertype' name='flyers[" + uvsflyerloc + "][" + uvsflyersetnewkey + "][type]'>" + uvsflyertypeshtml + "</select></div></div><div class='uvs-infolist-item'><div class='uvsname'>Flyer Ratio:</div><div class='uvsvalue'><select class='uvsjson uvsflyerration' name='flyers[" + uvsflyerloc + "][" + uvsflyersetnewkey + "][ratio]'>" + uvsflyerratiohtml + "</select></div></div><div class='actions'><a class='uvsjs-removeflyer' href='javascript:;'>Remove</a></div></div>";

	jQuery(uvsflyersettarget).append(newflyerelement);

	uvs_pendchanges = true;
});

jQuery(document).on("click", ".uvsjs-addnewvenue", function () {
	var uvsnewvenuetarget = jQuery(this).data("target");

	const uvsnewvenuehtml = `<div class="uvs-admin-venueinf uvs-admin-venueinf-vc-new"><input class="uvsjson venueprimary" type="hidden" name="" data-inputname="venues[{venuekey}][isprimary]" value=""><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Venue KEY:</div><div class="uvsvalue"><input class="uvsjson uvsjs-spread-venuekey" type="text" name="" value="" data-inputname="venues[{venuekey}][venuekey]"></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Venue Logo:</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][logourl]" value=""></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Venue Name:</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][venuename]" value=""></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Venue Name Alias:</div><div class="uvsvalue"><input type="text" name="" data-inputname="venues[{venuekey}][venuealias]" value="" class="uvsjson"></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Use Alias as Venue Name:</div><div class="uvsvalue"><div class="uvs-switch-ui "><button class="uvsjs-trigger-switch" type="button"><span class="uvs-lb-on">Yes</span><span class="uvs-lb-off">No</span></button><input class="uvsjson" type="hidden" name="" data-inputname="venues[{venuekey}][venueforcealias]" value="" data-value-on="1" data-value-off=""></div></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Hide Events:</div><div class="uvsvalue"><div class="uvs-switch-ui "><button class="uvsjs-trigger-switch" type="button"><span class="uvs-lb-on">Yes</span><span class="uvs-lb-off">No</span></button><input class="uvsjson" type="hidden" name="" data-inputname="venues[{venuekey}][venuehideinevents]" value="" data-value-on="1" data-value-off=""></div></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Venue Code (VENXXXX):</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][venuecode]" value=""></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Manageentid:</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][manageentid]" value=""></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Providerid:</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][providerid]" value=""></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Resellerid:</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][resellerid]" value=""></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Venue ID:</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][urvenueid]" value=""></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">Client ID:</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][clientid]" value=""></div></div><div class="uvs-infolist-item uvs-clearfix"><div class="uvsname">UrVenue Server:</div><div class="uvsvalue"><input class="uvsjson" type="text" name="" data-inputname="venues[{venuekey}][uvserver]" value=""></div></div><div class="actions"><a class="uvsjs-removevenue" href="javascript:;">Remove</a></div></div>`;

	jQuery(uvsnewvenuetarget).append(uvsnewvenuehtml);

	uvs_pendchanges = true;
});

jQuery(document).on("change", ".uvsjs-spread-venuekey", function () {
	const uvvenuekey = this.value;
	const uvjsoninputelems = this.closest(".uvs-admin-venueinf").querySelectorAll(".uvsjson");

	Array.prototype.forEach.call(uvjsoninputelems, function (el, i) {
		let uvinputname = el.getAttribute("data-inputname");
		uvinputname = uvinputname.replace("{venuekey}", uvvenuekey);
		el.setAttribute("name", uvinputname);
	});
});

jQuery(document).on("change", ".uvsjson", function () {
	uvs_pendchanges = true;
});
jQuery(document).on("click", ".uvsjs-removeflyer", function () {
	jQuery(this).closest(".uvs-infolist-groupnoti").remove();
	uvs_pendchanges = true;
});
jQuery(document).on("click", ".uvsjs-triggervenueprimary", function () {
	if (!jQuery(this).hasClass("active")) {
		jQuery("#uvs-admin-venuesinfo .uvs-admin-venueinf").each(function () {
			var uvsvenueitemtarget = jQuery(this);

			uvsvenueitemtarget.find(".uvsjs-triggervenueprimary").removeClass("active").html("Make Primary");
			uvsvenueitemtarget.find("input.venueprimary").val(0);
		});

		jQuery(this).closest(".uvs-admin-venueinf").find(".uvsjs-triggervenueprimary").addClass("active").html("Is Primary");
		jQuery(this).closest(".uvs-admin-venueinf").find("input.venueprimary").val(1);

		uvs_pendchanges = true;
	}
});
jQuery(document).on("click", ".uvs-switch-ui", function () {
	if (jQuery(this).hasClass("uvs-on")) {
		jQuery(this).removeClass("uvs-on");
		var uvsinputnewval = jQuery(this).closest(".uvs-switch-ui").find("input").data("value-off");
		jQuery(this).closest(".uvs-switch-ui").find("input").val(uvsinputnewval).change();
	}
	else {
		jQuery(this).addClass("uvs-on");
		var uvsinputnewval = jQuery(this).closest(".uvs-switch-ui").find("input").data("value-on");
		jQuery(this).closest(".uvs-switch-ui").find("input").val(uvsinputnewval).change();
	}
});
jQuery(document).on("change", ".uvsjs-controlfieldview", function () {
	var uvscvtarget = jQuery(this).data("target");
	var uvscvshowon = jQuery(this).data("showon");
	var uvscvhideon = jQuery(this).data("hideon");

	if (jQuery(this).val() == uvscvshowon)
		jQuery(uvscvtarget).css("display", "flex");
	else if (jQuery(this).val() == uvscvhideon)
		jQuery(uvscvtarget).hide();
});
jQuery(document).on("click", ".uvsjs-gotoadminoptpage", function () {
	var uvsgototarget = jQuery(this).data("target");

	jQuery(".uvs-adminbox-mainmenu a[href='" + uvsgototarget + "']").click();
});
jQuery(document).on("click", ".uvs-admin-iconboxlist a", function (e) {
	//e.preventDefault();

	var uvsgototarget = jQuery(this).attr("href");
	jQuery(".uvs-adminbox-mainmenu a[href='" + uvsgototarget + "']").click();
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
	uvsadminformobj.find(".uvs-adminbox-actions .uvs-adminbox-actions-status").html(uvsactionsmessage).addClass("active");

	uvs_admboxactmsgst = setTimeout(function () {
		uvsadminformobj.find(".uvs-adminbox-actions .uvs-adminbox-actions-status").removeClass("active");
	}, 4000);
}



/*POPUPS ACTIONS*/
jQuery(document).on("click", ".uvs-pop-cont", function () {
	uvsHidePopup(jQuery(this));
});
jQuery(document).on("click", ".uvsjs-closepop", function () {
	uvsHidePopup(jQuery(this).closest(".uvs-pop-cont"), true);
});
/***************/

/*POPUPS FUNCTIONS*/
function uvsClearPopup(uvspoptarget, uvspopcontent) {
	uvspopcontent = (uvspopcontent != undefined) ? uvspopcontent : "";
	uvspoptarget.find(".uvs-pop-charge").html(uvspopcontent);
}
function uvsExpandPopup(uvspoptarget, uvspopexpand) {
	uvspoptarget.find(".uvs-pop-box").css("max-width", uvspopexpand);
}
function uvsFadePopup(uvspoptarget) {
	jQuery("html").addClass("uvs-pop-open");
	uvspoptarget.addClass("visible");
}
function uvsHidePopup(uvspoptarget, uvspopforceclose) {
	uvspopforceclose = (uvspopforceclose != undefined) ? uvspopforceclose : false;

	if ((uvspopforceclose) || ((uvspoptarget != undefined) && (uvspoptarget.find(".uvs-pop-box").length > 0) && (uvspoptarget.find(".uvs-pop-box:hover").length < 1))) {
		if (uvspoptarget.hasClass("clearonclose"))
			uvsClearPopup(uvspoptarget);

		uvspoptarget.attr("class", "uvs-pop-cont");
		jQuery("html").removeClass("uvs-pop-open");

		setTimeout(function () {
			uvspoptarget.find(".uvs-pop-box").css("max-width", "");
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

/*** Before Leave ***/
window.onbeforeunload = function () {
	if (uvs_pendchanges) {
		return "Changes you made may not be saved";
	} else {
		return;
	}
};

function uvsClickListener(uvselector, uvhandler) {
	document.addEventListener("click", function (e) {
		for (var target = e.target; target && target != this; target = target.parentNode) {
			if (target.matches(uvselector)) {
				uvhandler.call(target, e);
				break;
			}
		}
	}, false);
}

function uvsChangeListener(uvselector, uvhandler) {
	document.addEventListener("change", function (e) {
		for (var target = e.target; target && target != this; target = target.parentNode) {
			if (target.matches(uvselector)) {
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