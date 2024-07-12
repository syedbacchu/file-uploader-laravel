<?php

namespace Sdtech\FileUploaderLaravel\Service;


class FileUploadService
{
    private $imgManager;
    private $validation;

    public function __construct()
    {
        $this->validation = new ValidationService();
    }


    /**
     * upload file in storage folder
     * @param FILE $reqFile (mandetory) uploaded file
     * @param STRING $path (mandetory) file path where upload iamge
     * @param STRING $oldFile (optional) old file name
     * @param ARRAY $allowedFileType  (optional) allowed image type like ["png","webp"]
     * @param INT $maxSize (optional) max upload size in KB 1024KB = 1MB
     *
     */
    public function uploadFileInStorage($reqFile,$path,$oldFile=null,$allowedFileType=[],$maxSize="") {
        $data = [];
        try {
            $checkValidation = $this->validation->fileValidationBeforeUpload($reqFile,$allowedFileType,$maxSize);

            if ($checkValidation['success'] == false) {
                return $checkValidation;
            }
            $getExt = $this->validation->getFileExt($reqFile,'file',"");
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

            $data['path'] = $path.'/'.$data['file_name'];
            $data['file_path'] = $storagePath.'/'.$data['file_name'];
            $data['file_url'] = $this->showStorageFileViewPath($path,$data['file_name']);

            // Remove the old file if it exists
            if (!empty($oldFile)) {
                $this->unlinkFile($storagePath,$oldFile);
            }

            $this->saveFileProcess('storage',$reqFile,$path,$data['file_name']);

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
    public function uploadFileInPublic($reqFile,$path,$oldFile=null,$allowedFileType=[],$maxSize="") {
        $data = [];
        try {
            $checkValidation = $this->validation->fileValidationBeforeUpload($reqFile,$allowedFileType,$maxSize);

            if ($checkValidation['success'] == false) {
                return $checkValidation;
            }
            $getExt = $this->validation->getFileExt($reqFile,'file',"");
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

            $data['path'] = $path.'/'.$data['file_name'];
            $data['file_path'] = $filePath.'/'.$data['file_name'];
            $data['file_url'] = $this->showFileViewPath($path,$data['file_name']);


            // Remove the old file if it exists
            if (!empty($oldFile)) {
                $this->unlinkFile($filePath,$oldFile);
            }

            $this->saveFileProcess('public',$reqFile,$filePath,$data['file_name']);
            return $this->validation->sendResponse(true,200,'upload success',$data);
        } catch(\Exception $e) {
            return $this->validation->sendResponse(false,500,$e->getMessage());
        }
    }

    // save file path
    public function saveFileProcess($type,$reqFile,$filePath,$fileName) {
        if ($type == 'storage') {
            // Store the file in the storage directory
            // dd($reqFile,$filePath,$fileName);
            $reqFile->storeAs('public/'.$filePath, $fileName);
        } else {
            // Store the file in the public directory
            $reqFile->move($filePath, $fileName);
        }
    }



    // delete image path
    public function unlinkFile($path,$oldFile) {
        if (!empty($oldFile) && file_exists($path . '/' . $oldFile)) {
            unlink($path . '/' . $oldFile);
        }
    }

    // get image view path for storage folder
    public function showStorageFileViewPath($path,$fileName){
        return asset('storage/' . $path .'/'. $fileName);
    }
    // get image view path for public folder
    public function showFileViewPath($path,$fileName){
        return asset($path .'/'. $fileName);
    }
}
