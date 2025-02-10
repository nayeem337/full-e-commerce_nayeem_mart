<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;


class Order extends Model
{
    use HasFactory;

    private static $order;

    public static function newOrder($request, $customerId)
    {
        self::$order = new Order();
        self::$order->customer_id        = $customerId;  // $this->customer->id=$customerId
        self::$order->order_date         = date('Y-m-d');
        self::$order->order_timestamp    = strtotime(date('Y-m-d')); //for date to convert in number
        self::$order->order_total        = Session::get('order_total');    // checkout index.blade.php
        self::$order->tax_total          = Session::get('tax_total');      // checkout index.blade.php
        self::$order->shipping_total     = Session::get('shipping_total'); // checkout index.blade.php
        self::$order->delivery_address   = $request->delivery_address;
        self::$order->payment_type       = $request->payment_type;
        self::$order->save();

        return self::$order;
    }

    public static function deleteOrder($id)
    {
        self::$order = Order::find($id);
        self::$order->delete();
    }

    public function customer() // Order -> Customer table = oneToOne [1 order = 1 customer]
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderDetails() // Order -> Product table = oneToMany [1 order = many products]
    {
        return $this->hasMany(OrderDetail::class);
    }

}
