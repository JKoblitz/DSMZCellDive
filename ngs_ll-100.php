
<div class="container">

    <div class="content">

        <div class="row">
            <div class="col-lg-6 align-vertical-center">
                <div class="content mx-auto" style="max-width: 50rem;">
                    <h2 class="mb-0 ">The LL-100 panel</h2>
                    <h4 class="mt-0 text-primary font-weight-normal">100 cell lines for blood cancer studies</h5>

                        <p>
                        The RNA-seq data from the LL-100 panel (<a href='https://www.ebi.ac.uk/arrayexpress/experiments/E-MTAB-7721'>ArrayExpress</a>; <a href="https://www.ebi.ac.uk/ena/browser/view/PRJEB30312" target="_blank" rel="noopener noreferrer">ENA</a>) allow expression analysis for single genes (bar chart) or for sets of genes (bar chart, heat map). Different to the description of the cited paper below, RNA-seq data were quantified via <a href='https://salmon.readthedocs.io/en/latest/index.html'>Salmon</a> and normalised via <a href='https://www.bioconductor.org/packages/3.6/bioc/vignettes/DESeq2/inst/doc/DESeq2.html'>DESeq2</a>.
                        </p>
                        <a href="<?= ROOTPATH ?>/rna/ll-100/bar" class="btn btn-primary mt-10"><i class="fas fa-chart-column"></i> Bar chart</a>
                        <a href="<?= ROOTPATH ?>/rna/ll-100/heat" class="btn btn-success mt-10 text-white"><i class="fas fa-table-cells-large"></i> Heat map</a>
                        <a href="<?= ROOTPATH ?>/documentation#rna-seq" class="btn btn-help"><i class="far fa-lg fa-book mr-5"></i> <span class="d-none d-md-inline">Help</span></a>

                </div>

            </div>
            <div class="col-lg-6 align-vertical-center">
                <div class="content">
                    <img src="<?= ROOTPATH ?>/img/ll-100.jpg" alt="" class="img-fluid rounded">
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-lg">
            <div class="card">
                <p class="text-muted mt-0">
                    Hilmar Quentmeier, Claudia Pommerenke, Wilhelm G. Dirks, Sonja Eberth, Max Koeppel, Roderick A. F. MacLeod, Stefan Nagel, Klaus Steube, Cord C. Uphoff & Hans G. Drexler
                </p>
                <hr>
                <p>
                    For many years, immortalized cell lines have been used as model systems for cancer research. Cell line panels were established for basic research and drug development, but did not cover the full spectrum of leukemia and lymphoma. Therefore, we now developed a novel panel (LL-100), 100 cell lines covering 22 entities of human leukemia and lymphoma including T-cell, B-cell and myeloid malignancies. Importantly, all cell lines are unequivocally authenticated and assigned to the correct tissue. Cell line samples were proven to be free of mycoplasma and non-inherent virus contamination. Whole exome sequencing and RNA-sequencing of the 100 cell lines were conducted with a uniform methodology to complement existing data on these publicly available cell lines. We show that such comprehensive sequencing data can be used to find lymphoma-subtype-characteristic copy number aberrations, mRNA isoforms, transcription factor activities and expression patterns of NKL homeobox genes. These exemplary studies confirm that the novel LL-100 panel will be useful for understanding the function of oncogenes and tumour suppressor genes and to develop targeted therapies.

                </p>
                <div class="text-right">
                    <a href="https://doi.org/10.1038/s41598-019-44491-x" class="btn" target="_blank">
                        <i class="fas fa-external-link"></i>
                        Read more in <em>Scientific reports</em>
                    </a>
                </div>

            </div>

        </div>
        <div class="col-lg">
            <div class="card p-0">
                <!-- <h6 class="px-10 font-weight-bold">All tumour groups from this project</h6> -->

                <?php include 'll100-abbreviations.html'; ?>

            </div>
        </div>
    </div>
</div>