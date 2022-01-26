<?php

include_once "_db.php";

$dir = "temp/";

$csv = array();

$result = array('msg' => array(), 'id' => false, 'genes' => array(), 'data' => null);

$project = $_POST['project'] ?? 1;
// $gene = "FGR,CFH,FUCA2,GCLC,NFYA,STPG1,NIPAL3";
// $tumour = "AML_myelo,CML_myelo_BC";

$matrix = $_POST['matrix'] ?? 'norm';
$multigene = $_POST['multigene'] ?? 'gene';

switch ($multigene) {
    case 'gene':
        $dendro = 'row';
        break;

    case 'cell':
        $dendro = 'column';
        break;

    default:
        $dendro = 'none';
        break;
}
// $dendro = $multigene == 'gene' ? 'row' : 'column';

if (!isset($_POST['genes']) || count($_POST['genes']) < 2) {
    array_push($result['msg'], "Please select at least two genes.");
    echo json_encode($result, JSON_NUMERIC_CHECK);
    die();
}
// $genes = explode(",", $gene);
$genes =  $_POST['genes'];
$placeholder = implode(',', array_fill(0, count($genes), '?'));
$sql = "SELECT gene_id, external_gene_name FROM genes WHERE external_gene_name IN ($placeholder)";
$query = $db->prepare($sql);
$query->execute($genes);
$genes = $query->fetchAll(PDO::FETCH_KEY_PAIR);

$result['genes'] = array_values($genes);

if (count($result['genes']) !== count($_POST['genes'])) {
    array_push($result['msg'], "The following genes could not be found in the database: " . implode(', ', array_diff($result['genes'], $_POST['genes']))) . ".";
}

if (count($genes) < 2) {
    array_push($result['msg'], "Please select at least two valid genes.");
    echo json_encode($result, JSON_NUMERIC_CHECK);
    die();
}


// $tumours = explode(",", $tumour);

if (isset($_POST['celllines']) && !empty($_POST['celllines'])) {
    $tumours = $_POST['celllines'];
    $where = "cellline";
    // echo $sql;
} elseif (isset($_POST['groups']) && !empty($_POST['groups'])) {
    $tumours = $_POST['groups'];
    $where = "tumour_short";
} else {
    array_push($result['msg'], "Please select at least one cell line entity.");
    echo json_encode($result, JSON_NUMERIC_CHECK);
    die();
}

$placeholder = implode(',', array_fill(0, count($tumours), '?'));
$sql = "SELECT cell_id, CONCAT(tumour_short, ':', cellline) FROM celllines WHERE `$where` IN ( $placeholder ) ORDER BY tumour_short";
$query = $db->prepare($sql);
$query->execute($tumours);
$cells = $query->fetchAll(PDO::FETCH_KEY_PAIR);

$row1 = ['GENE'];
$row2 = ['GROUP'];
foreach ($cells as $id => $cell) {
    $cell = explode(':', $cell, 2);
    array_push($row1, $cell[1]);
    array_push($row2, $cell[0]);
}
array_push($csv, $row1);
array_push($csv, $row2);

foreach ($genes as $id => $gene) {
    $sql = "SELECT cell_id, `$matrix` FROM ngs WHERE gene_id = ?";
    $query = $db->prepare($sql);
    $query->execute([$id]);
    $table = $query->fetchAll(PDO::FETCH_KEY_PAIR);

    $row = [$gene];
    $rowsum = 0;
    foreach ($cells as $id => $cell) {
        $rowsum += floatval($table[$id]);
        array_push($row, $table[$id]);
    }
    if ($rowsum > 0.00001) {
        array_push($csv, $row);
    } else {
        array_push($result['msg'], "Gene $gene showed no expression in the selected cell lines.");
    }
}

$id = rand(1000, 9999);

$filename = $dir . "file_$id.csv";
$fp = fopen($filename, 'w');
if ($fp !== false) {

    foreach ($csv as $fields) {
        fputcsv($fp, $fields);
    }

    fclose($fp);

    chmod($filename, 0755);

    shell_exec('Rscript heatmap.R ' . $id . ' ' . $dendro);

    $jsonfile = 'temp/heatmap_' . $id . '.json';
    if (is_file($jsonfile)) {
        $json = file_get_contents($jsonfile);
        $result['id'] = $id;
        $result['data'] = json_decode($json);
    } else {
        array_push($result['msg'], "Something went wrong. Please check your input parameters.");
        $result['id'] = '1246';
        $json = file_get_contents('test.json');
        $result['data'] = json_decode($json);
    }

    echo json_encode($result, JSON_NUMERIC_CHECK);

    /*** delete files older than 24 hours (86400 seconds)  ***/
    $now = time();
    foreach (glob($dir . "*") as $file) {
        if (is_file($file) && $now - filectime($file) > 86400) {
            unlink($file);
        }
    }
} else {
    array_push($result['msg'], "File could not be opened.");
    echo json_encode($result, JSON_NUMERIC_CHECK);
    die();
}
