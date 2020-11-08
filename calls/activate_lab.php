<?php
include("../src/api.php");
if(isset($_GET["activate"]) && isset($_GET["project"]) && isset($_GET["name"]) ){

  $project=$_GET["project"];
  $name = $_GET["name"];
  $api = new ApiGNS3();
  $result = $api->runProject($project,$name);
  echo $result;
}
?>
