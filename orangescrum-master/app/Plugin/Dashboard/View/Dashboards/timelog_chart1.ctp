<div id="tmlog_bck" class="fl hide_buttn"><button type="button" value="Back" name="Back" class="btn btn_blue" onclick="fulscreen_timelog('timelog')"><?php echo __('Back'); ?> </button></div>
<div id="exprt_timelog" class="fr hide_buttn"><button type="button" value="Export" name="Export" class="btn btn_blue" onclick="export_timelog()"><?php echo __("Export Task (.csv)"); ?></button></div>
<?php //echo "<pre>";print_r($dt_arr);exit;?>
<input type="hidden" id="prjct_id" name="prjct_id" value="<?php echo $project_id ; ?>">
<input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id ; ?>">
<input type="hidden" id="time_flt" name="time_flt" value="<?php echo $time_flt ; ?>">
<div class="cb"></div>
<div id="dboardtimelog" class="tlog-chrt-prnt"></div>
<div id="timelog_table" class="table-responsive">
    <table class="table table-hover tbody_scroll">
        <thead>
            <tr>
                <th><?php echo __("Project Name"); ?> </th>
                <th><?php echo __("Resource Name"); ?></th>
                <th><?php echo __("Date"); ?></th>
                <th style="text-align:center"><?php echo __("Billable Hour"); ?></th>
                <th style="text-align:center"><?php echo __("Non Billable Hour"); ?></th>
                <th style="text-align:center"><?php echo __("Total Hour"); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php  
                foreach($log_times as $k => $val){ 
                    $billbale_hr = isset($val['billablehr']) && !empty($val['billablehr']) ? $val['billablehr'] : '0' ;
                    $unbillable_hr = isset($val['non_billablehr']) && !empty($val['non_billablehr']) ? $val['non_billablehr'] : '0' ;
                    $total_hr = round($billbale_hr + $unbillable_hr,2); 
                    $bil_hr = (int)($billbale_hr);
                    $bil_min = round(($billbale_hr - $bil_hr)*60);
                    $non_bil_hr = (int)($unbillable_hr);
                    $non_bil_min = round(($unbillable_hr - $non_bil_hr)*60);
                    $tot_hr = (int)($total_hr);
                    $tot_min = round(($total_hr - $tot_hr)*60) ;?>
            
            <tr>
                <td><?php echo $val['project_name'] ;//$prjnm != $val['Project']['name'] ? $val['Project']['name'] : ''; ?></td>
                <td><?php echo $val['user_name']; ?></td>
                <td><?php echo $this->Format->chngdate($k); ?></td>
                <td style="text-align:center"><?php echo $bil_hr." hrs&nbsp".$bil_min." mins" ; ?></td>
               <td style="text-align:center"><?php echo $non_bil_hr." hrs&nbsp".$non_bil_min." mins" ; ?></td>
                <td style="text-align:center"><?php echo $tot_hr." hrs&nbsp".$tot_min." mins" ; ?></td>
            </tr>
            <?php 
                } ?>
        </tbody>
    </table>
</div>
<script>
    function export_timelog(){
        var prjct_id = $('#prjct_id').val();
        var user_id = $('#user_id').val();
        var time_flt =  $('#time_flt').val();
        window.open(HTTP_ROOT +"Dashboard/dashboards/timelog_chart1?type=export&project_id="+prjct_id+"&user_id="+user_id+"&time_flt="+time_flt);
    }
$(function () {
    $('#expand_tlg').show();
    var dt = <?php echo $dt_arr; ?>;
    var series =<?php echo $series; ?>;
    $('#dboardtimelog').highcharts({

        
        title: {
            align: 'left',
            text: ' '
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
            type:'datetime',
            categories: eval(dt),
            showFirstLabel:true,
            showLastLabel:true,
            tickInterval: <?php echo $tinterval; ?>,
            title: {
                text: '<?php echo __("Dates"); ?>'
            }
        },

        yAxis: {
            allowDecimals: false,
            gridLineWidth: 0,
            minorGridLineWidth: 0,
            min: 0,
            title: {
                text: '<?php echo __("Hour(s) Spent"); ?>'
            }
        },

        tooltip: {
         //   pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
         //   shared: true
          formatter: function () {          
            var s = '<b>' + this.x + '</b>';
               $.each(this.points, function () {
                    var hrs = parseInt(Number(this.y));
                    var min = Math.round((Number(this.y)-hrs) * 60);
                    var ytimes = hrs +"hrs "+ min +" mins";
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
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: eval(series)
    });
});
</script>