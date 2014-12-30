<?php

if ( function_exists( 'add_theme_support' ) ) { 
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( 150, 100, true );
  
  add_image_size( 'featured-post', 636, 300, true ); 
  add_image_size( 'std-post', 150, 100, true );
  add_image_size( 'sidebar', 300, 9999);
  add_image_size( 'frontpage-category', 324, 100, true );
}

add_filter('excerpt_length', 'my_excerpt_length');
function my_excerpt_length($length) {
return 20; }

remove_filter('the_excerpt', 'wpautop');

function get_root_category($tmp)
{
	$tmproot = $tmp[0]->category_parent; 
	while ($tmproot != 0) {
		$tmp = get_category($tmproot);
		$tmproot = $tmp->category_parent;
	}
	return $tmp;
}

function TimeAgo($datefrom,$dateto=-1)
{
	//Zeitzone und Sommerzeit-Sensitivität aktivieren
	date_default_timezone_set('Europe/Berlin'); 
	
	// Defaults and assume if 0 is passed in that
	// its an error rather than the epoch

	if($datefrom<=0) { return "A long time ago"; }
	if($dateto==-1) { $dateto = time(); }

	// Calculate the difference in seconds betweeen
	// the two timestamps

	$difference = $dateto - $datefrom;

	// If difference is less than 60 seconds,
	// seconds is a good interval of choice

	if($difference < 60)
	{
		$interval = "s";
	}

	// If difference is between 60 seconds and
	// 60 minutes, minutes is a good interval
	elseif($difference >= 60 && $difference<60*60)
	{
		$interval = "n";
	}

	// If difference is between 1 hour and 24 hours
	// hours is a good interval
	elseif($difference >= 60*60 && $difference<60*60*24)
	{
		$interval = "h";
	}

	// If difference is between 1 day and 7 days
	// days is a good interval
	elseif($difference >= 60*60*24 && $difference<60*60*24*7)
	{
		$interval = "d";
	}

	// If difference is between 1 week and 30 days
	// weeks is a good interval
	elseif($difference >= 60*60*24*7 && $difference < 60*60*24*30)
	{
		$interval = "ww";
	}

	// If difference is between 30 days and 365 days
	// months is a good interval, again, the same thing
	// applies, if the 29th February happens to exist
	// between your 2 dates, the function will return
	// the 'incorrect' value for a day
	elseif($difference >= 60*60*24*30 && $difference < 60*60*24*365)
	{
		$interval = "m";
	}

	// If difference is greater than or equal to 365
	// days, return year. This will be incorrect if
	// for example, you call the function on the 28th April
	// 2008 passing in 29th April 2007. It will return
	// 1 year ago when in actual fact (yawn!) not quite
	// a year has gone by
	elseif($difference >= 60*60*24*365)
	{
		$interval = "y";
	}

	// Based on the interval, determine the
	// number of units between the two dates
	// From this point on, you would be hard
	// pushed telling the difference between
	// this function and DateDiff. If the $datediff
	// returned is 1, be sure to return the singular
	// of the unit, e.g. 'day' rather 'days'

	switch($interval)
	{
		case "m":
			$months_difference = floor($difference / 60 / 60 / 24 / 29);
			while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto)
			{
				$months_difference++;
			}
			$datediff = $months_difference;

	// We need this in here because it is possible
	// to have an 'm' interval and a months
	// difference of 12 because we are using 29 days
	// in a month

			if($datediff==12)
			{
				$datediff--;
			}
	
			$res = ($datediff==1) ? "vor einem Monat" : "vor $datediff Monaten";
			break;

		case "y":
			$datediff = floor($difference / 60 / 60 / 24 / 365);
			$res = ($datediff==1) ? "vor einem Jahr" : "vor $datediff Jahren";
			break;

		case "d":
			$datediff = floor($difference / 60 / 60 / 24);
			$res = ($datediff==1) ? "gestern" : "$vor datediff Tagen";
			break;
	
		case "ww":
			$datediff = floor($difference / 60 / 60 / 24 / 7);
			$res = ($datediff==1) ? "letzte Woche" : "vor $datediff Wochen";
			break;

		case "h":
			$datediff = floor($difference / 60 / 60);
			$res = ($datediff==1) ? "vor einer Stunde" : "vor $datediff Stunden";
			break;

		case "n":
			$datediff = floor($difference / 60);
			$res = ($datediff==1) ? "vor einer Minute" : "vor $datediff Minuten";
			break;

		case "s":
			$datediff = $difference;
			$res = ($datediff==1) ? "vor einer Sekunde" : "vor $datediff Sekunden";
			break;
	}
	return $res;
}

?>