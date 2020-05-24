<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    private $customerRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index()
    {
        $customers = $this->customerRepository->get();
        return view('chuckcms-module-ecommerce::backend.customers.index', compact('customers'));
    }

    public function detail(Customer $customer)
    {
        return view('chuckcms-module-ecommerce::backend.customers.detail', compact('customer'));
    }
}
