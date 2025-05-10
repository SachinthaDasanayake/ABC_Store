<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    private const PRODUCT_IMAGE_PATH = 'products';

    public function uploadImage(UploadedFile $image): string
    {
        $filename = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path(self::PRODUCT_IMAGE_PATH), $filename);
        return $filename;
    }

    public function deleteImage(string $filename): bool
    {
        $path = public_path(self::PRODUCT_IMAGE_PATH . '/' . $filename);

        if (file_exists($path)) {
            return unlink($path);
        }

        return false;
    }
}