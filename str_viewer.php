<?php
include_once 'php/_config.php';

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

<script>
    const STR_ID = 0;
</script>


<div class="content">
    <a href="<?= ROOTPATH ?>/documentation#str" class="btn btn-help float-right"><i class="far fa-lg fa-book mr-5"></i> <span class="d-none d-md-inline">Help</span></a>
    <h1>STR Profile Viewer</h1>
</div>

<div class="row card-deck">
    <div class="col-md-6">

        <div class="card" id="meta-form">
            <h2 class="card-title">Metadata</h2>
            <div class="card-content">

                <table class="table table-sm w-auto" id="META-input">
                    <tbody>
                        <tr>
                            <td>Cell line</td>
                            <td>
                                <?php if (!empty($meta['cell_id'])) { ?>
                                    <a href='<?= ROOTPATH ?>/cellline/ACC-<?= $meta['cell_id'] ?>'><?= $meta['cellline'] ?></a>
                                <?php } else {
                                    echo $meta['cellline'];
                                } ?>
                            </td>
                        </tr>
                        <tr>
                            <td>ACC</td>
                            <td>
                                <?php if (is_numeric($meta['cell_id'])) { ?>
                                    <a href='https://www.dsmz.de/collection/catalogue/details/culture/ACC-<?= $meta['ACC'] ?>' target='_blank' rel='noopener noreferrer'>ACC-<?= $meta['ACC'] ?></a>
                                <?php } else {
                                    echo  $meta['ACC'];
                                } ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td><?= format_date($meta['date']) ?></td>
                        </tr>
                        <!-- <tr>
                            <td>Gender</td>
                            <td><?= $meta['gender'] ?></td>
                        </tr> -->
                        <!-- <tr>
                            <td>EBV</td>
                            <td><?= $meta['EBV'] ?></td>
                        </tr>
                        <tr>
                            <td>largeT</td>
                            <td><?= $meta['largeT'] ?></td>
                        </tr> -->
                        <tr>
                            <td>MMR</td>
                            <td><?= $meta['MMR'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) { ?>

                <div>
                    <a href="<?= ROOTPATH ?>/str/edit/<?= $str_id ?>" class="btn btn-primary mt-20"><i class="fas fa-edit"></i> Edit</a>

                </div>
            <?php } ?>

        </div>
    </div>
    <div class="col-md-6">

        <div class="card" id="profile-form">
            <h2 class="card-title">Profile</h2>
            <div class="card-content">
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
                            <!-- <td><?= $row['allele'] ?></td> -->
                            <?php foreach ($allele as $row) { ?>
                                <td><?= str_val($row['value'], $locus) ?></td>
                            <?php } ?>

                        </tr>
                    <?php } ?>
                </table>


            </div>
            <div>
                <a href="<?= ROOTPATH ?>/str/search?str_id=<?= $str_id ?>" class="btn btn-primary mt-20"><i class="fas fa-search"></i> STR Search</a>

            </div>
        </div>
    </div>
</div>