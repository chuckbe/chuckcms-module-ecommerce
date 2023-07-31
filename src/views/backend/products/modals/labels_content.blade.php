<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="labelModalLabel">{{ ChuckProduct::title($product) }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12 form-group brand_area">
                <label for="brandname" class="form-label">Brand</label>
                <input type="text" id="brandname" class="form-control" value="{{ ChuckProduct::brand($product) }}" readonly>
            </div>
            @if(count($product->json['combinations']) == 0)
            <div class="col-12 form-group single_product_area">
                <div class="product_area mb-3">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" id="product_name" class="form-control" aria-describedby="product_name" value="{{ ChuckProduct::title($product) }}" readonly>
                </div>
                <div class="barcode_area mb-3">
                    <label for="barcode" class="form-label">Barcode</label>
                    <input type="text" id="barcode" class="form-control" aria-describedby="barcode" value="{{ $product->json['code']['ean'] }}" readonly>
                </div>
                <div class="row">
                    <div class="col price_area">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" id="price" class="form-control" aria-describedby="price" value="{{ ChuckEcommerce::formatPrice($product->json['price']['final']) }}" readonly>
                    </div>

                    <div class="col quantity_area">
                        <label for="price" class="form-label">Quantity</label>
                        <input type="number" id="quantity" class="form-control" aria-describedby="quantity" max="{{ $product->json['quantity'] }}" min="0" value="{{ $product->json['quantity'] }}">
                    </div>
                    <div class="col attributes_area">
                        @php
                        $attributes = $product->attributes ?? [];
                        $attributesText = '';
                        foreach ($attributes as $attribute) {
                            if ($attributesText !== '') {
                                $attributesText .= ' ';
                            }

                            $attributesText .= $attribute['display_name'][app()->getLocale()];
                        }
                        @endphp
                        <label for="attributes" class="form-label">Attributes</label>
                        <input type="text" id="attributes" class="form-control" aria-describedby="attributes" value="{{ $attributesText }}" readonly>
                    </div>
                </div>
            </div>
            @elseif(count($product->json['combinations']) > 0)
            <div class="col-12 form-group combination_area">
                <p>Available combinations</p>
                <table class="table combinations_row">
                    <thead>
                      <tr>
                        <th></th>
                        <th scope="col">combination</th>
                        <th scope="col">price per item</th>
                        <th scope="col">quantity</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($product->json['combinations'] as $combinationKey => $combination)
                        <tr class="combination_item"
                            data-product="{{ ChuckProduct::title($product) }}"
                            data-ean="{{ $combination['code']['ean'] }}"
                            data-attr="{{ Str::replace('__', ', ', $combinationKey) }}"
                            data-price="{{ ChuckEcommerce::formatPrice($combination['price']['final']) }}"
                            data-qty="{{ $combination['quantity'] }}"
                        >
                            <td><input class="combination_active" type="checkbox" @if($combination['quantity'] > 0) checked="checked" @endif name=""></td>
                            <td class="combination-name">{{ $combination['display_name'][app()->getLocale()] }}</td>
                            <td class="combination-price">{{ ChuckEcommerce::formatPrice($combination['price']['final']) }}</td>
                            <td  class="">
                                <input type="number" class="combination-quantity" value="{{ $combination['quantity'] }}" min="{{ $combination['quantity'] }}" max="{{ $combination['quantity'] }}">
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary combination-print print-btn" data-product-type="single_combi_product" @if($combination['quantity'] == 0) disabled @endif>Print</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <div class="row w-100">
            <div class="col-12 col-lg-6">
                <div class="d-flex w-100 align-items-center">
                    {{-- <span class="px-1" data-bind="html: printerConnectedIndicator, visible: printerChecked()"></span>
                    <small data-bind="text: printerName, visible: printerChecked()"></small> --}}
                </div>
            </div>
            <div class="col-12 col-lg-6 d-flex">
                <button type="button" class="btn btn-secondary ml-lg-auto" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary ml-lg-2 print-btn" data-product-type="{{ count($product->json['combinations']) > 0 ? 'multi_combi_products' : 'single' }}">Print</button>
            </div>
        </div>

    </div>
</div>
