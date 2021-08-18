<?php
require_once('Models/Database.php');
$dbInstance= Database::getInstance();
$db = $dbInstance->getdbConnection();

if (isset($_POST['search']))
{
    $term = $_POST['search'];
}

$query = "SELECT fullname FROM Users WHERE fullname LIKE '%$term%' LIMIT 5";
$statement = $db->prepare($query);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
$json = json_encode($result);
echo $json;
