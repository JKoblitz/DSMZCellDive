<script>
    const STR_ID = 0;
</script>

<?php
include_once 'php/_config.php';

$all_loci = array(
    "D5S818", "D13S317", "D7S820", "D16S539", "vWA", "TH01", "TPOX", "CSF1PO", "Amelogenin", "D3S1358", "D21S11", "D18S51", "PentaE", "PentaD", "D8S1179", "FGA", "D19S433", "D2S1338"
);

$query = array();
$values = array();
$loci = array();
$search = array();

if (isset($_POST['limit']) && is_numeric($_POST['limit'])) {
    $limit = min($_POST['limit'], 30);
} else {
    $limit = 10;
}

$n_query = 0;
$search_profile = array();
foreach ($_POST as $key => $value) {
    $key = explode('_', $key, 2);
    if (count($key) !== 2 || empty($value)) continue;
    $locus = $key[0];
    $allele = $key[1];
    if (!in_array($locus, $all_loci)) continue;

    if (!isset($loci[$locus]) || $loci[$locus] < $allele) {
        $loci[$locus] = $allele;
    }
    // $n_query++;

    $search_profile[$locus][$allele] = $value;

    array_push($values, $locus, $allele, floatval($value));
    array_push($query, '(locus = ? AND allele = ? AND `value` = ?)');
}

// add second 
foreach ($search_profile as $locus => $alleles) {
    if (count($alleles) == 1 && isset($alleles[1])) {
        // $n_query++;
        $search_profile[$locus][2] = $alleles[1];
        array_push($values, $locus, "2", floatval($alleles[1]));
        array_push($query, '(locus = ? AND allele = ? AND `value` = ?)');
    }
}

if (empty($query)) {
    echo "Error: Your query is empty.";
    die();
}
$query = implode(' OR ', $query);

$filterby = "WHERE `reference` = 1 ";
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $filterby = "";
}



// $stmt = $db->prepare("SELECT *, (`value` LIKE ?) AS is_13_3 FROM `str_profile` p WHERE locus='D13S317' AND str_id=43 ");
// $stmt->execute([13.3]);
// $table = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($table);


$max_alleles = max(array_map("count", $search_profile)) + 1;
$n_query = array_sum(array_map("count", $search_profile));
$in = implode(",", array_map(array($db, 'quote'), array_keys($search_profile))) ;

$sql = "SELECT m.*, c, (c*2 / ($n_query+n)) AS `score`
    FROM ( 
        SELECT str_id, COUNT(*) AS c FROM `str_profile` 
    	WHERE $query
    	GROUP BY str_id
    ) AS p LEFT JOIN str_meta m USING (str_id)
    LEFT JOIN (
        SELECT str_id, COUNT(*) AS n FROM str_profile WHERE locus IN ($in) GROUP BY str_id
    ) AS pc USING (str_id)
    $filterby
    ORDER BY `score` DESC
    LIMIT $limit";
// var_dump($values);
$stmt = $db->prepare($sql);
$stmt->execute($values);
$search = $stmt->fetchAll(PDO::FETCH_ASSOC);

$str_ids = implode(',', array_column($search, 'str_id'));

$stmt = $db->prepare("SELECT str_id, locus, allele, `value`, value_str
        FROM str_profile WHERE str_id IN ($str_ids)
    ");
$stmt->execute();
$table = $stmt->fetchAll(PDO::FETCH_ASSOC);

$profiles = [];
$allele_count = array_fill_keys(array_column($search, 'str_id'), 0);
foreach ($table as $row) {
    if (!isset($loci[$row['locus']]) || $loci[$row['locus']] < $row['allele']) {
        $loci[$row['locus']] = $row['allele'];
    }
    $profiles[$row['str_id']][$row['locus']][$row['allele']] = $row['value'];
    $allele_count[$row['str_id']]++;
}

?>

<style>
    table.table td input {
        max-width: 26rem;
    }

    td.highlight {
        font-weight: bold;
        color: var(--danger-color);
    }

    /* col:hover {
        background-color: #ffa;
    } */

    .headcol {
        position: absolute;
        top: auto;
        margin-top: -1px;
        text-overflow: ellipsis;
    }

    tbody .headcol {
        border-top: var(--table-border-width) solid var(--lm-table-border-color);
    }

    .dark-mode tbody .headcol {
        border-color: var(--dm-table-border-color);

    }

    .headcol.left {
        width: 10rem;
        left: 1rem;
    }

    .headcol.right {
        width: 15rem;
        left: 11rem;
        /* right: 0; */
        /* border-left: var(--table-border-width) solid var(--lm-table-border-color); */
    }

    .table-responsive {
        margin-left: 23rem;
    }

    .fake {
        position: relative;
        /* left: -14rem; */
        color: transparent;
        min-width: 15rem;
        pointer-events: none;
        visibility: hidden;
    }

    .table-responsive th,
    .table-responsive td {
        /* white-space: inherit; */
        overflow: hidden;
    }

    .headcol.right:hover {
        min-width: 15rem;
        width: auto;
    }

    .mw-150 {
        min-width: 20rem;
    }


    .score-good {
        background-color: var(--secondary-color-very-light);
    }
    .score-warning {
        background-color: #FFFF96;
    }
    .score-danger {
        background-color: var(--danger-color-very-light);
    }

    .score.score-good {
        color: var(--secondary-color);
    }
    .score.score-warning {
        color: goldenrod;
    }
    .score.score-danger {
        color: var(--danger-color);
    }

    .dark-mode .score-good {
        background-color: var(--secondary-color-very-dim);
    }
    .dark-mode .score-warning {
        background-color: #534600  ;
    }
    .dark-mode .score-danger {
        background-color: var(--danger-color-very-dim);
    }

    td.ACC {
        white-space: inherit; min-width:25rem
        /* max-width:25rem;
        overflow:hidden; */
    }
    /* td.ACC:hover {
        overflow:auto;
        background-color: var(--lm-base-body-bg-color);
    }
    .dark-mode td.ACC:hover {
        background-color: var(--dm-base-body-bg-color);
    } */

</style>





<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) { ?>
    <div class="modal" id="add-query" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" role="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
                <h5 class="modal-title">Add to database</h5>

                <form action="<?= ROOTPATH ?>/str/add/0" method="post">

                    <div class="form-group">
                        <label for="ACC">ACC</label>
                        <input type="text" class="form-control" name="ACC" required>
                    </div>
                    <div class="form-group">
                        <label for="cellline">Cell line</label>
                        <input type="text" class="form-control" name="cellline" required>
                    </div>
                    <div class='custom-switch'>
                        <input autocomplete='off' type='checkbox' id='reference' name='reference' value="1">
                        <label for='reference'>Reference</label>
                    </div>
                    <?php
                    foreach ($_POST as $key => $value) {
                        echo "<input type='hidden' name='$key' value='$value' >";
                    }
                    ?>

                    <p class="text-muted">Additional data fields are available and everything can be changed afterwards. However, please fill out the mandatory fields above.</p>

                    <div class="text-right mt-20">
                        <button type="submit" class="btn btn-danger"><i class="fas fa-plus"></i> Add to database</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

<?php } ?>



<div class="content">
    <a href="<?= ROOTPATH ?>/documentation#str" class="btn btn-help float-right"><i class="far fa-lg fa-book mr-5"></i> <span class="d-none d-md-inline">Help</span></a>

    <h1>STR Profile Search</h1>
    <p class="text-muted">
        The human STR profile database includes data sets of 2455 cell lines from ATCC, DSMZ, JCRB and RIKEN.
    </p>

    <button class="btn btn-primary" type="button" onclick="$('#form-input').slideToggle()"><i class="fas fa-search"></i> Refine search</button>
    <a href="<?= ROOTPATH ?>/str/search" class="btn"><i class="fas fa-chevron-left"></i> Start new search</a>

    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) { ?>
        <button class="btn text-danger border-danger" type="button" onclick="halfmoon.toggleModal('add-query')"><i class="fas fa-plus"></i> Add query to database</button>
    <?php } ?>
</div>


<div class="content" id="form-input" style="display: none;">
    <form method="post" action="<?= ROOTPATH ?>/str/search" class="">

        <table class="table table-sm w-auto" id="STR-input">
            <thead>
                <tr>
                    <th><i class="fas fa-external-link invisible"></i> STR</th>
                    <?php for ($allele = 1; $allele < $max_alleles; $allele++) { ?>
                        <th>Allele <?= $allele ?></th>
                    <?php } ?>

                    <th id="addAllele">
                        <button type="button" class="btn btn-sm" onclick="addAllele()" data-toggle="tooltip" data-title="Add more alleles to the search">
                            <i class="fas fa-plus"></i>
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_loci as $locus) {
                ?>
                    <tr id="<?= $locus ?>">
                        <td>
                            <label class="d-inline" for="<?= $locus ?>"><?= $locus ?></label>
                        </td>
                        <?php for ($allele = 1; $allele < $max_alleles; $allele++) {
                            $value = $search_profile[$locus][$allele] ?? "";
                        ?>
                            <td class="<?= ($allele == 1 ? 'ref' : '') ?>">
                                <?php if ($locus == "Amelogenin") { ?>
                                    <select class="form-control form-control-sm " name="<?= $locus . "_" . $allele ?>">
                                        <option value=""></option>
                                        <option value="1" <?= $value == "1" ? 'selected' : '' ?>>X</option>
                                        <option value="2" <?= $value == "2" ? 'selected' : '' ?>>Y</option>
                                    </select>
                                <?php } else { ?>
                                    <input class="form-control form-control-sm " type="number" step="0.1" name="<?= $locus . "_" . $allele ?>" value="<?= $value ?>">
                                <?php } ?>
                            </td>
                        <?php } ?>
                        <?php foreach ($alleles as $allele => $value) { ?>

                        <?php } ?>
                    </tr>
                <?php } ?>


            </tbody>
        </table>

        <div class="mt-20">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Search</button>
            <button class="btn" type="button" onclick="getSTR(211);"><i class="fas fa-question"></i> Example</button>
            <button class="btn" type="reset"><i class="fas fa-trash-alt"></i> Reset</button>
        </div>
    </form>

</div>

<div class="content">
    <?php if (empty($search)) { ?>
        Your search received no results.
    <?php } else { ?>
        <!-- <form action="<?= ROOTPATH ?>/str/excel" method="post">
            <?php hiddenFieldsFromPost(); ?>
            <button type="submit" class="btn"><i class="fas fa-file-excel"></i> Download</button>
        </form> -->
        <div class="table-responsive">

            <table class="table" id="str-result">
                <thead>
                    <tr>
                        <th class="headcol left">Similarity</th>
                        <th class="headcol right">Cell line</th>
                        <th>Source</th>
                        <?php foreach ($loci as $locus => $n_alleles) { ?>
                            <th class="" colspan="<?= $n_alleles ?>"><?= $locus ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="headcol left">
                            <a href="<?= ROOTPATH ?>/documentation#str-score" target="_blank" title="Explanation on score and colors"><i class="fas fa-lg fa-question-circle"></i></a>
                    </th>
                        <th class="headcol right">Your query</th>
                        <th></th>
                        <?php
                        foreach ($loci as $locus => $n_alleles) {
                            for ($i = 0; $i < $n_alleles; $i++) {
                                $val = $search_profile[$locus][$i + 1] ?? '';
                                echo "<td>" . str_val($val, $locus) . "</td>";
                            }
                        } ?>

                    </tr>

                    <?php 
                        foreach ($search as $i=>$row) {
                        $profile = $profiles[$row['str_id']];
                        // if ($i==0){var_dump($profile);}
                        // $n = $allele_count[intval($row['str_id'])] ?? 0;
                        // $n = array_sum(array_map("count", $profile));
                        // $score = ($row['c'] * 2) / ($n_query + $n) * 100;
                        // echo "($row[c] * 2) / ($n_query + $n) * 100;<br>";
                        $score = floatval($row['score'])*100;
                        $cls = "";
                        if ($score > 80) {
                            $cls = "score-good";
                        } elseif ($score > 60) {
                            $cls = "score-warning";
                        } else {
                            $cls = "score-danger";
                        }
                    ?>
                        <tr class="">
                            <th class="headcol left score <?= $cls ?>"><?= round($score, 1) ?>&nbsp;%</th>
                            <th class="headcol right query <?= $cls ?>">
                                <?php
                                if ($row['reference'] == 0) {
                                    echo '<i class="fa-solid fa-key-skeleton text-danger" title="internal"></i>';
                                }
                                ?>
                                <?php if (!empty($row['cell_id'])) { ?>
                                    <a href='<?= ROOTPATH ?>/cellline/ACC-<?= $row['cell_id'] ?>'><?= $row['cellline'] ?></a>
                                <?php } else {
                                    echo $row['cellline'];
                                } ?>
                            </th>
                            <td class="ACC" title="<?= $row['ACC'] ?>">
                                <?php if (is_numeric($row['ACC'])) { ?>
                                    DSMZ:
                                    <a href='https://www.dsmz.de/collection/catalogue/details/culture/ACC-<?= $row['ACC'] ?>' target='_blank' rel='noopener noreferrer'>ACC-<?= $row['ACC'] ?></a>
                                <?php } else {
                                    echo  $row['ACC'];
                                } ?>
                            </td>
                            <?php

                            foreach ($loci as $locus => $n_alleles) {
                                for ($i = 0; $i < $n_alleles; $i++) {
                                    $cls = "";
                                    if (($profile[$locus][$i + 1] ?? '') != ($search_profile[$locus][$i + 1] ?? '')) {
                                        $cls = 'highlight';
                                    }
                                    $val = $profile[$locus][$i + 1] ?? '';
                                    echo "<td class='$cls'>" . (str_val($val, $locus)) . "</td>";
                                }
                            }
                            echo "</tr>";
                            ?>


                        <?php } ?>


                </tbody>
            </table>

        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn" onclick="downloadExcel()"><i class="fas fa-file-excel"></i> Download</button>
            <form action="" method="post" class="d-inline-block">
                <?php
                hiddenFieldsFromPost(['limit']);
                ?>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <small class="input-group-text">Number of results</small>
                    </div>
                    <select name="limit" class="form-control">
                        <option value="5" <?= ($limit == "5" ? 'selected' : '') ?>>5</option>
                        <option value="10" <?= ($limit == "10" ? 'selected' : '') ?>>10</option>
                        <option value="20" <?= ($limit == "20" ? 'selected' : '') ?>>20</option>
                        <option value="50" <?= ($limit == "30" ? 'selected' : '') ?>>30</option>
                    </select>
                    <div class="input-group-append">
                        <button class="btn" type="submit"><i class="fas fa-check"></i></button>
                    </div>
                </div>
            </form>
        </div>

    <?php } ?>
</div>



<div class="card">
    If the results obtained by the search engine are used in any publication, please cite the respective paper:
    <a href="https://onlinelibrary.wiley.com/doi/full/10.1002/ijc.24999">Dirks et al. <em> Int J Cancer</em> (2010)</a>
</div>