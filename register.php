<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>register.php</title>
    <link href="css/style5.css" rel="stylesheet">
</head>

<body>
    
    <?php
    if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"])  && isset($_POST["phone"]) ) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        // 檢查是否有輸入欄位資料 
        if ($username != "" && $password != "" && $email != "" && $phone != "") {
           require_once("myproject_open.inc");
           // 建立SQL字串
           $query = "SELECT * FROM 使用者資訊表";
           $result = mysqli_query($link, $query);
           $lastRowIndex=0;
           if ($result) {
              $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
              $lastRowIndex = count($rows) +1;           
           }
           $sql = "INSERT INTO 使用者資訊表(用戶唯一識別碼,用戶名, 密碼, 電子郵件, 電話)  values ('";
           $sql.= "u" . $lastRowIndex . "', '" . $username ."', '".$password."', '" . $email . "', '".$phone."')";        
           if ( mysqli_query($link, $sql) ) { // 執行SQL指令
              echo "<font color=red>新增聯絡資料成功!";
              echo "</font><br/>";
           }
           mysqli_free_result($result);
           require_once("myproject_close.inc");
        }
     }
    ?>
    <form action="register.php" method="post">
        <table>
            <tr>
                <td>用戶名:</td>
                <td><input type="text" name="username" size="6" /></td>
            </tr>
            <tr>
                <td>密碼:</td>
                <td><input type="password" name="password" size="12" /></td>
            </tr>
            <tr>
                <td>電子郵件:</td>
                <td><input type="email" name="email" size="25" /></td>
            </tr>
            <tr>
                <td>電話:</td>
                <td><input type="text" name="phone" size="10" />
                </td>
            </tr>
        </table>
        <input type="submit" name="Insert" value="註冊" />
    </form>
    <?php
        if(isset($_POST['Insert'])) 
            echo "<div><a href='login.php'>跳轉至登錄</a></div>";
    ?>
</body>

</html>