<?php
header('Content-Type: text/html; charset=windows-1251');
session_start();
require("inc/constant.php");
require("inc/connection.php");
require("inc/global.php");
if(!isset($_SESSION["login_user"]))
{
	?>
		<script>alert('��� ����������� ������ ��� ���������� ��������� ���� !');
		window.parent.document.location.href = "<?=_SITE?>login.html";
		</script>
	<?
	exit();
}
foreach(array_keys($_GET) as $key)
{
	$_GET[$key] = str_ireplace(array("drop","delete","update","insert","select","#"),"0",$_GET[$key]);
}
foreach(array_keys($_POST) as $key)
{
	$_POST[$key] = str_ireplace(array("drop","delete","update","insert","select","#"),"0",$_POST[$key]);
}
if(isset($_GET["type"]))
{
	switch($_GET["type"])
	{
		case "info":
		$values = array();
		$varray = array();
		$fsql = mysql_query("SELECT * FROM clients_fields ORDER BY showorder");
		while($f = mysql_fetch_assoc($fsql))
		{
			$varray[] = $f["code"];
			if($f["isrequired"] == "1")
			{
				$array[] = $f["code"];
			}
		}
		
		
			foreach($varray as $current)
			{
				array_push($values,"$current = '".htmlspecialchars($_POST[$current],ENT_QUOTES)."'");
			}
			mysql_query("UPDATE clients SET 
			".implode(",",$values)."
			WHERE id = '".$_SESSION["login_user"]["id"]."'");
			$_SESSION["login_user"] = execute_row_assoc("SELECT * FROM clients WHERE id = ".$_SESSION["login_user"]["id"]);
			?>
			<script>alert('������ ������� ��������� !');</script>
			<?
			break;
		case "registration":
			if(!empty($_POST["newpassw"]))
			{
				if(strlen($_POST["newpassw"]) < 5)
				{
					?>
						<script>alert('������ ������ ������, ��� 5 �������� !');</script>
						<?
					exit();
				}
				if($_POST["newpassw"] != $_POST["renewpassw"])
				{
					?>
						<script>alert('���� ����� ������ � ��������� ������ �� ��������� !');</script>
					<?
					exit();
				}
				else
				{
					if(md5($_POST["oldpassw"]) != $_SESSION["login_user"]["passw"])
					{
						?>
						<script>alert('�� ����� �������� ������ ������ !');</script>
					<?
					exit();
					}
					else
					{
						mysql_query("UPDATE clients SET passw = '".md5($_POST["newpassw"])."' WHERE id = '".$_SESSION["login_user"]["id"]."'");
						
						$_SESSION["login_user"]["passw"] = md5($_POST["newpassw"]);
							?>
								<script>alert('������ ������� ��������� !');</script> 
							<?
							exit();
					}
				
				}
			}
			?>
								<script>alert('������ ������� ��������� !');</script>
							<?
			break;	
			
				
	}
}
?>