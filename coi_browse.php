<?php

$editor = false;
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $editor = true;
}

$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";
$order = $_GET["order"] ?? "";
$asc = $_GET["asc"] ?? 1;
$values = array();

switch ($order) {
    case 'cellline':
        $orderby = "cellline";
        break;
    case 'species':
        $orderby = "species";
        break;
    case 'celltype':
        $orderby = "cell_type";
        break;
    default:
        $orderby = "coi_id";
        break;
}
$orderby .= $asc == 1 ? " ASC" : " DESC";

if ($order == 'type') $orderby .= ", medium_base";

$filterby = "";
if (!empty($search)) {
    if (is_numeric($search)) {
        $filterby = "WHERE (`dsmz_acc` = ?)";
        array_push($values, $search);
    } elseif (substr($search, 0, 4) === "ACC-" && is_numeric(str_replace('ACC-', '', $search))) {
        $filterby = "WHERE (`dsmz_acc` = ?)";
        array_push($values, str_replace('ACC-', '', $search));
    } else {
        $filterby = "WHERE (`cellline` LIKE ?)";
        array_push($values, '%'.$search.'%');
    }
}


$limit =  $_GET["limit"] ?? 10;
$p = $_GET["p"] ?? 1;

$stmt = $db->prepare("SELECT COUNT(*) FROM coi LEFT JOIN celllines USING (cell_id)  $filterby");
$stmt->execute($values);
$count = $stmt->fetch(PDO::FETCH_COLUMN);
$last = ceil($count / $limit);

if ($p > $last) {
    $p = $last;
}
if ($p < 1) {
    $p = 1;
}
$offset = $p * $limit - $limit;

$stmt = $db->prepare(
    "SELECT * FROM coi LEFT JOIN celllines USING (cell_id) $filterby ORDER BY $orderby
    LIMIT $limit OFFSET $offset
    "
);

$stmt->execute($values);
$meta = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<style>
    table.table td input {
        max-width: 26rem;
    }

    td.highlight {
        font-weight: bold;
        color: var(--danger-color);
    }

    col:hover {
        background-color: #ffa;
    }

    .headcol {
        position: absolute;
        top: auto;
        /* border-top-width: 1px; */
        /*only relevant for first row*/
        margin-top: -1px;
        /*compensate for top border*/
        text-overflow: ellipsis;
    }

    .headcol.left {
        width: 15rem;
        left: 0;
    }

    .headcol.right {
        width: 5rem;
        right: 0;
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
        white-space: inherit;
    }

    .mw-150 {
        min-width: 20rem;
    }

    .highlight {
        background-color: #f5f51630 !important;
    }

    .table-striped tbody tr.highlight:nth-child(2n+1) {
        background-color: #f5f51650 !important;
    }

    .dark-mode .highlight {
        background-color: #f5f51620 !important;
    }

    .dark-mode .table-striped tbody tr.highlight:nth-child(2n+1) {
        background-color: #f5f51635 !important;
    }
</style>

<div class="content">

    <a href="<?= ROOTPATH ?>/documentation#coi" class="btn btn-help float-right"><i class="fal fa-lg fa-book mr-5"></i> <span class="d-none d-md-inline">Help</span></a>
    <h1>COI DNA Browser</h1>

    <form action="" method="get" class=" w-600 mw-full d-inline-block mb-5 mr-5">
        <?php
        hiddenFieldsFromGet(['search']);
        ?>
        <div class="input-group" id="search-bar">
            <input type="text" class="form-control" placeholder="Search by cell line or ACC" name="search" value="<?= $search ?>">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
    
</div>

<div class="content">

    <?php if (empty($meta)) { ?>
        Your search received no results.
    <?php } else { ?>
        <div class="table-responsive">
            <table class="table table-striped" id="str-result">

                <thead>
                    <tr>
                        <th>Cell line
                        <?php sortbuttons("cellline"); ?>
                        </th>
                        <th>Species
                        <?php sortbuttons("species"); ?>

                        </th>
                        <th>Cell type
                        <?php sortbuttons("celltype"); ?></th>
                        <th>View COI Species Report</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($meta as $row) {
                    ?>
                        <tr id="cell-<?= $row["coi_id"] ?>">
                            <td><a href="<?= ROOTPATH ?>/cellline/<?= $row['cellline'] ?>"><?= $row['cellline'] ?></a></td>
                            <td><?= $row['species'] ?></td>
                            <td><?= $row['cell_type'] ?></td>
                            <td>
                                <a href="<?=ROOTPATH?>/coi/view/<?=$row['cell_id']?>" class="btn btn-sm"><i class="fas fa-search"></i></a>
                            </td>
                        </tr>
                    <?php } ?>


                </tbody>
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