<div>&nbsp;</div>
<div class="title">Содержимое страницы </div>
<textarea name="body" id="page_content" ><?=htmlspecialchars_decode($row["body"])?></textarea>
<div>&nbsp;</div>

<script language="JavaScript">
CKEDITOR.replace( 'page_content',{
 filebrowserBrowseUrl : 'ckfinder/ckfinder.html',
 filebrowserImageBrowseUrl : 'ckfinder/ckfinder.html?Type=Images',
 filebrowserFlashBrowseUrl : 'ckfinder/ckfinder.html?Type=Flash',
 filebrowserUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
 filebrowserImageUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
 filebrowserFlashUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
} );
</script>