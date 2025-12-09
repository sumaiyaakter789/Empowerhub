<?php
session_start();
require('fpdf/fpdf.php');

if (!isset($_SESSION['id']) || !isset($_GET['course_id'])) {
    die("Unauthorized access.");
}

include("db_connection.php");
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['id'];
$course_id = $_GET['course_id'];

$sql = "
    SELECT c.title AS course_name, s.name AS student_name, i.name AS instructor_name
    FROM courses c
    JOIN signup s ON s.id = ?
    JOIN signup i ON i.id = c.instructor_id
    JOIN order_items oi ON oi.course_id = c.course_id
    JOIN orders o ON oi.order_id = o.id
    WHERE c.course_id = ? AND o.user_id = ? AND oi.status = 'completed';
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $course_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("No certificate available for this course.");
}

$details = $result->fetch_assoc();
$stmt->close();
$conn->close();

$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

$pdf->SetFillColor(245, 245, 245);
$pdf->Rect(0, 0, 297, 210, 'F');

$pdf->Image('certificate_border.png', 0, 0, 297, 210);
$pdf->Image('CLogo1-Photoroom.png', 115, 30, 70);

$pdf->SetFont('Times', 'B', 30);
$pdf->SetTextColor(50, 50, 54);
$pdf->Cell(0, 170, 'Certificate of Completion', 0, 1, 'C');

$pdf->SetFont('Times', '', 16);
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(-70);

$pdf->SetFont('Times', '', 16);
$pdf->MultiCell(0, 10, 'This certificate is proudly awarded to ', 0, 'C');

$pdf->SetFont('Times', 'B', 16);
$pdf->MultiCell(0, 10, $details['student_name'], 0, 'C');

$pdf->SetFont('Times', '', 16);
$pdf->MultiCell(0, 10, 'for successfully completing the course ', 0, 'C');

$pdf->SetFont('Times', 'B', 16);
$pdf->MultiCell(0, 10, $details['course_name'], 0, 'C');

$pdf->SetFont('Times', '', 16);
$pdf->MultiCell(0, 10, 'instructed by ', 0, 'C');

$pdf->SetFont('Times', 'B', 16);
$pdf->MultiCell(0, 10, $details['instructor_name'], 0, 'C');


$pdf->Ln(9);
$pdf->SetFont('Times', 'BI', 12);
$pdf->Cell(500, 10, 'EmpowerHub', 0, 1, 'C');

$pdf->Output("D", "certificate_{$course_id}.pdf");
?>
