<?php
if (!is_numeric($str_id)) {
    header("Location: " . ROOTPATH . "/str/browse?msg=wrong-id");
    die();
} elseif ($str_id != 0) {
    $stmt = $db->prepare("SELECT * FROM str_meta WHERE str_id = ? LIMIT 1");
    $stmt->execute([$str_id]);
    $meta = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($meta)) {
        header("Location: " . ROOTPATH . "/str/browse?msg=profile-does-not-exist");
        die();
    }
}
$keys = array();
$values = array();


if ($action == 'edit-meta') {
    // var_dump($_POST);
    $keys = array();
    $values = array();
    if (!isset($_POST['reference'])) {
        $_POST['reference'] = '0';
    }
    if (!isset($_POST['highlight'])) {
        $_POST['highlight'] = '0';
    }
    $relink = false;
    foreach ($_POST as $key => $value) {
        if ($value == "") {
            if ($key == 'cellline' || $key == 'ACC') {
                header("Location: " . ROOTPATH . "/str/edit/$str_id?msg=cellline-and-ACC-cannot-be-empty");
                die();
            }
            $value = null;
        }
        if ($value != $meta[$key]) {
            array_push($keys, "$key = ?");
            array_push($values, $value);
            if ($key == 'reference' || $key == 'ACC') {
                $relink = true;
            }
        }
    }
    if ($relink) {
        $cell_id = null;
        // cell_id might be updated
        if (is_numeric($_POST['ACC']) && $_POST['reference'] == '1') {
            // we need a new cell_id from the database
            $stmt = $db->prepare("SELECT cell_id FROM celllines WHERE cell_id = ?");
            $stmt->execute([$_POST['ACC']]);
            $cell_id = $stmt->fetch(PDO::FETCH_COLUMN);
            if (empty($cell_id)) {
                $cell_id = null;
            }
        }
        array_push($keys, "cell_id = ?");
        array_push($values, $cell_id);
    }
    // var_dump($keys, $values);

    if (!empty($values)) {
        $keys = implode(', ', $keys);
        array_push($values, $str_id);

        $stmt = $db->prepare("UPDATE str_meta SET $keys WHERE str_id = ?");
        $stmt->execute($values);
        header("Location: " . ROOTPATH . "/str/edit/$str_id?msg=metadata-updated");
        die();
    } else {
        header("Location: " . ROOTPATH . "/str/edit/$str_id?msg=nothing-to-be-done");
        die();
    }
} elseif ($action == 'edit-profile') {
    $stmt = $db->prepare("DELETE FROM str_profile WHERE str_id = ?");
    $stmt->execute([$str_id]);
    foreach ($_POST as $key => $value) {
        $key = explode('_', $key, 2);
        if (count($key) !== 2 || empty($value)) continue;
        // all profile entries have an underscore!
        $locus = $key[0];
        $allele = $key[1];
        $stmt = $db->prepare("INSERT INTO str_profile (str_id, locus, allele, `value`, `value_str`) VALUES (?,?,?,?,?)");
        $stmt->execute([$str_id, $locus, $allele, $value, $value]);
    }
    $db->exec('UPDATE str_meta LEFT JOIN (SELECT str_id, COUNT(*) as c from str_profile GROUP BY str_id) AS p USING (str_id) SET n_profile=c WHERE c IS NOT NULL');

    header("Location: " . ROOTPATH . "/str/edit/$str_id?msg=profile-updated");
    die();
} elseif ($action == 'add') {
    $reference = $_POST['reference'] ?? '0';
    if ($reference == '1' && is_numeric($_POST['ACC'])) {
        $stmt = $db->prepare("SELECT cell_id FROM celllines WHERE cell_id = ?");
        $stmt->execute([$_POST['ACC']]);
        $cell_id = $stmt->fetch(PDO::FETCH_COLUMN);
    } 
    
    if (empty($cell_id)){
        $cell_id = null;
    }

    $stmt = $db->prepare("INSERT INTO str_meta (ACC, cellline, cell_id, reference, `date`) VALUES (?,?,?,?, now())");
    $stmt->execute([$_POST['ACC'], $_POST['cellline'], $cell_id, $reference ?? 0]);
    $str_id = $db->lastInsertId();

    foreach ($_POST as $key => $value) {
        $key = explode('_', $key, 2);
        if (count($key) !== 2 || empty($value)) continue;
        // all profile entries have an underscore!
        $locus = $key[0];
        $allele = $key[1];
        $stmt = $db->prepare("INSERT INTO str_profile (str_id, locus, allele, `value`, `value_str`) VALUES (?,?,?,?,?)");
        $stmt->execute([$str_id, $locus, $allele, $value, $value]);
    }
    $db->exec('UPDATE str_meta LEFT JOIN (SELECT str_id, COUNT(*) as c from str_profile GROUP BY str_id) AS p USING (str_id) SET n_profile=c WHERE c IS NOT NULL');
  
    header("Location: " . ROOTPATH . "/str/edit/$str_id?msg=profile-added");
    die();
} elseif ($action == 'delete') {
    $stmt = $db->prepare("DELETE FROM str_profile WHERE str_id = ?");
    $stmt->execute([$str_id]);
    $stmt = $db->prepare("DELETE FROM str_meta WHERE str_id = ?");
    $stmt->execute([$str_id]);
    header("Location: " . ROOTPATH . "/str/browse?msg=profile-deleted");
    die();
}
