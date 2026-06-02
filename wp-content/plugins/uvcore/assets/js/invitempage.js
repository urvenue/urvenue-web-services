uwsDOMReady(function(){
    if(document.querySelector(".uwsitemdp")){
        const uvitemdpelem = document.querySelector(".uwsitemdp");

        const uvmindate = uvitemdpelem.getAttribute("data-date");
        const uvmaxdate = uvitemdpelem.getAttribute("data-maxdate");

        new Litepicker({
            element: uvitemdpelem,
            minDate: uvmindate,
            maxDate: uvmaxdate,
            inlineMode: 1,
            singleMode: 1,
            showTooltip: 0,
            firstDay: 0,
            startDate: uvmindate,
            setup: function(n) {
                n.on("selected", function(n, t){
                    const uvseldate = n.format('YYYY-MM-DD');
                    console.log(uvseldate);
                })
            }
        });
    }
});