<?php
function setForm(){
    $filename = getFileName();
    $message = getMessage();
    $hidden = setInputHidden();
    if(!empty($_GET['view'])&&$_GET['view'] === 'edit'){
        echo "<h1 class=\"title\">Редактирование файла <span>$filename</span></h1>
                <form name=\"myform\" id=\"editform\" action=\"action.php\" method=\"get\">
                    $message
                    $hidden";
                    $file = getFile();
                    echo "<textarea class=\"form-control\" name=\"editfile\" rows=\"20\">$file</textarea>
                    <input type=\"submit\" name=\"send\" class=\"btn btn-primary\" value=\"Сохранить\"/>
                    <a href=\"index.php\" class=\"btn btn-danger\">Вернуться на главную</a>
                </form>";
    } elseif (!empty($_GET['view'])&&$_GET['view'] === 'viewfile') {
        if (checkImages($filename)) {
            $path = $_GET['file'];
            copy("$path", "tmpimage/$filename");
            echo "<h1 class=\"title\">Вот так выглядит картинка <span>$filename</span></h1>
                    <div id=\"viewfile\">
                    <table class=\"table table-bordered\">
                        <tr>
                            <td><img src='tmpimage/$filename' alt='path'/></td>
                        </tr>
                    </table>
                    <a href=\"index.php\" class=\"btn btn-danger\">Вернуться на главную</a>
                </div>";
        } else {
            echo "<h1 class=\"title\">Содержимое файла файла <span>$filename</span></h1>
                <form name=\"myform\" id=\"editform\" action=\"action.php\" method=\"get\">
                    $message
                    $hidden";
            $file = getFile();
            echo "<textarea class=\"form-control\" readonly name=\"editfile\" rows=\"20\">$file</textarea>
                <a href=\"index.php\" class=\"btn btn-danger\">Вернуться на главную</a>
                </form>";
        }
    } else echo "<div id=\"message-nopage\">
                    <h1 class=\"title-message\">Вы ничего не выбрали для просмотра или редактирования=) <br>Вернитесь на главную страницу для того чтобы открыть файл!</h1>
                    <a href=\"index.php\" class=\"btn btn-danger btn-lg\">Вернуться на главную страницу</a>
                </div>";
}

function getFileName(){
    if(!empty($_GET['file'])) {
        $file = $_GET['file'];
        return basename("$file");
    } else return false;
}

function setInputHidden(){
    $string_hidden = "";
    if(!empty($_GET['view'])&&!empty($_GET['file'])){
        $view = $_GET['view'];
        $file = $_GET['file'];
        $string_hidden = "<input type=\"hidden\" name=\"view\" value=\"$view\">";
        $string_hidden .= "<input type=\"hidden\" name=\"file\" value=\"$file\">";
    }
    return $string_hidden;
}

function getFile(){
    if($_GET&&!empty($_GET['view'])){
        $file = $_GET['file'];
        $fp = fopen($file, "rb");
        $string = fread($fp, filesize($file));
        $string = htmlspecialchars($string);
        fclose($fp);
    } else return false;
    return $string;
}

function editFile(){
    if($_GET&&!empty($_GET['editfile'])){
        $file = $_GET['file'];
        $fp = fopen($file, "w");
        $editfile = $_GET['editfile'];
        fwrite($fp, $editfile);
        fclose($fp);
    } else return false;
    return $editfile;
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

function getMessage(){
    if(editFile()){
        $file = $_GET['file'];
        $string = "<div class='alert alert-success'>Файл $file успешно редактирован</div>";
    } else return false;
    return $string;
}

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
            <?php setForm(); ?>
        </div>
    </div>
    <?php editFile()?>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>
