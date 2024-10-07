<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            session_start();
            
            $servername = "localhost";
            $db_username = "root";
            $db_password = "";
            $dbname = "webboardd";
            $conn = new mysqli($servername, $db_username, $db_password, $dbname);
            
            date_default_timezone_set('Asia/Bangkok'); 
            mysqli_query($conn, "set NAMES utf8");
            mysqli_query($conn, "set character set utf8");
            mysqli_set_charset($conn, "utf8");

            // เอาไว้ถามความเเน่ใจว่าจะลบมั้ย
            $rucomfirm = 0;

            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                $useridlogin = $_SESSION['userid'];
                $privilege = $_SESSION['privilege'];
            }
            else{
                header("Refresh: 3; URL=login.php ");
            }

            if (isset($_POST['main'])){
                header("Location: main.php"); 
            }

            if (isset($_POST['logout'])) {
                session_destroy(); 
                header("Location: login.php"); 
                exit(); 
            }

            if(isset($_POST['check'])){
                $rucomfirm = 1;
            }

            if(isset($_POST['cancel'])){
                $rucomfirm = 0;
            }

            if(isset($_POST['deleteuser'])){
                // ลบคอมเม้นของuserนั้น
                $deletecomments = $conn->prepare("DELETE FROM comments  WHERE userid = ?");
                $deletecomments->bind_param("i", $_POST["userid"]);
                $deletecomments->execute();
                // ลบโพสต์ของuserนั้น
                $deletepost = $conn->prepare("DELETE FROM posts WHERE userid = ?");
                $deletepost->bind_param("i", $_POST["userid"]);
                $deletepost->execute();
                // ลบuser
                $userid=$_POST['userid'];
                $deleteuser = $conn->prepare('DELETE FROM users WHERE userid = ?');
                $deleteuser->bind_param("i", $_POST["userid"]);
                $deleteuser->execute();
            }
        ?>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>RMUTI_DRAMA</title>
        <link rel="stylesheet" href="stylemain.css?v=<?=time()?>">

        <div class="header">
            <div class="site-name">RMUTI_DRAMA
                <form method="POST">
                    <button class="" type="submit" name="main">ย้อนกลับ</button>
                </form>
            </div>
            <div class="user-section">
                <span><?php echo htmlspecialchars($username); ?></span>
                <form method="POST">
                    <button type="submit" name="logout">LOGOUT</button>
                </form>
            </div>
        </div>

    </head>

    
    <body>
        <?php
            $getuser = $conn->query("SELECT u.userid, u.username, u.firstname, u.lastname, u.email, u.privilege  FROM users u ORDER BY u.userid;");
            if($getuser){
            while ($rowx = $getuser->fetch_assoc()) {
                $userid = $rowx['userid'];
                $username = $rowx['username'];
                $firstname = $rowx['firstname'];
                $lastname = $rowx['lastname'];
                $email = $rowx['email'];
                $privilege = $rowx['privilege'];
                if($privilege < 2)
                {
        ?>
        <br>
        <div class="post-item">
            <div class="post-header">
                <h3><?php echo $userid; ?> || <?php echo $username;?></h3>
                <h2>ชื่อ <?php echo $firstname; ?>  <?php echo $lastname;?></h2>
                <p><?php echo $email; ?></p>
            </div>
            <div class="post-actions">
                <form method="POST">
                    
                    <button type="submit" name="check" class="delete">Delete</button>
                </form>
                <?php
                if ($rucomfirm == 1){
                ?>
                <br>
                <span>คุณเเน่ใจที่จะลบ</span>
                <form method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                    <button type="submit" name="deleteuser" class="comfirm">COMFIRM</button>
                    <button type="submit" name="cancel" class="delete">CANCEL</button>
                </form>
                <?php
                    }
                ?>
            </div>
        </div>
        <br>
        <?php
                }
            }
        }
        ?>        

    </body>
</html>