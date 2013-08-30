<textarea style="width:835px;height:324px;" name="<?=$this->name?>" id="<?=$this->id?>"><?=htmlspecialchars_decode($this->value)?></textarea>
<script language="JavaScript">
function after_load_content()
{
	CKEDITOR.replace( '<?=$this->name?>',{
 filebrowserBrowseUrl : 'ckfinder/ckfinder.html',
 filebrowserImageBrowseUrl : 'ckfinder/ckfinder.html?Type=Images',
 filebrowserFlashBrowseUrl : 'ckfinder/ckfinder.html?Type=Flash',
 filebrowserUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
 filebrowserImageUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
 filebrowserFlashUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
 width:"835"
} );
}<?
if(isset($this->current_row["plaintext"]) && $this->current_row["plaintext"] == 1)
{

}
else
{
?>
after_load_content();
<? } ?>
</script>