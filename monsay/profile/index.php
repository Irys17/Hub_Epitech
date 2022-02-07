<?php
    $servername = "localhost";
    $username = "app_admin";
    $password = "app@admin";
    $dbname = "masonry_gallery";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_POST) && !empty($_POST)) {
        if (empty($_POST['password'])) {
            echo("Please enter password.");
        } else {
            $sql = "UPDATE users SET email='".$_POST['email']."', password='".$_POST['password']."', otp=FALSE WHERE id='".$_POST['userid']."'";
            if ($_POST["otp"] === "on") {
                $sql = "UPDATE users SET email='".$_POST['email']."', password='".$_POST['password']."', otp=TRUE WHERE id='".$_POST['userid']."'";
            }
            if ($conn->query($sql) === TRUE) {
                echo "Account updated successfully!!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
                die();
            }
        }
    }

    if (isset($_COOKIE["token"]) && !empty($_COOKIE["token"])) {
        $token = $_COOKIE["token"];
        $sql = "SELECT * FROM users WHERE token='".$token."'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userid = $row["id"];
            $email = $row["email"];
            $otps = $row["otp"];
            $conn->close();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();
    } else {
        $newURL = "/login/";
        header('Location: '.$newURL);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles.css">
    <title>Masonry Layout con CSS Grid</title>
</head>
<body>
    <h1>Profile</h1>
    <div class="topnav">
        <a href="/dashboard/">Dashboard</a>
        <a class="active" href="/profile/">Profile</a>
        <a href="/upload/">Upload</a>
        <a href="/logout/">Logout</a>
        <br/>
    </div> 
    <h2>Welcome <?php echo($email); ?></h2>
    <div>
        <form action="" method="post">
            <input type="hidden" id="userid" name="userid" value="<?php echo($userid); ?>"/>
            <div class="email">
            <label for="email">Email Address</label>
            <div class="sec-2">
                <ion-icon name="mail-outline"></ion-icon>
                <input type="email" id="email" name="email" value="<?php echo($email) ?>" placeholder="Username@gmail.com"/>
            </div>
            </div>
            <div class="password">
            <label for="password">Password</label>
            <div class="sec-2">
                <ion-icon name="lock-closed-outline"></ion-icon>
                <input class="pas" type="password" id="password" name="password" placeholder="············"/>
                <ion-icon class="show-hide" name="eye-outline"></ion-icon>
            </div>
            </div>
            <div class="password">
                <label for="password">OTP</label>
                <div class="sec-2">
                    <?php
                        if (!$otps) { echo('<input type="checkbox" id="otp" name="otp"/>'); }
                        else { echo('<input type="checkbox" id="otp" name="otp" checked="checked"/>'); }
                    ?>
                </div>
            </div>
            <button class="login">Update </button>
        </form>
    </div>
    <script src="main.js"></script>
</body>
</html>