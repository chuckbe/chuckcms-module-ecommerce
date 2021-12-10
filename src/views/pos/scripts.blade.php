<script>
    $(document).ready(function() {
        $('body').on('click', '#openCouponsModal', function (event) {
            event.preventDefault();
            $('#couponsModal').modal('show');
        });
        $('body').on('click', '.cof_pos_product_card', function(event){
            event.preventDefault();
            let json = $.parseJSON($(this).attr('data-product-attributes'));
            $('#attributelist').empty();
            $('#attributedata').empty();
            let i = 0;
            $.each(json, function(index, item){
                console.log(item);
                $('#attributelist').append(`
                    <li class="nav-item">
                        <button 
                            class="nav-link${i == 0 ? ' active' : ''}" 
                            id="${index}-tab" 
                            data-bs-toggle="tab"
                            data-bs-target="#${item.key+'-'+index}" 
                            type="button"
                            role="tab" 
                            aria-controls="${item.key+'-'+index}" 
                            aria-selected="${i == 0 ? 'true' : 'false'}">
                            ${item.key}
                        </button>
                    </li>
                `)
                $('#attributedata').append(`
                    <div class="tab-pane fade${i == 0 ? ' show active' : ''}" id="${item.key+'-'+index}" role="tabpanel" aria-labelledby="${index}-tab">
                        <div class="options_modal_item_radio">
                            <label for="" class="options_item_name">Kies ${item.key}</label>
                            <div class="form-group cof_options_radio_item_input_group mb-2">
                                <div class="form-check cof_options_radio_item_input">
                                    <label class="form-check-label" for="exampleRadios1">
                                        <input class="form-check-input" type="radio" name="cof_options_radio" id="exampleRadios1" value="option1">
                                        <span> Default radio</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
                $(`#attributedata #${item.key+'-'+index} .options_modal_item_radio .cof_options_radio_item_input_group .cof_options_radio_item_input`).empty();
                $.each(item.values, function(inx, value){
                    $(`#attributedata #${item.key+'-'+index} .options_modal_item_radio .cof_options_radio_item_input_group .cof_options_radio_item_input`).append(`
                        <label class="form-check-label d-block" for="${inx}">
                            <input class="form-check-input" type="radio" name="cof_options_radio_${item.key+'-'+index}" id="${inx}" value="${value.value}">
                            <span>${inx}</span>
                        </label>
                    `);
                });
                
                i++;
            });
            $('#optionsModal').modal('show');
        })

        // <label class="btn btn-secondary mr-2 mb-3 attributes_modal_item_button">
        //                 <input type="radio" name="attributes" id="option1"> <span class="attributes_modal_item_button_text">${item.key}</span>
        //             </label>
    });
</script>