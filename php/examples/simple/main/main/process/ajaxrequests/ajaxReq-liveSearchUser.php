<?php
date_default_timezone_set('Europe/Moscow');
require($_SERVER['DOCUMENT_ROOT'] . '/config.inc.php');
# Подключаем конфигурационный файл
require(__DIR_ROOT . __ADM_FOLDER . '/config.adminize.inc.php');
# Подключаемся к базе
require(__DIR_ASSETS . '/dbconn/db_connection.php');
# Подключаем общие функции безопасности
require(__DIR_ASSETS . '/functions/func.cmn.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаем собственные функции сервиса Почта
require(__DIR_ASSETS . '/functions/func.adminize.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Включаем режим сессии
# session_start();
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----


if (isset($_SESSION['password']) && isset($_SESSION['login'])) {
    if (checkUserAuthorization($_SESSION['login'], $_SESSION['password']) == -1) { ?>
<div class="d-flex flex-row mb-1 row-item">
    <div class="search_result-name text-center" style="width:100%; color:#CCCCCC">Поиск невозможен...</div>
</div>
<?php
    } else {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_POST['search'])) {

            $search = $_POST['search'];
            $search = mb_eregi_replace("[^a-zа-яё0-9 ]", '', $search);
            $search = trim($search);

            $_QRY = mysqlQuery("SELECT * FROM ism_spstaff WHERE (lastname LIKE '{$search}%' OR firstname LIKE '{$search}%' OR middlename LIKE '{$search}%' OR cont_email LIKE '%{$search}%') AND status='1' ORDER BY lastname ASC");
            if (mysqli_num_rows($_QRY) > 0) {
        ?>
<div class="search_result shadow-sm p-3 bg-white rounded">
    <div class="d-flex flex-column">
        <div class="d-flex flex-row mb-1 row-title">
            <div class="search_result-num title" style="width:3%">#</div>
            <div class="search_result-name title" style="width:24%">Фамилия Имя Отчество</div>
            <div class="search_result-tel title" style="width:10%">Вн. номер</div>
            <div class="search_result-mob title" style="width:15%">Сотовый телефон</div>
            <div class="search_result-email title" style="width:15%">Рабочая почта</div>
            <div class="search_result-dolj title" style="width:33%">Должность</div>
        </div>
        <?php
                        $num = 1;
                        while ($_ROW = mysqli_fetch_assoc($_QRY)) {
                            $userID         = !empty($_ROW['id']) ? $_ROW['id'] : "";
                            $koddoljprof    = !empty($_ROW['koddoljprof']) ? $_ROW['koddoljprof'] : "";
                            $kodwoker       = !empty($_ROW['kodwoker']) ? $_ROW['kodwoker'] : "";
                            $lastname       = !empty($_ROW['lastname']) ? $_ROW['lastname'] : "";
                            $firstname      = !empty($_ROW['firstname']) ? $_ROW['firstname'] : "";
                            $middlename     = !empty($_ROW['middlename']) ? $_ROW['middlename'] : "";
                            $tel1           = !empty($_ROW['cont_telint1']) ? $_ROW['cont_telint1'] : '<span style="color:#CCCCCC">не указан</span>';
                            $tel2           = !empty($_ROW['cont_telint2']) ? " (" . $_ROW['cont_telint2'] . ")" : "";
                            $tel3           = !empty($_ROW['cont_telmobile']) ? $_ROW['cont_telmobile'] : '<span style="color:#CCCCCC">не указан</span>';
                            $email          = !empty($_ROW['cont_email']) ? $_ROW['cont_email'] : '<span style="color:#CCCCCC">не указан</span>';

                            $_QRY1 = mysqli_fetch_assoc(mysqlQuery("SELECT * FROM users WHERE id ='{$userID}' ORDER BY id DESC LIMIT 1"));

                            $_QRY2 = mysqli_fetch_assoc(mysqlQuery("SELECT * FROM hr_spdoljprof WHERE koddoljprof ='{$koddoljprof}' ORDER BY id DESC LIMIT 1"));

                            $_QRY3 = mysqli_fetch_assoc(mysqlQuery("SELECT namedoljtmp, namepodroffice, kodwoker FROM hr_docwokerproftmp WHERE userid ='{$userID}' ORDER BY id DESC LIMIT 1"));
                            $podr       = !empty($_QRY3['namepodroffice']) ? $_QRY3['namepodroffice'] : '<span style="color:#CCCCCC">не указан</span>';
                            $dolj       = !empty($_QRY2['namedoljprof']) ? $_QRY2['namedoljprof'] : '<span style="color:#CCCCCC">не указан</span>';
                        ?>
        <div class="d-flex flex-row mb-1 row-item">
            <div class="search_result-num" style="width:3%">
                <?php echo $num; ?></div>
            <div class="search_result-name" style="width:24%">
                <?php echo $lastname . " " . $firstname . " " . $middlename; ?></div>
            <div class="search_result-tel" style="width:10%">
                <?php echo $tel1 . $tel2; ?></div>
            <div class="search_result-mob" style="width:15%">
                <?php echo $tel3; ?></div>
            <div class="search_result-email" style="width:15%">
                <?php echo $email; ?></div>
            <div class="search_result-email" style="width:33%">
                <?php echo $dolj; ?></div>
        </div>
        <?php
                            $num++;
                        }
                        ?>
    </div>
</div>
<?php
            } else { ?>
<div class="search_result shadow-sm p-3 mb-5 bg-white rounded">
    <div class="d-flex flex-column">
        <div class="d-flex flex-row mb-1 row-item">
            <div class="search_result-name text-center" style="width:100%; color:#CCCCCC">Ничего не найдено...</div>
        </div>
    </div>
</div>
<?php
            }
        } else { ?>
<div class="search_result shadow-sm p-3 mb-5 bg-white rounded">
    <div class="d-flex flex-column">
        <div class="d-flex flex-row mb-1 row-item">
            <div class="search_result-name text-center" style="width:100%; color:#CCCCCC">Поиск невозможен...</div>
        </div>
    </div>
</div>
<?php
        }
    }
} else { ?>
<div class="search_result shadow-sm p-3 mb-5 bg-white rounded">
    <div class="d-flex flex-column">
        <div class="d-flex flex-row mb-1 row-item">
            <div class="search_result-name text-center" style="width:100%; color:#CCCCCC">Поиск невозможен...</div>
        </div>
    </div>
</div>
<?php
}