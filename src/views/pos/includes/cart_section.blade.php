{{-- bestelheader starts --}}
<div class="bestelHeader row align-items-center py-3">
    <div class="col-lg-6 text-start h-100">
        <h4 class="bestelHeaderTitle">Bestelling</h4>
    </div>
    <div class="col-6 text-end bestelHeaderInstellingen h-100">
        <button type="button" class="btn shadow-sm deletealles"><i class="fas fa-trash"></i></button>
    </div>
</div>{{-- bestelheader ends --}}
{{-- bestelTabheader starts --}}
<div class="bestelTabHandler row">
    <nav class="nav nav-pills flex-column flex-sm-row cof_cartTabList" id="bestelNavigationTab" role="tablist">       
        <a class="flex-sm-fill text-sm-center nav-link" id="cof_cartTabListNewOrderLink" href="#">
            <span><i class="fas fa-plus"></i><span>
        </a>
        <a class="cof_cartTabListLink flex-sm-fill text-sm-center nav-link active" id="cart_0123Tab" data-cart-id="cart_0123" href="#cart_0123" role="tab" data-toggle="tab" aria-controls="cart_0123Tab" aria-selected="true">
            <span>Cart: #1 (<span class="cof_cartTotalQuanity" data-cof-quantity="0">0</span>)</span>
            <span class="remove-tab cof_cartTabRemove"><i class="fas fa-times-circle"></i></span>
        </a>
    </nav>
</div>{{-- bestelTabheader ends --}}
{{-- bestelTabArea starts --}}
<div class="bestelTabArea row">
    <div class="tab-content" id="bestelNavigationTabContent">
        <div class="cof_cartTab tab-pane fade show active" id="cart_0123"  role="tabpanel" aria-labelledby="cart_0123Tab" data-cart-id="cart_0123">
            <div class="cof_CartProductList">
                <div class="bestelOrder cof_cartProductListItem row align-items-center" data-product-id="0" data-product-name="" data-attribute-name="" data-quantity="0" data-unit-price="0" data-total-price="0">
                    <div class="col-lg-5 bestelOrderDetails">
                        <div class="bestelOrderTitle cof_cartProductListDetails">
                            <span class="cof_cartProductListItemFullName">Product Naam</span>
                            <small class="text-muted d-block"><span class="cof_cartProductListItemQuantity">1</span> x <span class="cof_cartProductListItemUnitPrice">€ 0,00</span></small>
                            <small class="text-muted d-none cof_cartProductListItemOptions">
                              <span class="cof_cartProductListItemOptionName">Optie 1</span>: <span class="cof_cartProductListItemOptionValue">Waarde</span>
                            </small>
                            <small class="text-muted d-none cof_cartProductListItemExtras">
                              <span class="cof_cartProductListItemOptionName">Optie 1</span> <span class="cof_cartProductListItemOptionValue">Waarde</span>
                            </small>
                        </div> 
                    </div> 
                    <div class="col-lg-4 bestelOrderQuantity">
                        <div class="bestelOrderQuantityControl trash cof_cartProductListItemSubtraction">
                            <div class="cof_deleteProductFromListButton" style="cursor:pointer;">
                                <i class="fas fa-trash"></i>
                            </div>
                        </div>
                        <input type="text" class="cof_cartProductListItemQuantity" name="quantity" readonly value="1">
                        <div class="bestelOrderQuantityControl cof_cartProductListItemAddition">
                            <div class="addbtn"><i class="fas fa-plus"></i></div>
                        </div>
                    </div> 
                    <div class="col-3 bestelOrderPrice cof_cartProductListItemTotalPrice">
                      € 100,95
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>{{-- bestelTabArea ends --}}
{{-- klantArea starts --}}
<div class="klantArea row my-3 mx-0">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-9 klantDetails">
                        <div class="col-lg-2 klantIcon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="col-lg-10 klantGegevens">
                            <p>Klant:</p>
                            <p id="cof_selectedCustomerEmail">guest@guest.com</p>
                        </div>
                    </div>
                    <div class="col-lg-3 klantKoppeler">
                        <button class="btn btn-sm w-100" id="cof_selectCustomerAccount" data-guest="1"><small><i class="fas fa-cog"></i></small></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>{{-- klantArea ends --}}
{{-- priceCalculatorArea  starts --}}
<div class="priceCalculatorArea row my-3 mx-0">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="subtotaal row">
                  <div class="col-lg-6 text-start">Subtotaal</div>
                  <div class="col-lg-6 text-end cof_cartSubtotalPrice">€ 0,00</div>
                </div>
                <div class="korting row">
                    <div class="col-lg-6 text-start">Korting</div>
                    <div class="col-lg-6 text-end cof_cartDiscountPrice">€ 0,00</div>
                </div>
                <div class="row">
                    <div class="col-lg-12" id="cof_cartCouponWrapper">
                      <span class="badge badge-primary badge-sm mt-1 me-1 cof_cartCouponItem d-none" data-coupon="" style="font-size:0.7rem!important">
                        <button type="button" class="close ms-1 cof_cartCouponItemRemoveBtn" style="font-size:1.2rem!important;line-height: 0.65!important" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="cof_couponText">PROMO10</span>
                      </span>
                    </div>
                </div>
                <hr class="priceCalculatorDivider"/>
                <div class="totaal row">
                    <div class="col-lg-6 text-start">Totaal</div>
                    <div class="col-lg-6 text-end tot-value cof_cartTotalPrice">€ 0,00</div>
                </div>
                <div class="btw row">
                    <div class="col-lg-6 text-start">BTW</div>
                    <div class="col-lg-6 text-end cof_cartTotalVatPrice">€ 0,00</div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- betaalArea starts --}}
<div class="betaalArea row position-absolute w-100">
    <div class="container">
        <button class="btn text-center d-block " id="cof_placeOrderBtnNow">Betalen</button>
    </div>
</div>{{-- betaalArea ends --}}
