<?php
class SimpleImage {
 
   var $image;
   var $image_type;
 
   function load($filename) {
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=100, $permissions=null) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      }
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }
   function output($image_type=IMAGETYPE_JPEG) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }
   }
   function getWidth() {
      return imagesx($this->image);
   }
   function getHeight() {
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
   function resize($width,$height) {
      
	  $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   }
	function crop($crop = 'square',$percent = false) {
		
		$w_i = $this->getWidth();
		$h_i = $this->getHeight();	
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
		$new_image = imagecreatetruecolor($w_o, $h_o);
		imagecopy($new_image , $this->image, 0, 0, $x_o, $y_o, $w_o, $h_o);
		$this->image = $new_image;
	}
}



?>