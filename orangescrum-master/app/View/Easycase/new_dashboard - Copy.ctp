<style type="text/css">
    .new-dashboard-page{padding:10px;background:#fff;}
    .new-dashboard-page .row.top_dboard_src{margin:0px}
    .cmn_bdr_shadow{box-shadow:0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);-moz-box-shadow:0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);-webkit-box-shadow:0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);background:#fff;margin-bottom: 20px}
    .dashboard_page h2{margin:0px 0px 20px 0px;font-size:20px;}
    .width18{width:19%}
    .top_dboard_src .width18, .top_dboard_src .width25{padding:20px 5px;border-right:1px solid #D6D6D6;text-align:center;}
    .top_dboard_src .width18{padding:0 5px 0 0;color:#fff;height:100px;margin-right:13px}
    .top_dboard_src .width18.dash-proj{background:#27a9e3}
    .top_dboard_src .width18.dash-tsks{background:#28b779}
    .top_dboard_src .width18.dash-actusr{background:#ffb848}
    .top_dboard_src .width18.dash-hrs{background:#f74d4d}
    .top_dboard_src .width18.dash-fle{background:#2255a4}
    .top_dboard_src .width18:hover{background:#253650}
    .top_dboard_src .width18 ul{list-style-type: none;margin:0;padding:0;height:100%}
    .top_dboard_src .width18 ul li{display:inline-block;height:100%}
    .top_dboard_src .width18 ul li:first-child{float:left;position:relative;width:30%;box-sizing:border-box;}
    .top_dboard_src .width18 ul li:nth-child(2){float:right;width:70%;text-align:center;box-sizing:border-box;}
    .top_dboard_src .width18 ul li img{max-width:100%;width:70px;height:70px;position:absolute;top:0;left:0;right:0;bottom:0;margin:auto;display:inline-block}
    .top_dboard_src .width18 ul li h4{margin:10px 0 0}
    .top_dboard_src .width18 ul li h4 a, .top_dboard_src .width18 ul li h6{color:#fff}
    .top_dboard_src .width18 ul li h4 a{font-size:25px}
    .top_dboard_src .width18 ul li h6{ font-size: 13px; margin: 5px 0 0;}
     
    
    
    .top_dboard_src .width18:last-child, .top_dboard_src .width25:last-child{border-right:0px;}
    .top_dboard_src .width18 h4, .top_dboard_src .width25 h4{margin:0px;color:#333;font-family:'RobotoDraft-Medium';font-weight:bold;font-size:20px;}
    .top_dboard_src .width18 h4 span, .top_dboard_src .width25 h4 span{font-size:15px;}
    .top_dboard_src .width18 h6, .top_dboard_src .width25 h6{margin:15px 0 0;color:#777;font-family:'RobotoDraft-Medium';font-weight:normal;font-size:13px;}
    .top_dboard_src .os_sprite{width:25px;height:25px;margin-right:5px;}
    .top_dboard_src .os_sprite.task_duedt{background-position:-181px -113px;}
    .top_dboard_src .os_sprite.spent_scount{background-position:-208px -112px;}
    .top_dboard_src .os_sprite.spent_blog{background-position:-237px -110px;}
    .top_dboard_src .os_sprite.file_store{background-position:-265px -110px;}
    .top_dboard_src .os_sprite.active_usr{background-position:-293px -112px;}
    .dashboard_page .top_ttl_box{background:#6d8ba0;padding:10px 20px;border-bottom:1px solid #ddd; border-top: 3px solid #4e6d82}
    .dashboard_page .top_ttl_box h2{font-weight:normal;margin:0px;font-size:18px;color:#fff;font-family:'RobotoDraft-Medium';}
    .dashboard_page .top_ttl_box .material-icons{position:absolute;color:#727272;background:#EBEBEB;right:25px;top:12px;padding:4px;border-radius:100%;}
    .cstm_scroll{min-height: 230px;overflow-y: auto;}
    .new-dashboard-page .row.top_dboard_src .col-lg-6{padding-left:15px;padding-right:15px}
    .new-dashboard-page .row.top_dboard_src .cmn_bdr_shadow .top_ttl_box select.sel-filter{background:ffff;color: #222; margin-right:18px;
    width:auto;padding: 3px 5px; border-radius: 3px;}
     .new-dashboard-page .row.top_dboard_src .cmn_bdr_shadow .top_ttl_box .sel-filter:last-child{margin-right:0}
     .top_dboard_src .width18.mrgn-rght0{margin-right: 0px;}
     .new-dashboard-page .top_dboard_src .width100.cmn_bdr_shadow.top-divs{box-shadow:none}
     .new-dashboard-page .row.top_dboard_src .table-responsive {min-height: .01%;overflow-x: auto;}
    .new-dashboard-page .row.top_dboard_src .table {margin-bottom: 10px;width: 100%;max-width: 100%;margin-bottom: 20px;background-color: transparent;border-spacing: 0;border-collapse: collapse;}
    .new-dashboard-page .row.top_dboard_src .table > thead > tr > th {vertical-align: bottom;border-bottom: 2px solid #ebeff2;}
    .new-dashboard-page .row.top_dboard_src .table > tbody {color: #797979;}
    .new-dashboard-page .row.top_dboard_src .table > tbody > tr > td, .table > thead > tr > th {padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ebeff2;text-align: center}
    .new-dashboard-page .row.top_dboard_src .table > tbody > tr > td > .label {font-weight: 500;letter-spacing: 0.05em;padding: 0px 25px;display: inline;font-size: 75%;line-height: 1;color: #fff;text-align: center;white-space: nowrap;vertical-align: baseline;border-radius: .25em;}
    .new-dashboard-page .row.top_dboard_src .table > tbody > tr> td > .label.label-red{background:#FF0000}
    .new-dashboard-page .row.top_dboard_src .table > tbody > tr > td > .label.label-amber{background:#FFC200}
    .new-dashboard-page .row.top_dboard_src .table > tbody > tr > td > .label.label-green{background:#008000}
    .new-dashboard-page .row.top_dboard_src .table > tbody > tr > td > .label.label-grey{background:#A9A9A9}

     .top-icons{background:url(<?php echo HTTP_ROOT; ?>img/icons/dash-icons.png) no-repeat 0px 0px;display:inline-block;width:50px;height:50px;position: absolute;top:20px;left:10px}
     .top-icons.icons-proj{background-position:0px 5px}
     .top-icons.icons-user{background-position:0px -45px}
     .top-icons.icons-tsk{background-position:0px -95px}
     .top-icons.icons-tme{background-position:0px -144px}
     .top-icons.icons-file{background-position:0px -194px}
</style>
<div class="new-dashboard-page dashboard_page">
    <div class="row top-section top_dboard_src">
        <div class="col-lg-12">
            <div class="width100 cmn_bdr_shadow top-divs">
                <div  class="width18 dash-proj fl">
                    <ul>
                        <li>
                            <span class="top-icons icons-proj"></span>
                          
                        </li>
                        <li>
                            <h4 class="ellipsis-view mx-width-95" rel="tooltip" title="<?php echo $prjcnt; ?>"><span class="os_sprite task_duedt"></span><a href="<?php echo HTTP_ROOT . 'projects/manage' . $projecturl; ?>" onclick="return trackEventLeadTracker('Admin Dashboard', 'Projects', '<?php echo $_SESSION['SES_EMAIL_USER_LOGIN']; ?>');"><?php echo $prjcnt; ?></a></h4>
                            <h6>Projects</h6> 
                        </li>
                        <div class="cb"></div>
                    </ul>
                </div>
                 <div  class="width18 fl dash-actusr">
                    <ul>
                        <li>
                           <span class="top-icons icons-user"></span>
                        </li>
                        <li>
                              <h4 class="ellipsis-view mx-width-95" rel="tooltip" title="<?php echo $usrcnt; ?>"><span class="os_sprite active_usr"></span><a href="<?php echo HTTP_ROOT . 'users/manage'; ?>" onclick="return trackEventLeadTracker('Admin Dashboard', 'Active Users', '<?php echo $_SESSION['SES_EMAIL_USER_LOGIN']; ?>');"><?php echo $usrcnt; ?></a></h4>
                    <h6>Active User<?php if ($usrcnt > 1) { ?>s<?php } ?></h6>
                        </li>
                        <div class="cb"></div>
                    </ul>
                   
                </div>
                <div  class="width18 fl dash-tsks">
                    <?php
                    $closedtasks = $closedtasks != '' ? $closedtasks : 0;
                    $totaltasks = $totaltasks != '' ? $totaltasks : 0;
                    $time_filter = array(
                        'last30days' => 'Last 30 days',
                        'thisweek' => 'This Week',
                        'thismonth' => 'This Month',
                        'thisquarter' => 'This Quarter',
                        'thisyear' => 'This Year',
                        'lastweek' => 'Last Week',
                        'lastquarter' => 'Last Quarter',
                        'lastyear' => 'Last Year'
                    );
                    ?>
                    <ul>
                        <li>
                           <span class="top-icons icons-tsk"></span>
                        </li>
                        <li>
                             <h4 class="ellipsis-view mx-width-95" rel="tooltip" title="<?php echo $closedtasks . '/' . $totaltasks; ?>"><span class="os_sprite spent_blog"></span><a href="javascript:void(0);" onclick="statusTop(3);
                            return trackEventLeadTracker('Admin Dashboard', 'Closed Tasks/Total Tasks', '<?php echo $_SESSION['SES_EMAIL_USER_LOGIN']; ?>');" ><?php echo $closedtasks . '/' . $totaltasks; ?></a></h4>
                    <h6>Closed Tasks/Total Tasks</h6>
                        </li>
                        <div class="cb"></div>
                    </ul>
                   
                </div>
               
                <div  class="width18 fl dash-hrs">
                    <ul>
                        <li>
                          <span class="top-icons icons-tme"></span>
                        </li>
                        <li>
                              <h4 class="ellipsis-view mx-width-95" rel="tooltip" title="<?php echo $totalhours != '' ? $totalhours : '00 hrs & 00 mins'; ?>"><span class="os_sprite spent_scount"></span><a href="<?php echo HTTP_ROOT . 'dashboard#calendar_timelog'; ?>" onclick="return trackEventLeadTracker('Admin Dashboard', 'Spent And Still Counting', '<?php echo $_SESSION['SES_EMAIL_USER_LOGIN']; ?>');"><?php echo $totalhours != '' ? $totalhours : '00 hrs & 00 mins'; ?></a></h4>
                    <h6>Spent and still counting</h6>
                        </li>
                        <div class="cb"></div>
                    </ul>
                    
                </div>
                <div  class="width18 fr dash-fle mrgn-rght0">
                     <ul>
                        <li>
                          <span class="top-icons icons-file"></span>
                        </li>
                        <li>
                              <h4 class="ellipsis-view mx-width-95" rel="tooltip" title="<?php echo $usedspace; ?>"><span class="os_sprite file_store"></span><a href="<?php echo HTTP_ROOT . 'dashboard#files'; ?>" onclick="return trackEventLeadTracker('Admin Dashboard', 'MB Of File Storage', '<?php echo $_SESSION['SES_EMAIL_USER_LOGIN']; ?>');"><?php echo $usedspace; ?></a></h4>
                    <h6>MB of File Storage</h6>
                        </li>
                        <div class="cb"></div>
                    </ul>
                    
                </div>

                <div  class="cb"></div>
            </div>
        </div>
    </div>
    <div class="row top-section top_dboard_src">
        <div class="col-lg-12">
            <div class="width100 cmn_bdr_shadow">
                <div class="sort_li_inner width100 cmn_bdr_shadow" id="tlg_chart1">
                    <div class="top_ttl_box">
                        <h2><?php echo __('Project RAG Status'); ?></h2>
                    </div>
                    <div id="project_rag" class="dboard_cont"></div>
                    <div class="loader_dv_db" id="project_rag_ldrs" style="margin-top: 90px">
                         <center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row top-section top_dboard_src">
        <div class="col-lg-6">
            <div class="width100 cmn_bdr_shadow dash-tasklist to-dos-box">
                <div class="top_ttl_box">
                    <div class="fl"><h2><?php echo __('Project Time Sheet'); ?></h2></div>
                    <?php /* <div class="fr">
                        <select id="sel_prj_tsklst" class="sel-filter" onchange="tsk_lst_fltr();">

                            <option value="all">All</option>
                            <?php foreach ($prjct_list as $key => $value) { ?>
                                <option value="<?php echo $key; ?>" <?php if (PROJ_ID == $key) echo "selected='selected'"; ?> ><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_user_tsklst" class="sel-filter" onchange="tsk_lst_fltr();">
                            <?php foreach ($user_lst as $usrk => $val) {
                                ?>
                                <option value="<?php echo $usrk; ?>" <?php if (SES_ID == $usrk) echo "selected='selected'"; ?>><?php echo $val; ?></option>
                            <?php } ?>
                        </select>
                    </div> */ ?>
                    <div class="cb"></div>
                </div>

                <div class="htdb cstm_scroll">
                    <div class="loader_dv_db" id="project_timesheet_ldr" style="display:none;margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
                    <div class="dboard_cont" id="dash_project_timesheet"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="width100 cmn_bdr_shadow dash-activity user-dash-activity">
                <div class="top_ttl_box">
                    <div class="fl"><h2><?php echo __('Resource Time Sheet'); ?></h2></div>
                    <?php /* <div class="fr">
                        <select id="sel_prj_actvty" class="sel-filter" onchange="actvty_fltr();">

                            <option value="all">All</option>
                            <?php foreach ($prjct_list as $key => $value) { ?>
                                <option value="<?php echo $key; ?>" <?php if (PROJ_ID == $key) echo "selected='selected'"; ?> ><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_user_actvty" class="sel-filter" onchange="actvty_fltr();">
                            <?php foreach ($user_lst as $usrk => $val) {
                                ?>
                                <option value="<?php echo $usrk; ?>" <?php if (SES_ID == $usrk) echo "selected='selected'"; ?>><?php echo $val; ?></option>
                            <?php } ?>
                        </select>
                    </div> */ ?>
                    <div class="cb"></div>
                </div>
                <div class="htdb cstm_scroll">
                    <div class="loader_dv_db" id="resource_timesheet_ldr" style="display:none;margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
                    <div class="dboard_cont" id="dash_resource_timesheet"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row top-section top_dboard_src">
        <div class="col-lg-12">
            <!-- Start of Timelog chart -->
            <div class="sort_li_inner width100 cmn_bdr_shadow" id="tlg_chart1">
                <div class="top_ttl_box">
                    <div class="fl"><h2><?php echo __('Time log'); ?></h2></div>
                    <div class="fr">
                        <select id="sel_prj_chart1" class="sel-filter" onchange="prj_change_chart1(this);">

                            <option value="all">All</option>
                            <?php foreach ($prjct_list as $key => $value) { ?>
                                <option value="<?php echo $key; ?>" <?php if (PROJ_ID == $key) echo "selected='selected'"; ?> ><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_user_chart1" class="sel-filter" onchange="usr_change_chart1(this);">
                            <?php if (SES_TYPE < 3) { ?>
                                <option value="all">All</option>
                                <?php
                            }
                            foreach ($user_lst as $usrk => $val) {
                                ?>
                                <option value="<?php echo $usrk; ?>" <?php if (SES_ID == $usrk) echo "selected='selected'"; ?>><?php echo $val; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_time_chart1" class="sel-filter" onchange="time_change_chart1(this);">

                            <?php foreach ($time_filter as $fltk => $fltv) { ?>
                                <option value="<?php echo $fltk; ?>"><?php echo $fltv; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="cb"></div>
                </div>
                <div id="timelog_chart1" class="dboard_cont"></div>
                <div class="loader_dv_db" id="timelog_graph_ldrs1" style="margin-top: 90px"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
            </div>
        </div>
    </div>
    <div class="row top-section top_dboard_src">
        <div class="col-lg-6">
            <div class="sort_li_inner cmn_bdr_shadow" id="tlg_chart2">
                <div class="top_ttl_box">
                    <div class="fl"><h2><?php echo __('Time Log per Project'); ?></h2></div>
                    <div class="fr">
                        <select id="sel_prj_chart2" class="sel-filter" onchange="prj_change_chart2(this);">

                            <option value="all">All</option>
                            <?php foreach ($prjct_list as $key => $value) { ?>
                                <option value="<?php echo $key; ?>" <?php if (PROJ_ID == $key) echo "selected='selected'"; ?> ><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_time_chart2" class="sel-filter" onchange="time_change_chart2(this);">
                            <?php foreach ($time_filter as $fltk => $fltv) { ?>
                                <option value="<?php echo $fltk; ?>"><?php echo $fltv; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="cb"></div>
                </div>
                <div id="timelog_chart2" class="dboard_cont"></div>
                <div class="loader_dv_db" id="timelog_graph_ldrs2" style="margin-top: 90px"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class=" sort_li_inner cmn_bdr_shadow" id="tlg_chart3">
                <div class="top_ttl_box">
                    <div class="fl"><h2><?php echo __('Time Log per Resource'); ?></h2></div>
                    <div class="fr">
                        <select id="sel_user_chart3" class="sel-filter" onchange="usr_change_chart3(this);">
                            <?php if (SES_TYPE < 3) { ?>
                                <option value="all">All</option>
                                <?php
                            }
                            foreach ($user_lst as $usrk => $val) {
                                ?>
                                <option value="<?php echo $usrk; ?>" <?php if (SES_ID == $usrk) echo "selected='selected'"; ?>><?php echo $val; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_time_chart3" class="sel-filter" onchange="time_change_chart3(this);">

                            <?php foreach ($time_filter as $fltk => $fltv) { ?>
                                <option value="<?php echo $fltk; ?>"><?php echo $fltv; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="cb"></div>
                </div>
                <div id="timelog_chart3" class="dboard_cont"></div>
                <div class="loader_dv_db" id="timelog_graph_ldrs3" style="margin-top: 90px"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
            </div>
        </div>
    </div>
    <div class="row top-section top_dboard_src">
        <div class="col-lg-12">
            <div class="sort_li_inner width100 cmn_bdr_shadow" id="star_charts">
                <div class="top_ttl_box">
                    <div class="fl"><h2><?php echo __('Tasks per Resource'); ?></h2></div>
                    <div class="fr">

                        <select id="star_time_chart" class="sel-filter" onchange="time_change_star(this);">

                            <?php foreach ($time_filter as $fltk => $fltv) { ?>
                                <option value="<?php echo $fltk; ?>"><?php echo $fltv; ?></option>
                            <?php } ?>
                        </select> 
                    </div>
                    <div class="cb"></div>
                </div>
                <div id="star_chart" class="dboard_cont"></div>
                <div class="loader_dv_db" id="star_graph_ldrs" style="margin-top: 90px"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
            </div>
        </div>
    </div>
    <div class="row top-section top_dboard_src">
        <div class="col-lg-6">
            <div class="width100 cmn_bdr_shadow dash-tasklist to-dos-box">
                <div class="top_ttl_box">
                    <div class="fl"><h2><?php echo __('Task List'); ?></h2></div>
                    <div class="fr">
                        <select id="sel_prj_tsklst" class="sel-filter" onchange="tsk_lst_fltr();">

                            <option value="all">All</option>
                            <?php foreach ($prjct_list as $key => $value) { ?>
                                <option value="<?php echo $key; ?>" <?php if (PROJ_ID == $key) echo "selected='selected'"; ?> ><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_user_tsklst" class="sel-filter" onchange="tsk_lst_fltr();">
                            <?php foreach ($user_lst as $usrk => $val) {
                                ?>
                                <option value="<?php echo $usrk; ?>" <?php if (SES_ID == $usrk) echo "selected='selected'"; ?>><?php echo $val; ?></option>
                            <?php } ?>
                        </select>
                        <?php /*
                          <select id="sel_time_tsklst" style="color: #5191BD;background: #FFF;width: 140px;border: 1px solid #999;margin-top:7px" onchange="time_change_chart1(this);">

                          <?php foreach ($time_filter as $fltk => $fltv) { ?>
                          <option value="<?php echo $fltk; ?>"><?php echo $fltv; ?></option>
                          <?php } ?>
                          </select> */ ?>
                    </div>
                    <div class="cb"></div>
                </div>

                <div class="htdb cstm_scroll">
                    <div class="loader_dv_db" id="tasklst_ldr" style="display:none;margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
                    <div class="dboard_cont" id="dash_tsk_lst"></div>
                </div>
                <div class="fr moredb" id="more_dash_tsk_lst"><a href="javascript:void(0);" onclick="showTasks('my');">View All <span id="todos_cnt" style="display:none;">(0)</span></a></div>

            </div>
        </div>
        <div class="col-lg-6">
            <div class="width100 cmn_bdr_shadow dash-activity user-dash-activity">
                <div class="top_ttl_box">
                    <div class="fl"><h2><?php echo __('Activities'); ?></h2></div>
                    <div class="fr">
                        <select id="sel_prj_actvty" class="sel-filter" onchange="actvty_fltr();">

                            <option value="all">All</option>
                            <?php foreach ($prjct_list as $key => $value) { ?>
                                <option value="<?php echo $key; ?>" <?php if (PROJ_ID == $key) echo "selected='selected'"; ?> ><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_user_actvty" class="sel-filter" onchange="actvty_fltr();">
                            <?php foreach ($user_lst as $usrk => $val) {
                                ?>
                                <option value="<?php echo $usrk; ?>" <?php if (SES_ID == $usrk) echo "selected='selected'"; ?>><?php echo $val; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="cb"></div>
                </div>
                <div class="htdb cstm_scroll">
                    <div class="loader_dv_db" id="recent_activities_ldr" style="display:none;margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
                    <div class="dboard_cont" id="dash_recent_activities"></div>
                </div>
                <div class="fr moredb" id="more_recent_activities"><a href="javascript:void(0);" onclick="showTasks('activities');">View All <span id="todos_cnt" style="display:none;">(0)</span></a></div>

                <div class="custom-flow">
                    <div id="new_recent_activities" class="dash-activity-cont mtop10"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row top-section top_dboard_src">
        <div class="col-lg-6">
            <div class="cmn_bdr_shadow user-dash-summary">
                <div class="top_ttl_box">
                    <div class="fl">
                        <h2><?php echo __('Task Status');?></h2>
                    </div>
                    <div class="fr">
                        <select id="sel_prj_tsk_sts" class="sel-filter" onchange="status_fltr();">
                            <?php foreach ($prjct_list as $key => $value) { ?>
                                <option value="<?php echo $key; ?>" <?php if (PROJ_ID == $key) echo "selected='selected'"; ?> ><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_user_tsk_sts" class="sel-filter" onchange="status_fltr();">
                              <?php  foreach ($user_lst as $usrk => $val) {
                                ?>
                                <option value="<?php echo $usrk; ?>" <?php if (SES_ID == $usrk) echo "selected='selected'"; ?>><?php echo $val; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="cb"></div>
                </div>
                <div id="task_status_pie" class="dboard_cont dash-tasktype-graph">
                    <div class="loader_dv_db" id="task_status_pie_ldr" style="margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="cmn_bdr_shadow user-taskype-box">
                <div class="top_ttl_box">
                    <div class="fl">
                        <h2><?php echo __('Task Type');?></h2>
                    </div>
                    <div class="fr">
                        <select id="sel_prj_tsk_typ" class="sel-filter" onchange="tsk_typ_fltr();">

                            <option value="all">All</option>
                            <?php foreach ($prjct_list as $key => $value) { ?>
                                <option value="<?php echo $key; ?>" <?php if (PROJ_ID == $key) echo "selected='selected'"; ?> ><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_user_tsk_typ" class="sel-filter" onchange="tsk_typ_fltr();">
                              <?php  foreach ($user_lst as $usrk => $val) {
                                ?>
                                <option value="<?php echo $usrk; ?>" <?php if (SES_ID == $usrk) echo "selected='selected'"; ?>><?php echo $val; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                     <div class="cb"></div>
                </div>
                <div id="task_type_pie" class="dboard_cont dash-tasktype-graph">
                    <div class="loader_dv_db" id="task_type_pie_ldr" style="margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
                </div>
            </div>
        </div>
    </div>


</div>
</div>
</div>
<script type="text/javascript">
    var DASHBOARD_ORDER = <?php echo json_encode($GLOBALS['NEW_DASHBOARD']); ?>;

    $(document).ready(function () {
        
        $('#project_rag').load('<?php echo HTTP_ROOT;?>Dashboard/dashboards/project_rag',{}, function(res){
            $('#project_rag_ldrs').hide();
        });
        $('#dash_project_timesheet').load('<?php echo HTTP_ROOT;?>Dashboard/dashboards/project_timesheet',{}, function(res){
            $('#project_timesheet_ldr').hide();
        });
        $('#dash_resource_timesheet').load('<?php echo HTTP_ROOT;?>Dashboard/dashboards/resource_timesheet',{}, function(res){
            $('#resource_timesheet_ldr').hide();
        });
        
        var prjchrt1 = $('#sel_prj_chart1').val();
        var usrchrt1 = $('#sel_user_chart1').val();
        var timechrt1 = $('#sel_time_chart1').val();
        $('#timelog_chart1').load(HTTP_ROOT + 'Dashboard/dashboards/timelog_chart1', {'prjct_id': prjchrt1, 'user_id': usrchrt1, 'time_flt': timechrt1}, function (res) {
            $('#timelog_graph_ldrs1').hide();
        });
        var prjchrt2 = $('#sel_prj_chart2').val();
        var timechrt2 = $('#sel_time_chart2').val();
        $('#timelog_chart2').load(HTTP_ROOT + 'Dashboard/dashboards/timelog_resource_chart', {'prjct_id': prjchrt2, 'time_flt': timechrt2}, function (res) {
            $('#timelog_graph_ldrs2').hide();
        });
        var usrchrt3 = $('#sel_user_chart3').val();
        var timechrt3 = $('#sel_time_chart3').val();
        $('#timelog_chart3').load(HTTP_ROOT + 'Dashboard/dashboards/timelog_project_chart', {'user_id': usrchrt3, 'time_flt': timechrt3}, function (res) {
            $('#timelog_graph_ldrs3').hide();
        });
        var strtimechrt3 = $('#star_time_chart').val();
        $('#star_chart').load(HTTP_ROOT + 'Dashboard/dashboards/star_project_chart', {'time_flt': strtimechrt3}, function (res) {
            $('#star_graph_ldrs').hide();
        });
        var tsk_prjflt = $('#sel_prj_tsklst').val();
        //var tsk_tmeflt =$('#sel_time_tsklst').val();
        var tsk_usrflt = $('#sel_user_tsklst').val();

        $('#dash_tsk_lst').load(HTTP_ROOT + 'Dashboard/dashboards/task_list', {'prj_id': tsk_prjflt, 'user_fltid': tsk_usrflt}, function (res) {
            $('#tasklst_ldrs').hide();
        });
        // var tsk_tmeflt =$('#sel_time_actvty').val();
        var act_usrflt = $('#sel_user_actvty').val();
        var act_prjflt = $('#sel_prj_actvty').val();
        $('#dash_recent_activities').load(HTTP_ROOT + 'Dashboard/dashboards/dash_recent_activities', {'prj_id': act_prjflt, 'user_fltid': act_usrflt}, function (res) {
            $('#recent_activities_ldr').hide();
        });
        var tsk_typ_usrflt =$('#sel_user_tsk_typ').val();
        var tsk_typ_prjflt =$('#sel_prj_tsk_typ').val();
        $('#task_type_pie').load(HTTP_ROOT+'Dashboard/dashboards/dash_task_type',{'projid':tsk_typ_prjflt,'user_fltid':tsk_typ_usrflt},function(res){
            $('#task_type_pie_ldr').hide();
        });
        var tsk_typ_usrflt =$('#sel_user_tsk_typ').val();
        var tsk_typ_prjflt =$('#sel_prj_tsk_typ').val();
        $('#task_type_pie').load(HTTP_ROOT+'Dashboard/dashboards/dash_task_type',{'projid':tsk_typ_prjflt,'user_fltid':tsk_typ_usrflt},function(res){
            $('#task_type_pie_ldr').hide();
        });
        var tsk_typ_usrflt =$('#sel_user_tsk_sts').val();
        var tsk_typ_prjflt =$('#sel_prj_tsk_sts').val();
        $('#task_status_pie').load(HTTP_ROOT+'Dashboard/dashboards/dash_task_status',{'projid':tsk_typ_prjflt,'user_fltid':tsk_typ_usrflt},function(res){
            $('#task_status_pie_ldr').hide();
        });
        $('[rel=tooltip]').tipsy({gravity: 's', fade: true});
    });
</script>