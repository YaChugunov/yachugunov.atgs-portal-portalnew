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


function search_select($q, $text) {
    return preg_replace('/((?:^|>)[^<]*)(' . $q . ')/usi', '$1<span style="background-color:#FFFCE0">$2</span>', $text);
}


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

            $_mainQRY = mysqlQuery(
                "SELECT 
                    hr_docwokerkart.workerendnamemain as lastname, 
                    hr_docwokerkart.workerfistnamemain as firstname, 
                    hr_docwokerkart.workersecondnamemain as middlename, 
                    hr_wokermaindata.wokermail as wokermail, 
                    hr_wokermaindata.telmobiloffice as telmobiloffice, 
                    hr_docwokerproftmp.userid as id, 
                    hr_docwokerproftmp.koddoljprof as koddoljprof, 
                    hr_docwokerproftmp.kodofficework as kodofficework, 
                    hr_docwokerproftmp.namepodroffice as namepodroffice, 
                    hr_docwokerproftmp.namedoljtmp as namedoljtmp, 
                    ism_spstaff.cont_telint1 as cont_telint1, 
                    ism_spstaff.cont_telint2 as cont_telint2, 
                    ism_spstaff.cont_telmobile as cont_telmobile,
                    ism_spstaff.cont_email as cont_email 
                FROM hr_docwokerkart 
                LEFT JOIN hr_wokermaindata ON hr_docwokerkart.kodwokerkart=hr_wokermaindata.kodwoker
                LEFT JOIN hr_docwokerproftmp ON hr_docwokerkart.kodwokerkart=hr_docwokerproftmp.kodwoker
                LEFT JOIN ism_spstaff ON hr_docwokerkart.kodwokerkart=ism_spstaff.kodwoker
                WHERE (
                    hr_docwokerkart.workerendnamemain LIKE '{$search}%' OR 
                    hr_docwokerkart.workerfistnamemain LIKE '{$search}%' OR 
                    hr_docwokerkart.workersecondnamemain LIKE '{$search}%' OR 
                    wokermail LIKE '%{$search}%' OR 
                    cont_email LIKE '%{$search}%' 
                    ) AND hr_docwokerkart.archwoker<>'1' AND hr_docwokerkart.koddel<>'99' 
                ORDER BY hr_docwokerkart.workerendnamemain ASC"
            );

            $_QRY = mysqlQuery("SELECT * FROM ism_spstaff WHERE (lastname LIKE '{$search}%' OR firstname LIKE '{$search}%' OR middlename LIKE '{$search}%' OR cont_email LIKE '%{$search}%') AND status='1' ORDER BY lastname ASC");
            if (mysqli_num_rows($_mainQRY) > 0) {
        ?>
<div class="search_result shadow-sm p-3 bg-white rounded">
    <div class="d-flex flex-column">
        <div class="d-flex flex-row mb-1 row-title">
            <div class="search_result-num title" style="width:3%">#</div>
            <div class="search_result-name title" style="width:24%">Фамилия Имя Отчество</div>
            <div class="search_result-tel title" style="width:10%">Вн. номер</div>
            <div class="search_result-mob title" style="width:12%">Сот. телефон</div>
            <div class="search_result-email title" style="width:15%">Рабочая почта</div>
            <div class="search_result-dolj title" style="width:31%">Должность</div>
            <div class="search_result-locate title text-center" style="width:5%">Офис</div>
        </div>
        <?php
                        $num = 1;
                        while ($_ROW = mysqli_fetch_assoc($_mainQRY)) {
                            $userID         = !empty($_ROW['id']) ? $_ROW['id'] : "";
                            $koddoljprof    = !empty($_ROW['koddoljprof']) ? $_ROW['koddoljprof'] : "";
                            $kodwoker       = !empty($_ROW['kodwoker']) ? $_ROW['kodwoker'] : "";
                            $lastname       = !empty($_ROW['lastname']) ? search_select($search, $_ROW['lastname']) : "";
                            $firstname      = !empty($_ROW['firstname']) ? search_select($search, $_ROW['firstname']) : "";
                            $middlename     = !empty($_ROW['middlename']) ? search_select($search, $_ROW['middlename']) : "";
                            $tel1           = !empty($_ROW['cont_telint1']) ? $_ROW['cont_telint1'] : '<span style="color:#CCCCCC">не указан</span>';
                            $tel2           = !empty($_ROW['cont_telint2']) ? " (" . $_ROW['cont_telint2'] . ")" : "";
                            $tel3           = !empty($_ROW['cont_telmobile']) ? $_ROW['cont_telmobile'] : (!empty($_ROW['telmobiloffice']) ? $_ROW['telmobiloffice'] : '<span style="color:#CCCCCC">не указан</span>');

                            $email          = !empty($_ROW['cont_email']) ? search_select($search, $_ROW['cont_email']) : (!empty($_ROW['wokermail']) ? search_select($search, $_ROW['wokermail']) : '<span style="color:#CCCCCC">не указан</span>');

                            $_QRY2 = mysqli_fetch_assoc(mysqlQuery("SELECT * FROM hr_spdoljprof WHERE koddoljprof ='{$koddoljprof}' ORDER BY id DESC LIMIT 1"));
                            $kodofficeplace = !empty($_ROW['kodofficework']) ? $_ROW['kodofficework'] : "";
                            $podr           = !empty($_ROW['namepodroffice']) ? $_ROW['namepodroffice'] : '<span style="color:#CCCCCC">не указан</span>';
                            $dolj           = !empty($_QRY2['namedoljprof']) ? $_QRY2['namedoljprof'] : '<span style="color:#CCCCCC">не указана</span>';

                            $_QRY4 = mysqli_fetch_assoc(mysqlQuery("SELECT * FROM hr_docworkerofficeplace WHERE kodofficeplace ='{$kodofficeplace}' ORDER BY id DESC LIMIT 1"));
                            $location   = !empty($_QRY4['nameshort']) ? $_QRY4['nameshort'] : '<span style="color:#CCCCCC">---</span>';

                        ?>
        <div class="d-flex flex-row mb-1 row-item">
            <div class="search_result-num" style="width:3%">
                <?php echo $num; ?></div>
            <div class="search_result-name" style="width:24%">
                <?php echo $lastname . " " . $firstname . " " . $middlename; ?></div>
            <div class="search_result-tel" style="width:10%">
                <?php echo $tel1 . $tel2; ?></div>
            <div class="search_result-mob" style="width:12%">
                <?php echo $tel3; ?></div>
            <div class="search_result-email" style="width:15%">
                <?php echo $email; ?></div>
            <div class="search_result-dolj" style="width:31%">
                <?php echo $dolj; ?></div>
            <div class="search_result-locate text-center" style="width:5%">
                <?php echo $location; ?></div>
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
            <div class="search_result-name text-center" style="width:100%; color:#EA4335">Ничего не найдено...</div>
        </div>
    </div>
</div>
<?php
            }
        } else { ?>
<div class="search_result shadow-sm p-3 mb-5 bg-white rounded">
    <div class="d-flex flex-column">
        <div class="d-flex flex-row mb-1 row-item">
            <div class="search_result-name text-center" style="width:100%; color:#EA4335">Поиск невозможен...</div>
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
            <div class="search_result-name text-center" style="width:100%; color:#EA4335">Поиск невозможен...</div>
        </div>
    </div>
</div>
<?php
}