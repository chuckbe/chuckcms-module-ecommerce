@extends('chuckcms::backend.layouts.base')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.module.ecommerce.products.index') }}">Producten</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Bewerk Product</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="my-3">
                <ul class="nav nav-tabs justify-content-start" id="pageTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="p_general-tab" data-toggle="tab" href="#p_general" role="tab" aria-controls="p_general" aria-selected="true">Algemeen</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="p_prices-tab" data-toggle="tab" href="#p_prices" role="tab" aria-controls="p_prices" aria-selected="false">Prijzen</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="p_associations-tab" data-toggle="tab" href="#p_associations" role="tab" aria-controls="p_associations" aria-selected="false">Associaties</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="p_images-tab" data-toggle="tab" href="#p_images" role="tab" aria-controls="p_images" aria-selected="false">Afbeeldingen</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="p_combinations-tab" data-toggle="tab" href="#p_combinations" role="tab" aria-controls="p_combinations" aria-selected="false">Combinaties</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="p_options-tab" data-toggle="tab" href="#p_options" role="tab" aria-controls="p_options" aria-selected="false">Opties</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="p_texts-tab" data-toggle="tab" href="#p_texts" role="tab" aria-controls="p_texts" aria-selected="false">Teksten</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="p_dimensions-tab" data-toggle="tab" href="#p_dimensions" role="tab" aria-controls="p_dimensions" aria-selected="false">Dimensies</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="p_files-tab" data-toggle="tab" href="#p_files" role="tab" aria-controls="p_files" aria-selected="false">Bijlagen</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="p_data-tab" data-toggle="tab" href="#p_data" role="tab" aria-controls="p_data" aria-selected="false">Data</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <form action="{{ route('dashboard.module.ecommerce.products.update') }}" method="POST">
        <div class="row tab-content bg-light shadow-sm rounded p-3 mb-3 mx-1" id="pageTabContent">
            
            <div class="col-sm-12 tab-pane fade show active" id="p_general" role="tabpanel" aria-labelledby="p_general-tab">
              @include('chuckcms-module-ecommerce::backend.products.edit._tab_general')
            </div>


            <div class="col-sm-12 tab-pane fade" id="p_prices" role="tabpanel" aria-labelledby="p_prices-tab">
              @include('chuckcms-module-ecommerce::backend.products.edit._tab_prices')
            </div>


            <div class="col-sm-12 tab-pane fade" id="p_associations" role="tabpanel" aria-labelledby="p_associations-tab">
              @include('chuckcms-module-ecommerce::backend.products.edit._tab_associations')
            </div>

            <div class="col-sm-12 tab-pane fade" id="p_images" role="tabpanel" aria-labelledby="p_images-tab">
              @include('chuckcms-module-ecommerce::backend.products.edit._tab_images')
            </div>

            <div class="col-sm-12 tab-pane fade" id="p_combinations" role="tabpanel" aria-labelledby="p_combinations-tab">
              @include('chuckcms-module-ecommerce::backend.products.edit._tab_combinations')
            </div>

            <div class="col-sm-12 tab-pane fade" id="p_options" role="tabpanel" aria-labelledby="p_options-tab">
              @include('chuckcms-module-ecommerce::backend.products.edit._tab_options')
            </div>

            <div class="col-sm-12 tab-pane fade" id="p_texts" role="tabpanel" aria-labelledby="p_texts-tab">
              @include('chuckcms-module-ecommerce::backend.products.edit._tab_texts')
            </div>

            <div class="col-sm-12 tab-pane fade" id="p_dimensions" role="tabpanel" aria-labelledby="p_dimensions-tab">
              @include('chuckcms-module-ecommerce::backend.products.edit._tab_dimensions')
            </div>

            <div class="col-sm-12 tab-pane fade" id="p_files" role="tabpanel" aria-labelledby="p_files-tab">
              @include('chuckcms-module-ecommerce::backend.products.edit._tab_files')
            </div>

            <div class="col-sm-12 tab-pane fade" id="p_data" role="tabpanel" aria-labelledby="p_data-tab">
              @include('chuckcms-module-ecommerce::backend.products.edit._tab_data')
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 text-right">
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="_token" value="{{ Session::token() }}">
                <button class="btn btn-outline-success" type="submit">Opslaan</button>
            </div>
        </div>
    </form>
</div>
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
$(document).ready(function() {
  toggleRemoveExtraButton(); 
    
  $( "#options_input_container" ).sortable({revert: true});

  $('body').on('click', '.remove_line_button', function() {
      checker = $(this).parents('._input_container').find('._input_line').length;
      if(checker > 1) {
          $(this).parents('._input_line').remove();
      } else {
          $(this).parents('._input_line').addClass('d-none');
          $(this).parents('._input_line').find('input').prop('disabled', true);
      }
  });

  $('body').on('click', '#new_option_button', function() {
      $('#new_option_error').addClass('d-none');
      if($('#new_option_key').val().length == 0 || $('#new_option_value').val().length == 0) {
          $('#new_option_error').removeClass('d-none');
          return;
      }

      new_key = $('#new_option_key').val();
      new_value = $('#new_option_value').val();
      //new_file = $('#new_css_asset').is(':checked');

      if($('.option_input_line').length > 1) {
          $('.option_input_line:first').clone().appendTo('.options_input_container');
          $('.option_input_container').append('<hr>');
      } else {
        if($('.option_input_line:first').hasClass('d-none')) {
          $('.option_input_line:first').removeClass('d-none');
          $('.option_input_line:first').find('input').prop('disabled', false);
        } else {
          $('.option_input_line:first').clone().appendTo('.options_input_container');
          $('.option_input_container').append('<hr>');
        }
          
      }

      $('.option_input_line:last').find('.option_key_input').attr('id', 'option_key_'+new_key);
      $('.option_input_line:last').find('.option_key_input').val(new_key);
      $('.option_input_line:last').find('.option_key_input').siblings('label').attr('for', 'option_key_'+new_key);

      $('.option_input_line:last').find('.option_value_input').attr('id', 'option_value_'+new_key);
      $('.option_input_line:last').find('.option_value_input').val(new_value);
      $('.option_input_line:last').find('.option_value_input').siblings('label').attr('for', 'option_value_'+new_key);

      

      $('#new_option_key').val('');
      $('#new_option_value').val('');
  });

  $('body').on('click', '.addExtraRowButton', function (event) {
    event.preventDefault();
    $('.extra_input_row:first').clone().appendTo('.extraInputContainer');

    vardatainput = $('.extra_input_row:last').find('.img_lfm_link').attr('data-input');
    vardatapreview = $('.extra_input_row:last').find('.img_lfm_link').attr('data-preview');

    $('.extra_input_row:last').find('.img_lfm_link').attr('data-input', vardatainput+'_'+$('.extra_input_row').length);
    $('.extra_input_row:last').find('.img_lfm_link').attr('data-preview', vardatapreview+'_'+$('.extra_input_row').length);
    inputid = $('.extra_input_row:last').find('.img_lfm_input').attr('id');
    $('.extra_input_row:last').find('.img_lfm_input').attr('id',inputid+'_'+$('.extra_input_row').length);
    holderid = $('.extra_input_row:last').find('.img_lfm_holder').attr('id');
    $('.extra_input_row:last').find('.img_lfm_holder').attr('id',holderid+'_'+$('.extra_input_row').length);

    toggleRemoveExtraButton();

    init();
  });

  $('body').on('click', '.removeExtraRowButton', function (event) {
    event.preventDefault();
    $(this).parents('.extra_input_row').remove();

    toggleRemoveExtraButton();
  });

  function toggleRemoveExtraButton() {
    if($('.extra_input_row').length > 1) {
      $('.removeExtraRowButton').show();
    } else {
      $('.removeExtraRowButton').hide();
    }
  }


  $("body").on('keyup', "[data-auto-tax][data-auto-tax-price]", function () {
    let auto_tax_group = $(this).attr('data-auto-tax-group');
    var vat = parseFloat($("[data-auto-tax][data-auto-tax-group='"+auto_tax_group+"'][data-auto-tax-vat]").find(":selected").attr("data-amount"));
    var exvat = parseFloat($(this).val());
    var invat = (exvat + ((exvat / 100) * vat)).toFixed(6);
    $("[data-auto-tax][data-auto-tax-group='"+auto_tax_group+"'][data-auto-tax-final]").val(invat).change();
  });

  $("body").on('change', "[data-auto-tax][data-auto-tax-vat]", function() {
    let auto_tax_group = $(this).attr('data-auto-tax-group');
    var vat = parseFloat($(this).find(":selected").attr("data-amount"));
    var exvat = parseFloat($("[data-auto-tax][data-auto-tax-group='"+auto_tax_group+"'][data-auto-tax-price]").val());
    var invat = (exvat + ((exvat / 100) * vat)).toFixed(6);
    $("[data-auto-tax][data-auto-tax-group='"+auto_tax_group+"'][data-auto-tax-final]").val(invat).change(); //maybe add a check to see which was last edited and then update the opposite input
  });

  $("body").on('keyup', "[data-auto-tax][data-auto-tax-final]", function () {
    let auto_tax_group = $(this).attr('data-auto-tax-group');
    var vat = parseFloat('1.'+$("[data-auto-tax][data-auto-tax-group='"+auto_tax_group+"'][data-auto-tax-vat]").find(":selected").attr("data-amount"));
    var invat = parseFloat($(this).val());
    var exvat = (invat / vat).toFixed(6);
    $("[data-auto-tax][data-auto-tax-group='"+auto_tax_group+"'][data-auto-tax-price]").val(exvat).change();
  });

  

    $('body').on('change', '.boolean_checkbox_input', function() {
        if($(this).is(':checked')) {
            $(this).val(1);
            $(this).parent('label').siblings('input').prop('disabled', true);
        } else {
            $(this).val(0);
            $(this).parent('label').siblings('input').prop('disabled', false);
        }
    });

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
    $('.file_lfm_link').filemanager('file', {prefix: domain});

    $('.summernote-text-editor').summernote({
      height: 150,
      fontNames: ['Arial', 'Arial Black', 'Open Sans', 'Helvetica', 'Helvetica Neue', 'Lato'],
      fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '22', '24', '26', '28', '30', '36'],
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
      if($(this).val() == ''){
        $("#sale_price_ex_input").val(0.000000).change();
      }else{
        $("#sale_price_ex_input").val(exvat).change();
      }
    });


    $(".sale_price_ex_input").keyup(function(){
      var combi_slug = $(this).attr('data-combination-key');
      var vat = parseFloat($('#tax-input').find(":selected").attr("data-amount"));
      var exvat = parseFloat($(this).val());
      var invat = (exvat + ((exvat / 100) * vat)).toFixed(6);
      if($(this).val() == ''){
        $(".sale_price_in_input[data-combination-key="+combi_slug+"]").val(0.000000).change();
      }else{
        $(".sale_price_in_input[data-combination-key="+combi_slug+"]").val(invat).change();
      }
    });

    $(".sale_price_in_input").keyup(function(){
      var combi_slug = $(this).attr('data-combination-key');
      var vat = parseFloat('1.'+$('#tax-input').find(":selected").attr("data-amount"));
      console.log(vat);
      var invat = parseFloat($(this).val());
      var exvat = (invat / vat).toFixed(6);
      console.log(exvat);
      if($(this).val() == ''){
        $(".sale_price_ex_input[data-combination-key="+combi_slug+"]").val(0.000000).change();
      }else{
        $(".sale_price_ex_input[data-combination-key="+combi_slug+"]").val(exvat).change();
      }
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
    });

    $(".attribute-multi-select-input").on('focusout', function(){
      //check if all attributes are filled
      var selectedAttributes = $("#attributes_multi_select").val();
      var totalCombinations = parseInt(1);
      var attributesList = [];
      var attributesFullList = [];
      var isSelected = 0;

      for (var i = selectedAttributes.length - 1; i >= 0; i--) {
        if( $(".attribute-multi-select-input[data-attribute="+selectedAttributes[i]+"]").val() == null ){
          break; //break off function as not all attributes are filled
        } else {
          isSelected++;
          totalCombinations = totalCombinations * parseInt($(".attribute-multi-select-input[data-attribute="+selectedAttributes[i]+"]").val().length);

          var selectedOptions = $(".attribute-multi-select-input[data-attribute="+selectedAttributes[i]+"]").find('option:selected');

          attributesList[i] = [];
          attributesFullList[i] = [];

          selectedOptions.each(function(index) {
            var langs = $(this).attr('data-langs').split(',');
            var name = {};
            for (var g = 0; g < langs.length; g++) {
              name[langs[g]] =$(this).attr('data-type')+' '+$(this).attr('data-name-'+langs[g]);
            };
            console.log('name :: ', name);
            attributesList[i][index] = {
              'key':$(this).attr('value'),
              'name':$(this).attr('data-name'),
              'display_name':name
            };
            attributesFullList[i][index] = {
              'key':$(this).attr('value'),
              'name':$(this).attr('data-name'),
              'display_name':name
            };
          });
          
        }
      };


      if(selectedAttributes.length == isSelected) {
        var isAllSelected = true;
      } else {
        var isAllSelected = false;
      }


      if(isAllSelected == true){
        $('.quantity-row').hide();
        var finalCombinations = [];

        function cartesian(cartes) {
            var r = [], arg = cartes, max = arg.length-1;
            function helper(arr, i) {
                for (var j=0, l=arg[i].length; j<l; j++) {
                    var a = arr.slice(0); // clone arr
                    a.push(arg[i][j]);
                    if (i==max)
                        r.push(a);
                    else
                        helper(a, i+1);
                }
            }
            helper([], 0);
            return r;
        }
        //console.log('og og list ::  ', attributesList);
        preFinalCombinationsList = cartesian(attributesList);
        //console.log('cartesian function :: ', preFinalCombinationsList);
        //console.log('original attributes :: ', attributesFullList);

        //$(".combination-row:not(:first)").remove();
        for (var i = 0; i < totalCombinations; i++) {
          // add/copy attributes data to combination - preFinalCombinationsList[i]
          
          var combinationKey = '';
          var combinationName = '';
          var combinationDisplayName = {};
          var langs = $('.attributes-combinations-block:first').attr('data-langs').split(',');

          for (var g = 0; g < preFinalCombinationsList[i].length; g++) {

            if(combinationKey == ''){
              combinationKey = preFinalCombinationsList[i][g].key;
              combinationName = preFinalCombinationsList[i][g].name;
              for (var k = 0; k < langs.length; k++) {
                combinationDisplayName[langs[k]] = preFinalCombinationsList[i][g].display_name[langs[k]];
              };
              
            } else {
              combinationKey = combinationKey+'__'+preFinalCombinationsList[i][g].key;
              combinationName = combinationName+' '+preFinalCombinationsList[i][g].name;
              for (var k = 0; k < langs.length; k++) {
                combinationDisplayName[langs[k]] = combinationDisplayName[langs[k]]+' '+preFinalCombinationsList[i][g].display_name[langs[k]];
              };
            }

          };

          finalCombinations[i] = {'key':combinationKey,'name':combinationName,'display_name':combinationDisplayName};       
        };

        
        if($('.combination-row').length == 1) { // only 1 row => so no previous combinations...
          for (var i = 0; i < finalCombinations.length; i++) {
            if(i == 0){
              $('.combination-row:first').show();
              $('.combination-row:first').attr('data-combination-key',finalCombinations[i].key);
              $('.combination-row:first').find('.combination_name_input').attr('value', finalCombinations[i].name);
              //change name attributes of inputs
               $('.combination-row:first').find('.combination_price_sale_input').attr('data-combination-key', finalCombinations[i].key);
              $('.combination-row:first').find('.combination_price_final_input').attr('data-combination-key', finalCombinations[i].key);
            } else if(i > 0) {
              $('.combination-row:first').clone().appendTo('.attributes-combinations-block');
              $('.combination-row:last').attr('data-combination-key',finalCombinations[i].key);
              $('.combination-row:last').find('.combination_name_input').attr('value', finalCombinations[i].name);
              //change name attributes of inputs
              $('.combination-row:last').find('.combination_price_sale_input').attr('data-combination-key', finalCombinations[i].key);
              $('.combination-row:last').find('.combination_price_final_input').attr('data-combination-key', finalCombinations[i].key);
            }
          };
        } else { // there are previous combinations
          $('.combination-row').addClass('old-combination-row');
          for (var i = 0; i < finalCombinations.length; i++) {
            //keep combinations that are present, remove others and add remaining new combinations
            
              
              
              if($('.combination-row[data-combination-key="'+finalCombinations[i].key+'"]').length == 0){
                var oldQuantity = '0';
                var oldPriceSale = '0.000000';
                var oldPriceFinal = '0.000000';
                var oldPriceDiscount = '0.000000';
                var oldWidth = '0,00';
                var oldHeight = '0,00';
                var oldDepth = '0,00';
                var oldWeight = '0,000';
              } else {
                var oldQuantity = $('.old-combination-row[data-combination-key="'+finalCombinations[i].key+'"]').find('.combination_quantity_input').val();
                var oldPriceSale = $('.old-combination-row[data-combination-key="'+finalCombinations[i].key+'"]').find('.combination_price_sale_input').val();
                var oldPriceFinal = $('.old-combination-row[data-combination-key="'+finalCombinations[i].key+'"]').find('.combination_price_final_input').val();
                var oldPriceDiscount = $('.old-combination-row[data-combination-key="'+finalCombinations[i].key+'"]').find('.combination_price_discount_input').val();

                var oldWidth = $('.old-combination-row[data-combination-key="'+finalCombinations[i].key+'"]').find('.combination_width_input').val();
                var oldHeight = $('.old-combination-row[data-combination-key="'+finalCombinations[i].key+'"]').find('.combination_height_input').val();
                var oldDepth = $('.old-combination-row[data-combination-key="'+finalCombinations[i].key+'"]').find('.combination_depth_input').val();
                var oldWeight = $('.old-combination-row[data-combination-key="'+finalCombinations[i].key+'"]').find('.combination_weight_input').val();
              }

              console.log('da old quantity :: ', oldQuantity);

              $('.combination-row:first').clone().appendTo('.attributes-combinations-block');
              $('.combination-row:last').removeClass('old-combination-row');
              $('.combination-row:last').attr('data-combination-key',finalCombinations[i].key);
              
              $('.combination-row:last').find('.combination_quantity_input').val(oldQuantity);
              $('.combination-row:last').find('.combination_quantity_input').attr('name', 'combinations['+finalCombinations[i].key+'][quantity]');




              $('.combination-row:last').find('.combination_price_sale_input').val(oldPriceSale);
              $('.combination-row:last').find('.combination_price_sale_input').attr('name', 'combinations['+finalCombinations[i].key+'][price][sale]');
              $('.combination-row:last').find('.combination_price_sale_input').attr('data-combination-key', finalCombinations[i].key);

              $('.combination-row:last').find('.combination_price_final_input').val(oldPriceFinal);
              $('.combination-row:last').find('.combination_price_final_input').attr('name', 'combinations['+finalCombinations[i].key+'][price][final]');
              $('.combination-row:last').find('.combination_price_final_input').attr('data-combination-key', finalCombinations[i].key);

              $('.combination-row:last').find('.combination_price_discount_input').val(oldPriceDiscount);
              $('.combination-row:last').find('.combination_price_discount_input').attr('name', 'combinations['+finalCombinations[i].key+'][price][discount]');


              $('.combination-row:last').find('.combination_width_input').val(oldWidth);
              $('.combination-row:last').find('.combination_width_input').attr('name', 'combinations['+finalCombinations[i].key+'][dimensions][width]');
              $('.combination-row:last').find('.combination_height_input').val(oldHeight);
              $('.combination-row:last').find('.combination_height_input').attr('name', 'combinations['+finalCombinations[i].key+'][dimensions][height]');
              $('.combination-row:last').find('.combination_depth_input').val(oldDepth);
              $('.combination-row:last').find('.combination_depth_input').attr('name', 'combinations['+finalCombinations[i].key+'][dimensions][depth]');
              $('.combination-row:last').find('.combination_weight_input').val(oldWeight);
              $('.combination-row:last').find('.combination_weight_input').attr('name', 'combinations['+finalCombinations[i].key+'][dimensions][weight]');




              $('.combination-row:last').find('.combination_name_input').attr('value', finalCombinations[i].name);
              
              for (var k = 0; k < langs.length; k++) {
                $('.combination-row:last').find('.combination_display_name_'+langs[k]).attr('name', 'combinations['+finalCombinations[i].key+'][display_name]['+langs[k]+']');
                $('.combination-row:last').find('.combination_display_name_'+langs[k]).attr('value', finalCombinations[i].display_name[langs[k]]);
              };
              $('.combination-row:last').find('input.combination_slug').attr('value', finalCombinations[i].key);

              $('.combination-row:last').show();
              
          };
          $('.old-combination-row').remove();
        }
        

        console.log('combinatieLijst :: ', finalCombinations);

        init();

      } else {
        //remove current combination rows / hide will cause fields to be submitted...
        //$('.combination-row').hide();
        $('.combination-row:not(:first)').remove();
        $('.combination-row:first').attr('data-combination-key', '');
        $('.combination-row:first').find('.combination_quantity_input').val('0');
        $('.combination-row:first').hide();
        $('.quantity-row').show();

        init();
      }
    });

  }
    
  });
</script>
<script>
$( document ).ready(function() { 
initData(); 

function initData() {
  $(".resource_key").keyup(function(){
      var text = $(this).val();
      var iOrder = $(this).attr('data-order');
      slug_text = text.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'');
      $(".resource_key[data-order="+iOrder+"]").val(slug_text);   
  });
}

$('.add_resource_field_btn').click(function(){
  $('.resource_field_row:first').clone().appendTo('.resource_field_wrapper');

  $('.resource_field_wrapper').each(function() {
    var lang = $(this).attr('data-lang');
    var order = $(this).find('.resource_field_row').attr('data-order') + 1;

    $( this ).find('#resource_key').attr('name', 'resource_key[' + lang + '][]');
    $( this ).find('#resource_value').attr('name', 'resource_value[' + lang + '][]');

    $( this ).find('.resource_field_row').attr('data-order', order);
    $( this ).find('.resource_key:last').attr('data-order', order);
    $( this ).find('.resource_value:last').attr('data-order', order);
    
  });

  if( $('.resource_field_row').length > 1){
    $('.remove_resource_field_btn').show();
  }

  initData();
});

$('.remove_resource_field_btn').click(function(){
  
  $('.resource_field_wrapper').each(function() {
    
    if($( this ).find('.resource_field_row').length > 1){
      
      $( this ).find('.resource_field_row:last').remove();
      if($( this ).find('.resource_field_row').length == 1){
        $('.remove_resource_field_btn').hide();
      }
    }

  });

});

});
</script>
@endsection