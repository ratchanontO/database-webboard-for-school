<!DOCTYPE html>
<html lang="en">
    <head>

        <?php
        $status = 99;
        $erroreng = false;

        if(!empty($_POST["username"]))
        {
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $firstname = $_POST["firstname"];
            $lastname = $_POST["lastname"];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            //dbนั้นเเหละ
            $servername = "localhost";
            $db_username = "root";
            $db_password = "";
            $dbname = "webboardd";

            $conn = new mysqli($servername, $db_username, $db_password, $dbname);
            date_default_timezone_set('Asia/Bangkok');
            mysqli_query($conn,"set NAMES utf8");
            mysqli_query($conn,"set character set utf8");
            mysqli_set_charset($conn,"utf8");

            function isValidUsername($username) 
            {
                return preg_match('/^[a-zA-Z0-9]+$/', $username);
            }
            
                if (isValidUsername($username)) 
                {
                    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? or email = ?");
                    $stmt->bind_param("ss", $username, $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0)
                    {
                        $status = 1;
                    }
                    else{
                        $insert = $conn->prepare("INSERT INTO users (username, password, firstname, lastname, email, privilege) VALUES (?, ?, ?, ?, ?,1)");
                        $insert->bind_param("sssss",$username, $hashed_password, $firstname, $lastname, $email );
                        $insert->execute();
                        $status = 0; 
                        
                    }
                }       
                else
                {
                    $erroreng = true;
                }
        }
        if (isset($_POST['main'])){
            header("Location: index.php"); 
        }
        ?> 
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>RMUTI_DRAMA</title>
        <link rel = "stylesheet" href = "registerx.css?v=1dd2sdddd2" >
        
        <div class="header">
            <div class="site-name">RMUTI_DRAMA
                <form method="POST">
                    <button class="" type="submit" name="main">ย้อนกลับ</button>
                </form>
            </div>
        </div>
    </head>
    
    
    <body>
        <br>
        <div class="boxtop">
            <div class="headlogin">
                <h2>สมัครสมาชิก</h2>
            </div>
            <form action="register.php" method="post" role="form">
                <div class="input-group">
                    <label for="firstname">ชื่อ:</label>
                    <input type="text" id="firstname" name="firstname" required>
                </div>
                <div class="input-group">
                    <label for="lastname">นามสกุล:</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>
                <div class="input-group">
                    <label for="email">อีเมล:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="username">USERNAME:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">รหัสผ่าน:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <?php
                if ($erroreng) {
                    echo "<p class='error-msg'>USERNAME จำเป็นต้องเป็นภาษาอังกฤษ</p>";
                }
                if ($status == 1) {
                    echo "<p class='error-msg'>USERNAME หรือ EMAILL นี้ถูกใช้ไปเเล้ว</p>";
                    header("Refresh: 3; URL=register.php");
                } else if ($status == 0) {
                    echo "<p class='success-msg'>สมัครสมาชิกเรียบร้อย</p>";
                    header("Refresh: 3; URL=login.php");
                }
                ?>
                
                <button type="submit">สมัครสมาชิก</button>
            </form>
        </div>
    </body>
</html>
