$(document).ready(function () {
    $("#resultado").css('display', 'none');
    $(".btn_toggle").click(function () {
        $(".btn_toggle").removeClass("active");
        $(this).addClass("active");
    });
    $("#buscar").click(function () {
        var tipo_busqueda = $('.btn_toggle[class~="active"]').attr('rel');
        $("#resultado").css('display', 'block');
        $("#line-chart").html("");
        if (tipo_busqueda == 1) {
            $.getJSON(base_url + "/index.php?r=estadistica/historial-visitas&desde=" + $("#fecha_desde").val() + "&hasta=" + $("#fecha_hasta").val(), function (data) {
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
        }
        /* SPARKLINE CHARTS
         * ----------------
         * Create a inline charts with spark line
         */

        //-----------------
        //- SPARKLINE BAR -
        //-----------------
        /*$.getJSON(base_url + "/index.php?r=estadistica/cantidad-clientes-periodo&desde=" + $("#fecha_desde").val() + "&hasta=" + $("#fecha_hasta").val(), function (data) {
         $("#id_clientes_periodo").html(data.cantidad);
         
         $('.sparkbar').each(function () {
         var $this = $(this);
         $this.sparkline('html', {
         type: 'bar',
         height: $this.data('height') ? $this.data('height') : '30',
         barColor: $this.data('color')
         });
         });
         $('.sparkbar').sparkline([ 90,70,90,70,75,80,70 ], { type: 'bar',barColor:'#fff' });
         });*/
        if (tipo_busqueda == 2) {
            $("#line-chart").html("");
            $.getJSON(base_url + "/index.php?r=estadistica/historial-visitas-semana&desde=" + $("#fecha_desde").val() + "&hasta=" + $("#fecha_hasta").val(), function (data) {
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
                        //return d;
                    },
                    dateFormat: function (ts) {
                        var d = new Date(ts);
                        return d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear();
                    }
                });
                line.redraw();

            });
        }
        
        if (tipo_busqueda == 3) {
            $("#line-chart").html("");
            $.getJSON(base_url + "/index.php?r=estadistica/historial-visitas-mes&desde=" + $("#fecha_desde").val() + "&hasta=" + $("#fecha_hasta").val(), function (data) {
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
                        //return d;
                    },
                    dateFormat: function (ts) {
                        var d = new Date(ts);
                        return d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear();
                    }
                });
                line.redraw();

            });
        }
    });


});