<?php
global $content_type;

if(setting("sort_visible") == "1")
{
?>
<div class="sort">
<label>Сортировать:</label>&nbsp;
<?

	if($content_type == "asearch")
	{
		if(setting("sort_name_visible") == "1") 
		{
		?>
		Наименование&nbsp;(<a <?=(isset($_GET["sort"]) && $_GET["sort"] == "00" ? "class='asort'" : "")?> onclick="set_sort('00')" href="javascript:void(0)">А-Я</a>&nbsp;&nbsp;<a <?=(isset($_GET["sort"]) && $_GET["sort"] == "01" ? "class='asort'" : "")?> onclick="set_sort('01')" href="javascript:void(0)">Я-А</a>)&nbsp;&nbsp;<? } ?>Цена&nbsp;(<a <?=(isset($_GET["sort"]) && $_GET["sort"] == "10" ? "class='asort'" : "")?> onclick="set_sort('10')" href="javascript:void(0)">0-9</a>&nbsp;&nbsp;<a <?=(isset($_GET["sort"]) && $_GET["sort"] == "11" ? "class='asort'" : "")?> onclick="set_sort('11')" href="javascript:void(0)">9-0</a>)
		<?
	}
	else
	{
		if(isset($search)) 
		{ 
			if(setting("sort_name_visible") == "1") 
			{
			?>
			Наименование&nbsp;(<a <?=(isset($_GET["sort"]) && $_GET["sort"] == "00" ? "class='asort'" : "")?> onclick="set_sort('00')" href="javascript:void(0)">А-Я</a>&nbsp;&nbsp;<a <?=(isset($_GET["sort"]) && $_GET["sort"] == "01" ? "class='asort'" : "")?> onclick="set_sort('01')" href="javascript:void(0)">Я-А</a>)&nbsp;&nbsp;<? } ?>Цена&nbsp;(<a <?=(isset($_GET["sort"]) && $_GET["sort"] == "10" ? "class='asort'" : "")?> onclick="set_sort('10')" href="javascript:void(0)">0-9</a>&nbsp;&nbsp;<a <?=(isset($_GET["sort"]) && $_GET["sort"] == "11" ? "class='asort'" : "")?> onclick="set_sort('11')" href="javascript:void(0)">9-0</a>)

				<?
		}
		else
		{
			if(setting("sort_name_visible") == "1") {
			?>
			Наименование&nbsp;(<a <?=(isset($_GET["sort"]) && $_GET["sort"] == "00" ? "class='asort'" : "")?>  onclick="set_sort('00')" href="javascript:void(0)">А-Я</a>&nbsp;&nbsp;<a <?=(isset($_GET["sort"]) && $_GET["sort"] == "01" ? "class='asort'" : "")?> onclick="set_sort('01')" href="javascript:void(0)">Я-А</a>)&nbsp;&nbsp;<? } ?>Цена&nbsp;(<a <?=(isset($_GET["sort"]) && $_GET["sort"] == "10" ? "class='asort'" : "")?> onclick="set_sort('10')" href="javascript:void(0)">0-9</a>&nbsp;&nbsp;<a <?=(isset($_GET["sort"]) && $_GET["sort"] == "11" ? "class='asort'" : "")?> onclick="set_sort('11')" href="javascript:void(0)">9-0</a>)
			<?
		}
		
	}	
?>
</div>
<? } ?>