<?php

    /**
     * Image Processor / Resizer
     * 
     * Example Usage (crop to 200px x 200px):
     *        $this->ProcessImage->load($_FILES['file']['tmp_name']);
     *        $this->ProcessImage->crop(200, 200); 
     *        $this->ProcessImage->save('some/absolute/path/filename.jpg');
     *
     * @package App.Controller.Component
     * @version 1.2.0
     * @author cornellcampbell
     */
    class ProcessImageComponent extends Component {
        
        /**
         * Original Image, saved so that the image doesn't
         * need to be loaded multiple times if multiple operations 
         * are being done to the same image
         *
         * @var string
         */
        public $original_image = NULL;
        
        /**
         * Holds the image data being manipulated 
         *
         * @var string
         */
        public $image = NULL;
        
        /**
         * Type of image - jpg, gif, png
         *
         * @var string
         */
        public $image_type;
        
        /**
         * How much memory to allocate
         *
         * @var string
         */
        public $allocateMemory = '900M';
    
        /**
         * Load the Image into a variable ($this->image)
         *
         * @param string $filename 
         * @return void
         * @author cornellcampbell
         */
        public function load($filename) {
            // We don't want to run out of memory
            ini_set('memory_limit', $this->allocateMemory);
            
            $image_info = getimagesize($filename);
            $this->image_type = $image_info[2];
            if ($this->image_type == IMAGETYPE_JPEG) {
                $this->image = $this->original_image = imagecreatefromjpeg($filename);
            } elseif ($this->image_type == IMAGETYPE_GIF) {
                $this->image = $this->original_image = imagecreatefromgif($filename);
            } elseif ($this->image_type == IMAGETYPE_PNG) {
                $this->image = $this->original_image = imagecreatefrompng($filename);
            } else {
                return false;
            }
        }
        
        /**
         * Rotates Image 90 degrees clockwise
         *
         * @return void
         * @author cornellcampbell
         */
        public function rotate($direction = 'RIGHT') {
            switch ($direction) {
                case 'RIGHT': $degrees = -90; break;
                case 'LEFT': $degrees = 90; break;
                default: $degrees = -90; break;
            }
            $this->image = imagerotate($this->image, $degrees, 0);
        }
        
        /**
         * Save the Processed / Resized Image to file
         *
         * @param string $filename 
         * @param string $image_type 
         * @param string $compression 
         * @return void
         * @author cornellcampbell
         */
        public function save($filename, $image_type=IMAGETYPE_JPEG, $compression=90) {
            if ($image_type === NULL) {
                $image_type = $this->image_type;
            }
            if ($image_type == IMAGETYPE_JPEG) {
                imagejpeg($this->image, $filename, $compression);
            } elseif ($image_type == IMAGETYPE_GIF) {
                imagegif($this->image, $filename);         
            } elseif ($image_type == IMAGETYPE_PNG) {
                imagealphablending($this->image, false);
                imagesavealpha($this->image, false);
                imagepng($this->image, $filename, 0, PNG_NO_FILTER);
            }   
        }
        
        /**
         * Output the Processed / Resized image to screen
         *
         * @param string $image_type 
         * @return void
         * @author cornellcampbell
         */
        public function output($image_type=NULL, $compression=100) {
            if ($image_type === NULL) {
                $image_type = $this->image_type;
            }
                        
            if ($image_type == IMAGETYPE_JPEG) {
                header('Content-Type: image/jpeg');
                imagejpeg($this->image, NULL, $compression);
            } elseif ($image_type == IMAGETYPE_GIF) {
                header('Content-Type: image/gif');
                imagegif($this->image);         
            } elseif ($image_type == IMAGETYPE_PNG) {
                header('Content-Type: image/png');
                imagepng($this->image);
            }
            
            imagedestroy($this->image);
        }
        
        /**
         * Return the width of the loaded image
         *
         * @return void
         * @author cornellcampbell
         */
        public function getWidth() {
            return imagesx($this->image);
        }
        
        /**
         * Return the height of the loaded image
         *
         * @return void
         * @author cornellcampbell
         */
        public function getHeight() {
            return imagesy($this->image);
        }
        
        /**
         * Resizes an image and adds colored bars above or beside the image
         *     $matte can be either 'white' or 'black'
         *
         * @param int $target_width 
         * @param int $target_height 
         * @param string $matte 
         * @return void
         * @author cornellcampbell
         */
        public function matte($target_width, $target_height, $matte = 'auto') {
            $this->image = $this->original_image;
            
            $width = $this->getWidth();
            $height = $this->getHeight();
            $img_ratio = $width / $height;
            $target_ratio = $target_width / $target_height;
            
            if ($img_ratio >= $target_ratio) {
                $this->resizeToWidth($target_width);
            } else {
                $this->resizeToHeight($target_height);
            }
            
            $width = $this->getWidth();
            $height = $this->getHeight();
            $src_x = ($width < $target_width) ? floor(($target_width - $width) / 2) : 0;
            $src_y = ($height < $target_height) ? floor(($target_height - $height) / 2) : 0;
            
            $new_image = imagecreatetruecolor($target_width, $target_height);
            
            // Get matte color
            if ($matte == 'auto') {
                $sample = array();
                
                // Sample left side of image
                $i = 1;
                while ($i < $height) {
                    $sample[] = imagecolorat($this->image, 1, $i);
                    $i += 2;
                }
                
                // Sample right side of image
                $i = 1;
                while ($i < $height) {
                    $sample[] = imagecolorat($this->image, $width-1, $i);
                    $i += 2;
                }
                
                if ($matte == 'sample') {
                    
                    // Sample top of image
                    $i = 1;
                    while ($i < $width) {
                        $sample[] = imagecolorat($this->image, 1, $i);
                        $i += 2;
                    }
                    
                    // Sample bottom of image
                    $i = 1;
                    while ($i < $width) {
                        $sample[] = imagecolorat($this->image, $height-1, $i);
                        $i += 2;
                    }
                }
                
                $rgb = array_sum($sample) / count($sample);
                
                $rgb = array(
                    ($rgb >> 16) & 0xFF,
                    ($rgb >> 8) & 0xFF,
                    $rgb & 0xFF
                );
                
                // echo '<pre>'; print_r($rgb); echo '</pre>'; exit();
                
                if ($matte == 'auto') {
                    $hsl = $this->RgbToHsl($rgb);
                
                    if ($hsl[2] < 30) {
                        $matte = 'black';
                    } else {
                        $matte = 'white';
                    }
                }
            }
            
            if ($matte == 'sample') {
                $rTot = $gTot = $bTot = $tot = 0;
                
                // Loop through every column of the image.
                for ($col = 0; $width > $col; $col++) {
                    // Loop through every row in the current column of the image.
                    for ($row = 0; $height > $row; $row++) {
                        // Get the index of the color of the current pixel.
                        $rgb = imagecolorat($img, $col, $row);
                        // Extract the RGB values into the total variables.
                        $rTot += (($rgb >> 16) & 0xFF);
                        $gTot += (($rgb >> 8) & 0xFF);
                        $bTot += ($rgb & 0xFF);
                        // Increase the total amount of pixles.
                        $tot++;
                    }
                }
                
                // Get the rounded average RGB variables.
                $rAverage = round($rTot / $tot);
                $gAverage = round($gTot / $tot);
                $bAverage = round($bTot / $tot);
                
                $rgb = array($rAverage, $gAverage, $bAverage);
            }
            
            if ($matte == 'sample') {
                $background = imagecolorallocate($new_image, $rgb[0], $rgb[1], $rgb[2]);
            } else if ($matte == 'black') {
                $background = imagecolorallocate($new_image, 0, 0, 0);
            } else {
                $background = imagecolorallocate($new_image, 255, 255, 255);
            }            
            
            if ($this->image_type == IMAGETYPE_GIF) {
                imagealphablending($new_image, false);
                imagesavealpha($new_image,true);
                imagefilledrectangle($new_image, 0, 0, $target_width - 1, $target_height - 1, imagecolorallocatealpha($new_image, 255, 255, 255, 127));
            } else {
                imagefilledrectangle($new_image, 0, 0, $target_width - 1, $target_height - 1, $background);
            }
            imagecopyresampled($new_image, $this->image, $src_x, $src_y, 0, 0, $width, $height, $width, $height);
            $this->image = $new_image;
        }
        
        /**
         * Crop the loaded image
         *
         * @param string $target_width 
         * @param string $target_height 
         * @return void
         * @author cornellcampbell
         */
        public function crop($target_width, $target_height) {
            $this->image = $this->original_image;
            
            $width = $this->getWidth();
            $height = $this->getHeight();
            $img_ratio = $width / $height;
            $target_ratio = $target_width / $target_height;
            if ($img_ratio >= $target_ratio) {
                $src_w = $height * $target_ratio;
                $src_h = $height;
                $src_x = abs($width - $src_w) / 2;
                $src_y = 0;
            } else {
                $h_ratio = $target_height / $target_width;
                $src_w = $width;
                $src_h = $width * $h_ratio;
                $src_x = 0;
                $src_y = abs($height - $src_h) / 2;
            }
            $this->_cropImage($target_width, $target_height, $src_x, $src_y, $src_w, $src_h);
        }
        
        /**
         * Resized to longest side of loaded image
         *
         * @param string $size 
         * @return void
         * @author cornellcampbell
         */
        public function resizeLongest($size, $scale = true) {
            $this->image = $this->original_image;
            
            // Don't scale up if $scale === false
            if ( ! $scale && $this->getWidth() < $size && $this->getHeight() < $size) {
                return;
            }
            
            if ($this->getWidth() >= $this->getHeight()) {
                $this->resizeToWidth($size);
            } else {
                $this->resizeToHeight($size);
            }
        }
        
        /**
         * Resize to height of loaded image
         *
         * @param string $height 
         * @return void
         * @author cornellcampbell
         */
        public function resizeToHeight($height, $scale = true) {
            $this->image = $this->original_image;
            
            // Don't scale up if $scale === false
            if ( ! $scale && $this->getHeight() <= $height) {
                return;
            }
            
            $ratio = $height / $this->getHeight();
            $width = $this->getWidth() * $ratio;
            $this->_resize($width, $height);
        }
        
        /**
         * Resize to width of loaded image
         *
         * @param string $width 
         * @return void
         * @author cornellcampbell
         */
        public function resizeToWidth($width, $scale = true) {
            $this->image = $this->original_image;
            
            // Don't scale up if $scale === false
            if ( ! $scale && $this->getWidth() <= $width) {
                return;
            }
            
            $ratio = $width / $this->getWidth();
            $height = $this->getHeight() * $ratio;
            $this->_resize($width, $height);
        }
        
        /**
         * Scale loaded image
         *
         * @param string $scale 
         * @return void
         * @author cornellcampbell
         */
        public function scale($scale) {
            $this->image = $this->original_image;
            
            $width = $this->getWidth() * $scale/100;
            $height = $this->getHeight() * $scale/100; 
            $this->_resize($width, $height);
        }
        
        /**
         * Used by resizeToHeight(), resizeToWidth(), and scale();
         * Completes the actual resizing
         *
         * @param string $width 
         * @param string $height 
         * @return void
         * @author cornellcampbell
         */
        public function _resize($width, $height) {
            $new_image = imagecreatetruecolor($width, $height);
            if ($this->image_type == IMAGETYPE_GIF) {
                $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
                imagefill($new_image, 0, 0, $transparent);
                imagealphablending($new_image, true);
            } else {
                $white = imagecolorallocate($new_image, 0xFF, 0xFF, 0xFF);
                imagefilledrectangle($new_image, 0, 0, $width, $height, $white);
            }
            imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
            $this->image = $new_image;   
        }
        
        /**
         * Used by crop()
         * Completes the actual cropping
         *
         * @param string $target_width 
         * @param string $target_height 
         * @param string $src_x 
         * @param string $src_y 
         * @param string $src_w 
         * @param string $src_h 
         * @return void
         * @author cornellcampbell
         */
        public function _cropImage($target_width, $target_height, $src_x, $src_y, $src_w, $src_h) {
            $new_image = imagecreatetruecolor($target_width, $target_height);
            if ($this->image_type == IMAGETYPE_GIF) {
                $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
                imagefill($new_image, 0, 0, $transparent);
                imagealphablending($new_image, true);                
            } else {
                $white = imagecolorallocate($new_image, 0xFF, 0xFF, 0xFF);
                imagefilledrectangle($new_image, 0, 0, $target_width - 1, $target_height - 1, $white);
            }
            imagecopyresampled($new_image, $this->image, 0, 0, $src_x, $src_y, $target_width, $target_height, $src_w, $src_h);
            $this->image = $new_image;
        }
        
        /**
         * Convert RGB to HSL
         *
         * @param string $r 
         * @param string $g 
         * @param string $b 
         * @return array [hue, saturation, lumiosity]
         * @author cornellcampbell
         */
        public function RgbToHsl($r = NULL, $g = NULL, $b = NULL) {
            if (is_array($r) && count($r) == 3 && $g === NULL && $b === NULL) {
                list($r, $g, $b) = $r;
            }
            
            $r /= 255;
            $g /= 255;
            $b /= 255;
            
            $max = max($r, $g, $b);
            $min = min($r, $g, $b);
            $diff = $max - $min;
            $add = $max + $min;
            
            if ($min == $max) {
                $hue = 0;
            } else if ($r == $max) {
                $hue = ((60 * ($g - $b) / $diff) + 360) % 360;
            } else if ($g == $max) {
                $hue = (60 * ($b - $r) / $diff) + 120;
            } else {
                $hue = (60 * ($r - $g) / $diff) + 240;
            }
            
            $lum = 0.5 * $add;
            
            if ($lum == 0) {
                $sat = 0;
            } else if ($lum == 1) {
                $sat = 1;
            } else if ($lum <= 0.5) {
                $sat = $diff / $add;
            } else {
                $sat = $diff / (2 - $add);
            }
            
            $h = round($hue);
            $s = round($sat * 100);
            $l = round($lum * 100);
            
            return array($h, $s, $l);
        }
        
    }
    
?>