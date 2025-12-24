<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pricing;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $pricings = Pricing::query()
            ->when($search, function ($query, $search) {
                return $query->where('category', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            })
            ->orderBy('category', 'asc')
            ->get();

        return view('pages.admin.pricings.index', compact('pricings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255|unique:pricings,category',
            'hourly_price' => 'required|numeric|min:0',
            'monthly_price' => 'required|numeric|min:0',
            'total_spots' => 'required|integer|min:1',
        ], [
            'category.unique' => 'Esta categoria já existe no sistema.',
            'category.required' => 'O nome da categoria é obrigatório.',
        ]);

        try {
            Pricing::create([
                'category' => $validated['category'],
                'hourly_price' => $validated['hourly_price'],
                'monthly_price' => $validated['monthly_price'],
                'total_spots' => $validated['total_spots'],
                'is_active' => true,
            ]);

            return redirect()->route('admin.pricings.index')
                ->with('success', 'Categoria cadastrada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao salvar: ' . $e->getMessage())
                ->withInput();
        }
    }
}
