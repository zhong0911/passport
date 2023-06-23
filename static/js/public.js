let error = '<i class="fa fa-exclamation-circle"></i>';
let success = '<i class="fa fa-check-circle"></i>';

let usernameExists = false;

let host = $.url.attr("host");
if (!(host === "passport.antx.cc" || host === "localhost"))
    into("https://passport.antx.cc" + $.url.attr("path"));

function checkUsernameExist(username) {
    $.post({
        url: '/static/php/public.php', data: {
            'action': 'check_username_exist', 'username': username
        }, dataType: 'json', async: false, type: "POST", success: function (data) {
            usernameExists = data["exist"];
        }
    });
    return usernameExists;
}

let emailExists = false;

function checkEmailExist(email) {
    $.post({
        url: '/static/php/public.php', data: {
            'action': 'check_email_exist', 'email': email
        }, dataType: 'json', async: false, type: "POST", success: function (data) {
            emailExists = data["exist"];
        }
    });
    return emailExists;
}


let loginStatus = false;

function getLoginStatus() {
    $.post({
        url: '/static/php/public.php',
        data: {
            action: 'get_login_status'
        }, dataType: 'json',
        async: false,
        type: "POST",
        success: function (data) {
            loginStatus = data['success'];
        }
    });
    return loginStatus;
}

let userInfo;

function getUserInfo() {
    $.post({
        url: '/static/php/public.php',
        data: {
            action: 'get_user_info'
        }, dataType: 'json',
        async: false,
        type: "POST",
        success: function (data) {
            userInfo = data;
        }
    });
    return userInfo;
}


function listenLoggedIn() {
    let listening = setInterval(function () {
        if (!getLoginStatus()) {
            clearInterval(listening);
            notLoggedIn()
        }
    }, 5000);
}

function notLoggedIn() {
    $("#main").hide();
    setTimeout(function () {
        alertAndIntoLogin('身份验证失败, 请重新登录');
    })
}

function alertAndIntoLogin(txt) {
    $popup.alert(txt, () => {
        into("https://passport.antx.cc/login");
    })
}