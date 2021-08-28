$(function () {
    /* jQueryKnob */

    $(".knob").knob({
        /*change : function (value) {
         //console.log("change : " + value);
         },
         release : function (value) {
         console.log("release : " + value);
         },
         cancel : function () {
         console.log("cancel : " + this.value);
         },*/
        draw: function () {

            // "tron" case
            if (this.$.data('skin') == 'tron') {

                var a = this.angle(this.cv)  // Angle
                        , sa = this.startAngle          // Previous start angle
                        , sat = this.startAngle         // Start angle
                        , ea                            // Previous end angle
                        , eat = sat + a                 // End angle
                        , r = true;

                this.g.lineWidth = this.lineWidth;

                this.o.cursor
                        && (sat = eat - 0.3)
                        && (eat = eat + 0.3);

                if (this.o.displayPrevious) {
                    ea = this.startAngle + this.angle(this.value);
                    this.o.cursor
                            && (sa = ea - 0.3)
                            && (ea = ea + 0.3);
                    this.g.beginPath();
                    this.g.strokeStyle = this.previousColor;
                    this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
                    this.g.stroke();
                }

                this.g.beginPath();
                this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
                this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
                this.g.stroke();

                this.g.lineWidth = 2;
                this.g.beginPath();
                this.g.strokeStyle = this.o.fgColor;
                this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
                this.g.stroke();

                return false;
            }
        }
    });


    /* END JQUERY KNOB */

    'use strict';

    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */


    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas);
    /*var PieData = [
     {
     value: 700,
     color: "#f56954",
     highlight: "#f56954",
     label: "Chrome"
     },
     {
     value: 500,
     color: "#00a65a",
     highlight: "#00a65a",
     label: "IE"
     },
     {
     value: 400,
     color: "#f39c12",
     highlight: "#f39c12",
     label: "FireFox"
     },
     {
     value: 600,
     color: "#00c0ef",
     highlight: "#00c0ef",
     label: "Safari"
     },
     {
     value: 300,
     color: "#3c8dbc",
     highlight: "#3c8dbc",
     label: "Opera"
     },
     {
     value: 100,
     color: "#d2d6de",
     highlight: "#d2d6de",
     label: "Navigator"
     }
     ];*/
    var pieOptions = {
        //Boolean - Whether we should show a stroke on each segment
        segmentShowStroke: true,
        //String - The colour of each segment stroke
        segmentStrokeColor: "#fff",
        //Number - The width of each segment stroke
        segmentStrokeWidth: 1,
        //Number - The percentage of the chart that we cut out of the middle
        percentageInnerCutout: 50, // This is 0 for Pie charts
        //Number - Amount of animation steps
        animationSteps: 100,
        //String - Animation easing effect
        animationEasing: "easeOutBounce",
        //Boolean - Whether we animate the rotation of the Doughnut
        animateRotate: true,
        //Boolean - Whether we animate scaling the Doughnut from the centre
        animateScale: false,
        //Boolean - whether to make the chart responsive to window resizing
        responsive: true,
        // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio: false,
        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
        //String - A tooltip template
        tooltipTemplate: "<%=value %> <%=label%>"
    };
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    $.getJSON(base_url + "/index.php?r=site/cantidad-visitas-navegacion", function (data) {
        pieChart.Doughnut(data, pieOptions);
        var total = 0;
        $.each(data, function (key, val) {
            $(".chart-legend").append('<li><i class="fa fa-circle-o ' + val.clase + '"></i>' + val.label + '</li>');
            total += parseInt(val.value);
        });
        var porcentaje = 0;
        $.each(data, function (key, val) {
            porcentaje = (parseInt(val.value) * 100) / total;
            $("#porcentajes_visitas").append('<li><a>' + val.label + '<span class="pull-right ' + val.clase + '"><i class="fa fa-angle-down"></i> ' + porcentaje.toFixed(2) + '%</span></a></li>');
        });
    });

    /* SPARKLINE CHARTS
     * ----------------
     * Create a inline charts with spark line
     */

    //-----------------
    //- SPARKLINE BAR -
    //-----------------
    $('.sparkbar').each(function () {
        var $this = $(this);
        $this.sparkline('html', {
            type: 'bar',
            height: $this.data('height') ? $this.data('height') : '30',
            barColor: $this.data('color')
        });
    });

    

    $.getJSON(base_url + "/index.php?r=site/historial-visitas", function (data) {

        var line = new Morris.Line({
            element: 'line-chart',
            resize: true,
            data: data,
            xkey: 'fecha',
            ykeys: ['cantidad'],
            labels: ['Cantidad'],            
            lineColors: ['#efefef'],
            lineWidth: 2,
            hideHover: 'auto',
            gridTextColor: "#fff",
            gridStrokeWidth: 0.4,
            pointSize: 4,
            pointStrokeColors: ["#efefef"],
            gridLineColor: "#efefef",
            gridTextFamily: "Open Sans",
            gridTextSize: 10,
            xLabelFormat: function (d) {
                return d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear();
            },
            dateFormat: function (ts) {                
                var d = new Date(ts);
                return d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear();
            }
        });
        line.redraw();        
    });

});