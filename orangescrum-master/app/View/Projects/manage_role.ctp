<style type="text/css">
    .os_plus a .ctask_icn {opacity: 0;transform: rotate(225deg);visibility: hidden;}
    .os_plus.prj_btn {display: none;position: absolute;top: -60px;left: 0px;padding: 0px 4px 10px 4px;}
    .os_plus.mdl_btn {display: none;position: absolute;top: -115px;left: 0px;padding: 0px 4px 10px 4px;}
    .os_plus.actn_btn {display: none;position: absolute;top: -170px;left: 0px;padding: 0px 4px 10px 4px;}
    .os_plus.mdl_btn a, .os_plus.actn_btn a, .os_plus.prj_btn a {height: 47px;width: 47px;}
    .crt_task_btn_btm:hover .os_plus.prj_btn, .crt_task_btn_btm:hover .os_plus.mdl_btn, .crt_task_btn_btm:hover .os_plus.actn_btn{display: block;}
    /* ----------Accordion Starts--------------*/
    .u-vmenu ul li {width:100%;list-style-type: none;margin:0;padding:0;position:relative;}
    .u-vmenu ul ul{display: none;}
    /*.u-vmenu > ul > li {background-color:#f5f5f5;border:1px solid #ddd;margin-bottom:10px;}*/
    .u-vmenu ul li a {height:auto;line-height: 30px;display: block;font-size: 14px;color: #82846f;text-decoration: none;outline: none;}
    .u-vmenu > ul > li > a {height: 42px;line-height: 42px;padding-left: 30px;font-weight: 600;position:relative;background-color:#f5f5f5 !important;border:1px solid #ddd;margin-bottom:10px;}
    .u-vmenu > ul > li > ul > li > a {height:auto;padding:10px; background-image: none !important;background-color:rgb(148,179,236) !important;margin-bottom:10px;display:inline-block;color:#fff; line-height:20px;border-radius:5px}
    .u-vmenu > ul > li > ul > li > ul > form > li > a:not(.btn) {padding: 10px;background-image: none !important;}
    .u-vmenu > ul > li > ul > li > ul > form > li > a[data-option='on'] {  background-color: rgb(148, 179, 236) !important;background-image: none !important;border-radius:5px;color: #fff;display: inline-block;line-height: 16px;padding:5px 20px;}
    .u-vmenu > ul > li > ul > li > ul{background-color:#f5f5f5; margin-bottom: 10px;position:relative;padding-bottom: 20px;padding-top: 20px;}
    .u-vmenu > ul > li > ul > li > ul:before{content:'';border-left:1px solid #ccc;position:absolute;left:20px;top:0;height:100%;display:block}
    .u-vmenu > ul > li > ul > li > ul > form > li:before{content:'';width:5px;height:5px;background:#ccc;border-radius:50%;display:block;position:absolute;
    top:0;left:-22px;top:25px;bottom:0;}
    .u-vmenu > ul > li > ul > li > ul > form > li > ul{margin:20px 0 10px}
    .u-vmenu > ul > li > ul > li > ul > form > li > ul > li{display:inline-block;width:45%;margin:5px 0}
    .u-vmenu > ul > li > ul > li > ul > form > li > ul > li > a .action{width:250px;display:inline-block;}
    .u-vmenu > ul > li > ul > li > ul > form > li > ul > li > a span{margin-left:20px;}
    .u-vmenu ul li a[data-option='on']{background: url(<?php echo HTTP_ROOT; ?>img/left-arrow.png) left center no-repeat;}
    .u-vmenu ul li a[data-option='off'] {background: url(<?php echo HTTP_ROOT; ?>img/right-arrow.png) left center no-repeat;}
    /*.u-vmenu ul.1st_menu .has-children a[data-option='on']{background: url(<?php #echo HTTP_ROOT; ?>img/left-arrow.png) left center no-repeat;}
    .u-vmenu ul.1st_menu .has-children a[data-option='off'] {background: url(<?php #echo HTTP_ROOT; ?>img/right-arrow.png) left center no-repeat;}*/
    .u-vmenu  .edit-close{display:inline-block;position:absolute;right:20px;cursor:pointer}
    .u-vmenu  .edit-close span{background-image: url(<?php echo HTTP_ROOT; ?>img/role-icons.png);width:16px;height:16px;display:inline-block;}
    .u-vmenu .user{background-position:16px -185px;margin:0 10px 0}
    .u-vmenu .user:hover{background-position:16px -168px;margin:0 10px 0}
    .u-vmenu .edit{background-position:0px -31px;margin:0 10px 0}
    .u-vmenu .edit:hover{background-position:0px 3px;margin:0 10px 0}
    .u-vmenu .close{background-position:-17px 85px;margin:0 10px 0}
    .u-vmenu .close{background-position:-17px 85px;margin:0 10px 0}
    .u-vmenu .close:hover{background-position:-31px 85px;margin:0 10px 0}
    .u-vmenu .close .delete-rolegrp-div{position:absolute; z-index:999;background:#fff;font-weight:normal;width:200px;height:100px;padding:10px;border:1px solid #ccc; border-radius:5px;top:15px;right:0;display:none}
    .u-vmenu .close .delete-rolegrp-div hr{margin:0}
    .u-vmenu .close .delete-rolegrp-div span{display:block}
    .u-vmenu .close:hover .delete-rolegrp-div{display:block;}
    .u-vmenu .delete{background-position:0px 51px;margin:0 10px 0}
    .u-vmenu .delete:hover{background-position:-19px 51px;margin:0 10px 0}
    .u-vmenu .action{cursor: default}
    .u-vmenu > ul > li > ul > form > li > a{padding-left:30px}
    .u-vmenu > ul > li > ul > form > li > ul > li > a > span{width:48%;display:inline-block}
	.popup_bg{width:750px !important}
	.main-menu > ul{padding:0 10px}
	.main-menu > ul > li > ul{padding:0 15px}
	.main-menu > ul > li > ul > li > ul > li > a > span{width:65%;display:inline-block}
	.main-menu > ul > li > ul > li > ul > li > a > .action + span{width:30%;}
</style>
<div class="main-menu role-access-settings-page u-vmenu">
    <ul class="1st_menu cd-accordion-menu animated">
        <?php if(isset($roles) && !empty($roles)){ ?>
        <?php foreach ($roles as $key => $role) { ?>
            <li class="has-children">
                <a href="javascript:void(0);"><?php echo $role['Role']['role']; ?>
                    <?php /*<div class="edit-close">
                        <span class="edit" onclick="newRoleGroup('edit', <?php echo $roleGroup['RoleGroup']['id']; ?>, '<?php echo urlencode($roleGroup['RoleGroup']['name']); ?>')"></span>
                        <span class="close pr">
                            <span class="delete-rolegrp-div">
                                <div onclick="deleteRoleGroup(this, <?php echo $roleGroup['RoleGroup']['id']; ?>, 'only')">Delete Role Group Only</div>
                                <hr />
                                <div onclick="deleteRoleGroup(this, <?php echo $roleGroup['RoleGroup']['id']; ?>, 'roles')">Delete Role Group with Roles</div>
                            </span>
                        </span>
                        <span class="save-action-loader" style="display:none;">
                            <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loader"/>
                        </span>
                    </div> */ ?>
                </a>
                <ul class="sub-menu" id="info_list1">
                    <?php foreach ($modules as $moduleKey => $module) { ?>
                    <?php /*echo $this->Form->create('RoleAction', array('method' => 'POST', 'url' => array('controller' =>'roles', 'action' => 'saveProjectRoleActions'), 'name' => 'roleForm'.$role['id']));  */?>
                    <li class="has_par has-children">
                        <a href="javascript:void(0);"><?php echo ucfirst($module['Module']['name']); ?>&nbsp;&nbsp;(<?php echo (count($module['Action'])) ? ((count($module['Action']) > 1) ? count($module['Action']). ' Actions' : '1 Action') : 'No Action'; ?>)</a>
                        <ul class="sub-menu">
                            <?php foreach ($module['Action'] as $actionKey => $action) { ?>
                                <li class="has_par has-children">
                                    <a href="javascript:void(0);">
                                        <span class="action"><?php echo ucfirst($action['action']); ?></span>
                                        <span>
                                            <input name="<?php echo $action['id']; ?>" data-action-id="<?php echo $action['id']; ?>" type="checkbox" id="action_yes_<?php echo $action['id']; ?>" <?php if($role['ProjectAction'][$action['id']] == 1){?>checked="true"<?php } ?> onchange="changeNextCheckbox(this)" value="1" DISABLED <?php if($role['Role']['role'] == 'Owner'){ echo "DISABLED" ; } ?> /> <label for="action_yes_<?php echo $action['id']; ?>">Yes</label> &nbsp;&nbsp;
                                            <input name="<?php echo $action['id']; ?>" data-action-id="<?php echo $action['id']; ?>" type="checkbox" id="action_no_<?php echo $action['id']; ?>" <?php if($role['ProjectAction'][$action['id']] == 0){?>checked="true"<?php } ?>  onchange="changePrevCheckbox(this)" value="0" DISABLED <?php if($role['Role']['role'] == 'Owner'){ echo "DISABLED" ; } ?>/> <label for="action_no_<?php echo $action['id']; ?>">No</label>
                                        </span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
					<?php /*
                    <li>
                        <a class="fr btn btn_blue save-action-btn" href="javascript:void(0);" onclick="saveRoleActionPermissions(this, <?php echo $role['Role']['id']; ?>, '<?php echo $projectId; ?>')">Save Changes</a>
                        <span class="save-action-loader fr" style="display:none;">
                            <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loader"/>
                        </span>
                    </li> */ ?>
                    <div class="cb"></div>
                    <?php //echo $this->Form->end(); ?>
                </ul>
            </li>
        <?php } ?>
        <?php } else { ?>
            <li class="has-children">
                <a href="javascript:void(0);"> <?php echo __("No roles found"); ?>.</a>
            </li>
        <?php } ?>
    </ul>
</div>
<input type="hidden" name="prjct_name" id="mng_prct_name" value="<?php echo $project_name; ?>" >
<input type="hidden" name="prjct_id" id="mng_prct_id" value="<?php echo $projectId; ?>" >
<script type="text/javascript">
    (function ($) {
        $.fn.vmenuModule = function (option) {
            var obj, item;
            var options = $.extend({
                Speed: 220,
                autostart: true,
                autohide: 1
            }, option);
            obj = $(this);

            item = obj.find("ul").parent("li").children("a");
            item.attr("data-option", "off");

            item.unbind('click').on("click", function () {
                var a = $(this);
                if (options.autohide) {
                    a.parent().parent().find("a[data-option='on']").parent("li").children("ul").slideUp(options.Speed / 1.2,
                            function () {
                                $(this).parent("li").children("a").attr("data-option", "off");
                            })
                }
                if (a.attr("data-option") == "off") {
                    a.parent("li").children("ul").slideDown(options.Speed,
                            function () {
                                a.attr("data-option", "on");
                            });
                }
                if (a.attr("data-option") == "on") {
                    a.attr("data-option", "off");
                    a.parent("li").children("ul").slideUp(options.Speed)
                }
            });
            if (options.autostart) {
                obj.find("a").each(function () {

                    $(this).parent("li").parent("ul").slideDown(options.Speed,
                            function () {
                                $(this).parent("li").children("a").attr("data-option", "on");
                            })
                })
            }

        }
    })(window.jQuery);
    $(document).ready(function () {
        $(".u-vmenu").vmenuModule({
            Speed: 200,
            autostart: true,
            autohide: true
        });
        $(window).on('beforeunload', function(){
            var message = roleSettingsChanged == 1 ? "<?php echo __("You have modified actions, reloading the page will reset all changes"); ?>." : void 0;
            e.returnValue = message;
            return message;
        });
    });
</script>