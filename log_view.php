<?php
session_start();
include('database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $videoID = $_POST['video_id'];
    $userID = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;
    $sessionID = session_id();

    // Check if this user or session has already viewed the video
    $sql = "select * from views where video_id='$videoID' and (user_id='$userID' or session_id='$sessionID')";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        // Insert a new view record

        $insertSQL = "insert into views (user_id, video_id, session_id) VALUES ('$userID', '$videoID', '$sessionID')";
        mysqli_query($conn, $insertSQL);
        $insertSQL=  "insert into interaction(user_id,video_id,type) values('$userID','$videoID','watched')";
        mysqli_query($conn,$insertSQL);
        // Update the view count in the videos table
        $updateSQL = "update videos set views = views + 1 WHERE vid_id = '$videoID'";
        mysqli_query($conn, $updateSQL);
    }
}

$conn->close();
?>
