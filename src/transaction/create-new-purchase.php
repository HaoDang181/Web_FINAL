<?php

require_once '../db-connect.php';

if(isset($_POST)){
    $data = file_get_contents("php://input");
    $result = json_decode($data, true);
    echo $result[0]['name'];
}else{
    echo "nothing";
}