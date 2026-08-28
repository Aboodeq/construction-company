<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCompanyStatisticRequest;
use App\Http\Requests\Admin\UpdateCompanyStatisticRequest;
use App\Models\CompanyStatistic;

class CompanyStatisticController extends Controller
{
    public function index()
    {
        $this->authorize('homepage.edit');

        $statistics = CompanyStatistic::orderBy('order')->paginate(15);

        return view('admin.company-statistics.index', compact('statistics'));
    }

    public function create()
    {
        $this->authorize('homepage.edit');

        return view('admin.company-statistics.create');
    }

    public function store(StoreCompanyStatisticRequest $request)
    {
        CompanyStatistic::create($request->validated());

        return redirect()
            ->route('admin.company-statistics.index')
            ->with('success', 'تمت إضافة الإحصائية بنجاح.');
    }

    public function edit(CompanyStatistic $companyStatistic)
    {
        $this->authorize('homepage.edit');

        return view('admin.company-statistics.edit', compact('companyStatistic'));
    }

    public function update(UpdateCompanyStatisticRequest $request, CompanyStatistic $companyStatistic)
    {
        $companyStatistic->update($request->validated());

        return redirect()
            ->route('admin.company-statistics.index')
            ->with('success', 'تم حفظ التغييرات بنجاح.');
    }

    public function destroy(CompanyStatistic $companyStatistic)
    {
        $this->authorize('homepage.edit');

        $companyStatistic->delete();

        return redirect()
            ->route('admin.company-statistics.index')
            ->with('success', 'تم حذف الإحصائية.');
    }
}
