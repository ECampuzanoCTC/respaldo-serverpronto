<div id="tsk_typ_grph" class="tlog-chrt-prnt"> </div>
<script>
$(function () {
     var res = <?php echo $task_report; ?>;
    
       if (res.status == 'success' && parseInt(res.total_cnt) > 0) {
                $('#tsk_typ_grph').html('');
                Highcharts.setOptions({
                    lang: {
                        contextButtonTitle: 'Download'
                    }
                });
                $('#tsk_typ_grph').highcharts({
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
                                    onclick: function() {
                                        this.exportChart();
                                    },
                                    separator: false
                                }, {
                                    text: 'JPEG',
                                    onclick: function() {
                                        this.exportChart({
                                            type: 'image/jpeg'
                                        });
                                    },
                                    separator: false
                                }, {
                                    text: 'PDF',
                                    onclick: function() {
                                        this.exportChart({
                                            type: 'application/pdf'
                                        });
                                    },
                                    separator: false
                                }, {
                                    text: 'Print',
                                    onclick: function() {
                                        this.print();
                                    },
                                    separator: false
                                }]
                            }
                        },
                        filename: tsk_typ_grph
                    },
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        height: typeof extra != 'undefined' ? (extra == 'overview' ? 175 : 290) : 290
                    },
                    title: {
                        text: ''
                    },
                    tooltip: {
                        formatter: function() {
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
                        data: res.data
                    }]
                });
            } else {
                <?php if(isset($projectname)){?>
                $('#tsk_typ_grph').html('<div class="mytask_txt">Oops! <?php echo $username['User']['name']; ?> <?php echo __("has not assigned any task in the project"); ?> <?php echo $projectname; ?>! <?php echo __("Is is alright"); ?>?</div>');
                <?php }else{ ?>
                $('#tsk_typ_grph').html('<div class="mytask_txt">Oops! <?php echo $username['User']['name']; ?> <?php echo __("has not assigned any task in any project! Is is alright");?> ?</div>');
                <?php } ?>
            }
        
});
</script>