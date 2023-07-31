<div id="pos_cart" class="bestelling col-5">

    @include('chuckcms-module-ecommerce::pos.cart._header')


    {{-- <div class="bestelTabHandler row">
        <nav class="nav nav-pills flex-column flex-sm-row cof_cartTabList" id="bestelNavigationTab" role="tablist">
            <a class="flex-sm-fill text-sm-center nav-link" id="pos_addNewCart" href="#">
                <span><i class="fas fa-plus"></i><span>
            </a>
            <a class="pos_cartTabListBtn flex-sm-fill text-sm-center nav-link active" id="cart_0123Tab" data-cart-id="cart_0123" href="#cart_0123" role="tab" data-toggle="tab" aria-controls="cart_0123Tab" aria-selected="true">
                <span>Cart: #1 (<span class="cof_cartTotalQuanity" data-cof-quantity="0">0</span>)</span>
                <span class="remove-tab cof_cartTabRemove"><i class="fas fa-times-circle"></i></span>
            </a>
        </nav>
    </div> --}}


    <div class="bestelTabArea row">
        <div class="tab-content" id="bestelNavigationTabContent">
            <div class="cof_cartTab tab-pane fade show active" id="cart_0123"  role="tabpanel" aria-labelledby="cart_0123Tab" data-cart-id="cart_0123">

                @include('chuckcms-module-ecommerce::pos.cart._products')




              {{-- <ul class="list-group mb-3" id="cof_CartProductList" style="display:none;">
                <li class="list-group-item d-flex justify-content-start lh-condensed cof_cartProductListItem" data-product-id="0" data-product-name="" data-attribute-name="" data-quantity="0" data-unit-price="0" data-total-price="0">
                  <div style="padding: 7px 15px 7px 0px;">
                    <img src="{{ asset('chuckbe/chuckcms-module-order-form/trash-solid.svg') }}" class="cof_deleteProductFromListButton" height="12" width="12" alt="Verwijder product" style="cursor:pointer;">
                  </div>
                  <div class="flex-fill cof_cartProductListDetails">
                    <h6 class="my-0 cof_cartProductListItemFullName">Product name</h6>
                    <small class="text-muted d-block"><span class="cof_cartProductListItemQuantity">1</span> x <span class="cof_cartProductListItemUnitPrice">€ 0,00</span></small>
                    <small class="text-muted d-none cof_cartProductListItemOptions">
                      <span class="cof_cartProductListItemOptionName">Optie 1</span>: <span class="cof_cartProductListItemOptionValue">Waarde</span>
                    </small>
                    <small class="text-muted d-none cof_cartProductListItemExtras">
                      <span class="cof_cartProductListItemOptionName">Optie 1</span> <span class="cof_cartProductListItemOptionValue">Waarde</span>
                    </small>
                  </div>
                  <span class="text-muted cof_cartProductListItemTotalPrice">€ 0,00</span>
                </li>
                <li class="list-group-item d-flex justify-content-between" id="cof_CartProductListShippingLine">
                  <span>Verzending (EUR)</span>
                  <strong class="cof_cartShippingPrice">€ 0,00</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between" id="cof_CartProductListPriceLine">
                  <span>Totaal (EUR)</span>
                  <strong class="cof_cartTotalPrice">€ 0,00</strong>
                </li>
              </ul> --}}

            </div>
        </div>
    </div>
    <div class="klantArea row">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                  <div class="row">
                      <div class="col-9 klantDetails">
                          <div class="col-2 klantIcon">
                            <i class="fas fa-user-circle"></i>
                          </div>
                          <div class="col-10 klantGegevens">
                              <p>Customer:</p>
                              <p id="cof_selectedCustomerEmail">guest@guest.com</p>
                          </div>
                      </div>
                      <div class="col-3 klantKoppeler">
                        <button class="btn btn-sm w-100" id="pos_selectCustomerAccount" data-guest="{{ $guest->id }}"><small><i class="fas fa-cog"></i></small></button>
                      </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    @include('chuckcms-module-ecommerce::pos.cart._price')
    <div class="betaalArea row">
        <div class="container">
            <button class="btn text-center d-block" id="pos_checkoutBtn" data-final="{{ ChuckCart::instance('pos')->final() }}">CHECKOUT & PAY</button>
        </div>
    </div>
</div>
