<script type="text/javascript" language="javascript" class="init" src="http://<?php echo $_SERVER['HTTP_HOST'] . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH; ?>/js/userSettings-control.js">
</script>
<script type="text/javascript" language="javascript" class="init" src="http://<?php echo $_SERVER['HTTP_HOST'] . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH; ?>/js/userMessages-control.js">
</script>

<link href="http://<?php echo $_SERVER['HTTP_HOST'] . __SERVICENAME_PORTALNEW; ?>/_assets/css/portalnew.css" rel="stylesheet">
<link href="http://<?php echo $_SERVER['HTTP_HOST'] . __SERVICENAME_PORTALNEW; ?>/_assets/css/messages-list.css" rel="stylesheet">
<link href="http://<?php echo $_SERVER['HTTP_HOST'] . __SERVICENAME_PORTALNEW; ?>/_assets/css/messages-push.css" rel="stylesheet">

<script type="text/javascript" language="javascript" class="">
    //
    var sessionID = '<?php echo session_id(); ?>';
    var userID_session = '<?php echo $_SESSION['id']; ?>';
    var userLogin_session = '<?php echo $_SESSION['login']; ?>';
    // 
    // ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
    // Функция проверки переменной на значение
    //
    function checkVal(val) {
        if (typeof val !== "undefined" && val !== "" && val !== null) {
            return 1;
        } else {
            return 0;
        }
    }
    // 
    // ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
    // Функция проверки текущей PHP сессии
    //
    function ajaxRequest_checkSessionAsync(sessionID) {
        var result = false;
        $.ajax({
            async: false,
            cache: false,
            type: "post",
            url: "<?php echo __ROOT . __SERVICENAME_PORTALNEW; ?>/_assets/user-phpscript/ajaxReq-checkPHPSession.php",
            data: {
                sessionID: sessionID
            },
            success: function(response) {
                res = response.replace(new RegExp("\\r?\\n", "g"), "");
                if (res == '1') {
                    result = true;
                } else {
                    result = false;
                }
            }
        });
        return result;
    }
    // 
    // ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
    // Функция проверки текущей PHP сессии
    //
    var reqField_checkSession = {
        chksession: function(response) {}
    };

    function ajaxRequest_checkSession(data, responseHandler) {
        var response = false;

        // Fire off the request to /form.php
        request = $.ajax({
            url: "<?php echo __ROOT . __SERVICENAME_PORTALNEW; ?>/_assets/user-phpscript/ajaxReq-checkPHPSession.php",
            type: "post",
            cache: false,
            data: {
                sessionID: data
            },
            success: reqField_checkSession[responseHandler]
        });
        // Callback handler that will be called on success
        request.done(function(response, textStatus, jqXHR) {
            res = response.replace(new RegExp("\\r?\\n", "g"), "");
            if (res == '0') {
                // $("#sessionStatus_msg").html("Ваша сессия закончена. Войдите в систему снова.");
                // $("#sessionStatus-modalBox").modal("show");
                console.log('ajaxRequest_checkSession >>>', res, 'Сессия закончена');
            } else if (res == '-1') {
                // $("#sessionStatus_msg").html("Вы в системе, но ваша сессия устарела. Просто обновите текущую страницу.");
                // $("#sessionStatus-modalBox").modal("show");
                console.log('ajaxRequest_checkSession >>>', res, 'Сессия устарела');
            } else {
                console.log('ajaxRequest_checkSession >>>', res,
                    'Сессия крепка и стабильна! :)');
            }
        });
        // Callback handler that will be called on failure
        request.fail(function(jqXHR, textStatus, errorThrown) {
            console.error(
                "The following error occurred: " +
                textStatus, errorThrown
            );
        });
        // Callback handler that will be called regardless
        // if the request failed or succeeded
        request.always(function() {});
    }

    setInterval(function() {
        ajaxRequest_checkSession(sessionID, 'chksession');
        if (!ajaxRequest_checkSessionAsync(sessionID)) {
            console.log('ajaxRequest_checkSessionAsync', ajaxRequest_checkSessionAsync(sessionID));
            $('#modal-alarmMessage .modal-message').text(
                'Ваша сессия устарела. Закройте это окно и авторизуйтесь в сервисе снова. Если вы уже повторно авторизовались в этом браузере (например в другой вкладке), то эта страница просто перезагрузится в новой сессии.'
            );
            $('#modal-alarmMessage .modal-footer button').text('Закрыть и авторизоваться');
            $('#modal-alarmMessage').modal('show');
        } else {
            $('#modal-alarmMessage .modal-message').text('');
            $('#modal-alarmMessage .modal-footer button').text('');
            $('#modal-alarmMessage').modal('hide');
        }
    }, 30000);
</script>

<div id="portalnew" class="">
    <?php
    if (isset($_SESSION['password']) && isset($_SESSION['login'])) {
        if (checkUserAuthorization_defaultDB($_SESSION['login'], $_SESSION['password']) == -1) {
            if (isset($_GET['mode']) && $_GET['mode'] == "checkup" && isset($_GET['type']) && $_GET['type'] == "user" && !empty($_GET['secretkey'])) {
                include($_SERVER['DOCUMENT_ROOT'] . "/ism/php/examples/simple/checkup/checkup-main-usercheck.php");
            } else {
                // Редирект на главную страницу
                echo '<meta http-equiv="refresh" content="0; url=' . __ROOT . '">';
            }
        } else {
            // При удачном входе пользователю выдается все, что расположено НИЖЕ звездочек
            // ************************************************************************************
            if (checkUserRestrictions_defaultDB($_SESSION['id'], 'portalnew', 2, 0) == 1) {
                if (isset($_GET['mode']) && $_GET['mode'] == "profile" && isset($_GET['userid'])) {
                    include(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/profile/profile-main.php');
                } elseif (isset($_GET['mode']) && $_GET['mode'] == "changeslog") {
                    include(__DIR_ROOT . __SERVICENAME_PORTALNEW .  __PORTAL_MAIN_WORKPATH . '/changeslog/portalnew-changeslog.php');
                } else {
                    echo '<div id="portalnew-top" class="text-start">';
                    include "portalnew-topblock.php";
                    echo '</div>';
                    echo '<div id="portalnew-center" class="d-flex flex-column w-100">';
                    include __DIR_ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_WORKPATH . "/portalnew-main.php";
                    echo '</div>';
                    echo '<div id="portalnew-bottom" class="">';
                    echo '</div>';
                }
    ?>
</div>

<div aria-live="polite" aria-atomic="true" class="toasts-block position-fixed" style="z-index:9; opacity:1; top:110px; left:0">
    <!-- Разместите его: 
    `.toast-container` для интервала между тостами
    `top-0` и `end-0` для размещения всплывающих уведомлений в правом верхнем углу
    `.p-3`, чтобы тосты не прилипали к краю контейнера -->
    <div class="p-3">

        <div id="portalnew-toast-1" class="d-none">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-toast-id="" data-autohide="false">
                <div class="toast-header">
                    <strong class="mr-auto">
                        <div class="header-text"></div>
                    </strong>
                    <button type="button" class="close" data-dismiss="toast"><span class="text-white small">x</span></button>
                </div>
                <div class="toast-body">
                    <div class="main-text mb-1"></div>
                    <div class="sub-text1"></div>
                    <div class="sub-text2"></div>
                    <div class="sub-text3"></div>
                    <div class="special-text my-1"></div>
                    <div class="link-text1"></div>
                    <div class="link-text2"></div>
                    <div class="undermain-text">
                        <div class="toast-timestamp text-right"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="portalnew-toast-2" class="d-none">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-toast-id="" data-autohide="false">
                <div class="toast-header">
                    <strong class="mr-auto">
                        <div class="header-text"></div>
                    </strong>
                    <button type="button" class="close" data-dismiss="toast"><span class="text-white small">x</span></button>
                </div>
                <div class="toast-body">
                    <div class="main-text mb-1"></div>
                    <div class="sub-text1"></div>
                    <div class="sub-text2"></div>
                    <div class="sub-text3"></div>
                    <div class="special-text my-1"></div>
                    <div class="link-text1"></div>
                    <div class="link-text2"></div>
                    <div class="undermain-text">
                        <div class="toast-timestamp text-right"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="portalnew-toast-3" class="d-none">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-toast-id="" data-autohide="false">
                <div class="toast-header">
                    <strong class="mr-auto">
                        <div class="header-text"></div>
                    </strong>
                    <button type="button" class="close" data-dismiss="toast"><span class="text-white small">x</span></button>
                </div>
                <div class="toast-body">
                    <div class="main-text mb-1"></div>
                    <div class="sub-text1"></div>
                    <div class="sub-text2"></div>
                    <div class="sub-text3"></div>
                    <div class="special-text my-1"></div>
                    <div class="link-text1"></div>
                    <div class="link-text2"></div>
                    <div class="undermain-text">
                        <div class="toast-timestamp text-right"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="portalnew-toast-4" class="d-none">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-toast-id="" data-autohide="false">
                <div class="toast-header">
                    <strong class="mr-auto">
                        <div class="header-text"></div>
                    </strong>
                    <button type="button" class="close" data-dismiss="toast"><span class="text-white small">x</span></button>
                </div>
                <div class="toast-body">
                    <div class="main-text mb-1"></div>
                    <div class="sub-text1"></div>
                    <div class="sub-text2"></div>
                    <div class="sub-text3"></div>
                    <div class="special-text my-1"></div>
                    <div class="link-text1"></div>
                    <div class="link-text2"></div>
                    <div class="undermain-text">
                        <div class="toast-timestamp text-right"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="portalnew-toast-5" class="d-none">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-toast-id="" data-autohide="false">
                <div class="toast-header">
                    <strong class="mr-auto">
                        <div class="header-text"></div>
                    </strong>
                    <button type="button" class="close" data-dismiss="toast"><span class="text-white small">x</span></button>
                </div>
                <div class="toast-body">
                    <div class="main-text mb-1"></div>
                    <div class="sub-text1"></div>
                    <div class="sub-text2"></div>
                    <div class="sub-text3"></div>
                    <div class="special-text my-1"></div>
                    <div class="link-text1"></div>
                    <div class="link-text2"></div>
                    <div class="undermain-text">
                        <div class="toast-timestamp text-right"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="searchingOutput-modal" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Поиск от GenaGPT&nbsp;<sup>&trade;</sup></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="search-output-container" class="mb-1"></div>
                <div id="search-pagination-container" class="mt-1"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="loadedData-help-modal" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="loadedData-help-output" class=""></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="searchingSettings-modal" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="">
        <div class="modal-content modal-customstyle-1">
            <div class="modal-body">
                <h3 class="text-light">Настройки поиска</h3>
                <div id="searchingSettings-block" class="mb-4">
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" id="searchSwitch-mail" data-dbfield="enable_searchingMail" value="">
                        <label class="custom-control-label" for="searchSwitch-mail">Искать в
                            Почте<span id="mail_userRestr" class="ml-2"></span></label>
                    </div>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" id="searchSwitch-dog" data-dbfield="enable_searchingDog" value="">
                        <label class="custom-control-label" for="searchSwitch-dog">Искать в
                            Договоре, включая
                            этапы<span id="dog_userRestr" class="ml-2"></span></label>
                    </div>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" disabled id="searchSwitch-sp" data-dbfield="enable_searchingSP" value="">
                        <label class="custom-control-label" for="searchSwitch-sp">Искать в
                            Справочнике (скоро)</label>
                    </div>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" disabled id="searchSwitch-hr" data-dbfield="enable_searchingHR" value="">
                        <label class="custom-control-label" for="searchSwitch-hr">Искать в Кадрах
                            (скоро)</label>
                    </div>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" disabled id="searchSwitch-ism" data-dbfield="enable_searchingISM" value="">
                        <label class="custom-control-label" for="searchSwitch-ism">Искать в ИСМ/СМК
                            (скоро)</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="userSettings-modal" data-keyboard="false" data-backdrop="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" style="margin-right:-0.5rem !important; margin-top:7.5rem !important">
        <div class="modal-content modal-customstyle-1">
            <div class="modal-body">
                <h3 class="text-light">Настройки главной страницы Портала</h3>
                <div id="userSettings-block" class="mb-4" style="">
                    <div class="custom-control custom-switch mb-3" style="">
                        <input type="checkbox" class="custom-control-input" id="userSettingsSwitch-usePush" data-dbfield="use_pushMessages" value="">
                        <label class="custom-control-label" for="userSettingsSwitch-usePush">Разрешить
                            push-уведомления</label>
                    </div>
                    <div class="custom-control custom-switch mb-3" style="">
                        <input type="checkbox" class="custom-control-input" id="userSettingsSwitch-showTopblock" data-dbfield="mainPageUI_showTopblock" value="">
                        <label class="custom-control-label" for="userSettingsSwitch-showTopblock">Показывать верхний
                            блок</label>
                    </div>
                    <div class="custom-control custom-switch mb-3" style="">
                        <input type="checkbox" class="custom-control-input" id="userSettingsSwitch-showLeftBottomblock" data-dbfield="mainPageUI_showLeftBottomblock" value="">
                        <label class="custom-control-label" for="userSettingsSwitch-showLeftBottomblock">Показывать блок
                            слева от "Все сервисы"</label>
                    </div>
                    <div class="custom-control custom-switch mb-3" style="">
                        <input type="checkbox" class="custom-control-input" id="userSettingsSwitch-showRightBottomblock" data-dbfield="mainPageUI_showRightBottomblock" value="">
                        <label class="custom-control-label" for="userSettingsSwitch-showRightBottomblock">Показывать
                            блок справа от "Все сервисы"</label>
                    </div>
                    <div class="custom-control custom-switch mb-3" style="">
                        <input type="checkbox" class="custom-control-input" id="userSettingsSwitch-showCloudblock" data-dbfield="mainPageUI_showCloudblock" value="">
                        <label class="custom-control-label" for="userSettingsSwitch-showCloudblock">Показывать блок
                            "Облако" внизу (доступ к файлам Облака сохраняется)</label>
                    </div>
                    <div class="custom-control custom-switch" style="">
                        <input type="checkbox" class="custom-control-input" id="userSettingsSwitch-useLightTheme" data-dbfield="use_lightTheme">
                        <label class="custom-control-label" for="userSettingsSwitch-useLightTheme">Светлая тема
                            (страница сразу обновится)</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ListMessages-modal" data-keyboard="false" data-backdrop="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable fixed-top" style="margin-right:-0.5rem !important; margin-top:0.5rem !important; max-height: calc(100% - 1rem) !important">
        <div class="modal-content modal-customstyle-1" style="max-height:100% !important">
            <div class="modal-body">
                <h3 class="text-light">Ваши уведомления</h3>
                <div id="listMessages-output" class=""></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="portalCloud-listFiles-modal" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <table id="table-portalCloudFiles" class="table table-borderless table-striped mb-3" cellspacing="0" width="100%">
                    <thead class="thead-dark" style="display:none">
                        <tr>
                            <th>Файл</th>
                            <th>Описание</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
                <p class="text-info small text-left"><i class="fa-solid fa-exclamation mr-2"></i>Чтобы изменить
                    комментарий, просто кликните прям на тексте комментария - откроется поле
                    редактирования. Кликнув вне
                    поля - вы сохраните изменения.</p>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-alarmMessage" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modal-alarmMessage-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow border-0 bg-white text-dark" style="font-family:'Stolzl Book',sans-serif">
            <div class="modal-body pb-0">
                <div class="container-fluid">
                    <p class="modal-message text-center" style="font-size:1.0rem"></p>
                </div>
            </div>
            <div class="modal-footer justify-content-center border-top-0 pt-0">
                <button type="button" class="btn btn-danger text-white btn-sm" data-dismiss="modal" onclick="location.reload()"></button>
            </div>
        </div> <!-- end of modal content -->
    </div>
</div> <!-- end of modal -->


<div class="modal fade" id="modal-spTel" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modal-spTel-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow border-0 bg-white text-dark" style="font-family:'Stolzl Book',sans-serif">
            <div class="modal-body pb-0">
                <div class="container-fluid">
                    <p id="spTel-linkToDownload-info" class="modal-message text-center" style="font-size:0.75rem"></p>
                    <p id="spTel-linkToDownload" class="modal-message text-center" style="font-size:1.0rem"></p>
                </div>
            </div>
            <div class="modal-footer justify-content-center border-top-0 pt-0">
                <button type="button" class="btn btn-danger text-white btn-sm" data-dismiss="modal" onclick="close()">Закрыть окно</button>
            </div>
        </div> <!-- end of modal content -->
    </div>
</div> <!-- end of modal -->

<?php
            } else {
                include __DIR_ROOT . __SERVICENAME_PORTALNEW . "/php/examples/simple/main/main/common-includes/errpage-nopermissions.php";
            }
            // ************************************************************************************
            // При удачном входе пользователю выдается все, что расположено ВЫШЕ звездочек
        }
    } else {
        if (isset($_GET['mode']) && $_GET['mode'] == "checkup" && isset($_GET['type']) && $_GET['type'] == "user" && !empty($_GET['secretkey'])) {
            include($_SERVER['DOCUMENT_ROOT'] . "/ism/php/examples/simple/checkup/checkup-main-usercheck.php");
        } else {
            // Редирект на главную страницу
            echo '<meta http-equiv="refresh" content="0; url=' . __ROOT . '">';
        }
    }
?>
<script type="text/javascript" language="javascript" class="init">
    $(window).on("load", function() {

        $("#listMessages-icon").click(function() {
            $('#ListMessages-modal>.modal-dialog').css({
                'max-width': '500px',
            });
            ajaxRequest_getListMessages('getListMessages');
        });
        $("#userSettings-icon").click(function() {
            getUserSettings();
            $('#userSettings-modal>.modal-dialog').css({
                'max-width': '500px',
            });
            $('#userSettings-modal').modal('show');
        });
    });
    // 
    // 
    $(document).ready(function() {

        var userID_session = '<?php echo $_SESSION['id']; ?>';
        var userLogin_session = '<?php echo $_SESSION['login']; ?>';

        $('#switchTheme-icon').on('click', function(e) {
            var useLightTheme = <?php echo $use_lightTheme; ?>;
            var switchTheme = $('#userSettings-block input[id="userSettingsSwitch-useLightTheme"]');
            setfield = switchTheme.attr('data-dbfield');
            setval = (useLightTheme === 1) ? 0 : 1;
            ajaxRequest_saveUserSettings(setfield, setval, 'saveUserSettings');
        });

        setInterval(function() {
            use_pushMessages = <?php echo $use_pushMessages; ?>;
            console.log("getListPush!", "checkUnreadMessages!", "getListPush >>>", use_pushMessages);
            ajaxRequest_getListPush('getListPush');
            ajaxRequest_checkUnreadMessages('checkUnreadMessages');
        }, 10000);

        $(".toast button.close").click(function() {
            console.log("Toast closed!", "setMessageToChecked-1!", $(this).closest(
                '.toast').attr(
                "data-toast-id"));
            let toastid = $(this).closest('.toast').attr("data-toast-id");
            ajaxRequest_setMessageToChecked(toastid, 'setMessageToChecked');
        });

        $('#searchingSettings-modal .custom-switch>input, #userSettings-modal .custom-switch>input').on(
            'change',
            function(e) {
                $(this).val($(this).is(':checked') ? 1 : 0);
                setfield = $(this).attr('data-dbfield');
                setval = $(this).val();
                ajaxRequest_saveUserSettings(setfield, setval, 'saveUserSettings');
            });

        $(document).on('click', '.message-single.unread', function() {
            let msgid = $(this).attr('data-msg-id');
            console.log("setMessageToChecked-2!", msgid);
            ajaxRequest_setMessageToChecked(msgid, 'setMessageToChecked');
            ajaxRequest_checkUnreadMessages('checkUnreadMessages');
        });

        ajaxRequest_getStaffNews('getStaffNews');

        ajaxRequest_getMarqeeData('getMarqeeData');

    });
</script>