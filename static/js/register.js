$(document).ready(function () {
    initRegister();
});

function initRegister() {
    $("#card-body").load("/static/html/register/register-form.html", function () {
        $("#password-confirm").hide();
        $("#code-send").hide();
        $("#register-form").submit(function (event) {
                event.preventDefault();
                register();
            });
        $("#footer").load("https://www.antx.cc/static/html/footer/footer.html");
        $("#send-register-code").click(function () {
            sendRegisterCode();
        });
        $("#register-username").attr({
            "data-bs-toggle": "tooltip", "data-bs-placement": "right", "title": "不能包括空格\n开头必须为字母\n长度为5~12个字符\n必须包含字母和数字"
        }).keyup(function () {
            checkRegisterUsername();
        });
        $("#register-password").attr({
            "data-bs-toggle": "tooltip", "data-bs-placement": "right", "title": "不能包括空格\n长度为8~26个字符\n必须包含字母和数字"
        }).keyup(function () {
            checkRegisterPassword();
            $("#password-confirm").show(500);
        }).click(function () {
            $("#password-confirm").show(500);
        });
        $("#register-confirm-password").attr({
            "data-bs-toggle": "tooltip", "data-bs-placement": "right", "title": "不能包括空格\n长度为8~26个字符\n必须包含字母和数字"
        }).keyup(function () {
            checkRegisterConfirmPassword();
        });
        $("#register-code").attr({
            "data-bs-toggle": "tooltip", "data-bs-placement": "right", "title": "错误次数超过5次将失效"
        }).keyup(function () {
            checkRegisterCode();
        });
        $("#register-email").click(function () {
            $("#code-send").show(500)
        }).keyup(function () {
            checkRegisterEmail();
            $("#code-send").show(500)
        });
        $("#register-agree").click(function () {
            checkRegisterAgree();
        });
    });
}

function sendRegisterCode() {
    if (checkRegisterEmail()) {
        let email = $("#register-email").val();
        if (!checkEmailExist(email)) {
            $("#send-register-code").prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> 获取中');
            if (email) {
                if (!isEmail(email)) {
                    $('#email-error').html(error + ' 邮箱格式错误');
                } else {
                    let intervalId = null;
                    let countdown = 60;
                    $.post('/static/php/register.php', {
                        'action': 'send_register_code', 'email': email
                    }, function (data) {
                        if (data['success']) {
                            $prompt.success("验证码发送成功");
                            $("#code-error").html('');
                            $("#code-success").html(success + ' 验证码已发送, 可能会有延迟, 请耐心等待');
                            intervalId = setInterval(function () {
                                countdown--;
                                $("#send-register-code").html(countdown + '秒后重试');
                                if (countdown <= 0) {
                                    countdown = 60;
                                    clearInterval(intervalId);
                                    $("#sent-code").html('');
                                    $("#send-register-code").html("获取验证码").prop('disabled', false);
                                }
                            }, 1000);
                            $("#send-register-code").html('获取验证码');
                        } else {
                            $prompt.error("验证码发送失败\n" + data['message']);
                            $("#sent-code").html('');
                            $("#code-error").html(error + ' 发送失败');
                            $("#send-register-code").html('获取验证码');
                        }
                    }, 'json');
                }
            } else {
                $('#email-error').html(error + ' 邮箱不可为空');
            }
        }
    }
}

function checkRegisterUsername() {
    let username = $("#register-username").val()
    if (username) {
        if (!isUsername(username)) {
            $('#username-error').html(error + ' 用户名格式错误');
            return false;
        } else {
            if (checkUsernameExist(username)) {
                $('#username-error').html(error + ' 用户名已存在');
                return false;
            } else {
                $('#username-error').html('');
                return true;
            }
        }
    } else {
        $('#username-error').html(error + ' 用户名不可为空');
        return false;
    }
}

function checkRegisterPassword() {
    let password = $("#register-password").val()
    if (password) {
        if (!isPassword(password)) {
            $('#password-error').html(error + ' 密码格式错误');
            return false;
        } else {
            $('#password-error').html('');
            return true;
        }
    } else {
        $('#password-error').html(error + ' 密码不可为空');
        return false;
    }
}

function checkRegisterConfirmPassword() {
    let password = $("#register-password").val()
    let confirmPassword = $("#register-confirm-password").val()
    if (confirmPassword) {
        if (password === confirmPassword) {
            $('#confirm-password-error').html('');
            return true;
        } else {
            $('#confirm-password-error').html(error + ' 两次密码不一致');
            return false;
        }
    } else {
        $('#confirm-password-error').html(error + ' 密码不可为空');
        return false;
    }
}

function checkRegisterEmail() {
    let res;
    let email = $("#register-email").val();
    if (email) {
        if (!isEmail(email)) {
            $('#email-error').html(error + ' 邮箱格式错误');
            res = false;
        } else {
            if (checkEmailExist(email)) {
                $('#email-error').html(error + ' 邮箱已被绑定');
                res = false;
            } else {
                $('#email-error').html('');
                res = true;
            }
        }
    } else {
        $('#email-error').html(error + ' 邮箱不可为空');
        res = false;
    }
    return res;
}

function checkRegisterCode() {
    let code = $("#register-code").val()
    if (code) {
        $("#code-error").html('');
        $("#code-success").html('');
        return true;
    } else {
        $("#code-success").html('');
        $("#code-error").html(error + ' 验证码不可为空');
        return false;
    }
}

function checkRegisterAgree() {
    if ($("#register-agree").is(":checked")) {
        $('#agree-error').html('');
        return true;
    } else {
        $('#agree-error').html(error + ' 请先阅读并同意服务条约和隐私政策');
        return false;
    }
}

let verifyCodeResult;

function verifyRegisterCode() {
    if (checkRegisterCode()) {
        $("#register-button").prop('disabled', true).html("<span class='spinner-border spinner-border-sm'></span> 注册中...");
        let code = $("#register-code").val();
        let email = $("#register-email").val();
        $.post({
            url: '/static/php/register.php', data: {
                'action': 'verify_register_code', 'code': code, 'email': email
            }, dataType: 'json', async: false, type: "POST", success: function (data) {
                if (data["success"]) {
                    $("#code-error").html('');
                    $("#code-success").html('');
                    verifyCodeResult = true;
                } else {
                    setTimeout(function () {
                        $prompt.error("注册失败, 验证码错误或已失效")
                        $("#register-code").val('');
                        $("#code-error").html(error + ' 验证码错误或已失效');
                        $("#code-success").html('');
                        $("#register-button").prop('disabled', false).html("立即注册");
                    }, 1000);
                    verifyCodeResult = false;
                }
            }
        });
    }
    return verifyCodeResult;
}

function register() {
    let agree = checkRegisterAgree();
    if (agree) {
        let username = checkRegisterUsername();
        if (username) {
            let password = checkRegisterPassword();
            if (password) {
                let confirmPassword = checkRegisterConfirmPassword();
                if (confirmPassword) {
                    let email = checkRegisterEmail();
                    if (email) {
                        let code = verifyRegisterCode();
                        if (code) {
                            $.post({
                                url: '/static/php/register.php', data: {
                                    action: 'register',
                                    username: $("#register-username").val(),
                                    password: $("#register-password").val(),
                                    email: $("#register-email").val()
                                },
                                dataType: 'json',
                                async: false,
                                // type: "POST",
                                success: function (data) {
                                    setTimeout(function () {
                                        if (data["success"]) {
                                            $("#register-button").html("注册成功");
                                            registered(data);
                                        } else {
                                            $("#register-button").html("立即注册").prop('disabled', false);
                                            $prompt.error("注册失败, 原因:<br/>" + data['message']);
                                        }
                                    }, 1000);
                                }
                            });
                            return false;
                        } else return false;
                    } else return false;
                } else return false;
            } else return false;
        } else return false;
    } else return false;
}

function registered(data) {
    let uid = data['uid'];
    let username = data['username'];
    $("#modal").load("/static/html/register/registered-modal.html",
        function () {
            $("#registered-uid").text(uid);
            $("#registered-username").text(username);
            $("#registered-modal").modal("show");
        }
    );
}

