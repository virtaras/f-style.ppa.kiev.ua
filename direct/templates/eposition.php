	<a title="Вверх" href="http://<?=get_url(0,$this->config)?>&up=<?=$row["id"]?>&position=-1&sort=showorder+ASC"><img src="images/rasc.gif" /></a>&nbsp;<a title="Вниз" href="http://<?=get_url(0,$this->config)?>&up=<?=$row["id"]?>&position=1&sort=showorder+ASC"><img src="images/rdesc.gif" /></a>
	&nbsp;&nbsp;&nbsp;
	<a title="Добавить подгруппу" alt="Добавить подгруппу" href="index.php?t=catalog&parent=<?=$row["id"]?>&id=-1"><img src="images/ac.gif" /></a>
&nbsp;&nbsp;
<a href="index.php?t=catalog&parent=<?=$row["id"]?>&sort=showorder+asc"><img  src="images/folders.png"  title="Перейти к подгруппам" alt="Перейти к подгруппам" /></a>
&nbsp;&nbsp;
<a href="index.php?t=goods&parent=<?=$row["id"]?>"><img src="images/goods.png" alt="Перейти к товарам" title="Перейти к товарам" /></a>