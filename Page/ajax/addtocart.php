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

if(isset($_GET['action'])){                     // 確認有ACTION這個GET參數
    switch ($_GET['action']) {                  // 透過ACTION這個GET參數去判斷要做甚麼
        case 'login_check':                     // 登入確認
            if(isset($_SESSION['token'])){      // 確認SESSION TOKEN是否存在
                $data['success'] = true;        // 表示查詢成功
                $data['login_check'] = true;    // 登入確認為成功          
            }else{                              // 如果SESSION TOKEN不存在
                $data['success'] = true;        // 表示查詢成功
                $data['login_check'] = false;   // 登入確認為失敗，及狀態為非登入中
            }

            echo json_encode($data);            // 顯示JSON編碼後的資料(回傳資料)
            break;                              // 結束這個CASE

        case 'product_list':                    // 商品清單

            $link = mysqli_connect(
                'localhost',
                'bonus_cart_user',
                '820820',
                'db_bonus_cart'
            )or die('無法開啟MySQL資料庫連接!<br>');
            $sql = "SELECT * FROM product";
            if($result = mysqli_query($link, $sql)){
                $data_count = mysqli_num_rows($result);
                if($data_count > 0){   
                    $results = [];
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $results[$i]['id'] = $row['id'];
                        $results[$i]['name'] = $row['name'];
                        $results[$i]['price'] = $row['price'];
                        $results[$i]['url'] = $row['url'];
                        $i++;
                    }                 
                    $data['success'] = true;        // 查詢成功
                    $data['product'] = $results;    // 把資料存入PRODUCT中
                }else{
                    $data['success'] = false;       // 查詢失敗
                    $data['msg'] = '獲取資料失敗';   // 失敗提示訊息             
                }
                mysqli_free_result($result);
            }

            echo json_encode($data);            // 顯示JSON編碼後的資料(回傳資料)
            break;                              // 結束這個CASE
        case 'submit':                          // 結帳        
            
            if(isset($_SESSION['user'])){       // 確認登入狀態(SESSION存在即為登入中)
                $account = $_SESSION['user'];   // 帳號為SESSION中的資料
            }else{
                $data['success'] = false;       // 非登入中表示結帳失敗
                $data['msg'] = '請登入後再操作'; // 提示訊息
                echo json_encode($data);        // 顯示JSON編碼後的資料(回傳資料)
                return;                         // 結束這個CASE
            }

            $link = mysqli_connect(
                'localhost',
                'bonus_cart_user',
                '820820',
                'db_bonus_cart'
            )or die('無法開啟MySQL資料庫連接!<br>');
            $sql = "SELECT * FROM user WHERE account='" . $account ."'";
            if($result = mysqli_query($link, $sql)){
                $data_count = mysqli_num_rows($result);
                if($data_count > 0){   
                    while ($row = mysqli_fetch_assoc($result)) {
                        $user_id = $row['id'];
                    }                          
                }
                mysqli_free_result($result);
            }

            $data_arr = json_decode($_POST['data'], true); // JSON STRINg 轉成 ARRAY
            $amount = 0;                                // 定義金額初始值為零
            for($i = 0; $i < count($data_arr); $i++){   // FOR跑一遍所有資料
                $amount += $data_arr[$i]['price'];      // 加總所有資料的價格項目
            }

            $sql = "INSERT INTO checkout (user_id, amount) VALUES ('" . $user_id . "', '" . $amount . "')"; 
            if($result2 = mysqli_query($link, $sql)){
                mysqli_free_result($result2);
            }    
            
            $sql = "SELECT * FROM checkout WHERE user_id='" . $user_id ."' and amount='" . $amount . "'";
            if($result = mysqli_query($link, $sql)){
                $data_count = mysqli_num_rows($result);
                if($data_count > 0){   
                    while ($row = mysqli_fetch_assoc($result)) {
                        $ins_id = $row['id'];
                    }                          
                }
                mysqli_free_result($result);
            }

            for($i = 0; $i < count($data_arr); $i++){   // FOR跑一遍所有資料

                $product = $data_arr[$i]['title'];      // 商品名稱

                $sql = "SELECT * FROM product WHERE name='" . $product ."'";
                if($result = mysqli_query($link, $sql)){
                    $data_count = mysqli_num_rows($result);
                    if($data_count > 0){   
                        while ($row = mysqli_fetch_assoc($result)) {
                            $product_id = $row['id'];
                        }                          
                    }
                    mysqli_free_result($result);
                }

                $price = $data_arr[$i]['price'];        // 商品價格
                
                // 確認同一筆訂單中，是否有一樣的商品
                $sql = "SELECT * FROM checkout_detail WHERE checkout_id='" . $ins_id ."' and product_id='" . $product_id . "'";
                if($result = mysqli_query($link, $sql)){
                    $data_count = mysqli_num_rows($result);
                    if($data_count > 0){                    
                        $sql = "SELECT * FROM checkout_detail WHERE checkout_id='" . $ins_id ."' and product_id='" . $product_id . "'";
                        if($result = mysqli_query($link, $sql)){
                            $data_count = mysqli_num_rows($result);
                            if($data_count > 0){   
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $number = $row['number'];
                                }                          
                            }
                            mysqli_free_result($result);
                        }

                        $sql = "UPDATE checkout_detail SET number=" . ($number + 1) . " WHERE checkout_id='" . $ins_id ."' and product_id='" . $product_id . "'"; 
                        if($result2 = mysqli_query($link, $sql)){
                            mysqli_free_result($result2);
                        } 
                    }else{
                        $sql = "INSERT INTO checkout_detail (checkout_id, product_id, number) VALUES ('" . $ins_id . "', '" . $product_id . "', '" . 1 . "')"; 
                        if($result2 = mysqli_query($link, $sql)){
                            mysqli_free_result($result2);
                        }                  
                    }
                    mysqli_free_result($result);
                }             
            }
            $data['success'] = true;                    // 結帳成功

            echo json_encode($data);
            break;
        case 'logout':

            session_start();            // 操作SESSION一定要先執行START
            session_destroy();          // 清除所有SESSION

            $data['success'] = true;    // 登出成功
            $data['logout'] = true;
            
            echo json_encode($data);
            break;
    }
}

?>