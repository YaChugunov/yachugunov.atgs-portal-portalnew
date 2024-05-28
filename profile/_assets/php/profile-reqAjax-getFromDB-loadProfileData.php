<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаемся к базе
require($_SERVER['DOCUMENT_ROOT'] . '/_assets/drivers/db_connection.php');
require($_SERVER['DOCUMENT_ROOT'] . '/_assets/drivers/db_connection-atgsdinner.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаем общие функции безопасности
require($_SERVER['DOCUMENT_ROOT'] . '/_assets/functions/funcSecure.inc.php');
# Подключаем собственные функции сервиса Договор
// require('_assets/functions/funcDognet.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Включаем режим сессии
session_start();
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
$userID = isset($_POST['userID']) ? $_POST['userID'] : $_SESSION['id'];
$output1 = "";
$output2 = "";
$output3 = "";
$output4 = "";

mysqlQuery_atgsdinner(" SET character_set_results = 'utf8' ");
$_QRY1 = mysqlQuery_atgsdinner(" SELECT login, password, email, mailing_enbl, day_limit, day_overlimit_enbl FROM users WHERE id ='" . $userID . "'");
$_ROW1 = mysqli_fetch_array($_QRY1);

if ($_QRY1) {
	$output1 = $_ROW1['login'] . "///" . $_ROW1['password'] . "///" . $_ROW1['email'] . "///" . $_ROW1['mailing_enbl'] . "///" . $_ROW1['day_limit'] . "///" . $_ROW1['day_overlimit_enbl'];
} else {
	$output1 = "-1";
}

// --- --- --- --- ---

$_QRY2 = mysqlQuery(" SELECT login, password, email, email2, phone, phone2, phone3, avatar FROM users WHERE id ='" . $userID . "'");
$_ROW2 = mysqli_fetch_array($_QRY2);

if ($_QRY2) {
	$output2 = $_ROW2['login'] . "///" . $_ROW2['password'] . "///" . $_ROW2['email'] . "///" . $_ROW2['email2'] . "///" . $_ROW2['phone'] . "///" . $_ROW2['phone2'] . "///" . $_ROW2['phone3'] . "///" . $_ROW2['avatar'];
} else {
	$output2 = "-1";
}

// --- --- --- --- ---

$_QRY3 = mysqlQuery(" SELECT namedoljtmp, namepodroffice, kodwoker FROM hr_docwokerproftmp WHERE userid ='" . $userID . "' ");
$_ROW3 = mysqli_fetch_array($_QRY3);
$kodwoker = !empty($_ROW3['kodwoker']) ? $_ROW3['kodwoker'] : "";
$_QRY31 = mysqlQuery(" SELECT telmobiloffice, wokermail FROM hr_wokermaindata WHERE kodwoker ='" . $kodwoker . "' ");
$_ROW31 = mysqli_fetch_array($_QRY31);

if ($_QRY3) {
	$output3 = $_ROW3['namedoljtmp'] . "///" . $_ROW3['namepodroffice'] . "///" . $_ROW3['kodwoker'] . "///" . $_ROW31['telmobiloffice'] . "///" . $_ROW31['wokermail'];
} else {
	$output3 = "-1";
}


// --- --- --- --- ---

$_QRY4 = mysqlQuery(" SELECT dolj, kodunique, dept_num, kodwoker FROM ism_spstaff WHERE id ='" . $userID . "'");
$_ROW4 = mysqli_fetch_array($_QRY4);

if ($_QRY4) {
	$output4 = $_ROW4['dolj'] . "///" . $_ROW4['kodwoker'];
} else {
	$output4 = "-1";
}

echo $output1 . " | " . $output2 . " | " . $output3 . " | " . $output4;
