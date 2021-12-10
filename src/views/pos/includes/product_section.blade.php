<div class="menuItemArea container ps-4 pr-4 ps-md-5 pe-md-5 d-flex">
    <div class="tab-content" id="navigationTabContent">
        @foreach(ChuckCollection::all() as $collection)
        @if($collection->json['is_pos_available'] == true)
            <div 
                class="tab-pane fade{{$loop->index == 0 ? ' show active' : ''}}" 
                id="category-{{$collection->id}}-Tab" 
                role="tabpanel" 
                aria-labelledby="category-{{$collection->id}}-Tab">

                <div class="row">
                    @foreach (ChuckProduct::forCollection($collection->json['name']) as $product)
                        @if($product->json['is_pos_available'] == true)
                            {{$product->json['title'][(string)app()->getLocale()]}}
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach
        
        
        
        
        
        {{-- <div class="tab-pane fade show active" id="category55Tab" role="tabpanel" aria-labelledby="category55Tab">
            <div class="row">
                <div 
                    class="col-6 col-sm-4 col-md-3 p-1 cof_pos_product_card" 
                    data-product-id="25" 
                    data-product-category-id="55" 
                    data-product-name="6x Original" 
                    data-q="54=-1" 
                    data-vat-delivery="6" 
                    data-vat-takeout="6" 
                    data-vat-on-the-spot="12" 
                    data-current-price="7.950000" 
                    data-product-attributes="{{ json_encode(["name" => "Kaneel","price" => null,"image" => null]) }}" 
                    data-product-options="{{ json_encode([]) }}" 
                    data-product-extras="{{ json_encode([]) }}">
                    <div class="card shadow-sm">
                        <div class="card-body py-2 px-3">
                            <p class="card-title mb-2" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                                <small><b>6x Original</b></small>
                            </p>
                            <div class="row">
                                <div class="col">
                                    <small class="d-block card-subtitle mb-1 text-muted cof_productItemPriceDisplay" data-product-id="25" data-current-price="7.950000">
                                        <span class="cof_productItemUnitPrice" data-product-id="25" data-product-price="9.000000" data-has-discount="true" style="text-decoration:line-through">
                                            € 9,00
                                        </span>
                                        <span style="color:red;" class="cof_productItemDiscountPrice" data-product-id="25" data-discount-price="7.950000">
                                            € 7,95
                                        </span>
                                    </small> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>