<?php



namespace App\Http\Controllers\User;

use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use function collect;
use function compact;
use function now;
use function round;
use function view;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     */
    public function home()
    {
        $user = Auth::user();

        if ($user->companies()->count() === 1) {
            $company = $user->companies()->first();

            $companyId = $company->id;

            return Redirect::action('User\HomeController@index', $companyId);
        }

        if ($user->isSuperAdmin()) {
            return Redirect::action('Admin\AdminController@index');
        }

        return view('user.home', compact('user'));
    }

    public function index($companyId)
    {
        $company = Company::findOrFail($companyId);

        $companyTrainings =  $company->trainings;

        $collection = collect([]);

        foreach ($companyTrainings as $training) {
            $collection->push($training->employees->where('company_id', $companyId)->count() === 0 ? 0 : round($training->employees()->certified($training, $companyId)->count() / $training->employees->where('company_id', $companyId)->count() * 100));
        }

        $average = round($collection->avg());

        $companyRegistries = $company->registries;

        $collection = collect();

        foreach ($company->registries as $registry) {
            foreach ($registry->reports->where('company_id', $company->id) as $report) {
                if ($report->expiry_date <= now()) {
                    continue;
                }

                $collection->push($report);
            }
        }

        $validRegistries = $collection->unique('registry_id')->count();

        $registryChartValue = $companyRegistries->count() === 0 ? 0 : round($validRegistries / $companyRegistries->count() * 100);

        return view('user.dashboard', compact('company', 'companyTrainings', 'average', 'companyRegistries', 'registryChartValue'));
    }
}
