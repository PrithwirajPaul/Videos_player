
<?php
    session_start();
    if(isset($_POST['upload'])) {
    
        if($_FILES["thumbnail"]["error"]===4) {
            echo "<script> alert('Thumbnail doesn't exist!!')</script>";
        }else if($_FILES["video"]["error"]>0){
            echo "<script> alert('Error occured uploading videos')</script>";
        }
        else{
            $thumbnail= $_FILES['thumbnail']['name'];
            $tmpname= $_FILES['thumbnail']['tmp_name'];
            $vid=$_FILES["video"]["name"];
            $vidtmp=$_FILES["video"]["tmp_name"];

            $validImageExtension=['jpg','jpeg','png'];
            $validVidExtension=['mp4','webm','avi','flv'];

            $imgExtension = explode('.', $thumbnail);
            $vidExtension = explode('.',$vid);

            $imgExtension = strtolower(end($imgExtension));
            $vidExtension = strtolower(end($vidExtension));

            if(!in_array($imgExtension,$validImageExtension)){
                echo "<script> alert('Thumbnail format not applicable!!')</script>";
            }else if(!in_array($vidExtension,$validVidExtension)) {
                echo "<script> alert('Video format not applicable!!')</script>";
            }
            else {
                $newImgname= $thumbnail.uniqid();
                $newVidname= $vid.uniqid();

                $newImgname.='.'.$imgExtension;
                $newImgname = 'images/'. $newImgname;

                $newVidname.='.'.$vidExtension;
                $newVidname = 'videos/'. $newVidname;

                move_uploaded_file($tmpname,$newImgname);
                move_uploaded_file($vidtmp,$newVidname);

                include('database.php');
                $dat= date('d-m-Y');
                $infoSql= "Insert into vid_cInfo (dat,ch_id) VALUES('{$dat}','{$_SESSION['ch_id']}')";
                if(mysqli_query($conn, $infoSql)){ 
                    $lastInsertedID = mysqli_insert_id($conn);
                    $CreateSql= "Insert into videos (vid_id,vid_title,vid_description,video,video_thumbnail,likes,dislikes,comments,type,views) VALUES('{$lastInsertedID}','{$_POST['title']}','{$_POST['description']}','{$newVidname}','{$newImgname}',0,0,0,'{$_POST['type']}',0)";
                    if(mysqli_query($conn,$CreateSql)) {
                        header('location: main.php');
                    }else{
                        echo "<script> alert('Error inserting the videos')</script>";
                    }
                }else{
                    echo "<script> alert('Error inserting')</script>";
                }
                mysqli_close( $conn );   
                
            }
        }
    }
?>
