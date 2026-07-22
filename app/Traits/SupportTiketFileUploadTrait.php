<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Video\X264;

trait SupportTiketFileUploadTrait
{
    /**
     * Upload directly from an UploadedFile (controller usage).
     */
    public function uploadFile(UploadedFile $file, $folder = 'uploads', $disk = null)
    {
        return $this->performUpload(
            $file,
            $folder,
            $disk,
            $file->getClientOriginalExtension(),
            $file->getMimeType(),
            false
        );
    }

    /**
     * Upload from a temporary file path (queue usage).
     */
    public function uploadTempFile(
        string $tempPath,
        string $originalName,
        string $mimeType,
        $folder = 'uploads',
        $disk = null
    ) {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $file = new File(storage_path('app/' . $tempPath));

        return $this->performUpload(
            $file,
            $folder,
            $disk,
            $extension,
            $mimeType,
            true,
            $tempPath
        );
    }

    private function performUpload(
        $file,
        $folder,
        $disk,
        $extension,
        $mimeType,
        bool $deleteTemp = false,
        ?string $tempPath = null
    ) {
        $disk = $disk ?: config('filesystems.default');

        $fileName = date('YmdHis') . '_' . Str::random(12) . '.' . strtolower($extension);

        try {
            if (str_starts_with($mimeType, 'image/')) {

                $path = $folder . '/' . $fileName;

                $image = Image::make($file)
                    ->resize(1920, null, function ($c) {
                        $c->aspectRatio();
                        $c->upsize();
                    });

                Storage::disk($disk)->put(
                    $path,
                    (string) $image->encode($extension === 'png' ? 'png' : 'jpg', 75)
                );

                return $this->response($disk, $path, $fileName, $mimeType, $extension, null);
            }

            if (str_starts_with($mimeType, 'video/')) {

                $path = $folder . '/' . $fileName;
                $thumbnailName = pathinfo($fileName, PATHINFO_FILENAME) . '.jpg';
                $thumbnailPath = $folder . '/thumbnails/' . $thumbnailName;

                $localPath = $deleteTemp ? $tempPath : $file->store('temp');

                FFMpeg::fromDisk('local')
                    ->open($localPath)
                    ->export()
                    ->toDisk($disk)
                    ->inFormat((new X264('aac'))->setKiloBitrate(1000))
                    ->save($path);

                FFMpeg::fromDisk('local')
                    ->open($localPath)
                    ->getFrameFromSeconds(1)
                    ->export()
                    ->toDisk($disk)
                    ->save($thumbnailPath);

                if (!$deleteTemp) {
                    Storage::disk('local')->delete($localPath);
                }

                return $this->response(
                    $disk,
                    $path,
                    $fileName,
                    $mimeType,
                    $extension,
                    [
                        'file_name' => $thumbnailName,
                        'path' => $thumbnailPath,
                        'url' => $this->getFileUrl($thumbnailPath, $disk),
                        'mime_type' => 'image/jpeg',
                        'extension' => 'jpg',
                        'size' => Storage::disk($disk)->size($thumbnailPath),
                    ]
                );
            }

            $path = $folder . '/' . $fileName;
            Storage::disk($disk)->put($path, fopen($file->getRealPath(), 'r'));

            return $this->response($disk, $path, $fileName, $mimeType, $extension, null);
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        } finally {
            if ($deleteTemp && $tempPath && Storage::disk('local')->exists($tempPath)) {
                Storage::disk('local')->delete($tempPath);
            }
        }
    }

    private function response($disk, $path, $fileName, $mimeType, $extension, $thumbnail)
    {
        return [
            'success' => true,
            'disk' => $disk,
            'file_name' => $fileName,
            'path' => $path,
            'url' => $this->getFileUrl($path, $disk),
            'mime_type' => $mimeType,
            'extension' => $extension,
            'size' => Storage::disk($disk)->size($path),
            'thumbnail' => $thumbnail,
        ];
    }

    public function uploadMultipleFiles($files, $folder = 'uploads', $disk = null)
    {
        return collect($files)
            ->map(fn($file) => $this->uploadFile($file, $folder, $disk))
            ->toArray();
    }

    public function deleteFile($path, $disk = null)
    {
        $disk = $disk ?: config('filesystems.default');
        return Storage::disk($disk)->exists($path)
            ? Storage::disk($disk)->delete($path)
            : false;
    }

    public function fileExists($path, $disk = null)
    {
        return Storage::disk($disk ?: config('filesystems.default'))->exists($path);
    }

    public function getFileUrl($path, $disk = null)
    {
        $disk = $disk ?: config('filesystems.default');

        if ($disk === 'public') {
            return asset('storage/' . $path);
        }

        if ($disk === 's3') {
            return 'https://' . env('AWS_BUCKET') . '.s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . $path;
        }

        return $path;
    }
}
