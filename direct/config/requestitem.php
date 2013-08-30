<?php
$title = "Содержание запроса";
$source = "SELECT ri.id,request.title,ri.val,request.fieldtype FROM requestitem as ri 
INNER JOIN request ON request.id = ri.fieldid
WHERE ri.parentid = '$_GET[parent]'";
$title_fields["val"] = "Значение";
$title_fields["title"] = "Поле";
$edit_buttons = array(false,true);//array enable/disable standart edit button in row - array(<edit>,<delete>)
$top_links = array(false,false,true);//array enable/disable standart  top links - array(<add>,<copy>,<delete>);
$eval_fields = array("val"=>"if( \$row['fieldtype'] == '6' ) {echo '<a href=\"../formfiles/'.\$row['id'].'/'.\$row['val'].'\">'.\$row['val'].'</a>';} else {echo \$row['val'];}");
$exclude_fields = array("id","fieldtype");
?>