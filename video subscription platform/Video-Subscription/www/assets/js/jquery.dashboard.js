!function($) {
    "use strict";
    var Dashboard = function() {
    	this.$realData = []
    };
    //creates Stacked chart
    Dashboard.prototype.createStackedChart  = function(element, data, xkey, ykeys, labels, lineColors) {
        Morris.Bar({
            element: element,
            data: data,
            xkey: xkey,
            ykeys: ykeys,
            stacked: true,
            labels: labels,
            hideHover: 'auto',
            resize: true, //defaulted to true
            gridLineColor: '#eeeeee',
            barColors: lineColors
        });
    },
    //creates area chart with dotted
    Dashboard.prototype.createAreaChartDotted = function(element, pointSize, lineWidth, data, xkey, ykeys, labels, Pfillcolor, Pstockcolor, lineColors) {
        Morris.Area({
            element: element,
            pointSize: 0,
            lineWidth: 0,
            data: data,
            xkey: xkey,
            ykeys: ykeys,
            labels: labels,
            hideHover: 'auto',
            pointFillColors: Pfillcolor,
            pointStrokeColors: Pstockcolor,
            resize: true,
            gridLineColor: '#eef0f2',
            lineColors: lineColors,
            parseTime:false
        });

   },
    Dashboard.prototype.init = function() {
        // creating Stacked chart
        var month = $('#morris-bar-stacked').data('month');
        var subs = $('#morris-bar-stacked').data('subs');
        var paymt = $('#morris-bar-stacked').data('paymt');
        var arrayMonth = month.split(',');
        var arraySubs = subs.split(',');
        var arrayPaymt = paymt.split(',');
        var chart = [];
        for (var i = 0; i < arrayMonth.length; i++) {
            var element = {};
            element.y = arrayMonth[i];
            element.a = Number(arraySubs[i]);
            element.b = Number(arrayPaymt[i]);
            chart.push(element);
        }
        this.createStackedChart('morris-bar-stacked', chart, 'y', ['a', 'b'], ['Subscriptions', 'Payments'], ['#465059', '#B0CAEA']);
        //creates area chart with dotted
        var $array = $('#morris-area-with-dotted').data('played');
        $array = $array.split(',');
        var $arrayLast = $('#morris-area-with-dotted').data('last');
        $arrayLast = $arrayLast.split(',');
        var $areaDotData = new Array();
        for (var i = 0; i < $array.length; i++) {
            $areaDotData.push({ d: i.toString(), a: $array[i], b:$arrayLast[i]});
        }
        this.createAreaChartDotted('morris-area-with-dotted', 0, 0, $areaDotData, 'd', ['a', 'b'], ['Subscriptions', 'Payments'],['#ffffff'],['#999999'], ['#36404a', '#5d9cec','#bbbbbb']);
    },
    //init
    $.Dashboard = new Dashboard, $.Dashboard.Constructor = Dashboard
}(window.jQuery),
//initializing
function($) {
    "use strict";
    $.Dashboard.init();
}(window.jQuery);
