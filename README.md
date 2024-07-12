# file-uploader-laravel | A Library to upload image and file with validation.

[![Latest Version](https://img.shields.io/github/release/syedbacchu/file-uploader-laravel.svg?style=flat-square)](https://github.com/syedbacchu/file-uploader-laravel/releases)
[![Issues](https://img.shields.io/github/issues/syedbacchu/file-uploader-laravel.svg?style=flat-square)](https://github.com/syedbacchu/file-uploader-laravel)
[![Stars](https://img.shields.io/github/stars/syedbacchu/file-uploader-laravel.svg?style=social)](https://github.com/syedbacchu/file-uploader-laravel)
[![Stars](https://img.shields.io/github/forks/syedbacchu/file-uploader-laravel?style=flat-square)](https://github.com/syedbacchu/file-uploader-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/sdtech/file-uploader-laravel.svg?style=flat-square)](https://packagist.org/packages/sdtech/file-uploader-laravel)

- [About](#about)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Uses](#Uses)

## About

A simple library that help you to to upload image and file.
The current features are :

- Upload file
- Upload Image

## Requirements

* [Laravel 5.8+](https://laravel.com/docs/installation)
* [PHP ^8.1](https://www.php.net/)

## Installation
1. From your projects root folder in terminal run:

```bash
    composer require sdtech/file-uploader-laravel
```
2. Publish the packages views, config file, assets, and language files by running the following from your projects root folder:

```bash
    php artisan vendor:publish --tag=fileuploaderlaravel
```

## configuration
1. Go to your config folder, then open "fileuploaderlaravel.php" file
2. here you must add that info or add the info to your .env file .
3.
 ``` bash
    'ALLOWED_IMAGE_TYPE' => env('ALLOWED_IMAGE_TYPE'),
    'MAX_UPLOAD_IMAGE_SIZE' => env('MAX_UPLOAD_IMAGE_SIZE') // default 2048 KB
    'DEFAULT_IMAGE_FORMAT' => env('DEFAULT_IMAGE_FORMAT') // default 'webp',
    'DEFAULT_IMAGE_QUALITY' => env('DEFAULT_IMAGE_QUALITY') // default 80,
    'AWS_ACCESS_KEY_ID' => env('AWS_ACCESS_KEY_ID'),
    'AWS_SECRET_ACCESS_KEY' => env('AWS_SECRET_ACCESS_KEY'),
    'AWS_DEFAULT_REGION' => env('AWS_DEFAULT_REGION'),
    'AWS_BUCKET' => env('AWS_BUCKET'),
    'AWS_URL' => env('AWS_URL')
   ```
4. run this commad 
```bash
   php artisan storage:link
   sudo chmod -R 777 storage
    ```
## Uses
1. We provide a sample code of functionality that will help you to integrate easily

- some functions
``` bash
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
    uploadImageInStorage($reqFile,$path,$old_file="",$allowedImageType=[],$maxSize="", $format='',$width="",$height=null,$quality=null) 

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
    uploadImageInPublic($reqFile,$path,$old_file="",$allowedImageType=[],$maxSize="",$format='',$width="",$height=null,$quality=null) 

    /**
     * upload file in storage folder
     * @param FILE $reqFile (mandetory) uploaded file
     * @param STRING $path (mandetory) file path where upload iamge
     * @param STRING $oldFile (optional) old file name
     * @param ARRAY $allowedImageType  (optional) allowed image type like ["png","webp","jpeg"]
     * @param INT $maxSize (optional) max upload size in KB 1024KB = 1MB
     */
    uploadFileInStorage($reqFile,$path,$old_file="",$allowedImageType=[],$maxSize="")

     /**
     * upload file in public folder
     * @param FILE $reqFile (mandetory) uploaded file
     * @param STRING $path (mandetory) file path where upload iamge
     * @param STRING $oldFile (optional) old file name
     * @param ARRAY $allowedImageType  (optional) allowed image type like ["png","webp","jpeg"]
     * @param INT $maxSize (optional) max upload size in KB 1024KB = 1MB
     */
    ploadFileInPublic($reqFile,$path,$old_file="",$allowedImageType=[],$maxSize="")


    // delete file path
    unlinkFile($path,$oldFile)

    // get file view path for storage folder
    showStorageFileViewPath($path,$fileName)

    // get file view path for public folder
    showFileViewPath($path,$fileName)

    // get allowed image type
    allowedTypes()

    // get allowed image type
    allowedFileExtensions()
``` 
