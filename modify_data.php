<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>modify_data.php</title>
    <link href="css/style6.css" rel="stylesheet">
</head>

<body>
    <?php
    session_start(); // 啟用交談期
    require_once("myproject_open.inc");
    // 是否是表單送回
    if (isset($_POST["Update"])) {
        // 建立更新記錄的SQL指令字串
        $sql = "UPDATE 使用者資訊表 SET ";
        $sql .= "用戶名='" . $_POST["username"] . "',密碼='" . $_POST["password"] . "',";
        $sql .= "電子郵件= '" . $_POST["email"] . "',電話= '" . $_POST["phone"] . "'";
        $sql .= "WHERE 用戶唯一識別碼='" . $_SESSION["id"] . "'";
        // 送出UTF8編碼的MySQL指令
        mysqli_query($link, $sql); // 執行SQL指令
        header("Location: modify_data.php");
    }
    $sql = "SELECT * FROM 使用者資訊表 WHERE 用戶唯一識別碼='" . $_SESSION["id"] . "'";
    $result = mysqli_query($link, $sql);
    $meta = mysqli_fetch_assoc($result);
    $username = $meta["用戶名"];
    $password = $meta["密碼"];
    $email = $meta["電子郵件"];
    $phone = $meta["電話"];
    require_once("myproject_close.inc");
    ?>
    <form action="modify_data.php" method="post">
        <tr>
            <td>用戶名:</td>
            <td><input type="text" name="username" size="6" value="<?php echo $username ?>" /></td>
        </tr>
        <tr>
            <td>密碼:</td>
            <td><input type="text" name="password" size="12" value="<?php echo $password ?>" /></td>
        </tr>
        <tr>
            <td>電子郵件:</td>
            <td><input type="email" name="email" size="25" value="<?php echo $email ?>" /></td>
        </tr>
        <tr>
            <td>電話:</td>
            <td><input type="number" name="phone" size="10" value="<?php echo $phone ?>" />
            </td>
        </tr>
        </table>
        <br><input type="submit" name="Update" value="更新" />
    </form>
    <hr>
    <a href="index.php">首頁</a>
    <a href="function_screen.php">功能列表</a>
</body>

</html>