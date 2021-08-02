<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Validator;
use Modules\Product\Entities\AdvancedOptions;

class AdvancedOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('product::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('product::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('product::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('product::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {

        $product_id = $request->input('product_id');

        $labels = $request->input('advanced_label');

        $prices = $request->input('advanced_price');
        $advanced_color = $request->input('advanced_color');

        $fees = $request->input('fee');

        $id = $request->input('advance_option_id');

        $quantities = $request->input('advanced_quantity');

        $purchase_limit = $request->input('purchase_limit');

        foreach ($labels as $key => $label) {
            if ($id[$key] != 0) {
                $update = AdvancedOptions::find($id[$key]);
                $update->label = $label;
                $update->price = $prices[$key];
                $update->quantity = $quantities[$key];
                $update->advanced_color = $advanced_color[$key];
                $update->fee = $fees[$key];
                $update->purchase_limit = $purchase_limit[$key];
                $update->save();
            } elseif ($id[$key] == 0) {
                $add_AdvancedOptions = new AdvancedOptions;
                $add_AdvancedOptions->label = $label;
                $add_AdvancedOptions->product_id = $product_id;
                $add_AdvancedOptions->price = $prices[$key];
                $add_AdvancedOptions->quantity = $quantities[$key];
                $add_AdvancedOptions->advanced_color = $advanced_color[$key];
                $add_AdvancedOptions->fee = $fees[$key];
                $add_AdvancedOptions->purchase_limit = $purchase_limit[$key];
                $add_AdvancedOptions->save();
            }
        }

        return response()->json(['success' => 'done']);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Request $request)
    {
        
        $delete=AdvancedOptions::find($request->id)->delete();        
        return 1;
    }
}
