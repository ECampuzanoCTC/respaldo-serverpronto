
<div id="tsk_status_grph" class="tlog-chrt-prnt"> <?php //echo "<pre>";print_r($status_report) ; ?> </div>


<script>
$(function(){
     var res = <?php echo $status_report; ?>;
/*if ($("#tsk_status_grph").highcharts()) {
        $("#tsk_status_grph").highcharts().destroy();
    } */

    if (res.task_prog != null) {
    Highcharts.setOptions({
        lang: {
            contextButtonTitle: 'Download'
        }
    });
    $("#tsk_status_grph").highcharts({
        credits: {
            enabled: false
        },
        exporting: {
                        enabled: true,
            buttons: {
                contextButton: {
                                align: 'right',
                    symbolStrokeWidth: 2,
                    symbolStroke: '#969696',
                    menuItems: [{
                            text: 'PNG',
                            onclick: function () {
                                this.exportChart();
                            },
                            separator: false
                        }, {
                            text: 'JPEG',
                            onclick: function () {
                                this.exportChart({
                                    type: 'image/jpeg'
                                });
                            },
                            separator: false
                        }, {
                            text: 'PDF',
                            onclick: function () {
                                this.exportChart({
                                    type: 'application/pdf'
                                });
                            },
                            separator: false
                        }, {
                            text: 'Print',
                            onclick: function () {
                                this.print();
                            },
                            separator: false
                        }]
                }
            },
            filename: tsk_status_grph
        },
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 0,
            plotShadow: false,
            height: 290
        },
        title: {
                        text: ''
        },
        tooltip: {
            formatter: function () {
                var precsson = 3;
                if (this.point.percentage < 1)
                    precsson = 2;
                if (this.point.percentage >= 10)
                    precsson = 4;
                            return '<b>' + this.point.name + '</b>: ' + parseFloat((this.point.percentage).toPrecision(precsson)) + ' %';
            }
        },
        plotOptions: {
            pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            borderWidth: 0,
                            showInLegend: true,
                dataLabels: { 
                                enabled: typeof extra != 'undefined' ? (extra == 'overview' ? false : false) : false,
                                color: '#000000',
                                connectorColor: '#000000',
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        
                    }
            }
        },
        legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'top',
                        x: -20,
                        y: 110,
                        borderWidth: 0,
                        labelFormatter: function() { 
                            return this.name + ' - ' + this.y + '';
                        } 
                    },
        series: [{
                type: 'pie',
                name: ' ',
                
                data: res.task_prog
            }]
    });
    } 
    else{
        $('#tsk_status_grph').html("<div class='mytask_txt'>Oops! <?php echo $username['User']['name']; ?> <?php echo __("has not been assigned any task in the project"); ?> <?php echo $projectname; ?>! <?php echo __("Is it alright"); ?>?</div>");
    }
    
  });  
 </script>