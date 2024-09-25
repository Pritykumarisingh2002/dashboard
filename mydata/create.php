<?php
// Initialize variables
$name = "";
$department = "";
$email = "";
$phone = "";
$blood_group = "";
$address = "";
$upload_photo = "";

$errorMessage = "";
$successMessage = "";

// Create connection with database
$servername = "localhost";
$username = "root";
$password = "";
$database = "mydata";

$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST["name"];
    $department = $_POST["department"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $blood_group = $_POST["blood_group"];
    $address = $_POST["address"];

    // if (empty($_POST["image"])) {
    //     $imageError = "";
    //     } else {
    //     $image = check_input($_POST["image"]);
    //     $allowed =  array('jpeg','jpg', "png", "gif", "bmp", "JPEG","JPG", "PNG", "GIF", "BMP");
    //     $ext = pathinfo($image, PATHINFO_EXTENSION);
    //     if(!in_array($ext,$allowed) ) {
    //     $imageError = "jpeg only";
    //     }
    //     }
        
    if (isset($_FILES['upload_photo']) && $_FILES['upload_photo']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['upload_photo']['name'];
        $file_tmp = $_FILES['upload_photo']['tmp_name'];
        $file_type = $_FILES['upload_photo']['type'];
        $file_size = $_FILES['upload_photo']['size'];

        // Move the uploaded file to the desired location
        $upload_dir = "upload_photo/";
        $file_path = $upload_dir . basename($file_name);

        if (move_uploaded_file($file_tmp, $file_path)) {
            $upload_photo = $file_path; // Save the file path for database insertion
            
        } else {
            $errorMessage = "Failed to upload the photo.";
        }
    }

    do {
        // Check if any field is empty
        if (empty($name) || empty($department) || empty($email) || empty($phone) || empty($blood_group) || empty($address) || empty($upload_photo)) {
            $errorMessage = "All fields are required.";
            break;
        }

        // Check if email already exists in the database
        $emailCheckSql = "SELECT * FROM clients WHERE email = '$email'";
        $emailCheckResult = $connection->query($emailCheckSql);

        if ($emailCheckResult && $emailCheckResult->num_rows > 0) {
            $errorMessage = "This email is already in use.";
            break;
        }

        // Add new client to the database
        $sql = "INSERT INTO clients (name, department, email, phone, blood_group, address, upload_photo) VALUES ('$name', '$department', '$email', '$phone', '$blood_group', '$address', '$upload_photo')";
        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
            break;
        }

        // Clear the form fields
        $name = "";
        $department = "";
        $email = "";
        $phone = "";
        $blood_group = "";
        $address = "";
        $upload_photo = "";

        $successMessage = "Client added successfully.";

        // Redirect to index.php after success
        // header("location: /mydata/index.php");
        // exit;
    } while (false);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container my-5">
        <h2>New Employee</h2>
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
                <label class="col-sm-3 col-form-label">Blood_Group</label>
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
                <label class="col-sm-3 col-form-label">Upload Photo</label>
                <div class="col-sm-6">
                    <input type="file" class="form-control" name="upload_photo" accept=".png, .jpg, .jpeg">
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
