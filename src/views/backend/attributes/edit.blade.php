@extends('chuckcms::backend.layouts.admin')

@section('title')
  Bewerk attribuut
@endsection

@section('content')
<!-- START CONTAINER FLUID -->
<div class=" container-fluid container-fixed-lg">

<!-- START card -->
<form action="{{ route('dashboard.module.ecommerce.attributes.save') }}" method="POST">
<div class="card card-transparent">
  <div class="card-header ">
    <div class="card-title">Bewerk attribuut
    </div>
  </div>

<div class="card-block">
  <div class="row">
    <div class="col-lg-12">
      <div class="card card-default">
        <div class="card-block">
          
          <div class="form-group form-group-default required">
            <label>Naam *</label>
            <input type="text" class="form-control attribute_name_input" placeholder="Naam" id="attribute_name" name="name" value="{{ $attribute->json['name'] }}" required>
          </div>
          <div class="form-group form-group-default form-group-default-select2 required">
            <label>Type </label>
            <select class="full-width" name="type" data-init-plugin="select2" data-minimum-results-for-search="Infinity" data-placeholder="Selecteer een type" data-allow-clear="true" required>
                <option value="select" @if($attribute->json['type'] == 'select') selected @endif>Dropdown keuzelijst</option>
                <option value="radio" @if($attribute->json['type'] == 'radio') selected @endif>Keuzerondjes</option>
                <option value="color" @if($attribute->json['type'] == 'color') selected @endif>Kleur</option>
            </select>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

  <div class="card-block">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-transparent">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs nav-tabs-linetriangle" data-init-reponsive-tabs="dropdownfx">
            @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
              <li class="nav-item">
                <a href="#" @if($loop->iteration == 1) class="active" @endif data-toggle="tab" data-target="#tab_resource_{{ $langKey }}"><span>{{ $langValue['name'] }} ({{ $langValue['native'] }})</span></a>
              </li>
            @endforeach
          </ul>
          <!-- Tab panes -->
          <div class="tab-content">

            @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
            <div class="tab-pane fade show @if($loop->iteration == 1) active @endif" id="tab_resource_{{ $langKey }}">
              <div class="row column-seperation">
                <div class="col-lg-12">

                  <div class="form-group form-group-default required ">
                    <label>Weergavenaam *</label>
                    <input type="text" class="form-control attribute_display_name_input" placeholder="Weergavenaam" id="attribute_display_name_input" name="display_name[{{ $langKey }}]" value="{{ $attribute->getJson('display_name.'.$langKey) ? $attribute->json['display_name'][$langKey] : $attribute->json['name'] }}" required>
                  </div>

                  <hr>

                  <div class="resource_field_wrapper" data-lang="{{ $langKey }}">
                    @if(array_key_exists('values', $attribute->json))
                    @foreach($attribute->json['values'] as $vKey => $vValue)
                    <div class="row resource_field_row" data-order="{{ $loop->iteration }}">
                      <div class="col-sm-2">
                        <div class="row">
                          <button type="button" class="btn btn-xs btn-danger remove_attribute_button" data-order="{{ $loop->iteration }}" style="margin: 15px 2px;"><i class="fa fa-trash"></i></button>
                          <button type="button" class="btn btn-xs btn-primary move_down_attribute_button" data-order="{{ $loop->iteration }}" style="margin: 15px 2px;"><i class="fa fa-arrow-down"></i></button>
                          <button type="button" class="btn btn-xs btn-primary move_up_attribute_button" data-order="{{ $loop->iteration }}" style="margin: 15px 2px;"><i class="fa fa-arrow-up"></i></button>
                        </div>
                      </div>

                      <div class="col-sm-10">
                        <div class="row">
                          <div class="col-sm-4">
                            <div class="form-group form-group-default required ">
                              <label>Attribuut Key *</label>
                              <input type="text" class="form-control attribute_key" placeholder="Key" id="attribute_key" name="attribute_key[{{ $langKey }}][]" data-order="{{ $loop->iteration }}" value="{{ $vKey }}" required>
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group form-group-default required ">
                              <label>Attribuut Waarde *</label>
                              <input type="text" class="form-control attribute_value" placeholder="Waarde" id="attribute_value" name="attribute_value[{{ $langKey }}][]" data-order="{{ $loop->iteration }}" value="{{ $vValue['value'] }}" required>
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group form-group-default required ">
                              <label>Attribuut Naam *</label>
                              <input type="text" class="form-control attribute_display_name" placeholder="Naam" id="attribute_display_name" name="attribute_display_name[{{ $langKey }}][]" data-order="{{ $loop->iteration }}" value="{{ $vValue['display_name'][$langKey] }}" required>
                            </div>
                          </div>
                        </div>
                      </div>

                      
                    </div>
                    @endforeach
                    @else
                    <div class="row resource_field_row" data-order="1">
                        
                        <div class="col-sm-2">
                          <div class="row">
                            <button type="button" class="btn btn-xs btn-danger remove_attribute_button" data-order="1" style="margin: 15px 2px;"><i class="fa fa-trash"></i></button>
                            <button type="button" class="btn btn-xs btn-primary move_down_attribute_button" data-order="1" style="margin: 15px 2px;"><i class="fa fa-arrow-down"></i></button>
                            <button type="button" class="btn btn-xs btn-primary move_up_attribute_button" data-order="1" style="margin: 15px 2px;"><i class="fa fa-arrow-up"></i></button>
                          </div>
                        </div>

                        <div class="col-sm-10">
                          <div class="row">
                            <div class="col-sm-4">
                              <div class="form-group form-group-default required ">
                                <label>Attribuut Key *</label>
                                <input type="text" class="form-control attribute_key" placeholder="Key" id="attribute_key" name="attribute_key[{{ $langKey }}][]" data-order="1" value="" required>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group form-group-default required ">
                                <label>Attribuut Waarde *</label>
                                <input type="text" class="form-control attribute_value" placeholder="Waarde" id="attribute_value" name="attribute_value[{{ $langKey }}][]" data-order="1" value="" required>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group form-group-default required ">
                                <label>Attribuut Naam *</label>
                                <input type="text" class="form-control attribute_display_name" placeholder="Naam" id="attribute_display_name" name="attribute_display_name[{{ $langKey }}][]" data-order="1" value="" required>
                              </div>
                            </div>
                          </div>
                        </div>
                      
                    </div>
                    @endif
                  </div>

                  <hr>
                  <div class="row">
                    <div class="col-lg-6">
                      <button type="button" class="btn btn-primary add_resource_field_btn" id="add_resource_field_btn">+ Toevoegen</button>
                    </div>
                    <div class="col-lg-6">
                      <button type="button" class="btn btn-warning remove_resource_field_btn" id="remove_resource_field_btn" style="display:none;">- Verwijderen</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach

          </div>
          <br>
          <p class="pull-right">
            <input type="hidden" name="id" value="{{ $attribute->id }}">
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <button type="submit" name="update" class="btn btn-success btn-cons pull-right" value="1">Opslaan</button>
            <a href="{{ route('dashboard.module.ecommerce.attributes.index') }}" class="pull-right"><button type="button" class="btn btn-info btn-cons">Annuleren</button></a>
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
@endsection

@section('scripts')
	<script>
		$( document ).ready(function() { 
    init(); 


    function init() {
			$(".attribute_dispay_input").keyup(function(){
			    var text = $(this).val();
			    slug_text = text.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'');
			    $(".attribute_disple_input").val(slug_text);   
			});

      $(".attribute_key").keyup(function(){
          var text = $(this).val();
          var iOrder = $(this).attr('data-order');
          slug_text = text.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'');
          $(".attribute_key[data-order="+iOrder+"]").val(slug_text);   
      });

      $(".attribute_value").keyup(function(){
          var text = $(this).val();
          var iOrder = $(this).attr('data-order');
          slug_text = text.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'');
          $(".attribute_value[data-order="+iOrder+"]").val(slug_text);   
      });



      $('.remove_attribute_button').unbind('click').on('click', function(){
          var order = parseInt($(this).attr('data-order'));//store order of row to be deleted
          $('.resource_field_wrapper').each(function() {
            var lang = $(this).attr('data-lang');
            if($( this ).find('.resource_field_row').length > 1){

                $( this ).find('.resource_field_row[data-order="'+order+'"]').remove();
                
                $( this ).find('.resource_field_row').each(function() {
                  //update number from following rows
                  if( parseInt($(this).attr('data-order')) > order ){
                    var currentOrder = parseInt($(this).attr('data-order')); //currentRowOrder
                    var newOrder = currentOrder - 1;

                    //loop over all elements inside and lower the order by 1
                    $(this).find('[data-order='+currentOrder+']').each(function() {
                      $(this).attr('data-order', newOrder);
                    });

                    //change order of row itself
                    $(this).attr('data-order', newOrder);
                  }
                });


                
            }
          });
      });

      $('.move_down_attribute_button').unbind('click').on('click', function(){
          var order = parseInt($(this).attr('data-order'));//store order of row to be deleted
          $('.resource_field_wrapper').each(function() {
            var lang = $(this).attr('data-lang');
            var lastOrder = parseInt($(this).find('.resource_field_row:last').attr('data-order'));
            var newOrder = order + 1;

            if($( this ).find('.resource_field_row').length > 1 && order !== lastOrder){
                
                //$(this).find('.resource_field_row[data-order="'+order+'"]'); 
                
                $( this ).find('.resource_field_row').each(function() {
                  var currentOrder = parseInt($(this).attr('data-order'));
                  //update number from following rows
                  if( currentOrder == order ){

                    //loop over all elements inside and lower the order by 1
                    $(this).find('[data-order='+order+']').each(function() {
                      $(this).attr('data-order', newOrder);
                    });

                    //change order of row itself
                    $(this).attr('data-order', newOrder);
                  } 
                  else if( currentOrder == newOrder ){
                    //loop over all elements inside and lower the order by 1
                    $(this).find('[data-order='+currentOrder+']').each(function() {
                      $(this).attr('data-order', order);
                    });

                    //change order of row itself
                    $(this).attr('data-order', order);
                  }
                });

                $(this).find('.resource_field_row[data-order="'+order+'"]').detach().insertBefore($(this).find('.resource_field_row[data-order="'+newOrder+'"]'));
            }
          });
      });


      $('.move_up_attribute_button').unbind('click').on('click', function(){
          var order = parseInt($(this).attr('data-order'));//store order of row to be deleted
          $('.resource_field_wrapper').each(function() {
            var lang = $(this).attr('data-lang');
            var firstOrder = parseInt($(this).find('.resource_field_row:first').attr('data-order'));
            var newOrder = order - 1;

            if($( this ).find('.resource_field_row').length > 1 && order !== firstOrder){
                
                //$(this).find('.resource_field_row[data-order="'+order+'"]'); 
                
                $( this ).find('.resource_field_row').each(function() {
                  var currentOrder = parseInt($(this).attr('data-order'));
                  //update number from following rows
                  if( currentOrder == order ){

                    //loop over all elements inside and lower the order by 1
                    $(this).find('[data-order='+order+']').each(function() {
                      $(this).attr('data-order', newOrder);
                    });

                    //change order of row itself
                    $(this).attr('data-order', newOrder);
                  } 
                  else if( currentOrder == newOrder ){
                    //loop over all elements inside and lower the order by 1
                    $(this).find('[data-order='+currentOrder+']').each(function() {
                      $(this).attr('data-order', order);
                    });

                    //change order of row itself
                    $(this).attr('data-order', order);
                  }
                });

                $(this).find('.resource_field_row[data-order="'+newOrder+'"]').detach().insertBefore($(this).find('.resource_field_row[data-order="'+order+'"]'));
            }
          });
      });

    }

    


			$('.add_resource_field_btn').click(function(){
        
        var order = parseInt($('.resource_field_wrapper').find('.resource_field_row:last').attr('data-order')) + 1;
        
        $('.resource_field_row:first').clone().appendTo('.resource_field_wrapper');

        $('.resource_field_wrapper').each(function() {
          var lang = $(this).attr('data-lang');

          $( this ).find('#attribute_key').attr('name', 'attribute_key[' + lang + '][]');
          $( this ).find('#attribute_value').attr('name', 'attribute_value[' + lang + '][]');
          $( this ).find('#attribute_display_name').attr('name', 'attribute_display_name[' + lang + '][]');

          $( this ).find('.resource_field_row:last').attr('data-order', order);

          $( this ).find('.attribute_key:last').attr('data-order', order);
          $( this ).find('.attribute_value:last').attr('data-order', order);
          $( this ).find('.attribute_display_name:last').attr('data-order', order);

          $( this ).find('.remove_attribute_button:last').attr('data-order', order);
          $( this ).find('.move_down_attribute_button:last').attr('data-order', order);
          $( this ).find('.move_up_attribute_button:last').attr('data-order', order);

          $( this ).find('.attribute_key:last').val('');
          $( this ).find('.attribute_value:last').val('');
          $( this ).find('.attribute_display_name:last').val('');
          
        });

        if( $('.resource_field_row').length > 1){
          $('.remove_resource_field_btn').show();
        }

        init();
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