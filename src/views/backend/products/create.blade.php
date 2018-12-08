@extends('chuckcms::backend.layouts.admin')

@section('content')
<!-- START CONTAINER FLUID -->
<div class=" container-fluid   container-fixed-lg">

<!-- START card -->
<form action="{{ route('dashboard.module.ecommerce.products.save') }}" method="POST">
<div class="card card-transparent">
  <div class="card-header ">
    <div class="card-title">Maak een nieuw product
    </div>
  </div>

<div class="card-block">
  <div class="row">
    <div class="col-lg-12">
      <div class="card card-default">
        <div class="card-block">
          
          <div class="form-group form-group-default required">
            <label>Slug *</label>
            <input type="text" class="form-control product_slug_input" placeholder="slug" id="product_slug" name="slug" required>
          </div>

          <div class="row">
            <div class="col-sm-6">
              <div class="form-group form-group-default">
                <label>Productcode UPC</label>
                <input type="text" class="form-control" placeholder="UPC Code" name="code[upc]">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group form-group-default">
                <label>Productcode EAN</label>
                <input type="text" class="form-control" placeholder="EAN Code" name="code[ean]">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-4">
              <div class="form-group form-group-default input-group">
                <div class="form-input-group">
                  <label class="inline">Wordt weergegeven?</label>
                </div>
                <div class="input-group-addon bg-transparent h-c-50">
                  <input type="hidden" name="is_displayed" value="0">
                  <input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="is_displayed" />
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group form-group-default input-group">
                <div class="form-input-group">
                  <label class="inline">Mag verkocht worden?</label>
                </div>
                <div class="input-group-addon bg-transparent h-c-50">
                  <input type="hidden" name="is_buyable" value="0">
                  <input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="is_buyable" />
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group form-group-default input-group">
                <div class="form-input-group">
                  <label class="inline">Is virtueel product?</label>
                </div>
                <div class="input-group-addon bg-transparent h-c-50">
                  <input type="hidden" name="is_download" value="0">
                  <input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="is_download" />
                </div>
              </div>
            </div>
          </div>
          
          <hr>
          
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group form-group-default">
                <label>Inkoopprijs</label>
                <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="price[wholesale]" value="0.000000" placeholder="Inkoopprijs">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group form-group-default required">
                <label>Verkoopprijs *</label>
                <input type="text" id="sale_price_ex_input" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="price[sale]" value="0.000000" placeholder="Verkoopprijs" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6">
              <div class="form-group form-group-default form-group-default-select2 required">
                <label>Belasting (BTW) *</label>
                <select class="full-width" id="tax-input" name="price[vat]" data-init-plugin="select2" data-minimum-results-for-search="Infinity" required>
                  @foreach(config('chuckcms-module-ecommerce.vat') as $vatKey => $vatValue)
                    <option value="{{ $vatKey }}" data-amount="{{ $vatValue['amount'] }}" @if($vatValue['default']) selected @endif>{{ $vatValue['type'] }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group form-group-default required">
                <label>Verkoopprijs met BTW *</label>
                <input type="text" id="sale_price_in_input" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="price[final]" value="0.000000" placeholder="Verkoopprijs" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group form-group-default">
                    <label>Eenheidsprijs</label>
                    <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="price[unit][amount]" value="0.000000">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group form-group-default">
                    <label>Per</label>
                    <input type="text" class="form-control" name="price[unit][type]">
                  </div>
                </div>
              </div>
              
            </div>
            <div class="col-sm-6">
              <div class="form-group form-group-default">
                <label>Kortingsprijs met BTW</label>
                <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="price[discount]" value="0.000000" placeholder="Kortingsprijs met BTW">
              </div>
            </div>
          </div>

          <hr>
          
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group form-group-default form-group-default-select2 required">
                <label>Collectie *</label>
                <select class="full-width" name="collection" data-init-plugin="select2" data-minimum-results-for-search="5" data-placeholder="Selecteer een collectie" required>
                  <option></option>
                  @foreach($collections as $collection)
                    <option value="{{ $collection->id }}">{{ $collection->json['name'] }} {{ $collections->where('id', $collection->json['parent'])->first() ? '('.$collections->where('id', $collection->json['parent'])->first()->json['name'].')' : '' }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group form-group-default form-group-default-select2">
                <label>Merk/Fabrikant *</label>
                <select class="full-width" name="brand" data-init-plugin="select2" data-minimum-results-for-search="Infinity" data-placeholder="Selecteer een merk" data-allow-clear="true">
                  <option></option>
                  @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->json['name'] }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<div class="card-block">
  <div class="row">
    <div class="col-lg-12">
      <div class="card card-default">
        <div class="card-block">

          <div class="row">
            <div class="col-sm-12">
              <div class="form-group required">
                <label for="featured_image">Hoofdafbeelding</label>
                <div class="input-group">
                  <span class="input-group-btn">
                    <a id="lfm" data-input="featured_image_input" data-preview="featured_image_holder" class="btn btn-primary img_lfm_link" style="color:#FFF">
                      <i class="fa fa-picture-o"></i> Upload afbeelding
                    </a>
                  </span>
                  <input id="featured_image_input" name="featured_image" class="img_lfm_input form-control" accept="image/x-png" type="text" required>
                </div>
                <img id="featured_image_holder" src="" style="margin-top:15px;max-height:100px;">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="image">Afbeelding</label>
                <div class="input-group">
                  <span class="input-group-btn">
                    <a id="lfm" data-input="image_input" data-preview="image_holder" class="btn btn-primary img_lfm_link" style="color:#FFF">
                      <i class="fa fa-picture-o"></i> Upload afbeelding
                    </a>
                  </span>
                  <input id="image_input" name="image[]" class="img_lfm_input form-control" accept="image/x-png" type="text">
                </div>
                <img id="image_holder" src="" style="margin-top:15px;max-height:100px;">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="image">Afbeelding</label>
                <div class="input-group">
                  <span class="input-group-btn">
                    <a id="lfm" data-input="image1_input" data-preview="image1_holder" class="btn btn-primary img_lfm_link" style="color:#FFF">
                      <i class="fa fa-picture-o"></i> Upload afbeelding
                    </a>
                  </span>
                  <input id="image1_input" name="image[]" class="img_lfm_input form-control" accept="image/x-png" type="text">
                </div>
                <img id="image1_holder" src="" style="margin-top:15px;max-height:100px;">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="image">Afbeelding</label>
                <div class="input-group">
                  <span class="input-group-btn">
                    <a id="lfm" data-input="image2_input" data-preview="image2_holder" class="btn btn-primary img_lfm_link" style="color:#FFF">
                      <i class="fa fa-picture-o"></i> Upload afbeelding
                    </a>
                  </span>
                  <input id="image2_input" name="image[]" class="img_lfm_input form-control" accept="image/x-png" type="text">
                </div>
                <img id="image2_holder" src="" style="margin-top:15px;max-height:100px;">
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<div class="card-block">
  <div class="row">
    <div class="col-lg-12">
      <div class="card card-default">
        <div class="card-block">

          <div class="row">
            <div class="col-sm-12">
              <div class="form-group form-group-default form-group-default-select2">
                <label>Attributen</label>
                <select class="full-width" id="attributes_multi_select" name="attributes[]" data-init-plugin="select2" data-minimum-results-for-search="5" data-placeholder="Selecteer attributen" multiple="multiple">
                  <option></option>
                  @foreach($attributes as $attribute)
                    <option value="{{ $attribute->id }}">{{ $attribute->json['name'] }} ({{ count($attribute->json['values']) }})</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          
          <hr>

          @foreach($attributes as $attribute)
          <div class="row attribute-select-row" data-attribute="{{ $attribute->id }}" style="display:none;">
            <div class="col-sm-12">
              <div class="form-group form-group-default form-group-default-select2 required">
                <label>Attribuut {{ $attribute->json['name'] }}</label>
                <select class="full-width attribute-multi-select-input" name="attribute[{{ $attribute->id }}][]" data-attribute="{{ $attribute->id }}" data-init-plugin="select2" data-minimum-results-for-search="Infinity" data-placeholder="Selecteer attribuuts" data-allow-clear="true" multiple="multiple" required>
                  <option></option>
                  @foreach($attribute->json['values'] as $attributeKey => $attributeValue)
                    <option value="{{ $attributeKey }}" data-name="{{ $attribute->json['name'] }} {{ $attributeValue['display_name'][config('app.locale')] }}">{{ $attributeValue['display_name'][config('app.locale')] }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          @endforeach

          <hr>

          <div class="row combination-row">
            <div class="col-sm-4">
              <div class="form-group form-group-default required">
                <label>Combinatie Aantal</label>
                <input type="text" data-v-min="0" data-v-max="999999" data-m-dec="0" data-a-pad=true class="autonumeric form-control" name="combinations[slug][quantity]" value="0">
              </div>
            </div>
            <div class="col-sm-8">
              <div class="form-group form-group-default required">
                <label>Combinatie Naam </label>
                <input type="text" class="form-control combination_name_input" value="" disabled>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<div class="card card-transparent">
  <div class="card-block">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-transparent">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs nav-tabs-linetriangle" data-init-reponsive-tabs="dropdownfx">
            @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
              <li class="nav-item">
                <a href="#" @if($loop->iteration == 1) class="active" @endif data-toggle="tab" data-target="#tab_product_{{ $langKey }}"><span>{{ $langValue['name'] }} ({{ $langValue['native'] }})</span></a>
              </li>
            @endforeach
          </ul>
          <!-- Tab panes -->
          <div class="tab-content">

            @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
            <div class="tab-pane fade show @if($loop->iteration == 1) active @endif" id="tab_product_{{ $langKey }}">
                  <div class="form-group form-group-default required">
                    <label>Titel</label>
                    <input type="text" class="form-control" placeholder="Titel" name="title[{{ $langKey }}]" required>
                  </div>
                  
                  <div class="form-group">
                    <label>Korte Beschrijving</label>
                    <div class="summernote-wrapper">
                    <textarea name="description[short][{{ $langKey }}]" class="summernote-text-editor" placeholder="Korte Beschrijving"></textarea>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label>Lange Beschrijving</label>
                    <div class="summernote-wrapper">
                    <textarea name="description[long][{{ $langKey }}]" class="summernote-text-editor" placeholder="Lange Beschrijving"></textarea>
                    </div>
                  </div>
                
                  <div class="form-group form-group-default required">
                    <label>Meta Titel</label>
                    <input type="text" class="form-control" placeholder="Meta Titel" name="meta_title[{{ $langKey }}]" required>
                  </div>
                
                  <div class="form-group form-group-default required">
                    <label>Meta Beschrijving</label>
                    <textarea class="form-control" name="meta_description[{{ $langKey }}]" placeholder="Meta Beschrijving" cols="30" rows="10" style="height:80px" required></textarea>
                  </div>
                
                  <div class="form-group form-group-default required">
                    <label>Meta Keywords (scheiden met komma)</label>
                    <textarea class="form-control" name="meta_keywords[{{ $langKey }}]" placeholder="Meta Keywords (scheiden met komma)" cols="30" rows="10" style="height:80px" required></textarea>
                  </div>
                

            </div>
            @endforeach

          </div>
          <br>
          <p class="pull-right">
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <button type="submit" name="create" class="btn btn-success btn-cons pull-right" value="1">Opslaan</button>
            <a href="{{ route('dashboard.content.resources') }}" class="pull-right"><button type="button" class="btn btn-info btn-cons">Annuleren</button></a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END card -->
</form>
</div>
<!-- END CONTAINER FLUID -->
@endsection

@section('css')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <link href="//cdn.chuck.be/assets/plugins/summernote/css/summernote.css" rel="stylesheet" media="screen">
@endsection

@section('scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
  <script src="{{ URL::to('vendor/laravel-filemanager/js/lfm.js') }}"></script>
  <script src="//cdn.chuck.be/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
  <script src="//cdn.chuck.be/assets/plugins/summernote/js/summernote.min.js"></script>
	<script>
		$( document ).ready(function() { 
    init(); 

    function init() {
			$(".product_slug_input").keyup(function(){
			    var text = $(this).val();
			    slug_text = text.toLowerCase().replace(/[^\w ]+/g,'-').replace(/ +/g,'-');
			    $(".product_slug_input").val(slug_text);   
			});

      //Autonumeric plug-in - automatic addition of dollar signs,etc controlled by tag attributes
      $('.autonumeric').autoNumeric('init');

      //init media manager inputs 
      var domain = "{{ URL::to('dashboard/media')}}";
      $('.img_lfm_link').filemanager('image', {prefix: domain});

      $('.summernote-text-editor').summernote({
        height: 150,
        fontNames: ['Arial', 'Arial Black', 'Open Sans', 'Helvetica', 'Helvetica Neue', 'Lato'],
        toolbar: [
          // [groupName, [list of button]]
          ['style', ['bold', 'italic', 'underline', 'clear']],
          ['font', ['strikethrough', 'superscript', 'subscript']],
          ['fontsize', ['fontsize']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['height', ['height']]
        ]
      });

      $("#sale_price_ex_input").keyup(function(){
        var vat = parseFloat($('#tax-input').find(":selected").attr("data-amount"));
        var exvat = parseFloat($(this).val());
        var invat = (exvat + ((exvat / 100) * vat)).toFixed(6);
        $("#sale_price_in_input").val(invat).change();
      });

      $("#tax-input").on('change', function() {
        var vat = parseFloat($(this).find(":selected").attr("data-amount"));
        var exvat = parseFloat($("#sale_price_ex_input").val());
        var invat = (exvat + ((exvat / 100) * vat)).toFixed(6);
        $("#sale_price_in_input").val(invat).change();
      });

      $("#sale_price_in_input").keyup(function(){
        var vat = parseFloat('1.'+$('#tax-input').find(":selected").attr("data-amount"));
        console.log(vat);
        var invat = parseFloat($(this).val());
        var exvat = (invat / vat).toFixed(6);
        console.log(exvat);
        $("#sale_price_ex_input").val(exvat).change();
      });

      $("#attributes_multi_select").on('change', function(){
        var selectedAttributes = $(this).val();
        $('.attribute-select-row').each(function() {
          if( jQuery.inArray($(this).attr('data-attribute'), selectedAttributes) !== -1 ){
            $(this).show();
          } else {
            $(this).hide();
          }
        });

        for (var i = selectedAttributes.length - 1; i >= 0; i--) {
           selectedAttributes[i]
        };
      });

      $(".attribute-multi-select-input").on('change', function(){
        //check if all attributes are filled
        var selectedAttributes = $("#attributes_multi_select").val();
        var totalCombinations = parseInt(0);
        for (var i = selectedAttributes.length - 1; i >= 0; i--) {
          if(!$(".attribute-multi-select-input[data-attribute="+selectedAttributes[i]+"]").val()){
            return; //break off function as not all attributes are filled
          } else {
            var isAllSelected = true;
            totalCombinations = totalCombinations + parseInt($(".attribute-multi-select-input[data-attribute="+selectedAttributes[i]+"]").val().length);
          }
        };
        if(isAllSelected == true){
          for (var i = totalCombinations.length - 1; i >= 0; i--) {
            // combine data from options and add/copy to combination ---- totalCombinations[i]
          };
        }
      });

    }


			
		});
	</script>
@endsection