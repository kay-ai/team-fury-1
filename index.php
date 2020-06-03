<?php


$json = $_SERVER["QUERY_STRING"] ?? '';
$api = [];
$data = [];

function checkpass($string)
{
    if (preg_match('/Hello\sWorld,\sthis\sis\s\w+(?:\s\w+)*\swith\sHNGi7\sID\sHNG-[0-9]{5}\sand\semail\s{1,3}(([\w+\.\-]+)@([\w+\.\-]+)\.([a-zA-Z]{2,5}))\susing\s\w+\sfor\sstage\s2\stask/i', trim($string))) {
        return 'Pass';
    }
    else{
        return 'Fail';
    }

}

function checkemail($string){
    if (preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $string, $matches)) {
      return  $email = ($matches[0]);
    }
    else{
      return null;
    }
}

function checkid($string){
    if (preg_match_all("/HNG-[0-9]{5}/i", $string, $matches)) {
        
        return  $id = ($matches[0]);
    }
    else{
      return null;
    }
}

function checklanguage($string){
    if (preg_match_all("/using\s\w+/i", $string, $matches)) {
       $language = str_replace('using','',$matches[0]);
        return  $language;
    }
    else{
      return null;
    }
}

$dir = scandir("scripts/");

unset($dir[0]);
unset($dir[1]);
$output = [];
$execname = '';
$email = [];
$id = [];

foreach ($dir as $file) {
    $fileext = explode('.', $file);

    if($fileext[1] == "php"){
        $execname = "php";
    }
    if($fileext[1] == "js"){
        $execname = "node";
    }
    if($fileext[1] == "py"){
        $execname = "python";
    }
    if($fileext[1] == "java"){
        $execname = "java";
    }
    if($fileext[1] == "dart"){
        $execname = "dart";
    }

    $content = exec($execname . " scripts/".$file);
    $output[] = [$content, checkpass($content), str_replace('_',' ',$fileext[0]), $email];

    @$data[$fileext[0]]->content = $content;
    $data[$fileext[0]]->status = checkpass($content);
    $data[$fileext[0]]->name = str_replace('_',' ',$fileext[0]);
    $data[$fileext[0]]->email = checkemail($content);
    $data[$fileext[0]]->id = checkid($content);
    $data[$fileext[0]]->Language = checklanguage($content);
    
} ob_end_flush();
    $api = $data;

    if (isset($json) && $json == 'json') {
        echo json_encode($api);
    } else {
    ?>

        <!doctype html>
        <html lang="en">
        <head>
            <title>Team Fury | Task 1</title>
            <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            <!-- Google Fonts -->
            <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200&family=Pacifico&family=Titillium+Web:wght@200&display=swap" rel="stylesheet">
            <!-- Bootstrap CSS -->
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            
            <style>
                .header{
                    font-family:'Pacifico', cursive;
                }
                .text-font{
                    font-family: 'Nunito', sans-serif;
                    font-size:15px;
                }
                .title-font{
                    font-family: 'Nunito', sans-serif;
                }
                .text-content{
                    line-height: 15px;
                }
            </style>
        </head>
        <body>
            <div class="card shadow mb-4">
                <div class="row">
                    <div class="col-md-2">
                        <h2><span class="badge badge-secondary mt-3 ml-3">HNGi7</span></h2>
                    </div>
                    <div class="col-md-8">
                        <h2 class="text-center mt-3 mb-3 header">Team Fury</h2>
                    </div>
                    <div class="col-md-2">
                        <h5 class="mt-4 title-font">Submissions <span class="badge badge-secondary"><?php echo(count($dir)); ?></span></h5>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center"> 
                
                <?php
                    $sn=1;
                    foreach ($output as $out) {
                        $color = $out[1] == 'Pass' ? 'success' : 'danger';
                    
                        echo 
                        '<div class="col-md-2 text-right" style="line-height: 50px; font-size:20px; font-weight:500">';
                            echo $sn;
                                    $sn++;
                        echo   <<<EOL
                        </div>
                        <div class="col-md-8 mb-2">
                            <div class="card shadow text-white bg-$color">
                                <div class="card-body" style="line-height:10px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="card-title title-font"><b>Username:</b> $out[2]</p>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <small><b>$out[1]</b></small>
                                        </div>
                                    </div>
                                    <div class="text-content">
                                        <p class="card-text text-font">$out[0]</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2"></div>    
                        EOL;
                    } 
                ?>
                
            </div>
            <!-- Optional JavaScript -->
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        </body>
        </html>
        
<?php
    }
ob_start();
?>