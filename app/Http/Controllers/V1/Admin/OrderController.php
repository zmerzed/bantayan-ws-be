<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use Kolette\Marketplace\Http\Resources\OrderResource;
use Kolette\Marketplace\Models\Order;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collection = QueryBuilder::for(Order::class)
            ->allowedSorts(
                'id',
                'created_at',
                'raw-amount',
                'order_number',
                'status',
                'total_points_gained',
                AllowedSort::field('quantity', 'total_quantity'),
                AllowedSort::field('amount', 'raw_amount')
            )
            ->allowedIncludes(
                'customer',
                'customer.avatar',
                'seller',
                'seller.avatar',
                'seller.businessInformation',
                'orderDetails.product.photos',
                'orderDetails.options',
                AllowedInclude::count('orderDetails')
            )
            ->allowedFilters(
                AllowedFilter::scope('status')->ignore(null),
                AllowedFilter::scope('search')->ignore(null),
                AllowedFilter::scope('order_date')->ignore('null'),
                AllowedFilter::scope('amount_range')->ignore(null),
                AllowedFilter::scope('status_set')->ignore(null),
                AllowedFilter::scope('order_date_range')->ignore(null)
            )
            ->getOrPaginate();

        return OrderResource::collection($collection);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
