<html>
<head>
	<title>Список новостей</title>
</head>

<body>

<?php
$news_file = 'news.json';
$news = json_decode(file_get_contents($news_file),true);
foreach ($news as $item) { ?>
<h1><?php echo $item["name"] ?></h1><img src="<?php echo $item["pic"] ?>" style="max-width: 300px;"><br /><?php echo $item["desc"]; } ?>
</body>
</html>
