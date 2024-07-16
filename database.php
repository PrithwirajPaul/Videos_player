<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "video_player_database";

     // Create connection
     $conn = new mysqli($servername, $username, $password);
 
     // Check connection
    if ($conn->connect_error) {
        die("DB Connection failed: " . $conn->connect_error);
    } 
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Error creating database: " . $conn->error;
    }

    // Close connection to create a new one for the database
    $conn->close();

    // Create connection to the new database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "CREATE TABLE IF NOT EXISTS user (
        
        username VARCHAR(255),
        email VARCHAR(255),
        password varchar(255),
        channel_id INT NULL,
        user_id INT PRIMARY KEY AUTO_INCREMENT,
        img VARCHAR(255) default ('images/default_pp.jpg')
    )";

    if ($conn->query($sql) === TRUE) {
        
    } else {
        echo "Error creating table: " . $conn->error;
    }

    $sql = "CREATE TABLE IF NOT EXISTS videos (

    vid_id INT PRIMARY KEY,    
    vid_title VARCHAR(255),
    vid_description VARCHAR(255),
    video varchar(255),
    video_thumbnail varchar(255),
    likes int,
    dislikes int,
    comments int,
    type varchar(100),
    views int
)";

if ($conn->query($sql) === TRUE) {
    
} else {
    echo "Error creating table: " . $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS vid_cInfo (

vid_id INT primary key auto_increment,    
dat VARCHAR(255),
ch_id int
)";

if ($conn->query($sql) === TRUE) {

} else {
echo "Error creating table: " . $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS channel (

ch_id INT PRIMARY KEY auto_increment,    
ch_name VARCHAR(255),
ch_description VARCHAR(255),
total_vid int,
total_subs int
)";

if ($conn->query($sql) === TRUE) {

} else {
echo "Error creating table: " . $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS subsOrOwn (

user_id INT ,    
ch_id int,
type VARCHAR(255)
)";

if ($conn->query($sql) === TRUE) {

} else {
echo "Error creating table: " . $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS interaction (

interaction_id int primary key auto_increment,
user_id INT ,    
video_id int,
type VARCHAR(255),
comment_id int null
)";

if ($conn->query($sql) === TRUE) {

} else {
echo "Error creating table: " . $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS comments (

comment_id INT primary key ,    
vid_id int,
comment varchar(500),
dat VARCHar(100)
)";

if ($conn->query($sql) === TRUE) {

} else {
echo "Error creating table: " . $conn->error;
}

    
?>