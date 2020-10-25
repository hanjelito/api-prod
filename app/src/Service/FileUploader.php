<?php

namespace  App\Service;

use League\Flysystem\FilesystemInterface;

class FileUploader {

    private $defaultStorage;

    public function __construct(FilesystemInterface $defaultStorage)
    {
        $this->defaultStorage = $defaultStorage;
    }

    public function uploadBase64File(string $base64file): string
    {
        $extension = explode('/', mime_content_type($base64file))[1];
        $data = explode(',', $base64file);
        $filename = sprintf('/products/%s.%s', uniqid('book_', true), $extension);
        $this->defaultStorage ->write($filename, base64_decode($data[1]));
        return $filename;
    }
}