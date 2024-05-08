// ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
// ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
// ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
// 
//
// getUserRestrictions
// 
// 
const ajaxRequest_getUserRestrictions = (fieldname) => {
    var request_getUserRestrictions = '';
    return request_getUserRestrictions = $.ajax({
        type: 'post',
        url: 'http://192.168.1.89/portalnew/php/examples/simple/main/main/process/ajaxrequests/ajaxReq-getUserRestrictions.php',
        cache: false,
        data: {
            fieldname: fieldname,
        },
    });
    // Callback handler that will be called on success
    request_getUserRestrictions.done(function(response, textStatus, jqXHR) {
        res = response.replace(new RegExp("\\r?\\n", "g"), "");
        console.log('getUserRestrictions!', res);
    });
    request_getUserRestrictions.fail(function(jqXHR, textStatus, errorThrown) {
        console.error(
            "The following error occurred: " +
            textStatus, errorThrown
        );
    });
    // Callback handler that will be called regardless
    // if the request_addItem failed or succeeded
    request_getUserRestrictions.always(function() {});
};
// 
//
// getUserRestrictions
// 
// 
const getUserRestrictions = async (fieldname) => {
    const a = await ajaxRequest_getUserRestrictions(fieldname);
    console.log('ajaxRequest_getUserRestrictions await >>>', fieldname, a);
    if (a < 2 && fieldname === 'mail') {
        $('#mail_userRestr').html(
            '<span class="text-warning p-2" data-toggle="popover" data-content="Эта настройка не будет работать, потому что у вас не достаточно прав доступа к сервису Почта"><i class="fa-solid fa-circle-exclamation"></i></span>'
        );
        $('#mail_userRestr span[data-toggle="popover"]').popover({
            html: true,
            trigger: 'hover',
            placement: 'top',
        })
    }
    if (a < 3 && fieldname === 'dognet') {
        $('#dog_userRestr').html(
            '<span class="text-warning p-2" data-toggle="popover" data-content="Эта настройка не будет работать, потому что у вас не достаточно прав доступа к сервису Договор"><i class="fa-solid fa-circle-exclamation"></i></span>'
        );
        $('#dog_userRestr span[data-toggle="popover"]').popover({
            html: true,
            trigger: 'hover',
            placement: 'top',
        })
    }
    $('#searchingSettings-modal').modal('show');
};
// ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
// ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
// ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
// 
//
// getUserSettings
// 
// 
const ajaxRequest_getUserSettings = async () => {
    var request_getUserSettings = '';
    return request_getUserSettings = $.ajax({
        type: 'post',
        url: 'http://192.168.1.89/portalnew/php/examples/simple/main/main/process/ajaxrequests/ajaxReq-getUserSettings.php',
        cache: false,
        data: {},
    });
    // Callback handler that will be called on success
    request_getUserSettings.done(function(response, textStatus, jqXHR) {
        res = response.replace(new RegExp("\\r?\\n", "g"), "");
        console.log('getUserSettings!', res);
    });
    request_getUserSettings.fail(function(jqXHR, textStatus, errorThrown) {
        console.error(
            "The following error occurred: " +
            textStatus, errorThrown
        );
    });
    // Callback handler that will be called regardless
    // if the request_addItem failed or succeeded
    request_getUserSettings.always(function() {});
}
const getUserSettings = async () => {
    const response = await ajaxRequest_getUserSettings();
    console.log('ajaxRequest_getUserSettings await >>>', response);
    if (checkVal(response)) {
        res = response.replace(new RegExp("\\r?\\n", "g"), "");
        x = res.split("///");
        searchMail = x[0];
        searchDog = x[1];
        searchSP = x[2];
        searchISM = x[3];
        searchHR = x[4];
        showTopblock = x[5];
        showLeftBblock = x[6];
        showRightBblock = x[7];
        showCloudblock = x[8];
        usePush = x[9];
        useLightTheme = x[10];
        //
        if (searchMail === "1") {
            $('#searchingSettings-block input[id="searchSwitch-mail"]').prop('checked',
                'checked').val(1);
        } else {
            $('#searchingSettings-block input[id="searchSwitch-mail"]').prop('checked',
                false).val(0);
        }
        //
        if (searchDog === "1") {
            $('#searchingSettings-block input[id="searchSwitch-dog"]').prop('checked',
                'checked').val(1);
        } else {
            $('#searchingSettings-block input[id="searchSwitch-dog"]').prop('checked',
                false).val(0);
        }
        //
        if (searchSP === "1") {
            $('#searchingSettings-block input[id="searchSwitch-sp"]').prop('checked', true)
                .val(1);
        } else {
            $('#searchingSettings-block input[id="searchSwitch-sp"]').prop('checked', false)
                .val(0);
        }
        //
        if (searchISM === "1") {
            $('#searchingSettings-block input[id="searchSwitch-ism"]').prop('checked', true)
                .val(1);
        } else {
            $('#searchingSettings-block input[id="searchSwitch-ism"]').prop('checked',
                false).val(0);
        }
        //
        if (searchHR === "1") {
            $('#searchingSettings-block input[id="searchSwitch-hr"]').prop('checked', true)
                .val(1);
        } else {
            $('#searchingSettings-block input[id="searchSwitch-hr"]').prop('checked', false)
                .val(0);
        }
        //
        if (usePush === "1") {
            $('#userSettings-block input[id="userSettingsSwitch-usePush"]').prop('checked', true)
                .val(1);
        } else {
            $('#userSettings-block input[id="userSettingsSwitch-usePush"]').prop('checked', false)
                .val(0);
        }
        //
        if (showTopblock === "1") {
            $('#userSettings-block input[id="userSettingsSwitch-showTopblock"]').prop('checked', true)
                .val(1);
        } else {
            $('#userSettings-block input[id="userSettingsSwitch-showTopblock"]').prop('checked', false)
                .val(0);
        }
        //
        if (showLeftBblock === "1") {
            $('#userSettings-block input[id="userSettingsSwitch-showLeftBottomblock"]').prop('checked', true)
                .val(1);
        } else {
            $('#userSettings-block input[id="userSettingsSwitch-showLeftBottomblock"]').prop('checked', false)
                .val(0);
        }
        //
        if (showRightBblock === "1") {
            $('#userSettings-block input[id="userSettingsSwitch-showRightBottomblock"]').prop('checked', true)
                .val(1);
        } else {
            $('#userSettings-block input[id="userSettingsSwitch-showRightBottomblock"]').prop('checked', false)
                .val(0);
        }
        //
        if (showCloudblock === "1") {
            $('#userSettings-block input[id="userSettingsSwitch-showCloudblock"]').prop('checked', true)
                .val(1);
        } else {
            $('#userSettings-block input[id="userSettingsSwitch-showCloudblock"]').prop('checked', false)
                .val(0);
        }
        //
        if (useLightTheme === "1") {
            $('#userSettings-block input[id="userSettingsSwitch-useLightTheme"]').prop('checked', true)
                .val(1);
        } else {
            $('#userSettings-block input[id="userSettingsSwitch-useLightTheme"]').prop('checked', false)
                .val(0);
        }
        //
    }
// $('#userSettings-modal').modal('show');
};
// ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
// ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
// ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
// 
//
// saveUserSettings
// 
// 
var reqField_saveUserSettings = {
    saveUserSettings: function(response) {
        console.log('saveUserSettings!', response);
        if (checkVal(response)) {
            res = response.replace(new RegExp("\\r?\\n", "g"), "");
        }
    }
};
const ajaxRequest_saveUserSettings = (setfield, setval, responseHandler) => {
    request_saveUserSettings = $.ajax({
        type: 'post',
        url: 'http://192.168.1.89/portalnew/php/examples/simple/main/main/process/ajaxrequests/ajaxReq-saveUserSettings.php',
        cache: false,
        data: {
            setfield: setfield,
            setval: setval,
        },
        success: reqField_saveUserSettings[responseHandler]
    });
    // Callback handler that will be called on success
    request_saveUserSettings.done(function(response, textStatus, jqXHR) {
        if (setfield === "use_lightTheme") {
            // Обновление страницы
            location.reload();
        }
    });
    request_saveUserSettings.fail(function(jqXHR, textStatus, errorThrown) {
        console.error(
            "The following error occurred: " +
            textStatus, errorThrown
        );
    });
    // Callback handler that will be called regardless
    // if the request_addItem failed or succeeded
    request_saveUserSettings.always(function() {});
}
