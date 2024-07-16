<?php
    session_start();
    if(!isset($_SESSION['loggedin'])) {
        $_SESSION['loggedin']=false;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>VidTube</title>
    <style>
        header.container{
            width: 100vw;
            display: flex;
            margin-bottom: 20px;
        }
        header {
            background: #0f1120a4; 
            color: #fc6161;
            position : fixed; 
            width: 100%;
            padding: 1rem 0;
            margin: -10px -6px;
            display: flex;
            z-index: 9999;
        }
        .container {
            width: 80%;
            margin-left: 10%;
            overflow: hidden;
            display: flex;
            flex-direction: row;
        }
        .left{
            display: flex;
        }
        .middle{
            align-items: center;
            margin-left: 10%;
            display: flex;
            width: 50%;
            flex-direction: row;
        }
        .right{
            display: flex;
            flex-direction: row;
            align-items: center;
            width: 30%;
            margin-left: 10%;
        }
        .rt_img{
            margin-left: 40%;
        }
        
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #012239, #5d2020);
        }
        .search_bar{
            width: 100%;
            max-width: 700px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            border-radius: 60px;
            padding: 10px 20px;
        }
        .search_bar input{
            background: transparent;
            flex: 1;
            border: 0;
            outline: none;
            padding: 8px 15px;
            font-size: 15px;
            color: #cac7ff;
        }
        .search_bar button{
            background: #58629b;
            border: 0;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
        }
        .modal {
            background: #fff;
            padding: 0px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 30%;
            max-width: 840px;
            margin : 10% 30%;
            align-self: center;
        }
        .modal form {
            display: flex;
            flex-direction: column;
            position: relative; 
            padding: 50px;
        }
        .modal input {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 30px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
            align-self: center;
        }
        .modal input:focus {
            border-color: #9b59b6;
            outline: none;
        }

        .modal button {
            padding: 10px;
            border: none;
            border-radius: 30px;
            background-color: #5d2020;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 40%;
            max-width: 400px;
        }
        .c_btn{
            margin-left: 90%;
            padding-top: 20px;
            
        }

        .modal button:hover {
            background-color: #9178ac;
        }

    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="left"><h1><i>Vid</i><sub>tube</sub></h1></div>
            <div class="middle">
                <form action="search.php" method="post" class="search_bar">
                    <input type="text" placeholder="Search" name="s_txt">
                    <button type="submit" class="s_btn"><i class="bi bi-search"></i>
                    </button>
                </form>
            </div> 
                <div class="right">
                    <?php if($_SESSION['loggedin']){?>
                        <div style="display:flex;flex-direction:row;">
                            <!-- <?php if(isset($_SESSION['ch_id']) &&  $_SESSION['ch_id']!=null) {?>-->
                            <!--<?php } ?> -->
                            <div><button class="btn" style="cursor:pointer"><img src="images/create_videos.png" alt=""  height="20" width="20"></button></div> 
                                <dialog class="modal" id="modal">
                                    <a href="" class="c_btn"> <i class="bi bi-x-octagon"></i></a>
                                    <form action="create_videos.php" method="post" enctype="multipart/form-data">
                                        <input  type="text" name="title" placeholder="Enter your video title" required> <br>
                                        <input style="height:5%" type="text" name="description"  placeholder="Add description"> <br>
                                        <input style="height:5%" type="text" name="type" placeholder="Add Genre/Type"> <br>
                                        <div style="display:flex; flex-direction:row; align-items:space-between">
                                            <p style="margin-right:15px">Thumbnail- </p>
                                            <input style="border:0" type="file" name="thumbnail" id="thumbnail" placeholder="Choose thumbnail" required>
                                        </div>
                                        <div style="display:flex; flex-direction:row; align-items:space-between">
                                            <p style="margin-right:15px"> Video-</p>
                                            <input  style="border:0"  type="file" name="video" placeholder="Upload video" required>
                                        </div>
                                        
                                        <button style="align-self:center" type="submit" name="upload">Upload</button>
                                    </form>
                                </dialog>  
                            </div>
                            <div class="rt_img"><a href="profile.html"><img src="<?php echo 'images/default_pp.jpg';?>" alt="" height="30" width="30"></a></div>
                        </div>
                    <?php }else { ?>
                        <div><a href="login.html" style="color:#fc6161"><i class="bi bi-person-badge"></i>Join now!</a> </div>
                    <?php }?> 
                </div>
            </div>
        </div>
    </header>                    

    <script>
        const modal=document.querySelector('#modal');
        const openModal=document.querySelector('.btn');
        const closeModal=document.querySelector('.c_btn');
        openModal.addEventListener('click',()=>{
            modal.showModal();
        })
        closeModalModal.addEventListener('click',()=>{
            modal.close();
        })
    </script>

    
</body>
</html>