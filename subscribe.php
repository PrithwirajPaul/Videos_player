<?php
session_start();
include('database.php');

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "User not logged in.";
    exit;
}

// Get user_id and channel_id
$user_id = $_SESSION['userid'];
$channel_id = $_POST['channel_id'];

// Check if the user is already subscribed to the channel
$checkSubscriptionQuery = "SELECT * FROM subsOrOwn WHERE user_id = '$user_id' AND ch_id = '$channel_id' AND type = 'subscription'";
$checkSubscriptionResult = mysqli_query($conn, $checkSubscriptionQuery);

// If the user is already subscribed, unsubscribe them
if (mysqli_num_rows($checkSubscriptionResult) > 0) {
    $unsubscribeQuery = "DELETE FROM subsOrOwn WHERE user_id = '$user_id' AND ch_id = '$channel_id' AND type = 'subscription'";
    if (mysqli_query($conn, $unsubscribeQuery)) {
        echo "Unsubscribed successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // If the user is not subscribed, subscribe them
    $subscribeQuery = "INSERT INTO subsOrOwn (user_id, ch_id, type) VALUES ('$user_id', '$channel_id', 'subscription')";
    if (mysqli_query($conn, $subscribeQuery)) {
        echo "Subscribed successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
