<script language="JavaScript">
function after_load_content1()
{
	CKEDITOR.replace( 'content_1',{
 filebrowserBrowseUrl : 'ckfinder/ckfinder.html',
 filebrowserImageBrowseUrl : 'ckfinder/ckfinder.html?Type=Images',
 filebrowserFlashBrowseUrl : 'ckfinder/ckfinder.html?Type=Flash',
 filebrowserUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
 filebrowserImageUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
 filebrowserFlashUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
} );
}
function after_load_content2()
{
	CKEDITOR.replace( 'content_2',{
 filebrowserBrowseUrl : 'ckfinder/ckfinder.html',
 filebrowserImageBrowseUrl : 'ckfinder/ckfinder.html?Type=Images',
 filebrowserFlashBrowseUrl : 'ckfinder/ckfinder.html?Type=Flash',
 filebrowserUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
 filebrowserImageUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
 filebrowserFlashUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
} );
}
function load_content(obj)
{
	show_wait('div_content1');
	show_wait('div_content2');
	$('#div_content1').load("udf/ajax.php",{action:'show_editor',width:'600px',height:'250px',name:'content_1',sql:'SELECT content_1 FROM catalog WHERE id = <?=$_GET["id"]?>'},after_load_content1);
	$('#div_content2').load("udf/ajax.php",{action:'show_editor',width:'600px',height:'250px',name:'content_2',sql:'SELECT content_2 FROM catalog WHERE id = <?=$_GET["id"]?>'},after_load_content2);
	obj.onclick =  function() {SetCurrentTab(this,2); }
}
</script>
<p>
<b class="title">Контент 1:</b>
<div id="div_content1"></div></p>
<p>
<b class="title">Контент 2:</b>
<div id="div_content2"></div></p>