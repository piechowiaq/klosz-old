<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Department;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Position;
use App\Registry;
use App\Training;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View as IlluminateView;

use function redirect;
use function route;
use function view;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return Factory|IlluminateView
     */
    public function index()
    {
        $this->authorize('update');

        $companies = Company::getAll();

        return view('admin.companies.index', ['companies' => $companies]);
    }

    /**
     * @return Factory|IlluminateView
     */
    public function create()
    {
        $this->authorize('update');

        $company     = new Company();
        $departments = Department::getAll();
        $registries  = Registry::getAll();

        return view('admin.companies.create', ['company' => $company, 'departments' => $departments, 'registries' => $registries]);
    }

    /**
     * @return  RedirectResponse|Redirector
     *
     * @throws Exception
     */
    public function store(StoreCompanyRequest $request)
    {
        $this->authorize('update');

        $company = new Company();
        $company->setName($request->get('name'));
        $company->save();

        $registries = Registry::getRegistriesById($request->get('registry_id'));
        if ($registries->count() === 0) {
            throw new Exception('No registry found!');
        }

        $company->setRegistries($registries);

        $departments = Department::getDepartmentsById($request->get('department_id'));
        if ($departments->count() === 0) {
            throw new Exception('No department found!');
        }

        $company->setDepartments($departments);

        /**
         * @var Collection|Position[]
         */
        $positions = $departments->flatMap(static function (Department $department) {
            return $department->getPositions();
        });

        $company->setPositions($positions);

        /**
         * @var Collection|Training[]
         */
        $trainings = $positions->flatMap(static function (Position $position) {
            return $position->getTrainings();
        });

        $company->setTrainings($trainings);

        return redirect(route('admin.companies.show', ['company' => $company]));
    }

    /**
     * @return Factory|IlluminateView
     */
    public function show(Company $company)
    {
        $this->authorize('update');

        return view('admin.companies.show', ['company' => $company]);
    }

    /**
     * @return Factory|IlluminateView
     */
    public function edit(Company $company)
    {
        $this->authorize('update');

        $departments = Department::getAll();

        $registries = Registry::getAll();

        return view('admin.companies.edit', ['company' => $company, 'departments' => $departments, 'registries' => $registries]);
    }

    /**
     * @return  RedirectResponse|Redirector
     *
     * @throws Exception
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $this->authorize('update');

        $company->setName($request->get('name'));
        $company->save();

        $registries = Registry::getRegistriesById($request->get('registry_id'));
        if ($registries->count() === 0) {
            throw new Exception('No registry found!');
        }

        $company->setRegistries($registries);

        $departments = Department::getDepartmentsById($request->get('department_id'));
        if ($departments->count() === 0) {
            throw new Exception('No department found!');
        }

        $company->setDepartments($departments);

        /**
         * @var Collection|Position[]
         */
        $positions = $departments->flatMap(static function (Department $department) {
            return $department->getPositions();
        });

        $company->setPositions($positions);

        /**
         * @var Collection|Training[]
         */
        $trainings = $positions->flatMap(static function (Position $position) {
            return $position->getTrainings();
        });

        $company->setTrainings($trainings);

        return redirect(route('admin.companies.show', ['company' => $company]));
    }

    /**
     * @return  RedirectResponse|Redirector
     */
    public function destroy(Company $company)
    {
        $this->authorize('update');

        $company->delete();

        return redirect(route('admin.companies.index'));
    }
}
