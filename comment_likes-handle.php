<?php
    include('database.php');
    $cid=$_GET['cid'];
    $uid=$_GET['uid'];
    $color=$_GET['color'];
    $videoID=$_GET['videoID'];
    $parent=$_GET['parent'];
    $sql="SELECT likes FROM comment_manage WHERE comment_id='$cid'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0){
        if ($row = mysqli_fetch_assoc($result)){
            $likes=(INT)$row['likes'];
        }
    }
    if($color=='blue'){
    $likes--;
    $sql="UPDATE comment_manage SET likes='$likes' WHERE comment_id='$cid'";
    $result = mysqli_query($conn, $sql);
    $sql="DELETE FROM comment_likes_interaction WHERE comment_id='$cid' AND user_id='$uid'";
    $result = mysqli_query($conn, $sql);
    }
    else{
        $likes++;
        $sql="UPDATE comment_manage SET likes='$likes' WHERE comment_id='$cid'";
        $result = mysqli_query($conn, $sql);
        $sql="INSERT INTO comment_likes_interaction (comment_id,user_id) VALUES('$cid','$uid')";
        $result = mysqli_query($conn, $sql);
    }
    $conn->close();
    header("Location: comments.php?videoID=" .$videoID . "&parent=" . $parent);

?>