<?php
session_start();
include ('database.php');

$videoID = $_GET['postID'];

// Fetch video details
$sql = "SELECT * FROM vid_cinfo WHERE vid_id='$videoID'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $t = $row['ch_id'];
    $uploadedDate = $row['dat'];
}

// Fetch channel details
$sql = "SELECT * FROM channel WHERE ch_id='$t'";
$result = mysqli_query($conn, $sql);
$channel_name='dummy';
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $channel_name = $row['ch_name'];
    $subscriber_count = $row['total_subs'];
}

// Fetch channel image
$sql = "SELECT img FROM user WHERE channel_id='$t'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $ch_img = $row['img'];
}

// Check subscription status
$isSubscribed = false;
if ($_SESSION['loggedin']) {
    $user_id = $_SESSION['userid'];
    $subscriptionQuery = "SELECT user_id FROM subsOrOwn WHERE ch_id = '$t' AND user_id = '$user_id' AND type='subscription'";
    $subscriptionResult = mysqli_query($conn, $subscriptionQuery);
    $isSubscribed = mysqli_num_rows($subscriptionResult) > 0;
}

// Check if the video is liked or disliked by the user
$isLiked = false;
$isDisliked = false;
if ($_SESSION['loggedin']) {
    $likeSQL = "SELECT * FROM interaction WHERE user_id='$user_id' AND video_id='$videoID' AND type='liked'";
    $dislikeSQL = "SELECT * FROM interaction WHERE user_id='$user_id' AND video_id='$videoID' AND type='disliked'";

    $resLiked = mysqli_query($conn, $likeSQL);
    $isLiked = mysqli_num_rows($resLiked) > 0;

    $resDisliked = mysqli_query($conn, $dislikeSQL);
    $isDisliked = mysqli_num_rows($resDisliked) > 0;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        .video-post {
            margin-bottom: 20px;
        }
        .video-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .video-actions button {
            display: flex;
            align-items: center;
        }
        .video-actions i {
            margin-right: 5px;
        }
        header {
            background: #0f1120a4;
            color: #fc6161;
            position: fixed;
            width: 100%;
            padding: 1rem 0;
            margin: -10px -6px;
            display: flex;
            z-index: -1;
        }
        header.container {
            width: 100vw;
            display: flex;
            padding-bottom: 20px;
        }
        .subscribe-btn {
            background-color: #fc6161;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .subscribe-btn:hover {
            background-color: #e74c3c;
        }
        .actions{
          display: flex;
          flex-direction: row;
          align-items: center;
        }
        .actions p{
          
          padding: 10px;
          margin-top: 8px;
          
        }
        body {
            font-family: Arial, sans-serif;
            height: 100%;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #012239, #5d2020);
        }
    </style>
</head>
<body>
<header>
    <div class="container">
        <div class="left">
            <h1><i>Vid</i><sub>tube</sub></h1>
        </div>
    </div>
</header>

<section class="py-4" style="margin-top:5%">
    <div class="d-flex justify-content-center">
        <div class="card mb-4 py-4" style="width: 100%; max-width: 600px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex flex-row align-items-center">
                        <img src="<?php echo $ch_img ?>" width="70px" height="70px" alt="Avatar" style="border-radius: 50%; margin-right: 10px;">
                        <div>
                            <h2 class="h6 mb-0"><a href="channel.php?ch_id=<?php echo $t ?>"><?php echo $channel_name ?></a></h2>
                            <p class="small text-muted mb-0">
                                <?php
                                $uploadedDate = DateTime::createFromFormat('d-m-Y', $uploadedDate);
                                $currentDate = new DateTime();
                                $dateDifference = $currentDate->diff($uploadedDate);

                                $years = $dateDifference->y;
                                $months = $dateDifference->m;
                                $days = $dateDifference->d;

                                if ($years > 0) {
                                    echo $years . " years ago";
                                } elseif ($months > 0) {
                                    echo $months . " months ago";
                                } elseif ($days > 0) {
                                    echo $days . " days ago";
                                } else {
                                    echo "Today";
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                    <?php if($_SESSION['loggedin']) { ?>
                        <button class="subscribe-btn" onclick="toggleSubscription(<?php echo $t; ?>)">
                        <?php echo $isSubscribed ? 'Subscribed' : 'Subscribe'; ?>
                    </button>
                    <?php } ?>
                </div>
                <div class="d-flex justify-content-center my-4">
                    <?php
                    include ('database.php');
                    $sql = "SELECT * FROM videos WHERE vid_id='$videoID'";
                    $result = mysqli_query($conn, $sql);
                    $videoDetails = array();
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        array_push($videoDetails, $row['vid_title'], $row['vid_description'], $row['video'], $row['video_thumbnail'], $row['likes'], $row['dislikes'], $row['comments'], $row['type'], $row['views']);
                    }
                    $conn->close();
                    ?>
                </div>

                <video width="100%" height="auto" src="<?php echo $videoDetails[2] ?>" controls></video>
                <h4 class="h5"><?php echo $videoDetails[0]; ?></h4>
                <p class="text-muted mb-0"><?php echo $videoDetails[1]; ?></p>
                <p class="h7" style="margin-top: 15px;"><?php echo $videoDetails[8].' views'; ?></p>
                <div>
                    <div class="video-actions">
                        <div class="actions">
                            <button class="btn btn-text-dark" type="button" id="like-btn">
                                <?php echo $isLiked ? '<i class="bi bi-hand-thumbs-up-fill"></i>' : '<i class="bi bi-hand-thumbs-up"></i>'; ?>
                            </button>
                            <p><b><?php echo $videoDetails[4] ?></b></p>
                        </div>
                        <div class="actions">
                            <button class="btn btn-text-dark" type="button" id="dislike-btn">
                                <?php echo $isDisliked ? '<i class="bi bi-hand-thumbs-down-fill"></i>' : '<i class="bi bi-hand-thumbs-down"></i>'; ?>
                            </button>
                            <p><b><?php echo $videoDetails[5] ?></b></p>
                        </div>
                        <button class="btn btn-text-dark" type="button">
                            <?php if (isset($_SESSION['userid'])) { ?>
                                <a href="comments.php?videoID=<?php echo $videoID ?>&parent=<?php echo -1 ?>"><i data-feather="message-circle"></i></a>
                            <?php } else { ?>
                                <a href="#"><i data-feather="message-circle"></i></a>
                            <?php } ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>

document.addEventListener("DOMContentLoaded", function() {
    const video = document.querySelector('video');
    let viewed = false;

    video.addEventListener('timeupdate', function() {
        if (!viewed && video.currentTime > 30) { // Assuming 30 seconds or more should count as a view
            viewed = true;
            logView();  // Call function to log the view
        }
    });

    function logView() {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "log_view.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(`video_id=<?php echo $videoID; ?>`); // Send video ID to backend
    }
});

document.getElementById('like-btn').addEventListener('click', function() {
    interact('liked');
});

document.getElementById('dislike-btn').addEventListener('click', function() {
    interact('disliked');
});

function interact(action) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "interact.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText == "success") {
                location.reload(); // Reload to update the button states
            } else {
                alert("An error occurred.");
            }
        }
    };
    xhr.send("action=" + action + "&video_id=<?php echo $videoID; ?>");
}
</script>

</body>
</html>




<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
  integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"
  integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
  feather.replace();

  var mySwiper = new Swiper('.swiper-container', {
    // Optional parameters
    slidesPerView: 'auto',
    spaceBetween: 24,
  });

  function toggleSubscription(channel_id) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "subscribe.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        location.reload();
      }
    };
    xhr.send("channel_id=" + channel_id);
  }
</script>


