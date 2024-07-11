<?php

namespace Sdtech\FileUploaderLaravel\Service;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploadService
{
    private $imgManager;
    private $validation;

    public function __construct()
    {
        $this->imgManager = new ImageManager(new Driver());
        $this->validation = new ValidationService();
    }


    /**
     * upload image in storage folder
     * @param FILE $reqFile (mandetory) uploaded file
     * @param STRING $path (mandetory) file path where upload iamge
     * @param STRING $oldFile (optional) old file name
     * @param ARRAY $allowedImageType  (optional) allowed image type like ["png","webp"]
     * @param INT $maxSize (optional) max upload size in KB 1024KB = 1MB
     * @param STRING $format (optional) image output format default =webp
     * @param INT $width (optional) image width
     * @param INT $height (optional) image height
     * @param INT $quality (optional) image quality default = 80
     *
     */
    public function uploadImageInStorage($reqFile,$path,$oldFile=null,$allowedImageType=[],$maxSize="",$format=null,$width=null,$height=null,$quality=null) {
        $data = [];
        try {
            $checkValidation = $this->validation->imageValidationBeforeUpload($reqFile,$allowedImageType,$maxSize);

            if ($checkValidation['success'] == false) {
                return $checkValidation;
            }
            $getExt = $this->validation->getFileExt($reqFile,'image',$format);
            if ($getExt['success'] == false) {
                return $getExt;
            }

            $storagePath = storage_path('app/public/' . $path);

            // Ensure the directory exists, and create it if it does not
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true); // Note: Use 0777 with leading zero
            }
            // Set the directory permissions to 0777
            chmod($storagePath, 0777);

            // dd($path,$storagePath);

            $data['file_ext_original'] = $getExt['data']['file_ext_original'];
            $data['file_ext'] = $getExt['data']['file_ext'];
            $data['file_name'] = $getExt['data']['file_name'];


            $data['quality'] = !empty($quality) ? intval($quality) : intval(config('fileuploaderlaravel.DEFAULT_IMAGE_QUALITY'));

            $data['path'] = $path.$data['file_name'];
            $data['file_path'] = $storagePath.$data['file_name'];
            $data['file_url'] = $this->showStorageImageViewPath($path,$data['file_name']);

            // Remove the old file if it exists
            if (!empty($oldFile)) {
                $this->unlinkFile($storagePath,$oldFile);
            }

            $this->saveImageProcess($data['file_ext'],$reqFile,$data['file_path'],$data['file_name'],$width,$height,$quality);
            return $this->validation->sendResponse(true,200,'upload success',$data);
        } catch(\Exception $e) {
            return $this->validation->sendResponse(false,400,$e->getMessage());
        }
    }



    /**
     * upload image in storage folder
     * @param FILE $reqFile (mandetory) uploaded file
     * @param STRING $path (mandetory) file path where upload iamge
     * @param STRING $oldFile (optional) old file name
     * @param ARRAY $allowedImageType  (optional) allowed image type like ["png","webp"]
     * @param INT $maxSize (optional) max upload size in KB 1024KB = 1MB
     * @param STRING $format (optional) image output format default =webp
     * @param INT $width (optional) image width
     * @param INT $height (optional) image height
     * @param INT $quality (optional) image quality default = 80
     *
     */
    public function uploadImageInPublic($reqFile,$path,$oldFile=null,$allowedImageType=[],$maxSize="",$format=null,$width=null,$height=null,$quality=null) {
        $data = [];
        try {
            $checkValidation = $this->validation->imageValidationBeforeUpload($reqFile,$allowedImageType,$maxSize);

            if ($checkValidation['success'] == false) {
                return $checkValidation;
            }
            $getExt = $this->validation->getFileExt($reqFile,'image',$format);
            if ($getExt['success'] == false) {
                return $getExt;
            }

            $filePath = public_path($path);

            // Ensure the directory exists, and create it if it does not
            if (!file_exists($filePath)) {
                mkdir($filePath, 0777, true);
            }
            // Set the directory permissions to 0777
            chmod($filePath, 0777);

            $data['file_ext_original'] = $getExt['data']['file_ext_original'];
            $data['file_ext'] = $getExt['data']['file_ext'];
            $data['file_name'] = $getExt['data']['file_name'];


            $data['quality'] = !empty($quality) ? intval($quality) : intval(config('fileuploaderlaravel.DEFAULT_IMAGE_QUALITY'));

            $data['path'] = $path.$data['file_name'];
            $data['file_path'] = $filePath.$data['file_name'];
            $data['file_url'] = $this->showImageViewPath($path,$data['file_name']);


            // Remove the old file if it exists
            if (!empty($oldFile)) {
                $this->unlinkFile($filePath,$oldFile);
            }

            $this->saveImageProcess($data['file_ext'],$reqFile,$data['file_path'],$data['file_name'],$width,$height,$quality);
            return $this->validation->sendResponse(true,200,'upload success',$data);
        } catch(\Exception $e) {
            return $this->validation->sendResponse(false,400,$e->getMessage());
        }
    }

    // save image path
    public function saveImageProcess($outputExt,$reqFile,$filePath,$width=null,$height=null,$quality=null) {

        $file = $this->imgManager->read($reqFile);
        if ($width != null && $height != null && is_int($width) && is_int($height)) {
            $file = $file->scale($width,$height);
        }

        if ($outputExt == 'png') {
            $file->toPng()->save($filePath);
        } elseif($outputExt == 'jpeg') {
            $file->toJpeg(intval($quality),true)->save($filePath);;
        } elseif($outputExt == 'gif') {
            $file->toGif(true)->save($filePath);
        } elseif($outputExt == 'bmp') {
            $file->toJpeg(intval($quality),true)->save($filePath);
        } else {
            $file->toWebp(intval($quality),true)->save($filePath);
        }
    }
    

    // delete image path
    public function unlinkFile($path,$oldFile) {
        if (!empty($oldFile) && file_exists($path . '/' . $oldFile)) {
            unlink($path . '/' . $oldFile);
        }
    }

    // get image view path for storage folder
    public function showStorageImageViewPath($path,$fileName){
        return asset('storage/' . $path . $fileName);
    }
    // get image view path for public folder
    public function showImageViewPath($path,$fileName){
        return asset($path . $fileName);
    }

}
