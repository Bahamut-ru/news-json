<html>
<head>
	<title>Добавить новость</title>
</head>

<body>

<form action="add.php" enctype="multipart/form-data" method="post">
	<label for="name">Название новости:</label>
	<input type="text" name="name">

	<label for="desc">Краткое описание:</label>
	<input type="textarea" name="desc"></textarea>

	<label for="pic">Изображение:</label>
	<input type="file" name="pic" accept="image/jpeg,image/png">

	<input type="submit" name="submit" value="Отправить">
</form>

<?php
if (isset($_POST['submit']) && $_POST['submit'] == 'Отправить') {
	if (!empty($_POST['name'] && $_POST['desc'] && $_FILES['pic']) && $_FILES['pic']['error'] === UPLOAD_ERR_OK) {
		if ($_FILES['pic']['size'] < 1048576) {
			$pic_tmp_dest = $_FILES['pic']['tmp_name'];
			$pic_name = $_FILES['pic']['name'];
			$pic_ext = strtolower(end(explode(".", $pic_name)));
			$accept_ext = array('jpg','jpeg','png');
			if (in_array($pic_ext, $accept_ext)) {
				$new_pic_name = md5(time() . $pic_name) . '.' . $pic_ext;
				$pic_dest = './'.date('Y').'/'.$new_pic_name; // Я использую папку с годом публикации, потому что мне довелось разгребать новостной сайт без каталога и это была личная травма =)
				if (!is_dir(date('Y'))) {
					mkdir(date('Y'), 0755);
				}
				move_uploaded_file($pic_tmp_dest,$pic_dest);
				$news_file = 'news_addable.json';
				if (!file_exists($news_file)) {
					fopen($news_file, 'w');
				}
				$news = json_decode(file_get_contents($news_file),true);
				$add_news = array(name => htmlspecialchars($_POST['name']), desc => htmlspecialchars($_POST['desc']), pic => $pic_dest);
				if (!empty($news)) {
					array_push($news, $add_news); // Если данные уже есть — добавляем наш массив
				} else {
					$news[] = $add_news; // Если данных еще нет — создаем многомерный массив
				}
				$news = json_encode($news,JSON_UNESCAPED_UNICODE);
				file_put_contents($news_file,$news);
				echo "Новость добавлена!";
			} else {
				echo "Формат картинки не подходит.";
			}
		} else {
			echo "Файл слишком большой.";
		}
	} else {
		echo "Чего-то не хватает!";
	}
}
?>

</body>
</html>
