<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeDetail;
use App\Http\Requests\StoreEmployeeRequest;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    /**
     * List Employees
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $employees = Employee::with('department')->paginate(20);

        return response()->json($employees);
    }

    /**
     * Create Employee
     *
     * @bodyParam name string required Example: John Doe
     * @bodyParam email string required Example: john@example.com
     * @bodyParam department_id int required Example: 1
     *
     * @response 201 {"message": "Employee created"}
     */
    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        // Start transaction
        \DB::beginTransaction();

        try {
            // Create employee
            $employee = Employee::create([
                'id' => Str::uuid(),
                'name' => $request->name,
                'email' => $request->email,
                'department_id' => $request->department_id,
            ]);

            // Create employee detail
            EmployeeDetail::create([
                'employee_id' => $employee->id,
                'designation' => $request->designation,
                'salary' => $request->salary,
                'address' => $request->address,
                'joined_date' => $request->joined_date,
            ]);

            \DB::commit();

            return response()->json(['message' => 'Employee created successfully', 'data' => $employee], 201);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Failed to create employee', 'details' => $e->getMessage()], 500);
        }
    }
    /**
     * Show Employee
     *
     * @urlParam id uuid required Employee ID
     */
    public function show($id)
    {
        $employee = Employee::with('department')->findOrFail($id);

        return response()->json($employee);
    }

    /**
     * Update Employee
     *
     * @urlParam id uuid required Employee ID
     * @bodyParam name string Example: Apurba Sing
     * @bodyParam email string Example: apurba@gmail.com
     * @bodyParam department_id int Example: 2
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('employees')->ignore($employee->id),
            ],
            'department_id' => 'sometimes|required|exists:departments,id',
        ]);

        $employee->update($validated);

        return response()->json(['message' => 'Employee updated']);
    }

    /**
     * Delete Employee
     *
     * @urlParam id uuid required Employee ID
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        $employee->delete();

        return response()->json(['message' => 'Employee deleted']);
    }
}
