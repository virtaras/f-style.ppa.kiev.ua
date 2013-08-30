<?php
//Image functions *******************************************************************************
function load_images($parentid,$sourceid)
{
	?>
	<table width="100%">
	<?
	$images = mysql_query("SELECT * FROM images WHERE parentid = '$parentid' AND source = '$sourceid' ORDER BY showorder ASC ");
	$imcount = 0;
	while($im = mysql_fetch_assoc($images))
	{
		$imcount++;
		?>
			<tr>
				<td>
					<table width="100%">
						<tr>
							<td width="40">
							<input class="cb" onclick="javascript:return main_im(<?=$im["id"]?>)" title="Сделать изображение главным" type="radio" <?=($im["is_main"] == 1 ? "checked='true'" : "")?>  name="is_main_item" />
							</td>
							<td width="80">
								<input type="checkbox" class="cb" value="<?=$im["id"]?>" name="im[]" />
								&nbsp;<img onclick="set_position(-1,<?=$im["id"]?>)" src="images/rasc.gif" />
								&nbsp;<img onclick="set_position(1,<?=$im["id"]?>)" src="images/rdesc.gif" />
							</td>
							<td width="85" style="text-align:center;">
								<?php if($im["image"] != "") { ?>
									<img <?=($im["is_main"] == 1 ? "style='border:1px solid red;'" : "")?> src="../thumb.php?id=<?=$im["id"]?>&k=80" />
									<?php } ?>
							</td>
							<td style="text-align:center">
								<textarea rows="3" style="width:100%;font-size:11px;" name="t_<?=$im["id"]?>"><?=$im["title"]?></textarea>		
							</td>	
								<td style="text-align:center">
								<textarea rows="3" style="width:100%;font-size:11px;" name="tl_<?=$im["id"]?>"><?=$im["link"]?></textarea>		
							</td>	
						</tr>
					</table>
				</td>
			</tr>
		<?
	}
	?>
	</table>
	<?php if($imcount > 0) { ?>
	<script language="JavaScript">
	
	function main_im(imid)
	{
		if(confirm("Сделать изображение главным ?"))
		{
			show_wait("images_area");
			$("#images_area").load("templates/img.php",{set_main:true,id:imid,source:<?=$sourceid?>,parent:<?=$parentid?>});
		}
		else
		{
			return false;
		}
	}
	function set_position(pos,id)
	{
		show_wait("images_area");
		$("#images_area").load("templates/img.php",{up:id,position:pos,source:<?=$sourceid?>,parent:<?=$parentid?>});
	}
	</script>
	
	<? } 
}
if(isset($_POST["load_images"]))
{
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require("../config/global.php");
	require("../lib/user.php");
	require("../function/connection.php");
	require("../function/db.php");
	require("../function/global.php");
	load_images($_POST["parent"],$_POST["source"]);	
}
else if(isset($_GET["save_title"]))
{
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require("../config/global.php");
	require("../lib/user.php");
	require("../function/connection.php");
	require("../function/db.php");
	require("../function/global.php");
	foreach(array_keys($_POST) as $current)
	{
		if(substr($current,0,2) == "t_")
		{
			mysql_query("UPDATE images SET title = '".$_POST[$current]."' WHERE id = '".substr($current,2)."'");
		}
		
		if(substr($current,0,3) == "tl_")
		{
			mysql_query("UPDATE images SET link = '".$_POST[$current]."' WHERE id = '".substr($current,3)."'");
		}
	}
}
else if(isset($_POST["set_main"]))
{
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require("../config/global.php");
	require("../lib/user.php");
	require("../function/connection.php");
	require("../function/db.php");
	require("../function/global.php");
	mysql_query("UPDATE images SET is_main = '0' WHERE parentid = '".$_POST["parent"]."' AND source = '".$_POST["source"]."'");
	mysql_query("UPDATE images SET is_main = '1' WHERE id = '".$_POST["id"]."'");
	load_images($_POST["parent"],$_POST["source"]);	
}
else if(isset($_GET["rm"]))
{
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require("../config/global.php");
	require("../lib/user.php");
	require("../function/connection.php");
	require("../function/db.php");
	require("../function/global.php");
	$im = explode(",",$_GET["id"]);
	$uploaddir = "../../images/files/";
	if(count($im) > 0)
	{
		foreach($im as $current)
		{
			$im_sql = db_query("SELECT image FROM images WHERE id = '".$current."'");
			$im_row = db_fetch_assoc($im_sql);
			if($im_row["image"] != "")
			{
				unlink($uploaddir."$im_row[image]");
			}
			mysql_query("DELETE FROM images WHERE id = '".$current."'");
		}
		
	}
	?>
		<script language="JavaScript">
		window.parent.load_images();
		</script>
		<?
}
else if(isset($_POST["up"]))
{
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require("../config/global.php");
	require("../lib/user.php");
	require("../function/connection.php");
	require("../function/db.php");
	require("../function/global.php");
	$parent = $_POST["parent"];
	$sourceid = $_POST["source"];
	$count_item = execute_scalar("SELECT count(*) AS count_item FROM images WHERE parentid = '$parent' AND source = '$sourceid'");
	$current = db_get_row("SELECT id,showorder FROM images WHERE id = '$_POST[up]'");
	if(($current["showorder"] == 1 && $_POST["position"] == "-1") || ($current["showorder"] == $count_item && $_POST["position"] == "1"))
	{
		
	}
	else
		{
			$old_position = $current["showorder"];
			$new_position = $current["showorder"] + $_POST["position"];
			//echo $old_position." ".$new_position;
			$old = db_get_field("SELECT id FROM images WHERE parentid = '$parent' AND showorder = '$new_position' AND source = '$sourceid'","id");
			
			mysql_query("UPDATE images SET showorder = '$old_position' WHERE id = '$old'");
			mysql_query("UPDATE images SET showorder = '$new_position' WHERE id = '$_POST[up]'");
		}
load_images($parent,$sourceid);
}
else if(isset($_GET["upload"]))
{
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require("../config/global.php");
	require("../lib/user.php");
	require("../function/connection.php");
	require("../function/db.php");
	require("../function/global.php");
	$uploaddir = "../../images/files/";
	if(count($_FILES) > 0) 
	{
		$imindex = 0;
		foreach(array_keys($_FILES) as $key)
		{
			$sourceid = $_GET["source"];
			$parent = $_GET["parent"];
			$count_img = db_query("SELECT COUNT(*) as imgs FROM images WHERE parentid = '$parent' AND source = '$sourceid' ");
			$count_img_res = db_fetch_assoc($count_img);
			$imindex++;
			$size = getimagesize($_FILES[$key]["tmp_name"]);
				$width = $size[0];
				$height = $size[1];
				$format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
			$_FILES[$key]['name'] = $sourceid."_".$parent."_".date("dmYHis")."_$imindex.$format";
			$uploadfile = $uploaddir.basename($_FILES[$key]['name']);
			move_uploaded_file($_FILES[$key]["tmp_name"],$uploadfile);
			
			$is_main = 0;
			if($count_img_res["imgs"] == 0)
			{
				$is_main = 1;
			}
			if(file_exists($uploadfile))
			{
				$max = execute_scalar("SELECT count(*) FROM images WHERE parentid = '".$parent."' AND source = '$sourceid'");
				
				
				db_query("INSERT INTO images (parentid,image,is_main,source,showorder,width,height,format) VALUES ('".$parent."','".($_FILES[$key]['name'])."','$is_main','$sourceid','".($max + 1)."','$width','$height','$format')");	
			}
		}
		?>
		<script language="JavaScript">
		window.parent.load_images();
		</script>
		<?
	}	
}
else {
//Image functions  *******************************************************************************
if(!isset($sourceid))
{
	global $sourceid;
}
?>
<p >
<fieldset style="padding-left:5px;padding-right:5px;"><legend>Изображения</legend>
<script language="JavaScript">
function show_file(obj)
{
	var i;
	var html = "";
	for(i = 1;i < obj.options[obj.selectedIndex].value;i++)
	{
		html = html + "<div><input type='file' name='goods_img"+(i+1)+"' /></div>";
	}
	document.getElementById("images").innerHTML = html;
}
function upload_images()
{
	show_wait("images_area");
	document.forms[0].action = "templates/img.php?upload&source=<?=$sourceid?>&parent=<?=$_GET["id"]?>";
	document.forms[0].target = "img_frame";
	document.forms[0].submit();
	document.forms[0].target = "";
	
}
function load_images()
{
	show_wait("images_area");
	$("#images_area").load('templates/img.php',{load_images:true,source:<?=$sourceid?>,parent:<?=$_GET["id"]?>})
	$("#images").empty();
	document.getElementById("goods_img1").value = "";
	$("#images_buttons").show();
}
function rm_im()
{
	if(confirm('Удалить выбранные изображения ?'))
	{
		var images = document.getElementsByName("im[]");
		var rm_images = "0";
		var ind = 0;
		for(var i = 0;i < images.length;i++)
		{
			if(images[i].checked)
			{
				rm_images += "," + images[i].value;
				ind++;
			}	
		}
		if(rm_images != "0")
		{
			show_wait("images_area");
			document.forms[0].action = "templates/img.php?rm&source=<?=$sourceid?>&parent=<?=$_GET["id"]?>&id="+rm_images;
			document.forms[0].target = "img_frame";
			document.forms[0].submit();
			document.forms[0].target = "";
		}	
	}
}
function save_title()
{
	document.forms[0].action = "templates/img.php?save_title";
	document.forms[0].target = "img_frame";
	document.forms[0].submit();
	document.forms[0].target = "";
}
function check_all()
{
	var images = document.getElementsByName("im[]");
	for(var i = 0;i < images.length;i++)
	{
		images[i].checked = true;
	}
}
function uncheck_all()
{
	var images = document.getElementsByName("im[]");
	for(var i = 0;i < images.length;i++)
	{
		images[i].checked = false;
	}
}
</script>
<iframe name="img_frame" style="display:none;"></iframe>
<table>
	<tr>
		<td class="title">Загрузить файл</td>
		<td><input style="width:200px;" type="file" id="goods_img1" name="goods_img1" />&nbsp;<select onchange="show_file(this)">
		<?php
		for($i = 1;$i < 21;$i++)
		{
			?>
			<option value="<?=$i?>"><?=$i?></option>
			<?
		}
		?>
		</select>
		<div id="images"></div>
		</td>
		<td><input onclick="upload_images()" type="button" style="width:100px;" class="buttons" value="Загрузить" /></td>
	</tr>
</table>

<a href="javascript: check_all()" >Отметить все</a>&nbsp;&nbsp;<a href="javascript:uncheck_all() " onclick="uncheck_all()">Снять ометки</a>
<div id="images_area" style="height:400px;overflow:auto;margin-top:5px;margin-bottom:5px;border:1px solid #C7D9E2;">

</div>
<div id="images_buttons" style="display:none;">
	<input title="Сохранить описания" type="button" class="buttons" onclick="save_title()" value="Сохранить описания" />&nbsp;<input title="Удалить выбранное изображение" type="button" class="buttons" onclick="rm_im()" value="Удалить" />
	</div>
</fieldset>
<script language="JavaScript">
load_images();
</script>
</p>
<? } ?>