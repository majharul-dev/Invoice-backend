<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;


class CompanyController extends Controller
{
    public function index()
    {
        return Company::with('clients')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:companies,email',
        ]);

        return Company::create($request->all());
    }

    public function show(Company $company)
    {
        return $company->load('clients');
    }

    public function update(Request $request, Company $company)
    {
        $company->update($request->all());
        return response()->json(['message' => 'Updated successfully']);
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
