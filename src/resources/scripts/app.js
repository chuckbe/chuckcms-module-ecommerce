let online = true;
setInterval(function(){
    let img = new Image();
    img.onerror=function() {
        online = false;
    }
    img.src="https://donuttello.com/img/donuttello-logo.png?rnd="+new Date().getTime();
}, 3000);
var cart = [];
if (localStorage.getItem("cart") !== null) {
  let storedCart = JSON.parse(localStorage.getItem('cart'));
  cart = storedCart;
  console.log(storedCart);
  storedCart.forEach((billItem)=> {
      let bestelNavigation = $('#bestelNavigationTab');
      let $tab = $(`<a class="flex-sm-fill text-sm-center nav-link" id="bestelNavigation${billItem.rekening}Tab" href="#${billItem.rekening}" role="tab" data-toggle="tab" aria-controls="${billItem.rekening}Tab" aria-selected="true" data-bestel-id="${billItem.rekening}"><span>bestelcode: #${(billItem.rekening).match(/\d/g).join("")}</span><span class="remove-tab"><i class="fas fa-times-circle"></i></span></a>`);
      let $tabPane = $(`<div class="tab-pane fade show" id="${billItem.rekening}"  role="tabpanel" aria-labelledby="${billItem.rekening}Tab" data-bestel-id="${billItem.rekening}"></div>`)  
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
        billItem.products.map(function(product) {
            let featured_img = '';
            for( let key in product.productData.json.images) {
                if(product.productData.json.images[key].is_featured === true) {
                    let url = window.location.protocol + "//" + location.host.split(":")[0];
                    featured_img = url+product.productData.json.images[key].url.replace(" ","%20");
                }
            }
            let newOrder = 
            $(`
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
        });
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
	
};
$(document).ready(function(){
    $.ajax({
        url: "http://chuck.package/dashboard/pos/data",
        type: 'GET',
        dataType: 'json', // added data type
        success: function(res) {
            localStorage.setItem('pos-products', JSON.stringify(res));        
            function ucwords(str,force){
              str=force ? str.toLowerCase() : str;  
                return str.replace(/(\b)([a-zA-Z])/g,
                function(firstLetter){
                    return   firstLetter.toUpperCase();
                });
            }
            // READ STRING FROM LOCAL STORAGE
            let retrievedObject = localStorage.getItem('pos-products');

            // CONVERT STRING TO REGULAR JS OBJECT
            let parsedObject = JSON.parse(retrievedObject);

            // ACCESS DATA
            if(parsedObject.collections.length > 0) {
                $('#navigationTab').empty();
                parsedObject.collections.forEach((category,categoryIndex)=> {
                    $('#navigationTab').append(`<li class="nav-item mr-3"><a class="nav-link ${categoryIndex == 0 ? 'active' : ''}" id="navtiagtion${ucwords(category.json.name.toLowerCase())}Tab" href="#${category.json.name.toLowerCase()}" role="tab" data-toggle="tab" aria-controls="donutsTab" aria-selected="true">${category.json.name}</a></li>`)
                });
            }

            if(parsedObject.products.length > 0) {
                $('#navigationTabContent').empty();
                parsedObject.collections.forEach((category,categoryIndex)=> {
                    let $tabpanel = $(`<div class="tab-pane fade show ${categoryIndex == 0 ? 'active' : ''}" id="${category.json.name.toLowerCase()}" role="tabpanel" aria-labelledby="${category.json.name.toLowerCase()}Tab"></div>`);
                    $('#navigationTabContent').append($tabpanel);
                    $(`.tab-pane#${category.json.name.toLowerCase()}`).append(`<div class="row" id="row${category.json.name.toLowerCase()}"></div>`);
                    parsedObject.products.forEach((product)=> {
                        if(product.json.collection == category.id) {
                            let featured_img = '';
                            for (let key in product.json.images) { 
                                if(product.json.images[key].is_featured === true) {
                                    let url = window.location.protocol + "//" + location.host.split(":")[0];
                                    featured_img = url+product.json.images[key].url.replace(" ","%20");
                                }
                            }
                            let $card = $(`<div class="col-3 p-1 posproduct ${(product.json.quantity > 0) ? '' : 'unavailable'}" data-pid=${product.id}>
                                            <div class="card shadow-sm">
                                                <div class="card-body">
                                                    <h5 class="card-title">${product.json.title.nl}</h5>
                                                    <div class="row">
                                                        <div class="col">
                                                            <h6 class="card-subtitle mb-2 text-muted">€ ${parseFloat(product.json.price.final).toFixed(2).replace(".", ",")}</h6>
                                                            ${(product.json.quantity > 0) ? '' : '<p style="font-size: 10px; color: #e72870">Niet beschikbaar</p>'}
                                                        </div>
                                                        <div class="col">
                                                            <img src=${featured_img} class="img-fluid" alt=${product.json.title.nl}>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`);
                            $(`.tab-pane#${category.json.name.toLowerCase()} #row${category.json.name.toLowerCase()}`).append($card);
                        }
                    });
                });
            }
        }
    });
    let checkExist = setInterval(function() {
        if ($('.posproduct').length) {
            $( ".posproduct" ).each(function(index) {
                $(this).on("click", function(){
                    let id = $(this).data('pid');
                    addToCart(id);
                });
            });
            clearInterval(checkExist);
        }
    }, 100); // check every 100ms

    // bestel navigation system
    if($("#bestelNavigationTab").children().length < 2){
        let $randomBestelCode = GenRandom.Job();
        let $newTab = $(`<a class="flex-sm-fill text-sm-center nav-link active" id="bestelNavigationbestelcode${$randomBestelCode}Tab" href="#bestelcode${$randomBestelCode}" role="tab" data-toggle="tab" aria-controls="bestelcode${$randomBestelCode}Tab" aria-selected="true" data-bestel-id="bestelcode${$randomBestelCode}"><span>bestelcode: #${$randomBestelCode}</span><span class="remove-tab"><i class="fas fa-times-circle"></i></span></a>`)
        let $newTabPane = $(`<div class="tab-pane fade show active" id="bestelcode${$randomBestelCode}"  role="tabpanel" aria-labelledby="bestelcode${$randomBestelCode}Tab" data-bestel-id="bestelcode${$randomBestelCode}"></div>`)
        $('#bestelNavigationTab #bestelNavigationnNieuweBestellingTab').after($newTab);
        $('#bestelNavigationTabContent').prepend($newTabPane);
        cart.push({
            'rekening': `bestelcode${$randomBestelCode}`,
            'state': 'active',
            'products': []
        });
        localStorage.setItem('cart', JSON.stringify(cart));
    };

    // creates new tab
    $('#bestelNavigationnNieuweBestellingTab').on("click", function(e) {
        e.preventDefault();
        $('#bestelNavigationTab').children().removeClass("active");
        $('#bestelNavigationTabContent').children().removeClass("active");
        let $randomBestelCode = GenRandom.Job();
        let $newTab = $(`<a class="flex-sm-fill text-sm-center nav-link active" id="bestelNavigationbestelcode${$randomBestelCode}Tab" href="#bestelcode${$randomBestelCode}" role="tab" data-toggle="tab" aria-controls="bestelcode${$randomBestelCode}Tab" aria-selected="true" data-bestel-id="bestelcode${$randomBestelCode}"><span>bestelcode: #${$randomBestelCode}</span><span class="remove-tab"><i class="fas fa-times-circle"></i></span></a>`)
        let $newTabPane = $(`<div class="tab-pane fade show active" id="bestelcode${$randomBestelCode}"  role="tabpanel" aria-labelledby="bestelcode${$randomBestelCode}Tab" data-bestel-id="bestelcode${$randomBestelCode}"></div>`)
        $('#bestelNavigationTab #bestelNavigationnNieuweBestellingTab').after($newTab);
        $('#bestelNavigationTabContent').prepend($newTabPane);
        cart.forEach((cartItem,cartIndex)=>{
            cartItem.state = 'inactive';
        })
        cart.push({
            'rekening': `bestelcode${$randomBestelCode}`,
            'state': 'active',
            'products': []
        });
        localStorage.setItem('cart', JSON.stringify(cart));
    });
    
    // removes products
    $(document).on("click","#bestelNavigationTab .nav-link .remove-tab",function() {
        let $tab = $(this).parent();
        let tabpaneid = $($tab).prop('href').split('#')[1];
        let $nextTab = $tab.next('.nav-link');
        let activeBestelId = $nextTab.attr('data-bestel-id');
        if($nextTab.length == 0){
            let $prevTab = $tab.prev('.nav-link');
            let $prevTabPane = $(`#bestelNavigationTabContent #${tabpaneid}`).prev('.tab-pane');
            if($prevTab.attr('data-target') != 'nieuweBestelling'){
                $prevTab.addClass("active");
                $prevTabPane.addClass("active");

            }
        }else {
            let $nextTabPane = $(`#bestelNavigationTabContent #${tabpaneid}`).next('.tab-pane');
            $nextTab.addClass("active");
            $nextTabPane.addClass("active");
        }
        $(`#bestelNavigationTabContent #${tabpaneid}`).remove();
        $($tab).remove();
        for (i = 0; i < cart.length; i++) {
            cart[i].state = 'inactive';
            if(cart[i].rekening == activeBestelId){
                cart[i].state = 'active';//set state in cart array
            }
            if(cart[i].rekening == `${tabpaneid}`){
                cart = cart.filter(item => item !== cart[i]) //remove element from array;
            }
        }
        localStorage.setItem('cart', JSON.stringify(cart));
    });
    // bestel navigation system ends

    // bestel cart area
    let local = localStorage.getItem('pos-products');
    let localParsed = JSON.parse(local);
    // add to cart btn on tapping the product
    const addToCart = function(id) {
        localParsed.products.forEach((product)=> {
            if(product.id == id){
                if(!$.isEmptyObject(product.json.combinations)){
                    console.log("product with combination");
                    let $wrapper = $('.wrapper');
                    let $modal = $(`<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    ...
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`);
                    $wrapper.append($modal);
                    $('#exampleModal').modal('show');
                }else{
                    let activeRekeningId = $("#bestelNavigationTabContent .tab-pane.active").attr('data-bestel-id');
                    cart.forEach((cartItem)=>{
                        if(cartItem.rekening == activeRekeningId){
                            cartItem.state = 'active';
                            if(cartItem.products.length === 0){
                                cartItem.products.push({
                                    'productData': product,
                                    'quantity': 1
                                });
                                localStorage.setItem('cart', JSON.stringify(cart));
                            }else {
                                let isProductPresent = cartItem.products.some(el => el.productData.id === id);
                                if(isProductPresent){
                                    cartItem.products.forEach((cartproduct)=>{
                                        if(cartproduct.productData.id === id){
                                            cartproduct.quantity = cartproduct.quantity+1;
                                            localStorage.setItem('cart', JSON.stringify(cart));
                                        }
                                    });
                                }else{
                                    cartItem.products.push({
                                        'productData': product,
                                        'quantity': 1
                                    });
                                    localStorage.setItem('cart', JSON.stringify(cart));
                                }
                                
                            }
                            localStorage.setItem('cart', JSON.stringify(cart));
                        }else{
                            cartItem.state = 'inactive';
                            localStorage.setItem('cart', JSON.stringify(cart));
                        }
                        
                    });
                }
            }
        });
        cart.forEach( cartItem=>{
            let bestelPane = $('#bestelNavigationTabContent').find(`[data-bestel-id='${cartItem.rekening}']`);
            if(bestelPane.hasClass('active')){
                if(bestelPane.attr("data-bestel-id") == cartItem.rekening) {
                    bestelPane.empty();
                    cartItem.products.map(function(product) {
                        let featured_img = '';
                        for( let key in product.productData.json.images) {
                            if(product.productData.json.images[key].is_featured === true) {
                                let url = window.location.protocol + "//" + location.host.split(":")[0];
                                featured_img = url+product.productData.json.images[key].url.replace(" ","%20");
                            }
                        }
                        let newOrder = 
                        $(`
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
                        bestelPane.append(newOrder);
                    });
                }
            }

        });
    }
    //delete btn below
    $(document).on('click', '.bestelOrderQuantityControl .deletebtn', function(event) {
        let tab = $(this).parents()[3];
        let orderId = $(tab).attr('id');
        let productrow = $(this).parents()[2];
        let productId = $(productrow).attr('data-product-id');
        let bestelOrderQuantity = $(this).parent().siblings('input#quantity').val();
        console.log(bestelOrderQuantity);
        if(bestelOrderQuantity == 1){
            console.log('one left');
            let deletebtn = $(productrow).children('.bestelOrderQuantityControl .deletebtn');
            deletebtn.html('<i class="fas fa-trash"></i>')
        }
        cart.forEach((cartItem)=>{
            if(orderId == cartItem.rekening){
                cartItem.state = 'active';
                //console.log("delete this item: ",cartItem);
                cartItem.products.forEach((product)=>{
                    if(product.productData.id == productId){
                        //console.log("delete this item: ", product);
                        if(product.quantity > 1){
                            product.quantity = product.quantity - 1;
                            localStorage.setItem('cart', JSON.stringify(cart));
                            $(this).parent().siblings(`input#quantity_product${product.productData.id}`).val(product.quantity);
                            let pricecontainer = $(productrow).children('.bestelOrderPrice');
                            pricecontainer.text(`€ ${parseFloat(product.productData.json.price.final * product.quantity).toFixed(2).replace(".", ",")}`);
                        } else{
                            if(confirm("Are you sure you want to delete this?")){
                                cartItem.products = jQuery.grep(cartItem.products, function(value) {
                                    return value != product;
                                });
                                ($(this).parents()[2]).remove();
                                localStorage.setItem('cart', JSON.stringify(cart));
                            }
                        }
                    }
                });
            }else{
                cartItem.state = 'inactive';
            }
            localStorage.setItem('cart', JSON.stringify(cart));
        });
    });

    //add btn below
    $(document).on('click', '.bestelOrderQuantityControl .addbtn', function(event){
        let tab = $(this).parents()[3];
        let orderId = $(tab).attr('id');
        let productrow = $(this).parents()[2];
        let productId = $(productrow).attr('data-product-id');
        cart.forEach((cartItem)=>{
            if(orderId == cartItem.rekening){
                cartItem.state = 'active';
                cartItem.products.forEach((product)=>{
                    if(product.productData.id == productId){
                        product.quantity = product.quantity + 1;
                        localStorage.setItem('cart', JSON.stringify(cart));
                        $(this).parent().siblings(`input#quantity_product${product.productData.id}`).val(product.quantity);
                        let pricecontainer = $(productrow).children('.bestelOrderPrice');
                        pricecontainer.text(`€ ${parseFloat(product.productData.json.price.final * product.quantity).toFixed(2).replace(".", ",")}`);
                    }
                });
            } else {
                cartItem.state = 'inactive';
            }
            localStorage.setItem('cart', JSON.stringify(cart));
        });
    });

    // bestel cart area ends
});