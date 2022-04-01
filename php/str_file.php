<?php
include_once "_db.php";


function str_val($num, $locus)
{
    if (empty($num)) return "";
    if ($num == 0) {
        return 'n.d.';
    }
    if ($locus == 'Amelogenin') {
        if ($num == 1) {
            return "X";
        } elseif ($num == 2) {
            return "Y";
        }
    }
    $pos = strpos($num, '.');
    if ($pos === false) { // it is integer number
        return $num;
    } else { // it is decimal number
        return rtrim(rtrim($num, '0'), '.');
    }
}


function read_str($path)
{
    global $db;
    $mappings = array(
        "CSF1" => "CSF1PO",
        "D13" => "D13S317",
        "D16" => "D16S539",
        "D18" => "D18S51",
        "D19" => "D19S433",
        "D2" => "D2S1338",
        "D21" => "D21S11",
        "D3" => "D3S1358",
        "D5" => "D5S818",
        "D7" => "D7S820",
        "D8" => "D8S1179",
        "THO1" => "TH01",
        "cell_line" => "cellline",
        "bemerkung" => "notes",
        "ebv-status" => "EBV",
        "large-t status" => "largeT",
        "mmr-status" => "MMR"
    );

    $metadata = array(
        "reference" => [1, 0],
        "source" => 'utf8',
        "ACC" => 'utf8',
        "cellline" => '',
        "lot" => '',
        "date" => 'date',
        "date_animal_pcr" => 'date',
        "M" => ['-', '+', '(+)'],
        "R" => ['-', '+', '(+)'],
        "CH" => ['-', '+', '(+)'],
        "SH" => ['-', '+', '(+)'],
        "notes" => 'utf8',
        "gender" => ['male', 'female', 'unknown'],
        "EBV" => ['-', '+', '(+)'],
        "largeT" => ['-', '+', '(+)'],
        "MMR" => ['MSS', 'MSI', 'MSI-L', 'MSI-H'],
        'BAT25' => ['0', '1', 'f', '-1', 'MSS', 'MCF7'],
        'BAT26' => ['0', '1', 'f', '-1', 'MSS', 'MCF7'],
        'D5S346' => ['0', '1', 'f', '-1', 'MSS', 'MCF7'],
        'D2S123' => ['0', '1', 'f', '-1', 'MSS', 'MCF7'],
        'D17S250' => ['0', '1', 'f', '-1', 'MSS', 'MCF7']
    );

    $fileHandle = fopen($path, "r");

    $header = array();
    $messages = array();
    $data = array();

    while (($row = fgetcsv($fileHandle, 0, ";")) !== FALSE) {
        if (empty($header)) {
            foreach ($row as $head) {
                if (preg_match("/(Amelogenin|CSF1|D\d+|D\d+S\d+|FGA|PentaD|PentaE|TH01|THO1|TPOX|vWA)_A?\d$/", $head)) {
                    $temp = explode("_", $head, 2);
                    $allele = intval($temp[1]);
                    $locus = $temp[0];
                    if (array_key_exists($locus, $mappings)) {
                        $locus = $mappings[$locus];
                    }
                    $header[] = ["table" => "profile", "locus" => $locus, "allele" => $allele];
                } else {
                    if (preg_match("/[a-z]/", $head)) {
                        $head = strtolower($head);
                    }
                    if (array_key_exists($head, $mappings)) {
                        $head = $mappings[$head];
                    }
                    $header[] = ["table" => "meta", "name" => $head];
                }
            }
            continue;
        }

        // parse content
        $meta = array();
        $profile = array();
        foreach ($row as $i => $value) {
            $head = $header[$i];
            $value = trim($value);
            if (empty($value)) continue;
            if ($head['table'] == "meta") {
                $name = $head['name'];
                if (!array_key_exists($name, $metadata)) continue;
                $rules = $metadata[$name];
                if (!empty($rules)) {
                    if ($rules == 'date') {
                        // parse dates
                        $date = date_create_from_format('j.m.Y', $value);
                        if ($date === false) {
                            $messages[] = "Fehler: $name muss ein korrekt formatiertes Datum sein: 'dd.mm.yyyy'.  Folgender Wert wurde gefunden: $value";
                            continue;
                        }
                        $value = $date->format('Y-m-j');
                    } elseif ($rules == 'utf8') {
                        // parse dates
                        $value = utf8_decode($value);
                    } elseif (is_array($rules)) {
                        // parse enum
                        if (!in_array($value, $rules)) {
                            $messages[] = "Fehler: $name muss einer der folgenden Werte sein: " . implode(", ", $rules). ".  Folgender Wert wurde gefunden: $value";
                            continue;
                        }
                    }
                }
                $meta[$name] = $value;
            } elseif ($head['table'] == "profile") {
                if ($head['locus'] == "Amelogenin") {
                    if (strtolower($value) == "x") {
                        $value = 1;
                    } elseif (strtolower($value) == "y") {
                        $value = 2;
                    } else {
                        $messages[] = "Fehler: Amelogenin kann nur X oder Y sein. Folgender Wert wurde gefunden: $value.";
                        continue;
                    }
                } else {
                    if (!is_numeric($value)) {
                        $messages[] = "Fehler: $head[locus] muss ein numerischer Wert sein (Nutze 0 für n.d.). Folgender Wert wurde gefunden: $value. ";
                        continue;
                    }
                    $value = floatval($value);
                }
                $profile[] = array($head['locus'], $head['allele'], $value);
            }
        }
        if (!isset($meta['reference'])) {
            $meta['reference'] = 0;
        }
        if (!isset($meta['ACC'])) {
            $messages[] = "Fehler: ACC is needed and cannot be empty.";
        }

        if ($meta['reference'] == '1' && is_numeric($meta['ACC'])) {
            $stmt = $db->prepare("SELECT cell_id FROM celllines WHERE cell_id = ?");
            $stmt->execute([$meta['ACC']]);
            $cell_id = $stmt->fetch(PDO::FETCH_COLUMN);
            if (!empty($cell_id)) {
                $meta['cell_id'] = 0;
            }
        }

        $data[] = array("meta"=> $meta, "profile" => $profile);
       
    }
    return array("data" => $data, "messages" => $messages);
}



function str_table($profile, $cls="table table-sm w-auto"){
    // $profile is an array containing str triplets (locus, allele, value)
    $str_count = max(array_column($profile, 1));
    $str = array();
    foreach ($profile as $row ) {
        // if (!array_key_exists($row[0], $str)){
        //     $str[$row[0]] = 
        // }
        $str[$row[0]][] = $row[2];
        
    }
    ?>
    <table class="<?=$cls?>">
            <thead>
                <tr>
                    <th>Locus</th>
                    <?php
                    foreach (range(1, $str_count) as $number) {
                        echo "<th>Allele $number</th>";
                    }
                    ?>
                </tr>
            </thead>
            <?php foreach ($str as $locus => $allele) { ?>
                <tr>
                    <td><?= $locus ?></td>
                    <?php foreach ($allele as $row) { ?>
                        <td><?= str_val($row, $locus) ?></td>
                    <?php } ?>

                </tr>
            <?php } ?>
        </table>
<?php 
}