<?php

use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Employee::class, 10)->create()->each(function ($employee) {
            $employee->trainings()->save(factory(App\Training::class)->make());
            $employee->departments()->save(factory(App\Department::class)->make());
            $employee->positions()->save(factory(App\Position::class)->make());
        });
    }
}
