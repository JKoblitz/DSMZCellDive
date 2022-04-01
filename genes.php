<?php

$search = trim(filter_var($_GET['search'] ?? "", FILTER_SANITIZE_STRING));
$order = trim(filter_var($_GET['order'] ?? "", FILTER_SANITIZE_STRING));
$limit =  filter_var($_GET["limit"] ?? 10, FILTER_VALIDATE_INT);
$p =  filter_var($_GET["p"] ?? 1, FILTER_VALIDATE_INT);
$asc =  filter_var($_GET["asc"] ?? 1, FILTER_VALIDATE_INT);

// 'asc'   => [FILTER_VALIDATE_INT, array('options' => array('default' => 1))],

switch ($order) {
    case "name":
        $orderby = "external_gene_name";
        break;
    case "chromosome":
        $orderby = "chromosome_name";
        break;
    case "position":
        $orderby = "gene_start_position";
        break;
    case "ensembl":
        $orderby = "ensembl_gene_id";
        break;
    case "entrez":
        $orderby = "entrez_gene_id";
        break;
    default:
        $orderby = "gene_id";
        break;
}
$orderby .= $asc == 1 ? " ASC" : " DESC";

if ($search !== "") {
    if (is_numeric($search)){
        $sql = "FROM genes WHERE external_gene_name LIKE ? OR entrez_gene_id = ?";
        $values = array($search, $search);
    } else {
        $sql = "FROM genes WHERE external_gene_name LIKE ? OR description LIKE ? OR ensembl_gene_id LIKE ?";
        $values = array(str_replace('*', '%', $search), "%$search%", $search);
    }
} else {
    $sql = "FROM genes";
    $values = array();
}

$stmt = $db->prepare("SELECT COUNT(*) " . $sql);
$stmt->execute($values);
$count = $stmt->fetch(PDO::FETCH_COLUMN);

$last = ceil($count / $limit);

if ($last && $p > $last) {
    $p = $last;
} elseif ($p < 1) {
    $p = 1;
}

$offset = $p * $limit - $limit;

$stmt = $db->prepare("SELECT *
    $sql
    ORDER BY $orderby
    LIMIT $limit OFFSET $offset
    ");
$stmt->execute($values);
$genes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="content">
<a href="<?= ROOTPATH ?>/documentation#genes" class="btn btn-help float-right"><i class="far fa-lg fa-book mr-5"></i> <span class="d-none d-md-inline">Help</span></a>

    <h1><i class="fad fa-dna text-primary"></i> Genes</h1>

    <p class="text-muted">
    Gene names correspond to <a href="https://www.gencodegenes.org/" target="_blank" rel="noopener noreferrer">GENCODE</a>  names (v38).
    </p>
    <form action="" method="get" class="w-600 mw-full">
        <!-- Input group with appended button -->
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search by gene name, description (contains!), Ensembl, or Entrez-ID" name="search" value="<?= $search ?>">
            <div class="input-group-append">
                <button class="btn btn-success text-white" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
    <div class="table-responsive">

        <table class="my-10 table">
            <thead>
                <tr>
                    <th>Gene name
                        <?php sortbuttons("name"); ?>
                    </th>
                    <th>Description</th>
                    <th>Chromosome
                        <?php sortbuttons("chromosome"); ?>
                    </th>
                    <!-- <th>Gene position
                        <?php sortbuttons("position"); ?>
                    </th> -->
                    <th>Strand</th>
                    <th title="External link to the Ensembl database">
                        <i class="fas fa-external-link"></i>
                        Ensembl
                        <?php sortbuttons("ensembl"); ?>
                    </th>
                    <th title="External link to the Entrez database">
                        <i class="fas fa-external-link"></i>
                        Entrez
                        <?php sortbuttons("entrez"); ?>
                    </th>
                </tr>
            </thead>
            <?php
            foreach ($genes as $gene) {
            ?>
                <tr id="gene-<?= $gene["gene_id"] ?>">
                    <td><?= $gene['external_gene_name'] ?></td>
                    <td style="white-space: inherit; min-width:400px"><?= $gene['description_short'] ?></td>
                    <td><?= $gene['chromosome_name'] ?></td>
                    <!-- <td><?= $gene['gene_start_position'] ?> - <?= $gene['gene_end_position'] ?></td> -->
                    <td><?= $gene['strand'] ?></td>
                    <td>
                        <a href="https://ensembl.org/Homo_sapiens/Gene/Summary?db=core;g=<?= $gene['ensembl_gene_id'] ?>" target="_blank" rel="noopener noreferrer">
                        <?= $gene['ensembl_gene_id'] ?>
                    </a>
                    </td>
                    <td>
                        <a href="https://www.ncbi.nlm.nih.gov/gene/<?= $gene['entrez_gene_id'] ?>" target="_blank" rel="noopener noreferrer">
                        <?= $gene['entrez_gene_id'] ?>
                    </a></td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>



    <div class="text-right">
        <?php
        if ($count <= 0) {
            echo "No results";
        } else {
            echo "Show " . ($offset + 1) . " to " . min($offset + $limit, $count) . " (" . $count . " total)";
        }
        ?>
    </div>

    <div class="table-footer justify-content-between">
        <nav class="d-inline-block">
            <form action="" method="get" class="">
                <ul class="pagination">

                    <?php
                    hiddenFieldsFromGet(['p']);
                    ?>
                    <li class="page-item <?= ($p <= 1 ? "disabled" : "") ?>"><button type="submit" name="p" value="1" class="page-link" tabindex="-1" title="first"><i class="fas fa-chevron-double-left"></i></button></li>
                    <li class="page-item <?= ($p <= 1 ? "disabled" : "") ?>"><button type="submit" name="p" value="<?= ($p - 1) ?>" class="page-link" tabindex="-1" title="previous"><i class="fas fa-chevron-left"></i></button></li>
                    <?php
                    if ($p - 1 > 1 && $p == $last) {
                        echo '<li class="page-item" aria-current="page"><button type="submit" name="p" value="' . ($p - 2) . '" class="page-link">' . ($p - 2) . '</button></li>';
                    }
                    if ($p > 1) {
                        echo '<li class="page-item" aria-current="page"><button type="submit" name="p" value="' . ($p - 1) . '" class="page-link">' . ($p - 1) . '</button></li>';
                    }
                    echo '<li class="page-item active" aria-current="page"><button type="submit" name="p" value="' . ($p) . '" class="page-link">' . ($p) . '</button></li>';
                    if ($p < $last) {
                        echo '<li class="page-item" aria-current="page"><button type="submit" name="p" value="' . ($p + 1) . '" class="page-link">' . ($p + 1) . '</button></li>';
                    }
                    if ($p + 1 < $last && $p == 1) {
                        echo '<li class="page-item" aria-current="page"><button type="submit" name="p" value="' . ($p + 2) . '" class="page-link">' . ($p + 2) . '</button></li>';
                    }
                    ?>
                    <li class="page-item <?= ($p >= $last ? "disabled" : "") ?>"><button type="submit" name="p" value="<?= ($p + 1) ?>" class="page-link" title="next"><i class="fas fa-chevron-right"></i></button></li>
                    <li class="page-item <?= ($p >= $last ? "disabled" : "") ?>"><button type="submit" name="p" value="<?= ($last) ?>" class="page-link" title="last"><i class="fas fa-chevron-double-right"></i></button></li>
                </ul>
            </form>
        </nav>

        <form action="" method="get" class="d-inline-block float-md-right">
            <?php
            hiddenFieldsFromGet(['limit']);
            ?>

            <div class="input-group">
                <div class="input-group-prepend">
                    <small class="input-group-text">Results per page</small>
                </div>
                <select name="limit" class="form-control">
                    <option value="10" <?= ($limit == "10" ? 'selected' : '') ?>>10</option>
                    <option value="20" <?= ($limit == "20" ? 'selected' : '') ?>>20</option>
                    <option value="50" <?= ($limit == "50" ? 'selected' : '') ?>>50</option>
                    <option value="100" <?= ($limit == "100" ? 'selected' : '') ?>>100</option>
                </select>
                <div class="input-group-append">
                    <button class="btn" type="submit"><i class="fas fa-check"></i></button>
                </div>
            </div>
        </form>


    </div>
</div>