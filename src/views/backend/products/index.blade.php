@extends('chuckcms::backend.layouts.base')

@section('title')
	Producten
@endsection

@section('add_record')
	@can('create forms')
	<a href="{{ route('dashboard.module.ecommerce.products.create') }}" class="btn btn-link text-primary m-l-20 hidden-md-down">Voeg Nieuw Product Toe</a>
	@endcan
@endsection




@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item active" aria-current="page">Producten</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
    	<div class="col-sm-12 text-right">
    		<a href="{{ route('dashboard.module.ecommerce.products.create') }}" class="btn btn-sm btn-outline-success">Product Toevoegen</a>
    	</div>
        <div class="col-sm-12 my-3">
        	<div class="table-responsive">
        		<table class="table" data-datatable  style="width:100%">
        			<thead>
        				<tr>
        					<th scope="col">#</th>
        					<th scope="col">Titel</th>
        					<th scope="col">Collectie</th>
        					<th scope="col" class="pr-5">Prijs</th>
        					<th scope="col">Status</th>
        					<th scope="col">Hvl</th>
        					<th scope="col" style="min-width:200px">Acties</th>
        				</tr>
        			</thead>
        			<tbody>
        				@foreach($products as $product)
        				<tr class="product_line" data-id="{{ $product->id }}">
        					<th scope="row">{{ $product->id }}</th>
        					<td>{{ $product->json['title'][ChuckSite::getFeaturedLocale()] }}</td>
        					<td>{{ is_null(ChuckProduct::collection($product)) ? '' : ChuckProduct::collection($product)->json['name']}}</td>
        					<td>{{ChuckProduct::lowestPrice($product)}}</td>
        					<td class="text-center">
								<span class="badge badge-{{ ChuckProduct::isBuyable($product) ? 'success' : 'danger' }}">
									{!!ChuckProduct::isBuyable($product) ? '✓' : '✕'!!}
								</span>
							</td>
        					<td>{{ChuckProduct::quantity($product, ChuckProduct::defaultSku($product)) }}</td>
        					<td>
        						@can('edit forms')
					    		<a href="{{ route('dashboard.module.ecommerce.products.edit', ['product' => $product->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
					    			<i class="fa fa-pen"></i> edit 
					    		</a>
								<button 
									{{-- data-toggle="modal" data-target="#labelModal"  --}}
									data-product="{{$product->id }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block labelbtn">
					    			<i class="fa fa-tag"></i> 
					    		</button>
					    		@endcan
					    		@can('delete forms')
					    		<a href="#" class="btn btn-sm btn-outline-danger rounded d-inline-block product_delete" data-id="{{ $product->id }}">
					    			<i class="fa fa-trash"></i> delete 
					    		</a>
					    		@endcan
        					</td>
        				</tr>
        				@endforeach
        			</tbody>
        		</table>
        	</div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="labelModal" tabindex="-1" role="dialog" aria-labelledby="labelModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title" id="labelModalLabel">Modal title</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-12 form-group brand_area">
					<label for="brandname" class="form-label">Brand</label>
					<input type="text" id="brandname" class="form-control" aria-describedby="brandname" readonly>
				</div>
				<div class="col-12 form-group single_product_area">
					<div class="product_area mb-3">
						<label for="product_name" class="form-label">Product Name</label>
						<input type="text" id="product_name" class="form-control" aria-describedby="product_name" readonly>
					</div>
					<div class="barcode_area mb-3">
						<label for="barcode" class="form-label">Barcode</label>
						<input type="text" id="barcode" class="form-control" aria-describedby="barcode" readonly>
					</div>
					<div class="row">
						<div class="col price_area">
							<label for="price" class="form-label">Price</label>
							<input type="text" id="price" class="form-control" aria-describedby="price" readonly>
						</div>

						<div class="col quantity_area">
							<label for="price" class="form-label">Quantity</label>
							<input type="number" id="quantity" class="form-control" aria-describedby="quantity" max="0" min="0" value="0">
						</div>
						<div class="col attributes_area">
							<label for="attributes" class="form-label">Attributes</label>
							<input type="text" id="attributes" class="form-control" aria-describedby="attributes" readonly>
						</div>
					</div>
				</div>
				<div class="col-12 form-group combination_area" style="display: none;">
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
							<tr class="combination_item">
								<td><input class="combination_active" type="checkbox" checked="checked" name=""></td>
								<td class="combination-name">combination name</td>
								<td class="combination-price">€ 0,00</td>
								<td  class="">
									<input type="number" class="combination-quantity" value="0" min="0" max="0">
								</td>
								<td>
									<button type="button" class="btn btn-primary combination-print print-btn" data-product-type="single_combi_product">Print</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<div class="row w-100">
				<div class="col-12 col-lg-6">
					{{-- <div class="d-flex w-100 align-items-center">
						<small data-bind="text: message">Loading</small><br>
						<small data-bind="text: webServicePresent, visible: environmentChecked()"></small>
					</div> --}}
					<div class="d-flex w-100 align-items-center">
						<span class="px-1" data-bind="html: printerConnected, visible: printerChecked()"></span>
						<small data-bind="text: printerName, visible: printerChecked()"></small>
					</div>
				</div>
				<div class="col-12 col-lg-6 d-flex">
					<button type="button" class="btn btn-secondary ml-lg-auto" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary ml-lg-2 print-btn" data-product-type="single">Print</button>
				</div>
			</div>
		  
		</div>
	  </div>
	</div>
  </div>
@endsection

@section('css')
	<style>
		.online, .offline {
			width: 5px;
			height: 5px;
			border-radius: 50%;
		}
	</style>
@endsection

@section('scripts')
<script src="https://cdn.chuck.be/assets/plugins/sweetalert2.all.js"></script>
<script src="/chuckbe/chuckcms-module-ecommerce/js/labelprinter/DYMO.Label.Framework.3.0.js"></script>
<script src="/chuckbe/chuckcms-module-ecommerce/js/labelprinter/Knockout-min.js"></script>
<script src="/chuckbe/chuckcms-module-ecommerce/js/labelprinter/label.js"></script>
<script>
$( document ).ready(function (){
    $('body').on('click', '.product_delete', function (event) {
        event.preventDefault();
        var product_id = $(this).attr("data-id");
        var token = '{{ Session::token() }}';

        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) { 
                $.ajax({
                    method: 'POST',
                    url: "{{ route('dashboard.module.ecommerce.products.delete') }}",
                    data: { 
                        product_id: product_id, 
                        _token: token
                    }
                }).done(function (data) {
                    if(data.status == 'success'){
                        $(".product_line[data-id='"+product_id+"']").first().remove();
                        swal('Deleted!','The product has been deleted.','success')
                    } else {
                        swal('Oops!','Something went wrong...','danger')
                    }
                });
            }
        })
    });
});
</script>
<script>
$( document ).ready(function (){
	$(document).on('click','.labelbtn', function (e) {
		let $invoker = $(this);
		let modal = $('#labelModal');
		let product = $invoker.closest('tr').children('td').eq(0).text();
		let product_id = $invoker.attr('data-product');
		let token = '{{ Session::token() }}';
		modal.find('#labelModalLabel').text(product);
		$.ajax({
           type:'POST',
           url:"{{ route('dashboard.module.ecommerce.products.product') }}",
           data: { 
				product_id: product_id, 
				_token: token
			}
		}).done(function (data) {
			if(data.status == 'success'){
				let modal_body = $(modal).find('.modal-body');
				let product_data = data.product.json;
				if(data.brand !== ''){
					modal_body.find('#brandname').val(data.brand);
				}
				if(product_data.combinations && Object.keys(product_data.combinations).length > 0){
					// modal.find('.modal-footer .print-btn').attr('disabled', 'disabled');
					modal.find('.modal-footer .print-btn').attr('data-product-type', 'multi_combi_products');
					modal_body.find('.single_product_area #barcode').val('');
					modal_body.find('.single_product_area').hide();
					modal_body.find('.combination_area').show();
					$.each(product_data.combinations, function(k,v){
						let combiEl = modal_body.find('.combinations_row .combination_item').eq(0).clone();
						let price = new Intl.NumberFormat("nl-BE", { style: "currency", "currency":"EUR" }).format(v.price.final);
						combiEl.attr('data-product', product_data.title.nl);
						combiEl.attr('data-ean', v.code.ean);
						combiEl.attr('data-attr', k);
						combiEl.attr('data-price', price);
						combiEl.attr('data-qty', v.quantity);

						if(v.quantity == 0){
							combiEl.find('.combination_active').attr('checked',false);
							combiEl.find('.combination-print').attr('disabled','disabled');
						}
						

						combiEl.find('.combination-name').text(v.display_name.nl);
						combiEl.find('.combination-price').text(price);
						combiEl.find('.combination-quantity').attr('value',v.quantity);
						combiEl.find('.combination-quantity').attr('max',v.quantity);
						combiEl.appendTo($(modal_body.find('.combinations_row')));
					});
					modal_body.find('.combinations_row .combination_item').first().remove();
				}else{
					let attributes = [];
					Object.values(product_data.attributes).forEach(function(attribute){
						let name = attribute.display_name.nl;
						attributes.push(name);
					});
					let attr;
					if(attributes.length > 1){
						attr = attributes.join(' ');
					}else{
						attr = attributes.join('');
					}
					

					modal.find('.modal-footer .print-btn').attr('disabled', false);
					modal_body.find('.single_product_area #barcode').val(product_data.code.ean);
					modal_body.find('.single_product_area #product_name').val(product_data.title.nl);
					let price = new Intl.NumberFormat("nl-BE", { style: "currency", "currency":"EUR" }).format(product_data.price.final);
					modal_body.find('.single_product_area #price').val(price);
					modal_body.find('.single_product_area #quantity').attr('value', product_data.quantity);
					modal_body.find('.single_product_area #quantity').attr('max', product_data.quantity);
					modal_body.find('.single_product_area #attributes').val(attr);;
					modal_body.find('.single_product_area').show();
					modal_body.find('.combination_area').hide();
				}
				modal.modal('show');
			}else{
				console.log(data);
			}
		});
	})
	
	$('#labelModal').on('hide.bs.modal', function (e) {
		let modal =  $('#labelModal');
		let modal_body = $(modal).find('.modal-body');
		modal_body.find('.combination_area .combination_item').not(':first').remove();
	});
	
	$(document).on('click','#labelModal .print-btn', function(e){
		let modalbody = $('#labelModal').find('.modal-body');
		let manufacturer;
		let product_name;
		let barcode;
		let price;
		let quantity;
		let attributes;
		let jobName; 
		if($(this).attr('data-product-type') == 'single'){
			manufacturer = $(modalbody).find('input#brandname').val();
			product_name = $(modalbody).find('input#product_name').val();
			barcode = $(modalbody).find('input#barcode').val();
			price = $(modalbody).find('input#price').val();
			quantity = $(modalbody).find('input#quantity').val();
			attributes = $(modalbody).find('input#attributes').val();
			jobName = 'customJob';

			if(checkEan(barcode)){
				if(barcode.length == 13){
					barcode = barcode.slice(0, -1)
				}
				if(quantity > 0){
					printLabel(manufacturer,product_name,barcode,attributes,price,quantity, jobName);
				}else{
					console.log('quantity set to 0');
				}
			}else{
				console.log('invalid barcode');
			}
			
		}
		if($(this).attr('data-product-type') == 'single_combi_product'){
			manufacturer = $(modalbody).find('input#brandname').val();
			let ci = $(this).closest('tr.combination_item')
			
			product_name = $(ci).attr('data-product');
			barcode = $(ci).attr('data-ean');
			price = $(ci).find('.combination-price').text();;
			quantity = $(ci).find('.combination-quantity').val();
			attributes = $(ci).attr('data-attr');
			jobName = 'customJob';

			if(checkEan(barcode)){
				if(barcode.length == 13){
					barcode = barcode.slice(0, -1)
				}
				if(quantity > 0){
					printLabel(manufacturer,product_name,barcode,attributes,price,quantity, jobName);
				}else{
					console.log('quantity set to 0');
				}
			}else{
				console.log('invalid barcode');
			}
		}

		if($(this).attr('data-product-type') == 'multi_combi_products'){
			manufacturer = $(modalbody).find('input#brandname').val();
			let combinations = $('.combinations_row tr.combination_item');
			if(combinations.length > 0){
				for (let i = 0; i < combinations.length; i++) {
					let active = $(combinations[i]).find('.combination_active').is(":checked")
					if(active){
						let ci = combinations[i]
						product_name = $(ci).attr('data-product');
						barcode = $(ci).attr('data-ean');
						price = $(ci).find('.combination-price').text();;
						quantity = $(ci).find('.combination-quantity').val();
						attributes = $(ci).attr('data-attr');
						jobName = 'customJob';
						if(checkEan(barcode)){
							if(barcode.length == 13){
								barcode = barcode.slice(0, -1)
							}
							if(quantity > 0){
								// printLabel
								printLabel(manufacturer,product_name,barcode,attributes,price,quantity, jobName);
							}else{
								console.log('quantity set to 0');
							}
						}else{
							console.log('invalid barcode');
						}
					}
				}
			}else{
				console.log('no combinations found');
			}
		}


		
			
		
	});
});



function printLabel($manufacturer,$product_name,$barcode,$attributes,$price,$quantity,$printJobName) {
	printerViewModel.message("Spooling");
	var label = dymo.label.framework.openLabelXml(shippingLabelTemplate);

	let manufacturer_name = $manufacturer;
    let product = $product_name;
    let barcode = $barcode;
    let attributes = $attributes;
    let price = $price;
    let quantity = $quantity;


	let basicPrintParamsXML = '<?xml version="1.0" encoding="utf-8"?>\n' +
    '<LabelWriterPrintParams>\n' +
    '  <Copies>'+quantity+'</Copies>\n' +
    '  <JobTitle>'+$printJobName+'</JobTitle>\n' +
    '  <FlowDirection>LeftToRight</FlowDirection>\n' +
    '  <PrintQuality>Auto</PrintQuality>\n' +
    '  <TwinTurboRoll>Auto</TwinTurboRoll>\n' +
    '</LabelWriterPrintParams>';

	label.setObjectText('brand_name', manufacturer_name);
    label.setObjectText('product_name', product);
    label.setObjectText('barcode', barcode);
    label.setObjectText('attribute', attributes);
    label.setObjectText('price', price);

	label.print(printerViewModel.printerName(), basicPrintParamsXML);


	// label.printAsync(printerViewModel.printerName(), basicPrintParamsXML).then(function(state) {
    //     if (state) {
    //         printerViewModel.message("Printing");
    //         setTimeout(function() {
    //             printerViewModel.message("Ready");
    //         }, 2000);
    //     } else {
    //         printerViewModel.message("Error");
    //     }
    // });
    // return false;
}
</script>
@endsection
