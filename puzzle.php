<?php
$data = $_POST;

if(isset($data['img'])) {
    $filename = $data['img'];
}else if($_FILES['file']['tmp_name']){
    $filename = uniqid().'.jpg';
    move_uploaded_file($_FILES['file']['tmp_name'], 'photos/'.$filename);
}else {

    header('Location: /?error=Вы не выбрали картинку');
}

$original_path = 'photos/'.$filename;
$img_old = imagecreatefromjpeg($original_path);
$size = getimagesize($original_path);
//var_dump($img_old);

$w = 0;
//$w = $size[0] % $data["columns"];
$h = 0;
//$h = $size[1] % $data["rows"];

//echo $size[0]." ".$w;
//echo $size[1]." ".$h;

//var_dump($size);
//$wStep = (int) floor(($size[0]-$w)/$data['columns']);
$wStep = ($size[0]-$w)/$data['columns'];
//$hStep = (int) floor(($size[1]-$h)/$data['rows']);
$hStep = ($size[1]-$h)/$data['rows'];

$pieces = [];


$directory = uniqid();
mkdir('photos/'.$directory);
for($j = 0;$j < $data['rows'];$j++){
for($i = 0;$i < $data['columns'];$i++){

        $filename = uniqid().'.jpg';
        $pieces[] = $filename;
        imagejpeg(imagecrop($img_old, [
            'x' => $i*$wStep,
            'y' => $j*$hStep,
            'width' => $wStep,
            'height' => $hStep,
        ]),'photos/'.$directory.'/'.$filename);

    }
}
$originalPieces = $pieces;
//shuffle($pieces);
//header('Content-Type: image/jpeg');

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">

    <script src="js/jquery.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <title>Assembling</title>
    <style>
        .grid {
            display: grid;
            max-width: 50%;
            width: 98%;
            max-height: 100vh;
            grid-template-columns: repeat(<?= $data['columns'] ?>,1fr);
            grid-template-rows: repeat(<?= $data['rows'] ?>,1fr);
            /*margin: 0 auto;*/
            box-shadow: 0 0 5px rgba(0,0,0,0.5);
            margin: 20px auto;
            transition: box-shadow 1.5s;
        }
        .container {
            position: relative;
        }
    </style>
</head>
<body>
    <script>
        function getRandomInt(min, max) {
            return Math.floor(Math.random() * (max - min)) + min;
        }
        $(function () {
            var imgCoefficient = <?= $size[1] / $size[0] ?>;
            $('.grid').css('height', Math.floor(imgCoefficient*$('.grid').width())+'px');
            // $('.grid').css('width', Math.floor(imgCoefficient*$('.grid').width())+'px');

            $('.draggable').draggable({
                start: function(){
                    $(this).removeClass('success');
                }
            });
            $('.droppable').droppable({
                drop: function (e) {
                    $(e.originalEvent.target).animate({
                        left: e.target.offsetLeft,
                        top: e.target.offsetTop,
                    });
                    if($(this).attr('data-id') === $(e.originalEvent.target).attr('data-id')){
                        $(e.originalEvent.target).addClass('success');
                        if($('.success').length === $('.droppable').length) {
                            $('.grid').addClass('green-shadow');
                            // $('.original').addClass('full');
                            setTimeout(function () {
                                let name = prompt('Как тебя зовут, счастливчик?');
                                alert('Поздравляю, '+name+'! ТЫ СДЕЛАЛ ЭТО ВХАААААА');
                            }, 2000);
                        }
                    }

                }
            });
            $('.draggable').width(Math.ceil($('.droppable').width()));
            $('.draggable').height(Math.ceil($('.droppable').height()));

            $('.draggable').each(function () {
                $(this).css({
                    left: getRandomInt(0, $(window).width() - $(this).width()),
                    top: getRandomInt($('.grid').height()+30, $(window).height() - $(this).height()),
                })
            });
        });
    </script>
        <div class="original">
            Показать оригинал
            <img src="<?= $original_path ?>" alt="">
        </div>
        <div class="grid">
            <?php foreach($pieces as $piece): ?>
                <div data-id="<?= $piece ?>" class="droppable"></div>
            <?php endforeach; ?>
        </div>
        <?php foreach($pieces as $piece): ?>
            <img data-id="<?= $piece ?>" class="draggable" src="photos/<?= $directory ?>/<?= $piece ?>" alt="piece">
        <?php endforeach; ?>

</body>
</html>
