<?php
require_once('Models/Database.php');

$dbInstance= Database::getInstance();
$db = $dbInstance->getdbConnection();

session_start();

$_SESSION['receiver'] = $_GET['username'];
$receive = $_GET['userid'];
$sender = $_SESSION['id'];


    if (isset($_GET['insert'])) {
        $msg = $_GET['message'];
        $query = "INSERT INTO messages(sender_id, receiver_id, message) VALUES($sender, $receive, $msg)";
        echo $query;
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $json = json_encode($result);
        echo $json;

    } else {


        if (isset($_GET['received'])) {
            $query = "SELECT * FROM messages WHERE sender_id = $receive AND receiver_id = $sender";

        }
        else {

            $query = "SELECT * FROM messages WHERE sender_id = $sender AND receiver_id = $receive";
        }
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $json = json_encode($result);
        echo $json;
    }




