$(document).ready(function () {
    initLogin();
});

let loginType = 'account';

function initLogin() {
    if (getLoginStatus()) into("/home");
    else $("#card-body").load("/static/html/login/login-form.html", function () {
        if (decodeURIComponent($.url.param('login_type')) === 'email') $('#email-login').tab('show');
        $("#login-form").submit(function (event) {
                event.preventDefault();
                login();
            }
        );
        $("#footer").load("https://www.antx.cc/static/html/footer/footer.html");
        $("#login-username").keyup(function () {
            checkLoginUsername();
        });
        $("#login-email").keyup(function () {
            checkLoginEmail();
        });
        $("#send-login-code").click(function () {
            sendLoginCode();
        });
        $("#login-code").keyup(function () {
            checkLoginCode();
        });
        $("#login-password").keyup(function () {
            checkLoginPassword();
        });
        $("#login-agree").click(function () {
            checkLoginAgree();
        });
        $("#eye").click(function () {
            togglePassword('#eye', '#login-password');
        });

        $("#account-login").click(function () {
            loginType = 'account';
            $("#login-agree").prop("checked", false);
        });

        $("#email-login").click(function () {
            loginType = 'email';
            $("#login-agree").prop("checked", false);
        });
    });
}

function checkLoginUsername() {
    let username = $("#login-username").val()
    if (username) {
        $('#username-error').html('');
        return true;
    } else {
        $('#username-error').html(error + ' 用户名不可为空');
        return false;
    }
}

function checkLoginPassword() {
    let password = $("#login-password").val()
    if (password) {
        $('#password-error').html('');
        return true;
    } else {
        $('#password-error').html(error + ' 密码不可为空');
        return false;
    }
}

function checkLoginEmail() {
    let res;
    let email = $("#login-email").val();
    if (email) {
        if (!isEmail(email)) {
            $('#email-error').html(error + ' 邮箱格式错误');
            res = false;
        } else {
            $('#email-error').html('');
            res = true;
        }
    } else {
        $('#email-error').html(error + ' 邮箱不可为空');
        res = false;
    }
    return res;
}

function checkLoginCode() {
    let code = $("#login-code").val()
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

function checkLoginAgree() {
    if ($("#login-agree").is(":checked")) {
        $('#agree-error').html('');
        return true;
    } else {
        $('#agree-error').html(error + ' 请先阅读并同意服务条约和隐私政策');
        return false;
    }
}

function login() {
    if (checkLoginAgree()) {
        if (loginType === 'account') {
            if (checkLoginUsername()) {
                if (checkLoginPassword()) {
                    $("#login-button").prop('disabled', true).html("<span class='spinner-border spinner-border-sm'></span> 登录中...");
                    let loginError = function () {
                        $("#login-password").val("");
                        $("#password-error").html(error + ' 账号或密码错误');
                        $.growl.error({title: "错误", message: "登录失败, 账号或密码错误"});
                        $("#login-button").html("立即登录").prop('disabled', false);
                    }
                    if (isUsername($("#login-username").val()) && isPassword($("#login-password").val())) {
                        $.post({
                            url: '/static/php/login.php', data: {
                                action: 'account_login',
                                username: $("#login-username").val(),
                                password: $("#login-password").val()
                            }, dataType: 'json', async: false, type: "POST",
                            success: function (data) {
                                setTimeout(function () {
                                    if (data["success"]) {
                                        $("#password-error").html('');
                                        $prompt.success("登录成功");
                                        $("#login-button").html("正在跳转中...");
                                        setTimeout(function () {
                                            let target = $.url.param("target");
                                            if (target)
                                                into(decodeURIComponent(target));
                                            else
                                                into("/home");
                                        }, 500)
                                    } else {
                                        $prompt.error("登录失败, 账号或密码错误");
                                        $("#login-password").val("");
                                        $("#password-error").html(error + ' 账号或密码错误');
                                        $("#login-button").html("立即登录").prop('disabled', false);
                                    }
                                }, 1000);
                            }
                        });
                    } else {
                        setTimeout(function () {
                            loginError();
                        }, 1000);
                    }

                    return false;
                }
            }
        } else if (loginType === 'email') {
            if (checkLoginEmail()) {
                if (checkLoginCode()) {
                    $("#login-button").prop('disabled', true).html("<span class='spinner-border spinner-border-sm'></span> 登录中...");
                    setTimeout(function () {
                        if (verifyLoginCode()) {
                            $("#code-error").html('');
                            $("#code-success").html('');
                            $("#login-button").html("正在跳转中...");
                            $prompt.success("登录成功");
                            setTimeout(function () {
                                let target = $.url.param("target");
                                if (target)
                                    into(decodeURIComponent(target));
                                else
                                    into("/home");
                            }, 500)
                        } else {
                            $prompt.error("登录失败, 验证码错误或已失效")
                            $("#login-code").val('');
                            $("#code-error").html(error + ' 验证码错误或已失效');
                            $("#code-success").html('');
                            $("#login-button").prop('disabled', false).html("立即登录");
                        }
                    }, 1000);
                }
            }
        } else {
            reflush();
        }
    }
    return false;
}

function sendLoginCode() {
    if (checkLoginEmail()) {
        let sent_code = function () {
            $prompt.success("验证码发送成功");
            $("#code-error").html('');
            $("#code-success").html(success + ' 验证码已发送, 可能会有延迟, 请耐心等待');
            $("#send-login-code").html("获取验证码").prop('disabled', true);
            let intervalId = null;
            let countdown = 60;
            intervalId = setInterval(function () {
                countdown--;
                $("#send-login-code").html(countdown + '秒后重试');
                if (countdown <= 0) {
                    clearInterval(intervalId);
                    $("#sent-code").html('');
                    $("#send-login-code").html("获取验证码").prop('disabled', false);
                }
            }, 1000);
            $("#send-login-code").html('获取验证码');
        }
        let email = $("#login-email").val();
        if (checkEmailExist(email)) {
            $("#send-login-code").prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> 获取中');
            if (email) {
                if (!isEmail(email)) {
                    $('#email-error').html(error + ' 邮箱格式错误');
                } else {
                    $.post('/static/php/login.php', {
                        'action': 'send_login_code', 'email': email
                    }, function (data) {
                        console.log(data);
                        if (data['success']) {
                            sent_code();
                        } else {
                            $prompt.error("验证码发送失败\n" + data['message']);
                            $("#sent-code").html('');
                            $("#code-error").html(error + ' 发送失败');
                            $("#send-login-code").html('获取验证码');
                        }
                    }, 'json');
                }
            } else {
                $('#email-error').html(error + ' 邮箱不可为空');
            }
        } else {
            sent_code();
        }
    }
}

let verifyCodeResult;

function verifyLoginCode() {
    if (checkLoginCode()) {
        let code = $("#login-code").val();
        let email = $("#login-email").val();
        $.post({
            url: '/static/php/login.php', data: {
                'action': 'verify_login_code', 'code': code, 'email': email
            }, dataType: 'json', async: false, type: "POST", success: function (data) {
                console.log(data);
                verifyCodeResult = data["success"];
            }
        });
    }
    return verifyCodeResult;
}