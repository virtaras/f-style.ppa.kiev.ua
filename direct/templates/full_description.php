<script language="JavaScript">
function after_load_content()
{
	CKEDITOR.replace( 'full_description',{
 filebrowserBrowseUrl : 'ckfinder/ckfinder.html',
 filebrowserImageBrowseUrl : 'ckfinder/ckfinder.html?Type=Images',
 filebrowserFlashBrowseUrl : 'ckfinder/ckfinder.html?Type=Flash',
 filebrowserUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
 filebrowserImageUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
 filebrowserFlashUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
} );
}
function load_full_description(obj)
{
	show_wait('div_full_description');
	$('#div_full_description').load("udf/ajax.php",{action:'show_editor',width:'700px',height:'400px',name:'full_description',sql:'SELECT full_description FROM goods WHERE id = <?=$_GET["id"]?>'},after_load_content);
	obj.onclick = function() {obj.onclick =  function() {SetCurrentTab(this,1); } }
}
</script>
<div id="div_full_description"></div>