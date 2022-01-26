<?php

if (is_numeric($cellline)) {
    $stmt = $db->prepare("SELECT *
    FROM celllines WHERE cell_id = ?
    ");
    $stmt->execute([$cellline]);
    $cell = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif (substr($cellline, 0, 4) === "ACC-" && is_numeric(str_replace('ACC-', '', $cellline))) {
    $stmt = $db->prepare("SELECT *
    FROM celllines WHERE dsmz_acc = ?
    ");
    $stmt->execute([str_replace('ACC-', '', $cellline)]);
    $cell = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $stmt = $db->prepare("SELECT *
    FROM celllines WHERE cellline LIKE ?
    ");
    $stmt->execute([$cellline]);
    $cell = $stmt->fetch(PDO::FETCH_ASSOC);
}


if (empty($cell)) {
    echo "Cell line does not exist! Go back to the <a href='" . ROOTPATH . "/celllines'>list of cell lines</a> and try again.";
    die();
}

$stmt = $db->prepare("SELECT str_id
        FROM str_meta WHERE cell_id = ? AND reference = 1
    ");
$stmt->execute([$cell['cell_id']]);
$str_id = $stmt->fetch(PDO::FETCH_COLUMN);


$stmt = $db->prepare("SELECT *
        FROM coi WHERE cell_id = ?
    ");
$stmt->execute([$cell['cell_id']]);
$coi = $stmt->fetch(PDO::FETCH_ASSOC);



$stmt = $db->prepare("SELECT `isotype`, GROUP_CONCAT(`hla` SEPARATOR ', ') 
        FROM `hla` WHERE `cell_id` = ? AND `isotype` IN ('A','B','C', 'DPA1', 'DPB1', 'DQA1', 'DQB1', 'DRA', 'DRB1')
        GROUP BY `isotype`
    ");
$stmt->execute([$cell['cell_id']]);
$hla = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<div class="content">
    <a href="<?= ROOTPATH ?>/documentation#cell-line" class="btn btn-help float-right"><i class="fal fa-lg fa-book mr-5"></i> <span class="d-none d-md-inline">Help</span></a>

    <h1 class="d-flex"><img src="<?= ROOTPATH ?>/img/mutz.png" alt="" class="img-icon mr-10"> <?= $cell['cellline'] ?></h1>

    <p><b>DSMZ:</b> <a href="https://www.dsmz.de/collection/catalogue/details/culture/ACC-<?= $cell['dsmz_acc'] ?>" target="_blank" rel="noopener noreferrer">ACC-<?= $cell['dsmz_acc'] ?></a></p>
    <p>
        <b>Species:</b>
        <em><?= $cell['species_scientific'] ?></em>
        (<?= $cell['species'] ?>)
    </p>
    <p>
        <b>Cell type:</b>
        <?= $cell['cell_type'] ?>
    </p>
    <?php if (!empty($cell['medium'])) {
        $repl = array(
            "[" => "<sup>",
            "]" => "</sup>",
            "{" => "<sub>",
            "}" => "</sub>"
        );
        $medium = $cell['medium'];
    ?>
        <p>
            <b>Incubation medium:</b>
            <?= $medium ?>
        </p>
    <?php } ?>

    <?php if (!empty($cell['morphology'])) {
        $morph = explode(';', $cell['morphology'])[0];
    ?>
        <p>
            <b>Morphology:</b>
            <?= $morph ?>
        </p>
    <?php
    } ?>

</div>

<?php if (!empty($str_id)) {

    $stmt = $db->prepare("SELECT locus, allele, `value`, value_str
                FROM str_profile WHERE str_id = ?
                ");
    $stmt->execute([$str_id]);
    $str = $stmt->fetchAll(PDO::FETCH_GROUP);
    $stmt = $db->prepare("SELECT MAX(allele) 
                FROM str_profile WHERE str_id = ?
                ");
    $stmt->execute([$str_id]);
    $str_count = $stmt->fetch(PDO::FETCH_COLUMN);

?>
    <div class="card">
    <a href="<?= ROOTPATH ?>/documentation#str" class="btn btn-help float-right"><i class="fal fa-lg fa-book mr-5"></i> <span class="d-none d-md-inline">Help</span></a>

        <h3 class="card-title">
            <a class="mr-10" href="#" onclick="$(this).parent().next().toggle();$(this).children().toggleClass('fa-arrows-to-line')"><i class="fas fa-arrows-to-line fa-arrows-from-line"></i></a>
            STR Profile
        </h3>

        <table class="table table-sm w-auto">
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
                        <td><?= str_val($row['value'], $locus) ?></td>
                    <?php } ?>

                </tr>
            <?php } ?>
        </table>

        <a href="<?= ROOTPATH ?>/str/search?str_id=<?= $str_id ?>" class="btn btn-primary mt-20"><i class="fas fa-search"></i> STR Search</a>

    </div>
<?php } elseif (!empty($coi)) {
    $Parsedown = new Parsedown();
    $text = $Parsedown->text($coi['markdown']);
    $text = str_replace('<table>', '<table class="table">', $text);
?>
    <div class="card">
        <a href="<?= ROOTPATH ?>/documentation#coi" class="btn btn-help float-right"><i class="fal fa-lg fa-book mr-5"></i> <span class="d-none d-md-inline">Help</span></a>

        <h3 class="card-title">
        <a class="mr-10" href="#" onclick="$(this).parent().next().toggle();$(this).children().toggleClass('fa-arrows-to-line')"><i class="fas fa-arrows-to-line fa-arrows-from-line"></i></a>
COI DNA Barcoding Report
</h3>
        <div class="parsedown">
            <?= $text ?>
        </div>
    </div>
<?php } ?>

    <?php if (!empty($hla)) { ?>

<div class="card">
<a href="<?= ROOTPATH ?>/documentation#hla" class="btn btn-help float-right"><i class="fal fa-lg fa-book mr-5"></i> <span class="d-none d-md-inline">Help</span></a>

    <h3 class="card-title">
        <a class="mr-10" href="#" onclick="$(this).parent().next().toggle();$(this).children().toggleClass('fa-arrows-to-line')"><i class="fas fa-arrows-to-line fa-arrows-from-line"></i></a>
        HLA Typing
    </h3>
        <table class="table table-sm w-auto">
            <thead>
                <tr>
                    <th>Isotype</th>
                    <th>Allele 1</th>
                    <th>Allele 2</th>
                </tr>
            </thead>
            <?php foreach ($hla as $isotype => $value) {
                if (empty($value)) continue;
            ?>
                <tr>
                    <td><?= $isotype ?></td>
                    <!-- <td><?= $row['allele'] ?></td> -->
                    <?php foreach (explode(', ', $value) as $v) { ?>
                        <td><?= $v ?></td>
                    <?php } ?>

                </tr>
            <?php } ?>
        </table>

</div>

    <?php } ?>
<div class="content">
<a href="<?= ROOTPATH ?>/documentation#rna-seq" class="btn btn-help float-right"><i class="fal fa-lg fa-book mr-5"></i> <span class="d-none d-md-inline">Help</span></a>

    <h3>
        RNA-seq project
    </h3>
</div>

<?php if (empty($cell['rna_seq'])) { ?>
    <div class="card">
        <p>This cell line is currently not part of any project.</p>
    </div>
<?php } else {

?>
    <div class="card">
<h3 class="card-title">
            <a class="mr-10" href="#" onclick="$(this).parent().next().toggle();$(this).children().toggleClass('fa-arrows-to-line')"><i class="fas fa-arrows-to-line fa-arrows-from-line"></i></a>
            <?= project_name($cell['rna_seq']) ?>
        </h3>

<div>
        <div class="project-chart" id="project-rna-hist"></div>
        <button class="btn btn-primary my-5" onclick="$(this).hide();chartRNA();">
            <i class="fad fa-fw fa-chart-column"></i> Histogram of normalised expression
        </button>
        <br>
        <div class="project-chart" id="project-rna-bar"></div>
        <button class="btn btn-primary my-5" onclick="$(this).hide();barchartRNA();">
            <i class="fad fa-fw fa-chart-column"></i> Normalised expression of all genes
        </button>
        <br>


       
</div>
 <a href="<?= ROOTPATH ?>/rna/<?= $cell['rna_seq'] ?>" class="btn mt-5"><i class="fas fa-fw fa-right"></i> <?= project_name($cell['rna_seq']) ?> overview page</a>

          </div>

<?php } ?>



<script>
    const CELL = '<?= $cell['cell_id'] ?>';
</script>