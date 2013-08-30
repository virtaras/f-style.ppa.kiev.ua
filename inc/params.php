<?
	if(isset($tovar))
    {
        $r = $tovar;
    }
	$fields_sql = mysql_query("SELECT f.name,f.table_name,f.field_type,f.rname,title FROM `fields` as f
    LEFT JOIN categoryfields cf ON   cf.fieldid = f.id
    WHERE f.id IS NOT NULL AND ((cf.inlist = 1 AND cf.categoryid = '$r[parentid]') OR f.isgeneral = 1)  
    ORDER BY cf.showorder");
    //if(mysql_num_rows($fields_sql) > 0 )
	//{
?>
	
	
	
    <?php
     while($field = mysql_fetch_assoc($fields_sql))
	{ 
		if($field["field_type"] != 5 && ($r[$field["rname"]] == "" || $r[$field["rname"]] == "0" || $r[$field["rname"]] == "-1"))
		{
			continue;
		}
	$pcount++;
	?>
	<div class="itemProperty">	<span class="itemPropertyName"><?=$field["title"]?>:
			<span class="itemPropertyValue">
		<?php
		if(file_exists(_DIR."templates/".$field["rname"].".html"))
		{
			include(_DIR."templates/".$field["rname"].".html");
		}
		else
		{
			switch($field["field_type"])
			{
				case "1":
					echo $r[$field["rname"]]."&nbsp;";
					
					break;
				case "2":
					$fsql = mysql_query("SELECT name FROM  $field[table_name] WHERE id = '".$r[$field["rname"]]."'");
					$f = mysql_fetch_array($fsql);
					echo $f[0]."&nbsp;";
					break;
				case "3":
					echo $r[$field["rname"]]."&nbsp;";
					break;
				case "4":
					if($r[$field["rname"]] == "1")
					{
						echo "Да";
					}
					else
					{
						echo "Нет";
					}
					break;
				case "5":
					$values = array();
					$t = $field["rname"];
					$ssql = mysql_query("SELECT $t.name FROM s$t
					INNER JOIN $t ON $t.id = s$t.valueid 
					WHERE goodsid = $r[id]");
					while($s = mysql_fetch_assoc($ssql))
					{
						array_push($values,$s["name"]);
					}
					echo implode(", ",$values);
					break;	
			} 
			
		}?></span></span>
	</div>
					<?php } ?>