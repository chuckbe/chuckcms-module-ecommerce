@extends('chuckcms::backend.layouts.base')

@section('content')
<div class="container min-height pb-3">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.module.ecommerce.discounts.index') }}">Kortingen & Coupons</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Korting Bewerken</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="my-3">
                <ul class="nav nav-tabs justify-content-start" id="pageTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="d_general-tab" data-toggle="tab" href="#d_general" role="tab" aria-controls="d_general" aria-selected="true">Algemeen</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="d_conditions-tab" data-toggle="tab" href="#d_conditions" role="tab" aria-controls="d_conditions" aria-selected="false">Voorwaarden</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="d_actions-tab" data-toggle="tab" href="#d_actions" role="tab" aria-controls="d_actions" aria-selected="false">Acties</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <form action="{{ route('dashboard.module.ecommerce.discounts.save') }}" method="POST">
        <div class="row tab-content bg-light shadow-sm rounded p-3 mb-3 mx-1" id="pageTabContent">
            
            <div class="col-sm-12 tab-pane fade show active" id="d_general" role="tabpanel" aria-labelledby="d_general-tab">
              @include('chuckcms-module-ecommerce::backend.discounts.edit._tab_general')
            </div>


            <div class="col-sm-12 tab-pane fade" id="d_conditions" role="tabpanel" aria-labelledby="d_conditions-tab">
              @include('chuckcms-module-ecommerce::backend.discounts.edit._tab_conditions')
            </div>


            <div class="col-sm-12 tab-pane fade" id="d_actions" role="tabpanel" aria-labelledby="d_actions-tab">
              @include('chuckcms-module-ecommerce::backend.discounts.edit._tab_actions')  
            </div>

        </div>
        <div class="row">
            <div class="col-sm-12 text-right">
                <input type="hidden" name="_token" value="{{ Session::token() }}">
                <input type="hidden" name="id" value="{{ $discount->id }}">
                <button class="btn btn-outline-success" value="1" name="update" type="submit">Bewerken</button>
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
$( "#options_input_container" ).sortable({revert: true});

$('body').on('change', '.conditions_type_selector', function() {
    var condition_type = $(this).find('option:selected').first().val();
    var condition_selector = $(this).attr('data-element-selector');
    $(condition_selector).find('option[data-type="'+condition_type+'"]').removeClass('d-none').prop('disabled', false).prop('selected', false);
    $(condition_selector).find('option:not([data-type="'+condition_type+'"])').addClass('d-none').prop('disabled', true).prop('selected', false);
    $(condition_selector).find('option[data-type="'+condition_type+'"]').first().prop('selected', true);
});

$('.conditions_type_selector').trigger('change');

$('body').on('click', '.remove_line_button', function() {
    checker = $(this).parents('._input_container').find('._input_line').length;
    if(checker > 1) {
        $(this).parents('._input_line').remove();
    } else {
        $(this).parents('._input_line').addClass('d-none');
        $(this).parents('._input_line').find('select').prop('disabled', true);
    }
});

$('body').on('click', '#new_condition_button', function() {
    $('#new_condition_error').addClass('d-none');
    if($('#new_conditions_type').find('option:selected').first().val().length == 0 || $('#new_conditions_value').find('option:selected').first().val().length == 0) {
        $('#new_condition_error').removeClass('d-none');
        return;
    }

    new_type = $('#new_conditions_type').find('option:selected').first().val();
    new_value = $('#new_conditions_value').find('option:selected').first().val();
console.log('new type and value : ',new_type,' - ', new_value);
    //new_file = $('#new_css_asset').is(':checked');

    if($('.conditions_input_line').length > 1) {
        $('.conditions_input_line:first').clone().appendTo('.conditions_wrapper');
        //$('.conditions_wrapper').append('<hr>');
    } else {
      if($('.conditions_input_line:first').hasClass('d-none')) {
        $('.conditions_input_line:first').removeClass('d-none');
        $('.conditions_input_line:first').find('select').prop('disabled', false);
      } else {
        $('.conditions_input_line:first').clone().appendTo('.conditions_wrapper');
        //$('.conditions_wrapper').append('<hr>');
      }
        
    }

    $('.conditions_input_line:last').find('.condition_type_input').first().find('option:not([data-type="'+new_type+'"])').prop('selected', false).prop('disabled', true);
    $('.conditions_input_line:last').find('.condition_type_input').first().find('option[value="'+new_type+'"]').first().prop('disabled', false).prop('selected', true);


    $('.conditions_input_line:last').find('.condition_value_input').first().find('option').addClass('d-none').prop('selected', false).prop('disabled', true);
    $('.conditions_input_line:last').find('.condition_value_input').first().find('option[data-type="'+new_type+'"]').removeClass('d-none').prop('disabled', false);
    $('.conditions_input_line:last').find('.condition_value_input').first().find('option[value="'+new_value+'"]').first().removeClass('d-none').prop('disabled', false).prop('selected', true);
});


});
</script>

<script>
    $( document ).ready(function() { 

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

  }
        
    });
</script>
@endsection