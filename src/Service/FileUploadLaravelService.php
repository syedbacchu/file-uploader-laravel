<?php

namespace Sdtech\FileUploaderLaravel\Service;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

/*

*/

class FileUploadLaravelService

{

    private $imgManager;
    public function __construct()
    {
        $this->imgManager = new ImageManager(new Driver());
    }

    public function testing(){
        return 'ok google';
    }


    private function is_setup() {
        return true;
    }

    private function sendResponse($status,$message = "",$data = [])
    {
        return [
            'success' => $status,
            'message' => $message ? $message : 'Something went wrong',
            'data' => $data
        ];
    }


    /**
     * test upload
     * @param email
     * @param password
     * @param google_auth_otp
     *
     */
    public function testUpload($file,$path) {
        try {
            if ($file) {
                $imgName = time().uniqid().$file->getClientOriginalExtension();
                $img = $this->imgManager->read($file);
                $img = $img->resize(370,246);
                $img->toJpeg(80)->save(storage_path('app/public/',$path).$imgName);

            }
            return $this->sendResponse(true);
        } catch(\Exception $e) {
            return $this->sendResponse(false);
        }
    }


}
