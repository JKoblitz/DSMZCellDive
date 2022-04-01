<?php

$gene = filter_var($_GET['gene'] ?? '', FILTER_SANITIZE_STRING);
if ($gene == '' && isset($_COOKIE['dsmzcelldive_gene'])) {
    $gene = $_COOKIE['dsmzcelldive_gene'];
}

if ($gene == '') {
    if ($plottype == 'heat') {
        $gene = "FGR,CFH,FUCA2,GCLC,NFYA,STPG1,NIPAL3";
    } else {
        $gene = "GAPDH";
    }
}

$genes = explode(",", $gene);
if ($plottype == 'bar' && count($genes) > 5) {
    $gene = implode(';', array_slice($genes, 0, 5));
}

$matrix = filter_var($_GET['matrix'] ?? '', FILTER_SANITIZE_STRING);
if ($matrix == '' && isset($_COOKIE['dsmzcelldive_matrix'])) {
    $matrix = $_COOKIE['dsmzcelldive_matrix'];
}

$multigene = filter_var($_GET['multigene'] ?? '', FILTER_SANITIZE_STRING);
if ($multigene == '' && isset($_COOKIE['dsmzcelldive_multigene'])) {
    $multigene = $_COOKIE['dsmzcelldive_multigene'];
}
if ($multigene == '') {
    $multigene = 'gene';
}

$selectby = filter_var($_GET['selectby'] ?? 'groups', FILTER_SANITIZE_STRING);
$samples = filter_var($_GET['samples'] ?? '', FILTER_SANITIZE_STRING);
if ($samples == "" && isset($_COOKIE['dsmzcelldive_samples'])) {
    $samples = $_COOKIE['dsmzcelldive_samples'];
    $selectby = explode(":", $samples)[0];
    $samples = explode(":", $samples)[1];
}
if ($samples == "") {
    $samples = "AML_myelo,CML_myelo_BC";
}
$samples = explode(",", $samples);
// if (empty($samples)) {
//     $samples = ['AML_myelo', 'AML_myelo'];
// }

if ($plottype === 'heat') { ?>
    <style>
        iframe {
            border: none;
            width: 100%;
            height: 500px;
        }
    </style>
    <!-- <script src="<?= ROOTPATH ?>/php/temp/files/htmlwidgets-1.5.4/htmlwidgets.js"></script>
  <script src="<?= ROOTPATH ?>/php/temp/files/plotly-binding-4.10.0/plotly.js"></script>
  <script src="<?= ROOTPATH ?>/php/temp/files/typedarray-0.1/typedarray.min.js"></script>
  <script src="<?= ROOTPATH ?>/php/temp/files/jquery-3.5.1/jquery.min.js"></script>
  <link href="<?= ROOTPATH ?>/php/temp/files/crosstalk-1.2.0/css/crosstalk.min.css" rel="stylesheet" />
  <script src="<?= ROOTPATH ?>/php/temp/files/crosstalk-1.2.0/js/crosstalk.min.js"></script>
  <link href="<?= ROOTPATH ?>/php/temp/files/plotly-htmlwidgets-css-2.5.1/plotly-htmlwidgets.css" rel="stylesheet" />
  <script src="<?= ROOTPATH ?>/php/temp/files/plotly-main-2.5.1/plotly-latest.min.js"></script> -->

<?php } ?>




<div class="row">
    <div class="col-md-3">
        <div class="alert bg-light-lm mb-10">
            <!-- <a href="<?= ROOTPATH ?>/rna/<?= $project ?>/<?= $plottype == 'heat' ? 'bar' : 'heat' ?>" class="btn mb-10"><?= $plottype == 'heat' ? 'Bar Chart' : 'Heatmap' ?></a> -->
            <!-- <form class="well" role="complementary"> -->
            <a href="<?= ROOTPATH ?>/documentation#rna-seq" class="btn btn-help float-right btn-sm"><i class="far fa-lg fa-book mr-5"></i></a>

            <h5 class="alert-heading">Data selection</h5>


            <div class="form-group position-relative">
                <label for="gene">Gene</label>
                <input type="text" name="gene" class="form-control" id="gene" value="<?= $gene ?>">
                <a href="#" onclick="clearSuggestion();" class="float-left" title="Remove all genes from the selection"><i class="fas fa-close"></i> clear</a>

                <div class="text-right">
                    <a href="<?= ROOTPATH ?>/genes?project=<?= $project ?>" target="_blank"><i class="far fa-external-link"></i> list of genes</a>
                </div>
            </div>
            <div class="form-group">
                <label for="multigene" class="mb-0"><?=$plottype == 'heat' ? 'Cluster': 'Group' ?> by: </label><br>

                <div class="custom-radio d-inline-block mr-5">
                    <input type="radio" name="multigene" id="multigene-gene" value="gene" <?= $multigene != 'cell' ? 'checked="checked"' : '' ?>>
                    <label for="multigene-gene">Gene</label>
                </div>
                <div class="custom-radio d-inline-block mr-5">
                    <input type="radio" name="multigene" id="multigene-cell" value="cell" <?= $multigene == 'cell' ? 'checked="checked"' : '' ?>>
                    <label for="multigene-cell">Cell line</label>
                </div>
                <?php if ($plottype == 'heat') { ?>

                    <div class="custom-radio d-inline-block">
                        <input type="radio" name="multigene" id="multigene-none" value="none" <?= $multigene == 'none' ? 'checked="checked"' : '' ?>>
                        <label for="multigene-none">None</label>
                    </div>
                <?php } else { ?>
                    <br>
                    <small class="text-muted">only effective if multiple genes are selected</small>
                <?php } ?>

            </div>
            <hr>
            <div id="rnaseq-gene-fieldset" class="shinyngsFieldset">
                <div class="form-group">
                    <label for="select-by">Select samples by</label>
                    <select id="select-by" class="form-control" name="color" onchange="ui_selectby(this)" autocomplete="off">
                        <option value="groups" <?= $selectby == 'groups' ? 'selected' : '' ?>>Tumour entity</option>
                        <option value="celllines" <?= $selectby == 'celllines' ? 'selected' : '' ?>>Cell lines</option>
                    </select>
                </div>
                <!-- <button class="btn btn-sm" onclick="ui_selectall(this)">Select all</button> -->
                <div class="custom-checkbox mb-10">
                    <input type="checkbox" onchange="ui_selectall(this)" id="check_select-all">
                    <label for="check_select-all">Select all</label>
                </div>
                <div class="cell-groups" <?= $selectby == 'celllines' ? 'style="display: none;"' : '' ?>>
                    <?php
                    if ($project == 'all') {
                        $sql = 'SELECT DISTINCT tumour_short FROM celllines WHERE tumour_short IS NOT NULL AND rna_seq IS NOT NULL ORDER BY tumour_short ASC';
                    } else {
                        $sql = "SELECT DISTINCT tumour_short FROM celllines WHERE rna_seq LIKE '$project' ORDER BY tumour_short ASC";
                    }
                    $query = $db->query($sql);
                    $tumours = $query->fetchAll(PDO::FETCH_COLUMN);
                    foreach ($tumours as $i => $tumour) {
                        $checked = '';
                        if (in_array($tumour, $samples)) {
                            $checked = 'checked="checked"';
                        }

                    ?>
                        <div class="custom-checkbox d-inline-block">
                            <input type="checkbox" name="cell-group" value="<?= $tumour ?>" id="check_<?= $tumour ?>" <?= $checked ?>>
                            <label for="check_<?= $tumour ?>"><?= $tumour ?></label>
                        </div>
                    <?php } ?>
                    <div class="text-right">
                        <a href="#tumour-description">see tumour list</a>
                    </div>
                </div>
                <div class="cell-celllines" <?= $selectby == 'groups' ? 'style="display: none;"' : '' ?>>
                    <?php
                    if ($project == 'all') {
                        $sql = 'SELECT cellline FROM celllines WHERE rna_seq IS NOT NULL ORDER BY cellline ASC';
                    } else {
                        $sql = "SELECT cellline FROM celllines WHERE rna_seq LIKE '$project' ORDER BY cellline ASC";
                    }
                    $query = $db->query($sql);
                    $celllines = $query->fetchAll(PDO::FETCH_COLUMN);
                    foreach ($celllines as $i => $cellline) {
                        $checked = '';
                        if ((empty($samples) && $i < 5) || in_array($cellline, $samples)) {
                            $checked = 'checked="checked"';
                        }
                    ?>
                        <div class="custom-checkbox d-inline-block">
                            <input type="checkbox" name="cell-celllines" value="<?= $cellline ?>" id="check_<?= $cellline ?>" <?= $checked ?>>
                            <label for="check_<?= $cellline ?>"><?= $cellline ?></label>
                        </div>
                    <?php } ?>
                </div>
                <hr>
                <div class="form-group">
                    <label for="matrix">Matrix</label>
                    <select id="matrix" class="form-control" name="matrix" onchange="updateChart()">
                        <?php if ($project != 'all') { ?>
                            <option value="norm" <?= $matrix == 'norm' ? 'selected' : '' ?>>normalised</option>
                        <?php } ?>
                        <option value="counts" <?= $matrix == 'counts' ? 'selected' : '' ?>>estimated counts</option>
                        <option value="tpm" <?= $matrix == 'tpm' ? 'selected' : '' ?>>tpm</option>
                    </select>
                    <div class="text-right">
                        <a class="text-secondary" href="<?= ROOTPATH ?>/documentation#matrix-description"><i class="fal fa-book"></i> matrix description</a>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" type="button" onclick="updateChart()">Update chart</button>
            <a class="btn btn-square" href="<?= ROOTPATH ?>/rna/<?= $project ?>/<?= $plottype ?>" id="share-btn" data-toggle="tooltip" data-title="Copy link to this page by right-click > Copy link">
                            <i class="far fa-lg fa-fw fa-share-nodes"></i>
        </a>

        </div>
        <?php if ($plottype !== 'heat') { ?>
            <!-- <div class="alert  bg-light-lm">
                <h5 class="alert-heading">Appearance</h5>
                <div class="form-group">
                    <label for="group-color">Color palette</label>
                    <?php if ($plottype == "bar") { ?>
                        <select id="group-color" class="form-control" name="color" onchange="updateColors()">
                            <option value="Dark2" selected>Dark2</option>
                            <option value="Set1">Set1</option>
                            <option value="Set2">Set2</option>
                            <option value="Set3">Set3</option>
                            <option value="Pastel1">Pastel1</option>
                            <option value="Pastel2">Pastel2</option>
                            <option value="Paired">Paired</option>
                            <option value="Accent">Accent</option>
                            <option value="Viridis">Viridis</option>
                        </select>
                    <?php } else { ?>
                        <select id="group-color" class="form-control" name="color" onchange="updateColors()">
                            <option value="myOwn" selected>Red, yellow, blue</option>
                            <option value="Spectral">Spectral</option>
                            <option value="Hot">Hot</option>
                            <option value="Viridis">Viridis</option>
                            <option value="YlGnBu">Blue, yellow, green</option>
                            <option value="Earth">Earth</option>
                        </select>
                    <?php } ?>

                </div>
            </div> -->
        <?php } ?>
    </div>
    <div class="col-md-9">
        <div class="content">
            <div id="messages"></div>
            <?php if ($plottype == 'bar') { ?>
                <div id="chart"></div>
            <?php } else { ?>

                <!-- <div id="htmlwidget_container">
                    <div id="htmlwidget-1" style="width:960px;height:500px;" class="plotly"></div>
                </div> -->
                <div id="chart"></div>

                <!-- Data and settings for the plot -->
                <!-- <script id="acutal-data" type="application/json" data-for="htmlwidget-1">
                    {}
                </script>
                <script type="application/htmlwidget-sizing" data-for="htmlwidget-1">
                    {"viewer":{"width":450,"height":350,"padding":5,"fill":true},"browser":{"width":960,"height":500,"padding":5,"fill":true}}
                </script> -->

            <?php } ?>

            <div id="content" class="table-responsive content"></div>

        </div>
    </div>
</div>


<script>
    const TYPE = '<?= $plottype ?>';
</script>