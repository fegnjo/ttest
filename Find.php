
<form action="#" method="POST">
    <!-- Поле MAX_FILE_SIZE требуется указывать перед полем загрузки файла -->
    <input type="text" name="FindValue" />
    <!-- Название элемента input определяет название элемента в суперглобальном массиве $_FILES -->
    <input type="submit" value="search" />
</form>
<?php
include 'db_connect.php';

if(!empty($_POST['FindValue']) && strlen($_POST['FindValue']) >= 3) {
    $findvalue = $_POST['FindValue'];
    $query = "SELECT records.title, comments.body FROM `records` INNER JOIN `comments` ON postId = records.id WHERE comments.body LIKE '%$findvalue%'";
    $sql = $conn->query($query);
    $data = $sql->fetch_all();
}else if (empty($_POST['FindValue'])) {
    echo "<script>alert('Введите значение.')</script>";
}else if (strlen($_POST['FindValue']) < 3) {
    echo "<script>alert('Значение должно содержать 3 и более символов.')</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<table border="1px">
    <thead>
    <tr>
        <th>Title записи</th>
        <th>Комментарий</th>

    </tr>
    </thead>
    <tbody>
    <?php
    if(!empty($data)) {
            foreach ($data as $value) {
                echo "<tr>
                        <th> $value[0] </th>
                        <td> $value[1] </td>
                    </tr>";
            }
    }
    ?>
    </tbody>
</table>
</body>
</html>
