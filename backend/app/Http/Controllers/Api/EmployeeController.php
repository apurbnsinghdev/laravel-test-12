<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeDetail;
use App\Http\Requests\StoreEmployeeRequest;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * List Employees
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        
        $query = Employee::with([
            'department', 
            'employeeDetail'
        ]);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhereHas('employeeDetail', function($q2) use ($search) {
                      $q2->where('designation', 'like', "%$search%")
                         ->orWhere('salary', 'like', "%$search%")
                         ->orWhere('address', 'like', "%$search%");
                  });
            });
        }else{
            $query->orderByDesc('created_at');
        }

        $employees = $query->paginate(20);
        return response()->json($employees);
    }

    /**
     * Create Employee
     *
     * @bodyParam name string required Example: Apurba Sing
     * @bodyParam email string required Example: apurba@gmail.com
     * @bodyParam department_id int required Example: 1
     *
     * @response 201 {"message": "Employee created"}
     */
    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        \DB::beginTransaction();

        try {

            $employee = Employee::create([
                'id' => Str::uuid(),
                'name' => $request->name,
                'email' => $request->email,
                'department_id' => $request->department_id,
            ]);

            $detail = $request->employee_detail;

            // Create employee detail
            EmployeeDetail::create([
                'employee_id' => $employee->id,
                'designation' => $detail['designation'],
                'salary' => $detail['salary'],
                'address' => $detail['address'],
                'joined_date' => $detail['joined_date'],
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
     * Update Employee + Details
     *
     * @urlParam id uuid required Employee ID
     * @bodyParam name string Example: Apurba Sing
     * @bodyParam email string Example: apurba@gmail.com
     * @bodyParam department_id int Example: 2
     * @bodyParam designation string Example: Software Engineer
     * @bodyParam salary number Example: 50000
     * @bodyParam address string Example: House-123, City, Country
     * @bodyParam joined_date date Example: 2025-05-06
     *
     * @response 200 {"message": "Employee updated successfully"}
     */

    public function update(Request $request, $id)
    {
        $employee = Employee::with('employeeDetail')->findOrFail($id);
    
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('employees')->ignore($employee->id),
            ],
            'department_id' => 'sometimes|required|exists:departments,id',
    
            // Nested employee_detail validation
            'employee_detail.designation' => 'sometimes|required|string|max:255',
            'employee_detail.salary' => 'sometimes|required|numeric',
            'employee_detail.address' => 'sometimes|required|string',
            'employee_detail.joined_date' => 'sometimes|required|date',
        ]);
    
        \DB::beginTransaction();
    
        try {
            // Update employee table
            $employee->update($validated);
    
            // Update employee_detail table (if sent)
            if ($request->has('employee_detail')) {
                $detail = $request->input('employee_detail');
    
                $employee->employeeDetail->update([
                    'designation' => $detail['designation'] ?? $employee->employeeDetail->designation,
                    'salary' => $detail['salary'] ?? $employee->employeeDetail->salary,
                    'address' => $detail['address'] ?? $employee->employeeDetail->address,
                    'joined_date' => $detail['joined_date'] ?? $employee->employeeDetail->joined_date,
                ]);
            }
    
            \DB::commit();
    
            return response()->json(['message' => 'Employee and details updated']);
    
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Update failed', 'details' => $e->getMessage()], 500);
        }
    }
     

    /**
     * Delete Employee
     *
     * @urlParam id uuid required Employee ID
     */
    public function destroy($id): JsonResponse
    {
        // Start transaction
        \DB::beginTransaction();

        try {
            $employee = Employee::findOrFail($id);

            EmployeeDetail::where('employee_id', $employee->id)->delete();

            $employee->delete();

            \DB::commit();

            return response()->json(['message' => 'Employee and details deleted successfully']);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'error' => 'Failed to delete employee',
                'details' => $e->getMessage()
            ], 500);
        }
    }

}
