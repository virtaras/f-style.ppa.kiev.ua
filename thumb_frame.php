<?php
if(!empty($_GET["id"]))
{
	$sufix = isset($_GET["sufix"]) ? $_GET["sufix"] : "";
	//$standart = isset($_GET["k"]) ? $_GET["k"] : 0;
	$fwidth=isset($_GET["fwidth"]) ? $_GET["fwidth"] : 0;
	$fheight=isset($_GET["fheight"]) ? $_GET["fheight"] : 0;
	$extarr = array("png","gif","jpg");
	$exist = false;
	$table=$_GET["table"];
	foreach($extarr as $format)
	{
		if(file_exists("$table/thumbs/$_GET[id]_$fwidth_$fheight".$sufix.".$format") )
		{
			header("Content-type: image/$format");
			$icfunc = "imagecreatefrom".$format;
			$res = $icfunc("$table/thumbs/$_GET[id]_$fwidth_$fheight".$sufix.".$format");
			if($format == "png")
			{
				imagealphablending($res , false);
				imagesavealpha($res , true);

			}
		
			$icfunc = "image".$format;
			$icfunc($res);
			imagedestroy($res);
			$exist = true;
		}
	
	
	}
	if(!$exist)
	{
		require("inc/constant.php");
		require("inc/connection.php");
		
		$image = mysql_query("SELECT image".$sufix.",width".$sufix.",height".$sufix.",format".$sufix." FROM $table WHERE id = '$_GET[id]'");
		$im = mysql_fetch_assoc($image);
		$src = _DIR."$table/files/".$im["image".$sufix.""];
		$width = $im["width".$sufix];
		$height = $im["height".$sufix];
		$format = $im["format".$sufix];
		header("Content-type: image/$format");
		$icfunc = "imagecreatefrom" . $format;
		if (!function_exists($icfunc)) return false;
		$source = $icfunc($src);  
		if(!$source)
		{
			return false;
		}	
		
		//watermarked
		/*if(file_exists(_TEMPLATE.'$table/watermark.png'))
		{
			$watermark = imagecreatefrompng(_TEMPLATE.'$table/watermark.png');
			$watermark_width = imagesx($watermark);  
			$watermark_height = imagesy($watermark); 
			if($watermark_width >= $width)
			{
				$old_watermark_width = $watermark_width;
				$old_watermark_height = $watermark_height;
				
				$watermark_width = $width - ($width*0.2);
				
				$k = $watermark_width/$old_watermark_width;
				$watermark_height = $watermark_height*$k;
				
				imagealphablending($watermark, false);
				imagesavealpha($watermark, true);
				
				$newImg = imagecreatetruecolor($watermark_width, $watermark_height);
				imagealphablending($newImg, false);
				imagesavealpha($newImg,true);
				$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
				imagefilledrectangle($newImg, 0, 0, $watermark_width, $watermark_height, $transparent);
				imagecopyresampled($newImg, $watermark, 0, 0, 0, 0, $watermark_width, $watermark_height,$old_watermark_width,$old_watermark_height);
				$watermark = $newImg;
			}
			
			$dest_x = ($width - $watermark_width)/2;  
			$dest_y = ($height - $watermark_height)/1.3; 
			imagecopy($source, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);  
		}*/
		//watermarked
		$newwidth = $width;
		$newheight = $height;
		if($fwidth>0 && $fheight>0)
		{
			if($width/$fwidth < $height/$fheight)
			{
					
					if($fwidth > $width)
					{
						$newwidth = $width;
					}
					else
					{
						$k = $fwidth/$width;
						$newwidth = $fwidth;
						$newheight = $height*$k;
						if($newheight > $height)
						{
							$newheight = $height;
						}
					}
					
			}
			else
			{
					if($fheight > $height)
					{
						$newheight = $height;
					}
					else
					{
						$k = $fheight/$height;
						$newheight = $fheight;
						$newwidth = $width*$k;
						if($newwidth > $width)
						{
							$newwidth = $width;
						}
					}
			}
		}
		$target = imagecreatetruecolor($fwidth, $fheight);
		
		if ( ($format  == "gif") || ($format  == "png") ) {
		$trnprt_indx = imagecolortransparent($source);

		// If we have a specific transparent color
		if ($trnprt_indx >= 0) {

		// Get the original image's transparent color's RGB values
		$trnprt_color    = imagecolorsforindex($source, $trnprt_indx);

		// Allocate the same color in the new image resource
		$trnprt_indx    = imagecolorallocate($target, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);

		// Completely fill the background of the new image with allocated color.
		imagefill($target, 0, 0, $trnprt_indx);

		// Set the background color for new image to transparent
		imagecolortransparent($target, $trnprt_indx);


		}
		// Always make a transparent background color for PNGs that don't have one allocated already
		elseif ($format  == "png") {

		// Turn off transparency blending (temporarily)
		imagealphablending($target, false);

		// Create a new transparent color for image
		$color = imagecolorallocatealpha($target, 0, 0, 0, 127);

		// Completely fill the background of the new image with allocated color.
		imagefill($target, 0, 0, $color);

		// Restore transparency blending
		imagesavealpha($target, true);
		}
		}
		
		//$x=0;$y=0;
		$x=0 - ($newwidth - $fwidth) / 2; // Center the image horizontally
		$y=0 - ($newheight - $fheight) / 2; // Center the image vertically
		
		imagecopyresampled(
	    $target,  // Идентификатор нового изображения
	    $source,  // Идентификатор исходного изображения
	    $x,$y,      // Координаты (x,y) верхнего левого угла
	              // в новом изображении
	    0,0,       // Координаты (x,y) верхнего левого угла копируемого
	              // блока существующего изображения
	    $newwidth,     // Новая ширина копируемого блока
	    $newheight,     // Новая высота копируемого блока
	    $width, // Ширина исходного копируемого блока
	    $height  // Высота исходного копируемого блока
	    );
		$icfunc = "image".$format;
		$icfunc($target,"$table/thumbs/$_GET[id]_$standart.$format");  
		$icfunc($target);  

	  imagedestroy($target);
	  imagedestroy($source);
  }
}

function resize($file_input, $file_output, $w_o, $h_o, $percent = false) {
	list($w_i, $h_i, $type) = getimagesize($file_input);
	if (!$w_i || !$h_i) {
		echo 'Невозможно получить длину и ширину изображения';
		return;
    }
    $types = array('','gif','jpeg','png');
    $ext = $types[$type];
    if ($ext) {
    	$func = 'imagecreatefrom'.$ext;
    	$img = $func($file_input);
    } else {
    	echo 'Некорректный формат файла';
		return;
    }
	if ($percent) {
		$w_o *= $w_i / 100;
		$h_o *= $h_i / 100;
	}
	if (!$h_o) $h_o = $w_o/($w_i/$h_i);
	if (!$w_o) $w_o = $h_o/($h_i/$w_i);
	$img_o = imagecreatetruecolor($w_o, $h_o);
	imagecopyresampled($img_o, $img, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i);
	if ($type == 2) {
		return imagejpeg($img_o,$file_output,100);
	} else {
		$func = 'image'.$ext;
		return $func($img_o,$file_output);
	}
}

function crop($file_input, $file_output, $crop = 'square',$percent = false) {
	list($w_i, $h_i, $type) = getimagesize($file_input);
	if (!$w_i || !$h_i) {
		echo 'Невозможно получить длину и ширину изображения';
		return;
    }
    $types = array('','gif','jpeg','png');
    $ext = $types[$type];
    if ($ext) {
    	$func = 'imagecreatefrom'.$ext;
    	$img = $func($file_input);
    } else {
    	echo 'Некорректный формат файла';
		return;
    }
	if ($crop == 'square') {
		$min = $w_i;
		if ($w_i > $h_i) $min = $h_i;
		$w_o = $h_o = $min;
	} else {
		list($x_o, $y_o, $w_o, $h_o) = $crop;
		if ($percent) {
			$w_o *= $w_i / 100;
			$h_o *= $h_i / 100;
			$x_o *= $w_i / 100;
			$y_o *= $h_i / 100;
		}
    	if ($w_o < 0) $w_o += $w_i;
	    $w_o -= $x_o;
	   	if ($h_o < 0) $h_o += $h_i;
		$h_o -= $y_o;
	}
	$img_o = imagecreatetruecolor($w_o, $h_o);
	imagecopy($img_o, $img, 0, 0, $x_o, $y_o, $w_o, $h_o);
	if ($type == 2) {
		return imagejpeg($img_o,$file_output,100);
	} else {
		$func = 'image'.$ext;
		return $func($img_o,$file_output);
	}
}

?>