<?php
include "db.php";

$title = $_POST['title'];
$content = $_POST['content'];
$created_at = date('Y-m-d H:i:s'); // Current date and time

$stmt = $conn->prepare("INSERT INTO posts(title, content, created_at) VALUES(?, ?, ?)");
$stmt->bind_param("sss", $title, $content, $created_at);
$stmt->execute();

header("Location: index.php");
exit();
?>