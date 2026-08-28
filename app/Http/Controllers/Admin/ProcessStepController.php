<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProcessStepRequest;
use App\Http\Requests\Admin\UpdateProcessStepRequest;
use App\Models\ProcessStep;

class ProcessStepController extends Controller
{
    public function index()
    {
        $this->authorize('homepage.edit');

        $processSteps = ProcessStep::orderBy('order')->paginate(15);

        return view('admin.process-steps.index', compact('processSteps'));
    }

    public function create()
    {
        $this->authorize('homepage.edit');

        return view('admin.process-steps.create');
    }

    public function store(StoreProcessStepRequest $request)
    {
        ProcessStep::create($request->validated());

        return redirect()
            ->route('admin.process-steps.index')
            ->with('success', 'تمت إضافة الخطوة بنجاح.');
    }

    public function edit(ProcessStep $processStep)
    {
        $this->authorize('homepage.edit');

        return view('admin.process-steps.edit', compact('processStep'));
    }

    public function update(UpdateProcessStepRequest $request, ProcessStep $processStep)
    {
        $processStep->update($request->validated());

        return redirect()
            ->route('admin.process-steps.index')
            ->with('success', 'تم حفظ التغييرات بنجاح.');
    }

    public function destroy(ProcessStep $processStep)
    {
        $this->authorize('homepage.edit');

        $processStep->delete();

        return redirect()
            ->route('admin.process-steps.index')
            ->with('success', 'تم حذف الخطوة.');
    }
}
