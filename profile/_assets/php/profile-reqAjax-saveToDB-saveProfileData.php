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
require($_SERVER['DOCUMENT_ROOT'] . '/profile/_assets/functions/funcProfile.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Включаем режим сессии
session_start();
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
$userID = isset($_POST['userID']) ? $_POST['userID'] : $_SESSION['id'];

$username = $_SESSION['firstname'] . " " . $_SESSION['middlename'];
$userlogin_portal = $_POST['userlogin_portal'];
$userlogin_dinner = $_POST['userlogin_dinner'];
$userpass = $_POST['userpass'];

$emailcorp = isset($_POST['emailcorp']) ? $_POST['emailcorp'] : "";
$emaildop = isset($_POST['emaildop']) ? $_POST['emaildop'] : "";
$mobiletel = isset($_POST['mobiletel']) ? $_POST['mobiletel'] : "";
$worktel = isset($_POST['worktel']) ? $_POST['worktel'] : "";
$workdoptel = isset($_POST['workdoptel']) ? $_POST['workdoptel'] : "";
$emaildinner = isset($_POST['emaildinner']) ? $_POST['emaildinner'] : "";
$mailingenbl = (isset($_POST['mailingenbl']) && $_POST['mailingenbl']) ? "1" : "0";

$sendmailenbl = $_POST['sendmailenbl'];
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# 

#
#
$output1 = "";
$output2 = "";
$output3 = "";
# --- - --- - --- - --- - --- - --- - --- - --- - --- - --- - --- - ---
if ($userpass !== "") {
	# ФОРМИРУЕМ НОВЫЙ ПАРОЛЬ
	#
	// Удаляем все лишнее
	$userpass = stripslashes($userpass);
	$userpass = htmlspecialchars($userpass);
	$userpass = trim($userpass);
	// Шифруем дату
	$new_userpass = md5($userpass);
	// Для надежности добавим реверс
	$new_userpass = strrev($new_userpass);
	// Добавляем постфикс
	// Постфикс должен быть везде одинаковый!!
	$new_userpass = $new_userpass . "b3p6f";
	// Обновляеи пароль пользователя в базе dinner.atgs.ru
	mysqlQuery_atgsdinner(" SET character_set_results = 'utf8' ");
	$_QRY1 = mysqlQuery_atgsdinner(" UPDATE users SET password = '$new_userpass' WHERE id ='$userID' ");
	$_QRY1_1 = mysqlQuery_atgsdinner(" UPDATE users_unique_key SET password = '$new_userpass' WHERE id ='$userID' ");
	$_QRY1_2 = mysqlQuery_atgsdinner(" UPDATE botTelg_users	SET service_password = '$new_userpass' WHERE service_userID ='$userID' ");
	$_QRY2 = mysqlQuery(" UPDATE users SET password = '$new_userpass' WHERE id ='$userID' ");
	#
	if ($_QRY1 && $_QRY1_1 && $_QRY2) {
		$output1 = "1";
	} else {
		$output1 = "-1";
	}
} else {
	$userpass = '<span style="font-weight:200; color:#999; font-style: italic">- вы не меняли пароль -</span>';
}

# --- - --- - --- - --- - --- - --- - --- - --- - --- - --- - --- - ---
// Обновляем email и mailing_enbl в базе данных dinner.atgs.ru
$_QRY3 = mysqlQuery_atgsdinner(" UPDATE users SET login = '$userlogin_dinner', email = '$emaildinner', mailing_enbl = '$mailingenbl' WHERE id ='$userID' ");
$_QRY3_1 = mysqlQuery_atgsdinner(" UPDATE users_unique_key SET login = '$userlogin_portal' WHERE id ='$userID' ");
$_QRY3_2 = mysqlQuery_atgsdinner(" UPDATE botTelg_users SET service_login = '$userlogin_dinner' WHERE service_userID ='$userID' ");
#
if ($_QRY3 && $_QRY3_1) {
	$output2 = "1";
} else {
	$output2 = "-1";
}

// Обновляем email и mailing_enbl в базе данных dinner.atgs.ru
$_QRY4 = mysqlQuery(" UPDATE users SET login = '$userlogin_portal', email = '$emaildop', email2 = '$emailcorp', phone = '$mobiletel', phone2 = '$worktel', phone3 = '$workdoptel' WHERE id ='$userID' ");
#
if ($_QRY4) {
	$output3 = "1";
} else {
	$output3 = "-1";
}

# --- - --- - --- - --- - --- - --- - --- - --- - --- - --- - --- - ---
// Обновляем контакты в таблицах сервиса ISM
$_QRY5 = mysqlQuery(" UPDATE ism_spstaff SET cont_emaildop1 = '$emaildop', cont_email = '$emailcorp', cont_telmobile = '$mobiletel', cont_telint1 = '$worktel', cont_telint2 = '$workdoptel' WHERE id ='$userID' ");
#
if ($_QRY5) {
	$output4 = "1";
} else {
	$output4 = "-1";
}


if ($sendmailenbl == 1) {
	# Отправляем уведомление на email с новым логином и паролем
	sendEmail2user($username, $userlogin_dinner, $userlogin_portal, $userpass, $emailcorp, $emaildop);
}
$userpass = "";

echo $output1 . " | " . $output2 . " | " . $output3 . " | " . $output4;
