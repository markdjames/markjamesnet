var search = {
	init: function() {		
		"use strict";
		$.widget( "custom.catcomplete", $.ui.autocomplete, {
			_renderMenu: function( ul, items ) {
				var that = this,
				currentCategory = "";
				$.each( items, function( index, item ) {
					if (item.category !== currentCategory) {
						ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
						currentCategory = item.category;
					}
					that._renderItemData( ul, item );
				});
			}
		});
		search.setAutoComplete('search');
	
		$('#search').keyup(function (e) {
			console.log(e);
			if (e.keyCode===13) {
				search.doSearch($(this).val());
			}
		});
	},
	
	setAutoComplete: function (target, callback) {
		"use strict";
		
		if (callback===null) {
			callback = function(event, ui) {
				search.doSearch(ui.item.label);
			};
		}
		
		$(function() {
			$( "#"+target).catcomplete({
				source: function(request, response) { 
							$.ajax({
								type: "GET",
								url: BASE+'/core/lib/ajax/search_suggestions.php',
								dataType: 'json',
								data: {
									q: request.term,
									format: 'json'
								},
								success: function (data) {
									if (data!=='') {
										response($.map(data, function (item) {
											return {
												label: item.title,
												category: item.category,
												value: item.title,
												id: item.id
											};
										}));
									}
								}
							}); 
						},
				minLength: 2,
				select: callback
			});
		});
	},
		
	doSearch: function (q, cat) {
		"use strict";
		$('#search').blur();
		
		/**
		 * Break query in to array and get longest two words
		 */
		var arr = q.split(" ");
		var sorted = arr.sort(function (a, b) { return b.length - a.length; });
		
		$('#search_tags').html('');
		
		for (var i=0; i<sorted.length; i++) {
			if (i===2) { break; }
			$('#search_tags').append("<input type='button' onclick='search.doSearch(\""+sorted[i]+"\")' value=\""+sorted[i]+"\">");
		}
		
		if (cat===null) {
			cat = '';
		}
		
		$.ajax({
			type:'GET',
			url:BASE+'/core/lib/search.php',
			data:{
				q:q,
				category:cat
			},
			beforeSend:function() {
				$('#search_results').html('searching...');
			},
			success:function(data) {
				$('#search_results').html(data);

				var query = 'q='+q;
				query += (cat!=='' && cat!='undefined') ? '&cat='+cat : '';
				pathChange(BASE+'/search?'+query);
			}
		});
	}
}

$(document).ready(function(){	
	search.init();
});

