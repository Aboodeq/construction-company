<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Services\ImageUploader;

class ProjectImageController extends Controller
{
    public function destroy(Project $project, ProjectImage $image, ImageUploader $images)
    {
        $this->authorize('projects.edit');

        abort_unless($image->project_id === $project->id, 404);

        $images->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'تم حذف الصورة.');
    }
}
