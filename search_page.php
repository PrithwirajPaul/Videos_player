<?php
function wordSeparate($s)
{
    $words = preg_split('/\s+/', trim($s));
    return $words;
}
function isVisited($visited, $s)
{
    return isset($visited[$s]) && $visited[$s] === true;
}
?>
<?php
session_start();
$my_search = $_GET['my_search'];
include('database.php');
$words = wordSeparate($my_search);

$visited = array();
$videoList = array();
if ($words[0] != '') {
    for ($i = 0; $i < count($words); $i++) {
        $word = $words[$i];
        $sql = "SELECT * FROM videos WHERE vid_title LIKE '%$word%'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (!isVisited($visited, $row['vid_id'])) {
                    array_push($videoList, array($row['vid_id'], $row['vid_title'], $row['vid_description'], $row['video'], $row['video_thumbnail'], $row['likes'], $row['dislikes'], $row['comments'], $row['type'], $row['views']));
                    $visited[$row['vid_id']] = true;
                }
            }
        }
    }
    $visited = array();
    $channelList = array();
    for ($i = 0; $i < count($words); $i++) {
        $word = $words[$i];
        $sql = "SELECT * FROM channel WHERE ch_name LIKE '%$word%'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (!isVisited($visited, $row['ch_id'])) {
                    array_push($channelList, array($row['ch_id'], $row['ch_name'], $row['ch_description']));
                    $visited[$row['ch_id']] = true;
                }
            }
        }
    }
}
mysqli_close($conn);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <style>
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
                <h1 onclick="window.location.href='main.php'"><i>Vid</i><sub>tube</sub></h1>
            </div>
        </div>
    </header>
    <h1 class="h3 text-center" style="margin-top: 100px; color:white">You Searched For <?php echo $_GET['my_search'] ?></h1>

    <div class="container bootstrap snippets bootdey">
        <hr>
        <ol class="breadcrumb">
            <li><a href="#">Page name</a></li>
            <li><a href="#">Search Results</a></li>
            <li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
        </ol>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="well search-result">

                </div>
            </div>
            <?php for ($i = 0; $i < count($videoList); ++$i) { ?>

                <div class="well search-result"
                    onclick="window.location.href='video-shower.php?postID=<?php echo $videoList[$i][0] ?>';"
                    style="cursor: pointer;">
                    <div class="row">
                        <a href="#">
                            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-2">
                                <img class="img-responsive" src="https://www.bootdey.com/image/400x200/7B68EE/000000"
                                    alt="">
                            </div>
                            <div class="col-xs-6 col-sm-9 col-md-9 col-lg-10 title">
                                <h3><?php echo $videoList[$i][1] ?></h3>
                                <p><?php echo $videoList[$i][2] ?></p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php } ?>


            <?php for ($i = 0; $i < count($channelList); ++$i) { ?>
                <div class="well search-result"
                    onclick="window.location.href='channel.php?ch_id=<?php echo $channelList[$i][0] ?>';"
                    style="cursor: pointer;">
                    <div class="row">
                        <a href="#">
                            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-2">
                                <img class="img-responsive" src="https://www.bootdey.com/image/400x200/48D1CC/000000"
                                    alt="">
                            </div>
                            <div class="col-xs-6 col-sm-9 col-md-9 col-lg-10 title">
                                <h3><?php echo $channelList[$i][1] ?></h3>
                                <p><?php echo $channelList[$i][2] ?></p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php } ?>

            <div class="row">
                <button type="button" class="btn btn-info  btn-block">
                    <i class="glyphicon glyphicon-refresh"></i>Load more...
                </button>
            </div>
        </div>
    </div>
    </div>
</body>

</html>