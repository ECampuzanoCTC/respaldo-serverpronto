<?php

class DatetimeHelper extends AppHelper {

    public $helpers = array('Tmzone');

    function nextDate($givenDateTime, $value, $type) {
        if ($givenDateTime) {
            $dat = explode(" ", $givenDateTime);
            $dat1 = explode("-", $dat[0]);
            $dat2 = explode(":", $dat[1]);
            if ($type == "day") {
                $next_dt = mktime($dat2[0], $dat2[1], $dat2[2], $dat1[1], $dat1[2] + $value, $dat1[0]);
            }
            if ($type == "month") {
                $next_dt = mktime($dat2[0], $dat2[1], $dat2[2], $dat1[1] + $value, $dat1[2], $dat1[0]);
            }
            $datetime = date("Y-m-d H:i:s", $next_dt);
            return $datetime;
        } else {
            return "";
        }
    }

    function dateDiff($date1, $date2) {
        if (strtotime($date2) > strtotime($date1)) {
            return round(abs(strtotime($date2) - strtotime($date1)) / 86400);
        } else {
            return round(abs(strtotime($date1) - strtotime($date2)) / 86400);
        }
    }

    function caseDetailsFormat($datetime, $curdate) {
        $output = explode(" ", $datetime);
        $dateExp = explode("-", $output[0]);
        $dateformated = $dateExp[1] . "/" . $dateExp[2] . "/" . $dateExp[0];

        $yesterday = date("Y-m-d", strtotime($curdate . "-1 days"));
        if ($dateformated == $this->dateFormatReverse($curdate)) {
            return __("Today at ") . date("g:i a", strtotime($datetime));
        } elseif ($dateformated == $this->dateFormatReverse($yesterday)) {
            return __("Y'day at ") . date("g:i a", strtotime($datetime));
        } else {
            return date("M jS Y, g:i a", strtotime($datetime));
        }
    }

    function dueDateFormat($duedate, $curdate) {
        $yesterday = date("Y-m-d", strtotime($curdate . "-1 days"));
        $tomorrow = date("Y-m-d", strtotime($curdate . "+1 days"));

        if ($duedate == $curdate) {
            return __("Today");
        } elseif ($duedate == $yesterday) {
            return __("Y'day");
        } elseif ($duedate == $tomorrow) {
            return __("Tomorrow");
        } else {
            return date("m/d/Y", strtotime($duedate));
        }
    }

    function dateFormatReverse($output_date) {
        if ($output_date != "") {
            if (strstr($output_date, " ")) {
                $exp = explode(" ", $output_date);
                $od = $exp[0];
            } else {
                $od = $output_date;
            }
            $date_ex2 = explode("-", $od);
            $dateformated_input = $date_ex2[1] . "/" . $date_ex2[2] . "/" . $date_ex2[0];
            if ($date_ex2[2] != "00") {
                return $dateformated_input;
            }
        }
    }

    function dateFormatOutputdateTime_day($date_time, $curdate = NULL, $type = NULL, $is_month_last = 0, $viewtype = '') {
        if ($date_time != "") {
            if ($GLOBALS['TimeFormat'] == 2) {
                $time_format = 'H:i';
            } else {
                $time_format = 'g:i a';
            }

            $date_time = date("Y-m-d H:i:s", strtotime($date_time));
            $output = explode(" ", $date_time);
            $date_ex2 = explode("-", $output[0]);

            $dateformated = $date_ex2[1] . "/" . $date_ex2[2] . "/" . $date_ex2[0];
            if ($date_ex2[2] != "00") {
                $displayWeek = 0;
                $timeformat = date($time_format, strtotime($date_time));

                $week1 = date("l", mktime(0, 0, 0, $date_ex2[1], $date_ex2[2], $date_ex2[0]));
                $week_sub1 = substr($week1, "0", "3");

                $yesterday = date("Y-m-d", strtotime($curdate . "-1 days"));

                if ($dateformated == $this->dateFormatReverse($curdate)) {
                    $dateTime_Format = __("Today", true);
                } elseif ($dateformated == $this->dateFormatReverse($yesterday)) {
                    $dateTime_Format = __("Yesterday", true);
                } else {
                    $CurYr = date("Y", strtotime($curdate));
                    $DateYr = date("Y", strtotime($dateformated));
                    if ($GLOBALS['DateFormat'] == 2) {
                        $date_format_kan = "d/m";
                        $date_format_yr_same = "d M";
                        $date_format = "d M, Y";
                    } else {
                        $date_format_kan = "m/d";
                        $date_format_yr_same = "M d";
                        $date_format = "M d, Y";
                    }
                    if ($viewtype == 'kanban') {
                        $dateformated = date($date_format_kan, strtotime($dateformated));
                    } elseif ($CurYr == $DateYr) {
                        $dateformated = date($date_format_yr_same, strtotime($dateformated));
                        $dtformated = date($date_format_yr_same, strtotime($dateformated)) . ", " . date("D", strtotime($dateformated));
                        $displayWeek = 1;
                    } else {
                        $dateformated = date($date_format, strtotime($dateformated));
                        $dtformated = date($date_format, strtotime($dateformated));
                    }
                    $dateTime_Format = $dateformated;
                }
                if ($type == 'date') {
                    return $dateTime_Format;
                } elseif ($type == 'time') {
                    return $dateTime_Format . " " . $timeformat;
                } elseif ($type == 'week') {
                    if ($dateTime_Format == __("Today", true) || $dateTime_Format == __("Yesterday", true) || !$displayWeek) {
                        return $dateTime_Format;
                    } else {
                        //return $dateTime_Format.", ".date("D",strtotime($dateformated));
                        return $dtformated;
                        //return $dateTime_Format;
                    }
                } else {
                    if ($dateTime_Format == __("Today", true) || $dateTime_Format == __("Yesterday", true)) {
                        if ($is_month_last) {
                            return $dateTime_Format;
                        } else {
                            return $dateTime_Format . " " . $timeformat;
                        }
                    } else {
                        if ($is_month_last) {
                            return date("D", strtotime($dateformated)) . ", " . $dateTime_Format;
                        } elseif ($viewtype == 'kanban') {
                            return $dateTime_Format . ", " . " " . $timeformat;
                        } else {
                            //return $dateTime_Format.", ".date("D",strtotime($dateformated))." ".$timeformat;
                            return $dtformated . " " . $timeformat;
                        }
                    }
                }
            }
        }
    }

    function dateFormatOutputdateTime($date_time, $curdate = NULL, $type = NULL) {
        //echo $date_time."------".$curdate."<br/>";
        $curr = strtotime($curdate);
        $crted = strtotime($date_time);
        $diff_in_sec = ($curr - $crted);
        $diff_in_min = round(($curr - $crted) / 60);
        $diff_in_hr = round(($curr - $crted) / (60 * 60));
        if ($diff_in_sec < 60) {
            if ($diff_in_sec != 1) {
                //return $diff_in_sec." secs ago";
                return __("just now");
            } else {
                //return $diff_in_sec." sec ago";
                return __("just now");
            }
        } else if ($diff_in_min < 60) {
            if ($diff_in_min != 1) {
                return $diff_in_min . __(" mins ago");
            } else {
                return $diff_in_min . __(" min ago");
            }
        } else if ($diff_in_hr < 24) {
            if ($diff_in_hr != 1) {
                return $diff_in_hr . __(" hours ago");
            } else {
                return $diff_in_hr . __(" hour ago");
            }
        }
    }

    function facebook_style($date, $curdate = NULL, $type = NULL) {

        $checkDate = date("Y-m-d", strtotime($date));
        $checkCur = date("Y-m-d", strtotime($curdate));
        if ($checkDate == $checkCur) {
            if ($type == 'date') {
                return $this->dateFormatOutputdateTime($date, $curdate, 'date');
            } else {
                return $this->dateFormatOutputdateTime($date, $curdate, 'time');
            }
        }

        $timestamp = strtotime($date);
        $difference = strtotime($curdate) - $timestamp;

        //return $date." - ".$curdate;

        $periods = array("sec", "min", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

        if ($difference > 0) { // this was in the past time
            $ending = __("ago");
        } else { // this was in the future time
            $difference = -$difference;
            $ending = __("to go");
        }
        for ($j = 0; ($difference >= $lengths[$j] && $j <= 6); $j++)
            $difference /= $lengths[$j];
        $difference = round($difference);
        if ($difference != 1)
            $periods[$j] .= "s";
        $text = "$difference $periods[$j] $ending";
        return $text;
    }

    /* Added by Smruti on 08092013 */

    function facebook_datetimestyle($date) {
        if ($GLOBALS['TimeFormat'] == 2) {
            $time_format = '\a\t H:i';
        } else {
            $time_format = '\a\t h:i a';
        }
        if ($GLOBALS['DateFormat'] == 2) {
            $date_format = 'l, d F, Y';
        } else {
            $date_format ='l, F d, Y';
        }

        return $checkDate = date($date_format.' '.$time_format, strtotime($date));
        //$checkTime = date('h:i a',strtotime($date));
        //return $checkDate." at ". $checkTime;
    }

    function facebook_datestyle($date) {
        if ($GLOBALS['DateFormat'] == 2) {
            $checkDate = date('l, d F, Y', strtotime($date));
        } else {
            $checkDate = date('l, F d, Y', strtotime($date));
        }
        return $checkDate;
    }
    function short_facebook_datestyle($date) {
        if ($GLOBALS['DateFormat'] == 2) {
            $checkDate = date('D, d M', strtotime($date));
        } else {
            $checkDate = date('D, M d', strtotime($date));
        }
        return $checkDate;
    }

    function facebook_style_date_time($date, $curdate = NULL, $type = NULL, $restype = '') {

        $checkDate = date("Y-m-d", strtotime($date));
        $checkCur = date("Y-m-d", strtotime($curdate));
        if ($checkDate == $checkCur) {
            if ($restype == 'days') {/* This is added only for days type results and for current date it will return 0 days,Used in osadmin manage company page */
                return 0;
            } elseif ($type == 'date') {
                return $this->dateFormatOutputdateTime_day($date, $curdate, 'date');
            } else {
                return $this->dateFormatOutputdateTime_day($date, $curdate, 'time');
            }
        }

        $timestamp = strtotime($date);
        $difference = strtotime($curdate) - $timestamp;

        //return $date." - ".$curdate;

        $periods = array("sec", "min", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

        if ($difference > 0) { // this was in the past time
            $ending = __("ago");
        } else { // this was in the future time
            $difference = -$difference;
            $ending = __("to go");
        }
        if ($restype == 'days') {
            $periods = array("sec", "min", "hour", "day");
            $lengths = array("60", "60", "24");
            for ($j = 0; ($difference >= $lengths[$j] && $j < 3); $j++)
                $difference /= $lengths[$j];
            if ($j < 3) {
                return 0; // As we are calculating everything in terms of days so we will skip the Hr , mins ,Secs
            }
            return round($difference);
        } else {
            for ($j = 0; $difference >= $lengths[$j]; $j++)
                $difference /= $lengths[$j];
        }

        $difference = round($difference);
        if ($difference != 1)
            $periods[$j] .= "s";
        $text = "$difference $periods[$j] $ending";
        return $text;
    }

    function caseDateTime_noTime($dateTime, $curdate) {
        $dt = explode(" ", $dateTime);
        $date = explode("-", $dt[0]);

        $date_week = $date[1] . "/" . $date[2] . "/" . $date[0];

        $date = $date[1] . "/" . $date[2] . "/" . substr($date[0], 2, 2);
        $date_week_exp = explode("/", $date_week);
        $time = explode(":", $dt[1]);
        if ($time[0] > "12") {
            $hour = $time[0] - 12;
            $timeformat = $hour . ":" . $time[1] . " pm";
        } elseif ($time[0] == "12") {
            $timeformat = $time[0] . ":" . $time[1] . " pm";
        } elseif ($time[0] < "12") {
            $timeformat = $time[0] . ":" . $time[1] . " am";
        }
        $week1 = date("l", mktime(0, 0, 0, $date_week_exp[0], $date_week_exp[1], $date_week_exp[2]));
        $week_sub1 = substr($week1, "0", "3");

        $yesterday = date("Y-m-d", strtotime($curdate . "-1 days"));
        if ($date_week == $this->dateFormatReverse($curdate)) {
            return __("Today");
        } elseif ($date_week == $this->dateFormatReverse($yesterday)) {
            return __("Y'day");
        } else {
            return $date . ", " . date("D", strtotime($date));
        }
    }

    function dateFormatOutputdateTime_details($date_time, $curdate) {
        if ($date_time != "") {
            $output = explode(" ", $date_time);
            $date_ex2 = explode("-", $output[0]);
            $dateformated = $date_ex2[1] . "/" . $date_ex2[2] . "/" . $date_ex2[0];
            if ($date_ex2[2] != "00") {
                $time = explode(":", $output[1]);
                if ($time[0] > "12") {
                    $hour = $time[0] - 12;
                    $timeformat = $hour . ":" . $time[1] . " pm";
                } elseif ($time[0] == "12") {
                    $timeformat = $time[0] . ":" . $time[1] . " pm";
                } elseif ($time[0] < "12") {
                    $timeformat = $time[0] . ":" . $time[1] . " am";
                }

                $week1 = date("l", mktime(0, 0, 0, $date_ex2[1], $date_ex2[2], $date_ex2[0]));
                $week_sub1 = substr($week1, "0", "3");

                $yesterday = date("Y-m-d", strtotime($curdate . "-1 days"));
                if ($dateformated == $this->dateFormatReverse($yesterday)) {
                    return __("Y'day at ") . $timeformat;
                }
                if ($dateformated == $this->dateFormatReverse($curdate)) {
                    return __("Today at ") . $timeformat;
                } else {
                    $dateTime_Format = $dateformated . __(" at ") . $timeformat;
                    return $dateTime_Format;
                }
            }
        }
    }

    function datediffernce($user_date, $type) {
        $upcoming_dates = "";
        $from = strtotime($user_date);
        $today = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
        if ($type == 'upcoming') {
            $diff = abs(strtotime($user_date) - strtotime($today));
        } else {
            $diff = abs(strtotime($today) - strtotime($user_date));
        }
        /*  $years = floor($diff / (365*60*60*24));
          $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
          $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
          if($years >0){
          $upcoming_dates .=$years .' years';
          }
          if($months > 0){
          $upcoming_dates .= $months .' months ';
          }
          if($days >0){
          $upcoming_dates = $days."&nbsp;Days" ;
          } else{
          $upcoming_dates = 'Today';
          }
          return $upcoming_dates; */
        $days = floor($diff / 86400);
        $hours = floor(($diff - ($days * 86400)) / 3600);
        $minutes = floor(($diff - ($days * 86400) - ($hours * 3600)) / 60);
        //  $dffrnce_date = floor($diff / (60 * 60 * 24 * 60)) ; 
        if ($days > 0) {
            $dffrnce_date = $days . " Days";
        } else {
            $dffrnce_date = $hours . 'hr ' . $minutes . ' mins';
        }
        //return $dffrnce_date > 0 ? $dffrnce_date." Days" : "Today" ;
        return $dffrnce_date;
    }

}

?>
