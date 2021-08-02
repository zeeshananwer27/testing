@extends('public.layout')

@section('content')
    <section class="confirmation text-center">
        <i class="fa fa-check-circle-o" aria-hidden="true"></i>

        <h2>{{ trans('storefront::order_placed.your_order_has_been_placed') }}</h2>

        <p>
            {{ trans('storefront::order_placed.order_id') }}<span> #{{ $order->id }}</span>
            <br>
            {{ trans('storefront::order_placed.thanks') }}
        </p>
        <?php //$qr_code = QrCode::size(300)->generate(json_encode(array('Name'=>'Hafiz Adil','Designation'=>'Software Engineer')));  echo $qr_code; ?>
    </section>
    <?php
 
                             $user = DB::table('orders')->where('id',$order->id)->get();
                             $res=$user[0]->options_data;
                                $p=json_decode($res,true);
                               $sub=0;
                            foreach ($p as $value)
                            {
                                $id=$value['option_id'];
                                $q=$value['quantity'];
                                // $query= DB::table('advanced_options')->where('id',$order->id)->get();
                               // $query=DB::table('advanced_options')->decrement('quantity', $q); 
                                
                                $query = DB::table('advanced_options')
                                 ->where('id',$id)->get();
                                //  print_r($query);
                                //  die;
                                $qu=$query[0]->quantity-$q;
                               $t=DB::table('advanced_options')
            ->where('id', $id)
            ->update(['quantity' =>DB::raw($qu)]);
                                
                            }
                               
   ?>
@endsection
