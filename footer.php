</div>
<nav class="navbar navbar-fixed-bottom text-muted">

    <div class="dropdown d-inline-block d-sm-none dropup with-arrow float-left">
        <button class="btn text-muted" data-toggle="dropdown" type="button" id="footer-mobile" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis" aria-hidden="true"></i>
        </button>
        <div class="dropdown-menu" aria-labelledby="footer-mobile">
            <span class="dropdown-item">Last updated: <?= date('M Y', filemtime(BASEPATH . '/index.php')) ?></span>
            <a class="dropdown-item" href="<?= ROOTPATH ?>/imprint">Imprint</a>
        </div>
    </div>

    <ul class="h-list d-none d-sm-inline-block mx-20">
        <li class="text-monospace">Last updated: <?= date('M Y', filemtime(BASEPATH . '/index.php')) ?></li>
        <li>
            <a class="text-muted" href="<?= ROOTPATH ?>/imprint">Imprint</a>
        </li>

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) { ?>
            <li>
                <a class="text-muted" href="<?= ROOTPATH ?>/user/logout">Logout</a>
            </li>
        <?php } else { ?>
            <li>
                <a class="text-muted" href="<?= ROOTPATH ?>/user/login">Login</a>
            </li>
        <?php } ?>
        <li>
            <a class="text-muted" href="<?= ROOTPATH ?>/documentation#cite">Cite us</a>
        </li>

    </ul>
    <a href="http://dsmz.de" target="_blank" rel="noopener noreferrer" class="d-inline-block ml-auto mr-20">
        <img src="<?= ROOTPATH ?>/img/dsmz_full.svg" alt="DSMZ">
    </a>
</nav>
</div>
<!-- Content wrapper end -->
</div>
<script>
    const ROOTPATH = "<?= ROOTPATH ?>";
</script>
<script src="<?= ROOTPATH ?>/js/jquery-3.3.1.min.js"></script>
<script src="<?= ROOTPATH ?>/js/halfmoon.min.js"></script>

<?php if (isset($project) && isset($plottype)) { ?>
    <script src="<?= ROOTPATH ?>/js/jquery.amsify.suggestags.js"></script>
    <script src="<?= ROOTPATH ?>/js/d3.v7.min.js"></script>
    <script src='<?= ROOTPATH ?>/js/plotly-2.4.2.min.js'></script>
    <script src="<?= ROOTPATH ?>/js/app.js?<?= filemtime(BASEPATH . '/js/app.js') ?>"></script>
<?php } elseif (isset($project) && $project == 'str') { ?>
    <script src="<?= ROOTPATH ?>/js/str.js?<?= filemtime(BASEPATH . '/js/str.js') ?>"></script>
<?php } elseif (isset($cellline)) { ?>
    <script src="<?= ROOTPATH ?>/js/d3.v7.min.js"></script>
    <script src='<?= ROOTPATH ?>/js/plotly-2.4.2.min.js'></script>
    <script src="<?= ROOTPATH ?>/js/celllines.js"></script>
<?php } ?>

<script>

if (typeof MESSAGE !== "undefined" && MESSAGE.length !== 0){
    halfmoon.initStickyAlert({
            content: MESSAGE,
            alertType: "alert-primary",
        })
}

</script>


</body>

</html>