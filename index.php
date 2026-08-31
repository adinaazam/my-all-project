<?php
include "db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Blog System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h1>Simple Blog System</h1>

    <!-- Add Blog Form -->
    <h2>Add New Post</h2>

    <form action="save_post.php" method="POST">

        <input type="text" name="title" placeholder="Enter Title" required>

        <textarea name="content" placeholder="Write your blog..." required></textarea>

        <button type="submit">Post Blog</button>

    </form>

    <hr>

    <h2>All Blog Posts</h2>

<?php

$result = mysqli_query($conn, "SELECT * FROM posts ORDER BY id DESC");

while($post = mysqli_fetch_assoc($result))
{
?>

<div class="post">

    <h3><?php echo htmlspecialchars($post['title']); ?></h3>

    <p><?php echo htmlspecialchars($post['content']); ?></p>

    <h4>Add Comment</h4>

    <form action="save_comment.php" method="POST">

        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">

        <textarea name="comment" placeholder="Write Comment" required></textarea>

        <button type="submit">Comment</button>

    </form>

    <h4>Comments</h4>

<?php

$comments = mysqli_query($conn, "SELECT * FROM comments WHERE post_id=".$post['id']);

while($row = mysqli_fetch_assoc($comments))
{
?>

<p>💬 <?php echo htmlspecialchars($row['comment']); ?></p>

<?php
}
?>

</div>

<hr>

<?php
}
?>

</div>

</body>
</html>