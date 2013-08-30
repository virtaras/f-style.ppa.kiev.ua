	<a title="Вверх" href="http://<?=get_url(0,$this->config)?>&up=<?=$row["id"]?>&position=-1&sort=showorder+ASC"><img src="images/rasc.gif" /></a>&nbsp;<a title="Вниз" href="http://<?=get_url(0,$this->config)?>&up=<?=$row["id"]?>&position=1&sort=showorder+ASC"><img src="images/rdesc.gif" /></a>
	&nbsp;&nbsp;&nbsp;
	<a title="Добавить новый подуровень" alt="Добавить новый подуровень" href="index.php?t=content&parent=<?=$row["id"]?>&id=-1"><img src="images/ac.gif" /></a>
&nbsp;&nbsp;
<a href="index.php?t=content&parent=<?=$row["id"]?>&sort=showorder+asc"><img  src="images/folders.png"  title="Перейти к подразделам" alt="Перейти к подразделам" /></a>