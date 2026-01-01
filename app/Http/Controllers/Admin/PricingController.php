<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PricingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $pricings = Pricing::query()
            ->when($search, function ($query, $search) {
                // Usamos 'ilike' para PostgreSQL (busca insensível a maiúsculas/minúsculas)
                return $query->where('category', 'ilike', "%{$search}%")
                             ->orWhere('id', 'like', "%{$search}%");
            })
            ->orderBy('category', 'asc')
            ->get();

        return view('pages.admin.pricings.index', compact('pricings'));
    }

    public function store(Request $request)
    {
        // 1. Limpeza rigorosa (Igual ao Update para não dar erro no Postgres)
        if ($request->has('hourly_price') || $request->has('monthly_price')) {
            $request->merge([
                'hourly_price' => $request->hourly_price 
                    ? (float) str_replace(',', '.', str_replace('.', '', $request->hourly_price)) 
                    : 0,
                'monthly_price' => $request->monthly_price 
                    ? (float) str_replace(',', '.', str_replace('.', '', $request->monthly_price)) 
                    : 0,
            ]);
        }

        // 2. Validação
        $validated = $request->validate([
            'category'      => 'required|string|max:255|unique:pricings,category',
            'hourly_price'  => 'required|numeric|min:0',
            'monthly_price' => 'required|numeric|min:0',
            'total_spots'   => 'required|integer|min:0',
        ]);

        try {
            // 3. Salvamento
            Pricing::create($validated + [
                'is_active' => $request->is_active ?? true
            ]);

            return redirect()->route('admin.pricings.index')->with('success', 'Tabela de preços cadastrada!');
        } catch (\Exception $e) {
            logger()->error("Erro ao criar precificação: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Erro ao salvar no banco de dados.');
        }
    }

    public function update(Request $request, $id)
    {
        if ($request->has('hourly_price') || $request->has('monthly_price')) {
            $request->merge([
                'hourly_price' => $request->hourly_price 
                    ? (float) str_replace(',', '.', str_replace('.', '', $request->hourly_price)) 
                    : 0,
                'monthly_price' => $request->monthly_price 
                    ? (float) str_replace(',', '.', str_replace('.', '', $request->monthly_price)) 
                    : 0,
                'is_active' => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        $pricing = Pricing::findOrFail($id);

        $validated = $request->validate([
            'category'      => 'required|string|max:255|unique:pricings,category,' . $id,
            'hourly_price'  => 'required|numeric|min:0',
            'monthly_price' => 'required|numeric|min:0',
            'total_spots'   => 'required|integer|min:0',
            'is_active'     => 'required|boolean',
        ]);

        try {
            $pricing->update($validated);
            return redirect()->route('admin.pricings.index')->with('success', "A categoria '{$pricing->category}' foi atualizada!");
        } catch (\Exception $e) {
            logger()->error("Erro ao atualizar precificação ID {$id}: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Falha ao salvar no banco de dados.');
        }
    }

    public function destroy($id)
    {
        try {
            $pricing = Pricing::findOrFail($id);
            $pricing->delete();
            return redirect()->route('admin.pricings.index')->with('success', 'A precificação foi removida!');
        } catch (\Exception $e) {
            return redirect()->route('admin.pricings.index')->with('error', 'Erro ao excluir. Verifique dependências.');
        }
    }
}