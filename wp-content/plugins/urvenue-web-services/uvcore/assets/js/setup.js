/**
	@module: system/scripts
	@author: UrVenue - aa
	@version: 1.0
*/

var uvsetupsubmit = false;

jQuery(document).ready(function(){
	if((typeof(uvcoreinput) != "undefined") && (uvcoreinput.length > 0)){
		var uvsproxysuccess;
			
		jQuery.get(uvcoreinput + '/uvcore.proxy.php?uvaction=uvsp_checkproxyurl', function(data){
			if(data == 'uv1')
				uvsproxysuccess = true;
			else
				uvsproxysuccess = false;
		}).fail(function(){
			uvsproxysuccess = false;
		}).always(function(){
			if(uvsproxysuccess){
				jQuery('#url').closest('.uvs-setupfield').addClass('uvs-setupfield-ok');
				jQuery(".uvs-btn-setup-manually").show();
				
				if(uvsetupsubmit){
					console.log("hola");

					jQuery("#uvs-input-write").val("1");
					jQuery("#uvs-form-setup").submit();
				}
			}
			else{
				jQuery('#url').closest('.uvs-setupfield').addClass('uvs-setupfield-nok');
				jQuery(".uvs-setup-errors").append("<div class='uvs-setup-error'><strong>UvCore URL</strong> It was not possible to access the uvcore file, plesae verify the URL.</div>");
			}
		});
	}
});

jQuery(document).on("click", ".uvsjs-btn-setup-manually", function(){
	jQuery("#uvs-input-manuallib").val(1);
	uvs_popup.addClass("uvs-jsonlibpop");
	
	uvsDisplayMsg("<textarea class='uvs-libjson' rows='5'>" + uvcorejsonlib + "</textarea><div class='uvs-text-right uvs-mt20'><button class='uvsjs-copycliptoclip uvs-btn uvs-btn-p' data-target='.uvs-libjson'>Copy</button></div>", "White File Manually", "hidden", 600);
});