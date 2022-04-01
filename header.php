<?php
$breadcrumb = $breadcrumb ?? [];
$pagetitle = array('DSMZCellDive');
foreach ($breadcrumb as $crumb) {
    array_push($pagetitle, $crumb['name']);
}
$pagetitle = implode(' | ', array_reverse($pagetitle));


if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle)
    {
        return $needle !== '' && strpos($haystack, $needle) !== false;
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta name="viewport" content="width=device-width" />

    <link rel="icon" href="<?= ROOTPATH ?>/img/favicon-dsmzcelldive.png">
    <title><?= $pagetitle ?? 'DSMZCellDive' ?></title>

    <script src="https://kit.fontawesome.com/65c1a321c3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?= ROOTPATH ?>/css/halfmoon-variables.css">
    <link rel="stylesheet" href="<?= ROOTPATH ?>/css/amsify.suggestags.css">
    <link rel="stylesheet" href="<?= ROOTPATH ?>/css/style.css?<?= filemtime(BASEPATH . '/css/style.css') ?>">
    <script type="text/javascript">
        // <!--
        function UnCryptMailto(s) {
            var n = 0;
            var r = "";
            for (var i = 0; i < s.length; i++) {
                n = s.charCodeAt(i);
                if (n >= 8364) {
                    n = 128;
                }
                r += String.fromCharCode(n - 1);
            }
            return r;
        }

        function linkTo_UnCryptMailto(s) {
            location.href = UnCryptMailto(s);
        }
        // --> 
    </script>

    <?php if (isset($_GET['msg'])) { ?>
        <script>
            const MESSAGE = '<?= str_replace('-', ' ', htmlspecialchars($_GET['msg'])) ?>';
        </script>
    <?php } ?>


    <?php if (INSIGHT) { ?>
        <!-- Matomo -->
        <script>
            var _paq = window._paq = window._paq || [];
            /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
            _paq.push(['trackPageView']);
            _paq.push(['enableLinkTracking']);
            (function() {
                var u = "https://piwik.dsmz.de/";
                _paq.push(['setTrackerUrl', u + 'matomo.php']);
                _paq.push(['setSiteId', '14']);
                var d = document,
                    g = d.createElement('script'),
                    s = d.getElementsByTagName('script')[0];
                g.async = true;
                g.src = u + 'matomo.js';
                s.parentNode.insertBefore(g, s);
            })();
        </script>
        <noscript>
            <p><img src="https://piwik.dsmz.de/matomo.php?idsite=14&amp;rec=1" style="border:0;" alt="" /></p>
        </noscript>
        <!-- End Matomo Code -->
    <?php } ?>

</head>

<body class="with-custom-webkit-scrollbars with-custom-css-scrollbars  <?= $_COOKIE['halfmoon_preferredMode'] ?? '' ?>" data-dm-shortcut-enabled="true" data-sidebar-shortcut-enabled="true">
    <!-- Modals go here -->
    <div id="loader">
        <span></span>
    </div>

    <div class="modal" id="tumour-description" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" role="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
                <h5 class="modal-title">LL-100 tumour abbreviations</h5>
                <?php include 'll100-abbreviations.html'; ?>
                <div class="text-right mt-20">
                    <a href="#" class="btn mr-5" role="button">Close</a>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="modal" id="matrix-description" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" role="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
                <h5 class="modal-title">Matrix description</h5>
                <div class="content">
                    <p>
                        Different to the description of the cited paper, RNA-seq data were quantified via <a href="https://salmon.readthedocs.io/en/latest/index.html" target="_blank" rel="noopener noreferrer">Salmon</a> and normalised via <a href="https://www.bioconductor.org/packages/3.6/bioc/vignettes/DESeq2/inst/doc/DESeq2.html" target="_blank" rel="noopener noreferrer">DESeq2</a>.
                        Following values are given:
                    </p>
                    <ul class="unordered-list">
                        <li><b>normalised values</b>: calculated via DESeq2 within one RNA-seq project</li>
                        <li><b>tpm</b>: transcripts per million reads - a rough measure to compare between samples</li>
                        <li><b>estimated counts</b>: Salmon's estimate of counted reads per transcripts, corresponds to counted raw reads; limited comparability between samples but recommended as basis for further statistical analysis</li>
                    </ul>
                    <p>
                        <i class="far fa-exclamation-triangle"></i>
                        Please note, that if all NGS projects are selected, TPM and count values are available only, since normalisation have been calculated for each project only. For specific statistical analyses please take the estimated counts of your samples of interest as starting point.
                    </p>
                </div>
                <div class="text-right mt-20">
                    <a href="#" class="btn mr-5" role="button">Close</a>
                </div>
            </div>
        </div>
    </div> -->


    <div class="modal" id="text-input" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" role="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
                <h5 class="modal-title">Text input</h5>
                <p class="text-muted">
                    Here you can copy and paste the header of your file, e.g. Excel, and the respective values.
                    Columns must be separated by tabs or spaces.
                    The header must include loci and alleles, separated by an underscore.
                    If no allele is given, it is assumed to be the first allele. Example: <code class="code">D13S317_2</code>
                </p>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Your marker ordering</td>
                            <td><textarea id="str-header" class="form-control" name="header" rows="3" cols="100"></textarea></td>
                        </tr>
                        <tr>
                            <td>Your marker values</td>
                            <td><textarea id="str-values" class="form-control" name="values" rows="3" cols="100" required></textarea></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right mt-20">
                    <button class="btn btn-primary" type="button" onclick="validateText();"><i class="fas fa-chevron-right"></i> Translate</button>
                    <button class="btn" type="button" onclick="exampleText();"><i class="fas fa-question"></i> Example</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Page wrapper start -->
    <div class="page-wrapper with-sidebar with-navbar" data-sidebar-type="full-height overlayed-sm-and-down">

        <div class="sticky-alerts"></div>

        <!-- Navbar start -->
        <nav class="navbar">

            <div class="navbar-content">
                <button class="btn btn-action" type="button" onclick="halfmoon.toggleSidebar()">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    <span class="sr-only">Toggle sidebar</span>
                </button>
            </div>

            <div class="navbar-content">
                <nav aria-label="DSMZCellDive breadcrumbs">
                    <ul class="breadcrumb">
                        <?php
                        $breadcrumb = $breadcrumb ?? [];
                        if (!empty($breadcrumb)) {
                            // array_unshift($breadcrumb , 'Home');
                            echo '<li class="breadcrumb-item"><a href="' . ROOTPATH . '/">Home</a></li>';
                            foreach ($breadcrumb as $crumb) {
                                if (!isset($crumb['path'])) {
                                    echo '<li class="breadcrumb-item active" aria-current="page"><a href="#">' . $crumb['name'] . '</a></li>';
                                } else {
                                    echo '<li class="breadcrumb-item"><a href="' . ROOTPATH . $crumb['path'] . '">' . $crumb['name'] . '</a></li>';
                                }
                            }
                        }
                        ?>
                    </ul>
                </nav>
            </div>

            <div class="navbar-content ml-auto">
                <button class="btn btn-action" type="button" onclick="halfmoon.toggleDarkMode()" title="Darkmode">
                    <i class="fas fa-lg fa-moon" aria-hidden="true"></i>
                </button>
            </div>

        </nav>

        <div class="sidebar-overlay" onclick="halfmoon.toggleSidebar()"></div>

        <!-- Navbar end -->
        <div class="sidebar">
            <div class="sidebar-menu">
                <!-- Sidebar brand -->
                <a href="<?= ROOTPATH ?>/" class="sidebar-brand position-relative">
                    <img src="<?= ROOTPATH ?>/img/logo.svg" alt="DSMZ CellDive">
                    <span class="text-danger font-weight-bold position-absolute font-size-16" style="bottom: -.5rem; left: 7.3rem">BETA</span>
                </a>
                <!-- <div class="sidebar-content">
                    <form action="<?= ROOTPATH ?>/celllines" method="get">
                    <input type="text" class="form-control" placeholder="Search cell line" name="search">
                </form> </div>-->
                <br>
                <!-- Sidebar links and titles -->
                <h5 class="sidebar-title">Content overview</h5>
                <div class="sidebar-divider"></div>
                <a href="<?= ROOTPATH ?>/celllines" class="sidebar-link sidebar-link-with-icon <?= str_contains($_SERVER['REQUEST_URI'], '/cellline') ? 'active' : '' ?>">
                    <span class="sidebar-icon">
                        <!-- <i class="fas fa-disease" aria-hidden="true"></i> -->
                        <i class="fak fa-cellline"></i>
                    </span>
                    Cell lines
                </a>
                <a href="<?= ROOTPATH ?>/genes" class="sidebar-link sidebar-link-with-icon <?= str_contains($_SERVER['REQUEST_URI'], '/gene') ? 'active' : '' ?>">
                    <span class="sidebar-icon">
                        <i class="fas fa-dna" aria-hidden="true"></i>
                    </span>
                    Genes
                </a>

                <br>
                <h5 class="sidebar-title">RNA-seq</h5>
                <div class="sidebar-divider"></div>
                <?php
                $rna_projects = [];
                foreach ($rna_projects as $rna_p) {
                ?>

                <?php }

                $project = $project ?? '';
                $snippet = "";

                if (isset($project) && $project !== 'str') {
                    $snippet = '
                    <a class="sidebar-link sidebar-link-with-icon ' . (($plottype ?? '') == 'bar' ? 'active' : '') . '" href="' . ROOTPATH . '/rna/' . $project . '/bar">
                    <span class="sidebar-icon"><i class="fad fa-chart-column"></i></span>
                         Bar chart
                    </a>';

                    if ($project !== "all") {
                        $snippet .= '<a class="sidebar-link sidebar-link-with-icon ' . (($plottype ?? '') == 'heat' ? 'active' : '') . '" href="' . ROOTPATH . '/rna/' . $project . '/heat">
                        <span class="sidebar-icon"><i class="fad fa-table-cells-large"></i></span>
                             Heat map
                        </a>';
                    }
                }

                ?>

                <a href="<?= ROOTPATH ?>/rna/all" class="sidebar-link <?= $project == 'all' ? 'active' : '' ?>">All RNA-seq projects</a>
                <?php if ($project == 'all') {
                    echo $snippet;
                } ?>
                <a href="<?= ROOTPATH ?>/rna/ll-100" class="sidebar-link <?= $project == 'll-100' ? 'active' : '' ?>">LL-100</a>
                <?php if ($project == 'll-100') {
                    echo $snippet;
                } ?>
                <a href="<?= ROOTPATH ?>/rna/breast-cancer" class="sidebar-link <?= $project == 'breast-cancer' ? 'active' : '' ?>">Breast Cancer <span class="badge">SOON</span></a>
                <?php if ($project == 'breast-cancer') {
                    // echo $snippet;
                } ?>
                <!-- <a href="<?= ROOTPATH ?>/rna/prostate-cancer" class="sidebar-link <?= $project == 'prostate-cancer' ? 'active' : '' ?>">Prostate cancer</a>
                <?php if ($project == 'prostate-cancer') {
                    echo $snippet;
                } ?> -->
                <br>
                <h5 class="sidebar-title">Authentication tools</h5>
                <div class="sidebar-divider"></div>
                <a href="<?= ROOTPATH ?>/str" class="sidebar-link <?= $project == 'str' ? 'active' : '' ?>">Short Tandem Repeats (human)</a>
                <?php if ($project == 'str') { ?>
                    <a class="sidebar-link sidebar-link-with-icon <?= ($pagename ?? 'browse') == 'search' ? 'active' : '' ?>" href="<?= ROOTPATH ?>/str/search">
                        <span class="sidebar-icon"><i class="fad fa-search"></i></span>
                        STR Profile Search
                    </a>
                    <a class="sidebar-link sidebar-link-with-icon <?= ($project == 'str' && ($pagename ?? 'browse') == 'browse') ? 'active' : '' ?>" href="<?= ROOTPATH ?>/str/browse">
                        <span class="sidebar-icon"><i class="fad fa-table-list"></i></span>
                        STR Profile Browser
                    </a>

                    <?php
                    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) { ?>

                        <a class="sidebar-link sidebar-link-with-icon <?= ($project == 'str' && ($pagename ?? 'browse') == 'import') ? 'active' : '' ?>" href="<?= ROOTPATH ?>/str/import">
                            <span class="sidebar-icon"><i class="fad fa-upload"></i></span>
                            Import CSV file
                        </a>
                <?php }
                } ?>
                <a class="sidebar-link <?= ($project == 'coi') ? 'active' : '' ?>" href="<?= ROOTPATH ?>/coi">
                    COI DNA Barcoding (animal)
                </a><?php if ($project == 'coi') { ?>
                    <a class="sidebar-link sidebar-link-with-icon <?= ($project == 'coi' && ($pagename ?? 'browse') == 'browse') ? 'active' : '' ?>" href="<?= ROOTPATH ?>/coi/browse">
                        <span class="sidebar-icon"><i class="fad fa-table-list"></i></span>
                        COI Browser
                    </a>
                <?php } ?>
                <br>

                <h5 class="sidebar-title">Other data</h5>
                <div class="sidebar-divider"></div>
                <a href="<?= ROOTPATH ?>/hla" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'], '/hla') ? 'active' : '' ?>">HLA Typing</a>
                <br>
                <h5 class="sidebar-title">About</h5>
                <div class="sidebar-divider"></div>
                <a href="<?= ROOTPATH ?>/documentation" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'], '/documentation') ? 'active' : '' ?>">
                    <i class="far fa-book"></i> Documentation
                </a>
            </div>
            <!-- <div class="sidebar-content">
            <?= $project ?>
            </div> -->
        </div>
        <!-- Content wrapper start -->
        <div class="content-wrapper">
            <div class="container-fluid p-10">
                <!-- <div class="container p-5" id="app"> -->