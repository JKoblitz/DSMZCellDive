<?php

include_once "_db.php";
$cell_id = $_POST['cell_id'];
$type = $_POST['type'] ?? 'hist';

if ($type == 'bar') {
    $colors = array(
        "1" => "#7570b3",
        "2" => "#1b9e77",
        "3" => "#d95f02",
        "4" => "#e7298a",
        "5" => "#66a61e",
        "6" => "#e6ab02",
        "7" => "#a6761d",
        "8" => "#666666",
        "9" => "#1b9e77",
        "10" => "#d95f02",
        "11" => "#7570b3",
        "12" => "#e7298a",
        "13" => "#66a61e",
        "14" => "#e6ab02",
        "15" => "#a6761d",
        "16" => "#666666",
        "17" => "#1b9e77",
        "18" => "#d95f02",
        "19" => "#7570b3",
        "20" => "#e7298a",
        "21" => "#66a61e",
        "22" => "#e6ab02",
        "X" => "#a6761d",
        "Y" => "#666666",
        "MT" => "#555555"
    );
    // $query = $db->prepare(
    //     "SELECT external_gene_name AS `x`, chromosome_name AS `x2`, norm AS `y` 
    //     FROM ngs LEFT JOIN genes USING (gene_id) 
    //     WHERE cell_id = ? AND norm != 0 
    //     ORDER BY chromosome_name, gene_start_position ");
    // $query->execute([$cell_id]);
    // $table = $query->fetchAll(PDO::FETCH_ASSOC);
    // $result =array('x' => array(array_column($table, 'x2'), array_column($table, 'x')), 'y' => array_map("floatval", array_column($table, 'y')));

    $query = $db->prepare(
        "SELECT chromosome_name AS `x2`, external_gene_name AS `x`, norm AS `y` 
        FROM ngs LEFT JOIN genes USING (gene_id) 
        WHERE cell_id = ? AND norm != 0 
        ORDER BY chromosome_name, gene_start_position "
    );
    $query->execute([$cell_id]);
    $table = $query->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
    $result = array();
    // $result =array('x' => array_column($table, 'x'), 'y' => array_map("floatval", array_column($table, 'y')), 'group' => array_column($table, 'x2'));
    foreach ($table as $group => $data) {
        $i = count($result);
        $result[] = array('x' => array(), 'y' => array(), 'type' => 'bar', 'name'=>$group, 'marker' => array('color' => $colors[$group]));
        foreach ($data as $row) {
            array_push($result[$i]['x'], $row['x']);
            array_push($result[$i]['y'], $row['y']);
        }
    }
} else {
    $query = $db->prepare("SELECT norm FROM ngs WHERE cell_id = ? AND norm != 0");
    $query->execute([$cell_id]);
    $result = $query->fetchAll(PDO::FETCH_COLUMN);
}

echo json_encode($result);
