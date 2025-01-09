<?php
# ### ### ### ### ### ### ### ### ### ### ### ### ### ### ###
# ### ### ### ### ### ### ### ### ### ### ### ### ### ### ###
$navbarTopmain_class = "container-fluid border-bottom border-dark fixed-top";
$navbarTopmain_style = "margin-bottom:110px";
$themeIcon_class = "fa-solid fa-circle-half-stroke";
$cardTitle_H5_class = "card-title mb-4 text-white text-center";
$cardBody_textColor_class = "text-secondary";
$dinnerOrder_btn_class = "btn btn-outline-warning btn-lg w-100";
$getSpTel_btn_class = "btn btn-outline-warning btn-lg w-100";
$tmpJoke1 = "тьмы";
# ### ### ### ### ### ### ### ### ### ### ### ### ### ### ###
# ### ### ### ### ### ### ### ### ### ### ### ### ### ### ###
?>
<style>
#portalnew-navbar .navbar-brand {
    margin-right: 0;
}

#portalnew-navbar button.btn.btn-link {
    color: #6C757D;
}

#portalnew-navbar button.btn.btn-link:hover {
    color: #6C757D;
    text-decoration: none;
}

#portalnew-navbar button.btn.btn-link:focus {
    box-shadow: none;
    text-decoration: none;
}

div#switchTheme-icon:hover {
    cursor: pointer;
}

/* --- --- --- --- TOP SERVICE ICONS CSS --- --- --- --- */
#portalnew-main-center-icons {}

#portalnew-main-center-icons .service-item .service-title {
    color: #CCCCCC !important;
    font-size: 0.9rem;
}

#portalnew-main-center-icons .service-item:hover .service-title {
    color: #FFFFFF !important;
}

#portalnew-main-center-icons .service-item.inactive .service-title {
    color: #666666 !important;
    font-size: 0.9rem;
}

#portalnew-main-center-icons .service-item:hover.inactive .service-title {
    color: #CCCCCC !important;
}

/* --- --- --- --- USER SETTINGS MAIN ELEMENTS CSS --- --- --- --- */

/* Main Input */
#portalnew-main-input {
    color: #CCCCCC;
    background-color: #28292A;
    height: 5rem;
}

#portalnew-main-input input.main-input {
    color: #CCCCCC;
    border-color: transparent;
    background-color: transparent !important;
    height: 5rem;
}

#portalnew-main-input input.main-input::placeholder {
    color: #CCCCCC;
}

#portalnew-main-input.input-inactive-border {
    border: 2px solid #495057;
    border-radius: 1rem;
}

#portalnew-main-input.input-active-border {
    border: 2px solid #F3AD2E;
    border-radius: 1rem;
}

#portalnew-main-input input.main-input:focus {
    border-color: transparent;
    box-shadow: none;
}

#searchInput-clear:hover,
#searchInput-settings:hover,
#searchInput-help:hover {
    cursor: pointer;
    color: white !important;
}

/* ----------*/
.corner-box {
    /* border: 1px solid #6C757D; */
    border: 1px solid #212529;
}

.corner-box-topC:before,
.corner-box-topC:after,
.corner-box-bottomL:before,
.corner-box-bottomL:after,
.corner-box-bottomR:before,
.corner-box-bottomR:after {
    background: #161617 !important;
}

.corner-box-topL:before,
.corner-box-topL:after,
.corner-box-topR:before,
.corner-box-topR:after,
.corner-box-bottomC:before,
.corner-box-bottomC:after {
    background: #28292A !important;
}

.card.corner-box-bottomC,
.card.corner-box-topL,
.card.corner-box-topR {
    background: #28292A !important;
}

.card.corner-box-topC,
.card.corner-box-bottomL,
.card.corner-box-bottomR {
    background: none !important;
    background-color: transparent !important;
}

/* --- --- --- --- USER SETTINGS MODAL CSS --- --- --- --- */

#userSettings-modal .modal-dialog .modal-body h1,
#userSettings-modal .modal-dialog .modal-body h2,
#userSettings-modal .modal-dialog .modal-body h3 {
    /* font-family: "Stolzl Book", Arial, Helvetica Neue, Helvetica, sans-serif; */
    font-family: "HeliosCond", Arial, Helvetica Neue, Helvetica, sans-serif;
    color: #AAAAAA;
}

#userSettings-modal .modal-dialog .modal-body h3 {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    font-weight: 600;
}

#userSettings-modal .modal-dialog .modal-body .exclamation {
    color: #FFC107 !important;
}

#userSettings-modal .modal-dialog .modal-content.modal-customstyle-1 {
    background-color: #333 !important;
    border: 3px solid #333;
    border-radius: 1rem;
}

#userSettings-modal .modal-dialog .modal-body .custom-switch {
    color: #BBBBBB !important;
}

#userSettings-modal .modal-dialog .modal-body .custom-switch label {
    font-size: 1.0rem;
    line-height: 1.25rem;
}

#userSettings-modal .modal-dialog .modal-body .exclamation {
    color: #999999 !important;
}

#userSettings-block {
    padding: 0;
}

/* --- --- --- --- LIST MESSAGES MODAL CSS --- --- --- --- */

#ListMessages-modal .modal-dialog .modal-body h1,
#ListMessages-modal .modal-dialog .modal-body h2,
#ListMessages-modal .modal-dialog .modal-body h3 {
    /* font-family: "Stolzl Book", Arial, Helvetica Neue, Helvetica, sans-serif; */
    font-family: "HeliosCond", Arial, Helvetica Neue, Helvetica, sans-serif;
    color: #AAAAAA
}

#ListMessages-modal .modal-dialog .modal-body h3 {
    font-size: 1.5rem;
    margin-bottom: 1.75rem;
}

#ListMessages-modal .modal-dialog .modal-content.modal-customstyle-1 {
    background-color: #333 !important;
    border: 3px solid #333;
    border-radius: 1rem;
}

/* --- --- --- --- SEARCH SETTINGS MODAL CSS --- --- --- --- */

#searchingSettings-modal .modal-dialog .modal-body h1,
#searchingSettings-modal .modal-dialog .modal-body h2,
#searchingSettings-modal .modal-dialog .modal-body h3 {
    /* font-family: "Stolzl Book", Arial, Helvetica Neue, Helvetica, sans-serif; */
    font-family: "HeliosCond", Arial, Helvetica Neue, Helvetica, sans-serif;
    color: #AAAAAA
}

#searchingSettings-modal .modal-dialog .modal-body h3 {
    font-size: 1.5rem;
    margin-bottom: 1.75rem;
}

#searchingSettings-modal .modal-dialog .modal-content.modal-customstyle-1 {
    background-color: #333 !important;
    border: 3px solid #333;
    border-radius: 1rem;
}

#searchingSettings-modal .modal-dialog .modal-body .exclamation {
    color: #999999 !important;
}

/* --- --- --- --- SEARCH OUTPUT MODAL CSS --- --- --- --- */
#searchingOutput-modal .modal-header h5.modal-title {
    color: #28292A;
}

#searchingOutput-modal .modal-dialog .modal-content .modal-body .result-searchString,
#searchingOutput-modal .modal-dialog .modal-content .modal-body .result-searchCount {
    color: #111111 !important;
    font-weight: 600;
    font-size: 0.85rem;
}

#searchingOutput-modal .modal-dialog .modal-content .modal-body .result-searchText {
    color: #666666 !important;
    font-size: 0.85rem;
}

#searchingOutput-modal .resultItem {
    font-family: "Stolzl Book", Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 12px;
    font-weight: 400;
    padding: 0.5rem 0.5rem;
    margin: 1.25rem 0;
    /* border-radius: 1rem; */
    /* border-bottom: 1px solid #6C757D; */
}

#searchingOutput-modal .modal-dialog .modal-content {
    background-color: #F1F1F1 !important;
    color: #111111 !important;
}


#searchingOutput-modal .resultItem .resultItem-link .item-id {
    color: #111111 !important;
}

#searchingOutput-modal .resultItem .resultItem-link a,
#searchingOutput-modal .resultItem .resultItem-link a:hover {
    color: #315EFB;
}

#searchingOutput-modal .resultItem .resultItem-text a.info {
    color: #000000 !important;
    text-decoration: underline;
}

#searchingOutput-modal .resultItem .resultItem-text a.info:hover {
    color: #000000 !important;
    text-decoration: none;
}

/* -- -- -- -- -- -- -- -- -- -- */
#searchingOutput-modal .resultItem .resultItem-badges .caption,
#searchingOutput-modal .resultItem .resultItem-badges .info,
#searchingOutput-modal .resultItem .resultItem-text .caption,
#searchingOutput-modal .resultItem .resultItem-text .info,
#searchingOutput-modal .resultItem .resultItem-text .noinfo {
    font-size: 0.75rem;
}

#searchingOutput-modal .resultItem .resultItem-badges .info,
#searchingOutput-modal .resultItem .resultItem-text .info {
    color: #000000 !important;
    font-weight: 600;
}

#searchingOutput-modal .resultItem .resultItem-text .noinfo {
    color: #999999 !important;
}

#searchingOutput-modal .resultItem .resultItem-badges .caption,
#searchingOutput-modal .resultItem .resultItem-text .caption {
    color: #666666 !important;
}

/* -- -- -- -- -- -- -- -- -- -- */

#searchingOutput-modal .resultItem .resultItem-badges .servicelink.badge {
    color: #F1F1F1 !important;
    background-color: #111111 !important;
    font-weight: 200;
    font-size: 0.75rem;
}


#searchingOutput-modal .resultItem .resultItem-text .comment {
    color: #000000 !important;
}

#searchingOutput-modal .resultItem .resultItem-text .file-ext,
#searchingOutput-modal .resultItem .resultItem-text .file-link a,
#searchingOutput-modal .resultItem .resultItem-text .file-link a:hover {
    font-family: "Stolzl", Arial, Helvetica Neue, Helvetica, sans-serif;
    color: #000000;
}

#searchingOutput-modal .resultItem .resultItem-text .file-link a {
    text-decoration: underline;
}

#searchingOutput-modal .resultItem .resultItem-text .file-link:hover a {
    text-decoration: none;
}

/* --- --- --- --- HELP OUTPUT MODAL CSS --- --- --- --- */
#loadedData-help-modal .modal-dialog .modal-content {
    background-color: #F1F1F1 !important;
    color: #28292A !important;
}

#loadedData-help-modal .modal-dialog .modal-body {
    font-family: "Stolzl", Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 12px;
}

#loadedData-help-modal .modal-dialog .modal-body h3.h-3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111111;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}

#loadedData-help-modal .modal-dialog .modal-body .text-accent {
    color: #74000B;
}

#loadedData-help-modal .modal-dialog .modal-body p {
    font-size: 0.85rem;
    color: #28292A;
    line-height: 1.25rem;
    margin-bottom: 0.5rem;
}

#loadedData-help-modal .modal-dialog .modal-body ul {
    font-size: 0.85rem;
    color: #495057;
    margin-bottom: 0.75rem;
}

#loadedData-help-modal .modal-dialog .modal-header .modal-title,
#loadedData-help-modal .modal-dialog .modal-header .close {
    color: #28292A;
}

#loadedData-help-modal .modal .modal-header button.close {
    text-shadow: none;
    color: #CCCCCC;
}


#loadedData-help-modal .modal-header {
    border-bottom-color: #495057;
}

#loadedData-help-modal .modal-footer {
    border-top-color: #495057;
}

#loadedData-help-modal .modal-header h5.modal-title {
    font-family: 'Helioscond', sans-serif;
    font-size: 1.8rem;
    color: #CCCCCC;
}

/* --- --- --- --- CLOUD OUTPUT MODAL CSS --- --- --- --- */

#portalCloud-listFiles-modal {}

#portalCloud-listFiles-modal .modal-dialog .modal-content {
    border: 3px solid #333;
    border-radius: 1rem;
}

#portalCloud-listFiles-modal .modal-dialog .modal-body {
    background-color: #333333;
    color: #F1F1F1;
}

#portalCloud-listFiles-modal table tbody tr td span a,
#portalCloud-listFiles-modal table tbody tr td span a:hover {
    color: #007BFF;
}

#portalCloud-listFiles-modal table tbody tr td span a {
    text-decoration: underline;
}

#portalCloud-listFiles-modal table tbody tr td span a:hover {
    text-decoration: none;
}

#portalnew-main-bottom-files {}

#portalnew-main-bottom-files .card-title {
    color: #FFFFFF !important;
}

#portalnew-main-bottom-files .drop-container {
    border-style: dashed !important;
    border-width: 2px !important;
}

#portalnew-main-bottom-files .drag-none {
    border-color: #343a40 !important;
    color: #CCCCCC !important;
}

#portalnew-main-bottom-files .drag-caption {
    color: #666666 !important;
    font-size: 3.0rem;
    line-height: 1.8rem
}

#portalnew-main-bottom-files .drag-caption span.special-text {
    font-size: 0.9rem;
    color: #666666 !important;
}

#portalnew-main-bottom-files .drag-over {
    border-color: #FFC107;
}

#portalnew-main-bottom-files .drag-caption.over {
    color: #FFC107;
}

/*
! ----- ----- ----- ----- -----
! ПРОФИЛЬ ПОЛЬЗОВАТЕЛЯ
! ----- ----- ----- ----- -----
*/
#profile-title,
#profile-top,
#profile-main {
    font-family: "Stolzl Book", Arial, Helvetica Neue, Helvetica, sans-serif;
}

#profile-title {
    color: #FFFFFF !important;
}

#profile-main {
    color: #999999 !important;
}

#profile .card,
#profile .card .card-header,
#profile .card .card-footer,
#profile .card .card-body,
#profile .card .card-body .list-group-item {
    background: transparent !important;
}

#profile .card {
    border-color: #28292A;
}

#profile .card .card-header,
#profile .card .card-footer {
    border: 0;
}

#profile label,
#profile .card .card-header {
    font-family: "Stolzl Book", Arial, Helvetica Neue, Helvetica, sans-serif;
    font-weight: 200;
    color: #999999 !important;
}

#profile label {
    margin-bottom: 0;
}

#profile .card .card-body .list-group-item {
    color: #999999 !important;
    border: 0;
}

#profile h3.title {
    font-family: 'HeliosCond', sans-serif;
    font-size: 2.0rem;
    font-weight: 200;
    color: #FFFFFF !important;
}

#profile p.small-text {
    font-family: "Stolzl Book", Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 0.75rem;
    color: #666666;
    line-height: 1rem;
}

#dinner-block1,
#dinner-block2 {
    opacity: 0;
    animation: dinner-block 5.0s forwards
}

@keyframes dinner-block {
    0% {
        opacity: 0
    }

    100% {
        opacity: 1
    }
}

#profile-main input {
    color: #FFC107;
    background-color: #28292A;
    border-color: #28292A;
}

#profile-main input:disabled {
    color: #333333;
    background-color: #1c1c1c;
    border-color: #28292A;
}

#profile-main input::placeholder {
    color: #444444;
}

#profile-main div.card-body,
#profile-main div.card-body p {
    font-size: 14px
}

#profile-main a.card-link {
    color: #0d6efd
}

#profile-main a.card-link:hover {
    color: #0a58ca
}

#profile-main h3.card-title {
    font-size: 24px;
    font-family: "Stolzl Bold", Arial, Helvetica Neue, Helvetica, sans-serif;
    margin-bottom: 2.0rem
}

#profile-main h4.card-title {
    font-size: 20px;
    font-family: "Stolzl Book", Arial, Helvetica Neue, Helvetica, sans-serif
}

#profile-main h5.card-title {
    font-size: 15px;
    font-family: "Stolzl Regular", Arial, Helvetica Neue, Helvetica, sans-serif
}

#profile-main .nav .nav-link {
    color: #999999
}

#profile-main .nav .nav-link.active,
#profile-main .nav .nav-link:hover {
    color: #000000;
    background-color: #F1F1F1;
}

/*
! ----- ----- ----- ----- -----
! SYSLOG
! ----- ----- ----- ----- -----
*/
#portalmain-syslog>tbody {
    color: #D4D4D4;
}

#portalmain-syslog>tbody>tr.odd {
    background-color: #111111 !important;
}

#portalmain-syslog>tbody>tr.even {
    background-color: #161617 !important;
}


#portalmain-syslog>tbody>tr>td:last-child.dataTables_empty {
    color: #999999 !important;
    text-align: center !important;
}

#portalmain-syslog-block,
#portalmain-syslog-block .dataTables_scrollBody,
#portalmain-staffNews,
#portalmain-staffAbsence,
#portalmain-staffBirthdays

/* override x.xhtml.ru style */
    {
    scrollbar-width: thin;
    scrollbar-color: #F3AD2E #222222;
}

#portalmain-syslog-block::-webkit-scrollbar,
#portalmain-syslog-block .dataTables_scrollBody::-webkit-scrollbar,
#portalmain-staffNews::-webkit-scrollbar,
#portalmain-staffAbsence::-webkit-scrollbar,
#portalmain-staffBirthdays::-webkit-scrollbar {
    height: 12px;
    width: 12px;
}

#portalmain-syslog-block::-webkit-scrollbar-track,
#portalmain-syslog-block .dataTables_scrollBody::-webkit-scrollbar-track,
#portalmain-staffNews::-webkit-scrollbar-track,
#portalmain-staffAbsence::-webkit-scrollbar-track,
#portalmain-staffBirthdays::-webkit-scrollbar-track {
    background: #222222;
}

#portalmain-syslog-block::-webkit-scrollbar-thumb,
#portalmain-syslog-block .dataTables_scrollBody::-webkit-scrollbar-thumb,
#portalmain-staffNews::-webkit-scrollbar-thumb,
#portalmain-staffAbsence::-webkit-scrollbar-thumb,
#portalmain-staffBirthdays::-webkit-scrollbar-thumb {
    background-color: #F3AD2E;
    border-radius: 5px;
    border: 3px solid #222222;
}

#portalmain-staffNews .reportStatus,
#portalmain-staffAbsence .reportStatus,
#portalmain-staffBirthdays .reportStatus {
    width: 100%;
    background-color: #1A1A1A !important;
    border-radius: 0.5rem;
}

#portalmain-staffNews .reportStatus:hover,
#portalmain-staffAbsence .reportStatus:hover,
#portalmain-staffBirthdays .reportStatus:hover {
    background-color: #111111 !important;
}

#portalmain-staffNews .reportStatus h5,
#portalmain-staffAbsence .reportStatus h5,
#portalmain-staffBirthdays .reportStatus h5 {
    font-size: 0.85rem;
    color: #ffc107 !important;
}

#portalmain-staffNews .reportStatus p,
#portalmain-staffAbsence .reportStatus p,
#portalmain-staffBirthdays .reportStatus p {
    font-size: 0.75rem;
    margin-bottom: 0;
}

#portalmain-staffNews .reportStatus:hover p,
#portalmain-staffAbsence .reportStatus:hover p,
#portalmain-staffBirthdays .reportStatus:hover p {
    color: #CCCCCC !important;
}

#portalmain-syslog>tbody>tr>td span.badge {
    font-size: 0.7rem;
    font-weight: 200;
    color: #fff;
    background-color: #4d4d4d !important;
}

/*
! ----- ----- ----- ----- -----
! STAFF NEWS
! ----- ----- ----- ----- -----
*/
#portalmain-staffNews .nameIn {
    color: white !important;
    font-weight: 600;
}

#portalmain-staffNews .nameOut {
    color: #CCCCCC !important;
    font-weight: 600;

}

/*
! ----- ----- ----- ----- -----
! MARQUEE
! ----- ----- ----- ----- -----
*/
.marquee__item span.item__el1 {
    color: white !important;
    padding: 0.2rem 0.5rem;
    background-color: #17a2b8;
    border: 1px #17a2b8 solid;
    border-radius: 4px;
    margin-right: 0.5rem;
}

.marquee__item span.item__el2 {
    color: #ffc107 !important;
}

.marquee__item span.item__el2 a {
    color: #17a2b8 !important;
}

.marquee__item span.item__el2 a:hover,
.marquee__item span.item__el2 a:visited {
    color: #17a2b8 !important;
    text-decoration: underline;
}

    {
    color: #17a2b8 !important;
}

.marquee__item span.item__el3 {
    color: white !important;
}

.marquee__item span.item__el4 {
    color: white !important;
}

/* --- --- --- --- CHANGES LIST CSS --- --- --- --- */
#changeslogList .title {
    margin-bottom: 2.25rem;
}

#changeslogList .title h3 {
    color: #FFC107 !important;
}

#changeslogList .title h3 span.small {
    color: #AAAAAA !important;
}

#changeslogList h4.title {
    font-family: 'Stolzl Book', sans-serif;
    font-size: 1.55rem;
    color: #F1F1F1 !important;
}

#changeslogList .item .maintext {
    font-family: 'Stolzl Book', sans-serif;
    font-size: 0.95rem;
    font-weight: 400;
    line-height: 1.25rem;
    color: #CCCCCC !important;
}

#changeslogList .item .specialtext {
    margin-top: 0.5rem;
    /* display: flex;
        flex-direction: row; */
}

#changeslogList .item .specialtext span {
    font-family: 'Stolzl Book', sans-serif;
    font-size: 0.8rem;
    font-weight: 400;
    line-height: 1.25rem;
    color: #666666 !important;
}

#changeslogList .item .specialtext span.textLabel {
    color: #666666 !important;
    /* border: 1px solid #666666; */
    padding: 0 0.35rem;
    border-radius: 0.15rem;
    background-color: #101010 !important;
    color: #AAAAAA !important;
}

#portalmain-workStatus .workstatus-btn {
    align-content: center;
    text-align: center;
    background-color: #F3AD2E !important;
    color: black;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
}

#portalmain-workStatus .reportStatus:hover {
    cursor: pointer;
}

#portalmain-workStatus .reportStatus:hover .control-btn {
    color: #F3AD2E !important;
}

#portalmain-workStatus .control-btn {
    align-content: center;
    text-align: center;
    background-color: transparent !important;
    width: 1.0rem;
    height: 1.0rem;
    border-radius: 50%;
    top: -7px;
    position: relative;
    right: -7px;
}

#portalmain-workStatus .workstatus-btn:hover {
    background-color: #FAFAFA !important;
    cursor: pointer;
}

#modal-workStatus .modal-header {
    background-color: #1A1A1A !important;
    color: white;
}

#modal-workStatus .modal-body,
#modal-workStatus .modal-body label,
#modal-workStatus .modal-body input,
#modal-workStatus .modal-body select,
#modal-workStatus .modal-body textarea {
    font-size: 0.85rem;
}

#modal-workStatus .modal-body input,
#modal-workStatus .modal-body select {
    height: 2.2rem;
}

#modal-workStatus .modal-body label {
    font-weight: 600;
    margin-bottom: 0.15rem;
}

#portalnew-main-center .main-input.hint {
    color: #F3AD2E !important;
    font-size: 0.85rem;
    font-weight: 600;
    margin-left: 1rem;
    margin-bottom: 0.25rem;
}

#startPopupMsg-modal .modal-content {
    color: #FFFFFF !important;
    background-color: #161617 !important;
}

#modal-updateShow .modal-content {
    font-family: 'Stolzl Book', sans-serif;
    background-color: #161617 !important;
    color: #F1F1F1 !important
}

#modal-updateShow .slider .bx-wrapper {
    color: #F1F1F1 !important;
    border: none !important;
    background-color: #161617 !important;
}

#modal-updateShow .modal-content .modal-footer input.form-check-input {
    color: #F1F1F1 !important
}

#modal-updateShow .modal-content .modal-body h1,
#modal-updateShow .modal-content .modal-body h2,
#modal-updateShow .modal-content .modal-body h3,
#modal-updateShow .modal-content .modal-body h4 {
    font-family: 'Oswald', sans-serif;
    color: #FFFFFF !important;
}

#modal-updateShow .modal-content .modal-body h3 {
    font-size: 1.75rem;
    font-weight: 300;
    color: white !important;
}

#modal-updateShow .modal-content .modal-body p.lead {
    color: #F1F1F1 !important;
}

#modal-updateShow .slider-items .item p:not(p.alert, p.lead),
#modal-updateShow .slider-items .item ul li {
    font-family: "Stolzl Book", Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 0.8rem;
    color: #CCCCCC !important;
}

#modal-updateShow .slider-items .item p.alert {
    font-family: "Stolzl Book", Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 0.8rem;
}

#modal-updateShow .slider-items .item p a,
#modal-updateShow .slider-items .item p span>a {
    color: #49AAEE !important;
    text-decoration: underline;
}

#modal-updateShow .slider-items .item p a:hover,
#modal-updateShow .slider-items .item p span>a:hover {
    text-decoration: none;
}

#modal-updateShow .modal-footer label {
    color: #F3AD2E !important;
}

#modal-updateShow .slider-items .item .badge.badge-timing {
    padding: 0.15rem 0.2rem;
    font-size: 85%;
    font-weight: 400;
    color: #000;
    background-color: #fff;
}
</style>
<?php
if (__NY_STYLE) {
    if (checkIsItSuperadmin_defaultDB($_SESSION['id']) == 1) {
        ?>
<style>
#portalnew-navbar {
    border-bottom: none !important;
}

#portalnew-navbar>div {
    background: url(https://free-png.ru/wp-content/uploads/2021/10/free-png.ru-136.png);
    background-size: contain;
}

#portalnew-navbar h3.service-subtitle {
    color: #111111 !important;
    font-weight: 400;
    background-color: #FFFFFF !important;
    padding: 2px 8px;
    opacity: unset;
}

#portalnew-navbar div.icons>i {
    color: #FFFFFF !important;
}

#portalnew-navbar .btn-group>button>span,
#portalnew-navbar .btn-group>button.dropdown-toggle::after {
    color: #FFFFFF !important;
}

#portalnew-navbar img.logo.rounded-circle {
    /* border: white 4px solid !important; */
}
</style>
<?php
} else { ?>
<style>
#portalnew-navbar {
    border-bottom: none !important;
}

#portalnew-navbar>div {
    background: url(https://free-png.ru/wp-content/uploads/2021/10/free-png.ru-136.png);
    background-size: contain;
}

#portalnew-navbar h3.service-subtitle {
    color: #111111 !important;
    font-weight: 400;
    background-color: #FFFFFF !important;
    padding: 2px 8px;
    opacity: unset;
}

#portalnew-navbar div.icons>i {
    color: #FFFFFF !important;
}

#portalnew-navbar .btn-group>button>span,
#portalnew-navbar .btn-group>button.dropdown-toggle::after {
    color: #FFFFFF !important;
}

#portalnew-navbar img.logo.rounded-circle {
    /* border: white 4px solid !important; */
}
</style>
<?php
}
}

?>