<script>
    const STR_ID = '<?= $str_id ?>'
</script>

<style>
    table#STR-input td input {
        max-width: 26rem;
    }
</style>

<div class="content">
    <h1>STR Profile Editor</h1>
    <p class="text-danger">
        This page is only for internal use!
    </p>

</div>



<div class="card" id="meta-form">
    <h2 class="card-title">Metadata</h2>
    <form method="post" action="<?= ROOTPATH ?>/str/edit-meta/<?= $str_id ?>" class="">
        <table class="table table-sm" id="META-input">
            <thead>
                <tr>
                    <th>Datafield</th>
                    <th>Current value</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($meta as $row) { ?>
                    <?php foreach ($row as $key => $value) { ?>
                        <tr>
                            <td class="w-200">
                              <?= $key ?>
                            </td>
                            <td class="w-200">
                                <?php
                                switch ($key) {
                                    case 'reference':
                                    case 'highlight':
                                        echo ($value == 0) ? 'no': 'yes';
                                        break;
                                    case 'created':
                                    case 'date':
                                    case 'date_2':
                                    case 'date_animal_pcr':
                                        $date = new DateTime($value);
                                        echo $date->format('d.m.Y');
                                        break;
                                    
                                    default:
                                        echo $value;
                                        break;
                                }
                                ?>  
                                </td>
                            <td>
                                <?php
                                switch ($key) {
                                    case 'reference':
                                    case 'highlight':
                                        echo "<div class='custom-switch'>
                                        <input autocomplete='off' type='checkbox' id='switch-$key' value='1' name='$key' " . ($value == 1 ? 'checked' : '') . ">
                                        <label for='switch-$key' class='blank'></label>
                                      </div>";
                                        break;

                                    case 'str_id':
                                    case 'created':
                                        echo "cannot be changed";
                                        break;
                                    case 'cell_id':
                                        echo "is assigned automatically based on ACC (only if reference!)";
                                        break;
                                    case 'notes':
                                        echo "<textarea name='$key' id='$key' cols='30' rows='10' class='form-control form-control-sm'>$value</textarea>";
                                        break;
                                    case 'gender':
                                    case 'M':
                                    case 'R':
                                    case 'CH':
                                    case 'SH':
                                    case 'EBV':
                                    case 'largeT':
                                    case 'MMR':
                                        $enum = [];
                                        echo "<select name='$key' id='$key' class='form-control form-control-sm'>";
                                        if ($key == 'gender') {
                                            $enum = ['', 'male', 'female', 'unknown'];
                                        } elseif ($key == "MMR") {
                                            $enum = ['', 'MSS', 'MSI', 'MSI-L', 'MSI-H'];
                                        } else {
                                            $enum = ['', '-', '+', '(+)'];
                                        }
                                        foreach ($enum as $option) {
                                            echo "<option value='$option' " . ($value == $option ? 'selected' : '') . ">$option</option>";
                                        }
                                        echo "</select>";
                                        break;
                                    case 'date':
                                    case 'date_2':
                                    case 'date_animal_pcr':
                                        echo " <input autocomplete='off' type='date' name='$key' id='$key' value='$value' class='form-control form-control-sm'>";
                                        break;
                                    case 'ACC':
                                    case 'cellline':
                                        echo " <input autocomplete='off' type='text' name='$key' id='$key' value='$value' class='form-control form-control-sm' required>";
                                        break;
                                    default:
                                        echo " <input autocomplete='off' type='text' name='$key' id='$key' value='$value' class='form-control form-control-sm'>";
                                        break;
                                }
                                ?>

                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>

        <div class="mt-20">
            <button class="btn btn-primary" type="submit"><i class="fas fa-check"></i> Submit changes</button>
        </div>
    </form>


</div>

<div class="card" id="profile-form">
    <h2 class="card-title">Profile</h2>
    <form method="post" action="<?= ROOTPATH ?>/str/edit-profile/<?= $str_id ?>" class="">

        <table class="table table-sm w-auto" id="STR-input">
            <thead>
                <tr>
                    <th>Locus</th>
                    <th>Allele 1</th>
                    <th>Allele 2</th>
                    <th id="addAllele"><button type="button" class="btn btn-sm" onclick="addAllele()" data-toggle="tooltip" data-title="Add more alleles to the search">+</button></th>
                </tr>
            </thead>
            <tbody>
                <tr id="D5S818">
                    <td><label class="d-inline" for="D5S818">D5S818</label></td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D5S818_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D5S818_2" value=""></td>
                </tr>
                <tr id="D13S317">
                    <td><label class="d-inline" for="D13S317">D13S317</label></td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D13S317_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D13S317_2" value=""></td>
                </tr>
                <tr id="D7S820">
                    <td><label class="d-inline" for="D7S820">D7S820</label></td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D7S820_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D7S820_2" value=""></td>
                </tr>
                <tr id="D16S539">
                    <td><label class="d-inline" for="D16S539">D16S539</label></td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D16S539_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D16S539_2" value=""></td>
                </tr>
                <tr id="vWA">
                    <td><label class="d-inline" for="vWA">vWA</label></td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="vWA_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="vWA_2" value=""></td>
                </tr>
                <tr id="TH01">
                    <td><label class="d-inline" for="TH01">TH01</label></td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="TH01_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="TH01_2" value=""></td>
                </tr>
                <tr id="TPOX">
                    <td><label class="d-inline" for="TPOX">TPOX</label></td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="TPOX_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="TPOX_2" value=""></td>
                </tr>
                <tr id="CSF1PO">
                    <td><label class="d-inline" for="CSF1PO">CSF1PO</label></td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="CSF1PO_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="CSF1PO_2" value=""></td>
                </tr>
                <tr id="Amelogenin">
                    <td><label class="d-inline" for="Amelogenin">Amelogenin</label></td>
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
                    <td><label class="d-inline" for="D3S1358">D3S1358</label></td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D3S1358_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D3S1358_2" value=""></td>
                </tr>
                <tr id="D21S11">
                    <td><label class="d-inline" for="D21S11">D21S11</label></td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D21S11_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D21S11_2" value=""></td>
                </tr>
                <tr id="D18S51">
                    <td><label class="d-inline" for="D18S51">D18S51</label></td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D18S51_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D18S51_2" value=""></td>
                </tr>
                <tr id="PentaE">
                    <td><label class="d-inline" for="PentaE">PentaE</label></td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="PentaE_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="PentaE_2" value=""></td>
                </tr>
                <tr id="PentaD">
                    <td><label class="d-inline" for="PentaD">PentaD</label></td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="PentaD_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="PentaD_2" value=""></td>
                </tr>
                <tr id="D8S1179">
                    <td><label class="d-inline" for="D8S1179">D8S1179</label></td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D8S1179_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D8S1179_2" value=""></td>
                </tr>
                <tr id="FGA">
                    <td><label class="d-inline" for="FGA">FGA</label></td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="FGA_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="FGA_2" value=""></td>
                </tr>
                <tr id="D19S433">
                    <td><label class="d-inline" for="D19S433">D19S433</label></td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D19S433_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D19S433_2" value=""></td>
                </tr>
                <tr id="D2S1338">
                    <td><label class="d-inline" for="D2S1338">D2S1338</label></td>
                    <td class="ref"><input class="form-control form-control-sm " type="number" step="0.1" name="D2S1338_1" value=""></td>
                    <td><input class="form-control form-control-sm " type="number" step="0.1" name="D2S1338_2" value=""></td>
                </tr>
            </tbody>
        </table>

        <div class="mt-20">
            <button class="btn btn-primary" type="submit"><i class="fas fa-check"></i> Submit changes</button>
        </div>
    </form>

</div>


<div class="card alert alert-danger">
    <h4 class="alert-header">Delete this dataset</h4>
    <form action="<?= ROOTPATH ?>/str/delete/<?= $str_id ?>" method="post">
        <button class="btn btn-danger" type="submit"><i class="fas fa-trash-alt"></i> Delete</button>
    </form>
</div>