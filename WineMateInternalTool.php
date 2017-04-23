#!/usr/bin/env php
<?php
namespace wineMateThrift;


error_reporting(E_ALL);

require_once __DIR__.'/lib/Thrift/ClassLoader/ThriftClassLoader.php';
require_once('db_utils.php');
require_once('utils.php');

use Thrift\ClassLoader\ThriftClassLoader;

$GEN_DIR = realpath(dirname(__FILE__)).'/gen-php';

$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', __DIR__ .'/lib');
$loader->registerDefinition('wineMateThrift', $GEN_DIR);
$loader->register();

if (php_sapi_name() == 'cli') {
  ini_set("display_errors", "stderr");
}

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TPhpStream;
use Thrift\Transport\TBufferedTransport;
use Thrift\Server\TServerSocket;
use Thrift\Server\TSimpleServer;
use Thrift\Factory\TBinaryProtocolFactory;
use Thrift\Factory\TTransportFactory;

class wineMateThriftHandler implements \wineMateThrift\WineMateServicesIf {
	public function uploadTagInfo(\wineMateThrift\TagInfo $tagInfo) {
                var_dump($tagInfo);
		$conn = db_connect();
                $response = new \wineMateThrift\UploadTagInfoResponse;
		$sql_check_tag_id = "SELECT * FROM tag_info WHERE tag_id ='".$tagInfo->tagID."'";
		$res_check_tag_id = $conn->query($sql_check_tag_id);
		if ($res_check_tag_id && $res_check_tag_id->num_rows > 0) {
		  printf ("Tag %s already exists\n", $tagInfo->tagID);
           	  $response->status = \wineMateThrift\UploadTagInfoStatus::UPLOAD_DUPLICATE_TAG_ID;
                  $response->tagInfo = $this->getTagInfo($tagInfo->tagID);
		  return $response;
		}

		$sql_insert_tag_info = sprintf("INSERT INTO tag_info (tag_id, wine_id, authentication_key, tag_password, roll_number, tag_written_operator, time_created) VALUES('%s', '%d', '%s', '%s', '%d', '%s', '%d')", $tagInfo->tagID, $tagInfo->wineID, $tagInfo->authenticationKey, $tagInfo->tagPassword, $tagInfo->rollNumber, $tagInfo->operatorID, time());
		$result = $conn->query($sql_insert_tag_info);
		if ($result) {
			printf ("Tag %s is uploaded successfully!\n", $tagInfo->tagID);
                        $response->status = \wineMateThrift\UploadTagInfoStatus::UPLOAD_SUCCESS;
		} else {
			printf ("Tag %s is failed to upload!\n", $tagInfo->tagID);
			$response->status = \wineMateThrift\UploadTagInfoStatus::UPLOAD_FAILED;
		}
                return $response;
	}

  public function getTagInfo($tagId) {
    $conn = db_connect();
    $sql_check_tag_id = "SELECT * FROM tag_info WHERE tag_id ='".$tagId."'";
    $result = $conn->query($sql_check_tag_id);
    if ($result && $result->num_rows == 1) {
      $row = $result->fetch_assoc();
      $response = new \wineMateThrift\TagInfo;
      $response->tagID = $row['tag_id'];
      $response->tagPassword = $row['tag_password'];
      $response->authenticationKey = $row['authentication_key'];
      $response->wineID = $row['wine_id'];
      $response->rollNumber = $row['roll_number'];
      $response->operatorID = $row['tag_written_operator'];
      return $response;
    } else {
      return null;
    }
  }
}

header('Content-Type', 'application/x-thrift');
if (php_sapi_name() == 'cli') {
  echo "\r\n";
}

$handler = new wineMateThriftHandler();
$processor = new \wineMateThrift\WineMateServicesProcessor($handler);

$serverTransport = new TServerSocket('0.0.0.0',7892);
//$serverTransport->listen();
$tfactory = new TTransportFactory();
$pfactory = new TBinaryProtocolFactory();
$server = new TSimpleServer($processor, $serverTransport, $tfactory, $tfactory, $pfactory, $pfactory);
$server->serve();
