login_check = get_data('../Pages/ajax/addtocart.php', 'login_check');   // 透過AJAX執行確認登入狀態
if (login_check.login_check) {                  // 登入狀態
    $('#btn_login').css('display', 'none');     // 變更該按鈕CSS，使其隱藏
    $('#btn_reg').css('display', 'none');       // 變更該按鈕CSS，使其隱藏
} else {                                        // 登出狀態
    $('#btn_logout').css('display', 'none');    // 變更該按鈕CSS，使其隱藏
}

$('#btn_login').click(() => {                   // 按下LOGIN按鈕
    location.href = './login.php';              // 導至LOGIN頁面
});

$('#btn_logout').click(() => {                  // 按下LOGIN按鈕
    var logout = get_data('../Pages/ajax/addtocart.php', 'logout'); // 透過AJAX執行LOGOUT動作
    if (logout.logout) history.go(0);                  // 重整頁面
});

$('#btn_reg').click(() => {                     // 按下REG按鈕
    location.href = './reg.php';                // 導至REG頁面
});

product_list = get_data('../Pages/ajax/addtocart.php', 'product_list'); // 透過AJAX載入商品清單
product_data = product_list.product;            // 定義變數存取清單ARRAY

const product = [];                             // 定義空的ARRAY
for (var j = 0; j < product_data.length; j++) { // 透過FOR迴圈存入商品資訊
    product.push({
        id: product_data[j]['id'],
        image: './../' + product_data[j]['url'],
        title: product_data[j]['name'],
        price: product_data[j]['price']
    })
}

const categories = [...new Set(product.map((item) => { return item }))]
let i = 0;
// document.getElementById('root').innerHTML = categories.map((item) => {
$('#root').html(categories.map((item) => {
    var { image, title, price } = item;
    return (
        `<div class='box'>
            <div class='img-box'>
                <img class='images' src=${image}></img>
            </div>
        <div class='bottom'>
        <p>${title}</p>
        <h2>$ ${price}</h2>` +
        "<button onclick='addtocart(" + (i++) + ")'>Add to cart</button>" +
        `</div>
        </div>`
    )
}).join(''))

var cart = [];

function addtocart(a) {
    login_check = get_data('../Pages/ajax/addtocart.php', 'login_check');   // 透過AJAX確認登入狀態
    if (login_check.login_check) {          // 登入
        cart.push({ ...categories[a] });
        displaycart();
        console.log(cart);
    } else {                                // 非登入
        location.href = './login.php';
    }
}
function delElement(a) {
    cart.splice(a, 1);
    displaycart();
    console.log(cart);
}

function displaycart() {
    let j = 0, total = 0;
    // document.getElementById("count").innerHTML = cart.length;
    $('#count').html(cart.length);
    if (cart.length == 0) {
        // document.getElementById('cartItem').innerHTML = "Your cart is empty";
        // document.getElementById("total").innerHTML = "$ " + 0;
        $('#cartItem').html("Your cart is empty");
        $('#total').html("$ " + 0);
    }
    else {
        // document.getElementById("cartItem").innerHTML = cart.map((items) => {
        $('#cartItem').html(cart.map((items) => {
            var { image, title, price } = items;
            total = total + parseInt(price);
            // document.getElementById("total").innerHTML = "$ " + total;
            $('#total').html("$ " + total);
            return (
                `<div class='cart-item'>
                <div class='row-img'>
                    <img class='rowimg' src='${image}'>
                </div>
                <p style='font-size:12px;'>${title}</p>
                <h2 style='font-size: 15px;'>$ ${price}</h2>` +
                "<i class='fa-solid fa-trash' onclick='delElement(" + (j++) + ")'></i></div>"
            );
        }).join(''));
    }
}

$('#btn_checkout').click(() => {        // 按下結帳按鈕
    if (cart.length > 0) {              // 確認購物車存在商品
        cart_submit(cart);              // 送出購物資訊
    } else {
        alert('Your cart is empty!');   // 提示購物車為空
    }
})

function get_data(url, action, param1 = '', param2 = '') {
    var return_data;                    // 定義回傳變數
    $.ajax({
        type: "POST",                   // 方法為POST
        url: `${url}?action=${action}`, // 位址(指定PHP路徑)，後面的ACTION為執行動作，對應PHP的$_GET['action']
        data: {                         // 資料以KEY VALUE方式存進去
            param1: param1,
            param2: param2
        },
        dataType: "JSON",               // 資料類型為JSON
        async: false,                   // 如果需要回傳資料，必須設定這行
        success: function (data) {      // 當AJAX成功時
            if (data.success) {         // 透過回傳參數再次確認執行成功
                return_data = data;     // 把要回傳的資料傳進變數
            } else {
                alert(data.msg);        // 當執行失敗，提示錯誤訊息
            }
        }
    });
    return return_data;                 // 回傳回傳變數
}

function cart_submit(cart) {
    $.ajax({
        type: "POST",                   // 方法為POST
        url: '../Pages/ajax/addtocart.php?action=submit',   // 位址(指定PHP路徑)，對應PHP的$_GET['action']
        data: {
            data: JSON.stringify(cart), // 轉換成JSON字串符
        },
        dataType: 'JSON',               // 資料類型為JSON
        success: function (data) {      // 當AJAX成功時
            if (data.success) {         // 透過回傳參數再次確認執行成功
                alert('Chekout finished!'); // 提示結帳成功
                history.go(0);          // 重整頁面
            } else {
                alert(data.msg);        // 錯誤提示錯誤訊息
            }
        }, error: function () {         // 當AJAX失敗時
            alert('Chekout failed!');   // 提示結帳錯誤
        }
    });
}

