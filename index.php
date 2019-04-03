<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Пазл от бога - собери мечту</title>
    <script src="js/jquery.js"></script>
    <script src="js/jquery-ui.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Puzzle From *the* God</h1>
        <h2>Assemble your dream</h2>
        <form method="post" action="/puzzle.php" enctype="multipart/form-data">
            <div><input type="number" name="rows" min="2" placeholder="Кол-во строк" required></div>
            <div><input type="number" name="columns" min="2" placeholder="Кол-во столбцов" required></div>
            <h3><?= $_GET['error'] ?></h3>
            <div class="photos">
                <?php
                    foreach(scandir('photos') as $file):
                    $fileExploded = explode('.', $file);
                    if($fileExploded[count($fileExploded) - 1] != 'jpg') continue;
                ?>
                        <input type="radio" id="<?= $fileExploded[0] ?>" hidden name="img" value="<?= $file ?>">
                        <div class="photo"><button onclick="$('#<?= $fileExploded[0] ?>').prop('checked', true);"><img src="photos/<?= $file ?>" alt="puzzle image"></button></div>
                <?php
                    endforeach;
                ?>
            </div>
            <input id="file" type="file" name="file" accept="image/jpeg" hidden>
            <label for="file" class="btn">Выбрать картинку</label>
            <button class="btn">Загрузить</button>
        </form>
    </div>
</body>
</html>