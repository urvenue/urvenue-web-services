/**
	@module: system/scripts
	@author: UrVenue - aa
	@version: 1.0
*/

var uvsetupsubmit = false;

function uvsSetupInit() {
	if ((typeof (uvcoreinput) != "undefined") && (uvcoreinput.length > 0)) {
		let uvsproxysuccess;

		const uvrequest = new XMLHttpRequest();
		uvrequest.open("GET", uvcoreinput + '/uvcore.proxy.php?uvaction=uvsp_checkproxyurl', true);

		uvrequest.onload = function () {
			if (this.status >= 200 && this.status < 400)
				uvsproxysuccess = (this.response == 'uv1');
			else
				uvsproxysuccess = false;

			uvsSetupProxyResult(uvsproxysuccess);
		};

		uvrequest.onerror = function () {
			uvsSetupProxyResult(false);
		};

		uvrequest.send();
	}
}

function uvsSetupProxyResult(uvsproxysuccess) {
	const uvsurlfield = document.querySelector("#url");
	const uvsurlcont = (uvsurlfield) ? uvsurlfield.closest(".uvs-setupfield") : null;

	if (uvsproxysuccess) {
		if (uvsurlcont) uvsurlcont.classList.add("uvs-setupfield-ok");

		const uvsmanualbtns = document.querySelectorAll(".uvs-btn-setup-manually");
		Array.prototype.forEach.call(uvsmanualbtns, function (el) {
			el.style.display = "";
		});

		if (uvsetupsubmit) {
			document.querySelector("#uvs-input-write").value = "1";
			document.querySelector("#uvs-form-setup").submit();
		}
	}
	else {
		if (uvsurlcont) uvsurlcont.classList.add("uvs-setupfield-nok");

		const uvssetuperrors = document.querySelector(".uvs-setup-errors");
		if (uvssetuperrors)
			uvssetuperrors.insertAdjacentHTML("beforeend", "<div class='uvs-setup-error'><strong>UvCore URL</strong> It was not possible to access the uvcore file, plesae verify the URL.</div>");
	}
}

uvsClickListener(".uvsjs-btn-setup-manually", function () {
	document.querySelector("#uvs-input-manuallib").value = 1;
	uvs_popup.classList.add("uvs-jsonlibpop");

	uvsDisplayMsg("<textarea class='uvs-libjson' rows='5'>" + uvcorejsonlib + "</textarea><div class='uvs-text-right uvs-mt20'><button class='uvsjs-copycliptoclip uvs-btn uvs-btn-p' data-target='.uvs-libjson'>Copy</button></div>", "White File Manually", "hidden", 600);
});

//kept at the end of the file so the listener above is already registered
if (document.readyState != "loading")
	uvsSetupInit();
else
	document.addEventListener("DOMContentLoaded", uvsSetupInit);
