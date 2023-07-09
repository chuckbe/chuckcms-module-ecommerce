<div id="pos_CartProducts">
    @foreach(ChuckCart::instance('pos')->content() as $cartRow)
    <div class="bestelOrder pos_cartItem row align-items-center" data-sku="{{ $cartRow->id }}" data-row-id="{{ $cartRow->rowId }}" data-quantity="{{ $cartRow->qty }}">
        <div class="col-4 bestelOrderDetails">
            <div class="bestelOrderTitle cof_cartProductListDetails">
                <span class="cof_cartProductListItemFullName">{{ $cartRow->name }}</span>
                <small class="text-muted d-block">
                    <span class="cof_cartProductListItemQuantity">{{ $cartRow->qty }}</span> x <span class="cof_cartProductListItemUnitPrice">{{ ChuckEcommerce::formatPrice($cartRow->price) }}</span>
                </small>

                @foreach($cartRow->options as $oKey => $oValue)
                <small class="text-muted d-block">{{ ucfirst($oKey) }}: {{ $oValue.(!$loop->last ? ', ' : '') }}</small>
                @endforeach

                @foreach($cartRow->extras as $eKey => $eValue)
                <small class="text-muted d-block">{{ $eValue['qty'].'x '.$eKey.': '.ChuckEcommerce::formatPrice(floatval($eValue['final']) * (int)$eValue['qty']) }}</small>
                @endforeach

                <small class="text-muted d-none cof_cartProductListItemSubproducts">
                    <span class="cof_cartProductListItemSubproductGroupItems">
                        <ul class="pl-2 ps-2 mb-0">
                            <li>Product 1</li>
                        </ul>
                    </span>
                </small>
            </div>
        </div>
        <div class="col-6 bestelOrderQuantity">
            <div class="bestelOrderQuantityControl {{ $cartRow->qty > 1 ? 'pos_subtractProductQuantity' : 'pos_removeProduct' }} ml-0" style="cursor:pointer;">
                <div class="cof_deleteProductFromListButton">
                    <i class="fas fa-{{ $cartRow->qty > 1 ? 'chevron-down' : 'trash' }}"></i>
                </div>
            </div>
            <input type="text" class="pos_quantity" name="quantity" value="{{ $cartRow->qty }}" readonly>
            <div class="bestelOrderQuantityControl pos_addProductQuantity mr-0" style="cursor:pointer;">
                <div class="addbtn">
                    <i class="fas fa-chevron-up"></i>
                </div>
            </div>
        </div>
        <div class="col-2 text-right bestelOrderPrice cof_cartProductListItemTotalPrice">
          {{ ChuckEcommerce::formatPrice($cartRow->total) }}
        </div>
    </div>
    @endforeach
</div>
