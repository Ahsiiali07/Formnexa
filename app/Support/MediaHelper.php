<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

class MediaHelper
{
    /**
     * Convert an uploaded image file into a base64 data URI.
     */
    public static function uploadedImageToDataUri(UploadedFile $file): string
    {
        $mime = $file->getMimeType() ?: 'image/png';
        $bytes = (string) file_get_contents($file->getRealPath());

        return 'data:' . $mime . ';base64,' . base64_encode($bytes);
    }
}
