<?php

namespace App\Services;

class ImageService
{
    public static function compressToWebP(string $sourcePath, ?string $destPath = null, int $maxDimension = 1600, int $quality = 80): ?string
    {
        if (!file_exists($sourcePath)) {
            return null;
        }

        $info = getimagesize($sourcePath);
        if (!$info) {
            return null;
        }

        $mime = $info['mime'];
        $width = $info[0];
        $height = $info[1];

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = @imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $image = @imagecreatefromgif($sourcePath);
                break;
            case 'image/webp':
                $image = @imagecreatefromwebp($sourcePath);
                break;
            default:
                return null;
        }

        if (!$image) {
            return null;
        }

        if ($width > $maxDimension || $height > $maxDimension) {
            if ($width > $height) {
                $newWidth = $maxDimension;
                $newHeight = (int) round(($height * $maxDimension) / $width);
            } else {
                $newHeight = $maxDimension;
                $newWidth = (int) round(($width * $maxDimension) / $height);
            }

            $newImage = imagecreatetruecolor($newWidth, $newHeight);

            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);

            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $newImage;
        }

        if (!$destPath) {
            $destPath = preg_replace('/\.[^.]+$/', '.webp', $sourcePath);
        }

        $success = imagewebp($image, $destPath, $quality);
        imagedestroy($image);

        if ($success) {
            if ($sourcePath !== $destPath && file_exists($sourcePath)) {
                @unlink($sourcePath);
            }
            return $destPath;
        }

        return null;
    }
}
