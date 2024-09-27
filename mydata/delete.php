<?php
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "mydata";

    $connection = new mysqli($servername, $username, $password, $database);
 

    $sql = "SELECT upload_photo FROM clients WHERE id = $id";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $filePath = $row['upload_photo'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

    $sql = "DELETE FROM clients WHERE id = $id";
    $connection->query($sql);
    $data_path=$connection->query($sql);
    }
}
header("Location: /mydata/index.php");
exit;
