<?php


?>

<script type="text/javascript" language="javascript" class="">
    //
    var userID_session = '<?php echo $_SESSION['id']; ?>';
    var userLogin_session = '<?php echo $_SESSION['login']; ?>';
    var userID = '<?php echo $_GET['userid']; ?>';
    var reqField_loadProfileData = {
        loadProfileData: function(response) {}
    };
    var reqField_saveProfileData = {
        saveProfileData: function(response) {}
    };
    //
    function ajaxRequest_loadProfileData(data, responseHandler) {
        var response_loadProfileData = false;

        // Fire off the request_check_sysStatus to /form.php
        request_loadProfileData = $.ajax({
            url: "http://<?php echo $_SERVER['HTTP_HOST'] . __SERVICENAME_PORTALNEW; ?>/profile/_assets/php/profile-reqAjax-getFromDB-loadProfileData.php",
            type: "post",
            cache: false,
            data: {
                userID: data
            },
            success: reqField_loadProfileData[responseHandler]
        });
        // Callback handler that will be called on success
        request_loadProfileData.done(function(response_loadProfileData, textStatus, jqXHR) {
            res_loadProfileData = response_loadProfileData.replace(new RegExp("\\r?\\n", "g"), "");
            // res_loadProfileData = response_loadProfileData;

            var host = window.location.protocol + "//" + window.location.host;

            var x = res_loadProfileData.split(' | ');
            var x1 = (x[0] != "") ? x[0].replace(/\ /g, '') : "";
            var x2 = (x[1] != "") ? x[1].replace(/\ /g, '') : "";
            var x3 = (x[2] != "") ? x[2] : "";
            var x4 = (x[3] != "") ? x[3] : "";
            // 
            var dinner_params = x1.split('///');
            var dinner_login = dinner_params[0];
            var dinner_passmd5 = dinner_params[1];
            var dinner_email = dinner_params[2];
            var dinner_mailenbl = dinner_params[3];
            var dinner_limit = dinner_params[4];
            var dinner_limitenbl = dinner_params[5];
            // 
            var profile_params = x2.split('///');
            var profile_login = profile_params[0];
            var profile_passmd5 = profile_params[1];
            var profile_emaildop = profile_params[2];
            var profile_emailcorp = profile_params[3];
            var profile_tel1 = profile_params[4];
            var profile_tel2 = profile_params[5];
            var profile_tel3 = profile_params[6];
            var profile_avatar = profile_params[7];
            // 
            var hr_params = x3.split('///');
            var hr_dolj = hr_params[0];
            var hr_office = hr_params[1];
            var hr_kodwoker = hr_params[2];
            var hr_tel = hr_params[3];
            var hr_email = hr_params[4];
            // 
            var ism_params = x4.split('///');
            var ism_dolj = ism_params[0];
            var ism_kodwoker = ism_params[1];

            console.log("dinner: " + x1);
            console.log("profile: " + x2);
            console.log("hr: " + x3);
            console.log("ism: " + x4);

            if (response_loadProfileData != "-1") {
                $('#profile-userid').html(data);
                $('#profile-userid-a').attr('href', host + '/profile/?userid=' + data);
                $('#profile-login').val(profile_login);
                $('#profile-emaildop').val(profile_emaildop);
                $('#profile-emailcorp').val(profile_emailcorp);
                (hr_office !== "") ? $(
                        '<li id="profile-card-dept" class="list-group-item"><i class="fa-solid fa-thumbtack mr-3"></i>' +
                        hr_office + '</li>')
                    .appendTo('#profile-card-info'): '';
                // $('#profile-card-dept').html(hr_office);
                (ism_dolj !== "") ? $(
                        '<li id="profile-card-dolj" class="list-group-item"><i class="fa-solid fa-briefcase mr-3"></i>' +
                        ism_dolj + '</li>')
                    .appendTo('#profile-card-info'): '';
                // $('#profile-card-dolj').html(ism_dolj);
                (profile_tel2 !== "") ? $(
                    '<li id="profile-card-tel" class="list-group-item"><i class="fa-solid fa-square-phone mr-3"></i>' +
                    profile_tel2 + ((profile_tel3 !== "") ? ', ' + profile_tel3 : '') +
                    '</li>').appendTo('#profile-card-info'): '';
                // $('#profile-card-tel').html(profile_tel2);
                (profile_emailcorp !== "") ?
                $('<li class="list-group-item"><i class="fa-solid fa-at mr-3"></i><a id="profile-card-email2-a" class="link-primary" href="mailto:' +
                        profile_emailcorp + '"><span id="profile-card-email2">' +
                        profile_emailcorp +
                        '</span></a></li>').appendTo('#profile-card-info'):
                    '';
                // $('#profile-card-email2-a').attr('href', 'mailto:' + profile_emailcorp);
                // $('#profile-card-email2').html(profile_emailcorp);
                $('#profile-tel').val(profile_tel1);
                $('#profile-worktel').val(profile_tel2);
                $('#profile-workdoptel').val(profile_tel3);

                $('#profile-telhr').val(hr_tel);
                $('#profile-emailhr').val(hr_email);

                $('div.card img').attr('src', host + '/' + profile_avatar);
                $('div.card-header > span > a.link1').attr('href', host +
                    '/hr/hr-docview.php?docview_type=details&uniqueID=' + hr_kodwoker);
                $('div.card-footer > span > a.link1').html(hr_kodwoker);

                $('#dinner-limit').html(dinner_limit);
                $('#dinner-login').val(dinner_login);
                $('#dinner-email').val(dinner_email);
                if (dinner_limitenbl == 1) {
                    if (!$('input[name="dinner-limitenbl"]').is(':checked')) {
                        $('#dinner-limitenbl').attr('checked', 'checked');
                    }
                } else {
                    if ($('input[name="dinner-limitenbl"]').is(':checked')) {
                        $('#dinner-limitenbl').removeAtt('checked');
                    }
                }

                if (dinner_mailenbl == 1) {
                    if (!$('input[name="dinner-mailenbl"]').is(':checked')) {
                        $('#dinner-mailenbl').attr('checked', 'checked');
                    }
                } else {
                    if ($('input[name="dinner-mailenbl"]').is(':checked')) {
                        $('#dinner-mailenbl').removeAttr('checked');
                    }
                }
            } else {
                $('#profile-login').val('');
                $('#profile-emailcorp').val('');
                $('#profile-emaildop').val('');
                $('#profile-tel').val('');
                $('#profile-worktel').val('');
                $('#profile-workdoptel').val('');
                $('#profile-card-dept').html('---');
                $('#profile-card-dolj').html('---');
                $('#profile-card-tel').html('---');

                $('#dinner-login').val('');
                $('#dinner-limit').html('XXX');
                $('#dinner-email').html('???');

                $('#profile-emailhr').val('');
                $('#profile-telhr').val('');
            }
        });
        // Callback handler that will be called on failure
        request_loadProfileData.fail(function(jqXHR, textStatus, errorThrown) {
            console.error(
                "The following error occurred: " +
                textStatus, errorThrown
            );
        });
        // Callback handler that will be called regardless
        // if the request_check_sysStatus failed or succeeded
        request_loadProfileData.always(function() {

        });
    }
    // 
    // Функция записи содержимого полей Профиля в БД
    // -- -- --
    //
    function ajaxRequest_saveProfileData(
        userid, userlogin_portal, userlogin_dinner, userpass, emailcorp, emaildop, mobiletel, worktel, workdoptel,
        emaildinner,
        mailingenbl,
        sendmailenbl,
        hrmobiletel,
        hremail,
        responseHandler) {
        var response_saveProfileData = false;

        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {
            keyboard: false
        });

        // Fire off the request_check_sysStatus to /form.php
        request_saveProfileData = $.ajax({
            url: "http://<?php echo $_SERVER['HTTP_HOST'] . __SERVICENAME_PORTALNEW; ?>/profile/_assets/php/profile-reqAjax-saveToDB-saveProfileData.php",
            type: "post",
            cache: false,
            data: {
                userID: userid,
                userlogin_portal: userlogin_portal,
                userlogin_dinner: userlogin_dinner,
                userpass: userpass,
                emailcorp: emailcorp,
                emaildop: emaildop,
                mobiletel: mobiletel,
                worktel: worktel,
                workdoptel: workdoptel,
                emaildinner: emaildinner,
                mailingenbl: mailingenbl,
                sendmailenbl: sendmailenbl,
                hrmobiletel: hrmobiletel,
                hremail: hremail,

            },
            success: reqField_saveProfileData[responseHandler]
        });
        // Callback handler that will be called on success
        request_saveProfileData.done(function(response_saveProfileData, textStatus, jqXHR) {
            res_saveProfileData = response_saveProfileData.replace(new RegExp("\\r?\\n", "g"), "");
            // res_loadProfileData = response_loadProfileData;

            var host = window.location.protocol + "//" + window.location.host;

            var x = res_saveProfileData.split(' | ');
            console.log("x: " + x);

            var x1 = (x[0] != "") ? x[0].replace(/\ /g, '') : "";
            var x2 = (x[1] != "") ? x[1].replace(/\ /g, '') : "";
            var x3 = (x[2] != "") ? x[2] : "";
            var x4 = (x[3] != "") ? x[3] : "";
            console.log("save: " + x1 + " / " + x2 + " / " + x3 + " / " + x4);

            $('#saveProfile-status').html("Данные профиля успешной сохранены");

            //myModal.show();
            if (userpass !== "" || userlogin_portal !== userLogin_session) {
                window.location.assign(host).reload(true);
            } else {
                window.location.reload();
            }
            //ajaxRequest_loadProfileData(userid);
        });
        // Callback handler that will be called on failure
        request_saveProfileData.fail(function(jqXHR, textStatus, errorThrown) {
            console.error(
                "The following error occurred: " +
                textStatus, errorThrown
            );
        });
        // Callback handler that will be called regardless
        // if the request_check_sysStatus failed or succeeded
        request_saveProfileData.always(function() {

        });
    }
    //
</script>


<div class="row gx-5">
    <div class="col-md-7">
        <h3 class="mb-3 title">Карточка пользователя</h3>
        <div class="card mb-3 shadow-sm" style="width: 100%;">
            <div class="row g-0">
                <div class="col-md-12">
                    <div class="card-header">
                        <label for="profile-userid">Постоянный URL вашего профиля</label>
                        <div class="input-group">
                            <span><a id="profile-userid-a" href="#" class="link-primary">profile/?userid=<span id="profile-userid"></span></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 align-self-center">
                    <!-- <svg class="bd-placeholder-img card-img-top" width="250" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Image cap" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"></rect><text x="50%" y="50%" fill="#dee2e6" dy=".3em" dominant-baseline="middle" text-anchor="middle">ФОТО ПОЛЬЗОВАТЕЛЯ</text></svg> -->
                    <img src="" class="img-fluid rounded-circle ml-3 p-2" title="" alt="">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <ul id="profile-card-info" class="list-group list-group-flush"></ul>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card-footer text-right">
                        <span>Кадры&nbsp;ID:&nbsp;<a href="#" class="link-primary link1">ID</a><span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <h3 class="mb-3 title">Параметры авторизации</h3>
        <label for="pr	ofile-login">Ваш логин в Портале</label>
        <div class="input-group mb-3">
            <input id="profile-login" type="text" class="form-control" placeholder="Логин" aria-label="Логин" aria-describedby="profile-login">
        </div>
        <label for="dinner-login">Ваш логин в dinner.atgs.ru</label>
        <div class="input-group mb-3">
            <input id="dinner-login" type="text" class="form-control" placeholder="Логин" aria-label="Логин" aria-describedby="dinner-login">
        </div>

        <div class="my-5"></div>

        <label for="profile-password">Новый пароль (общий для Портала и dinner.atgs.ru)</label>
        <div class="input-group">
            <input id="profile-password" type="password" class="form-control" placeholder="Новый пароль" aria-label="Новый пароль" aria-describedby="profile-password" disabled="disabled">
        </div>
        <div class="d-flex flex-row bd-highlight" id="dinner-mailenbl-block">
            <div class="p-2 bd-highlight align-self-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" id="profile-password-enbl" type="checkbox" name="profile-password-enbl">
                    <label class="form-check-label" for="profile-password-enbl">Изменить пароль</label>
                </div>
            </div>
        </div>
        <div>
            <p class="small-text" style="">После сохранения этот пароль будет использоваться как для доступа
                в Портал, так и для
                dinner.atgs.ru</p>
        </div>
        <div class="d-flex flex-row bd-highlight" id="profile-send-enbl-block">
            <div class="p-2 bd-highlight align-self-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" id="profile-send-enbl" type="checkbox" name="profile-send-enbl">
                    <label class="form-check-label" for="profile-send-enbl">Отправить данные для авторизации
                        на рабочий
                        email</label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row gx-5" style="margin-top: 2.0rem">
    <?php if ($_SESSION['id'] != "1201" && $_SESSION['id'] != "1202" && $_SESSION['id'] != "1203" && $_SESSION['id'] != "1204" && $_SESSION['id'] != "1150" && $_SESSION['id'] != "1151") { ?>
        <div class="col-md-4 block-view">
            <h3 class="mb-3 title">Параметры АТГС.Еда</h3>
            <label for="dinner-email">Email для уведомлений о заказе</label>
            <div class="input-group mb-3">
                <input id="dinner-email" type="text" class="form-control" placeholder="name@example.com" data-inputmask="'alias': 'email'" aria-describedby="dinner-email">
            </div>

            <div class="d-flex flex-row bd-highlight mb-3" id="dinner-mailenbl-block">
                <div class="p-2 bd-highlight align-self-center">
                    <div class="form-check form-switch">
                        <input class="form-check-input" id="dinner-mailenbl" type="checkbox" name="dinner-mailenbl">
                        <label class="form-check-label" for="dinner-mailenbl">Напоминания о заказе на
                            email</label>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-row bd-highlight mb-3" id="dinner-limit-block">
                <div class="p-2 bd-highlight align-self-center">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="dinner-limitenbl" disabled>
                        <label class="form-check-label" for="dinner-limitenbl">Превышение лимита на
                            заказ</label>
                    </div>
                </div>
                <div class="p-2 bd-highlight">
                    <div class="badge rounded-pill bg-warning text-dark text-nowrap" style="width: 5rem; padding: 0.6rem">
                        <span id="dinner-limit"></span>&nbsp;р.
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="col-md-4">
        <h3 class="mb-3 title">Рабочие контакты</h3>
        <label for="profile-worktel">Внутренний номер (осн)</label>
        <div class="input-group mb-3">
            <input id="profile-worktel" type="tel" class="form-control" pattern="[0-9]{3}" placeholder="" data-inputmask="'mask': '999'" aria-describedby="profile-worktel">
        </div>
        <label for="profile-workdoptel">Внутренний номер (доп)</label>
        <div class="input-group mb-3">
            <input id="profile-workdoptel" type="tel" class="form-control" pattern="[0-9]{3}" placeholder="" data-inputmask="'mask': '999'" aria-describedby="profile-workdoptel">
        </div>
        <label for="profile-emailcorp">Корпоративный email</label>
        <div class="input-group mb-3">
            <input id="profile-emailcorp" type="text" class="form-control" placeholder="name@atgs.ru" data-inputmask="'alias': 'email'" aria-describedby="profile-emailcorp">
        </div>
    </div>
    <div class="col-md-4">
        <h3 class="mb-3 title">Дополнительные контакты</h3>
        <label for="profile-tel">Мобильный телефон</label>
        <div class="input-group mb-3">
            <input id="profile-tel" type="tel" class="form-control" pattern="{1}[0-9]{1,3} [0-9]{11,14}" placeholder="" data-inputmask="'mask': '8 (999) 9999999'" aria-describedby="profile-tel">
        </div>

        <label for="profile-emaildop">Дополнительный email</label>
        <div class="input-group mb-3">
            <input id="profile-emaildop" type="text" class="form-control" placeholder="name@example.com" data-inputmask="'alias': 'email'" aria-describedby="profile-emaildop">
        </div>
        <h3 class="mb-3 title">Контакты в сервисе Кадры</h3>
        <label for="profile-telhr">Контактный телефон</label>
        <div class="input-group mb-3">
            <input id="profile-telhr" type="tel" class="form-control" pattern="{1}[0-9]{1,3} [0-9]{11,14}" placeholder="" data-inputmask="'mask': '8 (999) 9999999'" aria-describedby="profile-telhr">
        </div>

        <label for="profile-emailhr">Контактный email</label>
        <div class="input-group mb-3">
            <input id="profile-emailhr" type="text" class="form-control" placeholder="name@example.com" data-inputmask="'alias': 'email'" aria-describedby="profile-emailhr">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-3">
        <div id="btn-saveProfile" class="text-right">
            <button type="button" class="btn btn-outline-primary">Сохранить</button>
        </div>
    </div>
</div>