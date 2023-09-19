<?php

header("Content-Type:text/html; charset=utf-8");
if (!isset($_SESSION)) {                        
    $expire = 0;
    if ($expire == 0) {
        $expire = ini_get('session.gc_maxlifetime');
    } else {
        ini_set('session.gc_maxlifetime', $expire);
    }

    if (empty($_COOKIE['PHPSESSID'])) {
        session_set_cookie_params($expire);
        session_start();
    } else {
        session_start();
        setcookie('PHPSESSID', session_id(), time() + $expire);
    }
    header("Cache-control: private");
}


if(isset($_GET['action'])){             // 確認有ACTION這個GET參數
    switch ($_GET['action']) {          // 透過ACTION這個GET參數去判斷要做甚麼
        case 'login':                   // 登入

            $acc = $_POST['param1'];    // 透過POST取得param1的值，即帳號
            $pwd = $_POST['param2'];    // 透過POST取得param2的值，即密碼

            $link = mysqli_connect(
                'localhost',
                'bonus_cart_user',
                '820820',
                'db_bonus_cart'
            )or die('無法開啟MySQL資料庫連接!<br>');
            $sql = "SELECT * FROM user WHERE account='" . $acc ."' and password='" . $pwd . "'";
            if($result = mysqli_query($link, $sql)){
                $data_count = mysqli_num_rows($result);
                if($data_count > 0){  
                    unset($_POST['pwd']);        // 清掉POST pwd(密碼)
                    $_SESSION['token'] = time(); // 建立SESSION TOKEN，用以判斷是否為登入狀態
                    $_SESSION['user'] = $acc;    // 建立SESSION USER，用以得知使用者為誰
                    $data['success'] = true;     // 當登入成功，成功狀態為TRUE
                }else{
                    $data['success'] = false;    // 當登入失敗，成功狀態為FALSE
                    $data['msg'] = '帳號或密碼錯誤，請重新再試!';   // 登入失敗要提示的訊息                 
                }
                mysqli_free_result($result);
            }

            echo json_encode($data);         // 顯示JSON編碼後的資料(回傳資料)
            break;
    }
}

?>