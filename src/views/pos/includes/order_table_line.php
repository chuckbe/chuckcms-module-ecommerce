<tr>
  <th scope="row">{{ str_pad($order->id, mb_strlen($ordersCount, 'utf8'), '0', STR_PAD_LEFT) }} <br> <small>#{{ $order->entry['order_number'] }}</small></th>
  <td>{{ $order->created_at->format('d/m/Y - H:i:s') }}</td>
  <td>€ {{ number_format((float)$order->entry['order_price'], 2, ',', '.') }}</td>
  <td><button class="btn btn-sm btn-light" type="button" data-toggle="collapse" data-target="#collapseOrder{{ $order->id }}" aria-expanded="false" aria-controls="collapseOrder{{ $order->id }}">
    Meer
  </button></td>
</tr>
<tr>
    <td colspan="4" class="p-0">
        <div>
            <div class="collapse" id="collapseOrder{{ $order->id }}">
                <div class="card card-body border-0">
                    <div class="row">
                        <div class="col-12">
                            {{ $order->entry['first_name'] . ' ' . $order->entry['last_name'] }} <br>
                            <a href="mailto:{{ $order->entry['email'] }}">{{ $order->entry['email'] }}</a> <br>

                            @if($order->entry['tel'] !== null)
                            <a href="tel:{{ $order->entry['tel'] }}">{{ $order->entry['tel'] }}</a> <br>
                            @endif

                            @if($order->entry['street'] !== null)
                            <small>{{ $order->entry['street'].' '.$order->entry['housenumber'].', '.$order->entry['postalcode'].' '.$order->entry['city'] }}</small>
                            @endif
                        </div>
                        <div class="col-12">
                            <hr class="mb-0">
                        </div>
                        <div class="col-12">
                            @foreach($order->entry['items'] as $item)
                            <div class="row py-2 border-bottom order_line" data-id="{{ $item['id'] }}">
                                <div class="col-7">
                                    {{ $item['attributes'] == false ? $item['name'] : $item['name'] . ' - ' . $item['attributes'] }} <br>
                                    @if($item['options'] !== false)
                                        <small>
                                            @foreach($item['options'] as $option)
                                                {{ $option['name'] }}: {{ $option['value'] }}<br>
                                            @endforeach
                                        </small>
                                        @if(array_key_exists('extras', $item) && $item['extras'] !== false)
                                        <br>
                                        @endif
                                    @endif

                                    @if(array_key_exists('extras', $item) && $item['extras'] !== false)
                                        <small>
                                            @foreach($item['extras'] as $option)
                                                {{ $option['name'] }} (€ {{ $option['value'] }})<br>
                                            @endforeach
                                        </small>
                                    @endif
                                    @if(array_key_exists('subproducts', $item) && $item['subproducts'] !== false)
                                        <small>
                                            @foreach($item['subproducts'] as $subproduct)
                                                {{ $subproduct['name'] }}<br>
                                                <ul>
                                                    @foreach ($subproduct['products'] as $product)
                                                        <li>{{$product['p_name']}} x {{$product['p_qty']}} {{$product['p_extra_price'] !== 0 ? '( extra-price: € '.number_format(floatval($product['p_extra_price'])*$product['p_qty'], 2, ',', '.').' )': '' }}</li>
                                                    @endforeach
                                                </ul>
                                            @endforeach
                                        </small>
                                    @endif
                                </div>
                                <div class="col-2">{{ $item['qty'] }}x</div>
                                <div class="col-3">{{ $item['totprice'] }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
</tr>
