<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\DiscountRepository;

use Chuckbe\ChuckcmsModuleEcommerce\Models\Discount;
use Chuckbe\Chuckcms\Models\Repeater;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DiscountController extends Controller
{
    private $customerRepository;
    private $discountRepository;

    /**
     * Create a new controller instance.
     *
     * @param  $discountRepository DiscountRepository
     * @return void
     */
    public function __construct(
        CustomerRepository $customerRepository, 
        DiscountRepository $discountRepository
    )
    {
        $this->customerRepository = $customerRepository;
        $this->discountRepository = $discountRepository;
    }

    public function index()
    {
        $discounts = $this->discountRepository->get();
        return view('chuckcms-module-ecommerce::backend.discounts.index', compact('discounts'));
    }

    public function create()
    {
        $customers = $this->customerRepository->get();

        return view('chuckcms-module-ecommerce::backend.discounts.create', compact('customers'));
    }

    public function edit(Repeater $discount)
    {
        $customers = $this->customerRepository->get();

        return view('chuckcms-module-ecommerce::backend.discounts.edit', compact('customers', 'discount'));
    }

    public function save(Request $request)
    {
        $this->validate($request, [ 
            'name' => 'max:185|required',
            'description' => 'nullable',
            'code' => 'required|max:40',
            'priority' => 'required',
            'highlight' => 'required|in:0,1',
            'active' => 'required|in:0,1',
            
            'valid_from' => 'required|date',
            'valid_until' => 'required|date',
            'minimum' => 'required',
            'minimum_vat_included' => 'required',
            'minimum_shipping_included' => 'required',
            'available_total' => 'required',
            'available_customer' => 'required',

            'customers' => 'sometimes|array',
            'customer_groups' => 'required|array',

            'condition_min_quantity' => 'array|required',
            'condition_type' => 'nullable|array',
            'condition_value' => 'nullable|array',

            'action_type' => 'required|in:percentage,currency,gift',
            'action_value' => 'required',

            'id' => 'required_with:update'
        ]);

        if($request->has('id') && $request->has('update')) {
            $discount = $this->discountRepository->update($request);
        } elseif($request->has('create')) {
            $discount = $this->discountRepository->create($request);
        }

        if($discount->save()){
            return redirect()->route('dashboard.module.ecommerce.discounts.index');
        } else {
            return 'error';//add ThrowNewException
        }

        return view('chuckcms-module-ecommerce::backend.discounts.create');
    }

    public function delete(Request $request)
    {
        $this->validate($request, ['discount_id' => 'required']);

        $delete = $this->discountRepository->delete($request->get('discount_id'));

        if($delete){
            return response()->json(['status' => 'success']);
        }
    }

    public function refreshCode(Request $request)
    {
        return response()->json(['status' => 'success', 'code' => $this->discountRepository->generateCode()]);
    }
}
