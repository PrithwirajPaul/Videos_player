<?php
session_start();
include('database.php');

// Check if user is logged in
if (!isset($_SESSION['loggedin'])) {
    $_SESSION['loggedin'] = false;
}

$channel_id = $_GET['ch_id'];

//fetch image
$imageQuery = "SELECT img FROM user WHERE user_id = ( select user_id from subsOrOwn where ch_id='$channel_id' and type='owned')";
$imageResult = mysqli_query($conn, $imageQuery);
$image = mysqli_fetch_assoc($imageResult);

// Fetch channel details
$channelQuery = "SELECT * FROM channel WHERE ch_id = '$channel_id'";
$channelResult = mysqli_query($conn, $channelQuery);
$channel = mysqli_fetch_assoc($channelResult);

// Fetch subscriber count
$subscribersQuery = "SELECT COUNT(user_id) as subscriber_count FROM subsOrOwn WHERE ch_id = '$channel_id' and type='subscription'";
$subscribersResult = mysqli_query($conn, $subscribersQuery);
$subscribers = mysqli_fetch_assoc($subscribersResult);

// Check if the current user is subscribed to this channel
$isSubscribed = false;
if ($_SESSION['loggedin']) {
    $user_id = $_SESSION['userid'];
    $subscriptionQuery = "SELECT user_id FROM subsOrOwn WHERE ch_id = '$channel_id' AND user_id = '$user_id' and type='subscription'";
    $subscriptionResult = mysqli_query($conn, $subscriptionQuery);
    $isSubscribed = mysqli_num_rows($subscriptionResult) > 0;
}



// Fetch videos
$videosQuery = "SELECT * FROM videos join vid_cInfo on videos.vid_id=vid_cInfo.vid_id WHERE ch_id=$channel_id";
$videosResult = mysqli_query($conn, $videosQuery);
$videosCount = $videosResult != NULL ? mysqli_num_rows($videosResult) : 0;

if (isset($_POST["mysrc"])) {
    $searchQuery = $_POST['s_txt'];
    header("location:search_page.php?my_search=" . $searchQuery);
}
?>


<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Channel view</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .channel-info {
            display: flex;
            align-items: center;
            background: #1f1f1f;
            /* Background color for channel info */
            padding: 20px;
            margin-top: 150px;
            margin-bottom: 50px;
            margin-left: 2%;
            color: #cac7ff;
            /* Text color for channel info */
        }

        .channel-info img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 20px;
        }

        .channel-info div {
            display: flex;
            flex-direction: column;
        }

        .channel-info h2 {
            margin: 0;
            font-size: 24px;
            /* Adjust font size */
        }

        .subscribe-btn {
            background-color: #fc6161;
            /* Subscribe button background color */
            color: white;
            /* Subscribe button text color */
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .subscribe-btn:hover {
            background-color: #e74c3c;
            /* Darker shade on hover */
        }

        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
            margin-left: 30px;
        }

        .video-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            color: #cac7ff;
            transition: transform 0.3s ease;
        }

        .video-item:hover {
            transform: scale(1.05);
        }

        .video-item img {
            width: 100%;
            height: 150px;
            /* Fixed height */
            border-radius: 10px;
            object-fit: cover;
            /* Ensures image covers the area while maintaining aspect ratio */
        }

        .video-item h3 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .video-item p {
            color: #cac7ff;
            margin-bottom: 0;
            font-size: 14px;
        }

        .v_info {
            display: flex;
            align-items: center;
            justify-content: start;
            margin-top: 10px;
        }

        .v_info img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .v_info h3 {
            font-size: 16px;
            margin: 5px;
            text-align: left;
        }

        .v_info p {
            font-size: 14px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .video-item h3 {
                font-size: 14px;
            }

            .v_info h3 {
                font-size: 14px;
            }

            .v_info p {
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .video-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 10px;
            }

            .video-item {
                padding: 10px;
                margin-top: -5px;
            }

            .video-item img {
                height: 120px;
                /* Adjusted height for smaller screens */
            }

            .video-item h3 {
                font-size: 14px;
            }

            .video-item p {
                font-size: 12px;
            }

            .v_info img {
                width: 25px;
                height: 25px;
            }

            .v_info h3 {
                font-size: 14px;
            }

            .v_info p {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="left">
                <a href="main.php">
                    <h1 style="color: #fc6161"><i>Vid</i><sub>tube</sub></h1>
                </a>
            </div>
            <div class="middle">
                <form action="main.php" method="post" class="search_bar">
                    <input type="text" placeholder="Search" name="s_txt">
                    <button type="submit" class="s_btn" name="mysrc"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <div class="right">
                <?php if ($_SESSION['loggedin']) { ?>
                    <?php if (isset($_SESSION['ch_id']) && $_SESSION['ch_id'] != null) { ?>
                        <div style="display:flex; flex-direction:row;">
                            <div><a href="#" class="btn" style="cursor:pointer"><img src="images/create_videos.png" alt=""
                                        height="30" width="30"></a></div>
                            <dialog class="modal" id="modal">
                                <a href="#" class="c_btn"> <i class="bi bi-x-octagon"></i></a>
                                <form action="create_videos.php" method="post" enctype="multipart/form-data">
                                    <input type="text" name="title" placeholder="Enter your video title" required> <br>
                                    <input style="height:5%" type="text" name="description" placeholder="Add description"> <br>
                                    <input style="height:5%" type="text" name="type" placeholder="Add Genre/Type"> <br>
                                    <div style="display:flex; flex-direction:row; align-items:space-between">
                                        <p style="margin-right:15px">Thumbnail- </p>
                                        <input style="border:0" type="file" name="thumbnail" id="thumbnail"
                                            placeholder="Choose thumbnail" required>
                                    </div>
                                    <div style="display:flex; flex-direction:row; align-items:space-between">
                                        <p style="margin-right:15px"> Video-</p>
                                        <input style="border:0" type="file" name="video" placeholder="Upload video" required>
                                    </div>
                                    <button style="align-self:center" type="submit" name="upload">Upload</button>
                                </form>
                            </dialog>
                        </div>
                    <?php } ?>
                    <div class="rt_img dropdown">
                        <img style="cursor:pointer" src="<?php echo $_SESSION['image']; ?>" alt="Profile" height="30"
                            width="30" onclick="toggleDropdown()">
                        <div id="dropdownMenu" class="dropdown-content">
                            <?php if (isset($_SESSION['ch_id']) && $_SESSION['ch_id'] != null) { ?>
                                <a href="channel.php?ch_id=<?php echo $_SESSION['ch_id'] ?>">Your Channel</a>
                            <?php } else { ?>
                                <a href="#" style="cursor:pointer" class="crt_chnl">Create Channel</a>
                            <?php } ?>
                            <a href="history.php">History</a>
                            <a href="#" class="log">Log Out</a>
                        </div>
                    </div>
                    <dialog class="modal" id="logoutModal">
                        <a href="#" class="c_btn2"> <i class="bi bi-x-octagon"></i></a>
                        <h4>Are you sure want to log out?</h4>
                        <button onclick="window.location.href='logout.php'">Yes</button>
                        <button onclick="window.location.href='main.php'">No</button>
                    </dialog>
                <?php } else { ?>
                    <div><a href="login.html" style="color:#fc6161; cursor:pointer"><i class="bi bi-person-badge"></i>Join
                            now!</a></div>
                <?php } ?>
            </div>
        </div>
    </header>

    <div class="channel-info container">
        <div style="align-self:center;">
            <img src="<?php echo $image['img']; ?>" alt="Channel Image" height="100" width="100">
        </div>
        <div>
            <h2><?php echo $channel['ch_name']; ?></h2>
            <p><?php echo $channel['ch_description']; ?></p>
            <p>Subscribers: <?php echo $subscribers['subscriber_count']; ?></p>
            <p>Videos: <?php echo $videosCount; ?></p>
        </div>
        <div style="margin-left:4%; align-self:center;">
            <?php if ($_SESSION['loggedin']) { ?>
                <button class="subscribe-btn" onclick="toggleSubscription(<?php echo $channel_id; ?>)">
                    <?php echo $isSubscribed ? 'Subscribed' : 'Subscribe'; ?>
                </button>
            <?php } ?>
        </div>
    </div>

    <div class="video-grid container">
        <?php while ($video = mysqli_fetch_assoc($videosResult)) { ?>
            <div class="video-item" onclick="window.location.href='video-shower.php?postID=<?php echo $video['vid_id'] ?>';"
                style="cursor:pointer">
                <img src="<?php echo $video['video_thumbnail']; ?>" alt="Thumbnail">
                <div class="v_info">
                    <?php
                    $imgQuery = "select img from user where channel_id='{$video['ch_id']}'";
                    $img = mysqli_query($conn, $imgQuery);
                    $img = mysqli_fetch_assoc($img);
                    ?>
                    <img src=" <?php echo $img['img'] ?>">
                    <h3 style="margin-top:3px"><?php echo $video['vid_title']; ?></h3>
                </div>
                <div class="v_info" style="margin-top:-10px; margin-left:46px">
                    <p>
                        <?php
                        $uploadedDate = $video['dat'];
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
                    <p style="margin-left:auto"><?php echo $video['views'] . ' views'; ?></p>
                </div>
            </div>
        <?php } ?>
    </div>
    <script>
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
        function toggleDropdown() {
            document.getElementById("dropdownMenu").classList.toggle("show");
        }

        window.onclick = function (event) {
            if (!event.target.matches('.rt_img img')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
        const modal = document.querySelector('#modal');
        const openModal = document.querySelector('.btn');
        const closeModal = document.querySelector('.c_btn');
        openModal.addEventListener('click', () => {
            modal.showModal();
        })
        closeModal.addEventListener('click', () => {
            modal.close();
        })
        const logModal = document.querySelector('#logoutModal');
        const openlogModal = document.querySelector('.log');
        const closelogModal = document.querySelector('.c_btn2');


        openlogModal.addEventListener('click', () => {
            logModal.showModal();
        });

        closelogModal.addEventListener('click', () => {
            logModal.close();
        });
    </script>

</body>

</html>