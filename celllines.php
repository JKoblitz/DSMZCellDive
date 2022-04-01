<?php

$search = trim(filter_var($_GET['search'] ?? "", FILTER_SANITIZE_STRING));
$species = trim(filter_var($_GET['species'] ?? "", FILTER_SANITIZE_STRING));
$celltype = trim(filter_var($_GET['celltype'] ?? "", FILTER_SANITIZE_STRING));
$order = trim(filter_var($_GET['order'] ?? "", FILTER_SANITIZE_STRING));
$limit =  filter_var($_GET["limit"] ?? 10, FILTER_VALIDATE_INT);
$p =  filter_var($_GET["p"] ?? 1, FILTER_VALIDATE_INT);
$proj_filter =  filter_var($_GET["project"] ?? '', FILTER_SANITIZE_STRING);
$asc =  filter_var($_GET["asc"] ?? 1, FILTER_VALIDATE_INT);

// 'asc'   => [FILTER_VALIDATE_INT, array('options' => array('default' => 1))],
$colors = cellcolors();

switch ($order) {
    case "name":
        $orderby = "cellline";
        break;
    case "tumour":
        $orderby = "tumour_short";
        break;
    case "acc":
        $orderby = "dsmz_acc IS NULL, dsmz_acc";
        break;
    default:
        $orderby = "dsmz_acc IS NULL, dsmz_acc";
        break;
}
$orderby .= $asc == 1 ? " ASC" : " DESC";

$sql = "FROM celllines c ";
$where = array();
$values = array();

if (!empty($search)) {
    if (is_numeric($search)) {
        $where[] = "cell_id = ? OR cellline LIKE ?";
        $values = array(intval($search), $search);
    } elseif (preg_match("/^ACC-\d+$/", $search)) {
        $where[] = "cell_id = ?";
        $values = array(str_replace('ACC-', '', $search));
    } else {
        $where[] = "cellline LIKE ?";
        $values = array("%" . $search . "%");
    }
}

if (!empty($celltype)) {
    $where[] = "cell_type LIKE ?";
    $values[] = $celltype;
}

if (!empty($species)) {
    $where[] = "species LIKE ? OR species_scientific LIKE ?";
    array_push($values, $species, $species);
}

if (!empty($proj_filter)) {
    $where[] = "rna_seq LIKE ?";
    array_push($values, $proj_filter);
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}

$stmt = $db->prepare("SELECT COUNT(DISTINCT cell_id) " . $sql);
$stmt->execute($values);
$count = $stmt->fetch(PDO::FETCH_COLUMN);

$last = ceil($count / $limit);

if ($last && $p > $last) {
    $p = $last;
} elseif ($p < 1) {
    $p = 1;
}

$offset = $p * $limit - $limit;

$stmt = $db->prepare("SELECT c.*
    $sql
    GROUP BY cell_id
    ORDER BY $orderby
    LIMIT $limit OFFSET $offset
    ");
$stmt->execute($values);
$cells = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="content">
    <a href="<?= ROOTPATH ?>/documentation#cell-lines" class="btn btn-help float-lg-right"><i class="far fa-lg fa-book mr-5"></i> Help</a>
    <h1 class="d-flex"><img src="<?= ROOTPATH ?>/img/mutz.png" alt="" class="img-icon mr-10"> Cell lines</h1>

    <div>
        <form action="" method="get" class="d-inline-block w-500 mw-full">
            <!-- Input group with appended button -->
            <label for="search">Search by cell line (synonyms not included) or DSMZ ACC:</label>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search" name="search" value="<?= $search ?>">
                <div class="input-group-append">
                    <button class="btn btn-success text-white" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
        <div class="dropdown with-arrow">
            <button class="btn" data-toggle="dropdown" type="button" id="project-filter" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-filter"></i> More filters
            </button>
            <div class="dropdown-menu dropdown-menu-center " aria-labelledby="project-filter">
                <form action="" method="get" class="w-300 mw-full">
                    <div class="form-group">
                        <label for="species">Search for species:</label>
                        <input type="text" class="form-control" list="species" placeholder="Species ..." name="species" value="<?= $species ?>">
                    </div>
                    <div class="form-group">
                        <label for="celltype">Search for celltype:</label>
                        <input type="text" class="form-control" list="celltypes" placeholder="celltype ..." name="celltype" value="<?= $celltype ?>">
                    </div>
                    <div class="form-group">
                        <label for="project-ll-100" class="required">RNA-seq project</label>
                        <div class="custom-radio">
                            <input type="radio" name="project" id="project-ll-100" value="ll-100" <?= $proj_filter == 'll-100' ? 'checked' : '' ?>>
                            <label for="project-ll-100">LL-100</label>
                        </div>
                        <div class="custom-radio">
                            <input type="radio" name="project" id="project-breast-cancer" value="breast-cancer" <?= $proj_filter == 'breast-cancer' ? 'checked' : '' ?>>
                            <label for="project-breast-cancer">Breast cancer</label>
                        </div>
                        <div class="custom-radio">
                            <input type="radio" name="project" id="project-all" value="all" <?= empty($proj_filter) ? 'checked' : '' ?>>
                            <label for="project-all">show everything</label>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Filter</button>
                </form>
            </div>
        </div>
        <a href="<?= ROOTPATH ?>/celllines" class="btn" title="remove all filter"><i class="fas fa-filter-slash"></i></a>
    </div>


    <div class="table-responsive">

        <table class="my-10 table">
            <thead>
                <tr>
                    <th>Cell line
                        <?php sortbuttons("name"); ?>
                    </th>
                    <!-- <th>Tumour (short)
                        <?php sortbuttons("tumour"); ?>
                    </th> -->
                    <th>Cell type</th>
                    <th>Species</th>

                    <th><i class="fas fa-external-link"></i> DSMZ
                        <?php sortbuttons("acc"); ?>
                    </th>
                    <!-- <th>Virus diagnostic</th> -->
                    <th>RNA-seq</th>
                    <!-- <th>LL-100</th> -->
                </tr>
            </thead>
            <?php
            foreach ($cells as $cell) { ?>
                <tr id="cell-<?= $cell["cell_id"] ?>">
                    <td><a href="<?= ROOTPATH ?>/cellline/<?= $cell['cellline'] ?>"><?= $cell['cellline'] ?></a></td>
                    <td><?= $cell['cell_type'] ?></td>
                    <td><?= $cell['species'] ?></td>
                    <td>
                        <?php if (!empty($cell['dsmz_acc'])) { ?>
                            <a href="https://www.dsmz.de/collection/catalogue/details/culture/ACC-<?= $cell['dsmz_acc'] ?>" target="_blank" rel="noopener noreferrer">ACC-<?= $cell['dsmz_acc'] ?></a>
                        <?php } else { ?>
                            not available
                        <?php }  ?>

                    </td>
                    <!-- <td>todo</td> -->

                    <td>
                        <?php if (!empty($cell['rna_seq'])) { ?>
                            <a class="badge text-white" href="<?= ROOTPATH ?>/rna/<?= $cell['rna_seq'] ?>/bar?samples=<?= $cell['tumour_short'] ?>" style="background-color:<?= $colors[$cell['tumour_short'] ?? ''] ?>"> <?= project_name($cell['rna_seq']) ?></a>
                        <?php } ?>

                    </td>
                    <!-- <td><a href="<?= ROOTPATH ?>/rna/ll-100/bar?cell=<?= $cell['cellline'] ?>" class="btn"><i class="fa-solid fa-chart-column"></i></a></td> -->
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


<div>
    <datalist id="species">
        <?php
        $query = $db->query(
            "SELECT DISTINCT species FROM celllines WHERE species IS NOT NULL 
    UNION
    SELECT DISTINCT species_scientific FROM celllines WHERE species_scientific IS NOT NULL "
        );
        $species = $query->fetchAll(PDO::FETCH_COLUMN);
        foreach ($species as $s) {
            echo "<option>$s</option>";
        }
        ?>
    </datalist>
    <datalist id="celltypes">
        <?php
        $query = $db->query("SELECT DISTINCT cell_type FROM celllines WHERE cell_type IS NOT NULL");
        $cell_type = $query->fetchAll(PDO::FETCH_COLUMN);
        foreach ($cell_type as $c) {
            echo "<option>$c</option>";
        }
        ?>
    </datalist>
</div>