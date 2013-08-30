<p><b>Обновление прайс-листа</b></p>
<form action="" method="post" enctype="multipart/form-data">
    <b>Выберите файл Excel (ВНИМАНИЕ! Только файлы сохраненные в формате *.xls EXCEL 97-2003г.)</b><br />
    <input type="file" name="filename" size="15" /><br /><br />
    <input type="hidden" name="update" value="OK" />
    <input type="submit" value="Загрузить" /><br />
</form>
<select id="scategory">
<option value="0">Все</option>
<?
$sql = mysql_query("SELECT * FROM catalog ORDER BY name");
while($r = mysql_fetch_assoc($sql))
{
	?>
	<option value="<?=$r["id"]?>"><?=$r["name"]?></option>
	<?
}
?>
</select>
&nbsp;<a id="excel_link" target="_blank" href="templates/excel.php" onclick="this.href = 'templates/excel.php?id=' + $('#scategory').val();">Экспорт в Excel</a>
<br /><br />
<a href="templates/asitemap.php">Экспорт в Nadavi</a>