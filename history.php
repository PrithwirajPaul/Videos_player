<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    $_SESSION['loggedin'] = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
    <title>VidTube</title>
    <style>
        .history-container {
            margin-top: 130px;
            margin-left: -25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
        }

        .history-heading {
            font-size: 32px;
            margin-bottom: 20px;
        }

        .section {
            width: 80%;
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
            margin-left: 10px;
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
            border-radius: 10px;
            object-fit: cover;
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
                    <dialog class="modal" id="channelModal">
                        <a href="#" class="c_btn"> <i class="bi bi-x-octagon"></i></a>
                        <form action="create_channel.php" method="post" enctype="multipart/form-data">
                            <input type="text" name="channel_name" placeholder="Enter your channel name" required> <br>
                            <input type="text" name="channel_description" placeholder="Add channel description" required>
                            <br>
                            <div style="display:flex; flex-direction:row; align-items:space-between">
                                <p style="margin-right:15px">Channel Image- </p>
                                <input style="border:0" type="file" name="image" id="image" placeholder="Choose image"
                                    required>
                            </div>
                            <button style="align-self:center" type="submit" name="create">Create</button>
                        </form>
                    </dialog>
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
    <div class="history-container">
        <div class="history-heading">History</div>

        <div class="section">
            <div class="section-title">Watched Videos</div>
            <div class="video-grid">
                <?php
                //Fetch watched videos from the database for the current user
                $userId = $_SESSION['userid'];
                include('database.php');
                $sql = "select * from channel ch inner join (vid_cInfo c inner join videos v on c.vid_id=v.vid_id) on ch.ch_id=c.ch_id where v.vid_id= any(select video_id from interaction where user_id='{$_SESSION['userid']}' and type='watched');";
                $result = mysqli_query($conn, $sql);
                $watchedVideos = mysqli_fetch_all($result, MYSQLI_ASSOC);

                if (empty($watchedVideos)) {
                    echo '<div>No watched videos yet</div>';
                } else {
                    foreach ($watchedVideos as $video) { ?>
                        <div class="video-item"
                            onclick="window.location.href='video-shower.php?postID=<?php echo $video['vid_id'] ?>';"
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
                    <?php }
                }
                ?>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Liked Videos</div>
            <div class="video-grid">
                <?php
                // Fetch liked videos from the database for the current user
                $sql = "select * from channel ch inner join (vid_cInfo c inner join videos v on c.vid_id=v.vid_id) on ch.ch_id=c.ch_id where v.vid_id= any(select video_id from interaction where user_id='{$_SESSION['userid']}' and type='liked');";
                $result = mysqli_query($conn, $sql);
                $likedVideos = mysqli_fetch_all($result, MYSQLI_ASSOC);

                if (empty($likedVideos)) {
                    echo '<div>No liked videos yet</div>';
                } else {
                    foreach ($likedVideos as $video) { ?>
                        <div class="video-item"
                            onclick="window.location.href='video-shower.php?postID=<?php echo $video['vid_id'] ?>';"
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
                    <?php }
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        const modal = document.querySelector('#modal');
        const openModal = document.querySelector('.btn');
        const closeModal = document.querySelector('.c_btn');
        openModal.addEventListener('click', () => {
            modal.showModal();
        });
        closeModal.addEventListener('click', () => {
            modal.close();
        });

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
    </script>
    <script>
        const channelModal = document.querySelector('#channelModal');
        const openChannelModal = document.querySelector('.crt_chnl');

        openChannelModal.addEventListener('click', () => {
            channelModal.showModal();
        });

        closeModal.addEventListener('click', () => {
            channelModal.close();
        });
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