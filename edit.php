<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    session_start();
    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
        $useridlogin = $_SESSION['userid'];
        $privilege = $_SESSION['privilege'];
    }
    else{
        session_destroy();
        header("Refresh: 3; URL=login.php ");
    }
        $servername = "localhost";
        $db_username = "root";
        $db_password = "";
        $dbname = "webboardd";
        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        mysqli_query($conn, "set NAMES utf8");
        mysqli_query($conn, "set character set utf8");
        mysqli_set_charset($conn, "utf8");

        $postid = @$_REQUEST['post_id'];
        $commentid = @$_REQUEST['comment_id'];

        if (isset($_POST['editpost'])){
            echo $_POST['editpost'];
            $editposts = $conn->prepare("UPDATE posts SET text = ? WHERE postid = ?");
            $editposts->bind_param("si", $_POST['editpost'], $postid);
            $editposts->execute();
            header("Location: main.php"); 

            
        }
        if (isset($_POST['editcommentbutton'])){
            $editcomment = $conn->prepare("UPDATE comments SET comment_text = ? WHERE commentid = ?");
            $editcomment->bind_param("si", $_POST['editcomment'], $commentid);
            $editcomment->execute();
            header("Location: main.php"); 

        }
        if (isset($_POST['main'])){
            header("Location: main.php"); 
        }
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RMUTI_DRAMA <?=$postid?></title>
    <link rel="stylesheet" href="stylemain.css?v=<?=time()?>">
    
    <div class="header">
        <div class="site-name">RMUTI_DRAMA
            <form method="POST">
                <button class="" type="submit" name="main">ย้อนกลับ</button>
            </form>
        </div>
        
        <div class="user-section">
            <a  href="useredit.php">Edituser</a>
            <span><?php echo htmlspecialchars($username); ?></span>
            <form method="POST">
                <button type="submit" name="logout">LOGOUT</button>
            </form>
        </div>
    </div>
    <br>
</head>
<body>
    <?php
        if(isset($postid)){
            $get_posts = $conn->prepare("SELECT p.postid, p.text, p.date, u.username, u.userid FROM posts p JOIN users u ON p.userid = u.userid WHERE p.postid = ?  ORDER BY p.date DESC");
            $get_posts->bind_param("i", $postid);

            $get_posts->execute();
            $get_posts = $get_posts->get_result();
            $get_posts = $get_posts->fetch_assoc();
            $useridpost = $get_posts['userid'];
            $post_id = $get_posts['postid'];
            $post_text = htmlspecialchars($get_posts['text']);
            $post_date = $get_posts['date'];
            $post_user = htmlspecialchars($get_posts['username']);
            
        ?>
        <div class="post-item">
            <div class="post-header">
                <h3><?php echo $post_user," <3"; ?></h3> เวลา <span><?php echo $post_date; ?></span>
            </div>
            <div class="post-body">
                <p><?php echo $post_text; ?></p>
            </div>
            <br>
            <span>แก้ไข้โพส์ต</span>
            <div class="add-comment">
                <form method="POST">
                    <textarea name="editpost" placeholder="E D I T P O S T . . ." required></textarea>
                    <button type="submit" name="editpostbutton" class="delete">EDIT POST</button>
                </form>
            </div>
        <?php
            }
        ?>

        <!-- คอมเม้น -->
        <?php
        if(isset($commentid)){
            $get_comment = $conn->prepare("SELECT c.commentid, c.comment_text, c.comment_date, u.username, u.userid FROM comments c JOIN users u ON c.userid = u.userid WHERE c.commentid = ? ORDER BY c.comment_date DESC");
            $get_comment->bind_param("i", $commentid);

            $get_comment->execute();
            $get_comment = $get_comment->get_result();
            $get_comment = $get_comment->fetch_assoc();
            $username = $get_comment["username"];
            $commentdate = $get_comment["comment_date"];
            $commenttext = $get_comment["comment_text"];
            
        ?>
        <div class="post-item">
            <div class="post-header">
                <h3><?php echo $username," <3"; ?></h3> เวลา <span><?php echo $commentdate; ?></span>
            </div>
            <div class="post-body">
                <p><?php echo $commenttext; ?></p>
            </div>
            <br>
            <span>แก้ไข้โพส์ต</span>
            <div class="add-comment">
                <form method="POST">
                    <textarea name="editcomment" placeholder="E D I T C O M M E N T . . ." required></textarea>
                    <button type="submit" name="editcommentbutton" class="delete">EDIT COMMENT</button>
                </form>
            </div>
        <?php
            }
        ?>



    <div class="post-list">
            
    </div> 

    
</body>
</html>