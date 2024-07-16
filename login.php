<?php
    session_start();
    if (isset($_POST['login'])){
        if (!empty($_POST['email']) && !empty($_POST['password'])){
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            //echo $_POST['username'].' '. $_POST['password']. ' '. $_POST['type']. ' '. $hashed_password ;
            include('database.php');
            $sql = "SELECT * FROM user where email='{$_POST['email']}'";
            $result = mysqli_query($conn, $sql);
            

            if(mysqli_num_rows($result) > 0){ 
                $row = mysqli_fetch_assoc($result);
                if(password_verify($_POST['password'],$row['password'])){
                    $_SESSION['username']=$row['username'];
                    $_SESSION['userid'] = $row['user_id'];
                    $_SESSION['email']= $row['email'];
                    $_SESSION['image']=$row['img'];
                    $_SESSION['ch_id']=$row['channel_id'];
                    $_SESSION['loggedin']=true;
                    header('location: main.php');
                }else{
                    echo "<script> alert('Wrong password')</script>";
                    //header('location: login.html');
                }
            }else {
                echo "<script> alert('No user Found')</script>"; 
                //header('location: login.html');
            }
            mysqli_close( $conn );
        }
        else{
            echo "<script> alert('Not found')</script>";
            //header('location: login.html');
        }
    }
    
?>