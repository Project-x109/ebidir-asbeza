<?php
include "../connect.php";
session_start();
if (isset($_POST['add_user'])) {

    $filename = $_FILES["profile"]["name"];
    $tempname = $_FILES["profile"]["tmp_name"];
    $date = date("s-h-d-m-Y");
    $folder = "../images/" . $date . $filename;

    // Now let's move the uploaded image into the folder: image
    $sql = "INSERT INTO `users`(`name`, `dob`, `phone`, `password`, `role`,`TIN_Number`,`profile`) 
    VALUES ('$_POST[name]','$_POST[dob]','$_POST[phone]','123','user','$_POST[TIN_Number]','$folder')";
    $res = $conn->query($sql);
    if (move_uploaded_file($tempname, $folder)) {
        echo "<h3>  Image uploaded successfully!</h3>";
    } else {
        echo "<h3>  Failed to upload image!</h3>";
    }
    $_SESSION['success'] = "User created Successfully";

    echo $_SESSION['success'];
    // header("location:addusers.php");"
    echo "<script>document.location='addusers.php'</script>";
}
echo "<script>alert(" . $_SESSION['success'] . ")</script>";
