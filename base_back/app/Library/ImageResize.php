<?php

namespace App\Library;

use Exception;
use Image;
use Log;
use Storage;

/**
 * Resize based in Instagram rules (Units in pixels px)
 * 1. Profile image lg: 180 x 180
 * 2. Profile image sm: 110 x 110
 * 3. Horizontal image (landscape from aspect ratio of 1.91:1 but you can go up tu 16:9) lg: 1080 x 566
 * 4. Horizontal image sm: 600 x 400
 */

class ImageResize
{
    private $path;
    private $tempPath;
    private $image;
    private $width;
    private $height;
    private $ratio;
    
    public function __construct($file, $path)
    {
        $this->path = $path;
        $this->tempPath = public_path('/thumbs');
        $this->image = Image::make($file);
        $this->width = $this->image->width();
        $this->height = $this->image->height();
        $this->ratio = round($this->width / $this->height, 3);
    }

    /**
     * Check instagram aspect ration vs image size
     */
    public function validAspectRatio()
    {
        //1:1 - squares
        if ($this->width == $this->height)
        {
            return true;
        }

        // Landscape: min 1.01 | max 1.91
        if ($this->width > $this->height)
        {
            if ($this->ratio >= 1.01 && $this->ratio <= 1.91)
            {
                return true;
            }
        }
        
        return false;
    }
  
    /**
     * Resizes a image using the InterventionImage package.
     *
     * @param object $width
     * @param string $height
     * @return string
     */
    public function createThumbnail($width, $height, $folder, $type, $prefix) 
    {
        try
        {
            $name = str_replace('public/' . $folder . '/', '', $this->path);

            if($this->validAspectRatio($this->width, $this->height))
            {
                if('resize' === $type)
                {
                    return $this->resize($width, $height, $folder, $prefix, $name);
                }
                elseif('crop' === $type)
                {
                    return $this->fit($width, $height, $folder, $prefix, $name);
                }
            }
            else
            {
                //Create image with canvas on background
                return $this->canvasImage($folder, $prefix, $name);
            }
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return '';
        }

        return '';
    }

    /**
     * Generate thumbnail using resize method
     */
    private function resize($width, $height, $folder, $prefix, $name)
    {
        $this->image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $this->image->save($this->tempPath . '/' . $prefix . $name);

        return $this->moveThumbnail($folder, $prefix, $name);
    }

    /**
     * Generate thumbnail using resize and crop method
     */
    private function fit($width, $height, $folder, $prefix, $name)
    {
        $this->image->fit($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $this->image->save($this->tempPath . '/' . $prefix . $name);

        return $this->moveThumbnail($folder, $prefix, $name);
    }

    /**
     * Create a new image adding color background for get correct size
     */
    private function canvasImage($folder, $prefix, $name)
    {
        /*if($this->height > $this->width) 
        {
            $isVertical = true;
        } 
        elseif($this->height < $this->width) 
        {
            $isVertical = false;
        } 

        $maxSize = $isVertical ? $this->height : $this->width;*/

        //Create new image with transparent background color
        $background = Image::canvas(800, 400, array(255, 255, 255, 0.3));

        //Read image file and resize it to 800x400
        //But keep aspect-ratio and do not size up, so smaller sizes don't stretch
        $this->image->resize(800, 400, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        //Insert resized image centered into background
        $background->insert($this->image, 'center');

        //Save or do whatever you like
        $background->save($this->tempPath . '/' . $prefix . $name);

        return $this->moveThumbnail($folder, $prefix, $name);
    }

    /**
     * Move thumbnail between storage disks
     */
    private function moveThumbnail($folder, $prefix, $name)
    {
        $fullName = $prefix . $name;
        $destinationPath = $folder . '/' . $fullName;
        
        Storage::disk('public')->put($destinationPath, Storage::disk('thumbs')->get($fullName));
        Storage::disk('thumbs')->delete($fullName);

        return $fullName;
    }
}