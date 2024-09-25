<?php
// Include the TCPDF library
require_once('tcpdf/tcpdf.php');

$pdf=new TCPDF('L','mm','A4',true,'UTF-8', true );
$pdf->setprintHeader(false);
$pdf->setprintHeader(false);
$pdf->SetFont('times', 'BI', 20);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Fetch data from the database
$servername = "localhost";
$username = "root";
$password = "";
$database = "mydata";

// Create connection with database
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Read all rows from the database table
$sql = "SELECT * FROM clients";
$result = $connection->query($sql);

// Check if the query executed successfully
if (!$result) {
    die("Invalid query: " . $connection->error);
}

// Generate HTML content for the table
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 10, 'Sigma E Solution PVT. LTD. Employee List', 0, 1, 'C');
$pdf->SetFont('helvetica', 'B', 10);
$html = '
<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Department</th>
            <th>Email</th>
            <th>Phone No.</th>
            <th>Blood_Group</th>
            <th>Address</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>';

// Read data of each row
while ($row = $result->fetch_assoc()) {
    $pdf->SetFont('helvetica', '', 10);
    $html .= '<tr>
                <td>' . $row['id'] . '</td>
                <td>' . $row['name'] . '</td>
                <td>' . $row['department'] . '</td>
                <td>' . $row['email'] . '</td>
                <td>' . $row['phone'] . '</td>
                <td>' . $row['blood_group'] . '</td>
                <td>' . $row['address'] . '</td>
                <td>' . $row['created_at'] . '</td>
              </tr>';
}

$html .= '</tbody></table>';

// Print text using writeHTMLCell()
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('client_list.pdf', 'I'); // 'I' sends the PDF to the browser
?>
