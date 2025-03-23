<?php
session_start();
require 'dbconnect.php';
require('fpdf/fpdf.php'); // Include FPDF

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Learner') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch learner info
$user_query = $conn->prepare("SELECT name, surname FROM user_table WHERE user_id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();
$full_name = $user['name'] . ' ' . $user['surname'];

// Fetch enrolled courses with progress >= 40%
$course_query = $conn->prepare("SELECT c.course_title, l.progress FROM learning_table l 
    JOIN course_table c ON l.course_id = c.course_id WHERE l.user_id = ? AND l.progress >= 40");
$course_query->bind_param("i", $user_id);
$course_query->execute();
$course_result = $course_query->get_result();

$courses = [];
$total_progress = 0;
$course_count = 0;

while ($row = $course_result->fetch_assoc()) {
    $courses[] = $row;
    $total_progress += $row['progress'];
    $course_count++;
}

// Calculate average progress and remark
$average_progress = ($course_count > 0) ? ($total_progress / $course_count) : 0;
$remark = "Failure";
if ($average_progress > 80) $remark = "Very Good";
elseif ($average_progress > 60) $remark = "Good";
elseif ($average_progress > 40) $remark = "Pass";
elseif ($average_progress > 20) $remark = "Satisfactory";

// Current year
$completion_year = date("Y");

// Generate PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, 'Agribusiness Certificate', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 10, 'Online Agribusiness Center', 0, 1, 'C');
$pdf->Ln(10);
$pdf->Cell(190, 10, 'This certifies that', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, $full_name, 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 10, 'Has successfully completed the following courses:', 0, 1, 'C');
$pdf->Ln(5);

// List courses
$pdf->SetFont('Arial', '', 11);
foreach ($courses as $course) {
    $pdf->Cell(190, 8, "- " . $course['course_title'] . " (" . $course['progress'] . "%)", 0, 1, 'C');
}
$pdf->Ln(5);

// Completion year & remark
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, "Year of Completion: " . $completion_year, 0, 1, 'C');
$pdf->Cell(190, 10, "Remark: " . $remark, 0, 1, 'C');

// Output the PDF
$pdf->Output('D', 'Certificate.pdf'); // 'D' forces download
exit();
?>
