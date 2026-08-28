<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceImage;
use App\Services\ImageUploader;

class ServiceImageController extends Controller
{
    public function destroy(Service $service, ServiceImage $image, ImageUploader $images)
    {
        $this->authorize('services.edit');

        abort_unless($image->service_id === $service->id, 404);

        $images->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'تم حذف الصورة.');
    }
}
