<?php
function getPath(){
    if(($_GET) && (!empty($_GET['dir']))){
        $path = $_GET['dir'];
        return $path;
    } elseif(empty($_GET)||($_GET)){
        $path = getcwd();
        return $path;
    }
    return true;
}

function getFiles($dir){
    $array = glob("$dir\\*", GLOB_ONLYDIR);
    $array_result = [];
    foreach ($array as $v){
        $array_result[] = $v;
    }
    $array = glob("$dir\\*");
    foreach ($array as $v){
        if(is_dir($v)) continue;
        else {
            $array_result[] = $v;
        }
    }
    return $array_result;
}

function printLevelUpButton(){
    $dir = getPath();
    $dir = dirname($dir);
    echo "<tr>
            <td colspan=\"6\" class=\"levelup\">
                <span class=\"glyphicon glyphicon-level-up\" style=\"color: #a1a306;\" aria-hidden=\"true\"></span><a href=\"index.php?view=levelup&dir=$dir\">Наверх</a>
            </td>
          </tr>";
}

function checkImages($file){
    $imgformat = ['.jpg', '.jpeg', '.gif', '.png'];
    $file = basename("$file");
    foreach ($imgformat as $value){
        if(strpos($file, $value)){
            return true;
        } else return false;
    }
}

function printFiles($dir){
    $array = getFiles($dir);
    foreach ($array as $v){
        $color_icon = "";
        $glyphicon = "";
        $name = basename("$v");
        $creat = "";
        $size = "";
        $filemtime = "";
        $fileatime = "";
        $title = "";
        $link = "";
        if(is_dir($v)) {
            $color_icon = "#FFDC68";
            $glyphicon = "glyphicon glyphicon-folder-open";
            $creat = date("d.m.y", filectime($v));
            $size = "";
            $filemtime = "";
            $fileatime = "";
            $link = "index.php?view=open&dir=$v";
            $title = "<a href='index.php?view=open&dir=$v'>Открыть</a>";
        } elseif (is_file($v)) {
            $color_icon = "#000";
            $glyphicon = "glyphicon glyphicon-file";
            $creat = date("d.m.y", filectime($v));
            $size = filesize($v)." Байт";
            $filemtime = date("d.m.y в H:i", filemtime($v));
            $fileatime = date("d.m.y в H:i", fileatime($v));
            $link = "action.php?view=edit&file=$v";
            if (checkImages($v)){
                $title = "<a href='index.php?view=unlink&dir=$v'>Удалить</a>/<a href='action.php?view=viewfile&file=$v'>Просмотреть</a>";
                $link = "action.php?view=viewfile&file=$v";
            }
            elseif (is_writable($v)) $title = "<a href='index.php?view=unlink&dir=$v'>Удалить</a>/<a href='action.php?view=edit&file=$v'>Редактировать</a>";
            elseif (is_readable($v)) {
                $title = "<a href='action.php?view=viewfile&file=$v'>Просмотреть</a>";
                $link = "action.php?view=viewfile&file=$v";
            }
        }
        echo "<tr>
            <td><span class=\"$glyphicon\" style=\"color: $color_icon;\" aria-hidden=\"true\"></span><a href='$link' style='padding-left: 10px;'>$name</a></td>
            <td>$creat</td>
            <td>$size</td>
            <td>$filemtime</td>
            <td>$fileatime</td>
            <td>$title</td>
          </tr>";
    }
}

function actionFile(){
    $dir = getPath();
    if(($_GET)&&!empty($_GET['view'])){
        $array = $_GET;
        if($array['view'] === 'unlink'){
            unlink($array['dir']);
            $dir = $array['dir'];
            $dir = dirname($dir);
            printFiles($dir);
        }
        if($array['view'] === 'open'){
            $dir = $array['dir'];
            printFiles($dir);
        }
        if($array['view'] === 'levelup'){
            $dir = $array['dir'];
            printFiles($dir);
        }
    }else printFiles($dir);
    return true;
}

function unlinkTmpImage(){
    $path = glob("tmpimage/*.*");
    $file = "";
    if(!empty($path)){
        foreach ($path as $v){
            $file = basename($v);
        }
        unlink("tmpimage/$file");
    } elseif(empty($path)) return false;
    return true;
}
unlinkTmpImage();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <div class="col-md-12">
        <h1>FileManager</h1>
        <table id="table-filemanager" class="table table-striped">
            <thead>
                <tr>
                    <td>Файл</td>
                    <td>Создан</td>
                    <td>Размер</td>
                    <td>Последние изменения</td>
                    <td>Последний доступ</td>
                    <td>Действие</td>
                </tr>
            </thead>
            <tbody>
                <?php printLevelUpButton();?>
                <?php actionFile();?>
            </tbody>
        </table>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>
