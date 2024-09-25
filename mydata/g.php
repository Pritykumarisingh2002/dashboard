<?php
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "mydata";

    // Create a new database connection
    $connection = new mysqli($servername, $username, $password, $database);

    // Check for connection errors
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Use a prepared statement to avoid SQL injection
    $stmt = $connection->prepare("SELECT file_path FROM clients WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_path = $row['file_path'];  // Assuming `file_path` is the column where file paths are stored

        // Delete the file if it exists
        if (file_exists($file_path)) {
            echo 'Deleting File: ' . $file_path;
            unlink($file_path);
        } else {
            echo "File does not exist";
        }

        // Now delete the record from the database
        $delete_stmt = $connection->prepare("DELETE FROM clients WHERE id = ?");
        $delete_stmt->bind_param("i", $id);
        if ($delete_stmt->execute()) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $connection->error;
        }

        $delete_stmt->close();
    } else {
        echo "No record found with the given id";
    }

    $stmt->close();
    $connection->close();
}

// Redirect to the index page
header("Location: /mydata/index.php");
exit;
?>







<?php
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Database connection settings
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "mydata";

    // Create connection
    $connection = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Fetch the upload_photo file name before deletion
    $sql = "SELECT upload_photo FROM clients WHERE id = $id";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Delete the photo file from the upload_photo folder
        $filePath = 'upload_photo/' . $row['upload_photo'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Now delete the record from the database
        $sql_query = "DELETE FROM clients WHERE id = $id";
        if ($connection->query($sql_query) === TRUE) {
            echo "Deleted Successfully";
        } else {
            echo "Error deleting record: " . $connection->error;
        }
    } else {
        echo "No record found with ID: $id";
    }

    // Close the connection
    $connection->close();
}
?>
