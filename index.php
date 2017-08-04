<?php
//if($_POST)
//{
$values=array(
    'sd'=>'123',
    'fa'=>'',
    'we'=>'asd',
);

require('check.php');
header('Content-type:text/html;charset=utf-8');
$cc=Check::values($values)->rules(array(
    'sd'=>'required | numeric | between : 1,5|',
    'fa'=>'required | numeric',
    'we'=>'required | numeric',
))->check();
var_dump($cc->jsonErrors());
var_dump($cc->errors());

//var_dump(Check::values($values));
die;
//}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<form action="" method="post">
    <input type="text" name="a"/>
    <input type="text" name="a"/>
</form>
</body>
</html>
