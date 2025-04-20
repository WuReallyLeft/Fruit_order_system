<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8" />
   <title>login.php</title>
   <link href="css/style4.css" rel="stylesheet">
</head>

<body>
   <?php
   session_start(); // 啟用交談期
   $username = "";
   $password = "";
   // 取得表單欄位值
   if (isset($_POST["Username"]))
      $username = $_POST["Username"];
   if (isset($_POST["Password"]))
      $password = $_POST["Password"];
   // 檢查是否輸入使用者名稱和密碼
   if ($username != "" && $password != "") {
      require_once("myproject_open.inc");
      $sql = "SELECT * FROM 使用者資訊表 WHERE 密碼='";
      $sql .= $password . "' AND 用戶名='" . $username . "'";
      // 執行SQL查詢
      $result = mysqli_query($link, $sql);
      $total_records = mysqli_num_rows($result);
      $id = mysqli_fetch_assoc($result);
      // 是否有查詢到使用者記錄
      if ($total_records > 0) {
         // 成功登入, 指定Session變數
         $_SESSION["login_session"] = true;
         $_SESSION["username"]=$username;
         $_SESSION["id"]=$id["用戶唯一識別碼"];
         header("Location: function_screen.php");
      } else { // 登入失敗
         echo "<center><font color='red'>";
         echo "使用者名稱或密碼錯誤!<br/>";
         echo "</font>";
         $_SESSION["login_session"] = false;
      }
      require_once("myproject_close.inc");
   }
   ?>
   <form action="login.php" method="post">
      <table>
         <tr>
            <td>
               <font size="2">使用者名稱:</font>
            </td>
            <td><input type="text" name="Username" size="15" maxlength="10" />
            </td>
         </tr>
         <tr>
            <td>
               <font size="2">使用者密碼:</font>
            </td>
            <td><input type="password" name="Password" size="15" maxlength="10" />
            </td>
         </tr>
         <tr>
            <td colspan="2" align="center">
               <input type="submit" value="登入網站" />
            </td>
         </tr>
      </table>
   </form>
</body>

</html>