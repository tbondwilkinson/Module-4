<?php
require_once 'OpenID/RelyingParty.php';
require_once 'OpenID/Message.php';
require_once 'Net/URL2.php';

session_start();
 
$realm = "http://ec2-54-245-10-30.us-west-2.compute.amazonaws.com";
$returnTo = $realm . "/~tbondwilkinson/Module-4/Calendar/popup_return_to.php";

 
$identifier = $_GET['openid_identifier'];
 
$o = new OpenID_RelyingParty($returnTo, $realm, $identifier);

$authRequest = $o->prepare();
$url = $authRequest->getAuthorizeURL();
 
header("Location: ".$url);
exit;
?>
