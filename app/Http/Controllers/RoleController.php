<?php

namespace App\Http\Controllers;



use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('update');

        $roles = Role::all();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $this->authorize('update');

        $role = new Role();

        return view('admin.roles.create', compact( 'role' ));
    }

    public function store()
    {
        $this->authorize('update');

        $role = Role::create([
            'name' => request('name'),
            'description' => request('description')
        ]);

        return redirect($role->path());
    }

    public function show()
    {

        $this->authorize('update');

    }

    public function update(Role $role)
    {
        $this->authorize('update');

        $role->update($this->validateRequest());

        return redirect($role->path());
    }

    public function edit(Role $role)
    {
        $this->authorize('update');
    }

    public function destroy(Role $role)
    {
        $this->authorize('update');

        $role->delete();

        return redirect('/admin/roles');
    }
    protected function validateRequest()
    {
        return request()->validate([
            'name'=> 'sometimes|required',
            'description'=> 'sometimes|required',

        ]);
    }






}
