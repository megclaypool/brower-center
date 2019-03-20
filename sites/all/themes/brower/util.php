<?php
// function first_paragraph($text)
// {
//   $end_pos = strpos($text, '</p>');
//   if ($end_pos === FALSE)
//   {
//     return array('', 0);
//   } 
//   $start_pos = strpos($text, '<p');
//   if ($start_pos === FALSE)
//   {
//     return array('', 0);
//   } 
//   $paragraph = substr($text, $start_pos + 2, $end_pos - $start_pos - 2);
//   $start_pos = strpos($paragraph, '>');
//   $paragraph = substr($paragraph, $start_pos + 1);
//   return array($paragraph, $end_pos + 3);
// }
// 
// function first_sentence($text)
// {  
// #  $blank_line_pos = strpos($text, "<p>&nbsp;</p>");
// # if ($blank_line_pos === 0)
// #  {
// #    $text = substr($text, 13);
// #  }
//   $result = first_paragraph($text);
//   $paragraph = $result[0];
//   $p_end_pos = $result[1];
//   if ($p_end_pos == 0)
//   {
//     return '';
//   }
//   $next_paragraph = FALSE;
//   $test_pos = strstr($paragraph, "Click here");
//   if ($test_pos != FALSE)
//   {
//     $next_paragraph = TRUE;
//   }
//   $end_pos = strpos($paragraph, '.');
//   if ($end_pos === FALSE)
//   {
//     $next_paragraph = TRUE;
//   }
//   if ($next_paragraph)
//   {
//     $result = first_paragraph(substr($text, $p_end_pos + 1));
//     $paragraph = $result[0];
//     $p_end_pos = $result[1];
//   }
//   $end_pos = strpos($paragraph, '.');
//   if ($end_pos === FALSE)
//   {
//     return '';
//   }
//   $sentence = substr($paragraph, 0, $end_pos + 1);
//   return $sentence;
// }

function bp_base_url_process($text) {
	global $bp_base_url;
	$bp_base_url = 'http://d5.openwebgroup.ca';
	
	return str_replace(
		array(
			'href="/',
			'src="/'
		),
		array(
			'href="' . $bp_base_url . '/',
			'src="' . $bp_base_url . '/'
		),
		$text);
}

function bp_current_section( $primary_links)
{
	foreach ($primary_links as $menu_id => $menu_item)
	{
		if (substr($menu_id, -6) == 'active') {
			return str_replace(' ', '_', strtolower($menu_item['title']));
		}
	}
	return false;
}
?>
