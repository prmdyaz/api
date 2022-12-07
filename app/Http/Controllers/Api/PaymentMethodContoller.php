<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentResource;
use Illuminate\Support\Facades\File;

class PaymentMethodContoller extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = PaymentResource::collection(PaymentMethod::all());
        if (count($data) > 0) {
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'no data found'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'img' => 'required|file|mimes:png,jpg',
            'name' => 'required',
            'fees' => 'required|numeric',
            'wallet' => 'required'
        ]);

        $file = $request->file('img');
        $file_name = time() . '.' . $file->getClientOriginalExtension();
        $file->move('payment/img', $file_name);

        $payment = PaymentMethod::create([
            'name' => $request->name,
            'img' => $file_name,
            'fees' => $request->fees,
            'wallet' => $request->wallet
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success add new Payment Method',
            'data' => new PaymentResource($payment)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentMethod $payment)
    {
        return response()->json([
            'success' => false,
            'message' => 'Invalid Endpoint'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentMethod $payment)
    {
        $request->validate([
            'name' => 'sometimes',
            'img' => 'sometimes|mimes:png,jpg',
            'fees' => 'sometimes|numeric',
            'wallet' => 'sometimes'
        ]);

        // check if request not passing any data to updated
        if (!$request->name && !$request->img && !$request->fees && !$request->wallet) {
            return response()->json([
                'success' => false,
                'message' => 'No one data is updated'
            ], 202);
        }

        // Update all except img
        $payment->update($request->only(['name', 'fees', 'wallet']));

        // Update img only
        if ($request->hasFile('img')) {
            File::delete("payment/img/$payment->img");
            $file = $request->file('img');
            $file->move('payment/img', $payment->img);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success update Payment Method',
            'data' => new paymentResource($payment)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentMethod $payment)
    {
        // delete image first before delete data in database
        File::delete("payment/img/$payment->img");
        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Success delete Payment Method'
        ]);
    }
}
