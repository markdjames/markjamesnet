<?php
function urlify ($input, $remove_periods=true) {
	
	$output = urlencode(
				preg_replace('/[^a-zA-Z0-9-._]/i', '', 
					str_replace('#', '--', 
						str_replace('&', 'and', 
							str_replace('\'', '', 
								str_replace("__", "_", 
									str_replace(" ", "_", 
										strtolower(
											replace_latin(
												strip_tags($input)
											)
										)
									)
								)
							)
						)
					)
				)
			);
	
	return ($remove_periods==true) ? str_replace('.', '', $output) : $output;
}