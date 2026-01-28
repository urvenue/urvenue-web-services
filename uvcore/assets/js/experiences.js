//Requires: uwscore.js, uwsinventory.js

window.uws_experiences = window.uws_experiences || {};

uwsDOMReady(function(){
    if(document.querySelector(".uwsfilterexpdate")){
        const uvexpcontrolselems = document.querySelectorAll(".uws-experiences-controls");

		Array.prototype.forEach.call(uvexpcontrolselems, function(el, i){
			const uvmindate = el.getAttribute("data-mindate");
			const uvmaxdate = el.getAttribute("data-maxdate");
			const uvdate = el.getAttribute("data-date");

			new Litepicker({
				element: el.querySelector(".uws-dp-experiences-date"),
				minDate: uvmindate,
				maxDate: uvmaxdate,
				inlineMode: 1,
				singleMode: 1,
				showTooltip: 0,
				firstDay: 0,
				startDate: uvdate,
				setup: function(n) {
					n.on("selected", function(n, t){
						const uvseldate = n.format('YYYY-MM-DD');
						const uvddate = uws_fullmonths[n.getMonth()] + " " + n.getDate() + ", " + n.getFullYear();

						this.ui.closest(".uwshasdrop").classList.remove("uwsactive");
						el.setAttribute("data-date", uvseldate);
						el.querySelector(".uwsdy-dropvalue").innerHTML = uvddate;

						if(el.closest(".uws-experiences"))
							el.closest(".uws-experiences").setAttribute("data-date", uvseldate);
						else
							document.querySelector(".uws-experiences").setAttribute("data-date", uvseldate)

						console.log("date selected: " + t);
						uwsExpLoadDate();
					})
				}
			});
		});
    }
});

/*Load date on experiences*/
function uwsExpLoadDate(){
	const uvexpelem = document.querySelector(".uws-experiences");
	uvexpelem.classList.add("uwsloading");

	let uvexperiencesurl = uws_proxy + "&uvaction=uwspx_loadexperiences" + "&date=" + uvexpelem.getAttribute("data-date");

    let uvrequest = new XMLHttpRequest();
    uvrequest.open('GET', uvexperiencesurl, true);
    uvrequest.onload = function(){
        if(this.status >= 200 && this.status < 400){
            let uvresponse = this.response;
            uvresponse = JSON.parse(uvresponse);

			if(typeof(uvresponse.list) != "undefined"){
				uvexpelem.querySelector(".uws-experiences-list").innerHTML = uvresponse.list;

                uvexpelem.classList.remove("uwsloading");
                uvexpelem.classList.add("uwsprepare");
                setTimeout(function(){
                    uvexpelem.classList.add("uwsloaded");
                }, 100);

				if(typeof(uvhookExpDateLoaded) == "function" && typeof(uvresponse) != "undefined")
					uvhookExpDateLoaded(uvresponse);
            }
        } else {
            console.log("UVJS Error: Server returned an error");
        }
    };
    uvrequest.onerror = function(){
        console.log("UVJS Error: Request Error");
    };
    uvrequest.send();
}