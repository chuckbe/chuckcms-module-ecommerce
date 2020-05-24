<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\Chuckcms\Models\Template;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\AttributeRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\BrandRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CollectionRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\OrderRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use ChuckEcommerce;

class AccountOrderController extends Controller
{
    private $attributeRepository;
    private $brandRepository;
    private $collectionRepository;
    private $productRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        BrandRepository $brandRepository,
        CollectionRepository $collectionRepository,
        ProductRepository $productRepository,
        OrderRepository $orderRepository)
    {
        $this->attributeRepository = $attributeRepository;
        $this->brandRepository = $brandRepository;
        $this->collectionRepository = $collectionRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        $template = ChuckEcommerce::getTemplate();

        $orders = $this->orderRepository->getForCustomer(Auth::user()->customer);

        return view($template->hintpath.'::templates.'.$template->slug.'.account.order.index', compact('template', 'orders'));
    }

    public function detail($order_number)
    {
        $template = ChuckEcommerce::getTemplate();

        $order = $this->orderRepository->getByOrderNumber($order_number);

        if($order->customer_id !== Auth::user()->customer->id) {
            return redirect()->back();
        }

        return view($template->hintpath.'::templates.'.$template->slug.'.account.order.detail', compact('template', 'order'));
    }

}
