<?php
    include('database.php');
    $selected_comment_id = $_GET['selected_comment'];
    $selected_comment_parent= $_GET['selected_comment_parent'];
    $videoID=$_GET['videoID'];
    $parent=$_GET['parent'];
    echo $selected_comment_id;
    if($selected_comment_parent==$selected_comment_id){
        #delete all comment with parent=seleced_comment_parent
        $sql="DELETE FROM comment_likes_interaction WHERE comment_id IN(
            SELECT comment_id FROM comment_manage WHERE parent = '$selected_comment_parent')";
            $result = mysqli_query($conn, $sql);

        $sql="DELETE FROM interaction WHERE comment_id IN(
        SELECT comment_id FROM comment_manage WHERE parent = '$selected_comment_parent')";
        $result = mysqli_query($conn, $sql);
        $sql="DELETE FROM comment_manage WHERE parent = '$selected_comment_parent'";
        $result = mysqli_query($conn, $sql);
    }
    else{
        #commentid primary key in interactiom
        $sql="DELETE FROM comment_likes_interaction WHERE comment_id = '$selected_comment_id'";
        $result = mysqli_query($conn, $sql);
        $sql="DELETE FROM interaction WHERE comment_id = '$selected_comment_id'";
        $result = mysqli_query($conn, $sql);
        $sql="DELETE FROM comment_manage WHERE comment_id = '$selected_comment_id'";
        $result = mysqli_query($conn, $sql);
    }
    $conn->close();
    header("Location: comments.php?videoID=" .$videoID . "&parent=" . $parent);

?>