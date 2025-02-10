<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Session;
use ShoppingCart;

class CheckoutController extends Controller
{
    private $customer, $order, $orderDetail;


    public function index()
    {
        if (Session::get('customer_id'))
        {
            $this->customer = Customer::find(Session::get('customer_id'));
        }
        else
        {
            $this->customer = '';
        }
        return view('website.checkout.index', ['customer' => $this->customer]);
    }


    private function orderCustomerValidate($request)
    {
        $this->validate($request, [
            'name'              => 'required',
            'email'             => 'required|unique:customers,email',  //customers=table,email=column
            'mobile'            => 'required|unique:customers,mobile', //customers=table,mobile=column
            'delivery_address'  => 'required',
        ]);
    }


    public function newCashOrder(Request $request)
    {
        if (Session::get('customer_id'))
        {
            $this->customer = Customer::find(Session::get('customer_id'));
        }
        else
        {
            $this->orderCustomerValidate($request);

            $this->customer = Customer::newCustomer($request); //$this->customer for return self::$customer; Customer Model

            //for customer login to system
            Session::put('customer_id', $this->customer->id);
            Session::put('customer_name', $this->customer->name);
        }


        $this->order = Order::newOrder($request, $this->customer->id); //$this->order for return self::$order; Order Model

        OrderDetail::newOrderDetail($this->order->id);

        return redirect('/complete-order')->with('message','Congratulations! Your order has been placed successfully. Please hold on, we will contact you shortly. We truly appreciate your trust in us!');

    }


    public function completeOrder()
    {
        return view('website.checkout.complete-order');
    }

}
