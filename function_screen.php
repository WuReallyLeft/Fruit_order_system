<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8" />
   <title>function_screen.php</title>
   <link href="css/style7.css" rel="stylesheet">
</head>

<body>
   <?php
   session_start(); // 啟用交談期
   // 檢查Session變數是否存在, 表示是否已成功登入
   if ($_SESSION["login_session"] != true)
      header("Location: login.php");
   echo "歡迎使用者進入網站!<br/>";

   require_once("myproject_open.inc");
   $sql = "SELECT * FROM 使用者資訊表 WHERE 用戶唯一識別碼='" . $_SESSION['id'] . "'";
   // 執行SQL查詢
   $result = mysqli_query($link, $sql);
   $meta = mysqli_fetch_assoc($result);
   if ($meta["權限等級"] == 1) {
      //-------------------------------------------------------------------------------上架商品功能
      echo "<form action='function_screen.php' method='post'>
      <input type='submit' name='put' value='上架商品'>";
      if (isset($_POST["put"])) {
         ?>
         <form action="orderer_screen.php" method="post">
            <tr>
               <td>商品名稱:</td>
               <td><input type="text" name="pname" size="12" /></td>
            </tr>
            <tr>
               <td>等級:</td>
               <td><input type="text" name="grade" size="12" /></td>
            </tr>
            <tr>
               <td>規格:</td>
               <td><input type="text" name="specification" size="12" /></td>
            </tr>
            <tr>
               <td>禮盒價格:</td>
               <td><input type="text" name="price" size="12" /></td>
            </tr>
            <tr>
               <td>每盒幾粒:</td>
               <td><input type="text" name="num" size="12" /></td>
            </tr>
            <input type='submit' name='upput' value='上架'>
         </form>
         <?php
      }
      if (isset($_POST["upput"])) {
         $query = "SELECT * FROM 商品表";
         $result = mysqli_query($link, $query);
         $lastRowIndex = 0;
         if ($result) {
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $lastRowIndex = count($rows) + 1;
         }
         $sql = "INSERT INTO 商品表 values ('";
         $sql .= $_POST["pname"] . "', '" . "p" . $lastRowIndex . "', '" . $_POST["grade"] . "', '" . $_POST["specification"] . "', '" . $_POST["price"] . "', '" . $_POST["num"] . "')";
         mysqli_query($link, "SET FOREIGN_KEY_CHECKS = 0");
         $result = mysqli_query($link, $sql);
         mysqli_query($link, "SET FOREIGN_KEY_CHECKS = 1");
         echo "上架成功!";
      }
      //-------------------------------------------------------------------------------顯示對帳單
      ?>
      <!--<form action='function_screen.php' method='post'>
         <input type='submit' name='account' value='對帳'>-->
         <?PHP
         //if (isset($_POST['account'])) {
            $sql = "SELECT * FROM 用戶繳費單表 WHERE 對帳確認='N'";
            $result = mysqli_query($link, $sql);
            $_SESSION['count'] = 0;
            if ($result) {
               while ($row = mysqli_fetch_assoc($result)) {
                  echo "<br>對帳<br><table><tr><td>繳費單編號</td><td>用戶唯一識別碼</td><td>訂單唯一識別碼</td><td>繳費日期</td> 
             <td>繳費金額</td><td>繳費方式</td><td>匯款參考號碼</td></tr>";
                  echo "<tr><td>" . $row['繳費單編號'] . "</td>";
                  echo "<td>" . $row['用戶唯一識別碼'] . "</td>";
                  echo "<td>" . $row['訂單唯一識別碼'] . "</td>";
                  echo "<td>" . $row['繳費日期'] . "</td>";
                  echo "<td>" . $row['繳費金額'] +200 . "</td>";
                  echo "<td>" . $row['繳費方式'] . "</td>";
                  echo "<td>" . $row['匯款參考號碼'] . "</td></tr></table>";
                  ?>
                  <form action="function_screen.php" method="POST">
                     <input type="hidden" name="<?php echo "munbe" . $_SESSION['count'] ?>" value="<?php echo $row['繳費單編號'] ?>">
                     <input type="hidden" name="<?php echo "omunbe" . $_SESSION['count'] ?>" value="<?php echo $row['訂單唯一識別碼'] ?>">

                     <input type="submit" name="<?php echo "de" . $_SESSION['count'] ?>" value="刪除">
                     <input type="submit" name="<?php echo "ok" . $_SESSION['count'] ?>" value="確認">
                  </form>
                  <hr>
                  <?php
                  $_SESSION['count']++;
               }
            //}
            for ($i = 0; $i < $_SESSION['count']; $i++) {
               if (isset($_POST['de' . $i])) {
                  $sql = "DELETE FROM 用戶繳費單表 WHERE 繳費單編號 ='" . $_POST['munbe' . $i] . "'";
                  $result = mysqli_query($link, $sql);
                  $sql = "UPDATE 訂貨單表 SET `是否繳款`= 'N' WHERE `訂單唯一識別碼`='" . $_POST['omunbe' . $i] . "'";
                  $result = mysqli_query($link, $sql);    
                  echo "123";   
                  header("Location: function_screen.php");
               } else if (isset($_POST['ok' . $i])) {
                  $sql = "UPDATE 訂貨單表 SET `對帳確認`= 'Y' WHERE `訂單唯一識別碼`='" . $_POST['omunbe' . $i] . "'";
                  $result = mysqli_query($link, $sql);
                  $sql = "UPDATE 用戶繳費單表 SET `對帳確認`= 'Y' WHERE `訂單唯一識別碼`='" . $_POST['omunbe' . $i] . "'";
                  $result = mysqli_query($link, $sql);
                  $sql = "INSERT INTO 收款對帳單表(繳費單編號, 用戶唯一識別碼, 確認收款) VALUES ('" . $_POST['munbe' . $i] . "','" . $_SESSION['id'] . "','Y')";
                  $result = mysqli_query($link, $sql);
                  echo "456";
                  header("Location: function_screen.php");
               }
            }
         }

         //-------------------------------------------------------------------------------顯示訂單
         ?>
         <form action='function_screen.php' method='post'>
            <input type='submit' name='show' value='顯示訂單'>
            <?PHP
            if (isset($_POST["show"])) {
               $query = "SELECT 訂貨單表.訂單唯一識別碼, 訂貨單表.對帳確認 ,用戶唯一識別碼,收貨人,商品表.商品唯一識別碼 FROM 訂貨單表
         JOIN 訂單商品關聯表 ON 訂貨單表.訂單唯一識別碼 = 訂單商品關聯表.訂單唯一識別碼
         JOIN 商品表 ON 訂單商品關聯表.商品唯一識別碼 = 商品表.商品唯一識別碼";
               $result = mysqli_query($link, $query);
               // 檢查查詢結果
               if ($result) {
                  $identify = "";
                  // 獲取結果集中的資料
         
                  while ($row = mysqli_fetch_assoc($result)) {
                     if ($row['對帳確認'] == "Y") {
                        if ($identify != $row['訂單唯一識別碼']) {
                           echo "<table><tr><td>訂單編號</td><td>用戶編號</td><td>收貨人</td></td><td>商品編號:</td></tr>";
                           echo "<tr><td>" . $row['訂單唯一識別碼'] . "</td><td>" . $row['用戶唯一識別碼'] .
                              "</td><td>" . $row['收貨人'] . "</td><td>" . $row['商品唯一識別碼'] . "</td></tr>";
                        } else {
                           echo "<tr><td>" . $row['訂單唯一識別碼'] . "</td><td>" . $row['用戶唯一識別碼'] . "</td><td>"
                              . $row['收貨人'] . "</td><td>" . $row['商品唯一識別碼'] . "</td></tr>";
                        }
                        $identify = $row['訂單唯一識別碼'];
                     }
                  }
                  echo "</table>";
               }
            }
            echo "<br><a href='report.php'>報表</a>";
   } else {
      //-------------------------------------------------------------------------------填寫訂貨單功能
      ?>
            <form action='function_screen.php' method='post'>
               <input type='submit' name='write' value='填寫訂貨單'>
               <?php
               $query = "SELECT * FROM 商品表";
               $result = mysqli_query($link, $query);
               $lastRowIndex = 0;
               if ($result) {
                  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
                  $lastRowIndex = count($rows) + 1;
               }
               if (isset($_POST["write"])) {
                  ?>
                  <form action='function_screen.php' method='post'>

                     <table border='1'>
                        <tr>
                           <td>等級</td>
                           <td>規格</td>
                           <td>禮盒價格</td>
                           <td>每盒幾粒</td>
                           <td>數量</td>
                        </tr>

                        <?php
                        $sql = "SELECT * FROM 商品表";
                        // 執行SQL查詢
                        $result = mysqli_query($link, $sql);
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                           echo "<tr>
               <td>" . $row["等級"] . " </td>
               <td>" . $row["規格"] . " </td>
               <td>" . $row["每盒價格"] . " </td>
               <td>" . $row["每盒幾粒"] . " </td>
               <td><input tpye='number' name='" . "p" . $i . "' value='0' size='5'> </td>
               </tr>";
                           $i++;
                        }
                        ?>
                     </table>
                     <table>
                        <tr>
                           <td>收貨人:</td>
                           <td><input type="text" name="receive" size="12" /></td>
                        </tr>
                        <tr>
                           <td>收貨人行動電話:</td>
                           <td><input type="text" name="rmphone" size="12" /></td>
                        </tr>
                        <tr>
                           <td>收貨地址:</td>
                           <td><input type="text" name="raddress" size="50" /></td>
                        </tr>
                        <tr>
                           <td>到貨日期:</td>
                           <td><input type="date" name="date" size="12" /></td>
                        </tr>
                        <tr>
                           <td>到貨時段:</td>
                           <td><input type="time" name="hour" size="12" /></td>
                        </tr>
                     </table>
                     <input type='submit' name='order' value='訂貨'>
                  </form>
                  <?php
               }
               if (isset($_POST["order"])) {
                  $number = "";
                  $total = 0;
                  $pnumber = "";
                  for ($i = 1; $i < $lastRowIndex; $i++) {
                     if ($_POST["p" . $i] != 0) {
                        $sql = "SELECT 每盒價格 FROM 商品表 WHERE 商品唯一識別碼='p" . $i . "'";
                        $result = mysqli_query($link, $sql);
                        if ($result) {
                           $meta = mysqli_fetch_assoc($result);
                           $total += ($meta["每盒價格"] * $_POST["p" . $i]);
                           mysqli_free_result($result);
                        }
                        $pnumber .= "p" . $i;
                        $number .= $_POST["p" . $i] . "/";
                     }
                  }
                  $number = substr($number, 0, strlen($number) - 1);

                  // 插入訂貨單表（Orders）
                  $sql = "INSERT INTO 訂貨單表(用戶唯一識別碼, 收貨人, 收貨人行動電話, 收貨地址, 收貨日期, 到貨時段, 商品數量, 金額) VALUES ";
                  $sql .= "('" . $_SESSION['id'] . "','" . $_POST['receive'] . "', '" . $_POST["rmphone"] . "', ";
                  $sql .= "'" . $_POST["raddress"] . "', '" . $_POST["date"] . "', '" . $_POST["hour"] . "', '" . $number . "', '" . $total . "')";

                  $result = mysqli_query($link, $sql);
                  if ($result) {
                     // 獲取剛插入的訂單唯一識別碼
                     $lastOrderID = mysqli_insert_id($link);
                     mysqli_query($link, "SET FOREIGN_KEY_CHECKS = 0");
                     // 更新訂單唯一識別碼
                     $updateQuery = "UPDATE 訂貨單表 SET `訂單唯一識別碼` = 'o" . $lastOrderID . "' WHERE `訂單唯一識別碼` IS NULL";
                     $updateResult = mysqli_query($link, $updateQuery);
                     mysqli_query($link, "SET FOREIGN_KEY_CHECKS = 1");


                     if ($updateResult) {
                        // 插入訂單商品關聯表（Order_Products）
                        for ($i = 0; $i < strlen($pnumber); $i += 2) {
                           $sql = "INSERT INTO 訂單商品關聯表(商品唯一識別碼, 訂單唯一識別碼) VALUES ";
                           $sql .= "('" . substr($pnumber, $i, 2) . "','o" . $lastOrderID . "')";
                           $result = mysqli_query($link, $sql);
                        }
                     }
                  }
                  echo "已送出訂單!";
               }
               //-------------------------------------------------------------------------------顯示繳費單
               $sql = "SELECT * FROM 訂貨單表 WHERE 用戶唯一識別碼='";
               $sql .= $_SESSION['id'] . "'AND  是否繳款='N" . "'";
               $result = mysqli_query($link, $sql);
               $_SESSION['count'] = 0;
               if ($result) {
                  while ($row = mysqli_fetch_assoc($result)) {
                     echo "<br>繳費單<br>";
                     echo "訂單編號:" . $row['訂單編號'] . "<br>";
                     echo "總金額:" . $row['金額'] . "<br>";
                     ?>
                     <form action="function_screen.php" method="post">
                        運費:200<br>
                        <tr>
                           <td>
                              繳費方式:
                           </td>
                           <td>

                              <label><input type="radio" name="<?php echo "select" . $_SESSION['count'] ?>" value="現金">現金</label>
                              <label><input type="radio" name="<?php echo "select" . $_SESSION['count'] ?>" value="匯款">匯款</label>
                           </td>
                        </tr>
                        <tr>
                           <br>匯款帳號:郵局(700)0141256-0026756、農會(886)88601-11-2075129<br>
                        </tr>
                        <tr>
                           <label>匯款參考號碼:<input type="text" name="<?php echo "exchange" . $_SESSION['count'] ?>"></label>
                        </tr>

                        <input type="hidden" name="<?php echo "amount" . $_SESSION['count'] ?>" value="<?php echo $row['金額'] ?>">
                        <input type="hidden" name="<?php echo "munb" . $_SESSION['count'] ?>"
                           value="<?php echo $row['訂單唯一識別碼'] ?>">
                        <input type="submit" name="<?php echo "fee_ok" . $_SESSION['count'] ?>" value="確認繳費">
                     </form>
                     <hr>
                     <?php
                     $_SESSION['count']++;
                  }
               }
               for ($i = 0; $i < $_SESSION['count']; $i++) {
                  if (isset($_POST["fee_ok" . $i])) {
                     $sql = "INSERT INTO 用戶繳費單表(用戶唯一識別碼, 訂單唯一識別碼, 繳費日期, 繳費金額, 繳費方式, 匯款參考號碼) VALUES ";
                     $sql .= "('" . $_SESSION['id'] . "','" . $_POST['munb' . $i] . "', '" . date("Y/n/j") . "', '" . $_POST["amount" . $i] . "', ";
                     $sql .= "'" . $_POST["select" . $i] . "', '" . $_POST["exchange" . $i] . "')";
                     $result = mysqli_query($link, $sql);
                     $sql = "UPDATE 訂貨單表 SET `是否繳款`= 'Y' WHERE `訂單唯一識別碼`='" . $_POST['munb' . $i] . "'";
                     $result = mysqli_query($link, $sql);
                     header("Location: function_screen.php");
                  }
               }
      //-------------------------------------------------------------------------------
   }
   //mysqli_free_result($result);
   require_once("myproject_close.inc");
   echo "<br><a href='index.php'>跳轉至首頁</a>&nbsp";
   echo "<a href='modify_data.php'>修改資料</a>";
   ?>
</body>

</html>