<?php

// Smaato Code Snippet PHP
// Copyright Smaato, Inc., All rights reserved
// Rev: 20110115

// Version: 1.1

$phpsnip = 101;

// Your publisher-id
// (assigned by Smaato)
//$pub="0"; // test mode
$pub="923831522"; // production mode

// The adspace-id
// (assigned by Smaato)
// change get param
$adspace = "0"; // test mode

// The user-id, the soma-server has generated at first request
// Attention:
// If it is possible to track each single user on your side, the user-parameter should be empty (&user=).
// Inside the response, there will be the "SomaUserID"-header, with a new generated user-id.
// This new generated "SomaUserID" should be used as "&user="-parameter
// for every following request of this particular user.
//
// If it is not possible on your side, to split apart different users
// (and re-recognize him afterwards at each request)
// please use the following ID for ALL users/requests
// ExampleValue: 900
$user_id=900;

// max. width of the wished ad (e.g. MMA small is 120)
$width='';

// max. height of the wished ad (e.g. MMA small is 20)
$height='';

// Position of the ad
$pos="top";

// Amount of ads which should be requested. Default is "1"
$ad_count=1;

// parameter strict?
$formatstrict="true";

// request timeout
$timeout = 7;

// The wished format of the requested ad. Default: ALL (other possible values: "IMG" or "TXT")
$ad_format="img";

$beacon="true";

/////////////////////////////////
// Do not edit below this line //
/////////////////////////////////
// This section defines Smaato functions and should be used AS IS.

$response_format="HTML";
// The user-agent of the client device
$ua = isset($_SERVER["HTTP_USER_AGENT"] ) ? $_SERVER["HTTP_USER_AGENT"] : '';
$ua = isset($_SERVER["HTTP_X_OPERAMINI_PHONE_UA"] ) ? $_SERVER["HTTP_X_OPERAMINI_PHONE_UA"] : $ua;
$ua = isset($_SERVER["HTTP_X_ORIGINAL_USER_AGENT"] ) ? $_SERVER["HTTP_X_ORIGINAL_USER_AGENT"] : $ua;
$ua = isset($_SERVER["HTTP_X_DEVICE_USER_AGENT"] ) ? $_SERVER["HTTP_X_DEVICE_USER_AGENT"] : $ua;

// Set the user-agent header inside the request
@ini_set("user_agent", $ua);

// The user-agent of the client device as encoded device-parameter
$device=rawurlencode($ua);

// The ip-address of the client
// First, have a look if the headers can be accessed via $_SERVER
// and a X-FORWARDED-FOR header exists. If not, use client_ip or remote_address.
$x_forwarded_for="";
$ip="";
if (isset($_SERVER)) {
	if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		$x_forwarded_for=$_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	if (isset($_SERVER["HTTP_CLIENT_IP"])) {
		$ip=$_SERVER["HTTP_CLIENT_IP"];
	} else {
		$ip=$_SERVER["REMOTE_ADDR"];
	}
// If $_SERVER is not accessible, try to get the values out of the environment
} else  {
	if (getenv('HTTP_X_FORWARDED_FOR')) {
		$x_forwarded_for=getenv("HTTP_X_FORWARDED_FOR");
	}
	if (getenv('HTTP_CLIENT_IP')) {
		$ip=getenv('HTTP_CLIENT_IP');
	} else {
		$ip=getenv('REMOTE_ADDR');
	}
}

// Other client header informations, which are set using the prefix "X-MH-"
$mh_accept = isset($_SERVER["HTTP_ACCEPT"]) ? $_SERVER["HTTP_ACCEPT"] : '';
$mh_user_agent = isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : '';
$mh_accept_charset = isset($_SERVER["HTTP_ACCEPT_CHARSET"]) ? $_SERVER["HTTP_ACCEPT_CHARSET"] : '';
$mh_accept_language = isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]) ? $_SERVER["HTTP_ACCEPT_LANGUAGE"] : '';
$mh_x_wap_profile = isset($_SERVER["HTTP_X_WAP_PROFILE"]) ? $_SERVER["HTTP_X_WAP_PROFILE"] : '';
$mh_profile = isset($_SERVER["HTTP_PROFILE"]) ? $_SERVER["HTTP_PROFILE"] : '';
$mh_operamini_ua = isset($_SERVER["HTTP_X_OPERAMINI_PHONE_UA"]) ? $_SERVER["HTTP_X_OPERAMINI_PHONE_UA"] : '';
$mh_original_ua = isset($_SERVER["HTTP_X_ORIGINAL_USER_AGENT"]) ? $_SERVER["HTTP_X_ORIGINAL_USER_AGENT"] : '';
$mh_device_ua = isset($_SERVER["HTTP_X_DEVICE_USER_AGENT"]) ? $_SERVER["HTTP_X_DEVICE_USER_AGENT"] : '';

// parameter check
$clickurl = isset($_REQUEST['clickurl']) ? $_REQUEST['clickurl'] : null;
$size     = isset($_REQUEST['size']) ? $_REQUEST['size'] : '.320x50';
$adspace  = isset($_REQUEST['adspace']) ? $_REQUEST['adspace'] : $adspace;
// chenge ad size
list($width, $height) = explode('x', preg_replace('/^.*?([1-9])/', '${1}', $size)); 

$soma_url="http://soma.smaato.com/oapi/reqAd.jsp?pub=$pub&adspace=$adspace&adcount=$ad_count&response=$response_format&devip=$ip&user=$user_id&format=$ad_format&position=$pos&height=$height&width=$width&device=$device&beacon=$beacon&phpsnip=$phpsnip&formatstrict=$formatstrict&dimension=xxlarge";

// Example, how to add some client header to the request
$opts = array(
  'http'=>array(
    'method'=>"GET",
    'timeout'       => $timeout, // timeout....
    'ignore_errors' => true,     // block WARNING
    'header'=>"X-Forwarded-For: $x_forwarded_for\r\n"
		. "User-Agent: $ua\r\n"
		. "X-MH-Accept: $mh_accept\r\n"
		. "X-MH-User-Agent: $mh_user_agent\r\n"
		. "X-MH-Accept-Charset: $mh_accept_charset\r\n"
		. "X-MH-Accept-Language: $mh_accept_language\r\n"
		. "X-MH-X-Wap-Profile: $mh_x_wap_profile\r\n"
		. "X-MH-Profile: $mh_profile\r\n"
		. "X-MH-X-Forwarded-For: $x_forwarded_for\r\n"
		. "X-MH-X-OperaMini-Phone-UA: $mh_operamini_ua\r\n"
		. "X-MH-X-Original-User-Agent: $mh_original_ua\r\n"
		. "X-MH-X-Device-User-Agent: $mh_device_ua\r\n"
  )
);

// Create a context with the defined options
$context = stream_context_create($opts);

// Open the file using the HTTP headers set above
try {
    $tag = file_get_contents($soma_url, false, $context);

    // 取得確認
    if ($tag === false || empty($tag) || preg_match('/adspacer.gif/', $tag)) {
        throw new Exception('ad empty');
    }
    // include clickurl
    $tag = str_replace("<a href=\"","<a target=\"new\" href=\"".$clickurl,$tag); // for android
    $tag = str_replace("<p>","<p align=\"middle\">",$tag);
    $tag = "<body style=\"margin:0\">" . $tag . "</body>";

    echo $tag;
} catch (Exception $e) {
    echo include('./no_ad.html');
    error_log($soma_url);
}
