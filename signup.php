<?php
session_start();

$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);


if(isset($_POST['signup'])) {
    if (!empty($_POST['username']) && !empty($_POST['email']) ){
        $_SESSION['username'] = $_POST['username'];
        include('database.php');
        $sql = "Insert into user(username,email,password) VALUES('{$_POST['username']}','{$_POST['email']}','{$hashed_password}')";

        if(mysqli_query($conn, $sql)){ 
            $lastInsertedID = mysqli_insert_id($conn);
            $result = mysqli_query($conn, "select * from user where user_id='{$lastInsertedID}'");
            $row= mysqli_fetch_assoc($result);
            $_SESSION['username'] = $row['username'];
            $_SESSION['userid'] = $row['user_id'];
            $_SESSION['email']= $row['email'];
            $_SESSION['image']=$row['img'];
            $_SESSION['ch_id']=$row['channel_id'];
            $_SESSION['loggedin']=true;
            //echo $_SESSION['userid'] .'  '.$tmp['user_id']; 
            header('location: main.php');
        }else{
            echo "<script> alert('Error setting up the connection')</script>";
            header('location: signup.php');
        }
        mysqli_close( $conn );   
        
    }
    else{
        echo "<script> alert('Fill out the required field!!')</script>";
        header('location: signup.php');
    }
}



?>