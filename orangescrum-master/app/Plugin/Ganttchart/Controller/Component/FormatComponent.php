<?php

App::import('Vendor', 's3', array('file' => 's3' . DS . 'S3.php'));

class FormatComponent extends Component {

    public $components = array('Session', 'Email', 'Cookie');

    /* gantt start */

    function changeGanttDataV2($json_arr, $status_arr = '') {
        #echo "<pre>";print_r($json_arr); exit;
        $user_ids = array();

        $colors = array(0 => '#73BCDE', 1 => '#8BC2B9', 2 => '#F8B363', 3 => '#EA7373', 4 => '#9ECC61');
        foreach ($json_arr as $key => $value) {
            $assign_to = intval($value['assign_to']);
            if ($assign_to > 0 && !in_array($assign_to, $user_ids)) {
                $user_ids[] = $assign_to;
            }

            $json_arr[$key]['id'] = $value['id'];
            $json_arr[$key]['name'] = htmlspecialchars($value['title']);
            $json_arr[$key]['description'] = htmlspecialchars($value['message']);

            #STATUS_ACTIVE, STATUS_DONE, STATUS_FAILED, STATUS_SUSPENDED, STATUS_UNDEFINED.
            #$json_arr[$key]['status'] = 'STATUS_ACTIVE';
            $json_arr[$key]['status'] = $assign_to > 0 ? 'color' . array_search($assign_to, $user_ids) . '' : 'color15';
            $json_arr[$key]['canWrite'] = true;


            $json_arr[$key]['startIsMilestone'] = false;
            $json_arr[$key]['endIsMilestone'] = false;
            $json_arr[$key]['collapsed'] = false;
            $json_arr[$key]['assigs'] = array();
            $json_arr[$key]['hasChild'] = 0;
            $json_arr[$key]['level'] = 1;
            $json_arr[$key]['depends'] = $value['depends'];
            $json_arr[$key]['progress'] = $value['progress'];
            $json_arr[$key]['assigned_to'] = $value['assigned_to'];
            $json_arr[$key]['priority'] = $value['priority'];
            $json_arr[$key]['type_id'] = $value['type_id'];
            $json_arr[$key]['case_no'] = $value['case_no'];

            if ((!empty($value['gantt_start_date']) && !is_null($value['gantt_start_date']) && $value['gantt_start_date'] != '0000-00-00 00:00:00') && ($value['due_date'] != '' && !is_null($value['due_date']) && $value['due_date'] != '0000-00-00 00:00:00')) {
                //print_r($v['due_date']);print $v['id'];echo "    1";exit;
                $json_arr[$key]['start'] = $value['gantt_start_date'];
                $json_arr[$key]['end'] = $value['due_date'];
                $json_arr[$key]['color'] = $colors[$key];
            } else if ((empty($value['gantt_start_date']) || is_null($value['gantt_start_date']) || $value['gantt_start_date'] == '0000-00-00 00:00:00') && ($value['due_date'] != '' && !is_null($value['due_date']) && $value['due_date'] != '0000-00-00 00:00:00')) {
                //print_r($v['due_date']);echo "   2";exit;
                $json_arr[$key]['start'] = $value['due_date'];
                $json_arr[$key]['end'] = $value['due_date'];
                $json_arr[$key]['color'] = $colors[$key];
            } else if ((!empty($value['gantt_start_date']) && !is_null($value['gantt_start_date']) && $value['gantt_start_date'] != '0000-00-00 00:00:00') && ($value['due_date'] == '' || is_null($value['due_date']) || $value['due_date'] == '0000-00-00 00:00:00')) {
                //print_r($v['due_date']);echo "   3";exit;
                $json_arr[$key]['start'] = $value['gantt_start_date'];
                $json_arr[$key]['end'] = date('Y-m-d', $this->dateConvertion($value['gantt_start_date']));
                $json_arr[$key]['color'] = $colors[$key];
            } else {
                //print_r($v['gantt_start_date']);echo "   4";exit;
                $start = explode(' ', $value['actual_dt_created']);
                $json_arr[$key]['start'] = $start[0];
                $json_arr[$key]['end'] = date('Y-m-d', $this->dateConvertion($value['actual_dt_created']));
                $json_arr[$key]['color'] = $colors[$key];
            }

            /* convert to user timezone */
            $json_arr[$key]['start'] = $this->convert_date_timezone($json_arr[$key]['start']);
            $json_arr[$key]['end'] = $this->convert_date_timezone($json_arr[$key]['end']);

            $json_arr[$key]['duration'] = $this->days_diff($json_arr[$key]['start'], $json_arr[$key]['end']);
            $json_arr[$key]['o_start'] = ($json_arr[$key]['start']);
            $json_arr[$key]['o_end'] = ($json_arr[$key]['end']);

            /* convert to millisecond */
            $json_arr[$key]['start'] = strtotime($json_arr[$key]['start']) * 1000;
            $json_arr[$key]['end'] = strtotime($json_arr[$key]['end']) * 1000;

            if ($value['legend'] == '1') {
                $json_arr[$key]['color'] = '#f19a91';
            } else if ($value['legend'] == '2' || $value['legend'] == '6') {
                $json_arr[$key]['color'] = '#8dc2f8';
            } else if ($value['legend'] == '5') {
                $json_arr[$key]['color'] = '#f3c788';
            } else if ($value['legend'] == '3') {
                $json_arr[$key]['color'] = '#8ad6a3';
            } else if (isset($status_arr[$value['legend']]) && !empty($status_arr[$value['legend']])) {
                $json_arr[$key]['color'] = $status_arr[$value['legend']]['color'];
            } else {
                $json_arr[$key]['color'] = '#3dbb89';
            }
            unset($json_arr[$key]['title']);
            #unset($json_arr[$key]['id']);
            #unset($json_arr[$key]['legend']);
            unset($json_arr[$key]['gantt_start_date']);
            unset($json_arr[$key]['due_date']);
            unset($json_arr[$key]['actual_dt_created']);
        }//exit;
        #echo "<pre>";print_r($json_arr);exit;
        return $json_arr;
    }

    function get_formated_date($value = '') {
        if ((!empty($value['start_date']) && !is_null($value['start_date']) && $value['start_date'] != '0000-00-00') && ($value['end_date'] != '' && !is_null($value['end_date']) && $value['end_date'] != '0000-00-00')) {

            $start = $value['start_date'];
            $end = $value['end_date'];
            $color = $colors[$key];
        } else if ((empty($value['start_date']) || is_null($value['start_date']) || $value['start_date'] == '0000-00-00') && ($value['end_date'] != '' && !is_null($value['end_date']) && $value['end_date'] != '0000-00-00')) {

            $start = $value['created'];
            $end = $value['end_date'];
            $color = $colors[$key];
        } else if ((!empty($value['start_date']) && !is_null($value['start_date']) && $value['start_date'] != '0000-00-00') && ($value['end_date'] == '' || is_null($value['end_date']) || $value['end_date'] == '0000-00-00')) {

            $start = $value['start_date'];
            $end = date('Y-m-d', $this->dateConvertion($value['start_date']));
            $color = $colors[$key];
        } else {

            $start = explode(' ', $value['created']);
            $start = $start[0];
            $end = date('Y-m-d', $this->dateConvertion($value['created']));
            $color = $colors[$key];
        }
        /* convert to user timezone */
        $start = $this->convert_date_timezone($start);
        $end = $this->convert_date_timezone($end);

        $json_arr['duration'] = $this->days_diff($start, $end);
        $json_arr['o_start'] = $start;
        $json_arr['o_end'] = $end;

        $json_arr['color'] = $color;
        // convert to millisec
        $json_arr['start'] = strtotime($start) * 1000;
        $json_arr['end'] = strtotime($end) * 1000;
        return $json_arr;
    }

    function days_diff($from = '', $to = '') {
        $from_date = strtotime($from); // or your date as well
        $to_date = strtotime($to);
        $datediff = $to_date - $from_date;
        return round($datediff / (60 * 60 * 24)) > 1 ? round($datediff / (60 * 60 * 24)) : 1;
        #return floor($datediff / (60 * 60 * 24)) > 1 ? floor($datediff / (60 * 60 * 24)) : 1;
    }

    function convert_date_timezone($date = '') {
        if (trim($date == '')) {
            $date = date('Y-m-d H:i:s');
        }
        return $date;
        #return $this->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $date, "date");
    }

    function formatTitle($title) {
        if (isset($title) && !empty($title)) {
            $title = stripcslashes(htmlspecialchars(html_entity_decode($title, ENT_QUOTES, 'UTF-8')));
        }
        return $title;
    }

    function dateConvertion($date) {
        //print_r($date);exit;
        $seconds = strtotime($date);
        return ($seconds + 86400);
    }
    
    function generateUniqNumber() {
        $uniq = uniqid(rand());
        return md5($uniq . time());
    }

    /* gantt end */
}

?>
