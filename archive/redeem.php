<?php

define('DB_HOST', "localhost");
define('DB_NAME', "vodafone");
define('DB_USER', "root");
define('DB_PASSWORD', "12345678");

$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Failed to connect to MySQL");

$offer = $_GET["offer"];
$user_id = $_GET["user_id"];

$query = "SELECT offerId FROM offers WHERE offerTitle='$offer'";
$result = mysqli_query($con, $query);
$get = mysqli_fetch_assoc($result);
$offerId = $get["offerId"];

mysqli_query($con, "INSERT INTO user_history (user_id, offer_id) VALUES ('$user_id', '$offerId')");

header("Location: history.php");

?>