<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Video\X264;

trait FileUploadTrait
{
    
    public function uploadFile(UploadedFile $file, $folder = 'uploads', $disk = null)
{
    $disk = $disk ?: config('filesystems.default');

    $extension = strtolower($file->getClientOriginalExtension());
    $mimeType  = $file->getMimeType();

    $fileName = date('YmdHis') . '_' . Str::random(12) . '.' . $extension;

    try {

        /*
        |--------------------------------------------------------------------------
        | IMAGE UPLOAD
        |--------------------------------------------------------------------------
        */
        if (strpos($mimeType, 'image/') === 0) {

            $path = $folder . '/' . $fileName;

            $image = Image::make($file);

            $image->resize(
                1920,
                null,
                function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                }
            );

            $encodedImage = (string) $image->encode(
                $extension === 'png' ? 'png' : 'jpg',
                75
            );

            Storage::disk($disk)->put($path, $encodedImage);

            return [
                'success' => true,
                'disk' => $disk,
                'file_name' => $fileName,
                'path' => $path,
                'url' => $this->getFileUrl($path, $disk),
                'mime_type' => $mimeType,
                'extension' => $extension,
                'size' => Storage::disk($disk)->size($path),
                'thumbnail' => null,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | VIDEO UPLOAD
        |--------------------------------------------------------------------------
        */
        elseif (strpos($mimeType, 'video/') === 0) {

            $path = $folder . '/' . $fileName;

            $thumbnailName = pathinfo($fileName, PATHINFO_FILENAME) . '.jpg';
            $thumbnailPath = $folder . '/thumbnails/' . $thumbnailName;

            // Store temporarily
            $tempPath = $file->store('temp');

            // Convert & upload video
            FFMpeg::fromDisk('local')
                ->open($tempPath)
                ->export()
                ->toDisk($disk)
                ->inFormat(
                    (new X264('aac'))
                        ->setKiloBitrate(1000)
                )
                ->save($path);

            // Generate thumbnail at 1 second
            FFMpeg::fromDisk('local')
                ->open($tempPath)
                ->getFrameFromSeconds(1)
                ->export()
                ->toDisk($disk)
                ->save($thumbnailPath);

            // Delete temp file
            Storage::disk('local')->delete($tempPath);



            return [
                'success' => true,
                'disk' => $disk,

                'file_name' => $fileName,
                'path' => $path,
                'url' => $this->getFileUrl($path, $disk),
                'mime_type' => $mimeType,
                'extension' => $extension,
                'size' => Storage::disk($disk)->size($path),

                'thumbnail' => [
                    'file_name' => $thumbnailName,
                    'path' => $thumbnailPath,
                    'url' => $this->getFileUrl($thumbnailPath, $disk),
                    'mime_type' => 'image/jpeg',
                    'extension' => 'jpg',
                    'size' => Storage::disk($disk)->size($thumbnailPath),
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | OTHER FILES
        |--------------------------------------------------------------------------
        */
        else {

            $path = $folder . '/' . $fileName;

            Storage::disk($disk)->put(
                $path,
                fopen($file->getRealPath(), 'r')
            );

            return [
                'success' => true,
                'disk' => $disk,
                'file_name' => $fileName,
                'path' => $path,
                'url' => $this->getFileUrl($path, $disk),
                'mime_type' => $mimeType,
                'extension' => $extension,
                'size' => Storage::disk($disk)->size($path),
                'thumbnail' => null,
            ];
        }
    } catch (Exception $e) {

        return [
            'success' => false,
            'message' => $e->getMessage(),
        ];
    }
}

    /**
     * Upload multiple files
     */
    public function uploadMultipleFiles($files, $folder = 'uploads', $disk = null)
    {
        $results = [];

        foreach ($files as $file) {
            $results[] = $this->uploadFile($file, $folder, $disk);
        }

        return $results;
    }

    /**
     * Delete file
     */
    public function deleteFile($path, $disk = null)
    {
        $disk = $disk ?: config('filesystems.default');

        if (!Storage::disk($disk)->exists($path)) {
            return false;
        }

        return Storage::disk($disk)->delete($path);
    }

    /**
     * Check file exists
     */
    public function fileExists($path, $disk = null)
    {
        $disk = $disk ?: config('filesystems.default');

        return Storage::disk($disk)->exists($path);
    }

    /**
     * Get file URL (FIXED FOR LARAVEL 8)
     */
    public function getFileUrl($path, $disk = null)
    {
        $disk = $disk ?: config('filesystems.default');

        // LOCAL STORAGE
        if ($disk === 'public') {
            return asset('storage/' . $path);
        }

        // S3 STORAGE
        if ($disk === 's3') {

            return 'https://' .
                env('AWS_BUCKET') .
                '.s3.' .
                env('AWS_DEFAULT_REGION') .
                '.amazonaws.com/' .
                $path;
        }

        return $path;
    }
}
