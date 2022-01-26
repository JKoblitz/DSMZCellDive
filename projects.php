<div class="content">
    <h2 class="">RNA-seq</h2>
    <a href="<?= ROOTPATH ?>/rna/all" class="btn"><i class="fas fa-search"></i> See data from all RNA-seq projects</a>
</div>
<div class="row my-20 card-deck">
    <div class="col-md-6 col-xl-4">
        <div class="card p-0">
            <img src="<?= ROOTPATH ?>/img/ll-100.jpg" class="w-full rounded-top" alt="SKNO-1 cells">
            <h2 class="card-title m-20">
                LL-100
            </h2>
            <div class="card-content mx-20 text-muted">
                The LL-100 panel comprises 22 entities of leukemia and lymphoma including lymphoid and
                myeloid malignancies &hellip;
            </div>
            <div class="m-20">
                <a href="<?= ROOTPATH ?>/rna/ll-100" class="btn btn-primary mb-5"><i class="fas fa-search"></i> View project</a>
                <a href="<?= ROOTPATH ?>/rna/ll-100/bar" class="btn"><i class="fas fa-chart-column"></i> Gene expressions</a>
                
            </div>
        </div>
    </div>
    <div class="v-spacer d-md-none"></div>
    <div class="col-md-6 col-xl-4">
        <div class="card p-0">
            <img src="<?= ROOTPATH ?>/img/breast-cancer.jpg" class="w-full rounded-top" alt="MFM-223 cells">
            <h2 class="card-title m-20">
                Breast cancer
            </h2>
            <div class="card-content mx-20 text-muted">
                Coming soon &hellip;
            </div>
            <div class="m-20">
                <a href="#" class="btn btn-primary mb-5 disabled"><i class="fas fa-search"></i> View</a> 
                <!-- <?= ROOTPATH ?>/rna/breast-cancer -->
            </div>
        </div>
    </div>
</div>

<hr>


<div class="content">
    <h2>Authentication tools</h2>
</div>
<div class="row my-20 card-deck">
    <div class="col-md-6 col-xl-4">
        <div class="card p-0">
            <img src="<?= ROOTPATH ?>/img/dna.jpg" class="w-full rounded-top" alt="...">
            <h2 class="card-title m-20">
                STR Profile Search (human)
            </h2>
            <div class="card-content mx-20 text-muted">
                The DSMZ, together with the ATCC, JCRB, and RIKEN repositories, have generated comprehensive databases of short tandem repeats (STR) cell line proﬁles. Use of a consensus STR panel now enables multi-center interactive searches, work piloted at the DSMZ. To render it user friendly, a simple search engine for interrogating STR cell line proﬁles is available. Aided by simple prompts, users can input their own cell line STR data to retrieve best matches with authenticated cell lines listed on the database.
            </div>
            <div class="m-20">
                <a href="<?= ROOTPATH ?>/str" class="btn btn-primary mb-5"><i class="fas fa-search"></i> Read more</a>
                <a href="<?= ROOTPATH ?>/str/search" class="btn"><i class="fas fa-fingerprint"></i> STR Profile Search</a>
            </div>
        </div>
    </div>
    <div class="v-spacer d-md-none"></div>
    <div class="col-md-6 col-xl-4">
        <div class="card p-0">
            <img src="<?= ROOTPATH ?>/img/dna2.jpg" class="w-full rounded-top" alt="...">
            <h2 class="card-title m-20">
                COI DNA Barcoding (animal)
            </h2>
            <div class="card-content mx-20 text-muted">
                DNA barcoding is a taxonomic method, that uses a portion of the cytochrome c oxidase I (COI) gene
                to identify it as belonging to a particular species.
                Through this method, cell lines are identified to registered species based on comparison
                to a reference library.
            </div>
            <div class="m-20">
                <a href="<?= ROOTPATH ?>/coi" class="btn btn-primary mb-5"><i class="fas fa-search"></i> Read more</a>
                <a href="<?= ROOTPATH ?>/coi/browse" class="btn"><i class="fas fa-barcode"></i> COI DNA Browser</a>
            </div>
        </div>
    </div>

</div>