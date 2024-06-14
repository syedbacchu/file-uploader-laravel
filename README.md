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
    'TEST' => env('TEST') ?? "",
   ```

## Uses
1. We provide a sample code of functionality that will help you to integrate easily

