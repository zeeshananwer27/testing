<?php

namespace Modules\Checkout\Http\Controllers;
use Illuminate\Support\Facades\Mail;

use Exception;
use Modules\Support\Country;
use Modules\Cart\Facades\Cart;
use Modules\Page\Entities\Page;
use Illuminate\Routing\Controller;
use Modules\Payment\Facades\Gateway;
use Modules\Checkout\Events\OrderPlaced;
use Modules\User\Services\CustomerService;
use Modules\Checkout\Services\OrderService;
use Modules\Order\Http\Requests\StoreOrderRequest;
use Illuminate\Support\Facades\DB;
use QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// Modules\Checkout\Http\Controllers\Mail
use Modules\Checkout\Mail\OrderComplete;

class CheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['cart_not_empty', 'check_stock', 'check_coupon_usage_limit']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cart = Cart::instance();
        $countries = Country::supported();
        $gateways = Gateway::all();
        $termsPageURL = Page::urlForPage(setting('storefront_terms_page'));
        $user = auth()->user();

        if (!empty($user)) {
            $user_info = DB::table('orders')->where('customer_id', $user->id)->latest('created_at')->first();
        }else{
            $user_info = "";
        }
        return view('public.checkout.create', compact('cart', 'countries', 'gateways','termsPageURL','user','user_info'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Modules\Order\Http\Requests\StoreOrderRequest $request
     * @param \Modules\User\Services\CustomerService $customerService
     * @param \Modules\Checkout\Services\OrderService $orderService
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request, CustomerService $customerService, OrderService $orderService)
    {


        $counter = 1;
        //pdf generate for QR Codes object create

        //for add advance_options against order
        $cart = Cart::instance();



        $optionData = [];
        $options = [];
        $i=0;

        foreach($cart->items() as $key => $item) :

// echo "<pre>"; print_r($item->product->id);  exit(' oo'); 

          $data=json_decode($item->advance_options['seats_data']);
        if ($data){
            foreach ($data as $seat)
            {
                $i++;
                $advancedOptions = DB::table('advanced_options')->where('id', $seat->advancedOptionId)->first();
                
                $options = [
                'option_id' => $seat->advancedOptionId,
                'label' => $advancedOptions->label,
                'quantity' => 1,
                'price' => $advancedOptions->price,
                'fee' => $advancedOptions->fee,
                'seatKey' => $seat->seatKey,
                'seatName' => $seat->label,
                'event_id' => $item->product->id,
                ];
                array_push($optionData, $options);
                }
            }
        else{
                $advancedOptions = DB::table('advanced_options')->where('product_id', $item->advance_options['product_id'])->get();
                $i = 0;
                foreach($item->advance_options['quantity'] as $keyOne => $option) :
                $options = [
                'option_id' => $advancedOptions[$i]->id,
                'label' => $keyOne,
                'quantity' => $option[key($option)],
                'price' => key($option),
                'fee' => $advancedOptions[$i]->fee,
                'seatKey' => '',
                'seatName' => 'By Management',
                'event_id' => $item->product->id,
                ];
                array_push($optionData, $options);
                $i++;
                endforeach;
             }
        endforeach;

        $request->options_data = json_encode($optionData);
        //for add advance_options against order
//        dd($request->options_data.'   '.$data);
        if (auth()->guest() && $request->create_an_account) {
            $customerService->register($request)->login();
        }
        $order = $orderService->create($request);
        //For Save Tickcets against order
        $is_enter = 0;
        $qr_pdf = array();
        $seats = json_decode($request->session()->get('seats_data'));
        if ($seats==null){
           $seat_name ='By Management';
        }else{
            $seat_name =$seat->label;
        }
        $j = 0;
        // echo "<pre>";print_r($seats);
        // echo "<br>";
        // echo "<pre>";print_r($optionData); exit('inn');
        // echo "<pre>"; print_r($items); print_r($request->all()); exit(' oo'); 
        foreach($optionData as $option){

            // echo "<pre>";print_r($option); exit('inn');

            for($i = 1; $i<=$option['quantity'];$i++){
                
                $qr_token = Str::random(64);
                $data = [
                    'order_id' => $order['id'],
                    'option_id' => $option['option_id'],
                    'qr_token' => $qr_token,
                    'is_enter' => $is_enter,
                    'seat_name' =>$option['seatName'],
                    'event_id' =>$option['event_id']
                ];
                $create_ticket = DB::table('tickets')->insertGetId($data);
                $qr_code = QrCode::format('png')->size(900)->generate(
                    json_encode(
                        array(
                            'ticket_id'=>$create_ticket,
                            'order_id'=>$order['id'],
                            'qr_token' => $qr_token,
                            'identifier' => 'LaRolaRadio',
                            'seat_name' => $option['seatName'],
                            'event_id' =>$option['event_id']
                        )
                    )
                );

                Storage::put("tickets/".$create_ticket.'_'.$order['id'].'.png', $qr_code);


                //pdf generate for QR Codes start
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);


                $reval = DB::table('advanced_options')->where('id',$option['option_id'] )->select('label')->get();

                $pro = DB::table('advanced_options')->where('id',$option['option_id'] )->select('product_id')->get();



                $product_name = DB::table('product_translations')->where('product_id',$pro[0]->product_id )->select('name','start_event')->get();

                $event_start_date = DB::table('products')->where('id',$pro[0]->product_id )->select('start_event')->get();

                $start_event = date("F d\, Y",strtotime($event_start_date[0]->start_event));

                // $customer_name = auth()->user()->first_name.' '.auth()->user()->last_name;
                $customer_name = $order['customer_first_name'].' '.$order['customer_last_name'];

                $event_img = DB::table('entity_files')->join('files','files.id','=','entity_files.file_id')->where([['entity_files.entity_id',$pro[0]->product_id ],['entity_files.zone','base_image']])->select('files.path')->get();

                

                $html = '<div class = "row"><h5 style="text-align:center;">'.$start_event.'</h5></div><div style="float:left;width:30%">'.$customer_name.'</div>';
                if($option['seatName'] == 'By Management'){
                    $html  .= '<div style="float:left;width:40%;text-align:center">'.$product_name[0]->name.'</div><div style="float:right;width:30%;text-align:right">'.$option['label'].'</div><br><br>';
                }else{
                    $html  .= '<div style="float:left;width:40%;text-align:center">'.$product_name[0]->name.'</div><div style="float:right;width:30%;text-align:right">'.$option['seatName'].'</div><br><br>';    
                }
                

                $html .='<div style="margin-top:20px;text-align:center"><img src = "'. url("public/storage/media/OunjCU5qgj7G7xhPQ5zHnad4iO1vCG2cqXo9GT8j.png") .'" style=" width:100px;height:100px;" /></div><br><br>';

                $html .='<div style="float:left;width:50%"><img src = "'. url("storage/tickets/". $create_ticket."_".$order['id'] .".png") .'"  width="80%" height="70%" style="float:left !important;" /><img src = "'. url("storage/".$event_img[0]->path) .'"  width="80%" height="70%" style="float:left !important;" />
                    </div>';

                $html .='<div style="float:right; width:50%"><p>'.trans('storefront::checkout.disclaimer').'</p></div>';






                // $html = '<div class = "row"><h1 style="text-align:center;">Ticket '. $i .'.</h1><h2 style="text-align:center;">'. $reval[0]->label .'</h2><h2 style="text-align:center;">'. $product_name[0]->name .'</h2>';
                //     $html .='<img src = "'. url("public/storage/tickets/". $create_ticket."_".$order['id'] .".png")  . '"  width="32%" height="200px" style="float:left !important;" />';
                // $html .='</div>';

                $mpdf->WriteHTML($html);

                $path_pdf = public_path("storage/pdfs/" . $create_ticket ."_". $order['id'] ."_pdf.pdf");

                $mpdf->Output($path_pdf, 'F');

                unset($mpdf);
                //pdf generate for QR Codes end

                array_push($qr_pdf, $create_ticket.'_'.$order['id']);





                if ($create_ticket) {
                    $changeArr = ['QR_code' => "/public/storage/tickets/".$create_ticket.'_'.$order['id'].'.png'];
                    DB::table('tickets')->where('id', $create_ticket)->update($changeArr);




                }
                $j++;
            }
            $counter++;
        }
        $request->session()->forget('seats_data');
        // exit('outttt');

        // echo $request->payment_method; exit('thia');
        $gateway = Gateway::get($request->payment_method);

        try {
            $response = $gateway->purchase($order, $request);
        } catch (Exception $e) {
            $orderService->delete($order);

            return back()->withInput()->withError($e->getMessage());
        }

        if ($response->isRedirect()) {
            return redirect($response->getRedirectUrl());
        } elseif ($response->isSuccessful()) {
            $order->storeTransaction($response);

            event(new OrderPlaced($order));


            return redirect()->route('checkout.complete.show');
        }

        $orderService->delete($order);

        return back()->withInput()->withError($response->getMessage());
    }
}
