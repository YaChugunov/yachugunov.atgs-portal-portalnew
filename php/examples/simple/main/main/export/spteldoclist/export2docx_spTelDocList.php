<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU');
//
$months = array(1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
//
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Включаем режим сессии
// session_start();
# Подключаем конфигурационный файл
// require($_SERVER['DOCUMENT_ROOT']."/config.inc.php");
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаемся к базе
// require_once($_SERVER['DOCUMENT_ROOT']."/hr/_assets/drivers/db_connection.php");
//require_once($_SERVER['DOCUMENT_ROOT']."/hr/_assets/drivers/db_controller.php");
// $db_handle = new DBController();
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаем общие функции безопасности
// require(dirname(__FILE__) . '/_assets/functions/funcSecure.inc.php');
// require($_SERVER['DOCUMENT_ROOT']."/_assets/functions/funcSecure.inc.php");
# Подключаем собственные функции сервиса Почта
// require($_SERVER['DOCUMENT_ROOT']."/dognet/_assets/functions/funcDognet.inc.php");
#
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
require($_SERVER['DOCUMENT_ROOT'] . "/hr/_assets/drivers/PHPOffice/vendor/autoload.php");
#
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
#
function numberFormat($digit, $width) {
	while (strlen($digit) < $width)
		$digit = '0' . $digit;
	return $digit;
}
#
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Функция формирования следующего ID аванса (kodavans)
# для таблицы этапов 'dognet_docavans'
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function nextKodrestorder() {
	$query = mysqlQuery("SELECT MAX(kodrestorder) as lastKod FROM hr_docworkerrest ORDER BY id DESC");
	$row = mysqli_fetch_assoc($query);
	$lastKod = $row['lastKod'];
	$nextKod = $lastKod + rand(3, 13);
	return $nextKod;
}
#
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Функция формирования нового ID счета-фактуры (kodchfact)
# для таблицы этапов 'dognet_kalplanchf'
#
function nextKodorder() {
	$query = mysqlQuery("SELECT MAX(kodorder) as lastKod FROM hr_jurnalorderall ORDER BY id DESC");
	$row = mysqli_fetch_assoc($query);
	$lastKod = $row['lastKod'];
	$nextKod = $lastKod + rand(3, 13);
	return $nextKod;
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
#
#
#
#
#
#
#
#
#
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
#
$sectionStyle = array(
	'orientation' => 'portrait',
	'marginTop' => \PhpOffice\PhpWord\Shared\Converter::pixelToTwip(40),
	'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::pixelToTwip(40),
	'marginLeft' => 800,
	'marginRight' => 800,
	'colsNum' => 1,
	'pageNumberingStart' => 1
);
$sectionStyle_col2 = array(
	'orientation' => 'portrait',
	'marginTop' => \PhpOffice\PhpWord\Shared\Converter::pixelToTwip(90),
	'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::pixelToTwip(100),
	'marginLeft' => 800,
	'marginRight' => 800,
	'colsNum' => 2,
	'breakType' => 'continuous'
);
//
# Стили для таблицы
$_1px = \PhpOffice\PhpWord\Shared\Converter::pixelToTwip(1);
$_1px = 6;
$_2px = \PhpOffice\PhpWord\Shared\Converter::pixelToTwip(2);
$_2px = 12;
$_5px = \PhpOffice\PhpWord\Shared\Converter::pixelToTwip(5);
$_5px = 30;
$_10px = \PhpOffice\PhpWord\Shared\Converter::pixelToTwip(10);
$_10px = 100;

$_cellBorderNone = array('valign' => 'center');
$_cellBorderNone_gSpan2 = array('valign' => 'center', 'gridSpan' => 2);
$_cellBorderNone_gSpan3 = array('valign' => 'center', 'gridSpan' => 3);
$_cellBorderNone_gSpan4 = array('valign' => 'center', 'gridSpan' => 4);
$_cellBorderNone_gSpan5 = array('valign' => 'center', 'gridSpan' => 5);

$_cellBorderAll = array('valign' => 'center', 'borderTopSize' => $_1px, 'borderTopColor' => '111111', 'borderRightSize' => $_1px, 'borderRightColor' => '111111', 'borderBottomSize' => $_1px, 'borderBottomColor' => '111111', 'borderLeftSize' => $_1px, 'borderLeftColor' => '111111');
$_cellBorderAll_2px = array('valign' => 'center', 'borderTopSize' => $_2px, 'borderTopColor' => '111111', 'borderRightSize' => $_2px, 'borderRightColor' => '111111', 'borderBottomSize' => $_2px, 'borderBottomColor' => '111111', 'borderLeftSize' => $_2px, 'borderLeftColor' => '111111');
$_cellBorderAll_gSpan2 = array('valign' => 'center', 'gridSpan' => 2, 'borderTopSize' => $_1px, 'borderTopColor' => '111111', 'borderRightSize' => $_1px, 'borderRightColor' => '111111', 'borderBottomSize' => $_1px, 'borderBottomColor' => '111111', 'borderLeftSize' => $_1px, 'borderLeftColor' => '111111');
$_cellBorderAll_gSpan3 = array('valign' => 'center', 'gridSpan' => 3, 'borderTopSize' => $_1px, 'borderTopColor' => '111111', 'borderRightSize' => $_1px, 'borderRightColor' => '111111', 'borderBottomSize' => $_1px, 'borderBottomColor' => '111111', 'borderLeftSize' => $_1px, 'borderLeftColor' => '111111');
$_cellBorderAll_gSpan4 = array('valign' => 'center', 'gridSpan' => 4, 'borderTopSize' => $_1px, 'borderTopColor' => '111111', 'borderRightSize' => $_1px, 'borderRightColor' => '111111', 'borderBottomSize' => $_1px, 'borderBottomColor' => '111111', 'borderLeftSize' => $_1px, 'borderLeftColor' => '111111');
$_cellBorderAll_gSpan5 = array('valign' => 'center', 'gridSpan' => 5, 'borderTopSize' => $_1px, 'borderTopColor' => '111111', 'borderRightSize' => $_1px, 'borderRightColor' => '111111', 'borderBottomSize' => $_1px, 'borderBottomColor' => '111111', 'borderLeftSize' => $_1px, 'borderLeftColor' => '111111');
$_cellBorderAll_gSpan5_bgcolor_1px = array('valign' => 'center', 'gridSpan' => 5, 'bgColor' => 'F1F1F1', 'borderTopSize' => $_1px, 'borderTopColor' => '111111', 'borderRightSize' => $_1px, 'borderRightColor' => '111111', 'borderBottomSize' => $_1px, 'borderBottomColor' => '111111', 'borderLeftSize' => $_1px, 'borderLeftColor' => '111111');
$_cellBorderAll_gSpan5_bgcolor_2px = array('valign' => 'center', 'gridSpan' => 5, 'bgColor' => 'D0D0D0', 'borderTopSize' => $_2px, 'borderTopColor' => '111111', 'borderRightSize' => $_2px, 'borderRightColor' => '111111', 'borderBottomSize' => $_2px, 'borderBottomColor' => '111111', 'borderLeftSize' => $_2px, 'borderLeftColor' => '111111');
$_cellBorderAllSpecial_gSpan5_bgcolor_2px = array('valign' => 'center', 'gridSpan' => 5, 'bgColor' => 'D0D0D0', 'borderTopSize' => $_2px, 'borderTopColor' => '111111', 'borderRightSize' => $_1px, 'borderRightColor' => '111111', 'borderBottomSize' => $_2px, 'borderBottomColor' => '111111', 'borderLeftSize' => $_2px, 'borderLeftColor' => '111111');
$_cellBorderAll_vMergeR = array('valign' => 'center', 'vMerge' => 'restart', 'borderTopSize' => $_1px, 'borderTopColor' => '111111', 'borderRightSize' => $_1px, 'borderRightColor' => '111111', 'borderBottomSize' => $_1px, 'borderBottomColor' => '111111', 'borderLeftSize' => $_1px, 'borderLeftColor' => '111111');
$_cellBorderAll_vMergeC = array('valign' => 'center', 'vMerge' => 'continue', 'borderTopSize' => $_1px, 'borderTopColor' => '111111', 'borderRightSize' => $_1px, 'borderRightColor' => '111111', 'borderBottomSize' => $_1px, 'borderBottomColor' => '111111', 'borderLeftSize' => $_1px, 'borderLeftColor' => '111111');
$_cellBorderNone_vMergeR_vCenter = array('valign' => 'center', 'vMerge' => 'restart');
$_cellBorderNone_vMergeR_vCenter = array('valign' => 'center', 'vMerge' => 'restart');
$_cellBorderNone_vMergeR_vTop = array('valign' => 'top', 'vMerge' => 'restart');
$_cellBorderNone_vMergeR_vTop = array('valign' => 'top', 'vMerge' => 'restart');
$_cellBorderNone_vMergeC_vCenter = array('valign' => 'center', 'vMerge' => 'continue');
$_cellBorderNone_vMergeC_vCenter = array('valign' => 'center', 'vMerge' => 'continue');
$_cellBorderNone_vMergeC_vTop = array('valign' => 'top', 'vMerge' => 'continue');
$_cellBorderNone_vMergeC_vTop = array('valign' => 'top', 'vMerge' => 'continue');
$_cellBorderBottom = array('valign' => 'center', 'borderBottomSize' => $_1px, 'borderBottomColor' => '111111');
# ----- ----- ----- ----- ----- 
#
# ::::: ГОТОВИМ ДАННЫЕ
#
# ----- ----- ----- ----- ----- 
#
# ЗАГЛАВНАЯ ТАБЛИЦА ДОКУМЕНТА
#
// ФИО сотрудника
$_USERNAME_FULL = "";
$_USERNAME_LAST = "";
$_USERNAME_SCND = "";
$_USERNAME_FRST = "";
// Дата формирования документа о сотруднике
// Список месяцев с названиями для замены
$_monthsList = array(".01." => "января", ".02." => "февраля", ".03." => "марта", ".04." => "апреля", ".05." => "мая", ".06." => "июня", ".07." => "июля", ".08." => "августа", ".09." => "сентября", ".10." => "октября", ".11." => "ноября", ".12." => "декабря");
$_DATENOW = date("d.m.Y");
$_DAYNOW = date("d");
// Заменяем число месяца на название:
$_mN = date(".m.", strtotime($_DATENOW)); //для замены
$_MONTHNOW = str_replace($_mN, " " . $_monthsList[$_mN] . " ", $_mN);
$_YEARNOW = date("y");
#
#
#
# :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
# :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
# :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
# :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
# :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
# :::::
# ::::: ФОРМИРОВАНИЕ ЛИЧНОЙ КАРТОЧКИ СОТРУДНИКА
# :::::
# :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
# :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
# :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
# :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
# :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
#
#
#
$phpWord1 = new  \PhpOffice\PhpWord\PhpWord();
#
$phpWord1->setDefaultFontName('Arial');
$phpWord1->setDefaultFontSize(4);
#
$properties = $phpWord1->getDocInfo();
$properties->setCreator("АТГС.Портал");
$properties->setCompany('АТГС');
$properties->setTitle('Карточка гражданина, подлежащего воинскому учету');
$properties->setDescription('Карточка гражданина, подлежащего воинскому учету');
$properties->setCategory('Формы');
$properties->setLastModifiedBy('АТГС.Портал');
$properties->setCreated(mktime(Date('H'), Date('i'), Date('s'), Date('m'), Date('d'), Date('Y')));
$properties->setModified(mktime(Date('H'), Date('i'), Date('s'), Date('m'), Date('d'), Date('Y')));
$properties->setSubject('Карточка гражданина, подлежащего воинскому учету');
$properties->setKeywords('Форма, Воинский учет, Кадры');
#
#
# ----- ----- ----- ----- -----
$section = $phpWord1->addSection($sectionStyle);
# ----- ----- ----- ----- ----- 
#
#
$phpWord1->addTitleStyle(1, array('name' => "Arial", 'size' => 14, 'bold' => true), array('spaceAfter' => 240, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
$phpWord1->addTitleStyle(2, array('name' => "Arial", 'size' => 12, 'bold' => true), array('spaceAfter' => 180, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
$phpWord1->addTitleStyle(3, array('name' => "Arial", 'size' => 11, 'bold' => true), array('spaceAfter' => 180, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
#
#
#
# ПОДКЛЮЧАЕМ СТИЛИ ОФОРМЛЕНИЯ
#
#
#
require(__DIR_ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH . "/export/spteldoclist/export2docx_styles.inc.php");
#
$paragraphStyleName1 = 'pStyle1';
$phpWord1->addParagraphStyle($paragraphStyleName1, array('widowControl' => false, 'indentation' => array('left' => 0, 'right' => 0), 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT, 'spacing' => 120, 'spaceBefore' => 240, 'spaceAfter' => 360));
$paragraphStyleName2 = 'pStyle2';
$phpWord1->addParagraphStyle($paragraphStyleName2, array('widowControl' => false, 'indentation' => array('left' => 0, 'right' => 0), 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spacing' => 120, 'spaceBefore' => 240, 'spaceAfter' => 360));
$paragraphStyleName3 = 'pStyle3';
$phpWord1->addParagraphStyle($paragraphStyleName3, array('widowControl' => false, 'indentation' => array('left' => 0, 'right' => 0), 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spacing' => 90, 'spaceBefore' => 210, 'spaceAfter' => 330));
$paragraphStyleName4 = 'pStyle4';
$phpWord1->addParagraphStyle($paragraphStyleName4, array('widowControl' => false, 'indentation' => array('left' => 0, 'right' => 0), 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT, 'spacing' => 120, 'spaceBefore' => 240, 'spaceAfter' => 360));
$paragraphStyleName5 = 'pStyle5';
$phpWord1->addParagraphStyle($paragraphStyleName5, array('widowControl' => false, 'indentation' => array('left' => 0, 'right' => 0), 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spacing' => 80, 'spaceBefore' => 240, 'spaceAfter' => 240));
$paragraphStyleName6 = 'pStyle6';
$phpWord1->addParagraphStyle($paragraphStyleName6, array('widowControl' => false, 'indentation' => array('left' => 0, 'right' => 0), 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spacing' => 90, 'spaceBefore' => 90, 'spaceAfter' => 90));
//
$paragraphCMN1 = 'paragraphCMN1';
$phpWord1->addParagraphStyle($paragraphCMN1, array('widowControl' => false, 'indentation' => array('left' => 0, 'right' => 0), 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spacing' => 0, 'spaceBefore' => 30, 'spaceAfter' => 30));
//
$paragraphCMN1L = 'paragraphCMN1L';
$phpWord1->addParagraphStyle($paragraphCMN1L, array('widowControl' => false, 'indentation' => array('left' => 0, 'right' => 0), 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT, 'spacing' => 0, 'spaceBefore' => 30, 'spaceAfter' => 30));
//
$paragraphCMN2 = 'paragraphCMN2';
$phpWord1->addParagraphStyle($paragraphCMN2, array('widowControl' => false, 'indentation' => array('left' => 0, 'right' => 0), 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spacing' => 80, 'spaceBefore' => 160, 'spaceAfter' => 160));
//
$paragraphCMN3 = 'paragraphCMN3';
$phpWord1->addParagraphStyle($paragraphCMN3, array('widowControl' => false, 'indentation' => array('left' => 0, 'right' => 0), 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spacing' => 40, 'spaceBefore' => 120, 'spaceAfter' => 120));
//
$paragraphR1 = 'paragraphR1';
$phpWord1->addParagraphStyle($paragraphR1, array('widowControl' => false, 'indentation' => array('left' => 0, 'right' => 0), 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT, 'spacing' => 60, 'spaceBefore' => 120, 'spaceAfter' => 120));
//
$headerR1 = 'headerR1';
$phpWord1->addParagraphStyle($headerR1, array('widowControl' => false, 'indentation' => array('left' => 0, 'right' => 0), 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT, 'spacing' => 60, 'spaceBefore' => 0, 'spaceAfter' => 90));
# 
#
# ::::: СОЗДАЕМ ЗАГОЛОВКИ ТАБЛИЦ
#
# 
$cmnTable_style1 = 'Common Table Style 1';
$phpWord1->addTableStyle($cmnTable_style1, array('cellMarginLeft' => 40, 'cellMarginTop' => 20, 'cellMarginRight' => 40, 'cellMarginBottom' => 20, 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER), array('tblHeader' => false));
#
$cmnTable_style2 = 'Common Table Style 2';
$phpWord1->addTableStyle($cmnTable_style2, array('cellMarginLeft' => 40, 'cellMarginTop' => 20, 'cellMarginRight' => 40, 'cellMarginBottom' => 20, 'borderTopSize' => $_1px, 'borderTopColor' => '111111', 'borderRightSize' => $_1px, 'borderRightColor' => '111111', 'borderBottomSize' => $_1px, 'borderBottomColor' => '111111', 'borderLeftSize' => $_1px, 'borderLeftColor' => '111111', 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER), array('tblHeader' => false));
#
$cmnTable_style3 = 'Common Table Style 3';
$phpWord1->addTableStyle($cmnTable_style3, array('cellSpacing' => 40, 'cellMarginLeft' => 40, 'cellMarginTop' => 20, 'cellMarginRight' => 40, 'cellMarginBottom' => 20, 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER), array('tblHeader' => false));
#
$cmnTable_style4 = 'Null cellMargins table';
$phpWord1->addTableStyle($cmnTable_style4, array('cellSpacing' => 40, 'cellMarginLeft' => 60, 'cellMarginTop' => 60, 'cellMarginRight' => 60, 'cellMarginBottom' => 60, 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER), array('tblHeader' => false));
# 
#
# ::::: НАДПИСЬ В ВЕРХНЕМ КОЛОНТИТУЛЕ
#
# 
$header = $section->addHeader();
$header->firstPage();

$textrun = $header->addTextRun($headerR1);
$textrun->addText('Список телефонов и электронной почты сотрудников', $_FontStyle_Doc_P7);
$textrun->addTextBreak();
$textrun->addText('Тел.: (495) 660-08-02, 353-54-07, газ. телефон (701) 67019 - выход на газ. связь (#88008-9 № ГАЗ#)', $_FontStyle_Doc_P7);
$textrun->addTextBreak();
$textrun->addText('www.atgs.ru, e-mail: atgs@atgs.ru', $_FontStyle_Doc_P7);
# -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 
#
# 
# ::::: НАЗВАНИЕ ДОКУМЕНТА
#
# 
// $textrun = $section->addTextRun($paragraphStyleName6);
// $textrun->addText('Список телефонов и электронной почты сотрудников', $_FontStyle_Doc_P14_B);
// $textrun->addTextBreak();
# -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 
#
#
# Наименование организации
#
#
# Вставляем таблицу
$tableCmn = $section->addTable($cmnTable_style1);
# -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 
#
#
# Сверка документов
#
#
# Вставляем интервал перед абзацем
$textrun = $section->addTextRun($paragraphCMN1);
#
# Вставляем таблицу
$tableCmn = $section->addTable($cmnTable_style1);
# 
# -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 
#
$textrun = $section->addTextRun($paragraphCMN1);
#
# -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 
#
# 1. ФИО
#
#
# Вставляем таблицу
$tableCmn = $section->addTable($cmnTable_style1);
# 
# 
# 
# ::::: СТРОКА 1 : Фамилия
# 
# Вставляем строку
$tableCmn->addRow(220, array('cantSplit' => true));
# Вставляем столбцы
$cell = $tableCmn->addCell(200, $_cellBorderNone)->addTextRun($_TBL_CELLAlign_H_Left)->addText("", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(4400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Left)->addText("ФИО", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(1500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("Внутренние телефоны", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(2400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("Сотовый и доп. телефоны", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(2500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("Email", $_FontStyle_Doc_P9_B);


# :: SQL REQ :: Данные о сотруднике
$_QRY_FL = mysqlQuery("SELECT LEFT(lastname,1) as firstL FROM ism_spstaff WHERE status='1' GROUP BY firstL ORDER BY firstL ASC;
	");
while ($_ROW_FL = mysqli_fetch_assoc($_QRY_FL)) {
	$_FL = $_ROW_FL['firstL'];
	# Вставляем строку
	$tableCmn->addRow(220);
	# Вставляем столбцы
	$cell = $tableCmn->addCell(11000, $_cellBorderAllSpecial_gSpan5_bgcolor_2px)->addTextRun($_TBL_CELLAlign_H_Left)->addText($_FL, $_FontStyle_Doc_P10_B);

	$_QRY_USERS = mysqlQuery("SELECT id, LEFT(lastname,1) as firstL, lastname, firstname, middlename, cont_telint1, cont_telint2, cont_telmobile, cont_telreserve, cont_email, cont_emaildop1 FROM ism_spstaff WHERE status='1' AND viewinsptel='1' AND LEFT(lastname,1)='{$_FL}' ORDER BY lastname ASC;
	");
	while ($_ROW_USERS = mysqli_fetch_assoc($_QRY_USERS)) {
		$_lastName = $_ROW_USERS['lastname'];
		$_middleName = $_ROW_USERS['middlename'];
		$_firstName = $_ROW_USERS['firstname'];
		$_FIO = $_lastName . " " . $_firstName . " " . $_middleName;
		//
		$_intTel1 = $_ROW_USERS['cont_telint1'];
		$_intTel2 = $_ROW_USERS['cont_telint2'];
		$_INTTEL = !empty($_intTel1) ? $_intTel1 . (!empty($_intTel2) ? ", " . $_intTel2 : "") : $_intTel2;
		//
		$_mobTel = $_ROW_USERS['cont_telmobile'];
		$_resTel = $_ROW_USERS['cont_telreserve'];
		$_MOBTEL = !empty($_mobTel) ? $_mobTel . (!empty($_resTel) ? ", " . $_resTel : "") : $_resTel;
		//
		$_email = $_ROW_USERS['cont_email'];
		$_emailDop1 = $_ROW_USERS['cont_emaildop1'];
		$_EMAIL = !empty($_email) ? $_email . (!empty($_emailDop1) ? ", " . $_emailDop1 : "") : $_emailDop1;
		//
		# Вставляем строку
		$tableCmn->addRow(180, array('cantSplit' => true));
		# Вставляем столбцы
		$cell = $tableCmn->addCell(200, $_cellBorderNone)->addTextRun($_TBL_CELLAlign_H_Left)->addText("", $_FontStyle_Doc_P9);
		$cell = $tableCmn->addCell(4400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Left)->addText($_FIO, $_FontStyle_Doc_P9_B);
		$cell = $tableCmn->addCell(1500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText($_INTTEL, $_FontStyle_Doc_P9_B);
		$cell = $tableCmn->addCell(2400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText($_MOBTEL, $_FontStyle_Doc_P9);
		$cell = $tableCmn->addCell(2500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText($_EMAIL, $_FontStyle_Doc_P9);
	}
}
// --- --- --- --- ---
// ПОМЕЩЕНИЯ
//
# Вставляем строку
$tableCmn->addRow(220);
# Вставляем столбцы
$cell = $tableCmn->addCell(11000, $_cellBorderAllSpecial_gSpan5_bgcolor_2px)->addTextRun($_TBL_CELLAlign_H_Left)->addText("ПОМЕЩЕНИЯ", $_FontStyle_Doc_P10_B);
# Вставляем строку
$tableCmn->addRow(180, array('cantSplit' => true));
# Вставляем столбцы
$cell = $tableCmn->addCell(200, $_cellBorderNone)->addTextRun($_TBL_CELLAlign_H_Left)->addText("", $_FontStyle_Doc_P9);
$cell = $tableCmn->addCell(4400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Left)->addText("Охрана (проходная)", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(1500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("417", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(2400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("", $_FontStyle_Doc_P9);
$cell = $tableCmn->addCell(2500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("", $_FontStyle_Doc_P9);
//
# Вставляем строку
$tableCmn->addRow(180, array('cantSplit' => true));
# Вставляем столбцы
$cell = $tableCmn->addCell(200, $_cellBorderNone)->addTextRun($_TBL_CELLAlign_H_Left)->addText("", $_FontStyle_Doc_P9);
$cell = $tableCmn->addCell(4400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Left)->addText("Полигон (АСУТП)", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(1500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("516", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(2400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("", $_FontStyle_Doc_P9);
$cell = $tableCmn->addCell(2500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("", $_FontStyle_Doc_P9);
//
# Вставляем строку
$tableCmn->addRow(180, array('cantSplit' => true));
# Вставляем столбцы
$cell = $tableCmn->addCell(200, $_cellBorderNone)->addTextRun($_TBL_CELLAlign_H_Left)->addText("", $_FontStyle_Doc_P9);
$cell = $tableCmn->addCell(4400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Left)->addText("Полигон (ИТиТО АСУ)", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(1500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("655", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(2400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("", $_FontStyle_Doc_P9);
$cell = $tableCmn->addCell(2500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("", $_FontStyle_Doc_P9);
//
# Вставляем строку
$tableCmn->addRow(180, array('cantSplit' => true));
# Вставляем столбцы
$cell = $tableCmn->addCell(200, $_cellBorderNone)->addTextRun($_TBL_CELLAlign_H_Left)->addText("", $_FontStyle_Doc_P9);
$cell = $tableCmn->addCell(4400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Left)->addText("Полигон (ИУС)", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(1500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("619", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(2400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("", $_FontStyle_Doc_P9);
$cell = $tableCmn->addCell(2500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("", $_FontStyle_Doc_P9);
//
# Вставляем строку
$tableCmn->addRow(180, array('cantSplit' => true));
# Вставляем столбцы
$cell = $tableCmn->addCell(200, $_cellBorderNone)->addTextRun($_TBL_CELLAlign_H_Left)->addText("", $_FontStyle_Doc_P9);
$cell = $tableCmn->addCell(4400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Left)->addText("Переговорная", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(1500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("412", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(2400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("", $_FontStyle_Doc_P9);
$cell = $tableCmn->addCell(2500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("", $_FontStyle_Doc_P9);
//
# Вставляем строку
$tableCmn->addRow(180, array('cantSplit' => true));
# Вставляем столбцы
$cell = $tableCmn->addCell(200, $_cellBorderNone)->addTextRun($_TBL_CELLAlign_H_Left)->addText("", $_FontStyle_Doc_P9);
$cell = $tableCmn->addCell(4400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Left)->addText("Столовая", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(1500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("414", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(2400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("", $_FontStyle_Doc_P9);
$cell = $tableCmn->addCell(2500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("", $_FontStyle_Doc_P9);
//
// --- --- --- --- ---
// ФИЛИАЛЫ
//
# Вставляем строку
$tableCmn->addRow(220);
# Вставляем столбцы
$cell = $tableCmn->addCell(11000, $_cellBorderAllSpecial_gSpan5_bgcolor_2px)->addTextRun($_TBL_CELLAlign_H_Left)->addText("ФИЛИАЛЫ", $_FontStyle_Doc_P10_B);
# Вставляем строку
$tableCmn->addRow(180, array('cantSplit' => true));
# Вставляем столбцы
$cell = $tableCmn->addCell(200, $_cellBorderNone)->addTextRun($_TBL_CELLAlign_H_Left)->addText("", $_FontStyle_Doc_P9);
$cell = $tableCmn->addCell(4400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Left)->addText("НИЖНИЙ НОВГОРОД<w:br />Фролова Марина Владимировна<w:br />Носова Ирина Юрьевна", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(1500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(2400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("8 (831) 435 5618<w:br />8 (831) 435 5630<w:br />8 (831) 435 5617", $_FontStyle_Doc_P9);
$cell = $tableCmn->addCell(2500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("nn@atgs.ru<w:br />frolova@atgs.ru<w:br />nosova@atgs.ru", $_FontStyle_Doc_P9);
//
# Вставляем строку
$tableCmn->addRow(180, array('cantSplit' => true));
# Вставляем столбцы
$cell = $tableCmn->addCell(200, $_cellBorderNone)->addTextRun($_TBL_CELLAlign_H_Left)->addText("", $_FontStyle_Doc_P9);
$cell = $tableCmn->addCell(4400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Left)->addText("ТВЕРЬ<w:br />Галкин Дмитрий Евгеньевич", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(1500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("", $_FontStyle_Doc_P9_B);
$cell = $tableCmn->addCell(2400, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("8 (4822) 34 9177", $_FontStyle_Doc_P9);
$cell = $tableCmn->addCell(2500, $_cellBorderAll)->addTextRun($_TBL_CELLAlign_H_Center)->addText("galkin@atgs.ru", $_FontStyle_Doc_P9);
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
#
#
#
#
#
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
#
$__pref = date('Y') . "-" . $_SESSION['id'] . "-" . date('mdHis');
$__ext = "docx";
$md5 = md5(uniqid());
$__nameMD5 = $md5;
$__name2save = "Список телефонов АТГС (" . date('Ym') . ")-{$__pref}";
# 
$xmlWriter1 = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord1, 'Word2007');
$filepath1 = $_SERVER['DOCUMENT_ROOT'] . "/portalnew/tmp/";
$filename1 = $__name2save . "." . $__ext;
$xmlWriter1->save($filepath1 . $filename1);
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----

// Делаем запись в системный лог
// Все параметры в таблице portal_log_messages

//	PORTAL_SYSLOG('99944200', '0000002', null, $_GET['uniqueID1'], "Письмо о направлении акта", "WORD");

?>

<style>
.circles {
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: sans-serif;
    color: #ccc;
}

.circle {
    background: #FAFAFA;
    padding: 25px;
    margin: 5px;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px #ccc solid;
    border-radius: 50%;
    width: 10px;
    height: 10px;
    max-width: 10px;
    max-height: 10px;
}

.circle_number {
    font-size: 2.2rem;
}

.return-link {
    font-family: 'Oswald', sans-serif;
    font-size: 1.2em;
    font-weight: 300;
    text-transform: uppercase
}

.format-selected {
    font-family: 'Oswald', sans-serif;
    font-size: 1.2em;
    font-weight: 300;
    text-transform: none
}

.return-link {
    color: #999
}

a.return-link,
a.format-selected {
    text-decoration: underline
}

a.return-link:hover,
a.format-selected:hover {
    text-decoration: none
}
</style>


<script type="text/javascript">
</script>