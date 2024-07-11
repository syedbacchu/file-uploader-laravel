<?php

namespace Sdtech\FileUploaderLaravel\Service;

class ValidationService
{
    public function __construct()
    {
    }


    private function is_setup() {
        return true;
    }

    public function sendResponse($success,$status=200,$message = "",$data = [])
    {
        return [
            'success' => $success,
            'status' => $status,
            'message' => $message ? $message : __('Something went wrong'),
            'data' => $data
        ];
    }

    public function allowedTypes() {
        return [
            'jpeg',
            'webp',
            'gif',
            'png',
            'avif',
            'bmp',
        ];
    }

    public function allowedMimeTypes($userInput = []) {
        $allowedTypes = [];
        if (!empty($userInput)) {
            foreach ($userInput as $val) {
                $allowedTypes[] = 'image/' . $val;
            }
        } else {
            foreach ($this->allowedTypes() as $val) {
                $allowedTypes[] = 'image/' . $val;
            }
        }
        return $allowedTypes;
    }

    private function validateUploadFileType($type,$file,$allowedTypes) {
        if ($type == 'image') {
            $allowedTypes = $this->allowedMimeTypes($allowedTypes);
            $data['mime_type'] = $file->getMimeType();

            if (in_array($data['mime_type'], $allowedTypes)) {
                return $this->sendResponse(true,200,__('success'));
            } else {
                return $this->sendResponse(false,422,__('Invalid image type. supported types are '). implode(',',$allowedTypes));
            }
        } else {

        }
    }

    private function validateUploadFileSize($type,$file,$maxSize) {
        if ($type == 'image') {
            $size = $file->getSize(); // Get the size of the image in bytes

            // Convert maxSize from KB to bytes
            $maxUploadSize = !empty($maxSize) && $maxSize > 0 ? intval($maxSize) * 1024 : intval(config('fileuploaderlaravel.MAX_UPLOAD_IMAGE_SIZE')); // Size in bytes

            if ($size > $maxUploadSize) {
                return $this->sendResponse(false, 422, __('Image size exceeds the maximum allowed size of ') . $maxSize . ' KB');
            }
            return $this->sendResponse(true, 200, __('success'));
        } else {

        }
    }

    public function imageValidationBeforeUpload($file, $allowedTypes = [], $maxSize="") {
        $checkFileType = $this->validateUploadFileType('image',$file,$allowedTypes);
        if ($checkFileType['success'] == false) {
            return $checkFileType;
        }
        $checkFileSize = $this->validateUploadFileSize('image',$file,$maxSize);
        if ($checkFileSize['success'] == false) {
            return $checkFileSize;
        }

        return $this->sendResponse(true, 200, __('success'));
    }

    // check allowed output format for image
    private function checkAllowedOutputFormat($ext, $type) {
        if ($type == 'image') {
            if(in_array($ext,$this->allowedTypes())) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    public function getFileExt($file,$type,$ext ="") {
        if ($type == 'image') {
            $data['file_ext_original'] = $file->getClientOriginalExtension();
            $data['file_ext'] = !empty($ext) ? $ext : config('fileuploaderlaravel.DEFAULT_IMAGE_FORMAT');
            if (empty($data['file_ext'])) {
                $data['file_ext'] = $data['file_ext_original'];
            }
            if ($this->checkAllowedOutputFormat($data['file_ext'],'image')) {
                $data['file_name'] = time().uniqid().'.'.$data['file_ext'];
            } else {
                return $this->sendResponse(false,422,__('Invalid output extenstion , allowed extensions are '.implode(',',$this->allowedTypes())));
            }
        } else {

        }

        return $this->sendResponse(true,200,__('success'),$data);
    }

}
