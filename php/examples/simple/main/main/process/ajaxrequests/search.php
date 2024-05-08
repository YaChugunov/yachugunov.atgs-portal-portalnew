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
#
$userid = $_SESSION['id'];
$_QRY_Settings = mysqli_fetch_array(mysqlQuery("SELECT use_lightTheme FROM portal_userSettingsUI WHERE ID = '{$userid}'"));
$useLightTheme = !empty($_QRY_Settings) ? $_QRY_Settings['use_lightTheme'] : "";
#
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
// Код документа в таблице mailbox_incoming
$searchstring = isset($_POST['searchstring']) ? $_POST['searchstring'] : "";
$filter_mail = isset($_POST['filter_mail']) ? $_POST['filter_mail'] : "";
$filter_dog = isset($_POST['filter_dog']) ? $_POST['filter_dog'] : "";
$filter_sp = isset($_POST['filter_sp']) ? $_POST['filter_sp'] : "";
$filter_hr = isset($_POST['filter_hr']) ? $_POST['filter_hr'] : "";
$filter_ism = isset($_POST['filter_ism']) ? $_POST['filter_ism'] : "";

# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
$separator1 = '<i class="fa-solid fa-ellipsis-vertical mx-3" style="color:#CCCCCC !important"></i>';
$data = array();
$error = $search_output = "";

if (isset($_SESSION['password']) && isset($_SESSION['login'])) {
    if (checkUserAuthorization($_SESSION['login'], $_SESSION['password']) == -1) {
        return 0;
    } else {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $searchstring != "") {
            /*
            MySQL PHP Search Exercises by Adam Khoury @ developphp.com
            MySQL Database Version Used In the Lessons: 5.1.58
            PHP Version Used in the Lessons: 5.2.17
            For Code Logic and Code Explanations Watch the Videos
            */
            if (!empty($useLightTheme) && $useLightTheme == '1') {
                $searchInMailUse_str = $filter_mail == "1" ? "<span class='text-secondary'>Поиск в Почте включен<i class='fa-solid fa-arrow-right text-secondary mx-3'></i></span>" : "<span class='text-danger'>Поиск в Договоре выключен</span>";
                $searchInMailEnable_str = ((checkUserRestrictions($_SESSION['id'], 'mail', 2, 0) == 1) || (checkUserRestrictions($_SESSION['id'], 'mailnew', 2, 0) == 1)) ? "<span class='text-secondary'>Поиск выполнен</span>" : "<span class='text-danger'>У вас недостаточно прав доступа к сервису Почта, поэтому поиск по данным этого сервиса не проводился</span>";
                $searchInDogUse_str = $filter_dog == "1" ? "<span class='text-secondary'>Поиск в Договоре включен<i class='fa-solid fa-arrow-right text-secondary mx-3'></i></span>" : "<span class='text-danger'>Поиск в Договоре выключен</span>";
                $searchInDogEnable_str = (checkUserRestrictions($_SESSION['id'], 'dognet', 3, 0) == 1) ? (($filter_dog == "1") ? "<span class='text-secondary'>Поиск выполнен</span>" : "") : "<span class='text-danger'>У вас недостаточно прав доступа к сервису Договор, поэтому поиск по данным этого сервиса не проводился</span>";
            } else {
                $searchInMailUse_str = $filter_mail == "1" ? "<span class='text-secondary'>Поиск в Почте включен<i class='fa-solid fa-arrow-right text-secondary mx-3'></i></span>" : "<span class='text-danger'>Поиск в Договоре выключен</span>";
                $searchInMailEnable_str = ((checkUserRestrictions($_SESSION['id'], 'mail', 2, 0) == 1) || (checkUserRestrictions($_SESSION['id'], 'mailnew', 2, 0) == 1)) ? "<span class='text-secondary'>Поиск выполнен</span>" : "<span class='text-danger'>У вас недостаточно прав доступа к сервису Почта, поэтому поиск по данным этого сервиса не проводился</span>";
                $searchInDogUse_str = $filter_dog == "1" ? "<span class='text-secondary'>Поиск в Договоре включен<i class='fa-solid fa-arrow-right text-secondary mx-3'></i></span>" : "<span class='text-danger'>Поиск в Договоре выключен</span>";
                $searchInDogEnable_str = (checkUserRestrictions($_SESSION['id'], 'dognet', 3, 0) == 1) ? (($filter_mail == "1") ? "<span class='text-secondary'>Поиск выполнен</span>" : "") : "<span class='text-danger'>У вас недостаточно прав доступа к сервису Договор, поэтому поиск по данным этого сервиса не проводился</span>";
            }
            // Using MATCH AGAINST over multiple tables to perform search
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
            $search_output = "";
            $sqlCommand_start = "";
            $sqlCommand_mail = "";
            $sqlCommand_dog = "";
            $sqlCommand_end = "";
            if (isset($_POST['searchstring']) && $_POST['searchstring'] != "") {
                if ($filter_mail == "1" || $filter_dog == "1") {
                    $sqlCommand_start .= "
                    SELECT * 
                    FROM (
                    ";
                    if ($filter_mail == "1" && ((checkUserRestrictions($_SESSION['id'], 'mail', 2, 0) == 1) || (checkUserRestrictions($_SESSION['id'], 'mailnew', 2, 0) == 1))) {
                        $sqlCommand_mail .= "
                        (
                            SELECT
                                'mail_inbox' AS type,
                                ID AS id,
                                koddocmail AS kod,
                                inbox_docID AS par1,
                                inbox_docDate AS par2,
                                inbox_docSender AS par3,
                                inbox_docSender_kodzakaz AS par4,
                                inbox_docAbout AS par5,
                                inbox_docRecipientSTR AS par6,
                                inbox_docContractorSTR AS par7,
                                inbox_tags AS par8,
                                inbox_defaultTags AS par9,
                                inbox_UID AS par10, 
                                inbox_docFileID AS mainfile, 
                                inbox_docComment AS comment, 
                                'empty' AS par11, 
                                'empty' AS par12, 
                                MATCH (inbox_defaultTags, inbox_UID, inbox_docAbout, inbox_docSender, inbox_tags) AGAINST ('{$searchstring}' IN BOOLEAN MODE) AS rel
                            FROM
                                mailbox_incoming
                            WHERE
                                MATCH (inbox_defaultTags, inbox_UID, inbox_docAbout, inbox_docSender, inbox_tags) AGAINST ('{$searchstring}' IN BOOLEAN MODE)
                        )
                        UNION
                        (
                            SELECT
                                'mail_outbox' AS type,
                                ID AS id,
                                koddocmail AS kod,
                                outbox_docID AS par1,
                                outbox_docDate AS par2,
                                outbox_docRecipient AS par3,
                                outbox_docRecipient_kodzakaz AS par4,
                                outbox_docAbout AS par5,
                                outbox_docSenderSTR AS par6,
                                outbox_docContractorSTR AS par7,
                                outbox_tags AS par8,
                                outbox_defaultTags AS par9,
                                outbox_UID AS par10,
                                outbox_docFileID AS mainfile, 
                                outbox_docComment AS comment, 
                                'empty' AS par11, 
                                'empty' AS par12, 
                                MATCH (outbox_defaultTags, outbox_UID, outbox_docAbout, outbox_docRecipient, outbox_tags) AGAINST ('{$searchstring}' IN BOOLEAN MODE) as rel
                            FROM
                                mailbox_outgoing
                            WHERE
                                MATCH (outbox_defaultTags, outbox_UID, outbox_docAbout, outbox_docRecipient, outbox_tags) AGAINST ('{$searchstring}' IN BOOLEAN MODE)
                        )
                        ";
                    }
                    if ($filter_dog == '1' && checkUserRestrictions($_SESSION['id'], 'dognet', 3, 0) == 1) {
                        $sqlCommand_dog .= !empty($sqlCommand_mail) ? "UNION " : "";
                        $sqlCommand_dog .= "
                        (
                            SELECT
                                'dog_base' AS type,
                                ID AS id,
                                koddoc AS kod,
                                docnumberSTR AS par1,
                                yearnachdoc AS par2,
                                docpartnernumberSTR AS par3,
                                nameobjectfull AS par4,
                                docnameshot AS par5,
                                docnamefullm AS par6,
                                namezakfull AS par7,
                                tags AS par8,
                                defaultTags AS par9,
                                'empty' AS par10,
                                'empty' AS mainfile, 
                                'empty' AS comment, 
                                kodispolruk AS par11, 
                                kodispol AS par12, 
                                MATCH (defaultTags, docnumberSTR, docnamefullm, docpartnernumberSTR, namezakfull, nameobjectfull, docnameshot, tags) AGAINST ('{$searchstring}' IN BOOLEAN MODE) as rel
                            FROM
                                dognet_docbase
                            WHERE
                                MATCH (defaultTags, docnumberSTR, docnamefullm, docpartnernumberSTR, namezakfull, nameobjectfull, docnameshot, tags) AGAINST ('{$searchstring}' IN BOOLEAN MODE)
                        )
                        UNION
                        (
                            SELECT
                                'dog_stage' AS type,
                                ID AS id,
                                kodkalplan AS kod,
                                koddoc AS par1,
                                namefullstage AS par2,
                                numberstage AS par3,
                                'empty' AS par4,
                                nameobjectshort AS par5,
                                nameobjectfull AS par6,
                                nameshotstage AS par7,
                                tags AS par8,
                                defaultTags AS par9,
                                'empty' AS par10,
                                'empty' AS mainfile, 
                                'empty' AS comment, 
                                'empty' AS par11, 
                                'empty' AS par12, 
                                MATCH (defaultTags, namefullstage, nameobjectfull, nameshotstage, nameobjectshort, tags) AGAINST ('{$searchstring}' IN BOOLEAN MODE) as rel
                            FROM
                                dognet_dockalplan
                            WHERE
                                MATCH (defaultTags, namefullstage, nameobjectfull, nameshotstage, nameobjectshort, tags) AGAINST ('{$searchstring}' IN BOOLEAN MODE)
                        )
                        ";
                    }

                    $sqlCommand_end .= "
                        ) AS sitewide 
                        WHERE rel > 0 
                        ORDER BY rel DESC 
                        LIMIT 250
                        ";
                    $sqlCommand = $sqlCommand_start . $sqlCommand_mail . $sqlCommand_dog . $sqlCommand_end;
                    $query = mysqlQuery($sqlCommand) or die(mysqli_error($sqlCommand));
                    $count = mysqli_num_rows($query);
                    if ($count > 1) {
                        $search_output .= '<div class="d-flex flex-row">';
                        $search_output .= '<div class="w-75">';
                        $search_output .= '<div class="result-searchSet small"><span class=""><span class="">' . $searchInMailUse_str . '</span><span class="">' . $searchInMailEnable_str . '</span></span></div>';
                        $search_output .= '<div class="result-searchSet small"><span class="">' . $searchInDogUse_str . '</span><span class="">' . $searchInDogEnable_str . '</span></div>';
                        $search_output .= '</div>';
                        $search_output .= '<div class="w-25">';
                        $search_output .= '<div class="text-right"><span class="result-searchCount">' . $count . '</span><span class="result-searchText mx-1">совпадений по запросу</span><span class="result-searchString">' . $searchstring . '</span></div>';
                        $search_output .= '</div>';
                        $search_output .= '</div>';
                        $i = 1;
                        while ($row = mysqli_fetch_array($query)) {
                            // 
                            $_resItem = '';
                            switch ($row["type"]) {
                                case 'mail_inbox':
                                    $serviceTitle = "Входящая почта";
                                    $docID = $row["kod"];
                                    $tagsDef = $row["par9"];
                                    $tags = $row["par8"];
                                    $comment = $row["comment"];

                                    $fileID = $row["mainfile"];
                                    $filelink = "";
                                    if (!empty($fileID)) {
                                        $_filesQuery = mysqli_fetch_assoc(mysqlQuery("SELECT file_originalname, file_url, file_extension FROM mailbox_incoming_files WHERE id = '{$fileID}'"));
                                        $filename = $_filesQuery['file_originalname'];
                                        $fileurl = $_filesQuery['file_url'];
                                        if ($_filesQuery['file_extension'] == 'pdf') {
                                            $filext = '<i class="fa-regular fa-file-pdf fa-lg"></i>';
                                        } elseif ($_filesQuery['file_extension'] == 'doc') {
                                            $filext = '<i class="fa-regular fa-file-word fa-lg"></i>';
                                        } elseif ($_filesQuery['file_extension'] == 'xls' || $_filesQuery['file_extension'] == 'xlsx') {
                                            $filext = '<i class="fa-regular fa-file-excel fa-lg"></i>';
                                        } else {
                                            $filext = '<i class="fa-regular fa-file fa-lg"></i>';
                                        }
                                        $filelink = !empty($filename && $fileurl) ? '<span class="file-ext mr-1">' . $filext . '</span><a href="' . $fileurl . '" class="filelink" target="_blank">' . $filename . '</a>' : "";
                                    }

                                    $mainlink = '<a class="mainlink" href="http://' . $_SERVER["HTTP_HOST"] . '/mailnew/index.php?type=in&mode=profile&uid=' . $row["kod"] . '" title="" target="_blank">' . $row["par5"] . '</a>';
                                    $servicelink = '<a href="http://' . $_SERVER["HTTP_HOST"] . '/mailnew/index.php?type=in&mode=thisyear" class="servicelink badge mr-3">' . $serviceTitle . '</a>';
                                    $serviceinfo = '<span class="caption mr-2">№</span><span style="" class="info mr-2">' . $row["par1"] . '</span><span class="caption mr-2">от</span><span style="" class="info mr-2">' . date('d.m.Y', strtotime($row["par2"])) . '</span>';
                                    $text_str1 = '<span class="caption mr-2">Контрагент</span><a href="http://' . $_SERVER["HTTP_HOST"] . '/sp/index.php?type=contragents&mode=profile&uid=' . $row["par4"] . '" class="info" title="" target="_blank">' . $row["par3"] . '</a>';
                                    $text_str2 = '<span class="caption mr-2">Получатель</span><span class="info">' . $row["par6"] . '</span>' . $separator1 . '<span class="caption mr-2">Ответственный(ые)</span><span class="info">' . $row["par7"] . '</span>';
                                    $text_str3 = '<span class="small">' . str_replace(',', ' , ', $tagsDef) . str_replace(',', ' , ', $tags) . '</span>';
                                    $text_comment = (!empty($comment) && $comment !== "---") ? '<span class="comment small">' . $comment . '</span>' : '';
                                    $text_str4 = '<span class="small">ID<span class="ml-1">' . $docID . '</span></span>';
                                    $file_links = '<span class="file-link small">' . $filelink . '</span>';
                                    $relevance = '<span class="small">Релевантность запросу<span class="ml-2">' . round($row["rel"], 2, PHP_ROUND_HALF_UP) . '</span></span>';
                                    //
                                    $_resItem .= '<div class="resultItem mail-inbox d-flex flex-column shadow-sm">';
                                    $_resItem .= '<div class="d-flex flex-row">';
                                    $_resItem .= '<div class="d-flex flex-column w-75">';
                                    $_resItem .= '<div class="resultItem-link"><span class="item-id mr-2">' . $i . '.</span>' . $mainlink . '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '<div class="d-flex flex-column w-25">';
                                    $_resItem .= '<div class="resultItem-text text-right">' . $text_str4 . '</div>';
                                    $_resItem .= '<div class="resultItem-text text-right">' . $relevance . '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '<div class="d-flex flex-column w-100">';
                                    $_resItem .= '<div class="resultItem-badges mb-1">' . $servicelink . $serviceinfo . '</div>';
                                    $_resItem .= '<div class="resultItem-text mb-2">' . $text_str1 . '' . $separator1 . '' . $text_str2 . '</div>';
                                    // $_resItem .= '<div class="resultItem-text mb-1">' . $text_str2 . '</div>';
                                    $_resItem .= '<div class="resultItem-text">' . $text_comment . '</div>';
                                    $_resItem .= '<div class="resultItem-text">' . $file_links . '</div>';
                                    $_resItem .= '<div class="resultItem-text">' . $text_str3 . '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '<|>';
                                    break;
                                case 'mail_outbox':
                                    $serviceTitle = "Исходящая почта";
                                    $docID = $row["kod"];
                                    $tagsDef = $row["par9"];
                                    $tags = $row["par8"];
                                    $comment = $row["comment"];

                                    $fileID = $row["mainfile"];
                                    $filelink = "";
                                    if (!empty($fileID)) {
                                        $_filesQuery = mysqli_fetch_assoc(mysqlQuery("SELECT file_originalname, file_url, file_extension FROM mailbox_outgoing_files WHERE id = '{$fileID}'"));
                                        $filename = $_filesQuery['file_originalname'];
                                        $fileurl = $_filesQuery['file_url'];
                                        if ($_filesQuery['file_extension'] == 'pdf') {
                                            $filext = '<i class="fa-regular fa-file-pdf fa-lg"></i>';
                                        } elseif ($_filesQuery['file_extension'] == 'doc') {
                                            $filext = '<i class="fa-regular fa-file-word fa-lg"></i>';
                                        } elseif ($_filesQuery['file_extension'] == 'xls' || $_filesQuery['file_extension'] == 'xlsx') {
                                            $filext = '<i class="fa-regular fa-file-excel fa-lg"></i>';
                                        } else {
                                            $filext = '<i class="fa-regular fa-file fa-lg"></i>';
                                        }
                                        $filelink = !empty($filename && $fileurl) ? '<span class="file-ext mr-1">' . $filext . '</span><a href="' . $fileurl . '" class="filelink" target="_blank">' . $filename . '</a>' : "";
                                    }

                                    $mainlink = '<a class="mainlink" href="http://' . $_SERVER["HTTP_HOST"] . '/mailnew/index.php?type=out&mode=profile&uid=' . $row["kod"] . '" title="" target="_blank">' . $row["par5"] . '</a>';
                                    $servicelink = '<a href="http://' . $_SERVER["HTTP_HOST"] . '/mailnew/index.php?type=out&mode=thisyear" class="servicelink badge mr-3">' . $serviceTitle . '</a>';
                                    $serviceinfo = '<span class="caption mr-2">№</span><span style="" class="info mr-2">' . $row["par1"] . '</span><span class="caption mr-2">от</span><span style="" class="info mr-2">' . date('d.m.Y', strtotime($row["par2"])) . '</span>';
                                    $text_str1 = '<span class="caption mr-2">Контрагент</span><a href="http://' . $_SERVER["HTTP_HOST"] . '/sp/index.php?type=contragents&mode=profile&uid=' . $row["par4"] . '" class="info" title="" target="_blank">' . $row["par3"] . '</a>';
                                    $text_str2 = '<span class="caption mr-2">Отправитель</span><span class="info">' . $row["par6"] . '</span>' . $separator1 . '<span class="caption mr-2">Исполнитель</span><span class="info">' . $row["par7"] . '</span>';
                                    $text_str3 = '<span class="small">' . str_replace(',', ' , ', $tagsDef) . str_replace(',', ' , ', $tags) . '</span>';
                                    $text_comment = (!empty($comment) && $comment !== "---") ? '<span class="comment small">' . $comment . '</span>' : '';
                                    $text_str4 = '<span class="small">ID<span class="ml-1">' . $docID . '</span></span>';
                                    $file_links = '<span class="file-link small">' . $filelink . '</span>';
                                    $relevance = '<span class="small">Релевантность запросу<span class="ml-2">' . round($row["rel"], 2, PHP_ROUND_HALF_UP) . '</span></span>';
                                    //
                                    $_resItem .= '<div class="resultItem mail-outbox d-flex flex-column shadow-sm">';
                                    $_resItem .= '<div class="d-flex flex-row">';
                                    $_resItem .= '<div class="d-flex flex-column w-75">';
                                    $_resItem .= '<div class="resultItem-link"><span class="item-id mr-2">' . $i . '.</span>' . $mainlink . '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '<div class="d-flex flex-column w-25">';
                                    $_resItem .= '<div class="resultItem-text text-right">' . $text_str4 . '</div>';
                                    $_resItem .= '<div class="resultItem-text text-right">' . $relevance . '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '<div class="d-flex flex-column w-100">';
                                    $_resItem .= '<div class="resultItem-badges mb-1">' . $servicelink . $serviceinfo . '</div>';
                                    $_resItem .= '<div class="resultItem-text mb-2">' . $text_str1 . $separator1 . $text_str2 . '</div>';
                                    // $_resItem .= '<div class="resultItem-text mb-1">' . $text_str2 . '</div>';
                                    $_resItem .= '<div class="resultItem-text">' . $text_comment . '</div>';
                                    $_resItem .= '<div class="resultItem-text">' . $file_links . '</div>';
                                    $_resItem .= '<div class="resultItem-text">' . $text_str3 . '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '<|>';
                                    break;
                                case 'dog_base':
                                    $serviceTitle = "Договор";
                                    $docID = $row["kod"];
                                    $tagsDef = $row["par9"];
                                    $tags = $row["par8"];
                                    $comment = $row["comment"];
                                    $kodispolruk = $row["par11"];
                                    $kodispol = $row["par12"];
                                    $docPartnerNumnber = !empty($row["par3"]) ? $row["par3"] : '<span class="noinfo text-secondary">---</span>';
                                    $_ispolRukQuery = mysqli_fetch_assoc(mysqlQuery("SELECT ispolrukname, ispolruknamefull FROM dognet_spispolruk WHERE kodispolruk = '{$kodispolruk}'"));
                                    $_ispolQuery = mysqli_fetch_assoc(mysqlQuery("SELECT ispolnameshot, ispolnamefull FROM dognet_spispol WHERE kodispol = '{$kodispol}'"));

                                    $filelink = "";
                                    if (!empty($docID)) {
                                        $_filesQuery = mysqlQuery("SELECT file_originalname, file_url, file_extension FROM dognet_docpaper_files WHERE koddoc = '{$docID}'");
                                        while ($_filesRow = mysqli_fetch_array($_filesQuery)) {
                                            $filename = $_filesRow['file_originalname'];
                                            $fileurl = $_filesRow['file_url'];
                                            if ($_filesRow['file_extension'] == 'pdf') {
                                                $filext = '<i class="fa-regular fa-file-pdf fa-lg"></i>';
                                            } elseif ($_filesRow['file_extension'] == 'doc') {
                                                $filext = '<i class="fa-regular fa-file-word fa-lg"></i>';
                                            } elseif ($_filesRow['file_extension'] == 'xls' || $_filesRow['file_extension'] == 'xlsx') {
                                                $filext = '<i class="fa-regular fa-file-excel fa-lg"></i>';
                                            } else {
                                                $filext = '<i class="fa-regular fa-file fa-lg"></i>';
                                            }
                                            $filelink .= !empty($filename && $fileurl) ? '<span class="doc-base file-ext mr-1">' . $filext . '</span><a href="' . $fileurl . '" class="doc-base filelink" target="_blank">' . $filename . '</a>' : "";
                                            $filelink .= ' , ';
                                        }
                                        $filelink = rtrim($filelink, ' , ');
                                    }

                                    $mainlink = '<a class="mainlink" href="http://' . $_SERVER["HTTP_HOST"] . '/dognet/dognet-docview.php?docview_type=details&uniqueID=' . $row["kod"] . '" title="" target="_blank">' . $row["par6"] . '</a>';
                                    $servicelink = '<a href="http://' . $_SERVER["HTTP_HOST"] . '/dognet" class="servicelink badge mr-3">' . $serviceTitle . '</a>';
                                    $serviceinfo = '<span class="caption mr-2">Номер АТГС</span><span style="" class="info mr-2">' . $row["par1"] . '</span><span class="caption mr-2">от</span><span style="" class="info">' . date("Y", strtotime($row["par2"])) . 'г.</span>' . $separator1 . '<span class="caption mr-2">Номер партнера</span><span style="" class="info mr-2">' . $docPartnerNumnber  . '</span>';
                                    $text_str1 = '<span class="caption mr-2">Заказчик</span><a href="http://' . $_SERVER["HTTP_HOST"] . '/sp/index.php?type=contragents&mode=profile&uid=" class="info" title="" target="_blank">' . $row["par7"] . '</a>' . $separator1 . '<span class="caption mr-2">Объект</span><span class="info">' . $row["par4"] . '</span>';
                                    $text_str2 = '<span class="caption mr-2">Руководитель</span><span class="info">' . (!empty($_ispolRukQuery["ispolrukname"]) ? $_ispolRukQuery["ispolrukname"] : "") . '</span>' . $separator1 . '<span class="caption mr-2">ГИП</span><span class="info">' . (!empty($_ispolQuery["ispolnameshot"]) ? $_ispolQuery["ispolnameshot"] : "") . '</span>';
                                    $text_str3 = '<span class="small">' . str_replace(',', ' , ', $tagsDef) . str_replace(',', ' , ', $tags) . '</span>';
                                    $text_str4 = '<span class="small">ID<span class="ml-1">' . $docID . '</span></span>';
                                    $file_links = '<span class="file-link small">' . $filelink . '</span>';
                                    $relevance = '<span class="small">Релевантность запросу<span class="ml-2">' . round($row["rel"], 2, PHP_ROUND_HALF_UP) . '</span></span>';
                                    // 
                                    $_resItem .= '<div class="resultItem doc-base d-flex flex-column shadow-sm">';
                                    $_resItem .= '<div class="d-flex flex-row">';
                                    $_resItem .= '<div class="d-flex flex-column w-75">';
                                    $_resItem .= '<div class="resultItem-link"><span class="item-id mr-2">' . $i . '.</span>' . $mainlink . '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '<div class="d-flex flex-column w-25">';
                                    $_resItem .= '<div class="resultItem-text text-right">' . $text_str4 . '</div>';
                                    $_resItem .= '<div class="resultItem-text text-right">' . $relevance . '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '<div class="d-flex flex-column w-100">';
                                    $_resItem .= '<div class="resultItem-badges mb-1">' . $servicelink . $serviceinfo . '</div>';
                                    $_resItem .= '<div class="resultItem-text mb-2">' . $text_str1 . $separator1 . $text_str2 . '</div>';
                                    $_resItem .= '<div class="resultItem-text">' . $file_links . '</div>';
                                    $_resItem .= '<div class="resultItem-text">' . $text_str3 . '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '<|>';
                                    break;
                                case 'dog_stage':
                                    $serviceTitle = "Этап";
                                    $docID = $row["kod"];
                                    $tagsDef = $row["par9"];
                                    $tags = $row["par8"];
                                    $koddoc = $row["par1"];
                                    $numberstage = $row["par3"];
                                    $namestage = $row["par7"];
                                    $nameobject = $row["par6"];
                                    $_ispolDocnumber = mysqli_fetch_assoc(mysqlQuery("SELECT docnumber FROM dognet_docbase WHERE koddoc = '{$koddoc}'"));
                                    $docnumber = !empty($_ispolDocnumber["docnumber"]) ? $_ispolDocnumber["docnumber"] : 'не найден';

                                    $mainlink = '<a class="mainlink" href="http://' . $_SERVER["HTTP_HOST"] . '/dognet/dognet-docview.php?docview_type=details&uniqueID=' . $koddoc . '" title="" target="_blank">' . $namestage . '</a>';
                                    $servicelink = '<a href="http://' . $_SERVER["HTTP_HOST"] . '/dognet" class="servicelink badge mr-3">' . $serviceTitle . '</a>';
                                    $serviceinfo = '<span class="caption mr-2">Этап</span><span style="" class="info">' . $numberstage . '</span>' . $separator1 . '<span class="caption mr-2">Договор</span><span style="" class="info">' . $docnumber . '</span>' . $separator1 . '<span class="caption mr-2">Объект</span><span class="info">' . $nameobject . '</span>';
                                    $text_str3 = '<span class="text-secondary small">' . str_replace(',', ' , ', $tagsDef) . str_replace(',', ' , ', $tags) . '</span>';
                                    $text_str4 = '<span class="small">ID<span class="ml-1">' . $docID . '</span></span>';
                                    $relevance = '<span class="small">Релевантность запросу<span class="ml-2">' . round($row["rel"], 2, PHP_ROUND_HALF_UP) . '</span></span>';

                                    $_resItem .= '<div class="resultItem doc-stage d-flex flex-column shadow-sm">';
                                    $_resItem .= '<div class="d-flex flex-row">';
                                    $_resItem .= '<div class="d-flex flex-column w-75">';
                                    $_resItem .= '<div class="resultItem-link"><span class="item-id mr-2">' . $i . '.</span>' . $mainlink . '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '<div class="d-flex flex-column w-25">';
                                    $_resItem .= '<div class="resultItem-text text-right">' . $text_str4 . '</div>';
                                    $_resItem .= '<div class="resultItem-text text-right">' . $relevance . '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '<div class="d-flex flex-column w-100">';
                                    $_resItem .= '<div class="resultItem-badges mb-1">' . $servicelink . $serviceinfo . '</div>';
                                    $_resItem .= '<div class="resultItem-text">' . $text_str3 . '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '</div>';
                                    $_resItem .= '<|>';
                                    break;
                                case 'dog_sub':
                                    $serviceTitle = "Субподряд";
                                    break;
                                case 'contragent':
                                    $serviceTitle = "Справочник";
                                    break;
                                default:
                                    $serviceTitle = "Общий поиск";
                                    break;
                            }
                            // Убираем разделитель, если не используем jquery pagination при выводе
                            // $search_output .= $_resItem;
                            $search_output .= str_replace('<|>', '', $_resItem);
                            $i++;
                        } // close while
                    } else {
                        $search_output .= '<div class="small mr-2">' . $searchInMailUse_str . '<span class="">' . $searchInMailEnable_str . '</span></div>';
                        $search_output .= '<div class="small mr-2">' . $searchInDogUse_str . '<span class="">' . $searchInDogEnable_str . '</span></div>';
                        $search_output .= '<div class="noResult my-3">По запросу <b>' . $searchstring . '</b> ничего не найдено</div>';
                    }
                } else {
                    $search_output = '<div class="errorResult text-warning mb-1">Проверьте настройки. Судя по всему, не выбрана ни одна область для поиска.</div>';
                }
            } else {
                $search_output = '<div class="errorResult text-warning mb-1">Пустая строка запроса...</div>';
            }
        } else {
            $search_output = '<div class="errorResult text-danger mb-1">Что-то пошло не так...</div>';
        }
    }
}
unset($_POST);
// Вывод сообщений о результате загрузки.
echo $search_output;
