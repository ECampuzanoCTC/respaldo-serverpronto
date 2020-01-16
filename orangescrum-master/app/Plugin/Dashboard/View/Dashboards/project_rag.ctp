<div id="bck_rag" class="fl hide_buttn"><button type="button" value="Back" name="Back" class="btn btn_blue" onclick="hide_flscrn_div('project_rag')"><?php echo __("Back"); ?></button></div>
<div id="exprt_rag" class="fr hide_buttn"><button type="button" value="Export" name="Export" class="btn btn_blue" onclick="export_rag()"><?php echo __("Export(.csv)"); ?></button></div>
<div class="cb"></div>
<div class="table-responsive prjct-rag-tbl">
    <table class="table table-hover" id="rag_table">
        <thead>
            <tr>
                <th><?php echo __("Project Name"); ?></th>
                <th><?php echo __("Client Name"); ?></th>
                <th class="hide_td"><a class="sorttitle" onclick="sorting_rag('start_date');" title="Start Date" href="javascript:void(0);">
                        <div class="fl"><?php echo __("Start Date"); ?></div>
                        <div id="tsk_sort1" class="tsk_sort fl "></div>
                    </a></th>
                <th class="hide_td"><a class="sorttitle" onclick="sorting_rag('end_date');" title="End Date" href="javascript:void(0);">
                        <div class="fl"><?php echo __("End Date"); ?></div>
                        <div id="tsk_sort2" class="tsk_sort fl "></div>
                    </a></th>
                <th class="hide_td vscntnt" ><span style="display:block"><?php echo __("Estimated Hour(s)"); ?></span><span style="display:block">V/S</span><?php echo __("Hour(s) Spent"); ?></th>
                <th class="hide_td vscntnt" ><span style="display:block"><?php echo __("Total Task(s)"); ?></span><span style="display:block">V/S</span><?php echo __("Closed Task(s)"); ?></th>
                <!-- <th class="hide_td"><?php echo __("Total Task(s)/Closed Task(s)"); ?></th> -->
                <?php if (SES_TYPE == 1 || (SES_TYPE == 2 && $GLOBALS['is_admin_allowed'] == 1)) { ?>
                <th class="hide_td"><?php echo __("Budget"); ?></th>
                <th class="hide_td vscntnt"><?php echo __("Cost Approved"); ?></th>
                <th class="hide_td vscntnt"><?php echo __("Cost Incurred"); ?></th>
                <th class="hide_td vscntnt"><?php echo __("Cost Remaining"); ?></th>
                <?php } ?>
                <th style="text-align:center;"><?php echo __("RAG Time Status"); ?></th>
                <th style="text-align:center;"><?php echo __("RAG Cost Status"); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $limit = 5;
            $cnt = 0;
            #echo "<pre>";print_r($projects);exit;
            foreach ($projects as $k => $project) {
                $strt_date = (isset($project['start_date']) && !empty($project['start_date'])) ? $this->Format->chngdate($project['start_date']) : $this->Format->chngdate($project['dt_created']);
                $end_date = (isset($project['end_date']) && !empty($project['end_date'])) ? $this->Format->chngdate($project['end_date']) : 'No end date';
                $budget = isset($project['budget']) && $project['budget'] != '0.00' ? $project['budget'] : 0;
                $cost_approved = isset($project['cost_approved']) && $project['cost_approved'] != '0.00' && !empty($project['cost_approved']) ? $project['cost_approved'] : 0;
                $actual_cost = isset($project['actual_cost']) && $project['actual_cost'] != '0.00' ? $project['actual_cost'] : 0;

                $cost_approved = $cost_approved != 0 ? $cost_approved : $budget;
                $cost_balance = ($cost_approved - $actual_cost);
                ?>
                <tr <?php if ($cnt >= $limit) { ?> style="display:none;" <?php } ?> class="prograg_tr">
                    <td><?php echo $project['name']; ?></td>
                    <td><?php echo!empty($project['client_company_name']) ? $project['client_company_name'] : __('None'); ?></td>
                    <td class="hide_td" rel="tooltip" title="<?php echo $strt_date; ?>"><?php echo (isset($project['start_date']) && !empty($project['start_date'])) ? $strt_date : $strt_date; ?></td>
                    <td class="hide_td" rel="tooltip" title="<?php echo $end_date; ?>"><?php echo (isset($project['end_date']) && !empty($project['end_date'])) ? $end_date : 'No end date'; ?></td>
                    <td class="hide_td" style="text-align:center;"><?php echo isset($project['estimated_hours']) && $project['estimated_hours'] != '0.0' ? $project['estimated_hours'] : '0.0'; ?> / <?php echo isset($project['spent_hours']) ? $project['spent_hours'] : '0.0'; ?></td>
                    <td class="hide_td" style="text-align:center;"><?php echo isset($project['total_tasks']) ? $project['total_tasks'] : '0'; ?> / <?php echo isset($project['closed_tasks']) ? $project['closed_tasks'] : '0'; ?></td>
                 <?php if (SES_TYPE == 1 || (SES_TYPE == 2 && $GLOBALS['is_admin_allowed'] == 1)) { ?>
                <td class="hide_td"><?php echo isset($project['budget']) && $project['budget'] != '0.00'? $project['budget']."&nbsp;<small>".$project['currency']."</small>" : '0.00'; ?></td>
                <td class="hide_td" style="text-align:center;"><?php echo isset($project['cost_approved']) && $project['cost_approved'] != '0.00'? $project['cost_approved']."&nbsp;<small>".$project['currency']."</small>" : '0.00'; ?></td>
                <td class="hide_td" style="text-align:center;"><?php echo isset($project['actual_cost']) && $project['actual_cost'] != '0.00'? $project['actual_cost']."&nbsp;<small>".$project['currency']."</small>" : '0'; ?></td>
                <td class="hide_td" style="text-align:center;"><?php echo isset($cost_balance) && $cost_balance != 0 ? $cost_balance."&nbsp;<small>".$project['currency']."</small>" : '0'; ?></td>
                 <?php } ?>
                <td style="text-align:center;"><span class="label label-<?php echo $project['rag']; ?>" ></span></td> <?php //rel="tooltip" title="<?php echo $project['rag_title']; ?>
                    <td style="text-align:center;"><span class="label label-<?php echo $project['rag2']; ?>" ></span></td><?php //rel="tooltip" title="<?php echo $project['rag_title2'];  ?>
                </tr>
                <?php $cnt++;
            } /*
              if(count($projects) >= $limit){ ?>
              <tr class="view_tr">
              <td></td>
              <td></td>
              <td><a onclick="fulscreen_div('timelog')" href="javascript:void(0)">View All</a></td>
              </tr>
              <?php } */ ?>
        </tbody>
    </table>
</div>
<script>
    function export_rag() {
        $.post(HTTP_ROOT + "Dashboard/dashboards/project_rag?type=export", {"type": "export"}, function (res) {
            location.href = HTTP_ROOT + "Dashboard/dashboards/project_rag?type=export";
        });
    }
    $(document).ready(function () {
        $('[rel=tooltip]').tipsy({gravity: 's', fade: true});
    });
    function sorting_rag(type) {

        if (type == 'start_date') {
            var tsk_sort = '#tsk_sort1';
        } else if (type == 'end_date') {
            var tsk_sort = '#tsk_sort2';
        }
        var cls = $(tsk_sort).attr('class');
        cls = cls.split(" ");
        if (cls[3] == 'tsk_asc') {
            var sort = 'DESC';
        } else {
            var sort = 'ASC';
        }

        $.post(HTTP_ROOT + "Dashboard/dashboards/project_rag", {'type': type, 'sort': sort}, function (data) {
            if (data) {
                $('#project_rag').html(data);
                $(".hide_buttn").show();
                $('.view_tr').show();
                $('.hide_td').show();
                $('.prograg_tr').show();
                if (sort == 'ASC') {
                    $(tsk_sort).addClass('tsk_asc');
                } else {
                    $(tsk_sort).addClass('tsk_desc');
                }

                //$('#logstrtdt').val('');
                //$('#logenddt').val('');
            }
        });
    }
</script>