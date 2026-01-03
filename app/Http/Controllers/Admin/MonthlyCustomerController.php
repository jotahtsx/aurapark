<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MonthlyCustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $customers = MonthlyCustomer::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    // Removendo caracteres especiais da busca caso o usuário digite CPF com ponto/traço
                    $cleanSearch = preg_replace('/\D/', '', $search);
                    
                    $q->where('first_name', 'ilike', "%{$search}%")
                        ->orWhere('last_name', 'ilike', "%{$search}%")
                        ->orWhere('document_number', 'like', "%{$cleanSearch}%");
                });
            })
            ->orderBy('first_name')
            ->paginate(10);

        return view('pages.admin.monthly-customers.index', compact('customers'));
    }

    private function sanitizeRequest(Request $request)
    {
        // Importante: Usamos o merge para substituir os valores formatados por apenas números
        $request->merge([
            'cpf'      => preg_replace('/\D/', '', $request->cpf),
            'phone'    => preg_replace('/\D/', '', $request->phone),
            'zip_code' => preg_replace('/\D/', '', $request->zip_code),
        ]);
    }

    public function store(Request $request)
    {
        // Sanitizar ANTES da validação para o unique:monthly_customers funcionar corretamente
        $this->sanitizeRequest($request);

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:monthly_customers,email',
            'cpf'       => 'required|string|size:11|unique:monthly_customers,document_number',
            'due_day'   => 'required|integer|between:1,31',
            'is_active' => 'required|in:active,inactive',
        ]);

        try {
            $nameParts = explode(' ', $request->name, 2);
            $firstName = $nameParts[0];
            $lastName  = $nameParts[1] ?? '';

            $data = [
                'first_name'      => $firstName,
                'last_name'       => $lastName,
                'document_number' => $request->cpf,
                'id_card'         => $request->id_card,
                'phone'           => $request->phone ?? '',
                'zip_code'        => $request->zip_code ?? '',
                'address'         => $request->address ?? '',
                'address_number'  => 'S/N',
                'neighborhood'    => '',
                'city'            => '',
                'state'           => 'PI',
                'due_day'         => $request->due_day,
                'is_active'       => $request->is_active === 'active',
                'birth_date'      => now()->format('Y-m-d'), 
                'email'           => $request->email,
            ];

            MonthlyCustomer::create($data);

            return redirect()->route('admin.monthly_customers.index')
                ->with('success', "Mensalista {$firstName} cadastrado!");
        } catch (\Exception $e) {
            Log::error("Erro ao salvar: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Falha ao salvar no banco de dados.');
        }
    }

    public function update(Request $request, $id)
    {
        $this->sanitizeRequest($request);
        $customer = MonthlyCustomer::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:monthly_customers,email,' . $id,
            'cpf'       => 'required|string|size:11|unique:monthly_customers,document_number,' . $id,
            'due_day'   => 'required|integer|between:1,31',
            'is_active' => 'required|in:active,inactive',
        ]);

        try {
            $nameParts = explode(' ', $request->name, 2);

            $data = [
                'first_name'      => $nameParts[0],
                'last_name'       => $nameParts[1] ?? '',
                'email'           => $request->email,
                'document_number' => $request->cpf,
                'id_card'         => $request->id_card,
                'phone'           => $request->phone,
                'zip_code'        => $request->zip_code,
                'address'         => $request->address,
                'due_day'         => $request->due_day,
                'is_active'       => $request->is_active === 'active',
            ];

            $customer->update($data);
            return redirect()->route('admin.monthly_customers.index')->with('success', 'Usuário atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Falha ao atualizar.');
        }
    }

    public function destroy($id)
    {
        MonthlyCustomer::destroy($id);
        return back()->with('success', 'Deletado com sucesso!');
    }
}