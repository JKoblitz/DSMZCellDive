<link rel="stylesheet" href="css/documentation.css">
<div class="row">

    <div class="col-xl-9">

        <!-- Top section start  -->
        <div id="documentation" class="content">
           
        <h1 class=""><i class="fad text-secondary fa-book mr-10"></i> Documentation</h1>


        <section id="toc" class="d-block d-xl-none">
            <h4>
                On this page
            </h4>

            <ol class="doc-nav" id="doc-nav">
                <a href="#cell-lines">Cell lines</a>
                <a href="#genes">List of genes</a>
                <a href="#rna-seq">RNA-seq data</a>
                <a href="#str">Short tandem repeats for human cell lines</a>
                <a href="#coi">COI DNA barcoding for animal cell lines</a>
                <a href="#hla">HLA typing data</a>
                <a href="#cite">How to cite</a>
            </ol>

        </section>
        <hr>


        <section id="cell-lines">
            <h2>
                Cell lines
                <a href="#cell-lines" class="ml-5 text-decoration-none text-secondary"><i class="fas fa-hashtag"></i></a>
            </h2>

            <p>
                The cell line content page provides a summary over all available cell lines at DSMZCellDive.
                An overview shows the cell line, cell type, and species. Furthermore, the DSMZ ACC No. is given
                including a link to the DSMZ catalogue, where more information is available and the cell line can be purchased.
                The last column indicates if the cell line is featured in any RNA-seq data panel.
            </p>
            <a href="<?= ROOTPATH ?>/celllines" class="btn btn-primary"><i class="fas fa-arrow-right mr-10"></i> Browse all cell lines</a>


            <h4>
                <i class="fad fa-fw fa-filters text-secondary"></i>
                Search and filter
            </h4>
            <p>
                Cell lines can be searched by their name or DSMZ ACC No.
                When clicking on <span class="btn"><i class="fas fa-filter"></i> More filters</span>, a popover with more filter options will appear.
                Here, cell lines can be filtered by species and cell types. Both fields feature auto suggestions when you start typing.
                Furthermore, only cell lines that belong to a certain RNA-seq panel can be selected here.
            </p>

            <h4 id="cell-line">
                <i class="fad fa-fw fa-file-chart-column text-secondary"></i>
                Integrated cell line data
            </h4>

            <p>
                When a single cell line is selected by clicking on its name, a cell line summary page is opened.
                On this page, all information on this particular cell line is shown, e.g. certain meta data,
                the STR profile or the COI DNA barcoding report, and RNA-seq projects.
            </p>
            <h4 id="cell-line-rna-seq">
                <i class="fad fa-fw fa-dna text-secondary"></i>
                RNA-seq summary
            </h4>
            <p>
                If the cell line is featured in one of the RNA-seq panels, charts on these data can be triggered by clicking on one of the buttons in the panel.
                After a short loading time, either a histogram or a bar chart with all normalised expression levels are shown.
            </p>
            <figure>
                <img src="<?= ROOTPATH ?>/img/docs/celllines_barchart.png" alt="Cell line expression as bar chart" class="w-full">

                <figcaption class="text-muted">The expression of all genes in the selected cell line as bar charts. Colors indicate the chromosome where the gene is located.</figcaption>
            </figure>

        </section>

        <hr>

        <section id="genes">
            <h2>
                List of genes
                <a href="#genes" class="ml-5 text-decoration-none text-secondary"><i class="fas fa-hashtag"></i></a>
            </h2>

            <p>
                The genes content page lists all human genes and some meta data, e.g. the position in the human genome,
                database identifiers from Ensembl and Entrez and a short gene description.
                Gene names correspond to <a href="https://www.gencodegenes.org/" target="_blank" rel="noopener noreferrer">GENCODE</a> names (v38).
                The list of genes can be filtered by gene name, description, Ensembl-ID, or Entrez-ID.
                The description search is always a contains-search.
                For gene names, one can use an asterisk as wildcard character (<a href="<?= ROOTPATH ?>/genes?search=TSPAN*">Example</a>).
            </p>

            <a href="<?= ROOTPATH ?>/genes" class="btn btn-primary"><i class="fas fa-arrow-right mr-10"></i> Browse all genes</a>
        </section>

        <hr>

        <section id="rna-seq">
            <h2>
                RNA-seq data
                <a href="#rna-seq" class="ml-5 text-decoration-none text-secondary"><i class="fas fa-hashtag"></i></a>
            </h2>

            <p>
                Each RNA-seq panel has its own overview page that can be reached via the sidebar or the starting page.
                On this page, further information on this project are given, including literature to cite when using the data.
            </p>


            <h4 id="matrix-description"><i class="fad fa-fw fa-ballot-check text-secondary"></i> Matrix description</h4>
            <p>
                Different to the description of the LL-100 paper (<a href="#cite">see below</a>), RNA-seq data were quantified via <a href="https://salmon.readthedocs.io/en/latest/index.html" target="_blank" rel="noopener noreferrer">Salmon</a> and normalised via <a href="https://www.bioconductor.org/packages/3.6/bioc/vignettes/DESeq2/inst/doc/DESeq2.html" target="_blank" rel="noopener noreferrer">DESeq2</a>.
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

            <div class="row mb-20">
                <div class="col-lg-6 align-vertical-center">
                    <div>
                        <h4 id="bar-charts"><i class="fad fa-fw fa-ballot-check text-secondary"></i> Gene selection</h4>
                        <p>
                            Genes can be selected via an intuitive selection tool.
                            Start typing to get genes suggested based on their names and descriptions.
                            Clicking on the <strong class="text-primary">&times;</strong> will remove the selected gene.
                            You can clear the whole selection by clicking on <span class="text-primary"><i class="fas fa-clear"></i> clear</span> below.
                            For <i class="fas fa-chart-column"></i> Bar charts, you can select up to 5 genes.
                            For <i class="fas fa-table-cells-large"></i> Heat maps, between 2 and 50 genes can be selected.
                        </p>
                        <p>
                            You can also paste genes into this field (only gene names supported), if the genes are seperated by spaces, linebreaks, comma, or semicolon.
                            The gene selection is saved as a cookie for your convenience.
                        </p>
                        <a href="<?= ROOTPATH ?>/rna/ll-100/bar?gene=GAPDH&multigene=gene&matrix=tpm&selectby=groups&samples=ALCL,AML_mega,AML_mono,BL_B-ALL" class="btn btn-primary"><i class="fas fa-arrow-right mr-10"></i> Example bar chart</a>
     
                    </div>
                </div>
                <div class="col-lg-6 align-vertical-center">
                    <img src="<?= ROOTPATH ?>/img/docs/gene-selection.gif" alt="Gene selection via suggestion" class="img-fluid mx-auto d-block">
                </div>
            </div>
            <div class="row mb-20">
                <div class="col-lg-6 align-vertical-center">
                    <div>
                        <h4 id="bar-charts"><i class="fad fa-fw fa-chart-column text-secondary"></i> Bar charts</h4>
                        <p>
                            Bar charts are generated using the <a href="https://plotly.com/javascript/" target="_blank" rel="noopener noreferrer">Plotly.js</a> library.
                            They are interactive, one can zoom in by selecting parts of the graph, pan by tagging the
                            axes and hide/show single data sets by clicking on the legend. You can save the chart as PNG by clicking on the <i class="fas fa-camera"></i> that appears after hovering the plot.
                        </p>
                        <p>
                            Bar charts can display multiple genes (up to 5). Grouping will be done by gene or cell line, depending on user selection.
                        </p> <a href="<?= ROOTPATH ?>/rna/ll-100/heat?gene=FGR;CFH;FUCA2;GCLC;NFYA;STPG1;NIPAL3&multigene=gene&matrix=norm&selectby=groups&samples=AML_myelo,CML_myelo_BC" class="btn btn-primary"><i class="fas fa-arrow-right mr-10"></i> Example heat map</a>
     
                    </div>
                </div>
                <div class="col-lg-6 align-vertical-center">
                    <img src="<?= ROOTPATH ?>/img/docs/barplot-navigation.gif" alt="Gene selection via suggestion" class="img-fluid">
                </div>
            </div>


            <div class="row mb-20">
                <div class="col-lg-6 align-vertical-center">
                    <div>
                        <h4 id="heat-maps"><i class="fad fa-fw fa-table-cells text-secondary"></i> Heat maps</h4>
                        <p>
                            Heat maps are generated using the <a href="https://plotly.com/javascript/" target="_blank" rel="noopener noreferrer">Plotly.js</a> library and the R package heatmaply.
                            They can be zoomed and downloaded the same way as <a href="#bar-charts">the bar charts</a>.


                        </p>
                        <p>
                            Heat maps can be clustered by genes (default) or cell lines. Clustering can also be disabled (none).
                            The order of the genes is identical to the selection unless they are clustered. On top of the heat map is a color
                            legend indicating the tumour group. Hover over the labels to get more information.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6 align-vertical-center">
                    <img src="<?= ROOTPATH ?>/img/docs/heatmap-navigation.gif" alt="Gene selection via suggestion" class="img-fluid">
                </div>
            </div>

            <!-- <a href="<?= ROOTPATH ?>/genes" class="btn btn-primary"><i class="fas fa-arrow-right mr-10"></i> Go to cell lines</a> -->
        </section>

        <hr>

        <section id="str">
            <h2>
                Short tandem repeats for human cell lines
                <a href="#str" class="ml-5 text-decoration-none text-secondary"><i class="fas fa-hashtag"></i></a>
            </h2>

            <p>
                Short Tandem Repeats (STRs) are crucial for the authentication of human cell lines.
                Thus, we implemented a search engine comprising 17 STR loci and more than 4,500 data sets from cell lines of different sources.
            </p>

            <a href="<?= ROOTPATH ?>/str/search" class="btn btn-primary"><i class="fas fa-arrow-right mr-10"></i> STR search engine</a>

            <h4 id="str-search">
            <i class="fad fa-fw fa-search-plus text-secondary"></i> 
                The STR Search
            </h4>

            <p>
                The search tool offers a form, where one can enter each STR value manually. If more alleles are needed, they can be added using the <span class="badge">&plus;</span> button.
                Up to six alleles are possible. Click on <span class="badge"><i class="fas fa-question"></i> Example</span> to see an example.
                It is also possible to paste data from e.g. Excel files. To do this, please click on <span class="badge badge-primary">Use text input</span>
                and follow the instructions in the popup.
            </p>

            <p>
                The result page contains a table with your query and the best matching STR profiles. The first column shows the similarity. Read more about this score <a href="#str-score">here</a>.
                The next two columns give information on the cell line and its source. The following columns contain the STR profiles. Bold red fonts indicate mismatches from your query.
                The result can be downloaded as CSV file that can be opened with MS Excel.
            </p>

            <h4 id="str-score">
            <i class="fad fa-fw fa-abacus text-secondary"></i> 
                The similarity score
            </h4>

            <p>
                The similarity score is calculated as stated <a href="https://doi.org/10.11418/jtca1981.18.4_329" target="_blank" rel="noopener noreferrer">by Tanabe et al</a>:
            </p>

            <img src="<?=ROOTPATH?>/img/docs/eq_tanabe.png" alt="2 times shared alleles divided by the sum of alleles from the query and reference" class="img-fluid">
        </section>

        <hr>

        <section id="coi">
            <h2>
                COI DNA barcoding for animal cell lines
                <a href="#coi" class="ml-5 text-decoration-none text-secondary"><i class="fas fa-hashtag"></i></a>
            </h2>

            <p>
                While STR data are only used for human cell lines, the mitochondrial cytochrome c oxidase I (COI) gene is widely used to identify species of animal cell lines.
                DSMZCellDive offers COI DNA barcoding reports for all animal cell lines in the database.
                You can browse through them, view them in more detail and download each report as PDF.
            </p>
            <p>
                If you are looking for an identification engine, we recommend to use <a href="http://boldsystems.org/index.php/IDS_OpenIdEngine" target="_blank" rel="noopener noreferrer">BOLDSYSTEMS</a>.
            </p>

            <a href="<?= ROOTPATH ?>/coi/browse" class="btn btn-primary"><i class="fas fa-arrow-right mr-10"></i> Browse COI DNA reports</a>
        </section>

        <hr>
        
        <section id="hla">
            <h2>
                HLA typing data
                <a href="#hla" class="ml-5 text-decoration-none text-secondary"><i class="fas fa-hashtag"></i></a>
            </h2>
            <p>Human leukocyte antigen (HLA) genes encode proteins in the major histocompatibility complex (MHC) which play a central role in discriminating self and non-self. MHC Class I proteins (HLA-A, -B and -C) bind to and present intracellular antigens on the cell surface for cytotoxic T-cells, which trigger apoptosis if non-selfpeptides are detected. MHC Class II proteins (including HLA-DPB1, -DQB1 and -DRB1), on the other hand, present extracellular proteins to helper T-cells which mediate the adaptive immune response.</p>
            <p>The HLA gene cluster on chromosome 6 is highly polymorphic and suitable for cell line authentication. Furthermore, HLA typing is important for cancer research and for determination of tissue compatibility, in which tumour neoantigen binding to HLA surface proteins and rejection of specific HLA alleles play a role.</p>
            <p>
                Here, HLA typing was determined on RNA-seq data via arcasHLA, an alignment-based tool (<a href="https://academic.oup.com/bioinformatics/article/36/1/33/5512361" target="_blank" rel="noopener noreferrer">Orenbuch et al, 2020</a>).
            </p>
            <p>
                <i class="far fa-exclamation-triangle text-danger"></i>
                Protein expression was not determined.
            </p>


            <a href="<?= ROOTPATH ?>/hla" class="btn btn-primary"><i class="fas fa-arrow-right mr-10"></i> Browse HLA typing data</a>
        </section>

        <hr>
        
        <section id="cite">
            <h2>
                How to cite
                <a href="#cite" class="ml-5 text-decoration-none text-secondary"><i class="fas fa-hashtag"></i></a>
            </h2>
            
            <p>If you found DSMZCellDive to be useful for your research, we will be happy if you cite us. </p>

            <h4 id="cite-celldive">
                <i class="fad fa-fw fa-browser text-secondary"></i> 
                Cite DSMZCellDive
            </h4>
            <p>
                Koblitz J., Dirks, W.G., Eberth, S. et al. DSMZCellDive: Diving into high-throughput cell line data. <em>F1000Research</em> (2022), <a target="_blank" href="https://doi.org/">DOI: </a> 
            </p>

            <h4 id="cite-data">
                <i class="fad fa-fw fa-file-chart-line text-secondary"></i> 
                Cite the data publications
            </h4>
            <p>
                <b>LL-100 RNA-seq data</b><br>
                Quentmeier, H., Pommerenke, C., Dirks, W.G. et al. The LL-100 panel: 100 cell lines for blood cancer studies. <em>Sci Rep</em> 9, 8218 (2019). <a target="_blank" href="https://doi.org/10.1038/s41598-019-44491-x">DOI: 10.1038/s41598-019-44491-x</a>
            </p>
            
            <p>
                <b>STR profiling data</b><br>
                Dirks, W.G., MacLeod, R.A.F., Nakamura, Y. et al. Cell line cross-contamination initiative: An interactive reference database of STR profiles covering common cancer cell lines. <em>Int. J. Cancer</em>, 126: 303-304 (2010). <a target="_blank" href="https://doi.org/10.1002/ijc.24999">DOI: 10.1002/ijc.24999</a>
            </p>
        </section>

        </div>
        <!-- Up next section end -->

    </div>

    <!-- On this page navigation start  -->
    <div class="col-xl-3 on-this-page-nav-container d-none d-xl-block">
        <div class="content">
            <div class="on-this-page-nav" id="on-this-page-nav">
                <div class="title">On this page</div>
                <a href="#cell-lines">Cell lines</a>
                <a href="#genes">List of genes</a>
                <a href="#rna-seq">RNA-seq data</a>
                <a href="#str">STR profiles for human cell lines</a>
                <a href="#coi">COI barcoding for animal cell lines</a>
                <a href="#hla">HLA typing data</a>
                <a href="#cite">How to cite</a>
            </div>
        </div>
    </div>
    <!-- On this page navigation end  -->
    <div id="to-top" class="position-fixed bottom-0 right-0 z-50"><a href="#documentation" class="btn btn-primary m-20">Scroll to top</a></div>
</div>