<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyCustomer;
use Illuminate\Http\Request;

class MonthlyCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $customers = MonthlyCustomer::query()
            ->when($search, function ($query, $search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%");
            })
            ->orderBy('first_name')
            ->paginate(10);

        return view('pages.admin.monthly-customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MonthlyCustomer $monthlyCustomer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MonthlyCustomer $monthlyCustomer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MonthlyCustomer $monthlyCustomer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MonthlyCustomer $monthlyCustomer)
    {
        //
    }
}
