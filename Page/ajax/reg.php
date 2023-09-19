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

if(isset($_GET['action'])){                         // 確認有ACTION這個GET參數
    switch ($_GET['action']) {                      // 透過ACTION這個GET參數去判斷要做甚麼
        case 'regist':                              // 註冊
            $acc = $_POST['param1'];                // 透過POST取得param1的值，即帳號
            $pwd = $_POST['param2'];                // 透過POST取得param2的值，即密碼
            $mail = $_POST['param3'];               // 透過POST取得param3的值，即信箱
            
            // 確認帳號不存在
            $link = mysqli_connect(
                'localhost',
                'bonus_cart_user',
                '820820',
                'db_bonus_cart'
            )or die('無法開啟MySQL資料庫連接!<br>');
            $sql = "SELECT * FROM user WHERE account='" . $acc ."'";
            if($result = mysqli_query($link, $sql)){
                $data_count = mysqli_num_rows($result);
                if($data_count > 0){                    
                    $data['success'] = false;           
                    $data['msg'] = 'Account exists, please try another one';  
                }else{
                    $sql = "INSERT INTO user (account, password, email) VALUES ('" . $acc . "', '" . $pwd . "', '" . $mail . "')"; 
                    if($result2 = mysqli_query($link, $sql)){
                        $data['success'] = true;
                        mysqli_free_result($result2);
                    }                    
                }
                mysqli_free_result($result);
            }
            mysqli_close($link);

            echo json_encode($data);                // 顯示JSON編碼後的資料(回傳資料)
            break;
    }
}

?>