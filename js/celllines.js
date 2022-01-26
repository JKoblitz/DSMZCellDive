
var colors = {
    "1": "#7570b3",
    "2": "#1b9e77",
    "3": "#d95f02",
    "4": "#e7298a",
    "5": "#66a61e",
    "6": "#e6ab02",
    "7": "#a6761d",
    "8": "#666666",
    "9": "#1b9e77",
    "10": "#d95f02",
    "11": "#7570b3",
    "12": "#e7298a",
    "13": "#66a61e",
    "14": "#e6ab02",
    "15": "#a6761d",
    "16": "#666666",
    "17": "#1b9e77",
    "18": "#d95f02",
    "19": "#7570b3",
    "20": "#e7298a",
    "21": "#66a61e",
    "22": "#e6ab02",
    "X": "#a6761d",
    "Y": "#666666",
    "MT": "#555555"
}
// function colormap(val) {
//     return colors[val]
// }

function chartRNA() {
    $('#loader').show()
    $.ajax({
        type: "POST",
        data: {
            cell_id: CELL,
            type: 'hist'
        },
        dataType: "json",
        url: ROOTPATH + "/php/cellline_rna.php",
        success: function (response) {
            // change content:
            console.log(response);
            var trace = {
                x: response,
                type: 'histogram'
            };

            var data = [trace];

            var layout = {
                title: 'Histogram of normalized data',
                xaxis: {
                    title: "Normalized gene expression"
                },
            }
            Plotly.newPlot('project-rna-hist', data, layout);
            $('#loader').hide()
        },
        error: function (response) {
            console.log(response.responseText)
            $('#loader').hide()
        }
    })

}


function barchartRNA() {
    $('#loader').show()
    $.ajax({
        type: "POST",
        data: {
            cell_id: CELL,
            type: 'bar'
        },
        dataType: "json",
        url: ROOTPATH + "/php/cellline_rna.php",
        success: function (data) {
            // change content:
            console.log(data)
            // response.type = 'bar'
            // response.marker = {
            //     color: response.x[0].map(x => colors[x])
            // }
            

            // var data = [response];

            var layout = {
                title: 'Normalized expression of all genes',
                legend: {
                    title: {
                        text: 'Chromosome'
                    }
                },
                xaxis: {
                    // tickmode: 'array',
                    title: "Genes",
                    // dtick: 1,
                    // nticks: 25
                    // type: 'multicategory',
                    // automargin: true
                },
            }
            Plotly.newPlot('project-rna-bar', data, layout)
            $('#loader').hide()
        },
        error: function (response) {
            console.log(response.responseText)
            $('#loader').hide()
        }
    })

}