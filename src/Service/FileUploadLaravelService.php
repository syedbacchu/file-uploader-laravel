<?php

namespace Sdtech\FileUploaderLaravel\Service;

class FileUploadLaravelService extends BaseService
{
    private $imageService;
    private $fileService;
    private $service;

    public function __construct()
    {
        $this->imageService = new ImageUploadService();
        $this->fileService = new FileUploadService();
        $this->service = new ValidationService();
    }


    /**
     * upload image in storage folder
     * @param FILE $reqFile (mandetory) uploaded file
     * @param STRING $path (mandetory) file path where upload iamge
     * @param STRING $oldFile (optional) old file name
     * @param ARRAY $allowedImageType  (optional) allowed image type like ["png","webp","jpeg"]
     * @param INT $maxSize (optional) max upload size in KB 1024KB = 1MB
     * @param STRING $format (optional) image output format default = webp
     * @param INT $width (optional) image width
     * @param INT $height (optional) image height
     * @param INT $quality (optional) image quality default = 80
     */
    public function _uploadImageInStorage($reqFile,$path,$old_file="",$allowedImageType=[],$maxSize="", $format='',$width="",$height=null,$quality=null) {
        return $this->imageService->uploadImageInStorage($reqFile,$path,$old_file,$allowedImageType,$maxSize,$format,$width,$height,$quality);
    }

    /**
     * upload image in main public folder
     * @param FILE $reqFile (mandetory) uploaded file
     * @param STRING $path (mandetory) file path where upload iamge
     * @param STRING $oldFile (optional) old file name
     * @param ARRAY $allowedImageType  (optional) allowed image type like ["png","webp","jpeg"]
     * @param INT $maxSize (optional) max upload size in KB 1024KB = 1MB
     * @param STRING $format (optional) image output format default = webp
     * @param INT $width (optional) image width
     * @param INT $height (optional) image height
     * @param INT $quality (optional) image quality default = 80
     */
    public function _uploadImageInPublic($reqFile,$path,$old_file="",$allowedImageType=[],$maxSize="", $format='',$width="",$height=null,$quality=null) {
        return $this->imageService->uploadImageInPublic($reqFile,$path,$old_file,$allowedImageType,$maxSize,$format,$width,$height,$quality);
    }

    /**
     * upload file in storage folder
     * @param FILE $reqFile (mandetory) uploaded file
     * @param STRING $path (mandetory) file path where upload iamge
     * @param STRING $oldFile (optional) old file name
     * @param ARRAY $allowedImageType  (optional) allowed image type like ["png","webp","jpeg"]
     * @param INT $maxSize (optional) max upload size in KB 1024KB = 1MB
     */
    public function _uploadFileInStorage($reqFile,$path,$old_file="",$allowedImageType=[],$maxSize="") {
        return $this->fileService->uploadFileInStorage($reqFile,$path,$old_file,$allowedImageType,$maxSize);
    }

     /**
     * upload file in public folder
     * @param FILE $reqFile (mandetory) uploaded file
     * @param STRING $path (mandetory) file path where upload iamge
     * @param STRING $oldFile (optional) old file name
     * @param ARRAY $allowedImageType  (optional) allowed image type like ["png","webp","jpeg"]
     * @param INT $maxSize (optional) max upload size in KB 1024KB = 1MB
     */
    public function _uploadFileInPublic($reqFile,$path,$old_file="",$allowedImageType=[],$maxSize="") {
        return $this->fileService->uploadFileInPublic($reqFile,$path,$old_file,$allowedImageType,$maxSize);
    }


    // delete file path
    public function _unlinkFile($path,$oldFile) {
        return $this->fileService->unlinkFile($path,$oldFile);
    }

    // get file view path for storage folder
    public function _showStorageFileViewPath($path,$fileName){
        return $this->fileService->showStorageFileViewPath($path,$fileName);
    }
    // get file view path for public folder
    public function _showFileViewPath($path,$fileName){
        return $this->fileService->showFileViewPath($path,$fileName);
    }

    // get allowed image type
    public function _allowedTypes(){
        return $this->service->allowedTypes();
    }

    // get allowed image type
    public function _allowedFileExtensions(){
        return $this->service->allowedFileExtensions();
    }

}
