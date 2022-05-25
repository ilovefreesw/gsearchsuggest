<html>
<head>
<style>
ul{
        display:inline-block;
        border:1px solid #000;
        padding:20px;
}
</style>
</head>
<body>
<center>
<form action="index.php" method="get">
<b>Enter Keyword or a Pharse</b>:  <input type="text" name="key"/>
</br>
</br>
</br>
<input type="submit" value="Show Suggestions"/>
</br>
</form>
</center>
</body>

</html>
<?php
include 'Suggest.php';
if(isset($_GET['key']))
{

$suggest = new Suggest($_GET['key'], 'en');
echo '<center><ul style="list-style-type:none">';
foreach ($suggest->data as $item) {
echo '<li>'.$item.'</li>';
}
echo '</ul></center>';
}
?>