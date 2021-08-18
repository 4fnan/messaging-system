<?php
require_once('Models/Database.php');

$dbInstance= Database::getInstance();
$db = $dbInstance->getdbConnection();
session_start();

    $query = "SELECT * FROM Users WHERE user_id != '".$_SESSION['id']."'";
    $statement = $db->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
$json = json_encode($result);
echo $json;


