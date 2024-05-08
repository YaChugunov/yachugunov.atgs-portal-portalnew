<?php
date_default_timezone_set('Europe/Moscow');
# Подключаем конфигурационный файл
require($_SERVER['DOCUMENT_ROOT'] . '/config.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
require_once(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/config.portal.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаемся к базе
require_once(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/dbconn/db_connection.php');
require_once(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/dbconn/db_controller.php');
$db_handle = new DBController();
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаем общие функции безопасности
require_once(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/functions/func.secure.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаем собственные функции сервиса Почта
require_once(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/functions/func.portal.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Включаем режим сессии
// session_start();
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
#
/*
 * Example PHP implementation used for the index.html example
*/

$nowBeg = date('Y-m-d') . '00:00:01';
$datetimeBeg = date('Y-m-d H:i:s', strtotime($nowBeg . ' -1 DAY'));
// $datetimeBeg = date('Y-m-d H:i:s', strtotime($nowBeg));

// DataTables PHP library
require(__DIR_ROOT . __SERVICENAME_PORTALNEW . "/_assets/libs/Datatables/Editor-PHP-1.9.7/lib/DataTables.php");


// Alias Editor classes so they are easy to use
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Validate,
	DataTables\Editor\ValidateOptions;

// Build our Editor instance and process the data coming from _POST
Editor::inst($db, 'portal_syslog')
	->fields(
		Field::inst('portal_syslog.service'),
		Field::inst('portal_syslog.subgroup'),
		Field::inst('portal_syslog.user_id'),
		Field::inst('portal_syslog.user_firstname'),
		Field::inst('portal_syslog.user_lastname'),
		Field::inst('portal_syslog.timestamp')
			->getFormatter(Format::datetime(
				'Y-m-d H:i:s',
				'd.m.Y H:i'
			))
			->setFormatter(Format::datetime(
				'd.m.Y H:i',
				'Y-m-d H:i:s'
			)),
		Field::inst('portal_syslog.message'),
		Field::inst('portal_syslog.field_info1'),
		Field::inst('portal_syslog.field_info2'),
		Field::inst('portal_syslog.row_id'),
		Field::inst('portal_syslog.doc_id'),
		Field::inst('portal_syslog.doc_number'),
		Field::inst('portal_syslog.user_ip'),

		Field::inst('users.sex'),

	)

	// ->on('preGet', function ($editor) {
	// 	$editor->where(function ($q) {
	// 		$q->where('portal_syslog.timestamp', '(SELECT timestamp FROM portal_syslog WHERE timestamp >= NOW() - INTERVAL 3 DAY)', 'IN', false);
	// 	});
	// })

	->leftJoin('users', 'users.id', '=', 'portal_syslog.user_id')

	->where('portal_syslog.timestamp', $datetimeBeg, '>=')
	->where('portal_syslog.user_id', '999', '!=')
	->where('portal_syslog.user_id', '1011', '!=')
	->process($_POST)
	->json();
