<?php
	include "config.php";

	$sql = "select * from photos order by click_count desc";
	$result = mysqli_query($connect, $sql);
?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        .gallery {
            display: flex;
            width: 100%;
            flex-wrap: wrap;
            justify-content: flex-start;
            margin: 20px auto;
        }
    </style>
</head>
<body>

<?php

	// Пути загрузки файлов
	$path = 'C:/Users/DK/OSPanel/domains/HW5/Ex1/min/';
	$pathMax = 'C:/Users/DK/OSPanel/domains/HW5/Ex1/max/';
	$tmp_path = 'C:/Users/DK/OSPanel/domains/HW5/Ex1/tmp/';
	// Массив допустимых значений типа файла
	$types = array('image/gif', 'image/png', 'image/jpeg');
	// Максимальный размер файла
	$size = 1024000;

	// Обработка запроса
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		function resize($file)
		{
			global $tmp_path;

			// Ограничение по ширине в пикселях
			$max_thumb_size = 200;
			$max_size = 600;

			// Cоздаём исходное изображение на основе исходного файла
			if ($file['type'] == 'image/jpeg')
				$source = imagecreatefromjpeg($file['tmp_name']);
            elseif ($file['type'] == 'image/png')
				$source = imagecreatefrompng($file['tmp_name']);
            elseif ($file['type'] == 'image/gif')
				$source = imagecreatefromgif($file['tmp_name']);
			else
				return false;

			// Определяем ширину и высоту изображения
			$w_src = imagesx($source);
			$h_src = imagesy($source);

			$w = $max_thumb_size;

			// Если ширина больше заданной
			if ($w_src > $w) {
				// Вычисление пропорций
				$ratio = $w_src / $w;
				$w_dest = round($w_src / $ratio);
				$h_dest = round($h_src / $ratio);

				// Создаём пустую картинку
				$dest = imagecreatetruecolor($w_dest, $h_dest);

				// Копируем старое изображение в новое с изменением параметров
				imagecopyresampled($dest, $source, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);

				// Вывод картинки и очистка памяти
				imagejpeg($dest, $tmp_path . $file['name']);
				imagedestroy($dest);
				imagedestroy($source);

				global $connect;

                $insert = "INSERT INTO photos (path, name, size) VALUES ('".$file["name"]."', '".$file["name"]."', '".$file["size"]."');";
				mysqli_query($connect, $insert);

				return $file['name'];
			} else {
				// Вывод картинки и очистка памяти
				imagejpeg($src, $tmp_path . $file['name']);
				imagedestroy($src);

				global $connect;

				$insert = "INSERT INTO photos (path, name, size) VALUES ('".$file["name"]."', '".$file["name"]."', '".$file["size"]."');";
				mysqli_query($connect, $insert);

				return $file['name'];
			}
		}

		$name = resize($_FILES['userfile'], $_POST['file_type'], $_POST['file_rotate']);

		// Загрузка файла и вывод сообщения
    move_uploaded_file($_FILES['userfile']['tmp_name'], $pathMax . $_FILES['userfile']['name']);
		@copy($tmp_path . $name, $path . $name);

		// Удаляем временный файл
		unlink($tmp_path . $name);
	}
?>

<form enctype="multipart/form-data" method="POST">
    <!-- Название элемента input определяет имя в массиве $_FILES -->
    Отправить этот файл: <input name="userfile" type="file"/>
    <input type="submit" value="Отправить файл"/>
</form>

<div class="gallery">
	<?php
		while($data = mysqli_fetch_assoc($result)) {
			echo '<div class = "img" >
		                <a href="max.php?id=' . $data[id] . '" target="_blank">
		                  <img src="min/' . $data[name] . '" title="' . $data[name] . '" data-id="' . $data[id] . '" />
		                </a>
		            </div>';
		}
	?>
</div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>


    $('.img').on('click', 'a', function () {
      var id = $(this).find('img').data('id');

      $.ajax({
        type: 'POST',
        url: 'click.php',
        data: `id=${id}`
      });
    })
</script>
</html>