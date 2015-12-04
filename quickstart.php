<?php
require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_DriveService.php';

$client = new Google_Client();
// Get your credentials from the APIs Console
$client->setClientId('400654440137.apps.googleusercontent.com');
$client->setClientSecret('JlBAG6uU6L1jrpS4WLosvKqp');
$client->setRedirectUri('https://localhost/C2CSync/quickstart.php');
$client->setScopes(array('https://www.googleapis.com/auth/drive'));

$service = new Google_DriveService($client);

$authUrl = $client->createAuthUrl();

//Request authorization
//print "Please visit:\n$authUrl\n\n";
//print "Please enter the auth code:\n";
$authCode = $_GET["code"];
//echo $_GET["code"];
// Exchange authorization code for access token
$accessToken = $client->authenticate($authCode);
$client->setAccessToken($accessToken);

/**
 * Download a file's content.
 *
 * @param apiDriveService $service Drive API service instance.
 * @param File $file Drive File instance.
 * @return String The file's content if successful, null otherwise.
 */
 
//$fileId='0B3rqdQblIcAGRWR6a0F1SVJkLUk'; //txt file
$fileId='0B-m2SPsUniXpQW9iRkRCRzBwYWM'; //zip file


$file = new Google_DriveFile();
$file = $service->files->get($fileId);
$name = $file->getTitle();
echo $name;
//$content = downloadFile($service, $file);
//make($content);

//printFile($service, $fileId);
function printFile($service, $fileId) {
  try {
    $file = new Google_DriveFile();
	//$file->setTitle('My document');
    $file = $service->files->get($fileId);

    print "Title: " . $file->getTitle();
    //print "Description: " . $file->getDescription();
    //print "MIME type: " . $file->getMimeType();
  } catch (Exception $e) {
    print "An error occurred: " . $e->getMessage();
  }
}

function downloadFile($service, $file) {
  //$downloadUrl = $file->getWebContentLink();
  $downloadUrl = $file->getDownloadUrl();
  //download1($downloadUrl);
  //download2($downloadUrl);
 
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

/**
 * Copy an existing file.
 *
 * @param Google_DriveService $service Drive API service instance.
 * @param String $originFileId ID of the origin file to copy.
 * @param String $copyTitle Title of the copy.
 * @return DriveFile The copied file. NULL is returned if an API error occurred.
 */
 /*
 $copyTitle='PraiseTheLord';
 copyFile($service, $fileId, $copyTitle);
function copyFile($service, $originFileId, $copyTitle) {
  $copiedFile = new Google_DriveFile();
  $copiedFile->setTitle($copyTitle);
  try {
    return $service->files->copy($originFileId, $copiedFile);
  } catch (Exception $e) {
    print "An error occurred: " . $e->getMessage();
  }
  return NULL;
}*/

/**
   * Retrieve a file metadata and content from Drive.
   *
   * @param string $fileId ID for the file to retrieve from Drive
   * @return string JSON string representation of file metadata and content
   */
   
   //GetFile($fileId);
  function GetFile($fileId) {
    $fileVars = null;
    try {
      /*
       * Retrieve metadata for the file specified by $fileId.
       */
      $file = $this->service->files->get($fileId);
      $fileVars = get_object_vars($file);
  
      /*
       * Retrieve the file's content using download URL specified in metadata.
       */
      $request = new apiHttpRequest($file->downloadUrl, 'GET', null, null);
      $httpRequest = apiClient::$io->authenticatedRequest($request);
      $content = $httpRequest->getResponseBody();
      $fileVars['content'] = $content?($content):'';
    } catch (apiServiceException $e) {
      /*
       * Log error and re-throw
       */
      error_log('Error retrieving file from Drive: ' . $e->getMessage(), 0);
      throw $e;
    }
    return json_encode($fileVars);
  }
  function download2($downloadUrl){
  //$source = "http://someurl.com/afile.zip";
 $destination = "Downloadedfile.txt";

 $data = file_get_contents($downloadUrl);
 $file = fopen($destination, "w+");
 fputs($file, $data);
 fclose($file);
 }
 
 function download1($downloadUrl){
  $ch = curl_init();
  //$source = "http://someurl.com/afile.zip";
  curl_setopt($ch, CURLOPT_URL, $downloadUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $data = curl_exec ($ch);
  curl_close ($ch);

  $destination = "Downloadedfile.txt";
  $file = fopen($destination, "w+");
  fputs($file, $data);
  fclose($file);
 }
 
 function make($data){
 $destination = "Downloadedzip.zip";
 $file1 = fopen($destination, "w+");
 fputs($file1, $data);
 fclose($file1);
 }
?>