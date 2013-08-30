<? if(setting("filters_visible") == "1") { ?>
<form method="post" action="<?=_SITE?>search.php?id=<?=(isset($search) ? $search[0] : $_GET["id"])?>">
<div class="filters">
<div class="filters_left">
<div class="filters_top">
<div class="filters_right">
<div class="filters_bottom">
<div class="filters_left_top">
<div class="filters_right_top">
<div class="filters_right_bottom">
<div class="filters_left_bottom">

<?
				$categoryid = "";
				$child_array = array(isset($search) ? $search[0] : $_GET["id"]);
				get_child_id(isset($search) ? $search[0] : $_GET["id"],&$child_array);
				$categoryid = implode(",",$child_array);
				global $catalog_array;

				if(setting("brands_filter_visible") == "1")
				{
					$sql_brand = mysql_query("SELECT brand FROM goods WHERE parentid IN($categoryid)");
					$brand_array = array();
					while($r = mysql_fetch_assoc($sql_brand))
					{
						array_push($brand_array,$r["brand"]);
					}
					if(count($brand_array) > 0) {
					?>
						<table class="filters_brands" cellspacing="0" cellpadding="0" border="0" ><tr>
						<td  class="filters_brands_title" width="100">Производитель:</td>
						<td class="filters_brands_items">
						<table cellspacing="0" cellpadding="1" border="0" class="filters_brands_items_table"><tr>
							<td class="filters_brand_all">
								<input onclick="uncheck_by_name('brand[]')" type="checkbox" id='all_brand' value="0" <?=(!isset($search) || (isset($search) && $search[3] == "0") ? "CHECKED" : "")?> class="cb" />&nbsp;Все&nbsp;&nbsp;
							</td>
					<?
						$sql = mysql_query("SELECT * FROM brands WHERE id IN (".implode(",",$brand_array).") ORDER BY name ");
						$ind = 1;
						while($r = mysql_fetch_assoc($sql))
						{
							$ind++;
							if($ind == 1)
							{
								echo "<tr>";
							}
							
							?>
							<td class="filters_brand">
	<input onclick="$('#all_brand').attr('checked',false);" type="checkbox" <?=(isset($search) ? (in_array($r["id"],explode(",",$search[3])) ? "CHECKED" : "") : "")?> class="cb" name="brand[]" value="<?=$r["id"]?>" />&nbsp;<?=$r["name"]?> &nbsp;&nbsp;</td>
							<?
							if($ind == 5)
							{
								echo "</tr>";
							}
						}
						if($ind < 5  && $ind > 0)
						{
							for($i = 0;$i < (5-$ind);$i++)
							{
								?>
								<td>&nbsp;</td>
								<?
							}
							?>
							</tr>
							<?
						}
						?>
						</table>
						</td>
				   </tr></table>
						<? }
				}
				else
				{
					?>
					<input type="hidden" value="0" name="brand[]" />
					<?
				}
				// Filters params
				$fields_sql = mysql_query("SELECT f.*,cf.hide_default FROM `fields` as f
    LEFT JOIN categoryfields cf ON   cf.fieldid = f.id
    WHERE f.id IS NOT NULL  AND f.search = 1 AND (cf.categoryid = ".(isset($search) ? $search[0] : $_GET["id"])." OR f.isgeneral = 1)  AND f.field_type != 5
    ORDER BY cf.hide_default ASC,cf.showorder");
	if(mysql_num_rows($fields_sql) > 0) {
	$filters_result_count = 0;
				?>
				
			<table class="filters_params" cellspacing="0" cellpadding="0" border="0" ><tr>
				<td class="filters_params_title" width="100">Параметры:</td>
				<td class="filters_params_items">
					<?
					$i = 4;
					$is_hide = 1;
					while($field = mysql_fetch_assoc($fields_sql))
					{
						if($field["hide_default"] == "1" && $is_hide == 1)
						{
							$is_hide++;
							?>
							<div style="clear:both;"></div>
							<div id="show_hide_filters"><a href="javascript:void(0);" onclick="$('#hide_filters').slideDown();">Дополнительно</a></div>
							<div style="clear:both;"></div> 
							<div id="hide_filters" style="display:none;">
							<?
						}
						switch($field["field_type"])
						{
							case "2":
								$current_sql = mysql_query("SELECT $field[table_name].id,$field[table_name].name FROM $field[table_name] 
					INNER JOIN goods ON goods.$field[rname] =  $field[table_name].id 
					WHERE goods.id IS NOT NULL AND goods.exist_type != 2 ".($categoryid != "" ? " AND goods.parentid IN ($categoryid) " : "")."
					GROUP BY $field[table_name].id,$field[table_name].name
					ORDER BY $field[table_name].name");
					$count_items = mysql_num_rows($current_sql);
					if($count_items > 1)
					{
						if($field["showtype"] == "1")
						{
							?>
							<div class="filters_params_item">
								<div class="filters_params_name"><?=$field["title"]?>:</div>
								<div class="filters_params_input"><input type="hidden" value="0" name="<?=$field["rname"]?>" />
								<a href="javascript:void(0);">Выбрать</a></div>
							</div>
							<?
						}
						else if($field["showtype"] == "2")
						{ ?>
							<div class="filters_params_item">
								<div class="filters_params_name"><?=$field["title"]?>:</div>
								<div class="filters_params_input_select">
									<select name="<?=$field["rname"]?>" >
										<option value="0">Все</option>
										<? while($f = mysql_fetch_assoc($current_sql))
										{ ?>
											<option <?=(isset($search) && $search[$i] == $f["id"] ? " SELECTED " : "")?> value="<?=$f["id"]?>"><?=$f["name"]?></option>
										<? } ?>
									</select> 
								</div>
							</div>
						<? }
						
					}
						break;
						case "4": ?>
							<div class="filters_params_item">
								<div class="filters_params_input_checkbox">
									<input <?=(isset($search) && $search[$i] == 1 ? " CHECKED " : "")?> type="checkbox" name="<?=$field["rname"]?>"   value="" /> 
								</div>
								<div class="filters_params_name"><?=$field["title"]?></div>
							</div>
							<?
							break;
								
						}
						
						$i++;
					}
					if($is_hide > 1)
					{
						?>
						</div>
						<?
					}
					?>	
				</td>
</tr>				
				</table>
				<script language="JavaScript" type="text/javascript">
				<?
					if($filters_result_count == 0)
					{
						?>
						$("#filters_params").hide();
						<?
					}
				?>				
				</script>
			<?	
			}
			// End of Filters Params
				if(setting("price_filter_visible") == "1") {
			?>
<table class="filters_price" border="0" id="filters" cellpadding="0" cellspacing="0"><tr>
				<td class="filters_price_title"width="100">Цена:</td>
				<td class="filters_price_input_td">
					<input name="price_start" onfocus="set_input_text(this,'от',0)" onblur="set_input_text(this,'от',1)" type="text" value="<?=(isset($search) && $search[1] != 0 ? $search[1] : "от")?>"/>
					<input name="price_finish" onfocus="set_input_text(this,'до',0)" value="<?=(isset($search) &&  $search[2] != 0 ? $search[2] : "до")?>" onblur="set_input_text(this,'до',1)" type="text"/>
				</td>
				<td class="filters_btn">
					<input type="submit" value="Поиск" class="submit_naiti" />
				</td>
			</tr></table>	<? } else { ?><input name="price_start" type="hidden" value="0" /><input name="price_finish" type="hidden" value="0" />	<p>	<input type="submit" value="Поиск" /></p><? } ?>
			<table class="filters_catalog_mode" border="0" cellspacing="0" cellpadding="0"><tr>
				<td class="filters_catalog_mode_title">Отображение:</td>
				<td class="filters_catalog_mode_links">
					<a <?=(!isset($_SESSION["mode_"]) || (isset($_SESSION["mode_"]) && $_SESSION["mode_"] == "1") ? "class='asort'" : "")?> onclick="set_mode(<?=$current_category?>,1)" href="javascript:void(0)">Блоками</a>&nbsp;&nbsp;<a  <?=(isset($_SESSION["mode_"]) && $_SESSION["mode_"] == "2" ? "class='asort'" : "")?> onclick="set_mode(<?=$current_category?>,2)" href="javascript:void(0)">Списком</a>
				</td> 
				<?
				$show_size = explode(",",setting("pagesize_array"));
				if(count($show_size) > 0)
				{
				?>
				</tr>
				<tr>
				<td class="filters_catalog_pagesize_title">Показывать по:</td>
				<td  class="page_size">
                <a <?=($pagesize == 10000 ? "class='asort'" :"")?>  onclick="set_page_size(10000);" href="javascript:void(0)">Все</a>
                <?
                foreach($show_size as $key)
                {
                    ?>
                    &nbsp;<a href="javascript:void(0)" <?=($pagesize == $key ? "class='asort'" :"")?> onclick="set_page_size(<?=$key?>);"><?=$key?></a>
                    <?
                }
                ?>
            </td>
			<? } ?>
			</tr></table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</form><? } ?>