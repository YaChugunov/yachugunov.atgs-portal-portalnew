<?php

?>
<!-- <link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST'] . __SERVICENAME_PORTALNEW; ?>/profile/_assets/css/profile-style.css"> -->
<!-- <link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST'] . __SERVICENAME_PORTALNEW; ?>/profile/_assets/css/profile-additional.css"> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/RobinHerbots/jquery.inputmask@5.0.0-beta.87/css/inputmask.css">
<script src="https://cdn.jsdelivr.net/gh/RobinHerbots/jquery.inputmask@5.0.0-beta.87/dist/jquery.inputmask.min.js">
</script>


<div id="profile-title" class="container mt-5 pt-2 text-left">
    <div class="container px-0">
        <h1 class="display-4 mb-1">
            <?php echo $_SESSION['lastname'] . " " . $_SESSION['firstname'] . " " . $_SESSION['middlename']; ?></h1>
        <p class="lead">Управление профилем Портала и АТГС.Еда (dinner.atgs.ru)</p>
    </div>
</div>

<?php
// if ( ($_GET['userid']==$_SESSION['id']) || (checkIsItSuperadmin($_SESSION['id'])==1) ) {
if (($_GET['userid'] == $_SESSION['id'])) {
?>

<div id="profile-main" class="container mt-5">

    <ul class="nav nav-tabs mb-5" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="profile-tab" data-toggle="tab" data-target="#profile" type="button"
                    role="tab" aria-controls="profile" aria-selected="true">Профиль</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="access-tab" data-toggle="tab" data-target="#access" type="button" role="tab"
                    aria-controls="access" aria-selected="false">Доступ</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="notifications-tab" data-toggle="tab" data-target="#notifications" type="button"
                    role="tab" aria-controls="notifications" aria-selected="false">Уведомления</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="syslog-tab" data-toggle="tab" data-target="#syslog" type="button" role="tab"
                    aria-controls="syslog" aria-selected="false">Портал.Live</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent" style="padding-top: 0.1rem">

        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">

            <?php
                include __DIR_ROOT . __SERVICENAME_PORTALNEW . "/profile/tabs/personaldata/profile-main-personaldata.php";
                ?>

        </div>
        <div class="tab-pane mt-4 fade" id="access" role="tabpanel" aria-labelledby="access-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="shadow-lg p-3 mb-5 bg-white text-center rounded">
                        Все, что касается вашего доступа и возможностей в работе с сервисами Портала. Раздел в
                        работе...
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane mt-4 fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="shadow-lg p-3 mb-5 bg-white text-center rounded">
                        Уведомления Портала обо всех событиях системы, связанных с вами. Раздел в работе...
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane mt-4 fade" id="syslog" role="tabpanel" aria-labelledby="syslog-tab">
            <div class="row">
                <div class="col-md-12">


                    <?php
                        include __DIR_ROOT . __SERVICENAME_PORTALNEW . "/profile/tabs/syslog/profile-main-syslog.php";
                        ?>

                    <div class="shadow-lg p-3 mb-5 bg-white text-center rounded">
                        Лог основных событий и всех действий пользователей в сервисах Портала...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="my-2">&nbsp;</div>

</div>
<?php
} else {
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-sm-12">
            <p class="text-danger text-center">Ошибка идентификации пользователя. Доступ к данным профиля
                невозможен.
            </p>
        </div>
    </div>
</div>
<?php
}
?>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom:none">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ок</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" language="javascript" class="init">
// $(window).load(function() {
$(document).ready(function() {

    $('#profile-password-enbl').click(function() {
        password_enbl = $('input[name="profile-password-enbl"]').is(':checked');
        console.log("checked enbl: " + password_enbl);
        if (password_enbl) {
            console.log("checked enbl on: " + password_enbl);
            $('#profile-password').prop('disabled', false);
        } else {
            console.log("checked enbl off: " + password_enbl);
            $('#profile-password').val('');
            $('#profile-password').prop('disabled', true);
        }
    });

    ajaxRequest_loadProfileData(userID);

    $('#btn-saveProfile').on('click', 'button', function() {
        checked = $('input[name="dinner-mailenbl"]').is(':checked');
        console.log("checked: " + checked);
        if (checked) {
            $(this).prop('checked', false);
            mailenbl = 1;
            console.log("attr1: " + checked);
        } else {
            $(this).prop('checked', true);
            mailenbl = 0;
            console.log("attr2: " + checked);
        }
        //
        //
        sendmail_enbl = $('input[name="profile-send-enbl"]').is(':checked');
        if (sendmail_enbl) {
            sendmailenbl = 1;
        } else {
            sendmailenbl = 0;
        }
        //
        ajaxRequest_saveProfileData(
            userID,
            $('#profile-login').val(),
            $('#dinner-login').val(),
            $('#profile-password').val(),
            $('#profile-emailcorp').val(),
            $('#profile-emaildop').val(),
            $('#profile-tel').val(),
            $('#profile-worktel').val(),
            $('#profile-workdoptel').val(),
            $('#dinner-email').val(),
            mailenbl, sendmailenbl,
            $('#profile-telhr').val(),
            $('#profile-emailhr').val()
        );
    });

    // Initialize InputMask
    $('input').inputmask();

});
</script>