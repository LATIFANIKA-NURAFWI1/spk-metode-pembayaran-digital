<?php
require_once 'database.php';
require_once 'Topsis.php';

header('Content-Type: application/json');

$customWeights = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['weights']) && is_array($input['weights'])) {
        $customWeights = $input['weights'];
    }
}

$topsis = new Topsis($pdo, $customWeights);
$hasil = $topsis->calculate();

echo json_encode($hasil);
?>
