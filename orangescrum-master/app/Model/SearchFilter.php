<?php

class SearchFilter extends AppModel {

    var $name = 'SearchFilter';

    function getDefault() {
        $User = ClassRegistry::init('User');
        /*
         * Display the notifications of search filter to the users who are registred before 2016-06-10 not show to teh new customers
         */
        $userCount = $User->find('count', array('conditions' => array('User.id' => SES_ID, 'User.dt_created <= ' => '2016-06-10 00:00:00')));
        if ($userCount) {
            $sfarray = $this->find('count', array('conditions' => array('SearchFilter.user_id' => SES_ID, 'SearchFilter.name' => 'default', 'SearchFilter.first_records' => 1)));
        } else {
            $sfarray = 2;
        }
        return $sfarray;
    }

    function getFiltersWithCounts($data) {
        $sfarray = $this->find('all', array('conditions' => array('SearchFilter.user_id' => SES_ID, 'SearchFilter.name != ' => 'default')));
        foreach ($sfarray as $k => $v) {
            $arr = json_decode($v['SearchFilter']['json_array']);
            $sfarray[$k]['SearchFilter']['namewithcount'] = $v['SearchFilter']['name'] . ' (' . $this->getCount($arr, $data['projUniq'], $data['milestoneIds'], $data['case_srch'], $data['checktype']) . ')';
        }
        return $sfarray;
    }

    function getCount($data, $prjUniqIdCsMenu, $milestoneIds, $case_srch, $checktype) {
        $qry = '';
        //Filter Condition added in Menu filters counters
//            $caseMenuFilters =$_COOKIE['CURRENT_FILTER'];
        $caseMenuFilters = $_SESSION['CURRENT_FILTER'];
        $caseStatus = $data->STATUS;
        ; // Filter by Status(legend)

        $priorityFil = $data->PRIORITY; // Filter by Priority
        $caseTypes = $data->CS_TYPES; // Filter by case Types            
        $caseUserId = $data->MEMBERS; // Filter by Member
        $caseAssignTo = $data->ASSIGNTO; // Filter by AssignTo
        $caseMilestone = $data->MILESTONE; // Filter by AssignTo
        @$case_date = urldecode($data->DATE); // Filter by date
        @$case_duedate = $data->DUE_DATE; // Filter by due_date
        @$case_srch = $case_srch;
        $milestoneIds = $milestoneIds;
        $checktype = $checktype;
        App::import('Component', 'Format');
        $format = new FormatComponent(new ComponentCollection);
        ######### Filter by Case Types ##########
        if ($caseTypes && $caseTypes != "all") {
            $qry .= $format->typeFilter($caseTypes);
        }
        ######### Filter by Priority ##########
        if ($priorityFil && $priorityFil != "all") {

            $qry .= $format->priorityFilter($priorityFil, $caseTypes);
        }
        ######### Filter by Status ##########
        if ($caseStatus && $caseStatus != 'all') {
            $qry .= $format->statusFilter($caseStatus);
        }

        ######### Filter by Member ##########
        if ($caseUserId && $caseUserId != "all") {

            $qry .= $format->memberFilter($caseUserId);
        }
        ######### Filter by AssignTo ##########		/* Added by smruti on 08082013*/
        if ($caseAssignTo && $caseAssignTo != "all" && $caseAssignTo != 'unassigned') {
            $qry .= $format->assigntoFilter($caseAssignTo);
        } else if ($caseAssignTo && $caseAssignTo == 'unassigned') {
            $qry .= " AND Easycase.assign_to=0";
        }
        $qry1 = "";
        $qryAsn = "";
        if (!empty($caseMilestone) && $caseMilestone != "all") {
            if (strstr($caseMilestone, "-")) {
                $asnArr = explode("-", $caseMilestone);
                foreach ($asnArr as $asnChk) {
                    $qryAsn .= "EasycaseMilestone.milestone_id=" . $asnChk . " OR ";
                }
                $qryAsn = substr($qryAsn, 0, -3);
                $qry1 .= " AND (" . $qryAsn . ")";
            } else {
                $qry1 .= " AND EasycaseMilestone.milestone_id=" . $caseMilestone;
            }
        }
        ######### Search by KeyWord ##########
        $searchcase = "";
        if (trim(urldecode($caseSrch)) && (trim($case_srch) == "")) {
            $qry = "";
            $searchcase = $format->caseKeywordSearch($caseSrch, 'full');
        }
        if (trim(urldecode($case_srch)) != "") {
            $qry = "";
            $searchcase = "AND (Easycase.case_no = '$case_srch')";
        }

        if (trim(urldecode($caseSrch))) {
            if ((substr($caseSrch, 0, 1)) == '#') {
                $qry = "";
                $tmp = explode("#", $caseSrch);
                $casno = trim($tmp['1']);
                $searchcase = " AND (Easycase.case_no = '" . $casno . "')";
            }
        }

        if (trim($case_date) != "") {
            if (trim($case_date) == 'one') {
                $one_date = date('Y-m-d H:i:s', time() - 3600);
                $qry .= " AND Easycase.dt_created >='" . $one_date . "'";
            } else if (trim($case_date) == '24') {
                $day_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 day"));
                $qry .= " AND Easycase.dt_created >='" . $day_date . "'";
            } else if (trim($case_date) == 'week') {
                $week_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 week"));
                $qry .= " AND Easycase.dt_created >='" . $week_date . "'";
            } else if (trim($case_date) == 'month') {
                $month_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 month"));
                $qry .= " AND Easycase.dt_created >='" . $month_date . "'";
            } else if (trim($case_date) == 'year') {
                $year_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " -1 year"));
                $qry .= " AND Easycase.dt_created >='" . $year_date . "'";
            } else if (strstr(trim($case_date), ":")) {
                //echo $case_date;exit;
                $ar_dt = explode(":", trim($case_date));
                $frm_dt = $ar_dt['0'];
                $to_dt = $ar_dt['1'];
                $qry .= " AND DATE(Easycase.dt_created) >= '" . date('Y-m-d H:i:s', strtotime($frm_dt)) . "' AND DATE(Easycase.dt_created) <= '" . date('Y-m-d H:i:s', strtotime($to_dt)) . "'";
            }
        }
        if (trim($case_duedate) != "") {
            if (trim($case_duedate) == '24') {
                $day_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +1 day"));
                $qry .= " AND (DATE(Easycase.due_date) ='" . GMT_DATE . "')";
            } else if (trim($case_duedate) == 'overdue') {
                $week_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +1 week"));
                $qry .= " AND ( DATE(Easycase.due_date) <'" . GMT_DATE . "') AND (Easycase.legend !=3)";
            } else if (strstr(trim($case_duedate), ":")) {
                //echo $case_duedate;exit;
                $ar_dt = explode(":", trim($case_duedate));
                $frm_dt = $ar_dt['0'];
                $to_dt = $ar_dt['1'];
                $qry .= " AND DATE(Easycase.due_date) >= '" . date('Y-m-d', strtotime($frm_dt)) . "' AND DATE(Easycase.due_date) <= '" . date('Y-m-d', strtotime($to_dt)) . "'";
            }
        }

        if ($prjUniqIdCsMenu != 'all' && trim($prjUniqIdCsMenu)) {
            $Project = ClassRegistry::init('Project');
            $Project->recursive = -1;
            $projArr = $Project->find('first', array('conditions' => array('Project.uniq_id' => $prjUniqIdCsMenu, 'Project.isactive' => 1), 'fields' => array('Project.id')));
            if (count($projArr)) {
                $proj_id = $projArr['Project']['id'];
                $qry .= " AND Easycase.project_id='" . $proj_id . "' ";
            }
        } else {
            $cond = array('conditions' => array('ProjectUser.user_id' => SES_ID, 'Project.isactive' => 1), 'fields' => array('DISTINCT Project.id'), 'order' => array('ProjectUser.dt_visited DESC'));

            $ProjectUser = ClassRegistry::init('ProjectUser');
            $ProjectUser->unbindModel(array('belongsTo' => array('User')));
            $ProjectUser->bindModel(array('belongsTo' => array('Project')));
            $allProjArr = $ProjectUser->find('all', $cond);

            $ids = array();
            $idlist = '';
            foreach ($allProjArr as $csid) {
                $idlist .= '\'' . $csid['Project']['id'] . '\',';
                array_push($ids, $csid['Project']['id']);
            }
            $idlist = trim($idlist, ',');
            if ($idlist != '') {
                $qry .= " AND Easycase.project_id IN(" . $idlist . ")";
            }
        }
        $Easycase = ClassRegistry::init('Easycase');
        # print "SELECT count(*) as cnt FROM easycases as Easycase WHERE Easycase.isactive=1 AND Easycase.istype= 1  ".$qry;exit;
        $clt_sql = 1;
        $auth = new AuthComponent(new ComponentCollection);
        if ($auth->user('is_client') == 1) {
            $clt_sql = "((Easycase.client_status = " . $auth->user('is_client') . " AND Easycase.user_id = " . $auth->user('id') . ") OR Easycase.client_status != " . $auth->user('is_client') . ")";
        }
        $cnt = $Easycase->query("SELECT count(*) as cnt FROM easycases as Easycase LEFT JOIN easycase_milestones EasycaseMilestone ON Easycase.id=EasycaseMilestone.easycase_id WHERE Easycase.isactive=1 AND Easycase.istype= 1  AND " . $clt_sql . " " . $qry . $qry1);
        if ($cnt) {
            return $cnt[0][0]['cnt'];
        } else {
            return 0;
        }
    }

}
