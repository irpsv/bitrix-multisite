<!DOCTYPE html>
<html>
<head>
	<title>Создание второго сайта для Битрикс многосайтовости</title>
</head>
<body>
	<div>
		<?php

		error_reporting(E_ALL);

		$isCreated = true;
		$mainSiteRoot = $_POST['main-site-root'] ?? null;
		if ($mainSiteRoot && !file_exists($mainSiteRoot)) {
			$isCreated = false;
			echo "Указанная директория не существует";
		}
		else if ($mainSiteRoot) {
			$nowSiteRoot = $_SERVER['DOCUMENT_ROOT'];
			$isCreateSite = false;

			$folders = [
				"local",
				"bitrix",
				"upload",
			];
			foreach ($folders as $folder) {
				$mainFolder = realpath(trim($mainSiteRoot, '/')) .'/'. $folder;
				$nowFolder = realpath(trim($nowSiteRoot, '/')) .'/'. $folder;

				echo "{$mainFolder}  ->  {$nowFolder}.......";

				if (symlink($mainFolder, $nowFolder)) {
					echo "ok";
				}
				else if ($error = error_get_last()) {
					$isCreated = false;
					echo $error['message'] ?? json_encode($error);
				}
				else {
					$isCreated = false;
					echo "неизвестная ошибка";
				}
				echo "<br>" . PHP_EOL;
			}
		}
		else {
			$isCreated = false;
		}

		?>
	</div>

	<?php if ($isCreated): ?>

		<h1>
			Параметры нового сайта			
		</h1>
		<p>
			<label>Папка сайта</label>: <input type="text" value="/">
		</p>
		<p>
			<label>URL сервера</label>: <input type="text" value="<?= $_SERVER['HTTP_HOST'] ?>">
		</p>
		<p>
			<label>Путь к корневой папке веб-сервера для этого сайта</label>: <input type="text" value="<?= $nowSiteRoot ?>">
		</p>

	<?php endif; ?>

	<?php if (empty($mainSiteRoot)): ?>

		<h1>
			Параметры копирования
		</h1>
		<form method='post'>
			<div>
				<input type='text' name='main-site-root' placeholder="Полный путь до директории" />
			</div>
			<div>
				<input type='submit' value='Создать ссылки' />
			</div>
		</form>

	<?php endif; ?>
</body>
</html>