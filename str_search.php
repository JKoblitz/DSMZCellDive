<script>
    const STR_ID = '<?= (isset($_GET['str_id']) && is_numeric($_GET['str_id'])) ? $_GET['str_id'] : 0 ?>'
</script>

<style>
    table.table td input {
        max-width: 26rem;
    }
</style>

<div class="content">
    <a href="<?= ROOTPATH ?>/documentation#str" class="btn btn-help float-right"><i class="far fa-lg fa-book mr-5"></i> <span class="d-none d-md-inline">Help</span></a>

    <h1>STR Profile Search</h1>
    <p class="text-muted">
        The human STR profile database includes data sets of more than 3,600 distinct cell lines from ATCC, DSMZ, JCRB and RIKEN
    </p>
</div>

<div class="mx-20">
    <button class="btn btn-primary" type="button" onclick="halfmoon.toggleModal('text-input')">Use text input</button>
</div>

<div class="content" id="form-input">
    <form method="post" action="<?= ROOTPATH ?>/str/search" class="">

        <table class="table table-sm w-auto" id="STR-input">
            <thead>
                <tr>
                    <th><i class="fas fa-external-link invisible"></i> STR</th>
                    <th>Allele 1</th>
                    <th>Allele 2</th>
                    <th id="addAllele">
                        <button type="button" class="btn btn-sm" onclick="addAllele()" data-toggle="tooltip" data-title="Add more alleles to the search">
                            <i class="fas fa-plus"></i>
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr id="D5S818">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/str_D5S818.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="D5S818">D5S818</label>
                    </td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D5S818_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D5S818_2" value=""></td>
                </tr>
                <tr id="D13S317">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/str_D13S317.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="D13S317">D13S317</label>
                    </td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D13S317_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D13S317_2" value=""></td>
                </tr>
                <tr id="D7S820">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/str_D7S820.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="D7S820">D7S820</label>
                    </td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D7S820_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D7S820_2" value=""></td>
                </tr>
                <tr id="D16S539">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/str_D16S539.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="D16S539">D16S539</label>
                    </td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D16S539_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D16S539_2" value=""></td>
                </tr>
                <tr id="vWA">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/str_vWA.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="vWA">vWA</label>
                    </td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="vWA_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="vWA_2" value=""></td>
                </tr>
                <tr id="TH01">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/str_TH01.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="TH01">TH01</label>
                    </td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="TH01_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="TH01_2" value=""></td>
                </tr>
                <tr id="TPOX">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/str_TPOX.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="TPOX">TPOX</label>
                    </td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="TPOX_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="TPOX_2" value=""></td>
                </tr>
                <tr id="CSF1PO">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/str_CSF1PO.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="CSF1PO">CSF1PO</label>
                    </td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="CSF1PO_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="CSF1PO_2" value=""></td>
                </tr>
                <tr id="Amelogenin">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/Amelogenin.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="Amelogenin">Amelogenin</label>
                    </td>
                    <td class="ref">
                        <select class="form-control form-control-sm " name="Amelogenin_1">
                            <option value=""></option>
                            <option value="1">X</option>
                            <option value="2">Y</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control form-control-sm " name="Amelogenin_2">
                            <option value=""></option>
                            <option value="1">X</option>
                            <option value="2">Y</option>
                        </select>
                    </td>
                </tr>
                <tr id="D3S1358">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/str_D3S1358.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="D3S1358">D3S1358</label>
                    </td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D3S1358_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D3S1358_2" value=""></td>
                </tr>
                <tr id="D21S11">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/str_D21S11.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="D21S11">D21S11</label>
                    </td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D21S11_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D21S11_2" value=""></td>
                </tr>
                <tr id="D18S51">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/str_D18S51.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="D18S51">D18S51</label>
                    </td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D18S51_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D18S51_2" value=""></td>
                </tr>
                <tr id="PentaE">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/str_Penta_E.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="PentaE">PentaE</label>
                    </td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="PentaE_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="PentaE_2" value=""></td>
                </tr>
                <tr id="PentaD">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/str_Penta_D.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="PentaD">PentaD</label>
                    </td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="PentaD_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="PentaD_2" value=""></td>
                </tr>
                <tr id="D8S1179">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/str_D8S1179.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="D8S1179">D8S1179</label>
                    </td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D8S1179_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D8S1179_2" value=""></td>
                </tr>
                <tr id="FGA">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/str_FGA.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="FGA">FGA</label>
                    </td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="FGA_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="FGA_2" value=""></td>
                </tr>
                <tr id="D19S433">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/str_D19S433.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="D19S433">D19S433</label>
                    </td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D19S433_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D19S433_2" value=""></td>
                </tr>
                <tr id="D2S1338">
                    <td>
                        <a class="visibility-hover" href="https://strbase.nist.gov/str_D2S1338.htm" target="_blank" title="Read more at STRBase"><i class="fas fa-external-link"></i></a>
                        <label class="d-inline" for="D2S1338">D2S1338</label>
                    </td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D2S1338_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D2S1338_2" value=""></td>
                </tr>
            </tbody>
        </table>

        <div class="mt-20">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Search</button>
            <button class="btn" type="button" onclick="getSTR(211);"><i class="fas fa-question"></i> Example</button>
            <button class="btn" type="reset"><i class="fas fa-trash-alt"></i> Reset</button>
        </div>
    </form>

</div>



<div class="card">
    If the results obtained by the search engine are used in any publication, please cite the respective paper:
    <a href="https://onlinelibrary.wiley.com/doi/full/10.1002/ijc.24999">Dirks et al. <em> Int J Cancer</em> (2010)</a>
    <!-- Dirks WG, MacLeod RA, Nakamura Y, Kohara A, Reid Y, Milch H, Drexler HG, Mizusawa H.: 
    Cell line cross-contamination initiative: an interactive reference database of STR profiles covering common cancer cell lines. 
    Int J Cancer. 2010 Jan 1;126(1):303-4. (link: <a href="https://onlinelibrary.wiley.com/doi/full/10.1002/ijc.24999">https://onlinelibrary.wiley.com/doi/full/10.1002/ijc.24999</a>) -->
</div>