<?php
include_once 'php/_config.php';
include_once 'php/Parsedown.php';

$Parsedown = new Parsedown();

$report = $Parsedown->text($coi['markdown']);
$report = str_replace('<table>', '<table class="table">', $report);
?>

<div class="card">
    <a href="<?= ROOTPATH ?>/documentation#coi" class="btn btn-help float-right"><i class="far fa-lg fa-book mr-5"></i> <span class="d-none d-md-inline">Help</span></a>
    <h3 class="card-title">COI DNA Barcoding Report</h3>
    <div class="parsedown">
        <?= $report ?>
    </div>

    <a href="<?=ROOTPATH?>/cellline/ACC-<?=$cell_id?>" class="btn btn-primary">Go to cell line</a>

    <a href="<?=ROOTPATH?>/coi/pdf/<?=$cell_id?>" class="btn" target="_blank">Download as PDF (beta)</a>

    
</div>