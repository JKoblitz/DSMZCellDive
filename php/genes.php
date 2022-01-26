<?php

include_once "_db.php";

$n = 10;

$search = filter_var($_GET['term'], FILTER_SANITIZE_STRING) ?? '';
$search = trim($search);
$sql = "SELECT CONCAT(external_gene_name , IF(description_short IS NULL, '', CONCAT(' (' , `description_short` , ')'))) as `tag`, external_gene_name as `value`
    FROM genes 
    WHERE external_gene_name LIKE ? LIMIT $n";
$values = array($search.'%');

$query = $db->prepare($sql);
$query->execute($values);
$result = $query->fetchAll(PDO::FETCH_ASSOC);
// echo count($result);

if (count($result) < $n ){
    $n = $n - count($result);
    $sql = "SELECT CONCAT(external_gene_name , ' (' , `description_short` , ')') as `tag`, external_gene_name as `value`
    FROM genes 
    WHERE description_short LIKE ? AND description_short IS NOT NULL LIMIT $n";
    $values = array('%'.$search.'%');
    $query = $db->prepare($sql);
    $query->execute($values);
    $result = array_merge($result, $query->fetchAll(PDO::FETCH_ASSOC));
}
echo json_encode(array("suggestions"=> $result), JSON_NUMERIC_CHECK);
