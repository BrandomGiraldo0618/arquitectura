<?php

namespace App\Library;

use Exception;
use Image;
use Log;
use Storage;

/**
 * Resize based in Instagram rules (Units in pixels px)
 * 1. Profile image: 180 x 180
 * 2. Square image: 1080 x 1080 but will compress the file to 600 x 600 (1:1 aspect ratio)
 * 3. Horizontal image (landscape): 1080 x 566 will compress to use a smaller size such as 600 x 400
 *    (From aspect ratio of 1.91:1 but you can go up tu 16:9)
 * 4. Vertical image (portrait): 1080 x 1350 however Instagram will show it as a 480 x 600
 *    (Aspect ratio of 4:5)
 */

class Picture 
{
    /**
     * Check instagram aspect ration vs image size
     */
    public function validAspectRatio($width, $height)
    {
        //1:1 - squares
        if ($width == $height)
        {
            return true;
        }
                
        $ratio = round($width / $height, 3);
            
        // Portrait: min 0.8 | max 0.99   
        if ($width < $height)
        {
            if ($ratio >= 0.8 && $ratio <= 0.99)
            {
                return true;
            }
        }
         
        // Landscape: min 1.01 | max 1.91
        if ($width > $height)
        {
            if ($ratio >= 1.01 && $ratio <= 1.91)
            {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Apply filters
     */
    public function applyFilter()
    {
        $callback = function ($constraint) { $constraint->upsize(); };
        $image->widen(800, $callback)->heighten(800, $callback);
        //It's also possible to combine calls into filters and just run code like this:
        
        $image->filter(new CustomResize(800, 800));

        $image->resize($maxWidth, $maxHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
    }

    /**
     * Prepares a image for storing.
     *
     * @param mixed $request
     * @param string $imageType ('profile', 'post')
     * @return string
     */
    public function storeImage($request, $imageType) 
    {
        //Get file from request
        $file = $request->file('image');
  
        //Get filename with extension
        $originalName = $file->getClientOriginalName();
  
        //Get file path
        $fileName = pathinfo($originalName, PATHINFO_FILENAME);
  
        //Remove unwanted characters
        $fileName = preg_replace('/[^A-Za-z0-9 ]/', '', $fileName);
        $fileName = preg_replace('/\s+/', '-', $fileName);
  
        //Get the original image extension
        $extension = $file->getClientOriginalExtension();
  
        //Create unique file name
        $storeName = $fileName . '-' . time();
  
        //Refer image to method resizeImage
        if($this->resizeImage($file, $storeName, $extension, $imageType))
        {
            return $storeName . '.' . $extension;
        }

        return '';
    }
  
    /**
     * Resizes a image using the InterventionImage package.
     *
     * @param object $file
     * @param string $storeName
     * @param string $imageType ('profile', 'post')
     * @param string $extension (jpg, jpeg, png)
     * @return bool
     */
    public function resizeImage($file, $storeName, $extension, $imageType) 
    {
        //Check image aspect ratio
        $data = getimagesize($file);
        $width = $data[0];
        $height = $data[1];

        if(!$this->validAspectRatio($width, $height))
        {
            return false;
        }

        try
        {
            //Save original file
            $name = $storeName . '.' . $extension;
            $tempPath = public_path('/thumbs');

            if('profile' === $imageType)
            {
                $path = $file->storeAs('public/users', $name);

                //Generate 180 and 110 image size
                $img = Image::make($file);
                $img->resize(null, 180, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save($tempPath . '/md-' . $name);

                Storage::disk('public')->put('users/md-' . $name, Storage::disk('thumbs')->get('md-' . $name));
                Storage::disk('thumbs')->delete('md-' . $name);

                $img = $img->resize(null, 110, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save($tempPath . '/sm-' . $name);
                    
                Storage::disk('public')->put('users/sm-' . $name, Storage::disk('thumbs')->get('sm-' . $name));
                Storage::disk('thumbs')->delete('sm-' . $name);
            }
            else
            {
                $path = $file->storeAs('public/posts', $name);

                //Post with square full hd image
                if($width === $height && 1080 <= $width)
                {
                    $img = Image::make($file);
                    $img->resize($width, 1080, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $img->save($tempPath . '/md-' . $name);

                    Storage::disk('public')->put('posts/md-' . $name, Storage::disk('thumbs')->get('md-' . $name));
                    Storage::disk('thumbs')->delete('md-' . $name);
    
                    $img = $img->resize($width, 600, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $img->save($tempPath . '/sm-' . $name);
                    
                    Storage::disk('public')->put('posts/sm-' . $name, Storage::disk('thumbs')->get('sm-' . $name));
                    Storage::disk('thumbs')->delete('sm-' . $name);
                }
                elseif($width > $height && 566 <= $height)
                {
                    $img = Image::make($file);

                    //Add callback functionality to retain maximal original image size
                    $img->fit($width, 566, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $img->save($tempPath . '/md-' . $name);

                    Storage::disk('public')->put('posts/md-' . $name, Storage::disk('thumbs')->get('md-' . $name));
                    Storage::disk('thumbs')->delete('md-' . $name);
    
                    $img = $img->resize($width, 400, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $img->save($tempPath . '/sm-' . $name);
                    
                    Storage::disk('public')->put('posts/sm-' . $name, Storage::disk('thumbs')->get('sm-' . $name));
                    Storage::disk('thumbs')->delete('sm-' . $name);
                }
                elseif($width < $height && 1350 <= $height)
                {
                    $img = Image::make($file);
                    
                    //Add callback functionality to retain maximal original image size
                    $img->fit($width, 1350, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $img->save($tempPath . '/md-' . $name);

                    Storage::disk('public')->put('posts/md-' . $name, Storage::disk('thumbs')->get('md-' . $name));
                    Storage::disk('thumbs')->delete('md-' . $name);
                    
                    $img = $img->resize($width, 600, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $img->save($tempPath . '/sm-' . $name);
                    
                    Storage::disk('public')->put('posts/sm-' . $name, Storage::disk('thumbs')->get('sm-' . $name));
                    Storage::disk('thumbs')->delete('sm-' . $name);
                }
                else
                {
                    //Fill to square
                    //$this->fillToSquareImage($file, $path, $mdPath, 15);
                    return false;
                }
            }
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return false;
        }

        return true;
    }

    /**
     * Create a new image adding color background for get correct size
     */
    public function fillToSquareImage($file, $path, $thumbPath, $blur=0)
    {
        $data = getimagesize($file);
        $width = $data[0];
        $height = $data[1];

        if($height > $width) 
        {
            $isVertical = true;
        } 
        elseif($height < $width) 
        {
            $isVertical = false;
        } 

        $maxSize = $isVertical ? $height : $width;

        try
        {
            $img = Image::make($path);
            $img->resize($maxSize, $maxSize, function($constraint){
                $constraint->aspectRatio();
            });
            $img->backup();

            $canvas = Image::canvas($maxSize, $maxSize);
            
            if(isset($blur) && !empty($blur))
            {
                //Recomended use Imagick driver
                $canvas->fill($img->blur($blur));
            }
            else
            {
                $canvas->fill('#ffffff');
            }

            $canvas->insert($img, 'center');
            $canvas->save($thumbPath);
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return false;
        }

        return true;
    }
}