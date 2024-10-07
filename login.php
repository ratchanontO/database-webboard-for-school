<!DOCTYPE html>
<html lang="en">

<head>
<?php
        $checkrow = 99; // เอาไว้ตรวจสอบว่ามีข้อมูลหรือไม่
        $login_error = false; // ตัวแปรสำหรับเก็บสถานะการกรอกผิด
        
        session_start();
        
        if (!empty($_POST['username']) && !empty($_POST['password'])) {

            $username = $_POST['username'];
            $password = $_POST['password'];
            $servername = "localhost";
            $db_username = "root";
            $db_password = "";
            $dbname = "webboardd";
            
            $conn = new mysqli($servername, $db_username, $db_password, $dbname);
            
            // ตรวจสอบการเชื่อมต่อ
            if ($conn->connect_error) {
                die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
            }

            $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $check->bind_param("s", $username);
            $check->execute();
            $check = $check->get_result();

            if ($check->num_rows > 0){
                $check = $check->fetch_assoc();
                if (password_verify($password, $check['password'])){
                    $_SESSION['username'] = $check['username'];
                    $_SESSION['userid'] = $check['userid'];
                    $_SESSION['privilege'] = $check['privilege'];
                } else {
                    $login_error = true; // ตั้งค่าเป็น true ถ้ารหัสผ่านผิด
                }
            } else {
                $login_error = true; // ตั้งค่าเป็น true ถ้าชื่อผู้ใช้ไม่ถูกต้อง
            }
        } 
        if (isset($_POST['main'])){
            header("Location: index.php"); 
        }
    ?>

    <meta charset="UTF-8">
    <link rel = "stylesheet" href = "stylee.css?v=123332d4333ddd422" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "<3 " ?>RMUTI_DRAMA</title>

    <div class="header">
        <div class="site-name">RMUTI_DRAMA
            <form method="POST">
                <button class="" type="submit" name="main">ย้อนกลับ</button>
            </form>
        </div>
    </div>

</head>

<body>
    <div class="boxtop">
        <?php
        if (isset($_SESSION['username'])) {
        ?>
            <div class="headlogin">
                <h2>Welcome</h2>
                <h2><?php echo $_SESSION['username']; ?></h2>
            </div>
            <?php
            header("Refresh: 3; URL=main.php");
            exit();
        } else {
        ?>
            <div class="headlogin">
                <h2>Login</h2>
            </div>

            <form action="login.php" method="post" role="form" class="<?php if ($login_error) echo 'shake'; ?>">
                <div class="input-group">
                    <label for="username">ชื่อผู้ใช้:</label>
                    <input type="text" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">รหัสผ่าน:</label>
                    <input type="password" name="password" required>
                </div>

                <?php
                if ($login_error) {
                ?>
                    <h5 class="error-msg">"ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง"</h5>
                    <!-- aเมื่อกรอกข้อมูลผิด -->
                    <br>
                    <img src="images/tom.jpg" alt="tom" width="90" height="90">
                <?php
                }
                ?>
                <button type="submit" name="submit">เข้าสู่ระบบ</button>
            </form>
        <?php 
        }
        ?>
    </div>

</body>
</html>