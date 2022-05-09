<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentGatewayContract;
use App\Http\Requests\CheckoutFormRequest;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\CartService;
use Stripe\Exception\CardException;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CartService $cartService) {
        $contents = [
            'cartItems' => $cartService->setCartValues()->get('cartItems'),
            'cartTaxRate' => $cartService->setCartValues()->get('cartTaxRate'),
            'cartSubtotal' => $cartService->setCartValues()->get('cartSubtotal'),
            'newTax' => $cartService->setCartValues()->get('newTax'),
            'code' =>$cartService->setCartValues()->get('code'),
            'discount' => $cartService->setCartValues()->get('discount'),
            'newSubtotal' => $cartService->setCartValues()->get('newSubtotal'),
            'newTotal' => $cartService->setCartValues()->get('newTotal'),
        ];
        return Inertia::render('Checkout/Index', $contents);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CheckoutFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentGatewayContract $paymentService, CheckoutFormRequest $request) {
        try {
            $confirmation_number = Str::uuid();
            $user = auth()->user() ?? new User;

            $paymentService->charge($user, $request, $confirmation_number);

            return response([
                'success' => true,
            ], 200);
        }catch (CardException $e) {
            return response([
                'errors' => $e->getMessage()
            ], 500);
        }catch (\Error $e) {
            return response([
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
