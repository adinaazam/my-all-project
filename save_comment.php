<?php
include "db.php";

$post_id=$_POST['post_id'];
$comment=$_POST['comment'];

$stmt=$conn->prepare("INSERT INTO comments(post_id,comment) VALUES(?,?)");
$stmt->bind_param("is",$post_id,$comment);
$stmt->execute();

header("Location:index.php");
?>