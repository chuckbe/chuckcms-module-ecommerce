@extends('chuckcms::backend.layouts.base')

@section('content')
<div class="container min-height pb-3">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.module.ecommerce.discounts.index') }}">Kortingen & Coupons</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nieuwe Korting</li>
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
              @include('chuckcms-module-ecommerce::backend.discounts.create._tab_general')
            </div>


            <div class="col-sm-12 tab-pane fade" id="d_conditions" role="tabpanel" aria-labelledby="d_conditions-tab">
              @include('chuckcms-module-ecommerce::backend.discounts.create._tab_conditions')
            </div>


            <div class="col-sm-12 tab-pane fade" id="d_actions" role="tabpanel" aria-labelledby="d_actions-tab">
              @include('chuckcms-module-ecommerce::backend.discounts.create._tab_actions')  
            </div>

        </div>
        <div class="row">
            <div class="col-sm-12 text-right">
                <input type="hidden" name="_token" value="{{ Session::token() }}">
                <button class="btn btn-outline-success" value="1" name="create" type="submit">Aanmaken</button>
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
    let token = '{{ Session::token() }}';

    $( "#options_input_container" ).sortable({revert: true});

    $('body').on('click', '#discount_code_refresh_button', function (event) {
        event.preventDefault();
        button = $(this);

        button.find('i').addClass('fa-spin');
        new_code_str = '';

        refreshDiscountCode().done(function (data) {
            if(data.status == 'success'){
                new_code_str = data.code;
            } 

            $('#discount_code').val(new_code_str);
            button.find('i').removeClass('fa-spin');
        });
    });

    $('body').on('change', '.rule_type_selector', function() {
        var condition_type = $(this).find('option:selected').first().val();
        var condition_selector = $(this).attr('data-element-selector');
        $(condition_selector).find('option[data-type="'+condition_type+'"]').removeClass('d-none').prop('disabled', false).prop('selected', false);
        $(condition_selector).find('option:not([data-type="'+condition_type+'"])').addClass('d-none').prop('disabled', true).prop('selected', false);
        $(condition_selector).find('option[data-type="'+condition_type+'"]').first().prop('selected', true);
    });

    $('.rule_type_selector').trigger('change');

    $('body').on('click', '.remove_line_button', function() {
        let checker = $(this).parents('._input_container').find('._input_line').length;
        let group_id = $(this).parents('.conditions_wrapper').attr('data-group');
        if(checker > 1 || group_id !== '1') {
            $(this).parents('._input_line').remove();
        } else {
            $(this).parents('._input_line').addClass('d-none');
            $(this).parents('._input_line').find('select').prop('disabled', true);
        }
    });

    $('body').on('click', '#new_rule_button', function() {
        $('#new_rule_error').addClass('d-none');

        let group_id = $(this).attr('data-group');
        if ($('.conditions_wrapper[data-group="'+group_id+'"]').length == 0) {
            group_id = $('.conditions_wrapper:first').attr('data-group');
        }

        if($('#new_rule_type').find('option:selected').first().val().length == 0 || $('#new_rule_value').find('option:selected').first().val().length == 0) {
            $('#new_rule_error').removeClass('d-none');
            return;
        }

        new_type = $('#new_rule_type').find('option:selected').first().val();
        new_value = $('#new_rule_value').find('option:selected').first().val();

        if($('.conditions_input_line').length > 1) {
            if(group_id == '1' && $('.conditions_input_line:first').hasClass('d-none')) {
                $('.conditions_input_line:first').removeClass('d-none');
                $('.conditions_input_line:first').find('select').prop('disabled', false);
            } else {
                $('.conditions_input_line:first').clone().appendTo('.conditions_wrapper[data-group="'+group_id+'"]');
                $('.conditions_wrapper[data-group="'+group_id+'"]')
                        .find('.conditions_input_line:last')
                        .removeClass('d-none');
                $('.conditions_wrapper[data-group="'+group_id+'"]')
                        .find('.conditions_input_line:last')
                        .find('select').prop('disabled', false);
            }
        } else {
          if(group_id == '1' && $('.conditions_input_line:first').hasClass('d-none')) {
            $('.conditions_input_line:first').removeClass('d-none');
            $('.conditions_input_line:first').find('select').prop('disabled', false);
          } else {
            $('.conditions_input_line:first').clone().appendTo('.conditions_wrapper[data-group="'+group_id+'"]');
            $('.conditions_wrapper[data-group="'+group_id+'"]')
                    .find('.conditions_input_line:last')
                    .removeClass('d-none');
            $('.conditions_wrapper[data-group="'+group_id+'"]')
                    .find('.conditions_input_line:last')
                    .find('select').prop('disabled', false);
          }
            
        }

        $('.conditions_wrapper[data-group="'+group_id+'"]')
            .find('.conditions_input_line:last')
            .find('.condition_type_input').first()
                .prop('name', 'condition_type['+group_id+'][]');

        $('.conditions_wrapper[data-group="'+group_id+'"]')
            .find('.conditions_input_line:last')
            .find('.condition_type_input').first()
            .find('option:not([data-type="'+new_type+'"])').prop('selected', false).prop('disabled', true);
        
        $('.conditions_wrapper[data-group="'+group_id+'"]')
            .find('.conditions_input_line:last')
            .find('.condition_type_input').first()
            .find('option[value="'+new_type+'"]').first().prop('disabled', false).prop('selected', true);


        $('.conditions_wrapper[data-group="'+group_id+'"]')
            .find('.conditions_input_line:last')
            .find('.condition_value_input').first()
                .prop('name', 'condition_value['+group_id+'][]');

        $('.conditions_wrapper[data-group="'+group_id+'"]')
            .find('.conditions_input_line:last')
            .find('.condition_value_input').first()
            .find('option').addClass('d-none').prop('selected', false).prop('disabled', true);
        
        $('.conditions_wrapper[data-group="'+group_id+'"]')
            .find('.conditions_input_line:last')
            .find('.condition_value_input').first()
            .find('option[data-type="'+new_type+'"]').removeClass('d-none').prop('disabled', false);
        
        $('.conditions_wrapper[data-group="'+group_id+'"]')
            .find('.conditions_input_line:last')
            .find('.condition_value_input').first()
            .find('option[value="'+new_value+'"]').first().removeClass('d-none').prop('disabled', false).prop('selected', true);

        $(this).attr('data-group', '0');
        $('#addNewRuleWrapper').addClass('d-none');
    });

    $('body').on('click', '#addNewConditionGroupBtn', function (event) {
        event.preventDefault();

        $('.conditions_wrapper:first').clone().appendTo('.conditions_group_container');
        
        if ($('.conditions_wrapper').length > 1) {
            $('.conditions_wrapper:last').attr('data-group', $('.conditions_wrapper').length);
            $('.conditions_wrapper:last').find('input[name="condition_min_quantity[]"]').val(1);
            $('.conditions_wrapper:last').find('.remove_condition_group_btn').removeClass('d-none');
            $('.conditions_wrapper:last').find('.remove_line_button').trigger('click');
            $('.conditions_wrapper:last').find('.conditions_input_line:first').remove();
        }
    });

    $('body').on('click', '.add_rule_btn', function (event) {
        event.preventDefault();

        let group_id = $(this).parents('.conditions_wrapper').attr('data-group');
        $('#new_rule_button').attr('data-group', group_id);
        $('#addNewRuleWrapper').removeClass('d-none');
    });

    $('body').on('click', '.remove_condition_group_btn', function (event) {
        event.preventDefault();

        if ($('.conditions_wrapper').length > 1) {
            $(this).parents('.conditions_wrapper').remove();
            $('#new_rule_button').attr('data-group', '0');
            $('#addNewRuleWrapper').addClass('d-none');
        }
    });

    $('body').on('change', '.action_type_input', function (event) {
        let apply_on = $('input[name="apply_on"]:checked').val();
        let action_type = $(this).find('option:selected').val();
        if (action_type == 'gift') {
            
            if (apply_on == 'conditions' || apply_on == 'cart') {
                $('#apply_on_cart').prop('checked', false);
                $('#apply_on_conditions').prop('checked', false);
                $('#apply_on_product').prop('checked', true).trigger('change');
            }
            
            $('#apply_on_cart').prop('disabled', true);
            $('#apply_on_conditions').prop('disabled', true);

        } else {

            $('#apply_on_cart').prop('disabled', false);
            $('#apply_on_conditions').prop('disabled', false);

        }
    });

    $('body').on('change', 'input[name="apply_on"]', function (event) {
        let apply_on = $('input[name="apply_on"]:checked').val();
        if (apply_on == 'product') {
            $('#actions_apply_products_row').removeClass('d-none');
            $('#apply_product').prop('disabled', false);
        } else {
            $('#actions_apply_products_row').addClass('d-none');
            $('#apply_product').prop('disabled', true);
        }
    });

    function refreshDiscountCode() {
        return $.ajax({
            method: 'POST',
            url: "{{ route('dashboard.module.ecommerce.discounts.refresh_code') }}",
            data: { 
                _token: token
            }
        });
    }


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