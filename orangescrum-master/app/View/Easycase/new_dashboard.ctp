<style type="text/css">
    .new-dashboard-page{padding:10px;background:#fff;}
    .new-dashboard-page .row.top_dboard_src{margin:0px}
    .cmn_bdr_shadow{box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);-moz-box-shadow:0 1px 3px  rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);-webkit-box-shadow:0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);background:#fff;margin-bottom: 20px}
    .dashboard_page h2{margin:0px 0px 20px 0px;font-size:20px;}
    .width18{width:19%}
    .top_dboard_src .width18, .top_dboard_src .width25{padding:20px 5px;border:1px solid #D6D6D6;text-align:center;x-shadow:0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);-moz-box-shadow:0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);-webkit-box-shadow:0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);}
    .top_dboard_src .width18{padding:0 5px 0 0;color:#fff;height:75px;margin-right:1.2%;box-sizing:border-box}
    .top_dboard_src .width18.dash-proj ul li:first-child{background:#27a9e3}
    .top_dboard_src .width18.dash-tsks ul li:first-child{background:#28b779}
    .top_dboard_src .width18.dash-actusr ul li:first-child{background:#ffb848}
    .top_dboard_src .width18.dash-hrs ul li:first-child{background:#f74d4d}
    .top_dboard_src .width18.dash-fle ul li:first-child{background:#2255a4}
    .top_dboard_src .width18 ul li:first-child:hover{background:#253650}
    .top_dboard_src .width18 ul{list-style-type: none;margin:0;padding:0;height:100%}
    .top_dboard_src .width18 ul li{display:inline-block;height:100%}
    .top_dboard_src .width18 ul li:first-child{float:left;position:relative;width:30%;box-sizing:border-box;}
    .top_dboard_src .width18 ul li:nth-child(2){float:right;width:70%;text-align:center;padding:5px;box-sizing:border-box;}
    .top_dboard_src .width18 ul li img{max-width:100%;width:70px;height:70px;position:absolute;top:0;left:0;right:0;bottom:0;margin:auto;display:inline-block}
    .top_dboard_src .width18 ul li h4{margin:0;max-width:100%}
    /*.top_dboard_src .width18 ul li h4 a, .top_dboard_src .width18 ul li h6{color:#fff}*/
    .top_dboard_src .width18 ul li h4 a{font-size:18px}
    .top_dboard_src .width18 ul li h6{ font-size:12px; margin: 5px 0 0;}
    .top_dboard_src .width18:last-child, .top_dboard_src .width25:last-child{border-right:0px;}
    .top_dboard_src .width18 h4, .top_dboard_src .width25 h4{margin:0px;color:#333;font-weight:bold;font-size:20px;}
    .top_dboard_src .width18 h4 span, .top_dboard_src .width25 h4 span{font-size:15px;}
    .top_dboard_src .width18 h6, .top_dboard_src .width25 h6{margin:15px 0 0;color:#777;font-weight:normal;font-size:13px;}
    .top_dboard_src .os_sprite{width:25px;height:25px;margin-right:5px;}
    .top_dboard_src .os_sprite.task_duedt{background-position:-181px -113px;}
    .top_dboard_src .os_sprite.spent_scount{background-position:-208px -112px;}
    .top_dboard_src .os_sprite.spent_blog{background-position:-237px -110px;}
    .top_dboard_src .os_sprite.file_store{background-position:-265px -110px;}
    .top_dboard_src .os_sprite.active_usr{background-position:-293px -112px;}
    .dashboard_page .top_ttl_box{background-color:#F1F3F0;padding:8px 20px;border-bottom:1px solid #b7b7b7;height:50px;position:relative;}
    .dashboard_page .top_ttl_box h2{font-weight:600;margin:0px;font-size:14px;color:#63748E;line-height:20px;margin-top:5px}
    .dashboard_page .top_ttl_box .material-icons{position:absolute;color:#727272;background:#EBEBEB;right:25px;top:12px;padding:4px;border-radius:100%;}
    .cstm_scroll{min-height: 230px;overflow-y: auto;}
    .new-dashboard-page .row.top_dboard_src .col-lg-6{padding-left:15px;padding-right:15px}
    .new-dashboard-page .row.top_dboard_src .cmn_bdr_shadow .top_ttl_box select.sel-filter{border:1px solid #999; border-radius:5px;color:#484848; margin-right:30px;padding:2px 5px;width:115px;overflow:hidden;text-overflow:ellipsis;right:0}
    .new-dashboard-page .row.top_dboard_src .cmn_bdr_shadow .top_ttl_box .sel-filter:last-child{margin-right:0}
    .top_dboard_src .width18.mrgn-rght0{margin-right: 0px;}
    .new-dashboard-page .top_dboard_src .width100.cmn_bdr_shadow.top-divs{box-shadow:none}
    .new-dashboard-page .row.top_dboard_src .table-responsive {min-height: .01%;overflow-x: auto;}
    .new-dashboard-page .row.top_dboard_src .table-responsive.prjct-rag-tbl {max-height:550px;overflow-x: auto; overflow-y: auto;height: 100%;}
    @-moz-document url-prefix() {
        .new-dashboard-page .row.top_dboard_src .table-responsive.prjct-rag-tbl{
            max-height:580px
        }
    }
    .new-dashboard-page .row.top_dboard_src .table {margin-bottom: 10px;width: 100%;max-width: 100%;background-color: transparent;border-spacing: 0;border-collapse: collapse;}
    .new-dashboard-page .row.top_dboard_src .table > thead > tr > th {vertical-align: bottom;/*border-bottom: 2px solid #ebeff2;*/color: #484848;
                                                                      font-weight: 500}

    .new-dashboard-page .row.top_dboard_src .table > tbody {color: #212121/*#797979*/;}
    .new-dashboard-page .row.top_dboard_src .table > tbody > tr > td, .table > thead > tr > th {padding: 8px;line-height: 1.42857143;vertical-align: top;border-bottom: 1px solid #ebeff2;text-align: left}
    .new-dashboard-page .row.top_dboard_src .table > tbody > tr > td > .label {font-weight: 500;letter-spacing: 0.05em;padding:14px;display: inline-block;font-size: 75%;line-height: 1;color: #fff;text-align: center;white-space: nowrap;vertical-align: baseline;border-radius: 50%;}
    .new-dashboard-page .row.top_dboard_src .table > tbody > tr> td > .label.label-red{background:#FF0000}
    .new-dashboard-page .row.top_dboard_src .table > tbody > tr > td > .label.label-amber{background:#FFC200}
    .new-dashboard-page .row.top_dboard_src .table > tbody > tr > td > .label.label-green{background:#008000}
    .new-dashboard-page .row.top_dboard_src .table > tbody > tr > td > .label.label-grey{background:#A9A9A9}
    .new-dashboard-page .row .table > tbody > tr:last-child > td{border:none;}
    .table-hover tbody tr:hover {-moz-box-shadow: 3px 0px 3px 0px #ccc;-webkit-box-shadow: 3px 0px 3px 0px #ccc;box-shadow:3px 0px 3px 0px #ccc;}

    .new-dashboard-page td{color: #8f8f8f;font-size: 13px;  font-weight: 500;}
    .top-icons{background:url(<?php echo HTTP_ROOT; ?>img/icons/dash-icons.png) no-repeat 0px 0px;display:inline-block;width:42px;height:50px;position:absolute;top:0;left:0;right:0;bottom:0;margin:auto;}
    .top-icons.icons-proj{background-position:0px 5px}
    .top-icons.icons-user{background-position:0px -45px}
    .top-icons.icons-tsk{background-position:0px -95px}
    .top-icons.icons-tme{background-position:0px -141px}
    .top-icons.icons-file{background-position:0px -191px}


    #project_rag.fullscreen ,#timelog_chart1.fullscreen ,#dash_project_timesheet.fullscreen ,#dash_resource_timesheet.fullscreen,#timelog_tsktyp.fullscreen,#hlp_cntnt_div.fullscreen,#dash_cost_report.fullscreen,#dash_resource_cost_report.fullscreen{z-index: 9999;width: 100%;height: 100%;position: fixed;top: 0;left: 0;}
    #timelog_chart1,#project_rag,#dash_project_timesheet,#dash_resource_timesheet,#timelog_tsktyp,#hlp_cntnt_div,#dash_cost_report,#dash_resource_cost_report{background:#fff;}
    /*  #exprt_rag,#bck_rag,#bck_prjtsht,#bck_rcrstsht{display:none;margin-bottom:20px} */
    .hide_buttn{display:none;margin-bottom:20px}
    .hide_td{display:none;}
    .hide_td.vscntnt{text-align: center;}
    #tmlog_bck{display:none;margin-bottom:20px}
    .expand_icon{margin:0;display:inline-block;position:absolute;right:20px;top:14px;}
    /* #expand_tlg{display:none} */
    .tsklst_label{ /*border-radius: 9px;*/ color: rgb(255, 255, 255); display: inline-block;line-height: 14px; padding: 2px 4px; text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.25); vertical-align: baseline; white-space: nowrap; font-size: 10.844px;text-align:center;width:75px}
    .tsklst_label.ovrdueby{background:#f74d4d}
    .tsklst_label.crtdate{background:#3a87ad}
    .tsklst_label.upcomngin{background:#51a351}
    .nav-tabs{width:100%;border:none;border-bottom:2px solid #ccc}
    #dash_tsk_lst .nav-tabs li{ padding: inherit;margin-bottom:0;height: inherit;border:none;background:none;position:relative;transition:all 0.8s ease-in-out;-webkit-transition:all 0.8s ease-in-out;-moz-transition:all 0.8s ease-in-out;}
    /*     .nav-tabs > li > a{border-radius:0px;}*/
    #dash_tsk_lst .nav-tabs li:hover:before, .nav-tabs>li.active:before{content:'';border-bottom:2px solid #27a9e3;position:absolute;bottom:-2px;left:0;width:100%;display:block;background:none}
    /*#dash_tsk_lst .nav-tabs li:not(.active){border:none;border-bottom: 1px solid #ccc;}*/
    #dash_tsk_lst .nav-tabs>li>a{border:none;background:none;}
    #dash_tsk_lst .nav-tabs>li>a:hover{color:#000;}
    /* #dash_tsk_lst .nav-tabs li:hover,.nav-tabs>li.active{background:#FFCC33}*/
    /* #dash_tsk_lst .nav-tabs li:hover a,.nav-tabs>li.active a{background:#FFCC33;color:#fff;font-weight:bold} */
    #ovrdue .listdv,#upcmng_tsks .listdv{margin-top:10px}
    /*#dash_tsk_lst li.active {border-top: 1px solid #ff9600;
        border-left: 1px solid #ccc;
        border-right: 1px solid #ccc;
    }
    #dash_tsk_lst .nav-tabs{border:none}*/
    /*.top_dboard_src,.top_dboard_src .dboard_cont,.top_dboard_src .task_cre_rec_db,.top_dboard_src .time_rec_db{font-family:'Lucida Sans Unicode';}*/
    #prj_rag{height:390px}
    .to-dos-box .htdb.cstm_scroll{height:342px}
    #dash_recent_activities .act_title_db{padding-top:1px}
    #dash_recent_activities .task_cre_rec_db{padding-left:5px;padding-top: 1px;}
    .top_dboard_src .dboard_cont{padding-top:5px}
    #timelog_table,#tasktype_table{display:none;margin-top:20px}
    /*table.tbody_scroll{width:100%}
    table.tbody_scroll tbody,
    table.tbody_scroll thead { display: block; }
    table.tbody_scroll tbody {
        height: 100px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    .tbody_scroll tbody td:last-child, thead th:last-child {
        border-right: none;
    }
    .tbody_scroll tbody td, .tbody_scroll thead th {
        width: 16%;  Optional 
        border-right: 1px solid black;
    }*/


    .tbody_scroll tbody {
        display:block;
        height:300px;
        overflow:auto;
    }
    .tbody_scroll thead,.tbody_scroll tbody tr {
        display:table;
        width:100%;
        table-layout:fixed;
    }
    .tbody_scroll thead {
        width: calc( 100% - 1em )
    }
    #hlp_cntnt_div{background: rgba(4, 7, 8, 0.75);}
    .hlp_div{display:none;width:80%;margin:0 auto;}
    .hlp_inner{width:80%;margin:20px auto 0;border:none;
               padding:0px 0 20px;background:#fff;border-radius: 10px;box-shadow:inset 0px 0px 30px #94A3A7;}
    #hlp_cntnt_div h2{font-size:20px;margin: 0 0 15px; background:rgb(249, 174, 5);padding:20px;color:#fff;border-radius:10px 10px 0 0;}
    .hlp_cntnt{height:540px;overflow-y: scroll;width: 96%; margin: 0 auto;padding:0px}
    #hlp_cntnt_div strong{display: block;font-size:14px;color:#303030;margin: 15px 0 10px 0;}
    #hlp_cntnt_div p{font-size:12px;line-height:28px;margin: 0 0 10px;color:#303030}
    #hlp_cntnt_div .close_popup{background: rgba(0, 0, 0, 0) url("<?php echo HTTP_ROOT; ?>img/cross-symbol.png") no-repeat scroll 0 0;position: relative;top: 16px;height:16px;width:16px}
    .mytask_txt{width:100%}
    .recent-activity-new .htdb {height:427px}
    /* #star_chart .highcharts-container{height:450px !important;} */
</style>
<style media="print" >
    @media print {
        .exec_dash_icon{display:none !important}
        .expand_icon,.breadcrumb_div,.navbar-fixed-top,#footersection{display:none !important}

        .top_dboard_src .width18{box-sizing:border-box;margin-right:10px;}
        .top_dboard_src .width18 ul li{-webkit-print-color-adjust: exact;}
        .top_dboard_src .width18.dash-proj ul li:first-child {background: #27a9e3 !important;}
        .top_dboard_src .width18.dash-actusr ul li:first-child {background: #ffb848 !important;}
        .top_dboard_src .width18.dash-tsks ul li:first-child {background: #28b779 !important;}
        .top_dboard_src .width18.dash-hrs ul li:first-child {background: #f74d4d !important;}
        .top_dboard_src .width18.dash-fle ul li:first-child {background: #2255a4 !important;}
        .top-icons{left:3px;}
        .new-dashboard-page .row.top_dboard_src .table > tbody > tr> td > span{
            -webkit-print-color-adjust: exact;}
        .new-dashboard-page .row.top_dboard_src .table > tbody > tr > td > .label.label-grey {
            background: #A9A9A9 !important;}
        .new-dashboard-page .row.top_dboard_src .table > tbody > tr> td > .label.label-red {
            background: #FF0000 !important;}
        .new-dashboard-page .row.top_dboard_src .table > tbody > tr > td > .label.label-green {
            background: #008000 !important;}
        .cstm_scroll{display:block;width:auto;height:auto;overflow:visible;}
        .top_dboard_src .col-lg-6{widh:100% !important}
    }


</style>

<div id="hlp_cntnt_div" class="hlp_div">
    <div class="hlp_inner">
        <div><a href="javascript:jsVoid();" onclick="showHelpContent();"><div class="fr close_popup"><?php echo __("Close"); ?></div></a></div>
        <h2><?php echo __("Executive Dashboard Help Content"); ?></h2>
        <div class="hlp_cntnt">
            <strong><?php echo __("RAG Status"); ?> – <?php echo __("Time and Cost");?>:</strong>
                <p><?php echo __("Red Amber Green status indicates the project health in terms of time and cost"); ?>.</p>
                <p><b><?php echo __("RAG Time Status"); ?></b> - <?php echo __("The status on any given day is calculated by dividing the estimated hours / total actual spent hours as recorded against tasks that were worked upon"); ?>.</p>
                <p><?php echo __("Project Start Date"); ?><br/>
                    <?php echo __("Project Estimated Hours"); ?><br/>
                   <?php echo __(" Project Duration is calculated based on the estimated hours, work hours per day (Project Estimated hour/Project Duration) and the start date"); ?>.
                </p>
                <p><?php echo __("Also in the Project details page you can define the <b>minimum and maximum allowed deviation</b>. Once defined the Red, Amber and Green statuses are calculated accordingly.  This also applies for the RAG Cost Status"); ?>.</p>
                <p><b><?php echo __("RAG Cost Status"); ?></b> – <?php echo __("is derived based on the following parameters"); ?></p>
                <p style="text-align:center"> <b><?php echo __("Rate per Day");?> – <?php echo __("Total Cost");?>*/<?php echo __("Project Duration"); ?></b></p>
                <p><b>*</b><?php echo __("Total cost is taken from the Budget or the Cost Approved field. Where the Cost Approved field is empty the Budget is taken as the total cost of the project"); ?>.</p>
                <p style="text-align:center"><b><?php echo __("Rate per Hour");?> – <?php echo __("Rate per day");?>/ <?php echo __("Work hour per day");?> (<?php echo __("Project Estimated hour/Project Duration"); ?>)</b></p>
                <p><?php echo __("So");?>, <?php echo __("for any given day, the RAG Cost Status becomes"); ?> –</p>
                <p style="text-align:center"><b><?php echo __("Rate per hour");?> * <?php echo __("Actual spent hours as of today"); ?></b></p>
                <p><?php echo __("Here too, the Red, Amber, Green is accorded based on the");?> <b>“<?php echo __("minimum and maximum allowed deviation"); ?>”</b> <?php echo __("as defined in the project details page during project creation"); ?>.</p>
            <strong><?php echo __("TO DO List"); ?>:</strong>
                <p><?php echo __("Provides a quick view of Overdue and Upcoming tasks with interactive Project(s) and Resource(s) filters to choose from for real time task progress checks"); ?>.</p>
            <strong><?php echo __("Time Log"); ?>:</strong>
                <p><?php echo __("Line Chart depicts the billable, non-billable and total hours spent by the team on various projects. Project, Resource and an array of Time Range filters are provided for you to easily focus on project(s) or resource(s) that interest(s) you"); ?>.</p>
                <p><?php echo __("Full Screen view shows you the data in a tabular format along with the graph. CSV export also made available based on your applied filters so that you ONLY have the data you need"); ?>.</p>
            <strong><?php echo __("Weekly Task Progress By Project") ;?> :</strong>
                <p><?php echo __("This table gives a date wise daily view of the task progress on specific projects for the past week. You can easily see how tasks progressed each day for a particular project. It also shows the time spent on the tasks for the specific project on a daily basis"); ?>.</p>
                <p>
                      <?php echo __("Total Tasks"); ?> – <?php echo __("Count of all tasks in the project"); ?> <br/>
                     <?php echo __("In Progress"); ?> – <?php echo __("Count of tasks worked on the specific date as shown in the table"); ?><br/>
                     <?php echo __("Completed"); ?> – <?php echo __("Count of tasks completed on the specific date as shown in the table"); ?> <br/>
                  <?php echo __("Hours Spent"); ?> – <?php echo __("Total time spent by resources on tasks on this date"); ?> 
                </p>
            <strong><?php echo __("Weekly Task Progress By Resource"); ?> :</strong>
                <p><?php echo __("This table gives a date wise daily view of the task progress on specific projects by resources for the past week. You can easily see which resource spent how many hours on how many tasks each day on a particular project. A strategic progress card of task progress made by a user"); ?>. </p>
                 <p>
                      <?php echo __("Total Tasks"); ?>– <?php echo __("Count of all tasks in the project"); ?> <br/>
                    <?php echo __(" In Progress");?> – <?php echo __("Count of tasks worked on the specific date as shown in the table"); ?><br/>
                   <?php echo __("Completed"); ?> – <?php echo __("Count of tasks completed on the specific date as shown in the table"); ?> <br/>
                  <?php echo __("Hours Spent");?> – <?php echo __("Total time spent by the specific resource on assigned tasks on this date"); ?>
                </p>
            <strong><?php echo __("Project Time Log"); ?>:</strong>
                <p><?php echo __("Simple bar graph shows the Total time <b>(Billable + Non-Billable neatly stacked)</b> spent by EACH user of a project for a specific time period as selected from the Time Range filter"); ?>.</p>
                <p><?php echo __("You easily identify how resources are stacked on your chosen project and decide if you would like a specific resource to spend more time on this project"); ?>.</p>
                
            <strong><?php echo __("Resource Time Log"); ?>:</strong>
                <p><?php echo __("This straight forward graph shows the Total time (Billable + Non-Billable) spent by the specific user across ALL his/her projects for the selected Time Range"); ?>.</p>
                <p><?php echo __("You instantly see which project consumes a specific resource");?>’<?php echo __("s maximum time and at the same time which projects need to be revisited for resource allocation"); ?>.</p>
               
            <strong><?php echo __("Activities"); ?>:</strong>
                <p><?php echo __("As the name suggests, this box provides you with real time feed of activities by your teams in real time. Project and Resource filters have been provided for easy and quick reference on projects and resources that interest you"); ?>.</p>
                <p><?php echo __("This way you instantly know who is doing what, when and where and saves from needless chasing"); ?>.</p>
            <strong><?php echo __("Task Per Resource"); ?>:</strong>
                <p><?php echo __("Healthy Competition!!! This chart gives a quick snapshot of which user has been completing more tasks. Just a count comparison among users and their individual progress. The chart shows you the total tasks assigned to the user, count of tasks in progress and count of tasks closed by the user for a specific time range"); ?>.</p>
            <strong><?php echo __("Time Spent by Task Type"); ?>:</strong>
                <p><?php echo __("This intelligent graph shows the Task Types that consume the bulk of your team");?>’<?php echo("s time. It also shows how much of this time is billable and non-billable. Project, Resource and Time Range filters are provided for you to drill down specifically not only on task types, but the projects and resources as well. You are now equipped to identify any abnormal behaviour, process leaks or resource-skill misfit issues"); ?>.</p>
            <strong><?php echo __("Task Status"); ?>:</strong>
                <p><?php echo __("This pie-chart is a real time reflection of Task counts by Status for a given project and resource. A simple spread of task counts over various statuses"); ?>.</p>
            <strong><?php echo __("Task Type"); ?>:</strong>
                <p><?php echo __("Another pie-chart that provides the spread of various task types across your projects and resources. It quickly shows you which user works on which task types the most. A clear indication of users expertise in specific areas. Simple yet intelligent"); ?>!</p>
        </div>
     </div>
</div> 
<div class="new-dashboard-page dashboard_page" id="divToPrint_dash">
    <div class="row top-section top_dboard_src">
        <div class="col-lg-12">
            <div class="width100 cmn_bdr_shadow top-divs">
                <div  class="width18 dash-proj fl">
                    <ul>
                        <li>
                            <span class="top-icons icons-proj"></span>

                        </li>
                        <li>
                            <h4 class="ellipsis-view mx-width-95" rel="tooltip" title="<?php echo $prjcnt; ?>"><a href="<?php echo HTTP_ROOT . 'projects/manage' . $projecturl; ?>" onclick="return trackEventLeadTracker('Admin Dashboard', 'Projects', '<?php echo $_SESSION['SES_EMAIL_USER_LOGIN']; ?>');"><?php echo $prjcnt; ?></a></h4>
                            <h6><?php echo __("Projects"); ?></h6> 
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
                            <h4 class="ellipsis-view mx-width-95" rel="tooltip" title="<?php echo $usrcnt; ?>"><a <?php if(defined('ROLE') && ROLE ==1){ if(array_key_exists('View Users', $roleAccess) && $roleAccess['View Users'] == 0){}else{ ?>href="<?php echo HTTP_ROOT . 'users/manage'; ?>" <?php }} else { ?> href="<?php echo HTTP_ROOT . 'users/manage'; ?>" <?php } ?> onclick="return trackEventLeadTracker('Admin Dashboard', 'Active Users', '<?php echo $_SESSION['SES_EMAIL_USER_LOGIN']; ?>');"><?php echo $usrcnt; ?></a></h4>
                            <h6><?php echo __("Active User"); ?><?php if ($usrcnt > 1) { ?>s<?php } ?></h6>
                        </li>
                        <div class="cb"></div>
                    </ul>
                </div>
                <div  class="width18 fl dash-tsks">
                    <?php
                    $closedtasks = $closedtasks != '' ? $closedtasks : 0;
                    $totaltasks = $totaltasks != '' ? $totaltasks : 0;
                    $time_filter = array(
                        'last30days' => __('Last 30 days'),
                        'thisweek' => __('This Week'),
                        'thismonth' => __('This Month'),
                        'thisquarter' => __('This Quarter'),
                        'thisyear' => __('This Year'),
                        'lastweek' => __('Last Week'),
                        'lastquarter' => __('Last Quarter'),
                        'lastyear' => __('Last Year')
                    );
                    ?>
                    <ul>
                        <li>
                            <span class="top-icons icons-tsk"></span>
                        </li>
                        <li>
                            <h4 class="ellipsis-view mx-width-95" rel="tooltip" title="<?php echo $closedtasks . '/' . $totaltasks; ?>"><a href="<?php echo HTTP_ROOT . 'dashboard#tasks'; ?>" onclick="statusTop(3);
                                    return trackEventLeadTracker('Admin Dashboard', 'Closed Tasks/Total Tasks', '<?php echo $_SESSION['SES_EMAIL_USER_LOGIN']; ?>');" ><?php echo $closedtasks . '/' . $totaltasks; ?></a></h4>
                            <h6><?php echo __("Closed Tasks"); ?>/<?php echo __("Total Tasks"); ?></h6>
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
                            <h4 class="ellipsis-view mx-width-95" rel="tooltip" title="<?php echo $totalhours != '' ? $totalhours : '00 hrs & 00 mins'; ?>"><a href="<?php echo HTTP_ROOT . 'timelog'; ?>" onclick="return trackEventLeadTracker('Admin Dashboard', 'Spent And Still Counting', '<?php echo $_SESSION['SES_EMAIL_USER_LOGIN']; ?>');"><?php echo $totalhours != '' ? substr($totalhours, 0, strpos($totalhours, 's') + 1) : '00 hrs & 00 mins'; ?></a></h4>
                            <h6><?php echo __("Spent and counting"); ?></h6>
                        </li>
                        <div class="cb"></div>
                    </ul>
                </div>
                <div  class="width18 fl dash-fle mrgn-rght0">
                    <ul>
                        <li>
                            <span class="top-icons icons-file"></span>
                        </li>
                        <li>
                            <h4 class="ellipsis-view mx-width-95" rel="tooltip" title="<?php echo $usedspace; ?>"><a href="<?php echo HTTP_ROOT . 'dashboard#files'; ?>" onclick="return trackEventLeadTracker('Admin Dashboard', 'MB Of File Storage', '<?php echo $_SESSION['SES_EMAIL_USER_LOGIN']; ?>');"><?php echo $usedspace; ?></a></h4>
                            <h6><?php echo __("MB of File Storage"); ?></h6>
                        </li>
                        <div class="cb"></div>
                    </ul>
                </div>

                <div  class="cb"></div>
            </div>
        </div>
    </div>
    <div class="row top-section top_dboard_src">
        <div class="col-lg-6">

            <div class="sort_li_inner width100 cmn_bdr_shadow" id="prj_rag">
                <div class="top_ttl_box">
                    <div class="fl">  <h2><?php echo __('Project RAG Status'); ?></h2></div>

                    <div class="fr">
                        <span id="expand_div" class="expand_icon"><a onclick="fulscreen_div('project_rag')" href="javascript:void(0)" title="View All"><img src="<?php echo HTTP_ROOT; ?>img/switch-to-full-screen-button.png"></a></span>
                    </div>
                    <div class="cb"></div>
                </div>
                <div id="project_rag" class="dboard_cont"></div>
                <div class="loader_dv_db" id="project_rag_ldrs" style="margin-top: 90px">
                    <center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center>
                </div>
            </div>

        </div>
        <div class="col-lg-6">
            <div class="width100 cmn_bdr_shadow dash-tasklist to-dos-box">
                <div class="top_ttl_box">
                    <div class="fl"><h2><?php echo __('To Do List'); ?></h2></div>
                    <div class="fr">
                        <select id="sel_prj_tsklst" class="sel-filter" onchange="tsk_lst_fltr('project');">

                            <option value="all">All</option>
                            <?php foreach ($prjct_list as $key => $value) { ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_user_tsklst" class="sel-filter" onchange="tsk_lst_fltr('user');">
                            <?php foreach ($user_lsts as $usrk => $val) {
                                ?>
                                <option value="<?php echo $usrk; ?>" data-pid ="<?php echo $val['Projects']; ?>"><?php echo $val['Name']; ?></option>
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
                    <div class="loader_dv_db" id="tasklst_ldr" style="margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
                    <div class="dboard_cont" id="dash_tsk_lst"></div>
                </div>
                <div class="fr moredb" id="more_dash_tsk_lst"><a href="javascript:void(0);" onclick="showTasks('my');">View All <span id="todos_cnt" style="display:none;">(0)</span></a></div>

            </div>
        </div>
    </div>
    <?php if (SES_TYPE == 1 || (SES_TYPE == 2 && $GLOBALS['is_admin_allowed'] == 1)) { ?>
        <div class="row top-section top_dboard_src">
            <div class="col-lg-12">
                <div class="width100 cmn_bdr_shadow dash-activity user-dash-activity">
                    <div class="top_ttl_box">
                        <div class="fl"><h2><?php echo __('Cost Report'); ?></h2></div>
                        <div class="fr">
                            <span id="expand_divss" class="expand_icon"><a onclick="fulscreen_div('cost_rprt')" href="javascript:void(0);" title="View All"><img src="<?php echo HTTP_ROOT; ?>img/switch-to-full-screen-button.png"></a></span>
                        </div>
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
                        <div class="loader_dv_db" id="cost_report_ldr" style="margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
                        <div class="dboard_cont" id="dash_cost_report"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row top-section top_dboard_src">
            <div class="col-lg-12">
                <div class="width100 cmn_bdr_shadow dash-activity user-dash-activity">
                    <div class="top_ttl_box">
                        <div class="fl"><h2><?php echo __('Resource Cost Report'); ?></h2></div>
                        <div class="fr">
                            <select id="sel_rsrc_prj_typ" class="sel-filter" onchange="prj_change_rsrc_reprt();">

                                <?php /*   <option value="all">All</option> */ ?>
                                <?php foreach ($prjct_list as $key => $value) { ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php } ?>
                            </select>
                            <select id="sel_rsrc_time_typ" class="sel-filter" onchange="prj_change_rsrc_reprt();">
                                <?php foreach ($time_filter as $fltk => $fltv) { ?>
                                    <option value="<?php echo $fltk; ?>"><?php echo $fltv; ?></option>
                                <?php } ?>
                            </select>
                            <span id="expand_tlg" class="expand_icon"><a onclick="fulscreen_div('resource_cost')" href="javascript:void(0);" title="Full Screen View"><img src="<?php echo HTTP_ROOT; ?>img/switch-to-full-screen-button.png"></a></span>
                        </div>
                        <div class="cb"></div>
                    </div>
                    <div class="htdb cstm_scroll">
                        <div class="loader_dv_db" id="resource_cost_report_ldr" style="margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
                        <div class="dboard_cont" id="dash_resource_cost_report"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="row top-section top_dboard_src">
        <div class="col-lg-12">
            <!-- Start of Timelog chart -->
            <div class="sort_li_inner width100 cmn_bdr_shadow" id="tlg_chart1">
                <div class="top_ttl_box">
                    <div class="fl"><h2 style="margin-top:8px;"><?php echo __('Time Log'); ?></h2></div>
                    <div class="fr">

                        <select id="sel_prj_chart1" class="sel-filter" onchange="prj_change_chart1();" >

                            <option value="all">All</option>
                            <?php foreach ($prjct_list as $key => $value) { ?>
                                <option value="<?php echo $key; ?>"  ><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_user_chart1" class="sel-filter" onchange="usr_change_chart1();" >
                            <?php if (SES_TYPE < 3) { ?>
                                <option value="all">All</option>
                                <?php
                            }
                            foreach ($user_lsts as $usrk => $val) {
                                ?>
                                <option value="<?php echo $usrk; ?>"  data-pid ="<?php echo $val['Projects']; ?>"><?php echo $val['Name']; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_time_chart1" class="sel-filter" onchange="time_change_chart1();">

                            <?php foreach ($time_filter as $fltk => $fltv) { ?>
                                <option value="<?php echo $fltk; ?>"><?php echo $fltv; ?></option>
                            <?php } ?>
                        </select>
                        <span id="expand_tlg" class="expand_icon"><a onclick="fulscreen_timelog('timelog')" href="javascript:void(0);" title="Full Screen View"><img src="<?php echo HTTP_ROOT; ?>img/switch-to-full-screen-button.png"></a></span>
                    </div>
                    <div class="cb"></div>
                </div>
                <div id="timelog_chart1" class="dboard_cont"></div>
                <div class="loader_dv_db" id="timelog_graph_ldrs1" style="margin-top: 90px"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
            </div>
        </div>
    </div>
    <div class="row top-section top_dboard_src">
        <div class="col-lg-12">
            <div class="width100 cmn_bdr_shadow dash-tasklist">
                <div class="top_ttl_box">
                    <div class="fl"><h2><?php echo __('Weekly Task Progress By Project '); ?></h2></div>
                    <div class="fr">
                        <span id="expand_div" class="expand_icon"><a onclick="fulscreen_div('prj_timesheet')" href="javascript:void(0)" title="View All"><img src="<?php echo HTTP_ROOT; ?>img/switch-to-full-screen-button.png"></a></span>
                    </div>
                    <div class="cb"></div>
                </div>

                <div class="htdb cstm_scroll">
                    <div class="loader_dv_db" id="project_timesheet_ldr" style="margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
                    <div class="dboard_cont" id="dash_project_timesheet"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row top-section top_dboard_src">
        <div class="col-lg-12">
            <div class="width100 cmn_bdr_shadow dash-activity user-dash-activity">
                <div class="top_ttl_box">
                    <div class="fl"><h2><?php echo __('Weekly Task Progress By Resource'); ?></h2></div>
                    <div class="fr">
                        <span id="expand_divs" class="expand_icon"><a onclick="fulscreen_div('resource_timesheet')" href="javascript:void(0);" title="View All"><img src="<?php echo HTTP_ROOT; ?>img/switch-to-full-screen-button.png"></a></span>
                    </div>
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
                    <div class="loader_dv_db" id="resource_timesheet_ldr" style="margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
                    <div class="dboard_cont" id="dash_resource_timesheet"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row top-section top_dboard_src">
        <div class="col-lg-6">
            <div class=" sort_li_inner cmn_bdr_shadow" id="tlg_chart3">
                <div class="top_ttl_box">
                    <div class="fl"><h2><?php echo __('Resource Time Log'); ?></h2></div>
                    <div class="fr">
                        <select id="sel_user_chart3" class="sel-filter" onchange="usr_change_chart3(this);">
                            <?php if (SES_TYPE < 3) { ?>
                                <option value="all">All</option>
                                <?php
                            }
                            foreach ($user_lsts as $usrk => $val) {
                                ?>
                                <option value="<?php echo $usrk; ?>"  data-pid ="<?php echo $val['Projects']; ?>"><?php echo $val['Name']; ?></option>
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
        <div class="col-lg-6">
            <div class="width100 cmn_bdr_shadow dash-activity user-dash-activity recent-activity-new">
                <div class="top_ttl_box">
                    <div class="fl"><h2><?php echo __('Activities'); ?></h2></div>
                    <div class="fr">
                        <select id="sel_prj_actvty" class="sel-filter" onchange="actvty_fltr('project');">

                            <option value="all">All</option>
                            <?php foreach ($prjct_list as $key => $value) { ?>
                                <option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_user_actvty" class="sel-filter" onchange="actvty_fltr('user');">
                            <?php foreach ($user_lsts as $usrk => $val) {
                                ?>
                                <option value="<?php echo $usrk; ?>" data-pid ="<?php echo $val['Projects']; ?>"><?php echo $val['Name']; ?></option>
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
        <div class="col-lg-12">
            <div class="sort_li_inner cmn_bdr_shadow" id="tlg_chart2">
                <div class="top_ttl_box">
                    <div class="fl"><h2><?php echo __('Project Time Log'); ?></h2></div>
                    <div class="fr">
                        <select id="sel_prj_chart2" class="sel-filter" onchange="prj_change_chart2(this);">

                            <option value="all">All</option>
                            <?php foreach ($prjct_list as $key => $value) { ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
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
    </div>
    <div class="row top-section top_dboard_src">
        <div class="col-lg-12">
            <div class="sort_li_inner width100 cmn_bdr_shadow" id="star_charts">
                <div class="top_ttl_box">
                    <div class="fl"><h2><?php echo __('Tasks per Resource'); ?></h2></div>
                    <div class="fr">
                        <select id="sel_prj_star" class="sel-filter" onchange="time_change_star();">
                            <?php foreach ($prjct_list as $key => $value) { ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                        <select id="star_time_chart" class="sel-filter" onchange="time_change_star();">

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
        <div class="col-lg-12">
            <div class="sort_li_inner width100 cmn_bdr_shadow" id="tlg_tsktyp">
                <div class="top_ttl_box">
                    <div class="fl"><h2><?php echo __('Task Type Time Log'); ?></h2></div>
                    <div class="fr">

                        <select id="sel_prj_tsktyp" class="sel-filter" onchange="on_change_tsktyp('project');">

                            <option value="all">All</option>
                            <?php foreach ($prjct_list as $key => $value) { ?>
                                <option value="<?php echo $key; ?>"  ><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_user_tsktyp" class="sel-filter" onchange="on_change_tsktyp('user');">
                            <?php if (SES_TYPE < 3) { ?>
                                <option value="all">All</option>
                                <?php
                            }
                            foreach ($user_lsts as $usrk => $val) {
                                ?>
                                <option value="<?php echo $usrk; ?>"  data-pid ="<?php echo $val['Projects']; ?>"><?php echo $val['Name']; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_time_tsktyp" class="sel-filter" onchange="on_change_tsktyp('time');">

                            <?php foreach ($time_filter as $fltk => $fltv) { ?>
                                <option value="<?php echo $fltk; ?>"><?php echo $fltv; ?></option>
                            <?php } ?>
                        </select>
                        <span id="expand_tsktyp" class="expand_icon"><a onclick="fulscreen_timelog('dash_tasktype')" href="javascript:void(0);" title="Full Screen View"><img src="<?php echo HTTP_ROOT; ?>img/switch-to-full-screen-button.png"></a></span>
                    </div>
                    <div class="cb"></div>
                </div>
                <div id="timelog_tsktyp" class="dboard_cont"></div>
                <div class="loader_dv_db" id="timelog_graph_tsktyp" style="margin-top: 90px"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
            </div>
        </div>
    </div>

    <div class="row top-section top_dboard_src">
        <div class="col-lg-6">
            <div class="cmn_bdr_shadow user-dash-summary">
                <div class="top_ttl_box">
                    <div class="fl">
                        <h2><?php echo __('Task Status'); ?></h2>
                    </div>
                    <div class="fr">
                        <select id="sel_prj_tsk_sts" class="sel-filter" onchange="status_fltr('project');">
                            <?php foreach ($prjct_list as $key => $value) { ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_user_tsk_sts" class="sel-filter" onchange="status_fltr('user');">
                            <?php foreach ($user_lsts as $usrk => $val) {
                                ?>
                                <option value="<?php echo $usrk; ?>" data-pid ="<?php echo $val['Projects']; ?>"><?php echo $val['Name']; ?></option>
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
                        <h2><?php echo __('Task Type'); ?></h2>
                    </div>
                    <div class="fr">
                        <select id="sel_prj_tsk_typ" class="sel-filter" onchange="tsk_typ_fltr('project');">

                            <option value="all"><?php echo __("All"); ?></option>
                            <?php foreach ($prjct_list as $key => $value) { ?>
                                <option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                        <select id="sel_user_tsk_typ" class="sel-filter" onchange="tsk_typ_fltr('user');">
                            <?php foreach ($user_lsts as $usrk => $val) {
                                ?>
                                <option value="<?php echo $usrk; ?>"  data-pid ="<?php echo $val['Projects']; ?>"><?php echo $val['Name']; ?></option>
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
        status_fltr('project');
        setTimeout(function () {
            $('#project_rag').load('<?php echo HTTP_ROOT; ?>Dashboard/dashboards/project_rag', {}, function (res) {
                $('#project_rag_ldrs').hide();
                $('#dash_cost_report').load('<?php echo HTTP_ROOT; ?>Dashboard/dashboards/rag_cost_report', {}, function (res) {
                    $('#cost_report_ldr').hide();
                    var rsrc_prjflt = $("#sel_rsrc_prj_typ").val();
                    var rsrc_tmeflt = $("#sel_rsrc_time_typ").val();
                    $('#dash_resource_cost_report').load(HTTP_ROOT + 'Dashboard/dashboards/resource_cost_report', {'projid': rsrc_prjflt, 'time_flt': rsrc_tmeflt}, function (res) {
                        $('#resource_cost_report_ldr').hide();
                        $('#dash_project_timesheet').load('<?php echo HTTP_ROOT; ?>Dashboard/dashboards/project_timesheet', {}, function (res) {
                            $('#project_timesheet_ldr').hide();
                            $('#dash_resource_timesheet').load('<?php echo HTTP_ROOT; ?>Dashboard/dashboards/resource_timesheet', {}, function (res) {
                                $('#resource_timesheet_ldr').hide();
                                var usrchrt3 = $('#sel_user_chart3').val();
                                var timechrt3 = $('#sel_time_chart3').val();
                                $('#timelog_chart3').load(HTTP_ROOT + 'Dashboard/dashboards/timelog_project_chart', {'user_id': usrchrt3, 'time_flt': timechrt3}, function (res) {
                                    $('#timelog_graph_ldrs3').hide();
                                    var prjchrt2 = $('#sel_prj_chart2').val();
                                    var timechrt2 = $('#sel_time_chart2').val();
                                    $('#timelog_chart2').load(HTTP_ROOT + 'Dashboard/dashboards/timelog_resource_chart', {'prjct_id': prjchrt2, 'time_flt': timechrt2}, function (res) {
                                        $('#timelog_graph_ldrs2').hide();
                                        var strtimechrt3 = $('#star_time_chart').val();
                                        var strprjctid = $('#sel_prj_star').val();
                                        $('#star_chart').load(HTTP_ROOT + 'Dashboard/dashboards/star_project_chart', {'prjct_id': strprjctid, 'time_flt': strtimechrt3}, function (res) {
                                            $('#star_graph_ldrs').hide();
                                            var tme_typ_usrflt = $('#sel_user_tsktyp').val();
                                            var tme_typ_prjflt = $('#sel_prj_tsktyp').val();
                                            var tme_typ_tmeflt = $('#sel_time_tsktyp').val();
                                            $('#timelog_tsktyp').load(HTTP_ROOT + 'Dashboard/dashboards/dash_timelog_tasktype', {'projid': tme_typ_prjflt, 'user_fltid': tme_typ_usrflt, 'tme_flt': tme_typ_tmeflt}, function (res) {
                                                $('#timelog_graph_tsktyp').hide();
                                                var tsk_typ_usrflt = $('#sel_user_tsk_sts').val();
                                                var tsk_typ_prjflt = $('#sel_prj_tsk_sts').val();
                                                $('#task_status_pie').load(HTTP_ROOT + 'Dashboard/dashboards/dash_task_status', {'projid': tsk_typ_prjflt, 'user_fltid': tsk_typ_usrflt}, function (res) {
                                                    $('#task_status_pie_ldr').hide();
                                                    var tsk_typ_usrflt = $('#sel_user_tsk_typ').val();
                                                    var tsk_typ_prjflt = $('#sel_prj_tsk_typ').val();
                                                    $('#task_type_pie').load(HTTP_ROOT + 'Dashboard/dashboards/dash_task_type', {'projid': tsk_typ_prjflt, 'user_fltid': tsk_typ_usrflt}, function (res) {
                                                        $('#task_type_pie_ldr').hide();
//                            var prjchrt1 = $('#sel_prj_chart1').val();
//                            var usrchrt1 = $('#sel_user_chart1').val();
//                            var timechrt1 = $('#sel_time_chart1').val();
//                            $('#timelog_chart1').load(HTTP_ROOT + 'Dashboard/dashboards/timelog_chart1', {'prjct_id': prjchrt1, 'user_id': usrchrt1, 'time_flt': timechrt1}, function (res) {
//                                $('#timelog_graph_ldrs1').hide();
//                                
//                                    
//                                        var strtimechrt3 = $('#star_time_chart').val();
//                                        var strprjctid = $('#sel_prj_star').val();
//                                        $('#star_chart').load(HTTP_ROOT + 'Dashboard/dashboards/star_project_chart', {'prjct_id': strprjctid, 'time_flt': strtimechrt3}, function (res) {
//                                            $('#star_graph_ldrs').hide();
//                                            // var tsk_tmeflt =$('#sel_time_actvty').val();
//                                            var act_usrflt = $('#sel_user_actvty').val();
//                                            var act_prjflt = $('#sel_prj_actvty').val();
//                                            $('#dash_recent_activities').load(HTTP_ROOT + 'Dashboard/dashboards/dash_recent_activities', {'prj_id': act_prjflt, 'user_fltid': act_usrflt}, function (res) {
//                                                $('#recent_activities_ldr').hide();
//                                                var tsk_typ_usrflt = $('#sel_user_tsk_typ').val();
//                                                var tsk_typ_prjflt = $('#sel_prj_tsk_typ').val();
//                                                $('#task_type_pie').load(HTTP_ROOT + 'Dashboard/dashboards/dash_task_type', {'projid': tsk_typ_prjflt, 'user_fltid': tsk_typ_usrflt}, function (res) {
//                                                    $('#task_type_pie_ldr').hide();
//                                                   
//                                                       
//                                                           
//                                                               
//                                                                
//                                                                });
//                                                            });
//                                                        });
                                                    });
                                                });
                                            });
                                        });
                                    });
                                });
                            });
//
                        });
                    });
                });
            });
        }, 5000);
        $('[rel=tooltip]').tipsy({gravity: 's', fade: true});
    });
    function fulscreen_div(type) {
        if (type == "project_rag") {
            // $('#bck_rag,#exprt_rag').toggle();
            var data = $('#project_rag').html();
            if (data.indexOf("Oops") == -1) {
                $(".hide_buttn").toggle();
                $('.view_tr').toggle();
                $('.hide_td').show();
                $('.prograg_tr').show();
                $('#project_rag').addClass('fullscreen');
            } else {
                showTopErrSucc('error', 'No data to display');
            }
        } else if (type == 'prj_timesheet') {
            // $('#bck_prjtsht').toggle();
            var data = $('#dash_project_timesheet').html();
            if (data.indexOf("Oops") == -1) {
                $(".hide_buttn").toggle();
                $('.progtmesht_tr').show();
                $('#dash_project_timesheet').addClass('fullscreen');
            } else {
                showTopErrSucc('error', 'No data to display');
            }
        } else if (type == 'resource_timesheet') {
            var data = $('#dash_resource_timesheet').html();
            if (data.indexOf("Oops") == -1) {
                $(".hide_buttn").toggle();
                $('.rsrctmesht_tr').show();
                $('#dash_resource_timesheet').addClass('fullscreen');
            } else {
                showTopErrSucc('error', 'No data to display');
            }
        } else if (type == "cost_rprt") {
            var data = $('#dash_cost_report').html();
            if (data.indexOf("Oops") == -1) {
                $(".hide_buttn").toggle();
                $('.cst_rprt_tr').show();
                $('#dash_cost_report').addClass('fullscreen');
            } else {
                showTopErrSucc('error', 'No data to display');
            }
        } else if (type == "resource_cost") {
            var data = $('#dash_resource_cost_report').html();
            if (data.indexOf("Oops") == -1) {
                $(".hide_buttn").toggle();
                $('.view_tr').toggle();
                $('.hide_td').show();
                $('.resource_tr').show();
                $('#dash_resource_cost_report').addClass('fullscreen');
            } else {
                showTopErrSucc('error', 'No data to display');
            }
        }

    }
    function hide_flscrn_div(type) {
        if (type == "project_rag") {
            // $('#bck_rag,#exprt_rag').toggle();

            $(".hide_buttn").toggle();
            $('.view_tr').toggle();
            $('.hide_td').hide();
            $(".prograg_tr:gt(4)").css("display", "none");
            $('#project_rag').removeClass('fullscreen');
            $.post(HTTP_ROOT + "Dashboard/dashboards/project_rag", {}, function (data) {
                if (data) {
                    $('#project_rag').html(data);
                }
            });
        } else if (type == 'prj_timesheet') {
            // $('#bck_prjtsht').toggle();
            $(".hide_buttn").toggle();
            $('.progtmesht_tr:gt(4)').css("display", "none");
            $('#dash_project_timesheet').removeClass('fullscreen');
        } else if (type == 'resource_timesheet') {
            // $('#bck_rcrstsht').toggle();
            $(".hide_buttn").toggle();
            $('.rsrctmesht_tr:gt(4)').css("display", "none");
            $('#dash_resource_timesheet').removeClass('fullscreen');
        } else if (type == 'cost_rprt') {
            $(".hide_buttn").toggle();
            $('.cst_rprt_tr:gt(4)').css("display", "none");
            $('#dash_cost_report').removeClass('fullscreen');
        } else if (type == 'resource_cost') {
            $(".hide_buttn").toggle();
            $('.resource_tr:gt(4)').css("display", "none");
            $('#dash_resource_cost_report').removeClass('fullscreen');
        }
    }
    function fulscreen_timelog(type) {
        if (type == 'timelog') {
            var data = $('#timelog_chart1').html();
            if (data.indexOf("Oops") == -1) {
                $('.hide_buttn').toggle();

                $('#timelog_table').toggle();
                $('#timelog_chart1').toggleClass('fullscreen');
            } else {
                showTopErrSucc('error', 'No data to display');
            }
        } else if (type == 'dash_tasktype') {
            var data1 = $('#timelog_tsktyp').html();
            if (data1.indexOf("Oops") == -1) {
                $('.hide_buttn').toggle();
                $('#tasktype_table').toggle();
                $('#timelog_tsktyp').toggleClass('fullscreen');
            } else {
                showTopErrSucc('error', 'No data to display');
            }
        }
    }
    function PrintDiv_Dash() {
        window.print();
        /*var divToPrint = document.getElementById('divToPrint_dash');
         var popupWin = window.open('', '_blank', 'width=1280,height=900');
         popupWin.document.open();
         popupWin.document.write('<html><head><title>Orangescrum Dashboard</title>');
         popupWin.document.write('<link rel="stylesheet" href="' + HTTP_ROOT + 'css/print.css" media="print" /><link rel="stylesheet" href="' + HTTP_ROOT + 'css/bootstrap.min.css" type="text/css" /><link rel="stylesheet" href="' + HTTP_ROOT + 'css/style_inner.css" type="text/css"/>');
         popupWin.document.write('</head><body onload="window.print();return false;">' + divToPrint.innerHTML + '</body></html>');
         popupWin.document.close();*/
    }
    function showHelpContent() {
        $('.hlp_div').toggle();
        $('#hlp_cntnt_div').toggleClass('fullscreen');
    }
</script>
