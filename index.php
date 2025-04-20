<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>水果訂單系統</title>
    <meta name="description" content="水果訂單系統">
    <link rel="icon" type="image/png" href="images/favicon.png">

    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div id="home" class="big-bg">
        <header class="page-header wrapper">
            <h1><a href="index.php"><img class="logo" src="images/logo.png" alt="Cafe 首頁"></a></h1>
            <nav>
                <ul class="main-nav">
                    <?php
                    session_start(); // 啟用交談期
                    // 檢查Session變數是否存在, 表示是否已成功登入
                    if (isset($_SESSION['login_session'])) {
                        echo "<li><form action='index.php' method='post'>
                        <li style='color: rgb(255, 255, 255)'>" . $_SESSION['username'] . "</li>        
                        <li><input type='submit'  style='color: rgb(255, 255, 255)' name='fun' value='功能列表'></li>
                        <li><input type='submit'  style='color: rgb(255, 255, 255)' name='logout' value='登出'></li></form></li>";
                        if (isset($_POST["logout"])) {
                            session_start(); // 啟動會話
                            session_unset(); // 清除所有會話資料
                            session_destroy(); // 銷毀會話
                    
                            // 重新導向至其他頁面或重新載入當前頁面
                            header("Location: index.php"); // 替換成你希望跳轉的頁面
                            exit;
                        }else if(isset($_POST["fun"])){
                            header("Location: function_screen.php");
                            exit;
                        }
                    } else {
                        echo "<li><a href='register.php'>註冊</a></li>";
                        echo "<li><a href='login.php'>登陸</a></li>";
                    }
                
                    ?>
                    <!--<li><a href="register.php">註冊</a></li>
                    <li><a href="login.php">登陸</a></li>-->
                </ul>
            </nav>
        </header>

        <div class="home-content wrapper">
            <h2 class="page-title">We'll Make Your Day</h2>
            <p>想不想每天開心的吃水果呢。</p>
            <a class="button" href="product_preview.php">商品介紹</a>
        </div><!-- /.home-content -->
    </div><!-- /#home -->
</body>

</html>