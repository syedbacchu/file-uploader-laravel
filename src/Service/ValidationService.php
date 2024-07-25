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

    /**
     * Supported File Types:
        * ZIP: application/zip, application/x-zip-compressed
        * PDF: application/pdf
        * Word: application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document
        * CSV: text/csv
        * Excel: application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
        * Text: text/plain
        * Video: video/mp4, video/x-msvideo, video/x-ms-wmv, video/quicktime
        * Audio: audio/mpeg, audio/wav, audio/ogg, audio/mp4
     */
    public function allowedFileTypes() {
        return [
            'application/zip',
            'application/x-zip-compressed',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/csv',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
            'video/mp4',
            'video/x-msvideo',
            'video/x-ms-wmv',
            'video/quicktime',
            'audio/mpeg',
            'audio/wav',
            'audio/ogg',
            'audio/mp4'
        ];
    }

    public function allowedFileExtensions($userInput = []) {
        if(!empty($userInput) && isset($userInput[0])) {
            return $userInput;
        } else {
            return ['zip', 'pdf', 'doc', 'docx', 'csv', 'xls', 'xlsx', 'txt', 'mp4', 'avi', 'wmv', 'mov', 'mpeg', 'wav', 'ogg', 'mp3'];
        }
    }

    public function allowedMimeTypes($userInput = []) {
        $allowedTypes = [];
        if (!empty($userInput) && isset($userInput[0])) {
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

    private function validateUploadFileType($type,$file,$allowedType=[]) {
        
        if ($type == 'image') {
            $allowedTypes = $this->allowedMimeTypes($allowedType);
            $data['mime_type'] = $file->getMimeType();

            if (in_array($data['mime_type'], $allowedTypes)) {
                return $this->sendResponse(true,200,__('success'));
            } else {
                return $this->sendResponse(false,422,__('Invalid image type. supported types are '). implode(',',$allowedTypes));
            }
        } else {
            $allowedTypes = $this->allowedFileTypes();
            $data['mime_type'] = $file->getMimeType();
            if (!in_array($data['mime_type'],$allowedTypes)) {
                return $this->sendResponse(false,422,__('Inalid File'));
            }

            $allowedExtensions = $this->allowedFileExtensions($allowedType);
            $fileExtension = strtolower($file->getClientOriginalExtension());
            
            if (!in_array($fileExtension,$allowedExtensions)) {
                return $this->sendResponse(false,422,__('Invalid file type. supported types are '). implode(',',$allowedExtensions));
            }

            return $this->sendResponse(true,200,__('success'));
        }
    }

    private function validateUploadFileSize($type,$file,$maxSize) {
        $size = $file->getSize(); // Get the size of the image in bytes

        // Convert maxSize from KB to bytes
        $maxUploadSize = !empty($maxSize) && $maxSize > 0 ? intval($maxSize) * 1024 : intval(config('fileuploaderlaravel.MAX_UPLOAD_IMAGE_SIZE')); // Size in bytes

        if ($size > $maxUploadSize) {
            return $this->sendResponse(false, 422, __('File size exceeds the maximum allowed size of ') . $maxUploadSize . ' KB');
        }
        return $this->sendResponse(true, 200, __('success'));
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
            $data['file_ext_original'] = $file->getClientOriginalExtension();
            $data['file_ext'] = $data['file_ext_original'];
            $data['file_name'] = time().uniqid().'.'.$data['file_ext'];
        }

        return $this->sendResponse(true,200,__('success'),$data);
    }


    public function fileValidationBeforeUpload($file, $allowedTypes = [], $maxSize="") {
        $checkFileType = $this->validateUploadFileType('file',$file,$allowedTypes);
        if ($checkFileType['success'] == false) {
            return $checkFileType;
        }
        $checkFileSize = $this->validateUploadFileSize('file',$file,$maxSize);
        if ($checkFileSize['success'] == false) {
            return $checkFileSize;
        }

        return $this->sendResponse(true, 200, __('success'));
    }
}
