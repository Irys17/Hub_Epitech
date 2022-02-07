<?php
    $servername = "localhost";
    $username = "app_admin";
    $password = "app@admin";
    $dbname = "masonry_gallery";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_COOKIE["token"]) && !empty($_COOKIE["token"])) {
        $token = $_COOKIE["token"];
        $sql = "SELECT * FROM users WHERE token='".$token."'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userid = $row["id"];
            $email = $row["email"];
            $sql = "SELECT * FROM uploads WHERE user_id='".$userid."'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $rows = $result->fetch_all(MYSQLI_ASSOC);
            }
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
    <h1>Masonry Gallery</h1>
    <div class="topnav">
        <a class="active" href="/dashboard/">Dashboard</a>
        <a href="/profile/">Profile</a>
        <a href="/upload/">Upload</a>
        <a href="/logout/">Logout</a>
        <input type="text" placeholder="Search..">
        <br/>
    </div> 
    <div class="gallery" id="gallery">
        <?php
            foreach($rows as $row) {
                echo('<div class="gallery-item">
                <div class="content"><img src="/upload/'.$row["image"].'" alt=""></div>
            </div>');
            }
        ?>
    </div>
    <script src="main.js"></script>
</body>
</html>