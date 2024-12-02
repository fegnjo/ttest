<?php
include 'db_connect.php';
$lines = 0;
$data = [];
if (!empty($_GET['db'])) {
    $db_name = $_GET['db'];
    if($_GET['db'] == 'records') {
        $query = 'SELECT * FROM `records`';
    } else if($_GET['db'] == 'comments') {
        $query = 'SELECT * FROM `comments`';
    }
    $sql = $conn->query($query);
    while($row = $sql->fetch_assoc()){
        $data[] = $row;
    }
    $lines = count($data);
    header('Content-Description: File Transfer');
    header('Content-Type: text/plain');
    header("Content-Disposition: attachment; filename= $db_name.txt");
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
}
if (!empty($_POST['table_name'])) {
    if ($_FILES['userfile']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['userfile']['tmp_name'])) {
        $data = file_get_contents($_FILES['userfile']['tmp_name']);
    }
        $res = json_decode($data, true);
        $row = [];
        $lines = count($res);

        if ($_POST['table_name'] == 'records') {
            foreach ($res as $row) {
                $query = "INSERT INTO `records` (userId, title, body) VALUES ($row[userId], '$row[title]', '$row[body]')";
                $sql = $conn->query($query);
            }
            if ($lines > 0) {
                echo "<script>alert('База залита. Обработано записей: $lines.');location.href='{$_SERVER['PHP_SELF']}'</script>";
            }
        }
        if ($_POST['table_name'] == 'comments') {
            foreach ($res as $row) {
                $query = "INSERT INTO `comments` (postId, name, email, body) VALUES ($row[postId], '$row[name]', '$row[email]', '$row[body]')";
                $sql = $conn->query($query);
            }
            if ($lines > 0) {
                echo "<script>alert('База залита. Обработано комментариев: $lines.');location.href='{$_SERVER['PHP_SELF']}'</script>";
            }
        }
    }

?>

        <div>
            <a href="" onclick="var a='records';if(a){this.setAttribute('href', '?db='+a);}"><button>Скачать таблицу записей</button></a>
            <a href="" onclick="var a='comments';if(a){this.setAttribute('href', '?db='+a);}"><button>Скачать таблицу комментариев</button></a>
        </div><br><br>

        <form enctype="multipart/form-data" action="#" method="POST">
            <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
            <input type="hidden" name="table_name" value="records" />
            Загрузить базу записей: <input name="userfile" type="file" />
            <input type="submit" value="Отправить файл" />
        </form>
        <form enctype="multipart/form-data" action="#" method="POST">
            <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
            <input type="hidden" name="table_name" value="comments" />
            Загрузить базу комментариев: <input name="userfile" type="file" />
            <input type="submit" value="Отправить файл" />
        </form>



