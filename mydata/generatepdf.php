<?php
require_once('tcpdf/tcpdf.php'); 

$servername = "localhost";
$username = "root";
$password = "";
$database = "mydata";

// Create a connection with the database
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM clients WHERE id = $id";
$result = $connection->query($sql);

// if client data exists
if ($result->num_rows > 0) {
    $client = $result->fetch_assoc();
    
    $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

    // Add a page
    $pdf->AddPage();

    $pdf->SetFont('helvetica', 'B', 25);
    $pdf->Cell(0, 10, 'Sigma E Solution PVT. LTD.', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 20);
    $pdf->Cell(0, 10, 'Employee Details', 0, 1, 'C');

    $xForText = 10;
    $xForImage = 230; 
    $yForImage = 60; 
    $imageWidth = 50; 
    $imageHeight = 50; 

    $pdf->SetXY($xForText, 40); 
    $pdf->SetFont('helvetica', '', 15);
    $html = "
    <hr>
    <p><strong>Employee ID:</strong> {$client['id']}</p>
    <p><strong>Name:</strong> {$client['name']}</p>
    <p><strong>Department:</strong> {$client['department']}</p>
    <p><strong>Email:</strong> {$client['email']}</p>
    <p><strong>Phone:</strong> {$client['phone']}</p>
    <p><strong>Blood Group:</strong> {$client['blood_group']}</p>
    <p><strong>Address:</strong> {$client['address']}</p>
    <p><strong>Issued on:</strong> {$client['created_at']}</p>
    <p></p>
    <hr>
    ";

    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, false, true, 'L', true);

    $client_photo =  $client['upload_photo'];
    
    if (file_exists($client_photo)) {
        $pdf->Image($client_photo, $xForImage, $yForImage, $imageWidth, $imageHeight,'JPG','', '', true);
    } else {
        $pdf->SetXY($xForImage, $yForImage);
        $pdf->Cell($imageWidth, $imageHeight, 'Photo Not Available', 1, 1, 'C');
    }

    // Output the generated PDF
    $pdf->Output('client_' . $client['id'] . '_details.pdf', 'I'); 
} else {
    echo "No client found with ID $id.";
}

// Close the database connection
$connection->close();
?>