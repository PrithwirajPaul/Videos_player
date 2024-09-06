
<?php
    session_start();
    if(isset($_POST['create'])) {
    
        if($_FILES["image"]["error"]===4) {
            echo "<script> alert('Thumbnail doesn't exist!!')</script>";
        }
        else{
            $thumbnail= $_FILES['image']['name'];
            $tmpname= $_FILES['image']['tmp_name'];

            $validImageExtension=['jpg','jpeg','png','webp'];

            $imgExtension = explode('.', $thumbnail);

            $imgExtension = strtolower(end($imgExtension));

            if(!in_array($imgExtension,$validImageExtension)){
                echo "<script> alert('Thumbnail format not applicable!!')</script>";
            }
            else {
                $newImgname= $thumbnail.uniqid();

                $newImgname.='.'.$imgExtension;
                $newImgname = 'images/'. $newImgname;

                move_uploaded_file($tmpname,$newImgname);

                include('database.php');
                $ch_name = mysqli_real_escape_string($conn, $_POST['ch_name']);
                $ch_description = mysqli_real_escape_string($conn, $_POST['ch_description']);

                $infoSql = "INSERT INTO channel (ch_name, ch_description, total_vid, total_subs) VALUES ('$ch_name', '$ch_description', 0, 0)";
                if(mysqli_query($conn, $infoSql)){ 
                    $lastInsertedID = mysqli_insert_id($conn);
                    $CreateSql= "Insert into subsOrOwn (ch_id,user_id,type) VALUES('{$lastInsertedID}','{$_SESSION['userid']}','owned')";
                    $updtsql= "update user set img= '$newImgname' , channel_id='{$lastInsertedID}' where user_id='{$_SESSION['userid']}'";
                    if(mysqli_query($conn,$CreateSql) && mysqli_query($conn,$updtsql)) {
                        $_SESSION['ch_id']=$lastInsertedID;
                        $_SESSION['image']=$newImgname;
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
