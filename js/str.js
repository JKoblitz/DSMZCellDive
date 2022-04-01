var alleles = 2;
const default_header = 'D5S818	D5S818_2	D13S317	D13S317_2	D7S820	D7S820_2	D16S539	D16S539_2	vWA	vWA_2	TH01	TH01_2	TPOX	TPOX_2	CSF1PO	CSF1PO_2	Amelogenin	Amelogenin_2	D3S1358	D3S1358_2	D21S11	D21S11_2	D18S51	D18S51_2	PentaE	PentaE_2	PentaD	PentaD_2	D8S1179	D8S1179_2	FGA	FGA_2	D19S433	D19S433_2	D2S1338	D2S1338_2';

if (typeof STR_ID !== "undefined" && STR_ID != 0 && STR_ID != "0") {
    getSTR(STR_ID);
}


function addAllele() {

    if (alleles >= 6) {
        halfmoon.initStickyAlert({
            content: 'A maximum of 6 alleles is supported.',
            alertType: "alert-danger",
        })
        return
    }
    var head = $('<th>').html('Allele ' + (++alleles))

    head.insertBefore($('#addAllele'))
    var table = $('#STR-input')
    table.find('tbody tr').each(function (i, tr) {
        var name = $(tr).attr('id') + '_' + alleles; //find('label').attr('for')
        $(tr).find('td.ref')
            .clone()
            .removeClass('ref')
            .appendTo($(tr))
            .find('input,select')
            .attr('name', name)
            .val('');
    })
}

function removeAllele() {

    if (alleles <= 2) {
        halfmoon.initStickyAlert({
            content: 'A minimum of 2 alleles is required.',
            alertType: "alert-danger",
        })
        return
    }
    alleles--;
    $('#addAllele').prev().remove()
    $("#STR-input tbody tr td:last-child").remove()
}

function exampleText() {
    $('#str-header').html(default_header);
    $('#str-values').html('12	12	8	11	11	12	11	11	16	16	7	8	8	11	13	14	X	X	13	16	29	30	14	15	13	14	10	11	12	12	22	24	14	14	17	17');
}


// function update


function getSTR(str_id) {
    cleanForm();
    // $('#loader').show()
    $.ajax({
        type: "POST",
        data: {
            str_id: str_id
        },
        dataType: "json",
        url: ROOTPATH + "/php/get_str.php",
        success: function (res) {
            // change content:
            console.log(res);
            res.count = parseInt(res.count)
            if (alleles > res.count) {
                for (let i = 0; i = alleles - res.count; i++) {
                    removeAllele()
                }
            } else if (res.count > alleles) {
                for (let i = 0; i = res.count - alleles; i++) {
                    addAllele()
                }
            }
            alleles = res.count;

            for (const locus in res.data) {
                if (Object.hasOwnProperty.call(res.data, locus)) {
                    const row = res.data[locus];
                    // const tr = $('#'+locus)
                    // console.log(tr);
                    // if (locus == 'Amelogenin') {

                    // } else {
                    // var value = el.value;
                    row.forEach((el) => {
                        console.log(locus, el.allele, parseFloat(el.value));
                        $('[name="' + locus + '_' + el.allele + '"]').val(parseFloat(el.value))
                    });
                    // }
                }
            }

            // $('#loader').hide()
        },
        error: function (response) {
            console.log(response.responseText)
            // $('#loader').hide()
        }
    })
}

function cleanForm() {
    $('#STR-input').find('input,select').val('')
}

function divideStrings(input){
    
}

function validateText() {
    cleanForm();
    var header = $('#str-header').val()
    if (!header) {
        header = default_header;
    }
    var values = $('#str-values').val()
    if (!values) {
        // console.log("ERROR, values cannot be empty!");
        halfmoon.initStickyAlert({
            title: 'Error',
            content: 'Values cannot be empty!',
            alertType: "alert-danger",
        })
        return;
    }
    header = header.trim().split(/\s{1,4}/)
    values = values.trim().split(/\s{1,4}/)
    console.log(header, values);

    if (header.length !== values.length) {
        // console.log("ERROR, header and values must have the same length!");
        halfmoon.initStickyAlert({
            title: 'Error',
            content: 'Header and values must have the same length',
            alertType: "alert-danger",
        })
        return;
    }
    header.forEach((head, index) => {
        var head = head.split('_')
        var locus = head[0];
        var allele = head[1] ?? 1;
        var value = values[index];
        if (value == "X") {
            value = '1.0';
        } else if (value == "Y") {
            value = '2.0';
        }
        $('[name="' + locus + '_' + allele + '"]').val(parseFloat(value))
    });
    $('#text-input').removeClass('show')
    halfmoon.initStickyAlert({
        title: 'Success',
        content: 'The form was updated using the text input data.',
        alertType: "alert-secondary",
    })


}

// $(function(){
//     $("table#str-result").delegate('td','mouseover mouseleave', function(e) {
//         if (e.type == 'mouseover') {
//         //   $(this).parent().addClass("hover");
//           $("col.col-").eq($(this).index()).addClass("hover");
//         }
//         else {
//         //   $(this).parent().removeClass("hover");
//           $("col").eq($(this).index()).removeClass("hover");
//         }
//     });
// })

// function exampleSTR(){
//     var example = {
//         "D5_1": 10, "D5_2": 12, "D13_1": 9, "D13_2": 10, "D7_1": 11, "D7_2": 12, "D16_1": 11, "D16_2": 11, "vWA_1": 16, "vWA_2": 16, "Amel_2": "X", "CSF1_2": 10, "CSF1_1": 10
//     }
//     for (const key in example) {
//         if (Object.hasOwnProperty.call(example, key)) {
//             const value = example[key];

//         }
//     }
// }


function downloadExcel() {
    var data = []
    var table = $('#str-result')
    table.find('tr').each(function (i, tr) {
        var row = []

        $(tr).find('th,td').each(function (i, td) {
            var td = $(td)
            row.push(td.text().trim().replace(/\s\s+/g, ' '));
            // consider colspan:
            var cs = td.attr('colspan')
            if (cs) {
                for (let i = 0; i < parseInt(cs) - 1; i++) {
                    row.push('')
                }
            }
        })
        data.push(row)
    })

    console.log(data);
    csvContent = "data:text/csv;charset=utf-8,";
    /* add the column delimiter as comma(,) and each row splitted by new line character (\n) */
    data.forEach(function (rowArray) {
        row = rowArray.join(";");
        csvContent += row + "\r\n";
    });

    /* create a hidden <a> DOM node and set its download attribute */
    var encodedUri = encodeURI(csvContent);
    var link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "STR_result.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}