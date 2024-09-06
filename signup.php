<?php
session_start();

$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);


if(isset($_POST['signup'])) {
    if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        include('database.php');

        $email = $_POST['email'];
        $check_email_query = "SELECT * FROM user WHERE email='$email'";
        $result = mysqli_query($conn, $check_email_query);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('An account with this email already exists. Please use a different email.')</script>";
            header('location:signup.html');
        } else {
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $username = $_POST['username'];

            $sql = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

            if (mysqli_query($conn, $sql)) {
                $lastInsertedID = mysqli_insert_id($conn);
                $result = mysqli_query($conn, "SELECT * FROM user WHERE user_id='$lastInsertedID'");
                $row = mysqli_fetch_assoc($result);

                $_SESSION['username'] = $row['username'];
                $_SESSION['userid'] = $row['user_id'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['image'] = $row['img'];
                $_SESSION['ch_id'] = $row['channel_id'];
                $_SESSION['loggedin'] = true;

                header('location: main.php');
            } else {
                echo "<script>alert('Error creating account. Please try again later.')</script>";
                header('location:signup.html');
            }
        }
        mysqli_close($conn);
    } else {
        echo "<script>alert('Please fill out all required fields.')</script>";
        header('refresh:1; url=signup.html');
    }
}
?>