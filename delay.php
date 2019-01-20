<?
$flight = isset($_REQUEST['flight']) ? $_REQUEST['flight'] : "SU2072";

if($flight == "SU2072")
	$url = "https://uk.flightaware.com/live/flight/AFL2072"; // SU 2072
elseif($flight == "OS0831")
	$url = "https://uk.flightaware.com/live/flight/AUA831"; //OS0831
elseif($flight == "LH1760")
	$url = "https://uk.flightaware.com/live/flight/DLH1760";
elseif($flight == "CY0311")
	$url = "https://uk.flightaware.com/live/flight/CYP311";	
elseif($flight == "CY0427")
	$url = "https://uk.flightaware.com/live/flight/CYP427";	
elseif($flight == "QR0629")
	$url = "https://uk.flightaware.com/live/flight/QTR629";		
	
	
$r = file_get_contents($url);
$r = str_replace('<div id="topContent">', '<div id="topContent" style="display:none">', $r);
$r = str_replace('<header role="banner" id="topWrapper">', '<header role="banner" id="topWrapper" style="display:none">', $r);
$r = str_replace('<div class="flightPageUpgradeIdent">', '<div class="flightPageUpgradeIdent" style="display:none">', $r);
$r = str_replace('<div class="flightPageLinks ">', '<div class="flightPageLinks style="display:none">', $r);
$r = str_replace('<div class=\'flightPageDetails\'>', '<div class="flightPageDetails" style="display:none">', $r);

$style = "<style>.blockerDisclaimerContainer{display: none!important;}
.flightPageLinks{display: none!important;}
#Footer{display: none!important;}
#blockerDisclaimerContainer{display: none!important;}
</style>";
$r = str_replace("</head>", $style."</head>", $r);


echo $r;