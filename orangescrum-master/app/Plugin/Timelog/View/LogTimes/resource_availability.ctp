<style type="text/css">
    .resource-available .table-responsive {min-height: .01%;overflow-x: auto;}
    .resource-available .table-responsive .table {margin-bottom: 10px;width: 100%;max-width: 100%;margin-bottom: 20px;background-color: transparent;border-spacing: 0;border-collapse: collapse;}
    .resource-available .table-responsive .table > thead > tr > th {vertical-align: bottom;border: 1px solid #ebeff2;text-align: center}
    .resource-available .table-responsive .table > tbody {color: #797979;}
    .resource-available .table-responsive .table > tbody > tr > td, .table > thead > tr > th {padding: 8px;line-height: 1.42857143;vertical-align: top;border: 1px solid #ebeff2;text-align: center}
    .resource-available .table-responsive .table > tbody > tr > td.resource-booked{padding:0;width:100%; border:1px solid #ccc;background: #EEE;overflow:hidden}
    .resource-available .table-responsive .table > tbody > tr > td > .booked-percentage{background: #EA3939;height:35px}
    .resource-available .table-responsive .table > tbody > tr > td > .leave-percentage{background: #ccc;height:35px}
    .resource-available .table-responsive .table > tbody > tr > td > .available-percentage{background: #77C159;height:35px}
    .btn.nxt-btn{margin-bottom: 20px}
    .btn.prev-btn{margin-left: 20px;margin-bottom: 20px}
    .resource-available .width-100{width:100%}
    .resource-available .width-20{width:20%; margin: 0 auto}
    .resource-available .table-responsive .table > tbody > tr > td strong{font-size:12px;}
    .resource-available .table-responsive .table > tbody > tr > td:first-child, .resource-available .table-responsive .table > thead > tr > th:first-child {
        width: 90px;}
    .resource-available .table-responsive .table{table-layout: fixed;}
</style>
<div class="row resource-available">
    <?php echo $this->element('analytics_header'); ?>
    <div class="resr-filters" style="margin-top:15px">
        <div class="width-20 fl" style="margin: 0 10px 0;">
            <div class="input select">
                <select id="select_projects" class="form-control">
                    <option value="all"><?php echo __("All Project"); ?></option>
                </select>
            </div>
        </div>
        <div class="width-20 fl" style="margin: 0 10px 0;">
            <div class="input select">
                <select id="select_users" class="form-control">
                    <option value=""><?php echo __("Select Users"); ?></option>
                </select>
            </div>
        </div>
        <div style="clear:both"></div>
    </div>
    <center>
        <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." />
        <br />
        <p><?php echo __("Please wait, while I am searching for the availability of resources"); ?>.</p>
    </center>
    <div id="resource_availability_tbl" class="table-responsive">

    </div>
</div>
<script type="text/javascript">
    $(function () {
        $(document).on('change', '#select_users', function (event) {
            var user = new Array();
            if ($(this).val()) {
                user.push($(this).val())
                loadAvailabilityData('', '', user, $('#select_projects').val())
            } else {
                getAll_projects($('#select_projects').val())
            }
        });
        $(document).on('change', '#select_projects', function (event) {
            if ($(this).val() == "all") {
                $('#select_users').val('')
            }
            getAll_user($(this).val())
        });
        $(document).on('click', '.resrs-hours', function (event) {
            var book_stats = $(this).attr('data-bookstatus');
            rsrsDetails(book_stats, $(this).attr('data-usrid'), $(this).attr('data-date'));
        });
        getAll_projects('');
    });

    function rsrsDetails(book_stats, user_id, date) {
        if (book_stats) {
            if (DATEFORMAT == 2) {
                var date_format = 'DD MMM, YYYY';
            } else {
                var date_format = 'MMM DD, YYYY';
            }
            openPopup();
            $('#resource_notavailable_hrss').html('Booked On Tasks (' + moment(date, 'YYYY-MM-DD').format(date_format) + ')');
            $('#inner_resource_notavailable_hrs').html('');
            $(".resource_notavailable_hrs").show();
            $(".popup_bg").css({
                "width": '750px'
            });
            var not_available_ajax = HTTP_ROOT + 'Timelog/logTimes/getBookedhourreports';
            $.ajax({
                url: not_available_ajax,
                type: 'POST',
                dataType: 'html',
                data: {
                    user_id: user_id,
                    date: date
                }
            })
                    .done(function (res) {
                        $('.loader_dv').hide();
                        $('#inner_resource_notavailable_hrs').html(res);
                        $('[rel=tooltip]').tipsy({
                            gravity: 's',
                            fade: true
                        });
                    });
        }
    }

    function showOverloaddet(obj) {
        var user_id = $(obj).attr('data-userid');
        var date = $(obj).attr('data-date');
        closePopup();
        openPopup();
        $('#ovrld-date,#ovrld-user').val('');
        $(".nw-pr-overload").show();
        $('#inner_mlstn-nw-pr-overload').html('');
        $('#header_nw-pr-overload').html('Overload Details (' + moment(date).format('MMM DD, YYYY') + ')');
        $("#addeditMlst-nw-pr-overload").show();
        $(".popup_bg").css({
            "width": '750px'
        });
        $.ajax({
            url: HTTP_ROOT + 'Timelog/logTimes/getOverloads',
            type: 'POST',
            dataType: 'html',
            data: {
                user_id: user_id,
                date: moment(date, 'MMM DD, YYYY').format('YYYY-MM-DD')
            }
        })
                .done(function (res) {
                    $('.loader_dv').hide();
                    $('#inner_mlstn-nw-pr-overload').html(res);
                    $('[rel=tooltip]').tipsy({
                        gravity: 's',
                        fade: true
                    });
                });
    }

    function getAll_projects(proj_id) {
        $.getJSON(HTTP_ROOT + 'Timelog/logTimes/getProjs', {
            projUniq: proj_id || 'all'
        }, function (json, textStatus) {
            $("#select_projects").find("option:gt(0)").remove();
            $.each(json, function (index, val) {
                $('#select_projects').append($('<option>', {
                    value: index,
                    text: val
                }));
            });
            getAll_user('all')
        });
    }

    function closePopupOv() {
        var date = $('#ovrld-date').val();
        var user_id = $('#ovrld-user').val();
        $(".popup_overlay").css({
            display: "none"
        });
        $(".popup_bg").css({
            display: "none"
        });
        $(".sml_popup_bg").css({
            display: "none"
        });
        $(".cmn_popup").hide();
        rsrsDetails(1, user_id, date);
    }

    function getAll_user(proj_id) {
        $.getJSON(HTTP_ROOT + 'Timelog/logTimes/getProjusers', {
            projUniq: proj_id
        }, function (json, textStatus) {
            users = new Array();
            $("#select_users").find("option:gt(0)").remove();
            $.each(json, function (index, val) {
                $('#select_users').append($('<option>', {
                    value: val.User.id,
                    text: val.User.name
                }));
                users.push(val.User.id);
            });
            loadAvailabilityData('', '', users, $('#select_projects').val())
        });
    }

    function loadAvailabilityData(start_date, last_date, user_id, proj_id) {
        var url = HTTP_ROOT + 'Timelog/logTimes/ajax_resource_availability';
        $.post(url, {
            start_date: start_date,
            last_date: last_date,
            user_id: user_id,
            proj_id: proj_id
        }, function (res) {
            $('#resource_availability_tbl').html('');
            $('#resource_availability_tbl').prev('center').hide();
            $('#resource_availability_tbl').show();
            $('#resource_availability_tbl').html(res);
            $('[rel=tooltip]').tipsy({
                gravity: 's',
                fade: true
            });
        });
    }

    function nextMonthData() {
        $('#resource_availability_tbl').hide();
        $('#resource_availability_tbl').prev('center').show();
        var start_date = $('#last_date').val();
        var user = new Array();
        if ($("#select_users").val()) {
            user.push($("#select_users").val())
        } else {
            $("#select_users").find("option:gt(0)").each(function (index, el) {
                user.push($(this).val())
            });
        }
        loadAvailabilityData(start_date, '', user, $('#select_projects').val());
    }

    function prevMonthData() {
        $('#resource_availability_tbl').hide();
        $('#resource_availability_tbl').prev('center').show();
        var last_date = $('#start_date').val();
        var user = new Array();
        if ($("#select_users").val()) {
            user.push($("#select_users").val())
        } else {
            $("#select_users").find("option:gt(0)").each(function (index, el) {
                user.push($(this).val())
            });
        }
        loadAvailabilityData('', last_date, user, $('#select_projects').val());
    }

    function applyForVacation(user_id, start_date) {
        if (DATEFORMAT == 2) {
            var date_format = 'DD MMM, YYYY';
        } else {
            var date_format = 'MMM DD, YYYY';
        }
        openPopup();
        $('.new_leave').show();
        $('.loader_dv').hide();
        $('#inner_leave').show();
        $('#applicant').val(user_id);
        $('#leave_description').val('');
        $('#leave_start_date').val(moment(start_date, 'YYYY-MM-DD').format(date_format));
        $("#leave_start_date").datepicker("option", "minDate", moment(start_date, 'YYYY-MM-DD').format(date_format));
        $("#leave_end_date").datepicker("option", "minDate", moment(start_date, 'YYYY-MM-DD').format(date_format));
        $('#leave_end_date').val('');
    }

    function updateVacation(id) {
        openPopup();
        $('.new_leave').show();
        $('#inner_leave').hide();
        $('.loader_dv').show();
        var url = HTTP_ROOT + 'Timelog/logTimes/update_vacation';
        $.post(url, {
            id: id
        }, function (res) {
            if (res) {
                $('#inner_leave').html(res);
                $('#inner_leave').show();
                $('.loader_dv').hide();
            }
        });
    }

    function validateLeaveForm() {
        $('#btn_leave').hide();
        $('#ldr').show();
        var start_date = trim($('#leave_act_start_date').val());
        var end_date = trim($('#leave_act_end_date').val());
        var reason = trim($('#leave_description').val());
        var user_id = trim($('#applicant').val());
        var id = trim($('#leave_id').val());
        var done = 1;
        var msg = "";
        if (start_date === '') {
            msg = 'Start date can not be left blank';
            // showTopErrSucc('error', 'Start date can not be left blank');
            $('#leave_start_date').focus();
            done = 0;
        }
        if (end_date === '') {
            msg = 'End date can not be left blank';
            // showTopErrSucc('error', 'End date can not be left blank');
            $('#leave_end_date').focus();
            done = 0;
        }
        start_date = moment(start_date, 'YYYY-MM-DD').format('YYYY-MM-DD');
        end_date = moment(end_date, 'YYYY-MM-DD').format('YYYY-MM-DD');
        if (end_date < start_date) {
            msg = 'End date cannot be before start date';
            // showTopErrSucc('error', 'End date cannot be before start date');
            $('#leave_end_date').focus();
            done = 0;
        }
        if (done) {
            var url = HTTP_ROOT + 'Timelog/logTimes/save_vacation';
            $.post(url, {
                id: id,
                start_date: start_date,
                end_date: end_date,
                reason: reason,
                user_id: user_id
            }, function (res) {
                if (res.success) {
                    showTopErrSucc('success', 'Leave application is successfully saved.');
                    window.location.reload();
                    closePopup();
                    $('#btn_leave').hide();
                    $('#ldr').show();
                } else {
                    showTopErrSucc('error', 'Leave application could not be saved.Please try again');
                }
            }, 'json');
        } else {
            showTopErrSucc('error', msg);
            $('#btn_leave').show();
            $('#ldr').hide();
        }
    }

    function cancelLeave() {

        var id = trim($('#leave_id').val());
        if (confirm("Are you sure to cancel leave ?")) {
            $('#btn_leave').hide();
            $('#ldr').show();
            var url = HTTP_ROOT + 'Timelog/logTimes/cancel_vacation';
            $.post(url, {'leave_id': id}, function (data) {
                if (data == 1) {
                    showTopErrSucc('success', 'Leave application is successfully canceled.');

                } else {
                    showTopErrSucc('error', 'Unable to cancel the leave application.');
                }
                window.location.reload();
                closePopup();
                $('#btn_leave').hide();
                $('#ldr').show();
            });
        }
    }
</script>