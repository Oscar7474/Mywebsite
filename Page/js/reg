$('#inp_acc').val('');                          // 載入頁面時，清空數值
$('#inp_pwd').val('');                          // 載入頁面時，清空數值
$('#inp_pwd_check').val('');                    // 載入頁面時，清空數值
$('#inp_mail').val('');                         // 載入頁面時，清空數值
$('#inp_acc').focus();                          // 載入頁面時，FOCUS這個INPUT

$('#btn_send').click(() => {                    // 按下送出按鈕
    var account = $('#inp_acc').val();          // 取得帳號輸入框的值
    var pwd = $('#inp_pwd').val();              // 取得密碼輸入框的值
    var pwd_check = $('#inp_pwd_check').val();  // 取得密碼確認輸入框的值
    var mail = $('#inp_mail').val();            // 取得信箱輸入框的值

    if (pwd == pwd_check) {                     // 確認密碼&密碼確認相符
        get_data('../Pages/ajax/reg.php', 'regist', account, pwd, mail);    // 透過AJAX存入註冊資訊
    } else {
        alert('Password doesn\'t match, please try again.') // 密碼不相符時，提示訊息
    }

});

$('#btn_cancel').click(() => {                  // 按下取消按鈕
    location.href = './addtocart.php';          // 導至購物車頁面
})

$('#btn_login').click(() => {                   // 按下登入按鈕
    location.href = './login.php';              // 導至登入頁面
})

const get_data = (url, action, param1 = '', param2 = '', param3 = '') => {
    $.ajax({
        type: "POST",                           // 方法為POST
        url: `${url}?action=${action}`,         // 位址(指定PHP路徑)，對應PHP的$_GET['action']
        data: {
            param1: param1,
            param2: param2,
            param3: param3
        },
        dataType: "JSON",                       // 資料類型為JSON
        success: function (data) {              // 當AJAX成功時
            if (data.success) {                 // 透過回傳參數再次確認執行成功
                location.href = '../../My website/Pages/addtocart.php'  // 導至購物車頁面
            } else {                            // 透過回傳參數得知操作失敗
                alert(data.msg);                // 提示錯誤訊息
            }
        }
    });
}
