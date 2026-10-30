<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyJoinRequest;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Http\Requests\ConfirmNameRequest;
use App\Services\CompanyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/** Create / join / leave / rename / delete a company from Settings. */
class CompanyController extends Controller
{
    public function __construct(private CompanyService $companies) {}

    private function toCompanySection(): RedirectResponse
    {
        return redirect()->to(route('settings').'#company');
    }

    public function store(CompanyStoreRequest $request): RedirectResponse
    {
        $this->companies->create($request->user(), $request->validated('company_name'));

        return $this->toCompanySection()->with('toast', __('app.company.created'));
    }

    public function join(CompanyJoinRequest $request): RedirectResponse
    {
        $company = $this->companies->findJoinable(code: $request->validated('code'));

        if (! $company) {
            throw ValidationException::withMessages(['code' => __('app.company.invalid_code')]);
        }

        $this->companies->join($request->user(), $company);

        return redirect()->route('app.home', ['tab' => 'team'])->with('toast', __('app.company.joined', ['company' => $company->name]));
    }

    public function leave(Request $request): RedirectResponse
    {
        $membership = $request->user()->membership;
        abort_unless($membership, 404);
        $this->authorize('leave', $membership);

        $this->companies->leave($request->user());

        return $this->toCompanySection()->with('toast', __('app.company.left'));
    }

    public function update(CompanyUpdateRequest $request): RedirectResponse
    {
        $request->user()->company()->update(['name' => $request->validated('name')]);

        return $this->toCompanySection()->with('toast', __('app.saved'));
    }

    public function destroy(ConfirmNameRequest $request): RedirectResponse
    {
        $company = $request->user()->company();
        abort_unless($company, 404);
        $this->authorize('delete', $company);

        if (! $request->confirms($company->name)) {
            throw ValidationException::withMessages(['confirm_name' => __('app.company.confirm_name_mismatch')]);
        }

        $this->companies->delete($company);

        return $this->toCompanySection()->with('toast', __('app.company.deleted'));
    }
}
