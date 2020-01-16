<div id="chart_log_view" class="task_listing timelog_lview  timelog-table setting_wrapper task_listing">
    <div class="tlog_top_cnt timelog-table-head plan_page">
        <div class="fl"><h3 class="noborder"><?php echo __("Chart View"); ?></h3></div>       
        <div class="fr ">
        </div>
        <div class="cb"></div>
    </div>
    <div class="cb"></div>
    <?php 
/* Get the name of the week days */
$timestamp = strtotime('next Sunday');
$weekDays = array();
for ($i = 0; $i < 7; $i++) {
	$weekDays[] = strftime('%a', $timestamp);
	$timestamp = strtotime('+1 day', $timestamp);
}
$blank = date('w', strtotime("{$year}-{$month}-01"));
?>
<table class='table table-bordered pChart' style="table-layout: fixed;">
	<tr>
        <th colspan="7" class="text-center">
            <a href="javascript:void(0);" onclick="getChartForTimeLog('chart','<?php echo $prevMonth ; ?>');" class="next-prev-month">
                <img src="<?php echo HTTP_ROOT; ?>img/images/arrow1.png" alt="Prev" style="transform:rotate(270deg)" />
            </a> 
                <?php echo $title ?> <?php echo $year ?>
            <a href="javascript:void(0);" onclick="getChartForTimeLog('chart','<?php echo $nextMonth  ; ?>');"  class="next-prev-month">
                <img src="<?php echo HTTP_ROOT; ?>img/images/arrow1.png" alt="Next" style="transform:rotate(90deg)" />
            </a>
        </th>
	</tr>
	<tr class="day-row">
		<?php foreach($weekDays as $key => $weekDay) : ?>
			<td><?php echo $weekDay ?></td>
		<?php endforeach ?>
	</tr>
	<tr>
		<?php for($i = 0; $i < $blank; $i++): ?>
			<td></td>
		<?php endfor; ?>
		<?php for($i = 1; $i <= $daysInMonth; $i++):
                    $new_i=sprintf("%02d", $i);
                    ?>
			<td>
                            <?php if($day == $i && date('y-m-d', strtotime("{$year}-{$month}-{$day}"))== date('y-m-d')): ?>
                            <strong><span class="ddate"><?php echo $i; ?></span></strong>
                            <?php else: ?>                                                
                                <span class="ddate"><?php echo $i ?></span>                            
                            <?php endif; ?>
                            <?php if(isset($chart[$new_i])){ ?>
                                <div id="container_pie<?php echo $new_i;?>" class="container_pie"></div>
                                <div id="pophoverCnt<?php echo $new_i; ?>" class="pophoverCnt" >
                                    <!--div class='arrow-left'></div-->
                                    <div class="pophoverCntHeader"> 
                                        <h2 class="text-center"><?php print $this->Format->chngdate($chart[$new_i]['start_datetime']); ?></h2>
                                    </div>
                                    <div class="innerPie" id="popover_tooltip<?php echo $new_i; ?>"></div>
                                    <div class="fr innerPieDesc"><?php print $chart[$new_i]['actual_hours'];?> over
                                        <br /><?php echo count($chart[$new_i]['tasks']);?> task(s) by 
                                        <br /><?php echo count($chart[$new_i]['users']);?> user(s) 
                                        <!--br />tracking!--></div>
                                    <div class="cb"></div>
                                    <h4 class="tch-hd"><?php echo __("Top 5 Tasks"); ?></h4>
                                    <div>
                                        <?php $count=1;
                                        foreach($chart[$new_i]['tasks'] as $k=>$task){
                                            if($count <= 5){
                                            ?>                                            
                                        <div class="top5tasks">
                                            <strong class="fl"><?php echo $task['agrigate_hours'];?></strong> 
                                            <div class="ellipsis-view max-width-75 fl" rel="tooltip" title="<?php echo $task['title']?>"> <?php echo $task['title']?> </div>
                                            <div class="users-top-task fr">
                                         <?php  $uids=explode(',',$task['userids']);
                                                foreach($uids as $key1=>$value1){ ?>
                                                <span>
                                                 <?php if(trim($chart[$new_i]['users'][$value1]['photo'])) { ?>
                                                    <img data-original="<?php echo HTTP_ROOT;?>users/image_thumb/?type=photos&file=<?php echo $chart[$new_i]['users'][$value1]['photo']; ?>&sizex=28&sizey=28&quality=100" src="<?php echo HTTP_ROOT;?>users/image_thumb/?type=photos&file=<?php echo $chart[$new_i]['users'][$value1]['photo']; ?>&sizex=28&sizey=28&quality=100" class="lazy round_profile_img " rel="tooltip" title="<?php echo $chart[$new_i]['users'][$value1]['name']; ?>" height="28" width="28" />
                                        <?php } else {
                                                    $random_bgclr = $this->Format->getProfileBgColr($value1);
                                                    $usr_name_fst = mb_substr(trim($chart[$new_i]['users'][$value1]['name']),0,1, "utf-8");											
                                            ?>
                                            <span class="cmn_profile_holder <?php echo $random_bgclr; ?>" rel="tooltip" title="<?php echo $chart[$new_i]['users'][$value1]['name']; ?>" ><?php echo $usr_name_fst; ?></span>
                                        <?php } ?>
                                            <i> <?php echo $task['uid'][$value1]?></i>
                                                </span>    
                                              <?php   }
                                         ?>
                                    </div>
                                            <div class="cb"></div>
                                        </div>
                                        <?php 
                                            }else{ ?>
                                        <div class="text-center width100"><a class="italic font13 see-more-log" href="javascript:void(0);" onclick="showTimeLogPage('<?php echo date('M d, Y', strtotime("{$year}-{$month}-{$i}"));?>',event);"><i><?php echo __("Click here to see all records"); ?></a></div> 
                                         <?php break;
                                            }
                                        $count++;
                                        } ?>
                                </div>
                                </div>
                            <?php } ?>
                        </td>
			<?php if(($i + $blank) % 7 == 0): ?>
				</tr><tr>
			<?php endif; ?>
		<?php endfor; ?>
		<?php for($i = 0; ($i + $blank + $daysInMonth) % 7 != 0; $i++): ?>
			<td></td>
		<?php endfor; ?>
	</tr>
</table>
    <?php //pr($datas);?>
</div>
<script type="text/javascript">
    var datas = '<?php print $datas; ?>';
    datas = JSON.parse(datas);
    $(function() {
        var newArray = new Array();
        for (var d in datas) {
            data = []; 
            data.push(datas[d]); 
            newArray[d] = data;
            $('#container_pie' + d).highcharts({
                chart: {
                    margin: 0,
                    padding:0,
                    type: 'pie',
                    width:datas[d].agrigate_hours,
                    height:datas[d].agrigate_hours,
                    spacingBottom:0,
                    spacingLeft:0,
                    spacingRight:0,
                    spacingTop:0
                },
                title: {
                    text: null
                },
                plotOptions: {
                    series: {
                        animation: false,
                        dataLabels: {
                            enabled: false
                        }
                    },
                    pie: {
                        size: datas[d].agrigate_hours
                    }
                },
                tooltip: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                series: data
            });
        }
        $(".container_pie").closest('td').mouseenter(function(res) { 
            if (!$(this).find(".pophoverCnt").is(':visible')) {
            var  pophoverCnt=$(this).find(".pophoverCnt");
            //Calculate the left 
            var right = $(this).offset().left +  $(this).outerWidth();            
            if(right + 370 > $(document).width()){
               pophoverCnt.css('right',$(document).width() - $(this).offset().left - 30);  
            }else{ 
                pophoverCnt.css('left',$(this).offset().left-60);  
            }
            //Calculate the height
            var topHeight = $(this).offset().top; 
            if(topHeight + $(this).find(".pophoverCnt").height() > $(document).height()){ 
                hight=(topHeight + $(this).find(".pophoverCnt").height()-$(document).height());
                pophoverCnt.css('top',topHeight-200-hight);
            }else{
                pophoverCnt.css('top',topHeight-200);
            }
            // end
            pophoverCnt.show();    
               
                
                id = $(this).find(".pophoverCnt").find("div[id^='popover_tooltip']").first().attr('id').replace("popover_tooltip", "");
                $(this).find(".pophoverCnt").find("div[id^='popover_tooltip']").highcharts({
                    chart: {                       
                        margin: 0,
                        padding:0,
                        type: 'pie',
                        width:150,
                        height:150,
                        spacingBottom:0,
                        spacingLeft:0,
                        spacingRight:0,
                        spacingTop:0
                        
                    },
                    title: {
                        text: null
                    },
                    tooltip: {
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b><br />'+ this.point.hours ;
                        }
                    },
                    plotOptions: {
                        pie: {
                            size: 120,
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: false
                            },
                            showInLegend: true
                        }
                    },
                    legend: {
                        enabled: false,
                        labelFormatter: function() {
                            return this.name + ' - ' + this.y + '%s';
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    series: newArray[id]
                });
            }
        });
        $(".container_pie").closest('td').mouseleave(function(res) {
            if($('.tipsy:hover').length){
                return false;
            }
            $(this).find(".pophoverCnt").hide();
        });
    });  
    $(document).ready(function() {
        $('[rel="tooltip"]').tipsy({gravity: 's', fade: true});    
    });
    function showTimeLogPage(d,e){       
         $('#logstrtdt,#logenddt').val(d);
         $('#tlog_date').val(d + ':' + d);
         $('#tlog_externalfilter').val('1');         
         window.location.hash="timelog";
    }
</script>
