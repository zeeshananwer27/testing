<?php

namespace Modules\Order\Http\Controllers\Admin;

use Modules\Order\Entities\Order;
use Illuminate\Routing\Controller;
use Modules\Admin\Traits\HasCrudActions;
use Modules\Order\Http\Requests\SaveOrderRequest;
use Illuminate\Http\Request;
use Modules\Order\Exports\ExportUsers;
use Modules\Order\Exports\ExportUsersSearch;
use Excel;
use DB; 
class OrderController extends Controller
{
    use HasCrudActions;

    /**
     * Model for the resource.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['products', 'coupon', 'taxes'];

    /**
     * Label of the resource.
     *
     * @var string
     */
    protected $label = 'order::orders.order';

    /**
     * View path of the resource.
     *
     * @var string
     */
    protected $viewPath = 'order::admin.orders';

    /**
     * Form requests for the resource.
     *
     * @var array
     */
    protected $validation = SaveOrderRequest::class;
    
    public function export(Request $request)
    {   		
        
        
        
        
        $this->search_data = 'Explosion Grupera - Seattle';
        
        
        // $new_data= DB::table('tickets')
        //  ->join('orders', 'tickets.order_id', '=', 'orders.id')
        // //  ->join('advanced_options', 'tickets.option_id', '=', 'advanced_options.id')
        //  ->join('order_products', 'order_products.order_id', '=', 'orders.id')
        //  ->join('products', 'products.id', '=', 'order_products.product_id')
        //  ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
         
        //  ->where('orders.customer_first_name', 'like', '%'.$this->search_data.'%')
        //  ->orWhere('tickets.id', 'like', '%'.$this->search_data.'%')
        // //  ->orWhere('advanced_options.label', 'like', '%'.$this->search_data.'%')
         
        //  ->orWhere('product_translations.name', 'like', '%'.$this->search_data.'%')
         
        //  ->orWhere(DB::raw('CONCAT_WS(" ",orders.customer_first_name, orders.customer_last_name)'), 'like', '%'.$this->search_data.'%')
        //  ->orderBy('tickets.id', 'DESC')
        //  ->select('tickets.option_id','orders.customer_first_name', 'tickets.id as id')
        //  ->get()->toArray(); 
         
        //  echo "<pre>"; print_r($new_data); exit('outer'); 
         
        
        
        
        // foreach($new_data as $key => $firdata){
        //     $ad= DB::table('advanced_options')->where('id', $firdata->option_id)->select('label')->get()->toArray(); 
        //     if($ad){
        //         $new_data[$key]->options_data=$ad[0]->label;
        //         unset($new_data[$key]->option_id);
        //     }
        //     else{
        //         $new_data[$key]->options_data='';
        //         unset($new_data[$key]->option_id);
        //     }
        // }
        
        if($request['events'] == ''){
            return Excel::download(new ExportUsers, 'users.xlsx');  
        }
        else{
            return Excel::download(new ExportUsersSearch($request['events']), 'users.xlsx');  
        }
        
          
              
    }
    
}
