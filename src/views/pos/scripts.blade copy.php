<script>
    var cart = [];
    if (localStorage.getItem("cart") !== null) {
        let storedCart = JSON.parse(localStorage.getItem('cart'));
        cart = storedCart;
        console.log(storedCart);
        storedCart.forEach((billItem)=> {
            let bestelNavigation = $('#bestelNavigationTab');
            let $tab = $(`<a 
                            class="flex-sm-fill text-sm-center nav-link" 
                            id="bestelNavigation${billItem.rekening}Tab" 
                            href="#${billItem.rekening}" 
                            role="tab" data-toggle="tab" 
                            aria-controls="${billItem.rekening}Tab" 
                            aria-selected="true" 
                            data-bestel-id="${billItem.rekening}">
                                <span>bestelcode: #${(billItem.rekening).match(/\d/g).join("")}</span>
                                <span class="remove-tab"><i class="fas fa-times-circle"></i></span>
                        </a>`);
            let $tabPane = $(`<div 
                                class="tab-pane fade show" 
                                id="${billItem.rekening}"  
                                role="tabpanel" 
                                aria-labelledby="${billItem.rekening}Tab" 
                                data-bestel-id="${billItem.rekening}">
                                </div>`); 

            if(billItem.state === 'active') {
                $('#bestelNavigationTab').children().removeClass("active");
                $('#bestelNavigationTabContent').children().removeClass("active");
                $tab.addClass("active");
                $tabPane.addClass("active");
            }
            $('#bestelNavigationTab #bestelNavigationnNieuweBestellingTab').after($tab);
            $('#bestelNavigationTabContent').prepend($tabPane);
            let bestelPane = $('#bestelNavigationTabContent').find(`[data-bestel-id='${billItem.rekening}']`);
            if(bestelPane.attr("data-bestel-id") == billItem.rekening) {
                bestelPane.empty();
                for( let key in product.productData.json.images) {
                    if(product.productData.json.images[key].is_featured === true) {
                        let url = window.location.protocol + "//" + location.host.split(":")[0];
                        featured_img = url+product.productData.json.images[key].url.replace(" ","%20");
                    }
                }
                let newOrder = $(`
                    <div class="bestelOrder row align-items-center" data-product-id=${product.productData.id}>
                        <div class="col-5 bestelOrderDetails">
                            <div class="col bestelOrderImg">
                                <img src="${featured_img}" class="img-fluid" alt="${product.productData.json.title.nl}">
                            </div>
                            <div class="col bestelOrderTitle">
                                <span>${product.productData.json.title.nl}</span>
                            </div> 
                        </div>
                        <div class="col-4 bestelOrderQuantity">
                            <div class="bestelOrderQuantityControl trash">
                                <div class="deletebtn">
                                    ${(product.quantity > 1) ? '<i class="fas fa-minus"></i>': '<i class="fas fa-trash"></i>'}
                                </div>
                            </div>
                            <input type="text" id="quantity_product${product.productData.id}" name="quantity" value="${product.quantity}">
                            <div class="bestelOrderQuantityControl">
                                <div class="addbtn"><i class="fas fa-plus"></i></div>
                            </div>
                        </div>
                        <div class="col-3 bestelOrderPrice">
                            € ${parseFloat(product.productData.json.price.final * product.quantity).toFixed(2).replace(".", ",")}
                        </div>
                    </div>
                `);
                console.log(parseFloat(product.productData.json.price.final * product.quantity).toFixed(2).replace(".", ","));
                bestelPane.append(newOrder);
            }
        });
    }
    let GenRandom =  {
        Stored: [],
        Job: function(){
            let newId = Date.now().toString().substr(6); // or use any method that you want to achieve this string
            if( !this.Check(newId) ){
                this.Stored.push(newId);
                return newId;
            }
            return this.Job();
        },
        Check: function(id){
            for( let i = 0; i < this.Stored.length; i++ ){
                if( this.Stored[i] == id ) return true;
            } 
            return false;
        }
    }
    function getAllCartsFromStorage() {
        if(localStorage.getItem('cof_carts') === null) {
            carts = [];
            localStorage.setItem('cof_carts', JSON.stringify(carts));
        } else {
            carts = JSON.parse(localStorage.getItem('cof_carts'));
        }

        return carts;
    }

    function getCartFromStorage(cartId) {
        let carts = getAllCartsFromStorage();

        for (var g = 0; g < carts.length; g++) {
            if(carts[g].id == cartId) {
                return carts[g];
            }
        } 
    }

    $(document).ready(function() {

        $('body').on('click', '#cof_addSelectedCouponToCartBtn', function (event) {
            event.preventDefault();
            cart_id = getActiveCart();
            addSelectedCouponToCart(cart_id);
        });



        // creates new tab
        $('#bestelNavigationnNieuweBestellingTab').on("click", function(e) {
            e.preventDefault();
        });

        function addSelectedCouponToCart(cartId) {
            let coupon_elements = $('input[name="coupon_selector"]').prop("checked", true);
            $.each(coupon_elements, function(index, item){
                let coupon = {
                    id: $(item).val(),
                    name: $(item).attr('data-name'),
                    active: $(item).attr('data-active') == 1 ? true : false,
                    valid_from: parseInt($(item).attr('data-valid-from')),
                    valid_until: parseInt($(item).attr('data-valid-until')),

                    customers: $(item).attr('data-customers').length > 0 ? $(item).attr('data-customers').split(',') : [],
                    minimum: parseInt($(item).attr('data-minimum')),
                    available: {
                        total: parseInt($(item).attr('data-available-total')),
                        customer: parseInt($(item).attr('data-available-customer'))
                    },
                    conditions: JSON.parse($(item).attr('data-conditions')),
                    type: $(item).attr('data-discount-type'),
                    value: $(item).attr('data-discount-value'),
                    used_by: []
                }
                removeCouponErrorText();
                if (!isCouponValidForCart(coupon, cartId)) {
                    updateCouponErrorText(isCouponValidForCart(coupon, cartId, false));
                    return false;
                }

                addCouponToCart(coupon, cartId);
                closeCouponModal();
            });
        }

        function isCouponValidForCart(coupon, cartId, status = true) {
            let _now = Math.floor(Date.now() / 1000);

            if (!coupon.active) {
                return status ? false : 'Coupon is niet meer actief';
            }

            if (coupon.valid_from > _now) {
                return status ? false : 'Coupon is nog niet geldig';
            }

            if (coupon.valid_until < _now) {
                return status ? false : 'Coupon is niet meer geldig';
            }

            if (coupon.available.total == 0) {
                return status ? false : 'Coupon kan niet meer gebruikt worden';
            }

            if (!isCouponValidForCustomer(coupon, cartId)) {
                return status ? false : 'Coupon is niet geldig voor geselecteerde klant';
            }

            if (!isCouponCompatibleWithCart(coupon, cartId)) {
                return status ? false : 'Coupon kan niet gecombineerd worden met bestaande coupons';
            }

            if (!isCartMinimumReachedForCoupon(coupon, cartId)) {
                return status ? false : 'Winkelwagen heeft niet genoeg winkelwaarde voor coupon';
            }

            if (!isCouponPassingConditions(coupon, cartId)) {
                return status ? false : 'Winkelwagen heeft niet de juiste inhoud voor coupon';
            }

            return status ? true : '';
        }

        function isCouponValidForCustomer(coupon, cartId) {
            let cart = getCartFromStorage(cartId);

            if (coupon.customers == "") {
                return true;
            }

            if (!Array.isArray(coupon.customers)) {
                return true;
            }

            if (Array.isArray(coupon.customers) && coupon.customers.length == 0) {
                return true;
            }

            for (var cc = 0; cc < coupon.customers.length; cc++) {
                if (cart.customer_id == parseInt(coupon.customers[cc])) {
                    return true;
                }
            };

            //@TODO:coupon.available.customer ++ coupon.used_by (if not guest)
            
            return false;
        }

        function isCouponCompatibleWithCart(coupon, cartId) {
            let cart = getCartFromStorage(cartId);

            if (coupon.uncompatible_discounts == "") {
                return true;
            }

            if (!Array.isArray(coupon.uncompatible_discounts)) {
                return true;
            }

            if (Array.isArray(coupon.uncompatible_discounts) && coupon.uncompatible_discounts.length == 0) {
                return true;
            }

            if (coupon.remove_incompatible) {
                return true;
            }

            for (var cc = 0; cc < coupon.uncompatible_discounts.length; cc++) {
                for (var cd = 0; cd < cart.discounts.length; cd++) {
                    if (coupon.uncompatible_discounts[cc] == cart.discounts[cd].id) {
                        return false;
                    }  
                };
            };

            for (var cd = 0; cd < cart.discounts.length; cd++) {
                for (var cdud = 0; cdud < cart.discounts[cd].uncompatible_discounts.length; cdud++) {
                    if (cart.discounts[cd].uncompatible_discounts[cdud] == coupon.id) {
                        return false;
                    } 
                };        
            };

            return true;
        }

        function getActiveCart() {
            cart_id = $('.cof_cartTabListLink.active').attr('data-cart-id');
            return cart_id;
        }

        function removeCouponErrorText() {
            $('#cof_couponErrorText').text('');
            $('#cof_couponErrorText').addClass('d-none');
        }

        function productNeedsModal(product_id) {
            let productNeedsModal = false;

            if(productHasAttributes(product_id)) {
                productNeedsModal = true;
            }

            if(productHasOptions(product_id)) {
                productNeedsModal = true;
            }

            if(productHasExtras(product_id)) {
                productNeedsModal = true;
            }

            return productNeedsModal;
        }

        function productHasAttributes(product_id) {
            attributes = $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-attributes');
            if(attributes == '[]') { 
                return false;
            }
            // let json = $.parseJSON($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-attributes'));
            // console.log(json[0]['key']);
            // // $.each(json, function(index, item){
            // //     if(item.key == 'algemeen'){
            // //         return false; 
            // //     }else{
            // //         continue;
            // //     }
            // // });

            return true;
        }

        function productHasOptions(product_id) {
            options = $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-options');
            
            if(options == '[]') {
                return false;
            }

            return true;
        }

        function productHasExtras(product_id) {
            extras = $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-extras');
            
            if(extras == '[]') {
                return false;
            }

            return true;
        }

        function resetOptionsModal()
        {
            $('#attributesModalBody').addClass('d-none');
            $('.attributes_modal_item_button_group').attr('data-product-id', '');
            $('.attributes_modal_item_button:not(:first)').remove();
            $('.attributes_modal_item_button:first').removeClass('active');
            $('.attributes_modal_item_button:first').find('input').attr('id', '').prop('checked', false);
            $('.attributes_modal_item_button:first').find('input').attr('data-attribute-name', '');

            $('.options_modal_row:not(:first)').remove();
            $('.options_modal_row:first').find('.cof_options_radio_item_input:not(:first)').remove();
            $('.options_modal_row:first').find('.cof_options_select_item_input').find('option:not(:first)').remove();
            $('.options_modal_row:first').attr('data-product-option-type', '');
            $('.options_modal_row:first').attr('data-product-option-name', '');
            $('.options_modal_row:first').find('.cof_options_radio_item_input:first').find('input').attr('name', 'cof_options_radio');

            $('.extras_modal_row:not(:first)').remove();
            $('.extras_modal_row:first').find('.extras_item_name').text('cof_extra_name');
            $('.extras_modal_row:first').find('.extras_item_name').attr('for', 'cof_extra_name');
            $('.extras_modal_row:first').find('.extras_item_checkbox').attr('id', 'cof_extra_name');
            $('.extras_modal_row:first').find('.extras_item_checkbox').val('');
            $('.extras_modal_row:first').find('.extras_item_checkbox').attr('data-product-extra-item-price', '');
            $('.extras_modal_row:first').find('.extras_item_checkbox').attr('data-product-extra-item-vat-delivery', '');
            $('.extras_modal_row:first').find('.extras_item_checkbox').attr('data-product-extra-item-vat-takeout', '');
            $('.extras_modal_row:first').find('.extras_item_checkbox').attr('data-product-extra-item-vat-on-the-spot', '');
        }

        function setOptionsModal(product_id, product_name, current_price, quantity, total_price, product_attributes, product_options, product_extras)
        {
            $('.options_product_name').text(product_name);
            $('#addProductFromModalToCartButton').attr('data-product-id', product_id);
            $('#addProductFromModalToCartButton').attr('data-current-price', current_price);
            $('#addProductFromModalToCartButton').attr('data-quantity', quantity);
            $('#addProductFromModalToCartButton').attr('data-total-price', total_price);


            if(Object.keys(product_attributes).length > 0) {
                $('#attributesModalBody').removeClass('d-none');
                $('.attributes_modal_item_button_group').attr('data-product-id', product_id);
                $('#attributelist').empty();
                $('#attributedata').empty();
                let iterator = 0;
                for(let [key, value] of Object.entries(product_attributes)){
                    attribute_name = value['display_name']['{{app()->getLocale()}}'];
                    $('#attributelist').append(`
                        <li class="nav-item">
                            <button 
                                class="nav-link${iterator == 0 ? ' active' : ''}"
                                id="${key}-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#${value['key']+'-'+key}"  
                                type="button"
                                role="tab"
                                aria-controls="${value['key']+'-'+key}"  
                                aria-selected="${iterator == 0 ? 'true' : 'false'}"
                            >
                                ${attribute_name}
                            </button>
                        </li>
                    `);
                    $('#attributedata').append(`
                        <div class="tab-pane fade${iterator == 0 ? ' show active' : ''}" id="${value['key']+'-'+key}" role="tabpanel" aria-labelledby="${key}-tab">
                            <div class="options_modal_item_radio">
                                <label for="" class="options_item_name">Kies ${value['key']}</label>
                                <div class="form-group cof_options_radio_item_input_group mb-2">
                                    <div class="cof_options_radio_item_input py-3">
                                        <label class="form-check-label" for="exampleRadios1">
                                            <input class="form-check-input" type="radio" name="cof_options_radio" id="exampleRadios1" value="option1">
                                            <span> Default radio</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                    $(`#attributedata #${value['key']+'-'+key} .options_modal_item_radio .cof_options_radio_item_input_group .cof_options_radio_item_input`).empty();
                    $.each(value['values'], function(inx, value){
                        $(`#attributedata #${value['key']+'-'+key} .options_modal_item_radio .cof_options_radio_item_input_group .cof_options_radio_item_input`).append(`
                            <div class="form-check">
                                <label class="btn btn-secondary mr-2 mb-3" for="cof_options_radio_${inx}_${value['key']+'-'+key}">
                                    <input
                                        id="cof_options_radio_${inx}_${value['key']+'-'+key}"
                                        type="radio" 
                                        name="cof_options_radio_${value['key']+'-'+key}"
                                        value="${value.value}"
                                    >
                                    <span>${inx}</span>
                                </label>
                            </div>
                        `);
                    });
                    // console.log(value);
                    iterator++;
                }

            }


            radio_ids = [];
            if(product_options.length > 0) {
                $('#optionsModalBody').removeClass('d-none');
                for (var i = 0; i < product_options.length; i++) {
                    if(i > 0) {
                        $('.options_modal_row:first').clone().appendTo('#optionsModalBody');
                    }

                    option_name = product_options[i]['name'];
                    option_type = product_options[i]['type'];
                    option_values = product_options[i]['values'].split(',');
                    
                    $('.options_modal_row:last').attr('data-product-option-type', option_type);
                    $('.options_modal_row:last').attr('data-product-option-name', option_name);
                    $('.options_modal_row:last').find('.options_item_name').text(option_name);
                    
                    if(option_type == 'radio') {
                        $('.options_modal_row:last').find('.options_modal_item_radio').removeClass('d-none hidden');
                        $('.options_modal_row:last').find('.options_modal_item_select').addClass('d-none hidden');

                        $('.options_modal_row:last').find('.cof_options_radio_item_input:not(:first)').remove();

                        $('.options_modal_row:last').attr('data-product-option-input-name', 'cof_options_radio_'+i);

                        for (var k = 0; k < option_values.length; k++) {
                            if(k > 0) {
                                $('.options_modal_row:last').find('.cof_options_radio_item_input:first').clone().appendTo('.options_modal_row:last .cof_options_radio_item_input_group');
                            }
                            $('.options_modal_row:last')
                                .find('.cof_options_radio_item_input:last input:first').first()
                                .val(option_values[k])
                                .attr('id', 'cofOptionsRadioId'+i+'_'+k)
                                .attr('name', 'cof_options_radio_'+i)
                                .attr('checked', false);
                            $('.options_modal_row:last').find('.cof_options_radio_item_input:last').find('span').text(option_values[k]).attr('for', 'cofOptionsRadioId'+i+'_'+k);
                            $('.options_modal_row:last').find('.cof_options_radio_item_input:last').find('label').attr('for', 'cofOptionsRadioId'+i+'_'+k);

                            if(k == 0) {
                                radio_ids.push('#cofOptionsRadioId'+i+'_'+k);
                            }

                        }

                    } 
                    if(option_type == 'select') {
                        $('.options_modal_row:last').find('.options_modal_item_radio').addClass('d-none hidden');
                        $('.options_modal_row:last').find('.options_modal_item_select').removeClass('d-none hidden');

                        $('.options_modal_row:last').find('.cof_options_select_item_input').attr('name', 'cof_options_select'+i);
                        $('.options_modal_row:last').find('.cof_options_select_item_input').attr('id', 'cof_options_selectId'+i);

                        $('#cof_options_selectId'+i).find('.cof_options_option_input:not(:first)').remove();
                        $('.options_modal_row:last').find('.cof_options_radio_item_input:not(:first)').remove();
                        $('.options_modal_row:last').find('.cof_options_radio_item_input:first').attr('name', 'cof_options_radio').attr('id', '');

                        $('.options_modal_row:last').find('.cof_options_radio_item_input:first').find('label:first').attr('for', '');
                        $('.options_modal_row:last').find('.cof_options_radio_item_input:first').find('input:first').attr('name', 'cof_options_radio').attr('id', '').val('');

                        $('.options_modal_row:last').attr('data-product-option-input-name', 'cof_options_select'+i);

                        for (var j = 0; j < option_values.length; j++) {
                            if(j > 0) {
                                $('#cof_options_selectId'+i+' .cof_options_option_input:first').first().clone().appendTo('#cof_options_selectId'+i);
                            }

                            $('#cof_options_selectId'+i).find('.cof_options_option_input:last').last().val(option_values[j]);
                            $('#cof_options_selectId'+i).find('.cof_options_option_input:last').last().text(option_values[j]);
                            $('#cof_options_selectId'+i).find('.cof_options_option_input:last').last().prop('selected', false);

                        }
                        $('#cof_options_selectId'+i).val($('#cof_options_selectId'+i).find('option').first().val());
                    }
                    
                };

                for (var i = 0; i < radio_ids.length; i++) {
                    $(''+radio_ids[i]+'').prop('checked', true);
                };
                
            } else {
                $('#optionsModalBody').addClass('d-none');
            }


            if(product_extras.length > 0) {
                $('#extrasModalBody').removeClass('d-none');
                for (var i = 0; i < product_extras.length; i++) {
                    if(i > 0) {
                        $('.extras_modal_row:first').clone().appendTo('#extrasModalBody');
                    }

                    extra_name = product_extras[i]['name'];
                    extra_slug = extra_name.toLowerCase().replace(/ /g,'-').replace(/[-]+/g, '-').replace(/[^\w-]+/g,'');
                    extra_price = product_extras[i]['price'];
                    extra_vat_delivery = product_extras[i]['vat_delivery'];
                    extra_vat_takeout = product_extras[i]['vat_takeout'];
                    extra_vat_on_the_spot = product_extras[i]['vat_on_the_spot'];
                    
                    //$('.extras_modal_row:last').attr('data-product-option-type', option_type);
                    //$('.extras_modal_row:last').attr('data-product-option-name', option_name);
                    $('.extras_modal_row:last').find('.extras_item_name')
                                    .text( extra_name + ' (€ ' + parseFloat(extra_price).toFixed(2).replace('.', ',') + ')' );
                    $('.extras_modal_row:last').find('.extras_item_name').attr('for', extra_slug);
                    $('.extras_modal_row:last').find('.extras_item_checkbox').attr('id', extra_slug);
                    $('.extras_modal_row:last').find('.extras_item_checkbox').val(extra_name);
                    $('.extras_modal_row:last').find('.extras_item_checkbox').attr('data-product-extra-item-price', parseFloat(extra_price));
                    $('.extras_modal_row:last').find('.extras_item_checkbox').attr('data-product-extra-item-vat-delivery', parseInt(extra_vat_delivery));
                    $('.extras_modal_row:last').find('.extras_item_checkbox').attr('data-product-extra-item-vat-takeout', parseInt(extra_vat_takeout));
                    $('.extras_modal_row:last').find('.extras_item_checkbox').attr('data-product-extra-item-vat-on-the-spot', parseInt(extra_vat_on_the_spot));
                    $('.extras_modal_row:last').find('.extras_item_checkbox').prop('checked', false);
                    
                    
                    
                };
                
            } else {
                $('#extrasModalBody').addClass('d-none');
            }
            
        }

        function addProductToModal(elem) {
            product_id = elem.attr('data-product-id');
            product_name = elem.attr('data-product-name');
            current_price = elem.attr('data-current-price');
            quantity = 1;
            total_price = parseFloat(current_price) * parseInt(quantity);

            product_attributes = JSON.parse(elem.attr('data-product-attributes'));
            product_options = JSON.parse(elem.attr('data-product-options'));
            if ( elem.attr('data-product-extras') !== undefined ) {
                product_extras = JSON.parse(elem.attr('data-product-extras'));
            } else {
                product_extras = [];
            }

            resetOptionsModal();
            setOptionsModal(product_id, product_name, current_price, quantity, total_price, product_attributes, product_options, product_extras)
            $('#optionsModal').modal('show');
        }





        $('body').on('click', '#openCouponsModal', function (event) {
            event.preventDefault();
            $('#couponsModal').modal('show');
        });

        $('body').on('click', '.btn-group-toggle>.btn input[type="radio"]', function (event) {
            event.preventDefault();
            if($(this).prop('checked')){
                $(this).parent().addClass('active');
            }
        });

        $('body').on('click', '#attributedata .tab-pane .cof_options_radio_item_input_group input[type="radio"]', function (event) {
            event.preventDefault();
            if($(this).prop('checked')){
                $(this).parent().addClass('active');
            }
        });

    });
</script>