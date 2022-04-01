<div class="row row-eq-spacing">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="alert alert-primary filled-dm">
            <b class="font-size-16">Cell lines</b>
            <div class="font-size-24">
                <a class="text-muted-dm" href="<?= ROOTPATH ?>/celllines">922</a>
            </div>

            <form action="<?= ROOTPATH ?>/celllines" method="get">
                <div class="input-group">
                    <input type="text" class="form-control alt-dm" placeholder="Search cell line" name="search">
                    <div class="input-group-append">
                        <button class="btn bg-white-dm text-primary-dm" type="submit"><i class="fak fa-fw fa-lg fa-cellline"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="v-spacer d-sm-none"></div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="alert alert-primary filled-dm">
            <b class="font-size-16">Genes with RNA-seq data</b>
            <div class="font-size-24">
                <a class="text-muted-dm" href="<?= ROOTPATH ?>/genes">20,297</a>
            </div>
            <form action="<?= ROOTPATH ?>/genes" method="get">
                <div class="input-group">
                    <input type="text" class="form-control alt-dm" placeholder="Search gene" name="search">
                    <div class="input-group-append">
                        <button class="btn bg-white-dm text-primary-dm" type="submit"><i class="far fa-fw fa-lg fa-dna"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="v-spacer d-xl-none"></div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="alert alert-primary filled-dm">
            <b class="font-size-16">STR profiles</b>
            <div class="font-size-24">
                <a class="text-muted-dm" href="<?= ROOTPATH ?>/str/browse">4,555</a>
            </div>
            <div class="_text-right">
                <a href="<?= ROOTPATH ?>/str/search" class="btn bg-white-dm text-primary-dm btn-block"><i class="far fa-fw fa-lg fa-fingerprint"></i> STR Profile Search</a>
            </div>
        </div>
    </div>
    <div class="v-spacer d-sm-none"></div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="alert alert-primary filled-dm">
            <b class="font-size-16">COI DNA barcodes</b>
            <div class="font-size-24">
                <a class="text-muted-dm" href="<?= ROOTPATH ?>/coi/browse">197</a>
            </div>
            <div class="_text-right">
                <a href="<?= ROOTPATH ?>/coi/browse" class="btn bg-white-dm text-primary-dm btn-block"><i class="far fa-fw fa-lg fa-barcode"></i> COI mtDNA Sequence Browser</a>
            </div>
        </div>
    </div>
</div>

<hr>