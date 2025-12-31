<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonthlyCustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $customers = MonthlyCustomer::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('cpf', 'like', "%{$search}%")
                    ->orWhere('vehicle_plate', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10);

        return view('pages.admin.monthly-customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        // 1. Validação pesada dos 17 campos
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'nullable|email|unique:monthly_customers,email',
            'phone'          => 'nullable|string|max:20',
            'cpf'            => 'required|string|unique:monthly_customers,cpf',
            'zip_code'       => 'nullable|string|max:10',
            'address'        => 'nullable|string|max:255',
            'number'         => 'nullable|string|max:20',
            'neighborhood'   => 'nullable|string|max:100',
            'city'           => 'nullable|string|max:100',
            'state'          => 'nullable|string|max:2',
            'vehicle_model'  => 'required|string|max:100',
            'vehicle_plate'  => 'required|string|unique:monthly_customers,vehicle_plate',
            'vehicle_color'  => 'nullable|string|max:50',
            'monthly_fee'    => 'required|numeric|min:0',
            'due_day'        => 'required|integer|between:1,31',
            'status'         => 'required|in:active,inactive',
        ]);

        try {
            // 2. Criação no Banco
            MonthlyCustomer::create($validated);

            return redirect()
                ->route('admin.monthly-customers.index')
                ->with('success', 'Mensalista ' . $request->name . ' cadastrado com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao salvar: ' . $e->getMessage());
        }
    }

    public function destroy(MonthlyCustomer $monthlyCustomer)
    {
        try {
            $monthlyCustomer->delete();
            return redirect()->back()->with('success', 'Mensalista removido com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível excluir o registro.');
        }
    }

    // Os métodos show, edit e update faremos conforme a necessidade da interface!
}