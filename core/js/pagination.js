/*******************************
* function to switch pages of multiple different paginator types (see /classes/pagination.php
*
* @id:String - unique id of pagination block (incase 2+ on same page)
* @settings:Object - additional settings
*******************************/
var pagination_current_page = 0;
function pagination (id, settings, e) {
	"use strict";

	var loc = window.location.href;
	var path = loc.split('&')[0].split('?')[0];
	var query = "";
	if (loc.indexOf('?')!==-1) {
		query = loc.split('?')[1];
	} else if (loc.indexOf('&')!==-1) {
		query = loc.split('&')[1];
	}
	if (query!=="") {
		var split_query = query.split('&');
		for (var i=0; i<split_query.length; i++) {
			if (split_query[i].indexOf('date=')===0 || split_query[i].indexOf('p=')===0) {
				delete split_query[i];
			}
		}
		query = split_query.join('&').replace('&&', '&');
	}
	
	switch (settings.type) {
		case 'date':
			$('.paginator_year').css('display','none'); 
			$('.paginator_month').css('display','none'); 
			
			$('#'+id+'_'+settings.year).css('display', 'block');
			if (settings.month!==null) {
				$('#'+id+'_'+settings.year+settings.month).css('display', 'block');
				pathChange(path+'?date='+settings.year+'_'+settings.month+"&"+query);
			} else {
				$('#'+id+'_'+settings.year+' .paginator_month').css('display', 'block');
				//pathChange(path+'?date='+settings.year+"&"+query);
			}
			
			break;
		default:
		
			if (settings.letter) {
				$('.'+id+'_pages').css('display','none'); 
				$('#page_'+settings.letter+'_'+id).css('display', 'block');
				//pathChange(path+'?p='+settings.letter+"&"+query);
				$('.pagination_link').removeClass('active');
				$(e.target).addClass('active');

				
			} else if (settings.direction) {

				if (settings.direction==='prev' && pagination_current_page>0) {
					$('.'+id+'_pages').css('display','none'); 
					pagination_current_page--;
					$('#page_'+pagination_current_page+'_'+id).css('display', 'block');
					
				} else if (settings.direction==='next' && document.getElementById('page_'+(pagination_current_page+1)+'_'+id)) {
					$('.'+id+'_pages').css('display','none'); 
					pagination_current_page++;
					$('#page_'+pagination_current_page+'_'+id).css('display', 'block');
				}
			}
			break;
	}
	
	$('html, body').animate({scrollTop: $('#'+id).offset().top-200}, 1000);
	
	
	
	// DODGY FIX - hide subnav on click 
	$('nav li ul').css('display','none');
	setTimeout(function() {
		$('nav li ul').css('display','');
	}, 500);
}


/******************
* switch between pages on a paginated table made from createDateTables of the Tabulate class
*
* @id:Int - page number OR :String 'all' to show all
********************/
function paginate(id, type) {
	if (id!='all') {
		$('.tablebody').fadeOut(function() {
			$('#page'+id).fadeIn();
			$('html, body').animate({scrollTop: $(".pagination").offset().top}, 1000);
		});
	} else {
		$('.tablebody').show();	
	}
	if (type!=null) {
		$('#main').html('<div class="loading"><img src="'+BASE+'/core/images/loading.gif" /></div>');
		$.ajax({
			type:'GET',
			url:DIR+'/modules/'+type+'.php',
			data:{
				p:id,
				paginate:true
			},
			success:function(response) {
				$('#main').html(response);
			}
		});
	}
	pathChange(window.location.href.split('&')[0].split('?')[0]+'?p='+id);
}