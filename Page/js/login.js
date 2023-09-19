$('#inp_acc').val('');                  // 載入頁面時，清空數值
$('#inp_pwd').val('');                  // 載入頁面時，清空數值
$('#inp_acc').focus();                  // 載入頁面時，FOCUS這個INPUT

$('#btn_confirm').click(() => {         // 按下確認按鈕
    var account = $('#inp_acc').val()   // 取得帳號INPUT的值
    var pwd = $('#inp_pwd').val()       // 取得密碼INPUT的值
    get_data('../Pages/ajax/login.php', 'login', account, pwd); // 透過AJAX登入
})

$('#btn_cancel').click(() => {          // 按下取消按鈕
    location.href = './addtocart.php';  // 導至購物車頁面
})

$('#btn_reg').click(() => {             // 按下註冊按鈕
    location.href = './reg.php';        // 導至註冊頁面
})

const get_data = (url, action, param1 = '', param2 = '') => {
    var return_data;                    // 回傳回傳變數
    $.ajax({
        type: "POST",                   // 方法為POST
        url: `${url}?action=${action}`, // 位址(指定PHP路徑)，對應PHP的$_GET['action']
        data: {
            param1: param1,
            param2: param2
        },
        dataType: "JSON",               // 資料類型為JSON
        async: false,                   // 如果需要回傳資料，必須設定這行
        success: function (data) {      // 當AJAX成功時
            if (data.success) {         // 透過回傳參數再次確認執行成功
                location.href = '../../My_website/Pages/addtocart.php'  // 導至購物車頁面
            } else {
                alert(data.msg);        // 提示錯誤訊息
            }
        }
    });
    return return_data;                 // 回傳回傳變數
}

