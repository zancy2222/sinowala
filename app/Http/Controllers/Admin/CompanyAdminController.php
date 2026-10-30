<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminListRequest;
use App\Http\Requests\ConfirmNameRequest;
use App\Models\AuditLog;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CompanyAdminController extends Controller
{
    public function index(AdminListRequest $request): View
    {
        $companies = Company::query()
            ->withCount('memberships')
            ->when($request->validated('q'), fn ($q, $term) => $q->where('name', 'like', '%'.$term.'%'))
            ->when($request->validated('status'), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.companies', ['companies' => $companies]);
    }

    /** Approve a pending company, or reactivate a suspended one. */
    public function approve(Company $company): RedirectResponse
    {
        $this->authorize('approve', $company);
        $action = $company->isPending() ? 'company.approved' : 'company.reactivated';

        $company->status = Company::ACTIVE;
        $company->approved_at ??= now();
        $company->save();
        AuditLog::record($action, $company, $company->name);

        return back()->with('toast', __('admin.companies.toast.'.$action, ['name' => $company->name]));
    }

    public function suspend(Company $company): RedirectResponse
    {
        $this->authorize('suspend', $company);
        $company->status = Company::SUSPENDED;
        $company->save();
        AuditLog::record('company.suspended', $company, $company->name);

        return back()->with('toast', __('admin.companies.toast.company.suspended', ['name' => $company->name]));
    }

    /** Members become personal users again; their calendars stay. */
    public function destroy(ConfirmNameRequest $request, Company $company, CompanyService $companies): RedirectResponse
    {
        $this->authorize('destroyAsAdmin', $company);

        if (! $request->confirms($company->name)) {
            throw ValidationException::withMessages(['confirm_name' => __('app.company.confirm_name_mismatch')])
                ->errorBag('delete_'.$company->id);
        }

        AuditLog::record('company.deleted', $company, $company->name.' ('.$company->memberships()->count().')');
        $companies->delete($company);

        return redirect()->route('admin.companies')->with('toast', __('admin.companies.toast.company.deleted', ['name' => $company->name]));
    }
}
