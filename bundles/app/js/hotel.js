var searchbox_options = {
	refid:6665,
	environment: "prod",
	hotel:{
		enabled:false
	},
	car:{
		enabled:false	
	},
	air: {
		enabled:true,
		elements: {
			form: 'air',
			round_trip: '#air_round_trip',
			one_way: '#air_one_way',
			multi_dest: '#air_multi_dest',	
			adults : '.rs_adults_input',
			children : '.rs_child_input',			
			chk_in: '.rs_chk_in, .rs_mobi_in',
			chk_out: '.rs_chk_out, .rs_mobi_out',
			chk_in_display: '.rs_mobiin',
			chk_in1_display: '.rs_mobi1',
			chk_in2_display: '.rs_mobi2',
			chk_in3_display: '.rs_mobi3',
			chk_in4_display: '.rs_mobi4',
			chk_in5_display: '.rs_mobi5',
			chk_out_display: '.rs_mobiout',
			search:".rs_search"	
		},
		calendar: {
			output_format: '<div class="rs_mobi_chk_day">[d]</div><div class="rs_mobi_chk_month">[F]</div>'
		},
		autosuggest: {
			airports: true
		},
		select_name:true
	},
	vp:{
		active:true,
		enabled:false
	}
};
$(document).ready(function() {
	$('#rs_multi').searchbox(searchbox_options);
	
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		$("<link/>", {
		   rel: "stylesheet",
		   type: "text/css",
		   href: "css/mobile_search.css"
		}).appendTo("head");	
	}

	$('.round-trip').click(function(){
		$('#air_round_trip').show();
		$('#air_one_way').hide();
		$('#air_multi_dest').hide();
	});

	$('.one-way').click(function(){
		$('#air_round_trip').hide();
		$('#air_one_way').show();
		$('#air_multi_dest').hide();
	});

	$('.multi-city').click(function(){
		$('#air_round_trip').hide();
		$('#air_one_way').hide();
		$('#air_multi_dest').show();
		// window.location = 'http://secure.rezserver.com/air/home?refid=6665'
		// return false;
	});	
	var $icons = $('.rs_tabs');
    	$icons.click(function(){
       $icons.removeClass('highlight_tab');
       $(this).addClass('highlight_tab');
    });
});

function showMulti(num){
	var next= num+1;
	$('.rem_flight'+num).hide();
	$('.add_flight'+num).hide();
	$('.air_flight_'+next).slideDown();
}

function hideMulti(num){
	var prev= num-1;
	$('.air_flight_'+num).slideUp();
	$('.rem_flight'+prev).show();
	$('.add_flight'+prev).show();		
}