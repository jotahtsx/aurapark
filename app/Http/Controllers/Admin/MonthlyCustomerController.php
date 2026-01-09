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
                // Limpa o CPF digitado para buscar apenas os números no banco
                $cleanSearch = preg_replace('/\D/', '', $search);

                $query->where(function ($q) use ($search, $cleanSearch) {
                    $q->where('first_name', 'ilike', "%{$search}%")
                        ->orWhere('last_name', 'ilike', "%{$search}%")
                        ->orWhere('email', 'ilike', "%{$search}%");

                    // Se o usuário digitou números, busca no CPF também
                    if (!empty($cleanSearch)) {
                        $q->orWhere('document_number', 'like', "%{$cleanSearch}%");
                    }
                });
            })
            ->orderBy('first_name', 'asc')
            ->paginate(10)
            ->withQueryString(); // Isso aqui mantém o termo da busca quando você trocar de página

        return view('pages.admin.monthly-customers.index', compact('customers'));
    }

    private function sanitizeRequest(Request $request)
    {
        // Limpa formatação de CPF, Telefone e CEP antes de validar/salvar
        $request->merge([
            'cpf'      => preg_replace('/\D/', '', $request->cpf),
            'phone'    => preg_replace('/\D/', '', $request->phone),
            'zip_code' => preg_replace('/\D/', '', $request->zip_code),
        ]);
    }

    public function store(Request $request)
    {
        $this->sanitizeRequest($request);

        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:monthly_customers,email',
            'cpf'             => 'required|string|size:11|unique:monthly_customers,document_number',
            'due_day'         => 'required|integer|between:1,31',
            'is_active'       => 'required|in:active,inactive',
            'city'            => 'required|string', // Obrigatório para evitar erro do banco
            'address_number'  => 'required|string', // Obrigatório para evitar erro do banco
        ]);

        try {
            $nameParts = explode(' ', $request->name, 2);

            MonthlyCustomer::create([
                'first_name'      => $nameParts[0],
                'last_name'       => $nameParts[1] ?? '',
                'email'           => $request->email,
                'document_number' => $request->cpf,
                'phone'           => $request->phone,
                'zip_code'        => $request->zip_code,
                'address'         => $request->address,
                'address_number'  => $request->address_number, // Pegando do formulário agora!
                'neighborhood'    => $request->neighborhood,    // Pegando do formulário agora!
                'city'            => $request->city,            // Pegando do formulário agora!
                'state'           => strtoupper($request->state),
                'complement'      => $request->complement,
                'due_day'         => $request->due_day,
                'is_active'       => $request->is_active === 'active',
                'birth_date'      => now()->format('Y-m-d'), // Data padrão para a Migration não reclamar
            ]);

            return redirect()->route('admin.monthly_customers.index')
                ->with('success', "Mensalista cadastrado com sucesso!");
        } catch (\Exception $e) {
            Log::error("Erro ao salvar: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Falha ao salvar: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $this->sanitizeRequest($request);
        $customer = MonthlyCustomer::findOrFail($id);

        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:monthly_customers,email,' . $id,
            'cpf'             => 'required|string|size:11|unique:monthly_customers,document_number,' . $id,
            'due_day'         => 'required|integer|between:1,28',
            'is_active'       => 'required|in:active,inactive',
            'city'            => 'required|string',
            'address_number'  => 'required|string',
        ]);

        try {
            $nameParts = explode(' ', $request->name, 2);

            $customer->update([
                'first_name'      => $nameParts[0],
                'last_name'       => $nameParts[1] ?? '',
                'email'           => $request->email,
                'document_number' => $request->cpf,
                'phone'           => $request->phone,
                'due_day'         => $request->due_day,
                'is_active'       => $request->is_active === 'active',
                'zip_code'        => $request->zip_code,
                'address'         => $request->address,
                'neighborhood'    => $request->neighborhood,
                'address_number'  => $request->address_number,
                'complement'      => $request->complement,
                'city'            => $request->city,
                'state'           => $request->state,
            ]);

            return redirect()->route('admin.monthly_customers.index')->with('success', 'Mensalista atualizado!');
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Falha ao atualizar.');
        }
    }

    public function destroy($id)
    {
        MonthlyCustomer::destroy($id);
        return redirect()->route('admin.monthly_customers.index')->with('success', 'Deletado com sucesso!');
    }
}
