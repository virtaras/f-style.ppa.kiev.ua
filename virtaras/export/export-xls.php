<?php
//session_start();
require($_SERVER["DOCUMENT_ROOT"]."//inc/constant.php");
require($_SERVER["DOCUMENT_ROOT"]."//inc/connection.php");
require($_SERVER["DOCUMENT_ROOT"]."//inc/global.php");
require($_SERVER["DOCUMENT_ROOT"]."//inc/emarket.php");
mysql_query("SET NAMES windows-1251");
if (isset($_GET['t'])){
	$table=mysql_real_escape_string(trim($_GET['t']));

	$select = "SELECT * FROM $table";

	$export = mysql_query ( $select ) or die ( "Sql error : " . mysql_error( ) );

	$fields = mysql_num_fields ( $export );
	header('Content-Type: text/html; charset=windows-1251');
	header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
	header('Expires: 0');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', FALSE);
	header('Pragma: no-cache');
	header('Content-transfer-encoding: binary');
	header('Content-Disposition: attachment; filename='.$table.'.xls');
	header('Content-Type: application/x-unknown');?>
	<table border="1">
	<tr><?
	for ( $i = 0; $i < $fields; $i++ )
	{?>
		<td>
		<?
		$header = mysql_field_name( $export , $i ) . "\t";?>
		<?=htmlentities(iconv("utf-8", "windows-1251", $header),ENT_QUOTES, "cp1251");?>
		</td>
	<?}?>
	
	</tr>

	<?while( $row = mysql_fetch_row( $export ) )
	{
		$line = '';
		?><tr><?
		foreach( $row as $value )
		{?>
			<td>
			<?if ( ( !isset( $value ) ) || ( $value == "" ) )
			{
				$value = "\t";
			}
			else
			{
				$value = str_replace( '"' , '""' , $value );
				$value = '"' . $value . '"' . "\t";
			}
			$line .= $value;
			echo htmlentities(iconv("utf-8", "windows-1251", $value),ENT_QUOTES, "cp1251");?>
			</td>
		<?}?>
		</tr>
		<?$data .= trim( $line ) . "\n";
	}
	//$data = iconv("windows-1251","utf-8",str_replace( "\r" , "" , $data ));
	$data = str_replace( "\r" , "" , $data );

	if ( $data == "" )
	{
		$data = "\n(0) Records Found!\n";                        
	}
	?>
	</table>
	
<?}?>