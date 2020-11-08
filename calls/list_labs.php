<?php

include("./src/api.php");

$api = new ApiGNS3();
$result = $api->listProjects();
echo $result;
?>
