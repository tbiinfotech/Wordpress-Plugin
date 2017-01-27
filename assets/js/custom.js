 /*
* ASSIGN PACKAGES TO GROUPS
 */	   
jQuery(document).ready(function(){
	jQuery(".group").click(function() {
	var ischecked= jQuery(this).is(':checked');
                if(!ischecked)
                 alert('uncheckd ' + jQuery(this).val());
				jQuery.ajax({
				url: int_script_data.ajax_url,
				method: "POST",
				data: {
					'action':'assign_package',
							 
				},
				success:function(data) { 
			   if(data=="success"){
				jQuery("<h2 style='margin-left:20px;'>Data fetched successfully.</h2>").insertAfter( jQuery( ".syn_div" ) );
				var delay=1000;   setTimeout(function() {
				window.location.reload();
				}, delay); 
   		     }
	      },
        })
 }); 
	
/*
* PACKAGE IMPORT FUNCTION WITH SYNC BUTTON
 */	
jQuery( "body" ).on( "click", ".sync", function() { 
	jQuery(".package_loading").css("display","block");
	jQuery.ajax({
    url: int_script_data.ajax_url,
	method: "POST",
	data: {
		'action':'insert_package',
			 	 
	},
	success:function(data) { 
		jQuery(".package_loading").css("display","none");
		 
		if(data.indexOf("success")!=-1){
			jQuery( ".message" ).remove();
			success_data=data.replace("success", "");
			jQuery("<h2 style='margin-left:20px;' class='message'>Successfully processed "+success_data+" records</h2>").insertAfter( jQuery( ".syn_div" ) );
			var delay=1100;   setTimeout(function() {
			window.location.reload();
			}, delay); 
   		}
		else{
			jQuery( ".message" ).remove();
			jQuery("<h2 style='margin-left:20px;' class='message'>"+data+"</h2>").insertAfter( jQuery( ".syn_div" ) );
		}
	},
}) 
});

/*
* FUNCTION CALL BY CLICKING ON GROUP TAB ON PACKAGE GROUP PAGE
 */	
jQuery( "body" ).on( "click", ".group_count", function() { 
 
    jQuery(".group_count").removeClass("active");
    jQuery(this).addClass("active");
	jQuery(".loader_image").css("display","block");
	var gid=jQuery(this).attr("id");
	jQuery.ajax({
    url: int_script_data.ajax_url,
	method: "POST",
	data: {
		'action':'get_group_packages',
		'gid':gid	 	 
	},
	success:function(data) {  
	jQuery(".loader_image").css("display","none");
	 jQuery(".packages_container").html(data);
	},
});
});
        

	
/*
* PACKAGE IMPORT FUNCTION
 */	
jQuery( "body" ).on( "click", ".package_count .package_assign", function() { 
    var c='';
	if(jQuery(this).prop("checked") == true){
                var c="1";
            }
        else if(jQuery(this).prop("checked") == false){
                var c="0";
    }
	var pid=jQuery(this).attr("id");
	var gid=jQuery(".group_count.active").attr("id");
	jQuery.ajax({
    url: int_script_data.ajax_url,
	method: "POST",
	data: {
		'action':'insert_package_group',
		'c':c,
		'pid':pid,
		'gid':gid	 	 
	},
	success:function(data) { 
	 
	},
});
});

/*
* CONFIRMATION POPUP WHEN CLICK ON DELETE GROUP LINK
 */	
 jQuery(".del_group").easyconfirm({locale: {
	    title: '',
        text: 'Are you sure you want to delete?',
        button: ['NO', 'YES'],
        closeText: ''
	}});
	  
 }); 