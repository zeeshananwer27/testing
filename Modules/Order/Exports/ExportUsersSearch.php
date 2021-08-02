<?php
namespace Modules\Order\Exports;
   
use App\User;
use Maatwebsite\Excel\Concerns\WithHeadings; 
use Maatwebsite\Excel\Concerns\FromArray;
use DB; 
class ExportUsersSearch implements FromArray, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    // protected $search; 
    
    public $search_data;
    
    
    public function __construct($search_data)
    {
        $this->search_data = $search_data;
    }
    
    // public function collection($r =null)
    // {
    //     // ->where('orders.customer_first_name', '=', 'zeeshan')
        
    //  		return  DB::table('orders')
    //       ->join('order_products', 'order_products.order_id', '=', 'orders.id')
    //         ->join('products', 'products.id', '=', 'order_products.product_id')
    //         ->join('product_translations', 'product_translations.product_id', '=', 'products.id')
    //         ->where('orders.customer_first_name', 'like', '%'.$this->search_data.'%')
    //         ->select('orders.customer_first_name', 'product_translations.organizer','orders.id')
    //         ->orderBy('orders.customer_first_name', 'asc')
    //         ->get();
    
        
    // }
    
    
    public function array(): array
    {
        //  $new_data= DB::table('tickets')
        //  ->join('orders', 'tickets.order_id', '=', 'orders.id')
        // //  ->join('advanced_options', 'tickets.option_id', '=', 'advanced_options.id')
        //  ->join('order_products', 'order_products.order_id', '=', 'orders.id')
        //  ->join('products', 'products.id', '=', 'order_products.product_id')
        //  ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
         
        //  ->where('orders.customer_first_name', 'like', '%'.$this->search_data.'%')
        //  ->orWhere('tickets.id', 'like', '%'.$this->search_data.'%')
        // //  ->orWhere('advanced_options.label', 'like', '%'.$this->search_data.'%')
        //  ->orWhere('product_translations.organizer', 'like', '%'.$this->search_data.'%')
        //  ->orWhere('product_translations.name', 'like', '%'.$this->search_data.'%')
         
        //  ->orWhere(DB::raw('CONCAT_WS(" ",orders.customer_first_name, orders.customer_last_name)'), 'like', '%'.$this->search_data.'%')
        //  ->orderBy('tickets.id', 'DESC')
        //  ->select('tickets.option_id','orders.customer_first_name', 'tickets.id as id')
        //  ->get()->toArray(); 
        
        
    $new_data= DB::table('tickets')
        ->join('orders', 'tickets.order_id', '=', 'orders.id')
        ->join('order_products', 'order_products.order_id', '=', 'orders.id')
        ->join('products', 'products.id', '=', 'order_products.product_id')
        ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
        ->where('order_products.product_id', $this->search_data)
        ->orderBy('tickets.id', 'DESC')
        ->select("tickets.option_id", DB::raw('CONCAT_WS(" ",orders.customer_first_name, orders.customer_last_name) as customer_first_name') , "tickets.id as id", "tickets.seat_name")
        ->get()->toArray();
        
        
        foreach($new_data as $key => $firdata){
            
            $ad= DB::table('advanced_options')->where('id', $firdata->option_id)->select('label')->get()->toArray(); 
            
            if($ad){
                $new_data[$key]->options_data=$ad[0]->label;
                unset($new_data[$key]->option_id);
                
            }
            else{
                $new_data[$key]->options_data='';
                unset($new_data[$key]->option_id);
            }
            
        }
        
        return $new_data;
    }

    
    public function headings(): array
    {
        return [
            'Name',
            'Ticket ID',
            'Seat Name',
            'Types',
            
        ];
    }
}


