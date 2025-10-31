<?php

namespace Sdtech\FileUploaderLaravel\Service;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploadService
{
    private $imgManager;
    private $validation;
    private $fileService;

    public function __construct()
    {
        $this->imgManager = new ImageManager(new Driver());
        $this->validation = new ValidationService();
        $this->fileService = new FileUploadService();
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

            $data['file_ext_original'] = $getExt['data']['file_ext_original'];
            $data['file_ext'] = $getExt['data']['file_ext'];
            $data['file_name'] = $getExt['data']['file_name'];


            $data['quality'] = !empty($quality) ? intval($quality) : intval(config('fileuploaderlaravel.DEFAULT_IMAGE_QUALITY'));

            $data['path'] = $path.'/'.$data['file_name'];
            $data['file_path'] = $storagePath.'/'.$data['file_name'];
            $data['file_url'] = $this->fileService->showStorageFileViewPath($path,$data['file_name']);

            // Remove the old file if it exists
            if (!empty($oldFile)) {
                $this->fileService->unlinkFile($storagePath,$oldFile);
            }

            $this->saveImageProcess($data['file_ext'],$reqFile,$storagePath,$data['file_name'],$width,$height,$quality);
            return $this->validation->sendResponse(true,200,'upload success',$data);
        } catch(\Exception $e) {
            return $this->validation->sendResponse(false,500,$e->getMessage());
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

            $data['path'] = $path.'/'.$data['file_name'];
            $data['file_path'] = $filePath.'/'.$data['file_name'];
            $data['file_url'] = $this->fileService->showFileViewPath($path,$data['file_name']);


            // Remove the old file if it exists
            if (!empty($oldFile)) {
                $this->fileService->unlinkFile($filePath,$oldFile);
            }

            $this->saveImageProcess($data['file_ext'],$reqFile,$filePath,$data['file_name'],$width,$height,$quality);
            return $this->validation->sendResponse(true,200,'upload success',$data);
        } catch(\Exception $e) {
            return $this->validation->sendResponse(false,500,$e->getMessage());
        }
    }

    /**
     * upload image to s3 disk
     */
    public function uploadImageInS3($reqFile,$path,$oldFile=null,$allowedImageType=[],$maxSize="",$format=null,$width=null,$height=null,$quality=null) {
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

            $data['file_ext_original'] = $getExt['data']['file_ext_original'];
            $data['file_ext'] = $getExt['data']['file_ext'];
            $data['file_name'] = $getExt['data']['file_name'];

            $data['quality'] = !empty($quality) ? intval($quality) : intval(config('fileuploaderlaravel.DEFAULT_IMAGE_QUALITY'));

            $data['path'] = $path.'/'.$data['file_name'];
            $data['file_path'] = $data['path'];

            // Remove the old file from s3 if it exists
            if (!empty($oldFile)) {
                Storage::disk('s3')->delete($path.'/'.$oldFile);
            }

            // process to a temporary file, then upload to s3
            $tmpDir = sys_get_temp_dir();
            $tmpPath = rtrim($tmpDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $data['file_name'];

            $this->saveImageProcess($data['file_ext'],$reqFile,$tmpDir,$data['file_name'],$width,$height,$quality);

            // upload
            $stream = fopen($tmpPath, 'r');
            Storage::disk('s3')->put($data['path'], $stream, ['visibility' => 'public']);
            if (is_resource($stream)) {
                fclose($stream);
            }
            // cleanup
            if (file_exists($tmpPath)) {
                @unlink($tmpPath);
            }

            $data['file_url'] = $this->fileService->showS3FileViewPath($path,$data['file_name']);

            return $this->validation->sendResponse(true,200,'upload success',$data);
        } catch(\Exception $e) {
            return $this->validation->sendResponse(false,500,$e->getMessage());
        }
    }

    // save image path
    public function saveImageProcess($outputExt,$reqFile,$filePath,$fileName,$width=null,$height=null,$quality=null) {

        $file = $this->imgManager->read($reqFile);
        if ($width != null && $height != null && is_int($width) && is_int($height)) {
            $file = $file->scale($width,$height);
        }

        $filePathName = $filePath.'/'.$fileName;
        if ($outputExt == 'png') {
            $file->toPng()->save($filePathName);
        } elseif($outputExt == 'jpeg') {
            $file->toJpeg(intval($quality),true)->save($filePathName);;
        } elseif($outputExt == 'gif') {
            $file->toGif(true)->save($filePathName);
        } elseif($outputExt == 'bmp') {
            $file->toJpeg(intval($quality),true)->save($filePathName);
        } else {
            $file->toWebp(intval($quality),true)->save($filePathName);
        }
    }


}
