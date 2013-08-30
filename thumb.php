<?php
if(!empty($_GET["id"]))
{
	$standart = isset($_GET["k"]) ? $_GET["k"] : 0;
	$extarr = array("png","gif","jpg");
	$exist = false;
	foreach($extarr as $format)
	{
		if(file_exists("images/thumbs/$_GET[id]_$standart.$format") )
		{
			header("Content-type: image/$format");
			$icfunc = "imagecreatefrom".$format;
			$res = $icfunc("images/thumbs/$_GET[id]_$standart.$format");
			if($format == "png")
			{
				imagealphablending($res , false);
				imagesavealpha($res , true);

			}
		
			if($format == "jpg")
			{
				imagejpeg($res,NULL,100);
			}
			else
			{
				$icfunc = "image".$format;
				$icfunc($res);
			}
			imagedestroy($res);
			$exist = true;
		}
	
	
	}
	if(!$exist)
	{
		require("inc/constant.php");
		require("inc/connection.php");
		
		$image = mysql_query("SELECT image,width,height,format FROM images WHERE id = '$_GET[id]'");
		$im = mysql_fetch_assoc($image);
		$src = _DIR."images/files/$im[image]";
		$width = $im["width"];
		$height = $im["height"];
		$format = $im["format"];
		header("Content-type: image/$format");
		$icfunc = "imagecreatefrom" . $format;
		if (!function_exists($icfunc)) return false;
		$source = $icfunc($src);  
		if(!$source)
		{
			return false;
		}	
		
		//watermarked
		if(file_exists(_TEMPLATE.'images/watermark.png'))
		{
			$watermark = imagecreatefrompng(_TEMPLATE.'images/watermark.png');
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
		}
		//watermarked
		$newwidth = $width;
		$newheight = $height;
		if($standart > 0)
		{
			if(isset($_GET["h"]))
			{
				$newheight = $standart;
				$k = $standart/$height;
				$newwidth = $width*$k;
			}
			else if(isset($_GET["w"]))
			{
				$newwidth = $standart;
				$k = $standart/$width;
				$newheight = $height*$k;
			}
			else
			{
				if($width > $height)
				{
					
					if($standart > $width)
					{
						$newwidth = $width;
					}
					else
					{
						$k = $standart/$width;
						$newwidth = $standart;
						$newheight = $height*$k;
						if($newheight > $height)
						{
							$newheight = $height;
						}
					}
					
				}
				else
				{
					if($standart > $height)
					{
						$newheight = $height;
					}
					else
					{
						$k = $standart/$height;
						$newheight = $standart;
						$newwidth = $width*$k;
						if($newwidth > $width)
						{
							$newwidth = $width;
						}
					}
				}
			}
		}
		$target = imagecreatetruecolor($newwidth, $newheight);
		
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
		
		
		
		imagecopyresampled(
	    $target,  // Идентификатор нового изображения
	    $source,  // Идентификатор исходного изображения
	    0,0,      // Координаты (x,y) верхнего левого угла
	              // в новом изображении
	    0,0,      // Координаты (x,y) верхнего левого угла копируемого
	              // блока существующего изображения
	    $newwidth,     // Новая ширина копируемого блока
	    $newheight,     // Новая высота копируемого блока
	    $width, // Ширина исходного копируемого блока
	    $height  // Высота исходного копируемого блока
	    );
		$icfunc = "image".$format;
		$icfunc($target,"images/thumbs/$_GET[id]_$standart.$format");  
		$icfunc($target);  

	  imagedestroy($target);
	  imagedestroy($source);
  }
}

?>