<?php


		$Image  = $_GET['src'];
        $right  = $_GET['r'];
        $bottom = $_GET['b'];
        if($_GET['type']==2)
        {
            $WaterMark = 'watermark2.png';
        }else{
            $WaterMark = 'watermark.png';
        }

        $_WATERMARK = IMAGECREATEFROMPNG($WaterMark);
        $_RESIM = IMAGECREATEFROMJPEG($Image);

        $marge_right =$right;
        $marge_bottom = $bottom;
        $sx = IMAGESX($_WATERMARK);
        $sy = IMAGESY($_WATERMARK);

        IMAGECOPY($_RESIM, $_WATERMARK, IMAGESX($_RESIM) - $sx - $marge_right, IMAGESY($_RESIM) - $sy - $marge_bottom, 0, 0, IMAGESX($_WATERMARK), IMAGESY($_WATERMARK));

        header('Content-type: image/png');
        IMAGEPNG($_RESIM);
        IMAGEDESTROY($_RESIM);