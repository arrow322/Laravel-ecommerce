<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Webkul\Customer\Models\Customer;

/**
 * Customer controlller for the customer
 * basically for the tasks of customers
 * which will be done after customer
 * authenticastion.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    public function __construct()
    {
        $this->_config = request('_config');
    }

    /**
     * For taking the customer
     * to the dashboard after
     * authentication
     * @return view
     */
    private function getCustomer($id)
    {
        $customer = collect(Customer::find($id));
        return $customer;
    }

    public function dashboard()
    {
        $id = auth()->guard('customer')->user()->id;
        $customer = $this->getCustomer($id);
        return view($this->_config['view'])->with('customer', $customer);
    }

    public function editProfile()
    {
        $id = auth()->guard('customer')->user()->id;
        $customer = $this->getCustomer($id);
        return view($this->_config['view'])->with('customer', $customer);
    }
}
