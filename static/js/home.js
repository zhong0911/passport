$(document).ready(function () {
    initUserInfo();
});

function initUserInfo() {
    $("#footer").load("https://www.antx.cc/static/html/footer/footer.html");
    if (getLoginStatus()) {
        listenLoggedIn();
        let info = getUserInfo();
        if (info['success']) {
            $("#body").load("/static/html/home/user-info-form.html",
                function () {
                    let avatar = info['avatar'];
                    let username = info['username'];
                    let nickname = info['nickname'];
                    let email = info['email'];
                    let uid = info['uid'];
                    let age = info['age'];
                    let gender = info['gender'];
                    let birthday = info['birthday'];
                    if (!gender) gender = "未知";
                    else {
                        if (gender === "M") gender = "男";
                        else gender = "女";
                    }
                    $("#avatar").prop('src', avatar);
                    $("#username").val(username);
                    $("#nickname").val(nickname);
                    $("#email").val(email);
                    $("#uid").val(uid);
                    $("#age").val(age);
                    $("#gender").val(gender);
                    $("#birthday").val(birthday);

                    $("#logout").click(function () {
                        logout();
                    });
                });
        } else {
            notLoggedIn();
        }
    } else {
        notLoggedIn();
    }
}


function logout() {
    $.removeCookie('PHPSESSID', {path: '/', domain: '.antx.cc'});
    into('https://www.antx.cc/');
}