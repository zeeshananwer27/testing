<?php

namespace Modules\Cart\Http\Controllers;

use Modules\Cart\Facades\Cart;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Routing\Controller;
use Modules\Coupon\Checkers\MaximumSpend;
use Modules\Coupon\Checkers\MinimumSpend;
use Modules\Cart\Http\Requests\StoreCartItemRequest;
use Modules\Coupon\Exceptions\MaximumSpendException;
use Modules\Coupon\Exceptions\MinimumSpendException;
use Session;

class CartItemController extends Controller
{
    private $checkers = [
        MinimumSpend::class,
        MaximumSpend::class,
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('in_stock')->only('store');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Modules\Cart\Http\Requests\StoreCartItemRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCartItemRequest $request)
    {
//dd($request->all());
        $request->session()->put('seats_data', $request->seats_data);
        $data = isset($_POST) ? $_POST : '';
        $q = Cart::store($request->product_id, $request->qty, $request->options ?? [], $data);

        // echo "<pre>"; print_r($q->seats_data); exit('check');

        return back()->withSuccess(trans('cart::messages.added'));
    }

    public function ajaxstore(StoreCartItemRequest $request)
    {
        $request->session()->put('seats_data', $request->seats_data);
        $data = isset($_POST) ? $_POST : '';
        Cart::store($request->product_id, $request->qty, $request->options ?? [], $data);
        return json_encode(array(
            'status' => 1,
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param mixed $cartItemId
     * @return \Illuminate\Http\Response
     */
    public function update($cartItemId)
    {
        // exit('thre');
        // echo "<pre>"; print_r($_REQUEST); exit('outt');
        // echo "<pre>"; print_r($cartItemId); exit('out');
        Cart::updateQuantity(decrypt($cartItemId), request('qty'));

        try {
            resolve(Pipeline::class)
                ->send(Cart::coupon())
                ->through($this->checkers)
                ->thenReturn();
        } catch (MinimumSpendException | MaximumSpendException $e) {
            Cart::removeOldCoupon();
        }

        // Cart

        if($_REQUEST['_method'] == 'put' && isset($_REQUEST['advance_options']) ){

            // echo "<pre>"; print_r($_REQUEST); exit('thy');

            Cart::remove(decrypt($_REQUEST['cart_item_id']));   //self::destory($_REQUEST['cart_item_id'], 'update');

            // advance_options
            $_REQUEST['advance_options']['product_id'] = $_REQUEST['product_id'];

            $res = Cart::store($_REQUEST['product_id'], 1,[], $_REQUEST['advance_options']);

        }

        return back()->withSuccess(trans('cart::messages.quantity_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $cartItemId
     * @return \Illuminhtate\Http\Response
     */
    public function destroy($cartItemId)
    {

        Cart::remove(decrypt($cartItemId));

        return back()->withSuccess(trans('cart::messages.removed'));
    }
}
