<?php
include_once '../php/_db.php';
if (isset($_POST['str_id']) && is_numeric($_POST['str_id'])) {
    $stmt = $db->prepare("SELECT locus, allele, `value`, value_str
        FROM str_profile WHERE str_id = ?
    ");
    $stmt->execute([$_POST['str_id']]);
    $str = $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);

    $stmt = $db->prepare("SELECT MAX(allele) 
        FROM str_profile WHERE str_id = ?
    ");
    $stmt->execute([$_POST['str_id']]);
    $str_count = $stmt->fetch(PDO::FETCH_COLUMN);

    $result = array(
        'data' => $str,
        'count' => $str_count
    );

    echo json_encode($result);
}
