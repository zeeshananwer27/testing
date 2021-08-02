<?php
namespace Modules\Order\Exports;
   
use App\User;
use Maatwebsite\Excel\Concerns\WithHeadings; 
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
// use Modules\Order\Exports\ExportUsersSearch;
use DB; 
class ExportUsers implements FromArray, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    
    
//     public function collection($r =null)
//     {		
// 		return  DB::table('orders')
//       ->join('order_products', 'order_products.order_id', '=', 'orders.id')
//         ->join('products', 'products.id', '=', 'order_products.product_id')
//         ->join('product_translations', 'product_translations.product_id', '=', 'products.id')
//         ->select('orders.customer_first_name', 'product_translations.organizer','orders.id')
//         ->orderBy('orders.customer_first_name', 'asc')
//         ->get();

        
//     }

    public function array(): array
    {
        // ->select('orders.id','orders.customer_first_name', 'orders.options_data')
        // $news= DB::table('orders')
        //   ->join('order_products', 'order_products.order_id', '=', 'orders.id')
        //     ->join('products', 'products.id', '=', 'order_products.product_id')
        //     ->join('product_translations', 'product_translations.product_id', '=', 'products.id')
        //     ->select('orders.customer_first_name','orders.options_data','orders.id')
        //     ->get();
        //   $data=array();
        // foreach ($news as $new)
        // {   
        //     $i=(json_decode($new->options_data));
        //     $option=$i[0]->label;
       
        //   // echo $option;exit('here');
        //     $data[]=array([
        //     'customer_first_name' =>$new->customer_first_name,
        //     'options_data' => $option,
        //     'id' =>$new->id,
        //     ]);
        
        // }
         $new_data= DB::table('tickets')
         ->join('orders', 'tickets.order_id', '=', 'orders.id')
         ->orderBy('id', 'DESC')
         ->select('tickets.option_id','orders.customer_first_name', 'tickets.id as id', "tickets.seat_name")
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


