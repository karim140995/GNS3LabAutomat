<?php

class ApiGNS3{

  private $url = "http://192.168.1.170:3080"; //GNS3 Server url
  private $username = "admin"; //Username
  private $password = "tWgvBr4rEUgTOESKoSDHRnAwZ4BsEHYbly59bBWWm65ozHywRxFkduk4aIOGNxRQ"; //Password


  // Constructor
  public function __construct(){

  }

  //List all projects available in GNS3
  public function listProjects(){
    $uri = $this->url."/v2/projects";
    // use key 'http' even if you send the request to https://...
    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);

    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

    //execute
    $result = curl_exec($ch);
    return $result;
  }

  //List all the nodes in a project
  public function listNodes($project){

  }

  //Run a project
  //725be060-af6a-446a-aaf6-db64b9f00a10
  public function runProject($project,$name){
    $ch = curl_init();
    //Duplicating project
    $uri = $this->url."/v2/projects/".$project."/duplicate";
    //Random bytes to reference the project name
    $bytes = random_bytes(30);

    echo "===== Initializing network ====";
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //@curl_setopt($ch, CURLOPT_HEADER  , false);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
    curl_setopt($ch, CURLOPT_POSTFIELDS,'{"name" :"'.$name.$project.'"}' );
    $duplicate_result = curl_exec($ch);
  //  $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//    echo var_dump($duplicate_result);
    if(!$duplicate_result){
      echo "Error creating network, exiting";
      exit();
    }
    echo "<br>";
    echo "Network has been created.";
    echo "<br>";
    $duplicate_result = json_decode($duplicate_result);
    $project = $duplicate_result->project_id;



    $uri = $this->url."/v2/projects/".$project."/nodes";
    curl_setopt($ch,CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

    //Get the list of the nodes in the project
    $result = curl_exec($ch);
    if(!$result){
      echo "Project dosent exist.";
      exit();
    }
    $result = json_decode($result);

    //Open projects
    $uri = $this->url."/v2/projects/".$project."/open";
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_URL, $uri);
    $open_result = curl_exec($ch);
    echo "==== Starting project ====";
    echo "<br>";
    if(!$open_result){
      echo "Error starting project, Exiting.";
      exit();
    }
    echo "Project started.";
    echo "<br>";
    foreach ($result as $node){
        echo "====Starting nodes====";
        echo "<br>";
        //Start each node in the project
        $uri = $this->url."/v2/projects/".$project."/nodes/".$node->node_id."/start";
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_URL, $uri);
        $result = curl_exec($ch);
        if(!$result){
          echo "Coul  d not start node ".$node->name;
          exit();
        }

        $node_api_response = json_decode($result);
        echo "Node name : ".$node_api_response->name;
        echo "<br>";
        if($node_api_response->console){
          echo "Address : ".$this->url.":".$node_api_response->console;
        }
        echo "<br>";
    }
    curl_close($ch);
    echo "Done.";

  }
  //Run a node
  public function runNode($project){

  }


}




 ?>
