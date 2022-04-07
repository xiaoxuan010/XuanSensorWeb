var mainChart;
var $ = mdui.$;
var updateTimer;
var isOnFocus = true;

window.onload = function () {
    globalInit();
}

window.onfocus = function () {
    isOnFocus = true;
}

window.onblur = function () {
    isOnFocus = false;
}

$('#filter-select').on("close.mdui.select", function (e) { updateData() });

function globalInit() {
    chartComInit();
    updateData();
    window.onresize = function () {
        mainChart.resize();
    };
}

function ajaxJSONP(url) {
    var scriptElem = document.createElement('script');
    $(scriptElem).attr('src', url);
    $('body').append(scriptElem);
}

function newestDataGot(dataObj) {
    if (dataObj.code != 0) {
        console.log(dataObj);
        return;
    }
    $('#tem-num').text(dataObj.data.tem + '°C');
    $('#hum-num').text(dataObj.data.hum + '%');

    var LastUpdateTime = parseInt(dataObj.data.time) * 1000;
    var nextUpdateTime = LastUpdateTime + 35 * 1000;
    var timeout = nextUpdateTime - new Date().getTime();
    clearTimeout(updateTimer);
    if (timeout < 0) {
        console.log('Sensor disconnected');
        return;
    }
    else {
        updateTimer = setTimeout(updateData, timeout);
        if (isOnFocus)
            mdui.snackbar({ message: "数据已刷新", timeout: 1500 });
    }
}

function listDataGot(dataObj) {
    if (dataObj.code != 0) {
        console.log(dataObj);
        return;
    }
    $.each(dataObj.data, function (index, value) {
        value[0] *= 1000;
    });
    var dataDivi = Array(Array(), Array());
    for (var i = 0; i < 2; i++) {
        $.each(dataObj.data, function (index, value) {
            var dataRow = Array(value[0], value[i + 1]);
            dataDivi[i].push(dataRow);
        });
    }
    var mainOpt = {
        series: [{
            data: dataDivi[0]
        }, {
            data: dataDivi[1]
        }]
    };
    mainChart.setOption(mainOpt);
}


function chartComInit() {
    mainChart = echarts.init(document.getElementById('main-chart'));

    var mainOpt = {
        title: {},
        tooltip: {
            trigger: 'axis',
            valueFormatter: function (value) {
                return value.toFixed(2);
            }
        },
        legend: {},
        xAxis: {
            type: 'time'
        },
        yAxis: [{
            type: 'value',
            name: '相对湿度',
            position: 'right',
            axisLabel: {
                formatter: '{value}%'
            }
        }, {
            type: 'value',
            name: '气温',
            position: 'left',
            axisLabel: {
                formatter: '{value}°C'
            }
        }
        ],
        dataZoom: [{
            type: 'inside'
        }, {
            type: 'slider'
        }],
        series: [{
            name: '气温',
            type: 'scatter',
            yAxisIndex: 1,
            symbolSize: 3
        }, {
            name: '相对湿度',
            type: 'scatter',
            yAxisIndex: 0,
            symbolSize: 3
        }]
    };

    mainChart.setOption(mainOpt);

}

function updateData() {
    ajaxJSONP(`http://106.55.41.100:419/jsonp/get-newest-data.php?callback=newestDataGot`);
    ajaxJSONP(`http://106.55.41.100:419/jsonp/get-list-data.php?dt=${$('#filter-select').val()}&callback=listDataGot`);
}