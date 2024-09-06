<?php
session_start();
include('database.php');

if (isset($_POST['action']) && isset($_POST['video_id']) ) {
    if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
        echo "<script> alert('Please login first to interact')</script>";
    }
    $user_id = $_SESSION['userid'];
    $video_id = $_POST['video_id'];
    $action = $_POST['action'];

    // Fetch current likes and dislikes
    $videoSQL = "SELECT likes, dislikes FROM videos WHERE vid_id='$video_id'";
    $videoResult = mysqli_query($conn, $videoSQL);
    if (!$videoResult || mysqli_num_rows($videoResult) == 0) {
        echo "<script> alert('Video not found or database error')</script>";
        exit;
    }
    
    $videoData = mysqli_fetch_assoc($videoResult);
    $likes = $videoData['likes'];
    $dislikes = $videoData['dislikes'];

    // Check if the user has already liked/disliked the video
    $checkSQL = "SELECT * FROM interaction WHERE user_id='$user_id' AND video_id='$video_id'";
    $result = mysqli_query($conn, $checkSQL);
    if (!$result) {
        error_log("Error checking interaction: " . mysqli_error($conn));
        echo "error";
        exit;
    }
    $existingInteraction = mysqli_fetch_assoc($result);

    if ($existingInteraction) {
        if ($existingInteraction['type'] == $action) {
            // If the same action is already taken, remove it (unlike/undislike)
            $deleteSQL = "DELETE FROM interaction WHERE user_id='$user_id' AND video_id='$video_id'";
            if (!mysqli_query($conn, $deleteSQL)) {
                error_log("Error deleting interaction: " . mysqli_error($conn));
                echo "error";
                exit;
            }
            if ($action == 'liked') {
                $likes--;
            } else {
                $dislikes--;
            }
        } else {
            // If the opposite action is taken, update the record
            $updateSQL = "UPDATE interaction SET type='$action' WHERE user_id='$user_id' AND video_id='$video_id'";
            if (!mysqli_query($conn, $updateSQL)) {
                error_log("Error updating interaction: " . mysqli_error($conn));
                echo "error";
                exit;
            }
            if ($action == 'liked') {
                $likes++;
                $dislikes--;
            } else {
                $likes--;
                $dislikes++;
            }
        }
    } else {
        // If no interaction exists, insert the new action
        $insertSQL = "INSERT INTO interaction (user_id, video_id, type) VALUES ('$user_id', '$video_id', '$action')";
        if (!mysqli_query($conn, $insertSQL)) {
            error_log("Error inserting interaction: " . mysqli_error($conn));
            echo "error";
            exit;
        }
        if ($action == 'liked') {
            $likes++;
        } else {
            $dislikes++;
        }
    }

    // Ensure that like and dislike counts are not negative
    $likes = max(0, $likes);
    $dislikes = max(0, $dislikes);

    // Update the video's like/dislike counts
    $updateVideoSQL = "UPDATE videos SET likes='$likes', dislikes='$dislikes' WHERE vid_id='$video_id'";
    if (!mysqli_query($conn, $updateVideoSQL)) {
        error_log("Error updating video: " . mysqli_error($conn));
        echo "error";
        exit;
    }

    echo "success";
} else {
    echo "error";
    error_log("Invalid request parameters or session not set.");
}

$conn->close();
?>
