<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $payments = $query->orderBy('name', 'asc')->get();

        return view('pages.admin.payments.index', compact('payments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:payments,name',
        ], [
            'name.unique' => 'Esta forma de pagamento já está cadastrada.'
        ]);

        Payment::create([
            'name' => $data['name'],
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'Nova forma de pagamento adicionada!');
    }

    public function edit(Payment $payment)
    {
        return response()->json($payment);
    }

    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:payments,name,' . $payment->id,
            'is_active' => 'required|in:0,1',
        ], [
            'name.unique' => 'Este nome já está a ser usado noutra forma de pagamento.'
        ]);

        $payment->update([
            'name' => $data['name'],
            'is_active' => $data['is_active'] == '1' ? true : false,
        ]);

        return redirect()->route('admin.payments.index')->with('success', 'Forma de pagamento atualizada com sucesso!');
    }

    public function destroy(Payment $payment)
    {
        try {
            $payment->delete();

            return redirect()->route('admin.payments.index')
                ->with('success', 'Forma de pagamento eliminada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('admin.payments.index')
                ->with('error', 'Não é possível eliminar este método porque ele já foi utilizado em registos do sistema.');
        }
    }
}
