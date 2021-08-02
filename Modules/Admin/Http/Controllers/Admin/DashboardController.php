<?php

namespace Modules\Admin\Http\Controllers\Admin;

use Modules\User\Entities\User;
use Modules\Order\Entities\Order;
use Illuminate\Routing\Controller;
use Modules\Review\Entities\Review;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\SearchTerm;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with its widgets.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = validate_events();
        $orders = validate_orders();
        return view('admin::dashboard.index', [
            'totalSales' => Order::totalSales(),
            'totalOrders' => Order::whereIn('id', $orders)->count(),
            'totalProducts' => Product::withoutGlobalScope('active')->whereIn('id', $events)->count(),
            'totalCustomers' => User::totalCustomers(),
            'latestSearchTerms' => $this->getLatestSearchTerms(),
            'latestOrders' => $this->getLatestOrders(),
            'latestReviews' => $this->getLatestReviews(),
        ]);
    }
   

    private function getLatestSearchTerms()
    {
        return SearchTerm::latest('updated_at')->take(5)->get();
    }

    /**
     * Get latest five orders.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getLatestOrders()
    {
        $orders = validate_orders();
        return Order::select([
            'id',
            'customer_first_name',
            'customer_last_name',
            'total',
            'status',
            'created_at',
        ])
        ->latest()
        ->whereIn('id', $orders)
        ->take(5)
        ->get();
    }

    /**
     * Get latest five reviews.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getLatestReviews()
    {
        return Review::select('id', 'product_id', 'reviewer_name', 'rating')
            ->has('product')
            ->with('product:id')
            ->limit(5)
            ->get();
    }
}
