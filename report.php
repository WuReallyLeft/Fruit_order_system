<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>report.php</title>
    <link href="css/style8.css" rel="stylesheet">
</head>

<body>
    <h1>收入支出報表</h1>
    <table>
        <tr>
            <td>日期</td>
            <td>訂單唯一識別碼</td>
            <td>收入/支出</td>
            <td>金額</td>
        </tr>
        <?php
        session_start(); // 啟用交談期
        require_once("myproject_open.inc");
        $sql = "SELECT * FROM 用戶繳費單表 WHERE 對帳確認='Y'";
        $result = mysqli_query($link, $sql);
        $total = 0;
        // 檢查查詢結果
        if ($result) {
            // 獲取結果集中的資料
        
            while ($row = mysqli_fetch_assoc($result)) {
                $total += $row['繳費金額'];
                echo "<tr><td>" . $row['繳費日期'] . "</td>
                <td>" . $row['訂單唯一識別碼'] . "</td>";
                if ($row['繳費金額'] > 0) {
                    echo "<td>+</td>";
                } else {
                    echo "<td>-</td>";
                }
                echo "<td>" . $row['繳費金額'] . "</td>";
            }
        }
        ?>
        <tr>
            <td colspan="3" align="left">總收入</td>
            <td align="right">
                <?php echo $total; ?>
            </td>
        </tr>
    </table>
    <?PHP
    require_once("myproject_close.inc");
    ?>
    <a href="index.php">首頁</a>
    <a href="function_screen.php">功能列表</a>
</body>

</html>