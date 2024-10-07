<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        // เอาไว้ถามว่าตกลงเเน่นะ
        $rucomfirm = false;
        
        // เชื่อมต่อกับฐานข้อมูล MySQL
        $servername = "localhost";
        $db_username = "root";
        $db_password = "";
        $dbname = "webboardd";
        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        date_default_timezone_set('Asia/Bangkok'); 
        mysqli_query($conn, "set NAMES utf8");
        mysqli_query($conn, "set character set utf8");
        mysqli_set_charset($conn, "utf8");
        
        session_start();
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            $useridlogin = $_SESSION['userid'];
            $privilege = $_SESSION['privilege'];
        }
        else{
            header("Location: login.php");
        }
        
        if (isset($_POST['logout'])) {
            session_destroy(); 
            header("Location: login.php"); 
            exit(); 
        }
        
        if (isset($_POST['delete_post'])) {
            $deletecomments = $conn->prepare("DELETE FROM comments WHERE postid = ?");
            $deletecomments->bind_param("i", $_POST["post_id"]);
            $deletecomments->execute();

            $delete = $conn->prepare("DELETE FROM posts WHERE postid = ?");
            $delete->bind_param("i", $_POST["post_id"]);
            $delete->execute();
        }

        if (isset($_POST['deletecomment'])) {
            $commentid = $_POST['comment_id'];
            $delete = $conn->prepare("DELETE FROM comments WHERE commentid = ?");
            $delete->bind_param("i", $commentid);
            $delete->execute();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['submit_post'])) {
                $time = date('Y-m-d H:i:s');
                $new_post = $_POST['textpost'];
                $insert = $conn->prepare("INSERT INTO posts (text, date, userid) VALUES (?, ?, ?)");
                $insert->bind_param("ssi", $new_post, $time, $useridlogin);
                $insert->execute();

            }    
        }

        if (!empty($_POST['comment'])) {
            $post_id = $_POST['post_id'];
            $comment = $_POST['comment'];
            $comment_time = date('Y-m-d H:i:s');
            $insert_comment = $conn->prepare("INSERT INTO comments (postid, userid, comment_text, comment_date) VALUES (?, ?, ?, ?)");
            $insert_comment->bind_param("iiss", $post_id, $useridlogin, $comment, $comment_time);
            $insert_comment->execute();

        }

        if (isset($_POST['checkcomfirm'])){
            $rucomfirm = true;
        }
        
        if (isset($_POST['cancel'])){
            $rucomfirm = false;
        }
    ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RMUTI_DRAMA</title>
    <link rel="stylesheet" href="stylemain.css?v=<?=time()?>">
</head>

    <div class="header">
        <div class="site-name">RMUTI_DRAMA</div>
        <div class="user-section">
            <span><?php echo htmlspecialchars($username); ?></span>
            <?php
                if ($privilege > 1){
            ?>
            <a  href="useredit.php">Edituser--</a>
            <?php
                }
            ?>
            <form method="POST">
                <button type="submit" name="logout">LOGOUT  </button>
            </form>
        </div>
    </div>

    <!-- สร้างโพสต์ -->
    <div class="post">
        <div class="">
            <div class="add-comment">
                <form method="POST">
                    <textarea type="text" name="textpost" placeholder="อ ย า ก ส ร้ า ง ด ร า ม่ า อ ะ ไ ร . . ." required></textarea><br>
                    <button type="submit" name="submit_post">Post</button>
                </form>
            </div>
        </div>
    </div>
    <br>

    <!-- แสดงโพสต์ทั้งหมด -->
    <div class="post-list">
        <?php
            $get_posts = $conn->query("SELECT p.postid, p.text, p.date, u.username, u.userid FROM posts p JOIN users u ON p.userid = u.userid ORDER BY p.date DESC");
            while ($row = $get_posts->fetch_assoc()) {
                $useridpost = $row['userid'];
                $post_id = $row['postid'];
                $post_text = htmlspecialchars($row['text']);
                $post_date = $row['date'];
                $post_user = htmlspecialchars($row['username']);
                
        ?>
        <div class="post-item">
            <div class="post-header">
                <h3><?php echo $post_user," <3"; ?></h3> <h4>เวลา <?php echo $post_date; ?></h4>
                <hr>
            </div>
            <div class="post-body">
                <p><?php echo $post_text; ?></p>
            </div>
            <div class="post-actions">
                <?php
                    if($privilege > 1 or $useridlogin == $useridpost){
                ?>
                    <form method="POST">
                        <a class="right" href="edit.php?post_id=<?=$post_id?>">Edit</a>
                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                        <button type="submit" name="checkcomfirm" class="del">Delete</button>
                        <br>
                        <br>
                        <?php
                        if ($rucomfirm){
                            ?>
                        
                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                        <button type="submit" name="cancel" class="del">CANCEL</button>
                        <button type="submit" name="delete_post" class="comfirmright">CONFIRM</button>
                        <?php
                        }
                        ?>
                    </form>
                <?php
                    }
                ?>
            <br>
            </div>

            <!-- แสดงคอมเมนต์ -->
            <div class="post-actions">
                <?php
                    $get_comments = $conn->prepare("SELECT c.commentid, c.comment_text, c.comment_date, u.username, u.userid FROM comments c JOIN users u ON c.userid = u.userid WHERE c.postid = ? ORDER BY c.comment_date DESC");
                    $get_comments->bind_param("i", $post_id);
                    $get_comments->execute();
                    $comments = $get_comments->get_result();
                    
                    while ($comment_row = $comments->fetch_assoc()) {
                        $comment_user = htmlspecialchars($comment_row['username']);
                        $comment_text = htmlspecialchars($comment_row['comment_text']);
                        $comment_id = $comment_row['commentid'];
                        $comment_date = $comment_row['comment_date'];
                        $userid = $comment_row['userid'];
                ?>
                <div class="comment-item">
                    <strong><?php echo $comment_user; ?></strong> - <span><?php echo $comment_date; ?></span>
                    <p><?php echo $comment_text; ?></p> 
                    <?php 
                    if($privilege > 1 or $useridlogin == $userid ){
                    ?>
                    <form method="POST">
                        <br>
                        <a class="delete" href="edit.php?comment_id=<?=$comment_id?>">Edit</a>
                        <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>">
                        <button type="submit" name="deletecomment" class="delete">Delete</button>
                    </form>
                    <?php     }
                    ?>           
                </div>
                <?php } ?>
            </div>

            <!-- เพิ่มคอมเมนต์ -->
            <div class="add-comment">
                <form method="POST">
                    <textarea name="comment" placeholder="คิดเห็นอย่างไรกับโพส์ตนี้ . . ." required></textarea>
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                    <button type="submit" name="submit_comment">Comment</button>
                </form>
            </div>
        </div>
        <br>
        <?php } ?>
    </div>

</body>
</html>
