<?php
/*
 * The debug gateway is a simple gateway that calls the real gateway and then checks if
 * the outgoing message is correctly formatted. If not, it wraps the message into a correctly 
 * formatted message, so that even fatal errors are caught. It is highly recommended
 * the the real gateway be called directly for production use.  
 * 
 * This gateway requires CURL to work properly
 */

//Enabling sessions may result in lowered performance and computers being set on fire
//use at your own risk
define("ENABLE_SESSIONS", true);

//Guess gateway location (you may change this manually)
$gatewayUrl = 'http://' . $_SERVER['HTTP_HOST'] . str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])) . '/zend.php';
$gatewayUrl = str_replace('//gateway', '/gateway', $gatewayUrl);
$sessionName = ini_get('session.name');
if(isset($_GET[$sessionName]))
{
	//Add session id
	$gatewayUrl .= '?' . $sessionName . '=' . $_GET[$sessionName];
}

define("AMFPHP_BASE", realpath(dirname(__FILE__)) . "/"); 
define("AMFPHP_CONTENT_TYPE", 'Content-type: application/x-amf');

include_once(AMFPHP_BASE . 'io/AMFDeserializer.php');
include_once(AMFPHP_BASE . 'io/AMFInputStream.php');
include_once(AMFPHP_BASE . 'io/AMFOutputStream.php');
include_once(AMFPHP_BASE . 'io/AMFSerializer.php');
include_once(AMFPHP_BASE . 'util/AMFBody.php');
include_once(AMFPHP_BASE . 'util/AMFObject.php');
include_once(AMFPHP_BASE . 'util/AMFHeader.php');
include_once(AMFPHP_BASE . 'util/CharsetHandler.php');

//This portion taken from SabreAMF (released under LGPL)
$data = $GLOBALS['HTTP_RAW_POST_DATA'];
$error = NULL;
$ch = curl_init($gatewayUrl);

curl_setopt($ch,CURLOPT_VERBOSE,1);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_TIMEOUT,5);
curl_setopt($ch,CURLOPT_HTTPHEADER,array(AMFPHP_CONTENT_TYPE));
curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);

$result = curl_exec($ch);

if (curl_errno($ch)) {
	$error = 'CURL error: ' . curl_error($ch);
} else {
    curl_close($ch);
}

//Is the result valid so far?
if($result[0] != chr(0))
{
	//If chr(0) is not the first char, then this result is not good
	//Strip html
	$error = strip_tags($result);
}

if($data == NULL || $data == "")
{
	echo "cURL and the debug gateway are installed correctly. You may now use the debug gateway from Flash.";
	die();
}

if($error != NULL)
{
	//Get the last response index, otherwise the error will not register
	//In the NetConnection debugger	
	$amf = new AMFObject(); // create the amf object
	$amf->setInputStream($data);
	$deserializer = new AMFDeserializer($amf->getInputStream());
	$deserializer->deserialize($amf);
	
	$lastBody = $amf->getBodyAt($amf->numBody() - 1);
	$lastIndex = $lastBody->getResponseIndex();
	
	// add the error object to the body of the AMFObject
	$amfout = new AMFObject();
	$amfbody = new AMFBody($lastIndex."/onStatus", $lastIndex);
	$amfbody->setResults(array('description' => $error));
	$amfout->addBody($amfbody);  
	
	// create a new output stream
	$outstream = new AMFOutputStream();
	// create a new serializer
	$serializer = new AMFSerializer($outstream);
	
	// serialize the data
	$serializer->serialize($amfout);

	$result = $outstream->flush();
}

header(AMFPHP_CONTENT_TYPE);
header("Content-length: " . strlen($result));
print($result);
?>
