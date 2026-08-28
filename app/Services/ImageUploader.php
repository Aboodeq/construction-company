<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class ImageUploader
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Resize an uploaded image down to a max width, re-encode it as WebP,
     * and store it on the public disk. Returns the stored relative path.
     */
    public function store(UploadedFile $file, string $directory, int $maxWidth = 1600, int $quality = 82): string
    {
        $image = $this->manager->decodePath($file->getRealPath());
        $image->scaleDown(width: $maxWidth);

        $path = trim($directory, '/').'/'.Str::random(24).'.webp';

        Storage::disk('public')->put($path, (string) $image->encode(new WebpEncoder($quality)));

        return $path;
    }

    /**
     * Delete a previously stored image, if it exists.
     */
    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
