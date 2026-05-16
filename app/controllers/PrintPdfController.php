<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
require_once __DIR__ . "/../CONFIGS/database.php";
require_once __DIR__ . "/../vendor/autoload.php";

use Dompdf\Dompdf;

$db = (new Database())->connect();

$id = $_GET['id'];

$stmt = $db->prepare("
    SELECT v.*, u.name AS host_name
    FROM visitors v
    LEFT JOIN users u ON v.host_staff_id = u.id
    WHERE v.id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$data = $stmt->get_result()->fetch_assoc();

if(!$data){
    die("Visitor not found");
}

$html = "
<h2 style='text-align:center;'>VISITOR BADGE</h2>
<hr>
<p><b>Name:</b> {$data['visitor_name']}</p>
<p><b>Contact:</b> {$data['contact']}</p>
<p><b>Purpose:</b> {$data['purpose']}</p>
<p><b>Host:</b> {$data['host_name']}</p>
<p><b>Check-in:</b> {$data['check_in']}</p>
";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A6', 'portrait');
$dompdf->render();
$dompdf->stream("visitor_badge.pdf", ["Attachment" => true]);