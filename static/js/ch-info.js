$(document).ready(function () {
    initChangeInfo();
});

function initChangeInfo() {
    if (getLoginStatus()) {
        listenLoggedIn();
        let info = getUserInfo();
        if (info['success']) {
            $("#body").load("/static/html/home/change-info-form.html",
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

                    $("#cancel").click(function () {
                        refresh();
                    });
                });
        } else {
            notLoggedIn();
        }
    } else {
        notLoggedIn();
    }
}

function checkInfoSubmit() {
    return false;
}
