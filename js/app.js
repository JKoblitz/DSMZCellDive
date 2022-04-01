
var share = ""

const tumours = $('.cell-groups :checkbox').map(function () {
    return this.value;
}).get();

var layout = {
    // title: 'US Export of Plastic Scrap',
    xaxis: {
        type: 'category',
        tickfont: {
            size: 14,
            color: 'rgb(107, 107, 107)'
        }
    },
    yaxis: {
        // title: 'normalized',
        rangemode: 'tozero',
        fixedrange: true,
        titlefont: {
            size: 16,
            color: 'rgb(107, 107, 107)'
        },
        tickfont: {
            size: 14,
            color: 'rgb(107, 107, 107)'
        }
    },
    // paper_bgcolor: 'transparent',
    // plot_bgcolor: 'transparent',
    legend: {
        // orientation: 'h',
        // xanchor: 'center',
        // x: 0,
        // y: 0
        title: {text: 'Cell lines'},
        // font: {size: 10},
        // x: 1
    },
    height: 620,
    margin: {
        t: 25,
        r: 10,
        l: 60,
        b:120
    },
    // modebar: {
    //     orientation: 'v'
    // }
};


var bardata = [
    {
        x: [],
        y: [],
        type: 'bar'
    }
];


var heatdata = [
    {
        z: [],
        x: [],
        y: [],
        type: 'heatmap',
        hoverongaps: false,
        colorscale: 'Viridis'
    }
];



var amsifyIdentifier = new AmsifySuggestags($('#gene'));
amsifyIdentifier._settings({
    selectOnHover: false,
    noSuggestionMsg: 'This gene was not found in our database.',
    tagLimit: TYPE == 'heat' ? 50 : 5,
    // whiteList: true,
    // defaultTagClass: 'badge badge-primary',
    printValues: false,
    suggestionsAction: {
        timeout: -1,
        minChars: 1,
        minChange: -1,
        type: 'GET',
        url: ROOTPATH + '/php/genes.php'
    }
})
amsifyIdentifier._init();

var graphDiv = document.getElementById('chart');


function clearSuggestion() {
    $('#gene').val('')
    amsifyIdentifier.clear()
    amsifyIdentifier.refresh()
    // var genes = $('#gene').val()
    // var genes = genes.split(';')
    // genes.forEach(gene => {
    //     amsifyIdentifier.removeTag(gene)
    // });

    $('input.amsify-suggestags-input').focus()
}
// TSPAN6;BCL2L13;FGFR2;GDE1
// var N = 40,
// x = d3.range(N),
//     y = d3.range(N).map(d3.random.normal()),
//     data = [{ x: x, y: y }];
//     layout = { title: 'Click-drag to zoom' };

// Plotly.newPlot(graphDiv, bardata, layout);

// graphDiv.on('plotly_relayout', function (eventdata) {
//     // Plotly.restyle(graphDiv, 'y', [[]]); 
//                 console.log('ZOOM!' + '\n\n' +
//         'Event data:' + '\n' +
//             JSON.stringify(eventdata) + '\n\n' +
//             'x-axis start:' + eventdata['xaxis.range[0]'] + '\n' +
//             'x-axis end:' + eventdata['xaxis.range[1]']);
//         });
window.addEventListener('resize', function () {
    Plotly.Plots.resize('chart');
});
// 34

//   Plotly.newPlot('myDiv', data);

// Plotly.newPlot(graphDiv, heatdata, layout);

function colors() {
    scheme = $('#group-color').val()
    if (TYPE == 'heat') {
        if (scheme == 'myOwn') {
            scheme = [
                ['0.0', 'rgb(49,54,149)'],
                ['0.111111111111', 'rgb(69,117,180)'],
                ['0.222222222222', 'rgb(116,173,209)'],
                ['0.333333333333', 'rgb(171,217,233)'],
                ['0.444444444444', 'rgb(224,243,248)'],
                ['0.555555555556', 'rgb(254,224,144)'],
                ['0.666666666667', 'rgb(253,174,97)'],
                ['0.777777777778', 'rgb(244,109,67)'],
                ['0.888888888889', 'rgb(215,48,39)'],
                ['1.0', 'rgb(165,0,38)']
            ]
        }
        return scheme
    }
    count = 24
    // var colorsCategorical = ["Category10", "Paired", "Tableau10", "Set3", "Accent"]
    var colorsRadial = ["Spectral", "Cool", "Viridis", "Plasma", "Inferno", "Rainbow"]
    if (colorsRadial.includes(scheme)) {
        var colorScale = d3.scaleSequential(d3["interpolate" + scheme])
            .domain([1, count])
    }
    else {
        var colorScale = d3.scaleOrdinal(d3["scheme" + scheme])
    }
    return colorScale
}

function updateColors() {
    var colorscheme = colors()
    if (TYPE == 'bar') {
        bardata.forEach(el => {
            el.marker.color = colorscheme(tumours.indexOf(el.name))
        });
    } else {

        heatdata[0].colorscale = colorscheme
    }
    Plotly.redraw(graphDiv);
}

function getData(genes, groups = null, celllines = null, matrix = 'norm', multigene = 'gene') {
    $('#loader').show()
    if (TYPE === 'heat') {
        $.ajax({
            type: "POST",
            data: {
                genes: genes,
                groups: groups,
                celllines: celllines,
                matrix: matrix,
                type: TYPE,
                multigene: multigene
            },
            dataType: "json",
            url: ROOTPATH + "/php/query_heat.php",
            success: function (response) {
                // change content:
                $('#chart').html('')
                console.log(response);
                var msg = response.msg;
                msg.forEach((d) => ui_message(d))
                if (response.id) {
                    console.log(response.id);
                   
                        var plot = Plotly.newPlot('chart', response.data.x);
                      
                    $('#loader').hide()

                } else {
                    $('#loader').hide()
                }

                // $('iframe').css('height', )
                // $('#chart').html(response)
                
            },
            error: function (response) {
                console.log(response.responseText)
                $('#loader').hide()
            }
        })
        return;
    }
    $.ajax({
        type: "POST",
        data: {
            genes: genes,
            groups: groups,
            celllines: celllines,
            matrix: matrix,
            type: TYPE,
            multigene: multigene
        },
        dataType: "json",
        url: ROOTPATH + "/php/query.php",
        success: function (response) {
            // change content:
            console.log(response);
            var msg = response.msg;
            msg.forEach((d) => ui_message(d))
            var data = response.data;
            var genes = response.genes;
            if (data.length === 0) {
                $('#loader').hide()
                return
            }
            layout.yaxis.title = matrix
            if (TYPE == 'bar') {
                if (genes.length === 1) {
                    layout.xaxis.type = 'category'
                    layout.xaxis.automargin = false;
                    layout.title = genes[0];
                } else {
                    layout.xaxis.type = 'multicategory'
                    layout.xaxis.automargin = true;
                }
                // var colorscheme = colors()
            var colorscheme = response.colors;
            console.log(colorscheme);
                bardata = []
                for (const group in data) {

                    const row = data[group];
                    if (genes.length === 1) {
                        var rowx = row.map(x => x['key']);
                    } else {
                        var rowx = [row.map(x => x['key'].split(':')[1]), row.map(x => x['key'].split(':')[0])];
                    }
                    bardata.push({
                        x: rowx,
                        y: row.map(x => x['value']),
                        name: group,
                        type: 'bar',
                        marker: {
                            color: colorscheme[group]
                        }
                    })

                }
                console.log(bardata);
                Plotly.newPlot(graphDiv, bardata, layout);

                updateTable(bardata, genes);
            } else {

                heatdata[0].x = []
                heatdata[0].y = []
                heatdata[0].z = []
                for (const gene in data) {
                    const values = data[gene];
                    heatdata[0].y.push(gene)
                    heatdata[0].z.push(values.map(x => x['value']))
                    heatdata[0].x = values.map(x => x['key'])
                }
                heatdata[0].colorscale = colors()
                Plotly.newPlot(graphDiv, heatdata, layout);
            }
            $('#loader').hide()
        },
        error: function (response) {
            console.log(response.responseText)
            $('#loader').hide()
        }
    })

}

function updateChart() {
    ui_message(null)
    $("#content").html('')
    var gene = $('#gene').val()
    if (gene == '') {
        ui_message('Please select a gene.')
        return;
    }
    var genes = gene.split(';')
    console.log(genes);
    var matrix = $('#matrix').val()
    var selectby = $('#select-by').val()
    var multigene = $("input[name='multigene']:checked").val()

    if (selectby == 'groups') {
        var selected = $('.cell-groups :checkbox:checked').map(function () {
            return this.value;
        }).get();
        if (selected.length == 0) {
            ui_message('Please select at least one cell line.')
            return;
        }
        getData(genes, selected, null, matrix, multigene)
    } else {
        var selected = $('.cell-celllines :checkbox:checked').map(function () {
            return this.value;
        }).get();
        if (selected.length == 0) {
            ui_message('Please select at least one cell line.')
            return;
        }
        getData(genes, null, selected, matrix, multigene)
    }
    var samples = selected.join(',')
    console.log(genes.join(';'));
    createCookie("dsmzcelldive_gene", genes.join(','), 3)
    createCookie("dsmzcelldive_matrix", matrix, 3)
    createCookie("dsmzcelldive_samples", selectby + ":" + samples, 3)
    if (multigene) {
        createCookie("dsmzcelldive_multigene", multigene, 3)
    }

    share = `?gene=${genes.join(';')}&multigene=${multigene}&matrix=${matrix}&selectby=${selectby}&samples=${samples}`;
    $('#share-btn').attr('href', share)
}

if ($('#gene').val()) {
    updateChart()
}
function onlyUnique(value, index, self) {
    return self.indexOf(value) === index;
}

function updateTable(data, genes = []) {
    var matrix = $('#matrix').val()
    var multigenes = $("input[name='multigene']:checked").val()
    var decimals = 3
    if ($('#matrix').val() == "counts"){
        decimals = 0
    }
    console.log(data);
    // if (!gene.includes(';')){
    //     multigene = "no"
    // }
    if (genes.length > 1 && multigenes == 'gene') {
        var i_group = 1
        var i_genes = 0
    } else {
        var i_group = 0
        var i_genes = 1
    }
    if (data.length == 0) { return; }
    if (genes.length > 1 && Array.isArray(data[0].x[0])) {
        genes = data[0].x[i_genes].filter(onlyUnique)
    }
    $("#content").html('')
    var table = $('<table class="table table-sm table-striped">')
    var thead = $('<thead>')
    var th = $("<tr>")
    th.append('<th>Cell line</th>')
    th.append('<th>Tumour</th>')
    genes.forEach(gene => {
        th.append('<th>' + gene + ' (' + matrix + ')</th>')
    });
    thead.append(th)
    table.append(thead)

    var tbody = $('<tbody>')

    for (const dn in data) {

        const group = data[dn];

        if (genes.length === 1) {
            for (let i = 0; i < group.y.length; i++) {
                var tr = $('<tr>')
                const cellline = group.x[i];
                const value = group.y[i];
                tr.append("<td><a href='"+ROOTPATH+"/cellline/"+cellline+"' target='_blank'>" + cellline + "</a></td>")
                tr.append("<td>" + group.name + "</td>")
                tr.append("<td class='text-right'>" + value.toFixed(decimals) + "</td>")
                tbody.append(tr)
            }
        } else {
            const n = genes.length;
            const m = group.y.length / n;
            for (let i = 0; i < (m); i++) {
                var tr = $('<tr>')
                const cellline = group.x[i_group][i];
                // const values = group.y.slice(i * n, i * n + n);
                // const cellline = group.x[i_group][i * n];
                // const values = group.y.slice(i * n, i * n + n);
                tr.append("<td><a href='"+ROOTPATH+"/cellline/"+cellline+"' target='_blank'>" + cellline + "</a></td>")
                tr.append("<td>" + group.name + "</td>")
                genes.forEach((gene, g) => {
                    var val = group.y[(i+g*m)];
                    tr.append("<td class='text-right'>" + val.toFixed(decimals) + "</td>")
                });
                tbody.append(tr)
            }
        }
    }
    table.append(tbody)

    $("#content").append(table)

}


function ui_message(msg = null, type = 'primary') {
    var div = $('#messages')
    if (msg === null) {
        div.html('')
        return
    }
    var alert = $('<div class="alert alert-' + type + '">')
    alert.append('<button class="close" data-dismiss="alert" type="button" aria-label="Close"><span aria-hidden="true">&times;</span></button>')
    alert.append(msg)
    div.append(alert)

}

function ui_selectby(el) {
    $('.cell-groups, .cell-celllines').hide()
    $('.cell-' + el.value).show()
}

function ui_selectall(el) {
    var checkboxes = $('.cell-groups,.cell-celllines').find('input:checkbox')
    checkboxes.prop('checked', $('#check_select-all').is(':checked'));
}



function ui_permURL() {
    // var location = window.location.href;

    window.location.href = share
    // navigator.clipboard.writeText(location + "?" + share);
}

function createCookie(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/; SameSite=Strict";
}

// Read cookie
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(";");
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === " ") {
            c = c.substring(1, c.length);
        }
        if (c.indexOf(nameEQ) === 0) {
            return c.substring(nameEQ.length, c.length);
        }
    }
    return null;
}

