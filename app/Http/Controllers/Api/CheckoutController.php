<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResponseResource;
use App\Models\Penjual;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function paymentMthodPenjual(Request $request)
    {
        $penjualId = explode(',', $request->query('penjual')); // Memisahkan nilai-nilai 'penjual' berdasarkan koma

        $paymentMethod = Penjual::whereIn('id', $penjualId)
            ->with([
                'user:id,nama',
                'ewallet:id,nama,icon'
            ])
            ->select('id', 'user_id', 'ewallet_id')
            ->get();

        return new ResponseResource(true, "List Payment method dari penjual denga user_id: " . $request->query('penjual') . "", $paymentMethod);
    }
}
