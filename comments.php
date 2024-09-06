<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<!-- Add icon library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="comment-style.css">
</head>
<body>
<?php
session_start();
$current_user=$_SESSION['userid'];
include('database.php');
$sql="SELECT img FROM user WHERE user_id='$current_user'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0){
    if ($row = mysqli_fetch_assoc($result)) {
      $my_img=$row['img'];     
    }
  } 
  $conn->close();
?>
<section class="content-item" id="comments">
    <div class="container">   
    	<div class="row">
            <div class="col-sm-8">   
                <form id="postform" autocomplete="off" method ='post'> 
                <?php
                    $t="Add Comment"; 
                    if($_GET['parent']!=-1){
                    $t="Add Reply";
                    }
                ?>
                <h3 class="pull-left"><?php echo $t?></h3> 
                	<button type="submit" class="btn btn-normal pull-right" name="submitted">Submit</button>
                    <fieldset>
                        <div class="row">
                            <div class="col-sm-3 col-lg-2 hidden-xs">
                            	<img class="img-responsive" src=<?php echo $my_img?> alt="">
                            </div>
                            <div class="form-group col-xs-12 col-sm-9 col-lg-10">
                                <textarea class="form-control" name="my_comment" id="message" placeholder="Your message" required=""></textarea>
                            </div>
                        </div>  	
                    </fieldset>
                </form>
                <!-- COMMENT 1 - END -->
            </div>
        </div>
    </div>
</section>
</body>
</html>
<?php
    
    // if($_GET){
    //     print_r($_GET); //remember to add semicolon      
    // }else{
    // echo "Url has no user";
    // }
    
    include('database.php');
    $videoID=$_GET['videoID'];
    $parent=$_GET['parent'];
    
    if(isset($_POST['submitted'])){
        $myDate = date("d-m-y h:i:s"); 
        $my_comment=$_POST['my_comment'];
        $sql="SELECT MAX(comment_id) AS max_id FROM comment_manage";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            if ($row = mysqli_fetch_assoc($result)) {
              $newCommentID=(int)$row['max_id'];     
            }
          }
        $newCommentID+=1;
        if ($parent==-1){
            $parent=$newCommentID;
        }
        $sql="INSERT INTO comment_manage(comment_id,comment_string,parent,likes,date)VALUES('$newCommentID','$my_comment','$parent',0,'$myDate')";
        if(mysqli_query($conn, $sql)) {
        } else {
            echo 'Error: ' . mysqli_error($conn);
        }
        $sql="INSERT INTO interaction (user_id, video_id,comment_id) VALUES('$current_user','$videoID','$newCommentID')";
        $result = mysqli_query($conn, $sql);
        $parent=$_GET['parent'];
        header("Location: comments.php?videoID=" .$videoID . "&parent=" . $parent);
    }
    
    $sql="SELECT * FROM comment_manage WHERE comment_id IN
    (SELECT comment_id FROM interaction WHERE video_id='$videoID')";
    $result = mysqli_query($conn, $sql);
    $all_comment =array(); # all comment of Video X
    if(mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_assoc($result)){
            array_push ($all_comment ,array($row['comment_id'],$row['comment_string'],$row['date'],$row['parent'],$row['likes']));
        } 
    }
    $sql="SELECT * FROM comment_likes_interaction WHERE user_id = '$current_user'";
    $result = mysqli_query($conn, $sql);
    $my_liked_comment=array();
    if(mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_assoc($result)){
            array_push ($my_liked_comment,$row['comment_id']);
        } 
    }
    $sql="SELECT COUNT(comment_id) AS count FROM interaction
    WHERE video_id='$videoID'
    ";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){ 
        if($row = mysqli_fetch_assoc($result)){
            $count=$row['count'];
        } 
    }
    $conn->close();
?>

<h3> <?php echo $count?> Comments</h3>
<!-- COMMENT 1 - START -->
<?php
$visited = array();
?>
<?php
for ($i = 0; $i < count($all_comment); ++$i) {
    // Skip if the comment's parent ID is already visited
    if (in_array($all_comment[$i][3], $visited)) {
        continue;
    }
?>
    <div class="media" style="border:1px solid black; display:flex; flex-direction: column;">
        <a class="pull-left" href="#"></a>
        <?php
        // Inner loop to group comments by parent ID
        for ($j = 0; $j < count($all_comment); ++$j) {
            if ($all_comment[$j][3] != $all_comment[$i][3]) {
                continue;
            } else {
                array_push($visited, $all_comment[$j][3]);
            }
        ?>
            <div class="media-body">
            <?php
             include('database.php');
             $x = $all_comment[$j][0];
             $sql = "SELECT * FROM user WHERE user_id IN (
                         SELECT user_id FROM interaction WHERE comment_id = '$x')";
             $result = mysqli_query($conn, $sql);
             $commenter = '';
             if (mysqli_num_rows($result) > 0) {
                 if ($row = mysqli_fetch_assoc($result)) {
                     $commenter = $row['username'];
                     $commenter_id=$row['user_id'];
                     $commenter_img=$row['img'];
                 }
             }

            
            ?>
                
            <a class="pull-left" href="#"><img class="media-object" src=<?php echo $commenter_img;?> alt="" height="50px" width="50px"></a>
                <?php
                
                    // Find ancestor comment ID
                
                $boss = $all_comment[$j][0];
                $sql = "SELECT parent FROM comment_manage WHERE comment_id = '$boss'";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    if ($row = mysqli_fetch_assoc($result)) {
                        $ances = $row['parent'];
                    }
                }
                $conn->close();
                ?>
                <h4 style="font-family:'Courier New'" class="media-heading"><?php echo $commenter ?></h4>
                <p><?php echo $all_comment[$j][1] ?></p>
                <ul class="list-unstyled list-inline media-detail pull-left">
                    <li><i class="fa fa-calendar"></i><?php echo $all_comment[$j][2] ?></li>
                    <?php
                        if(in_array($all_comment[$j][0],$my_liked_comment)){
                            $color="blue";
                        }
                        else{
                            $color="black";
                        }
                    ?>
    <li>
        <a href="comment_likes-handle.php?uid=<?php echo $current_user ?>&cid=<?php echo $all_comment[$j][0] ?>&color=<?php echo $color?>&videoID=<?php echo $videoID?>&parent=<?php echo $parent?>">
            <i class="fa fa-thumbs-up" style="font-size:20px;color:<?php echo $color ?>;"></i>
        </a>
        <?php echo $all_comment[$j][4]?>
    </li>
                  <li class=""><a href="comments.php?videoID=<?php echo $videoID ?>&parent=<?php echo $ances ?>"><i class="fa fa-reply" aria-hidden="true"></i>
                  </a></li>
                  <?php 
                        #if the comments->coomenter == userid: then del
                        if($commenter_id==$current_user){?>
                        <li class=""><a href="comment-delete.php?selected_comment=<?php echo $all_comment[$j][0]?>&selected_comment_parent=<?php echo $all_comment[$j][3]?>&parent=<?php echo $parent?>&videoID=<?php echo $videoID?>"><i class="fa fa-trash-o" style="font-size:20px"></i>
                        </a></li>
                        <?php }?>
                </ul>
            </div>
        <?php } ?>
    </div>
<?php } ?>
