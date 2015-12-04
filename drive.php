<?php
require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_DriveService.php';
//require_once("connection.php");

//*************************************************  AUTHENTICATION  *****************************************************************************

$client = new Google_Client();
// Get your credentials from the APIs Console
$client->setClientId('207450131748-77dqmsu30ru8koskj5tm05av30da4e74.apps.googleusercontent.com');
$client->setClientSecret('hBfYYnU4Pz8x9WgnueKFZLwi');
$client->setRedirectUri('https://localhost/C2CSync/drive.php');
$client->setScopes(array('https://www.googleapis.com/auth/drive'));

$service = new Google_DriveService($client);

$authUrl = $client->createAuthUrl();

//Request authorization
//print "Please visit:\n$authUrl\n\n";
//print "Please enter the auth code:\n";
$authCode = $_GET["code"];

/*$reftoken = get_oauth2_token($authCode);

mysql_query("UPDATE files SET reftoken='".$reftoken."' WHERE sr='1'") or die('error updating');

$query1 = mysql_query("select * from  files");
$row1 = mysql_fetch_array($query1);
$authCode = $row1['reftoken'];
$accessToken = $reftoken->access_token;*/

// Exchange authorization code for access token

$accessToken = $client->authenticate($authCode);
$client->setAccessToken($accessToken);

//*************************************************  Select Flow  **************************************************************************

//$query = mysql_query("select * from  files");
//$row = mysql_fetch_array($query);
$flow='update';//$row['flow'];
if($flow=='down')
{

//**************************************************  DOWNLOAD  *****************************************************************************

$fileId = 1;//$row['fid'];
$file = new Google_DriveFile();
$file = $service->files->get($fileId);
$data = downloadFile($service, $file);
$name = $file->getTitle(); 
make($data, $name);
mysql_query("UPDATE files SET fname='".$name."' WHERE sr='1'") or die('error updating');

echo '<script type="text/javascript">';
echo 'window.location.href = "http://localhost/Dropbox-master/examples/putFile.php";';
echo '</script>';

}
elseif($flow=='up')
{

//**************************************************  UPLOAD  ****************************************************************************

$name = 'WorldCup2014.txt';
//Insert a file
$file = new Google_DriveFile();
$file->setTitle($name);
//$file->setDescription('A test document');
//$file->setMimeType('text/plain');

$data = file_get_contents($name);

$createdFile = $service->files->insert($file, array(
      'data' => $data,
    ));

print_r($createdFile);
/*
unlink($name); //Our privacy policy means we do not keep user's data 
session_destroy();

echo '<script type="text/javascript">';
echo 'window.location.href = "http://localhost/C2CSync";';
echo '</script>';*/
}
else
{
	$fileId = '0B8OGNVz7DYmxcjAzTkp1cW56RnM';
// First retrieve the file from the API.
	$file = new Google_DriveFile();
    $file = $service->files->get($fileId);

    // File's new metadata.
    $file->setTitle('NewWC');
    //$file->setDescription($newDescription);
    //$file->setMimeType($newMimeType);

    // File's new content.
    $data = 'The Dutch shall win !!!';//file_get_contents($newFileName);

    $additionalParams = array(
        'data' => $data
    );

    // Send the request to the API.
    $updatedFile = $service->files->update($fileId, $file, $additionalParams);
	print_r($updatedFile);
}
//**************************************************  FUNCTIONS  **********************************************************************************

function downloadFile($service, $file) {
  $downloadUrl = $file->getDownloadUrl();
  if ($downloadUrl) {
    $request = new Google_HttpRequest($downloadUrl, 'GET', null, null);
    $httpRequest = Google_Client::$io->authenticatedRequest($request);
    if ($httpRequest->getResponseHttpCode() == 200) {
      return $httpRequest->getResponseBody();
    } else {
      echo 'An error occurred.';// An error occurred.
      return null;
    }
  } else {
    echo 'The file doesnt have any content stored on Drive.';// The file doesn't have any content stored on Drive.
    return null;
  }
}

function make($data, $name){
 $destination = 'C:/xampp/htdocs/Dropbox-master/examples/'.$name;
 $file = fopen($destination, "w+");
 fputs($file, $data);
 fclose($file);
 }
 
 function get_oauth2_token($code) {
    global $client_id;
    global $client_secret;
    global $redirect_uri;
    global $lat;
    global $long;
    global $access_token;
 
    $oauth2token_url = "https://accounts.google.com/o/oauth2/token";//Request for authorization code exchange URL
    $clienttoken_post = array(
    "code" => $code,
    "client_id" => $client_id,
    "client_secret" => $client_secret,
    "redirect_uri" => $redirect_uri,
    "grant_type" => "authorization_code"
    );
     
    $curl = curl_init($oauth2token_url);
 
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $clienttoken_post);
    //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
 
    $data = curl_exec($curl);
    //echo $data;
    curl_close($curl);
    $data = json_decode($data);
	//$access_token = $data->access_token;
	
	return $data;
	}

?>

