<div class="modal fade" id="optionsModal" tabindex="-1" role="dialog" aria-labelledby="optionsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" id="options-form">
                <div class="modal-header">
                    <h5 class="modal-title font-cera-bold" id="optionsModalLabel">Selecteer de opties voor: <span class="options_product_name"></span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="attributesModalBody">
                        <div class="row attributes_modal_row">
                            <div class="col-sm-12 mb-3">
                                @isset($combinations)
                                    <label class="d-block mb-2">Kies attribuut</label>
                                    <div class="btn-group-horizontal btn-group-toggle attributes_modal_item_button_group" id="attributelist" data-toggle="buttons">
                                        @foreach ($combinations as $combination)
                                            <label class="btn btn-secondary mr-2 mb-3 attributes_modal_item_button" for="combination-value{{$loop->index}}">
                                                <input 
                                                    type="radio" 
                                                    name="attribute-combination" 
                                                    value="{{$combination['code']['sku']}}"
                                                    id="combination-value{{$loop->index}}"> 
                                                    <span class="attributes_modal_item_button_text">{{$combination['display_name'][\LaravelLocalization::getCurrentLocale()]}}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endisset
                                {{--  <label class="d-block mb-2">Kies attribuut</label>
                                <div class="btn-group-horizontal btn-group-toggle attributes_modal_item_button_group" id="attributelist" data-toggle="buttons">

                                    <label class="btn btn-secondary mr-2 mb-3 attributes_modal_item_button">
                                        <input type="radio" name="attributes" id="option1"> <span class="attributes_modal_item_button_text">Active</span>
                                    </label>
                                    <label class="btn btn-secondary attributes_modal_item_button">
                                        <input type="radio" name="attributes" id="option2"> Radio
                                    </label>
                                    <label class="btn btn-secondary attributes_modal_item_button">
                                        <input type="radio" name="attributes" id="option3"> Radio
                                    </label>
                                </div>  --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-block" id="addProductFromModalToCartButton">Toevoegen</button>
                </div>
            </form>
        </div>
    </div>
</div>