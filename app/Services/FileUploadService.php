<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class FileUploadService
{
    protected $disk;

    public function __construct($disk = 'public')
    {
        $this->disk = $disk; // change to s3 later easily
    }

    /**
     * Upload file with full control
     */
    public function upload($file, array $options = [])
    {
        $path = $options['path'] ?? 'uploads';
        $visibility = $options['visibility'] ?? 'public';
        $allowed = $options['allowed_types'] ?? [];
        $maxSize = $options['max_size'] ?? 2048; // KB

        // Validate type
        if (!empty($allowed) && !in_array($file->getClientOriginalExtension(), $allowed)) {
            throw new \Exception('Invalid file type');
        }

        // Validate size
        if ($file->getSize() > ($maxSize * 1024)) {
            throw new \Exception('File too large');
        }

        // Custom filename
        $filename = $this->generateFileName($file);

        // Store file
        $filePath = $file->storeAs($path, $filename, [
            'disk' => $this->disk,
            'visibility' => $visibility
        ]);

        return [
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'file_type' => $file->getClientMimeType(),
            'url' => $this->getUrl($filePath),
        ];
    }

    /**
     * Delete file
     */
    public function delete($filePath)
    {
        return Storage::disk($this->disk)->delete($filePath);
    }

    /**
     * Get file URL
     */
    public function getUrl($filePath)
    {
        return Storage::disk($this->disk)->url($filePath);
        // return Storage::url($filePath);
    }

    /**
     * Replace file (delete old + upload new)
     */
    public function replace($oldPath, $file, array $options = [])
    {
        $this->delete($oldPath);
        return $this->upload($file, $options);
    }

    /**
     * Generate unique filename
     */
    protected function generateFileName($file)
    {
        return Str::uuid() . '.' . $file->getClientOriginalExtension();
    }
}