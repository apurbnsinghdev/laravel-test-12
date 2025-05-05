<?php

namespace Database\Seeders;

#use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeDetail;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Insert 10 Departments
        $departments = Department::factory(10)->create();

        //Insert 100,000 Employees with EmployeeDetails
        foreach (range(1, 10) as $i) {
            Employee::factory(100000)
            ->create()
            ->each(function ($employee) {
                $employee->detail()->create(
                    \App\Models\EmployeeDetail::factory()->make()->toArray()
                );
            });
        }
        
    }
}
