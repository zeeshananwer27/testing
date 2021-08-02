<?php
namespace Modules\Page\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Payment\Facades\Gateway;
use Modules\Checkout\Events\OrderPlaced;
use Modules\Checkout\Services\OrderService;
use Modules\Order\Http\Requests\StoreOrderRequest;
use Modules\Order\Entities\Order;
use QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Auth;
// use Schema;
class ApiController extends Controller
{

    public function events(){


        $current_date = date('Y-m-d 00:00:00');

 		$all_events = DB::table('products')->join('product_translations', 'products.id', '=', 'product_translations.product_id')->where('products.is_active',1)->where('products.end_event' ,'>=' , $current_date)->where('products.deleted_at', NULL)->select('products.*', 'product_translations.name')->get()->toArray();



 		$all_events_img = Product::forCard()->latest()->get()->toArray();


		foreach ($all_events_img as $value) {
			$compare_id = $value['translations'][0]['product_id'];


			foreach ($all_events as $event) {


			if(isset($value['files'][0]['path'])){
			    $path = $value['files'][0]['path'];
			}
			else{
			    $path = '';
			}
			$address = $value['address'];
			$lat = $value['lat'];
			$long = $value['lng'];
				if( $compare_id == $event->id){
					$event->img = $path;
					$event->address = $address;
					$event->lat = $lat;
                    $event->long = $long;
					}
				}
		}


		$all_events_product_translations = Product::with('product_translations')->get()->toArray();


		foreach ($all_events_product_translations as $valuet) {
			$compare_id = $valuet['translations'][0]['product_id'];
			foreach ($all_events as $event) {
			$address = $valuet['address'];
			$lat = $valuet['lat'];
			$long = $valuet['lng'];
			$description = $valuet['description'];
			$organizer = $valuet['organizer'];
			$event_start = $valuet['start_event'];
			$event_end = $valuet['end_event'];
				if( $compare_id == $event->id){
				// 	$event->img = $path;
					$event->address = $address . ' ' . $valuet['city'] . ' ' . $valuet['state'] . ' '. $valuet['zip_code'];
					$event->lat = $lat;
                    $event->long = $long;
                    $event->description = $description;
                    $event->organizer = $organizer;
                    $event->event_start = $event_start;
                    $event->event_end= $event_end;

					}
				}
		}

		$advance_Options = DB::table('advanced_options')->get()->toArray();

            foreach ($all_events as $event) {


        			$options = array();
        		    foreach ( $advance_Options as $values ) {

        			$compare_ids = $values->product_id;

        				if( $compare_ids == $event->id){


        				    $abc = array();

            					if($values->id){
                                    $abc['option_id'] = $values->id;
                                   // array_push( $options, $abc);
                                }

                                if($values->label){
            					    $abc['label'] = $values->label;
            					   // array_push( $options, $abc);
            					}
            					if($values->price){
            					    $abc['price'] = $values->price;
            					   // array_push( $options, $abc);
            					}
            					if($values->quantity){
            					    $abc['quantity'] = $values->quantity;
            					   // array_push( $options, $abc);
            					}
            					if($values->fee){
            					    $abc['fee'] = $values->fee;
            					   // array_push( $options, $abc);
            					}
            					if($values->purchase_limit){
            					    $abc['limit'] = $values->purchase_limit;
            					   // array_push( $options, $abc);
            					}

            					array_push( $options, $abc);

        				    }

        				}
        				$event->options = $options;

        		}

// 		echo "<pre>"; print_r($all_events);
// 		exit();
		return json_encode($all_events);
 	}
 	public function advance_option( Request  $request){
        $option_data=DB::table('advanced_options')->where('product_id',$request->id)->get();
        return response()->json(['advance_option' => $option_data]);
        //        return $option_data;
    }
    public function advance_values( Request  $request){

    $option_data=DB::table('advanced_options')->find($request->id);
        return response()->json(['advance_option' => $option_data]);
    }
    public function sitting_arrangements($id = '' ){

        $option_data=DB::table('advanced_options')->where('product_id',$id)->get();
        $event_templates = DB::table('products')->where('template',1 )->select('id')->get();
        foreach ($event_templates as $temp){
            $path="/storage/templates/$temp->id/$temp->id.png";
            $pngString = file_get_contents(public_path().$path);
            $temp->sitting_image = $pngString;
        }

        $reservedSeats = DB::table('orders')->join('order_products', 'orders.id', '=', 'order_products.order_id')->select('orders.seats_data', 'orders.id')->where('order_products.product_id', $id)->get();
        return view('public.home.sitting_arrangement', array( 'e_id' => $id,'option_data'=>$option_data,'event_templates'=>$event_templates,'reservedSeats'=>$reservedSeats) );

	}
	
    public function show_templates( Request $request){
        $event_templates = DB::table('products')->where('id',$request->id)->where('template',1 )->select('id')->get();
        foreach ($event_templates as $temp){
            $path="/storage/templates/$temp->id/$temp->id.json";
            $jsonString = file_get_contents(public_path().$path);
            $temp->sitting_arrangement = json_decode($jsonString, true);
        }
        return $event_templates;
	}

	public function detele_templates( Request $request){
		// for removing files against templates
		
// 		$v = Product::where('id', $request->id)->update(array('template'=>0 ) );
		
		$v = DB::table('products')->where('id', $request->id)->update(array('template'=>0 ) );
		
		if($v){
		    $path=public_path()."/storage/templates/$request->id";
		 
    		if(file_exists($path)){
    		
        		$response = File::deleteDirectory($path);
        		// unlink(public_path().$path);
		    }
		}
		// end removing files
		
		// update template flug in product
		
		// End update template flug in product
		
		return 1;
	}

    public function save_sitting(Request $request){
// $designJson = json_encode($request->designedData);
        $designJson = $request->designedData;
        $designPng = $request->designPng;
        if($request->enId != 00 ){
            if($request->flug ==1){
                $v = Product::where('id', $request->enId)->update(array('template'=>$request->flug ) );
                $destinationPath=public_path()."/storage/templates/$request->enId/";
                $file_json = $request->enId.'.json';
                $file_png = $request->enId.'.png';
                if (File::exists($destinationPath)) {
                    if (!is_dir($destinationPath)) { mkdir($destinationPath,0777,true); }
                    File::put($destinationPath.$file_json,$designJson);
                    File::put($destinationPath.$file_png,$designPng);

                }else{
                    if (!is_dir($destinationPath)) { mkdir($destinationPath,0777,true); }
                    File::put($destinationPath.$file_json,$designJson);
                    File::put($destinationPath.$file_png,$designPng);
                }
                return $v;
            }else{
                $v = Product::where('id', $request->enId)->update(array('sitting_arrangement' =>$designJson ) );
                return $v;
            }


        }
        else{
            $temp_data = DB::table('temp_data')->select(DB::raw('*'))->where('user_id', '=', Auth::user()->id)->get()->toArray();

            if(empty($temp_data)){

                $insertion = array(
                    'sitting_arrangement' => $designJson,
                    'user_id' => Auth::user()->id
                );
                $se = DB::table('temp_data')->insert($insertion);
                $id = DB::getPdo()->lastInsertId();
            }else{
                $id = $temp_data[0]->id;
                $update = DB::table('temp_data')->where('user_id',Auth::user()->id)->update(array('sitting_arrangement' =>$designJson ) );
            }

            return json_encode([
                'url' => 'http://boletazodev.bustosmediallc.com/admin/products/create',
            ]);

        }

    }

 	public function sign(){
    	print_r($_POST);
        exit('Zeeshan is here yes');

    }

   	public function qr_code(){
    	print_r($_POST);
        exit('Zeeshan is here yes');

    }

    public function checkout_mob()
    {
       if (isset($_POST['checkout_data'])) {
    		$checkout_data = json_decode($_POST['checkout_data']);
    		if ($checkout_data) {
    			$checkout_data = $checkout_data->checkout_data;
    			//echo "<pre>"; print_r($checkout_data); exit('ok');
    			$card_no = $checkout_data[0]->card_number;
    			$exp_month = $checkout_data[0]->exp_month;
    			$exp_year = $checkout_data[0]->exp_year;
    			$cvc = $checkout_data[0]->cvc;

    			//$apiKey = 'sk_test_NdXu7GsQRdmoHPx28gFDq3xB00W4LMFcT8';

    			$val = DB::table('settings')->where('key', 'stripe_secret_key')->select('plain_value')->get()->first();
                if($val){
                   $apiKey =  unserialize($val->plain_value);
                 }


				$curl = curl_init();
				curl_setopt_array($curl, [
				    CURLOPT_RETURNTRANSFER => 1,
				    CURLOPT_URL => "https://api.stripe.com/v1/tokens",
				    CURLOPT_POST => 1,
				    CURLOPT_HTTPHEADER => [
				        "Authorization: Bearer " . $apiKey
				    ],
				    CURLOPT_POSTFIELDS => http_build_query([
				    	'card' => [
					        'number' => $card_no,
			      			'exp_month' => $exp_month,
			      			'exp_year' => $exp_year,
			      			'cvc' => $cvc
			      		],
				    ])
				]);
				$resp = curl_exec($curl);
				curl_close($curl);
				$response = json_decode($resp);
				if (isset($response->error)) {
					return json_encode(array(
		    			'status'  => 0,
		    			'message' => 'Invalid Card Info',
		    		));
				}else{
					$stripe_token = $response->id;
				}
    		}

    		if (isset($checkout_data[0]->options)) {
    			$options = json_encode($checkout_data[0]->options);
    		}else{
    			$options = "";
    		}

    		$request = (object)array(
	  			"customer_email" => $checkout_data[0]->customer_email,
				"customer_phone" => $checkout_data[0]->customer_email,
				//"create_an_account" => "0",
				//"password" => "miboletazo",
				"billing" => array(
				    "first_name" => $checkout_data[0]->customer_first_name,
				    "last_name" => $checkout_data[0]->customer_last_name,
				    "address_1" => $checkout_data[0]->billing_address_1,
				    "address_2" => $checkout_data[0]->billing_address_2,
				    "city" => $checkout_data[0]->billing_city,
				    "zip" => $checkout_data[0]->billing_zip,
				    "country" => $checkout_data[0]->billing_country,
				    "state" => $checkout_data[0]->billing_state,
				),
				"ship_to_a_different_address" => "0",
				"shipping" => array(
				    "first_name" => null,
				    "last_name" => null,
				    "address_1" => null,
				    "address_2" => null,
				    "city" => null,
				    "zip" => null,
				    "country" => "US",
				    "state" => null,
				),
				"amount" => $checkout_data[0]->total,
				"description" => "Example Description",
				"payment_method" => $checkout_data[0]->payment_method,
				"shipping_method" => "free_shipping",
				"terms_and_conditions" => "on",
				"stripe_token" => $stripe_token
	    	);
	    	$data = array(
	    		"customer_id"=>$checkout_data[0]->customer_id,
	    		"customer_email"=>$checkout_data[0]->customer_email,
	    		"customer_phone"=>$checkout_data[0]->customer_phone,
	    		"customer_first_name"=>$checkout_data[0]->customer_first_name,
	    		"customer_last_name"=>$checkout_data[0]->customer_last_name,
	    		"billing_first_name"=>$checkout_data[0]->customer_first_name,
	    		"billing_last_name"=>$checkout_data[0]->customer_last_name,
	    		"billing_address_1"=>$checkout_data[0]->billing_address_1,
	    		"billing_address_2"=>$checkout_data[0]->billing_address_2,
	    		"billing_city"=>$checkout_data[0]->billing_city,
	    		"billing_state"=>$checkout_data[0]->billing_state,
	    		"billing_country"=>$checkout_data[0]->billing_country,
	    		"billing_zip"=>$checkout_data[0]->billing_zip,
	    		"shipping_first_name"=>$checkout_data[0]->customer_first_name,
	    		"shipping_last_name"=>$checkout_data[0]->customer_last_name,
	    		"shipping_address_1"=>$checkout_data[0]->billing_address_1,
	    		"shipping_address_2"=>$checkout_data[0]->billing_address_2,
	    		"shipping_city"=>$checkout_data[0]->billing_city,
	    		"shipping_state"=>$checkout_data[0]->billing_state,
	    		"shipping_zip"=>$checkout_data[0]->billing_zip,
	    		"shipping_country"=>$checkout_data[0]->billing_country,
	    		"sub_total"=>$checkout_data[0]->total,
	    		"shipping_method"=> "free_shipping",
	    		"total"=>$checkout_data[0]->total,
	    		"payment_method"=>$checkout_data[0]->payment_method,
	    		"shipping_cost"=>0.0000,
	    		"discount"=>0.0000,
	    		"currency"=>"USD",
	    		"currency_rate"=>1.0000,
	    		"locale"=>"en",
	    		"status"=>"pending",
	    		"options_data"=>$options,
	    		'created_at' => date('Y-m-d H:i:s', time()),
    			'updated_at' => date('Y-m-d H:i:s', time()),
	    	);

	    	$create_order = DB::table('orders')->insertGetId($data);
	    	//For Save Tickcets against order
	    	$is_enter = 0;
	    	$optionData = json_decode($options);
	        foreach($optionData as $option){
	        	//echo "<pre>"; print_r($option); exit();
	            for($i = 1; $i<=$option->quantity;$i++){
	                $qr_token = Str::random(64);
	                $data = [
	                    'order_id' => $create_order,
	                    'option_id' => $option->option_id,
	                    'qr_token' => $qr_token,
	                    'is_enter' => $is_enter
	                ];
	                $create_ticket = DB::table('tickets')->insertGetId($data);
	                $qr_code = QrCode::format('png')->size(900)->generate(
	                    json_encode(
	                        array(
	                            'ticket_id'=>$create_ticket,
	                            'order_id'=>$create_order,
	                            'qr_token' => $qr_token,
	                            'identifier' => 'LaRolaRadio'
	                        )
	                    )
	                );
	                Storage::put("tickets/".$create_ticket.'_'.$create_order.'.png', $qr_code);
	                if ($create_ticket) {
	                    $changeArr = ['QR_code' => "/public/storage/tickets/".$create_ticket.'_'.$create_order.'.png'];
	                    DB::table('tickets')->where('id', $create_ticket)->update($changeArr);
	                }
	            }
	        }
	    	//echo "<pre>"; print_r($create_order); exit();
	    	if ($create_order) {

	    		$data = array(
	    			'order_id' => $create_order,
	    			'product_id' => $checkout_data[0]->product_id,
	    			'unit_price' => $checkout_data[0]->total,
	    			'qty' => 1,
	    			'line_total' => $checkout_data[0]->total,
	    		);

	    		$create_order_products = DB::table('order_products')->insert($data);
	    		//echo "<pre>"; print_r($create_order_products); exit('ok');
	    	}

	    	$order = (object)array(
			    'customer_id' => $checkout_data[0]->customer_id,
			    'customer_email' => $checkout_data[0]->customer_email,
			    'customer_phone' => $checkout_data[0]->customer_phone,
			    'customer_first_name' => $checkout_data[0]->customer_first_name,
			    'customer_last_name' => $checkout_data[0]->customer_last_name,
			    'billing_first_name' => $checkout_data[0]->customer_first_name,
			    'billing_last_name' => $checkout_data[0]->customer_last_name,
			    'billing_address_1' => $checkout_data[0]->billing_address_1,
			    'billing_address_2' => $checkout_data[0]->billing_address_2,
			    'billing_city' => $checkout_data[0]->billing_city,
			    'billing_state' => $checkout_data[0]->billing_state,
			    'billing_zip' => $checkout_data[0]->billing_zip,
			    'billing_country' => $checkout_data[0]->billing_country,
			    'shipping_first_name' => $checkout_data[0]->customer_first_name,
			    'shipping_last_name' => $checkout_data[0]->customer_last_name,
			    'shipping_address_1' => $checkout_data[0]->billing_address_1,
			    'shipping_address_2' => $checkout_data[0]->billing_address_2,
			    'shipping_city' => $checkout_data[0]->billing_city,
			    'shipping_state' => $checkout_data[0]->billing_state,
			    'shipping_zip' => $checkout_data[0]->billing_zip,
			    'shipping_country' => $checkout_data[0]->billing_country,
			    'payment_method' => $checkout_data[0]->payment_method,
			    'currency' => 'USD',
			    'currency_rate' => 1.0000,
			    'locale' => 'en',
			    'status' => 'pending_payment',
			    'created_at' => date('Y-m-d H:i:s', time()),
    			'updated_at' => date('Y-m-d H:i:s', time()),
			    'id' => $create_order,

			);
	    	//$order = $orderService->create($request);
	    	$gateway = Gateway::get('stripe');
	    	//echo "<pre>"; print_r($gateway); exit('ok');
	        //$gateway = 'stripe';
	    	$response = $gateway->purchase_mobile($order, $request);
            //echo "<pre>"; print_r($response); exit('ok');
	    	//$responsee = Omnipay\Stripe\Message\Response::get();

	    	if ($response->isSuccessful()) {
	    		return json_encode(array(
	    			'status'  => 1,
	    			'message' => 'Payment Successful',
	    		));
			} else {
				return json_encode(array(
	    			'status'  => 0,
	    			'message' => 'Payment Fail',
	    		));
			    // The response will not be successful if the 3DS authentication process failed or the card has been declined. Either way, it's back to step (1)!
			}

	    	//echo "<pre>"; print_r($response); exit('ok');


    	}

    }
    public function order_history()
    {
    	if (isset($_POST['user_id'])) {
	    	$Orders = Order::with('products')->where('customer_id', $_POST['user_id'])->get()->toArray();
	    	if(sizeof($Orders) > 0){
	    	    $all_events_img = Product::forCard()->latest()->get()->toArray();

    	    	$apiOrders = array();

    	    	foreach ($Orders as $key => $value) {
    	    	    //Get QrCodes against order and append in array
    	    	    $options_data = json_decode($value['options_data']);
    	    		foreach($options_data as $k =>  $option){
    	    			$options_data[$k]->QR = $Qr_codes = DB::table('tickets')->where([
    	    				'order_id' => $value['id'],
    	    				'option_id' => $option->option_id
    	    			])->pluck('qr_code')->toArray();
    	    		}
    	    		$apiOrders[] = array(
    	    			'id'=>strval($value['id']),
    	    			'customer_id' => strval($value['customer_id']),
    				    'customer_email' => $value['customer_email'],
    				    'customer_phone' => $value['customer_phone'],
    				    'customer_first_name' => $value['customer_first_name'],
    				    'customer_last_name' => $value['customer_last_name'],
    				    'amount' => $value['total']->amount(),
    				    'options_data' => $options_data,//(array)json_decode($value['options_data']),
    				    'product_name' => $value['products'][0]['product']['name'],
    				    'description' => $value['products'][0]['product']['description'],
    				    'address' => $value['products'][0]['product']['address'] .' '. $value['products'][0]['product']['city'] .' '. $value['products'][0]['product']['state'] .' '. $value['products'][0]['product']['zip_code'],
    				    'lat' => strval($value['products'][0]['product']['lat']),
    				    'lng' => strval($value['products'][0]['product']['lng']),
    				    'start_event' => $value['products'][0]['product']['start_event'],
    				    'end_event' => $value['products'][0]['product']['end_event'],
    				    'product_id' => $value['products'][0]['product_id'],
    				    'created_at' => $value['created_at'],
    				    'updated_at' => $value['updated_at'],
    	    		);
    	    		//For Add product iamge in array
    	    		foreach ($all_events_img as $key1 => $val) {
    	    			  $value['products'][0]['product_id'];
    	    			if ($value['products'][0]['product_id'] == $val['id']) {
    	    				$apiOrders[$key]['image'] = $val['files'][0]['path'];
    	    			}
    	    		}
    	    	}
    	    	return json_encode(array(
    	    		'status' => 1,
    	    		'message' => 'Orders Found Successfully',
    	    		'data' => $apiOrders
    	    	));
	    	}else{
	    	    return json_encode(array(
    	    		'status' => 0,
    	    		'message' => 'Orders Not Found',
    	    	));
	    	}
    	}else{
    		return json_encode(array(
    			'status' => 0,
    			'message' => 'Missing Information!'
    		));
    	}
    }
    public function qrcode_scann(Request $request)
    {
	    $v = Validator::make($request->all(), [
	        'ticket_id' => 'required',
	        'order_id' => 'required',
	        'qr_token' => 'required',
	        'event_id' => 'required',
	    ]);

	    if ($v->fails())
	    {
	    	return json_encode(array(
    			'status' => 0,
    			'message' => 'Falta informacion!'
    		));
	    }else{
	    	$ticket = DB::table('tickets')->where([
	    				'id' => $request->ticket_id,
	    				'order_id' => $request->order_id,
	    				'qr_token' => $request->qr_token
	    			])->get()->toArray();
	    	if (sizeof($ticket) > 0) {
	    		if ($ticket[0]->is_enter == 0) {
	    		    
	    		    
	    		    $validevent = DB::table('order_products')->where(['order_id' => $request->order_id, 'product_id' => $request->event_id ]) ->select('*')->get()->toArray();
	    		    if(empty($validevent)){
	    		        return json_encode(array(
            			    'status' => 0,
            			    'message' => 'La entrada no es válida para el evento!'
            		    ));
	    		    }
	    		    
	    			$data = ['is_enter' => '1'];

		    		$user_order = DB::table('orders')
									->where(['orders.id' => $request->order_id])
									->select('orders.customer_first_name as first_name','orders.customer_last_name as last_name','orders.customer_email', 'orders.customer_id')
									->get()->toArray();
					$update_qr_status = DB::table('tickets')->where('id', $request->ticket_id)->update($data);


					$advOptions = DB::table('advanced_options')->where(['id' => $ticket[0]->option_id])->get()->toArray();
					if(empty($advOptions)){
				    	return json_encode(array(
                			'status' => 0,
                			'message' => 'QRCode no valido.'
                		));
					}
					$user_order[0]->label = $advOptions[0]->label;
					$product_detail = DB::table('product_translations')->where(['product_id' => $advOptions[0]->product_id])->get()->toArray();
					$user_order[0]->name = $product_detail[0]->name;
					$user_order[0]->description = strip_tags($product_detail[0]->description);
					$user_order[0]->address = $product_detail[0]->address;
					$user_order[0]->ticket_id = $ticket[0]->id;
					$user_order[0]->seat_name = $ticket[0]->seat_name;
					return json_encode(array(
						'status' => 1,
						'message' => 'QRCode escaneado con exito.',
						'data' => $user_order
					));
	    		}else{
	    			return json_encode(array(
                		'status' => 0,
                		'message' => 'QRCode ya escaneado.'
	    			));
	    		}
	    	}else{
	    		return json_encode(array(
    			'status' => 0,
    			'message' => 'La entrada no es válida para el evento!'
    		    ));
	    	}
	    }
    }
    public function list_of_customers(Request $request){

        $list = DB::table('orders')
        ->join('order_products', 'order_products.order_id', '=', 'orders.id')
        ->join('tickets', 'tickets.order_id', '=', 'orders.id')
        ->where('order_products.product_id' , $request->event_id)
        ->orderBy('orders.customer_first_name' , 'ASC')
        ->select('tickets.is_enter', 'orders.customer_id','orders.customer_first_name', 'orders.customer_last_name')
        ->get()->toArray();

        if($list){
            return json_encode(array(
				'status' => 1,
				'message' => 'List of Customer.',
				'data' => $list
			));
        }
        else{
             return json_encode(array(
				'status' => 0,
				'message' => 'NO Customer Found.',
				'data' => ''
			));
        }


    }

    public function scanner_password(Request $request){
        
        if(!isset($request->event_id) || $request->event_id ==''){
        
             return json_encode(array(
				'status' => 0,
				'message' => 'Contraseña invalida.'
			));            
        }
        elseif(!isset($request->scanner_password) || $request->scanner_password ==''){
             return json_encode(array(
				'status' => 0,
				'message' => 'Contraseña invalida.'
			));                        
        }
        else{
                $res = DB::table('products')->where('scanner_pass', $request->scanner_password)->where('id', $request->event_id)->get()->toArray();
                    if(!empty($res)){
                        return json_encode(array(
            				'status' => 1,
            				'message' => 'Contraseña de energía.'
            			));
                    }
                    else{
                         return json_encode(array(
            				'status' => 0,
            				'message' => 'Contraseña invalida.'
            			));
                    }
            }
    }
    
//     public function scanner_password(Request $request){

//         $res = DB::table('users')->where('id', 1)->where('scanner_password', $request->scanner_password)->get()->toArray();
//         if(!empty($res)){
//             return json_encode(array(
// 				'status' => 1,
// 				'message' => 'Contraseña de energía.'
// 			));
//         }
//         else{
//              return json_encode(array(
// 				'status' => 0,
// 				'message' => 'Contraseña invalida.'
// 			));
//         }
//     }    
    public function reservedSeatsData(Request $request) {
        $reservedSeats = DB::table('orders')->join('order_products', 'orders.id', '=', 'order_products.order_id')->
        select('orders.seats_data', 'orders.id')->where('order_products.product_id', $request->id)->get()->toArray();
        return $reservedSeats;
    }

}
