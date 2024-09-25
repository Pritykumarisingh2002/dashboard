<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="container my-5">
        <h2>List of Employees</h2>
        <br>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <a class="btn btn-primary" href="/mydata/create.php" role="button">New Client</a>
            <a class="btn btn-primary" href="/mydata/generate.php" role="button">Generate File</a>
        </div>

        <form method="GET" action="">
            <div class="input-group rounded mb-3">
                <input type="search" class="form-control rounded" name="search_id" placeholder="Search by ID" aria-label="Search" aria-describedby="search-addon" value="<?php echo isset($_GET['search_id']) ? $_GET['search_id'] : '' ?>" />
                <span class="input-group-text border-0" id="search-addon">
                    <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Phone No.</th>
                        <th>Blood Group</th>
                        <th>Address</th>
                        <th>View Image</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $database = "mydata";

                    // Create connection with the database
                    $connection = new mysqli($servername, $username, $password, $database);

                    // Check connection
                    if ($connection->connect_error) {
                        die("Connection failed: " . $connection->connect_error);
                    }

                    $sql = "SELECT * FROM clients";

                    if (isset($_GET['search_id']) && !empty($_GET['search_id'])) {
                        $search_id = $connection->real_escape_string($_GET['search_id']);
                        $sql = "SELECT * FROM clients WHERE id=$search_id";
                    }

                    // Execute the query
                    $result = $connection->query($sql);

                    // Check if the query was successful
                    if (!$result) {
                        die("Invalid query: " . $connection->error);
                    }

                    // Check if there are any rows returned
                    if ($result->num_rows > 0) {
                        // Read data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['department']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['phone']}</td>
                                <td>{$row['blood_group']}</td>
                                <td>{$row['address']}</td>
                                <td><a class='fa fa-eye' href={$row['upload_photo']}></td>
                                <td>{$row['created_at']}</td>
                                <td><a class='btn btn-primary btn-sm' href='edit.php?id={$row['id']}'>Edit</a></td>
                                <td><a class='btn btn-danger btn-sm' href='delete.php?id={$row['id']}'>Delete</a></td>
                                <td><a class='btn btn-warning btn-sm' href='generatepdf.php?id={$row['id']}'>Generate</a></td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>Data not found</td></tr>";
                    }

                    // Close connection
                    $connection->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
</body>

</html>
