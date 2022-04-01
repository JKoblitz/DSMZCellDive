<?php

include_once "_db.php";
include_once "_config.php";

$genes = $_POST['genes'] ?? 'CD47';
$matrix = $_POST['matrix'] ?? 'norm';
$multigene = $_POST['multigene'] ?? 'gene';
// $project = $_POST['project'] ?? 1;
$plottype = $_POST['type'] ?? 'bar';

$result = array('msg' => array(), 'data' => array(), 'genes' => array());
$values = array();


$where = array();

$gene_ids = array();
foreach ($genes as $gene) {
    if (count($gene_ids) >= 5 && $plottype == 'bar') {
        array_push($result['msg'], "Only 5 genes are supported in bar charts.");
        break;
    }
    
    $g_id = gene_id($gene);

    if ($g_id === false) {
        array_push($result['msg'], "Gene $gene could not be found.");
    } else {
        array_push($gene_ids, $g_id);
        array_push($result['genes'], $gene);
    }  
}

if (empty($gene_ids)) {
    array_push($result['msg'], 'No genes found, nothing to be done.');
    echo json_encode($result, JSON_NUMERIC_CHECK);
    die();
} elseif (count($gene_ids) === 1) {
    array_push($where, 'gene_id = ?');
    array_push($values, $gene_ids[0]);
    $result['multi'] = 'no';
} else {
    $placeholder = implode(',', array_fill(0, count($gene_ids), '?'));
    array_push($where, "gene_id IN ( $placeholder )");
    $values = array_merge($values, $gene_ids);
}


// build sql query:
$sql = "SELECT ";

if ($plottype == 'bar') {
    $sql .= "tumour_short";
} else {
    $sql .= "external_gene_name";
}

$sql .= " AS `group`, ";

if (count($gene_ids) === 1) {
    $sql .= " cellline";
} elseif ($multigene == 'gene') {
    $sql .= " CONCAT(cellline , ':' , external_gene_name)";
} else {
    $sql .= " CONCAT(external_gene_name , ':' , cellline)";
}

$sql .= " AS `key`, `$matrix` AS `value`";

$sql .= " FROM celllines";
$sql .= " LEFT JOIN ngs USING (cell_id)";

if (count($gene_ids) > 1 || $plottype == 'heat') {
    $sql .= " LEFT JOIN genes USING (gene_id)";
}


if (isset($_POST['celllines']) && !empty($_POST['celllines'])) {
    $cells = $_POST['celllines'];
    $placeholder = implode(',', array_fill(0, count($cells), '?'));
    array_push($where, "cellline IN ( $placeholder )");
    $values = array_merge($values, $cells);
    // echo $sql;
} elseif (isset($_POST['groups']) && !empty($_POST['groups'])) {
    $groups = $_POST['groups'];
    $placeholder = implode(',', array_fill(0, count($groups), '?'));
    array_push($where, "tumour_short IN ( $placeholder )");
    $values = array_merge($values, $groups);
}

$sql .= " WHERE " . implode(" AND ", $where);

// echo $sql;
$query = $db->prepare($sql);
$query->execute(array_map("trim", $values));
$table = $query->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);

$result['data'] = $table;
$result['colors'] = cellcolors();

echo json_encode($result, JSON_NUMERIC_CHECK);


function gene_id($gene)
{
    global $db;
    $sql = "SELECT gene_id FROM genes WHERE external_gene_name LIKE ? LIMIT 1";
    $query = $db->prepare($sql);
    $query->execute([$gene]);
    $gene_id = $query->fetch(PDO::FETCH_COLUMN);
    return $gene_id;
}
