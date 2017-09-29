<?php
//if($_POST)
//{
$values=array(
    'sd'=>'admin@d',
    'fa'=>'',
    'we'=>'asd',
    'qf'=>'42',
);

require('MyCheck.php');
header('Content-type:text/html;charset=utf-8');
$cc=MyCheck::values($values)->rules(array(
    'sd'=>'required | numeric | between : 1,3|myok|email',
))->check();

//var_dump($cc->jsonErrors());
//var_dump($cc);
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
