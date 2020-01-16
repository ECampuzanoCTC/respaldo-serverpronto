<style>
  /*  #dboardtimelog3 {
    height:auto;
   
    position:absolute; 
}*/
</style> 
<div id="dboardtimelog3" class="tlog-chrt-prnt"> <?php //echo "<pre>";print_r($project_name); exit;?></div>

<script>
$(function () { 
    
    var prjct = <?php echo $project_name; ?>;
    var prjct_shtname = <?php echo $project_shrtname; ?>;
    var series =<?php echo $series; ?>;
   
    $('#dboardtimelog3').highcharts({

        chart: {
            type: 'column'
        },

        title: {
            align: 'left',
            text: ' '
        },
        scrollbar:{
            enabled:true
        },
        exporting: {
            enabled: true,
            buttons: {
                contextButton: {
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
            }
        },
        xAxis: {         
            categories: prjct,
            showFirstLabel:true,
            showLastLabel:true,
            title: {
                text: '<?php echo __("Project"); ?>'
            },
            min:0,
            max:prjct.length >= 3 ? 3: '',
            labels:{
                    formatter:function(){
                        return(unescape(escape(this.value)).substring(0,10)+"...");
                        
                        }
                }    
        },

        yAxis: {
            allowDecimals: false,
            gridLineWidth: 0,
            minorGridLineWidth: 0,
            min: 0,
            title: {
                text: '<?php echo __("Hour(s) Spent"); ?>'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                },
                formatter:function(){
                    return Math.round(this.total * 100) / 100;
                }
            }
        },

        tooltip: {
        // pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
            formatter: function () {          
            var s = '<b>' + this.x + '</b>';
               $.each(this.points, function () {
                    var hrs = parseInt(Number(this.y));
                    var min = Math.round((Number(this.y)-hrs) * 60);
                    var ytimes = hrs +" hrs "+ min +" mins";
                    s += '<br/>' + this.series.name + ': ' +
                        ytimes ;
                });

                return s;
            },
            shared: true
        },
		credits: {
			enabled: false
		},
        plotOptions: {
            series: {
                stacking: 'normal',
                dataLabels: {
                    enabled: false,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },

        series: series
    });
});
</script>