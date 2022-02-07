<?php
    $servername = "localhost";
    $username = "app_admin";
    $password = "app@admin";
    $dbname = "masonry_gallery";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    $n=30;
    function genToken($n) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    if (isset($_POST) && !empty($_POST)) {
        $target_dir = "images/";
        $extension = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);
        $target_file = $target_dir . genToken($n).".".$extension;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        if(isset($_POST["submit"])) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $sql = "INSERT INTO uploads (user_id, image) VALUES ('".$_POST['userid']."', '".$target_file."')";
                if ($conn->query($sql) === FALSE) {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                    die();
                }
                echo "The file has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
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
    <h1>Upload</h1>
    <div class="topnav">
        <a href="/dashboard/">Dashboard</a>
        <a href="/profile/">Profile</a>
        <a class="active" href="/upload/">Upload</a>
        <a href="/logout/">Logout</a>
        <br/>
    </div> 
    <h2>Upload new image</h2>
    <div>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" id="userid" name="userid" value="<?php echo($userid); ?>"/>
            <label for="image">Image</label>
            <input type="file" id="fileToUpload" name="fileToUpload" accept="image/*">
            <input type="submit" value="Upload" name="submit">
        </form>
    </div>
    <script src="main.js"></script>
</body>
</html>