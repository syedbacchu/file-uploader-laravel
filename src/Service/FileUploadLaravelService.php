<?php

namespace Sdtech\FileUploaderLaravel\Service;

/*

*/

class FileUploadLaravelService extends BaseService
{
    private $imageService;
    public function __construct()
    {
        $this->imageService = new ImageUploadService();
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

}
