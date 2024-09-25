<?php

$id = "";
$name = "";
$department = "";
$email = "";
$phone = "";
$blood_group = "";
$address = "";
$upload_photo = "";

$errorMessage = "";
$successMessage = "";

$servername = "localhost";
$username = "root";
$password = "";
$database = "mydata";

$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Get the client's ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve the existing client details from the database
    $sql = "SELECT * FROM clients WHERE id = $id";
    $result = $connection->query($sql);

    $row = $result->fetch_assoc();
    $name = $row["name"];
    $department = $row["department"];
    $email = $row["email"];
    $phone = $row["phone"];
    $blood_group = $row["blood_group"];
    $address = $row["address"];
    $upload_photo = $row["upload_photo"];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST["name"];
    $department = $_POST["department"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $blood_group = $_POST["blood_group"];
    $address = $_POST["address"];

    if (isset($_FILES['upload_photo']) && $_FILES['upload_photo']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['upload_photo']['name'];
        $file_tmp = $_FILES['upload_photo']['tmp_name'];

        // Move the uploaded file to the desired location
        $upload_dir = "upload_photo/";
        $file_path = $upload_dir . basename($file_name);

        if (move_uploaded_file($file_tmp, $file_path)) {
            $upload_photo = $file_path; // Save the file path for database insertion
        } else {
            $errorMessage = "Failed to upload the photo.";
        }
    }

    if (empty($name) || empty($department) || empty($email) || empty($phone) || empty($blood_group) || empty($address)) {
        $errorMessage = "All fields are required.";
    } else {
        // Update the client details in the database
        $sql = "UPDATE clients SET name='$name', department='$department', email='$email', phone='$phone', blood_group='$blood_group', address='$address', upload_photo='$upload_photo' WHERE id=$id";

        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
        } else {
            $successMessage = "Client updated successfully.";

            // Redirect to index.php after success
            header("location: /mydata/index.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container my-5">
        <h2>Edit Employee's details</h2>
        <br>

        <?php
        if (!empty($errorMessage)) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
            <strong>$errorMessage</strong>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='close'></button>
            </div>
            ";
        }
        ?>

        <form method="post" enctype="multipart/form-data">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Department</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="department" value="<?php echo $department; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="email" value="<?php echo $email; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Phone No.</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Blood Group</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="blood_group" value="<?php echo $blood_group; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Address</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="address" value="<?php echo $address; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Upload New Photo (optional)</label>
                <div class="col-sm-6">
                    <input type="file" class="form-control" name="upload_photo" accept=".jpg, .jpeg, .png">
                    <?php if ($upload_photo) : ?>
                        <img src="<?php echo $upload_photo; ?>" alt="Client Photo" style="width: 100px; height: 100px; margin-top: 10px;">
                    <?php endif; ?>
                </div>
            </div>

            <br>

            <?php
            if (!empty($successMessage)) {
                echo "
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>$successMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='close'></button>
                </div>
                ";
            }
            ?>

            <div class="row mb-3">
                <div class="col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/mydata/index.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>