<div class="container px-3 bg-dark text-white">
    <div class="header row">
        <div class="col-3 d-flex">
            <img class="logo my-auto w-auto" alt="logo" src="{{ URL::to('/') }}{{ ChuckSite::getSetting('logo.href') }}"/>
        </div>
        <div class="col-9 headerSearchArea d-flex justify-content-end">
            <div class="text-end d-flex justify-content-end align-items-center h-100">
                <div class="locationTypeSwitcherWrapper delivery">
                    <div class="locationTypeSwitcherWrapper">
                        <div class="custom-control custom-switch mt-2">
                            <label for="locationTypeSwitcher" class="d-inline-block me-4">Afhalen</label>
                            <input type="checkbox" class="custom-control-input" id="locationTypeSwitcher">
                            <label class="custom-control-label ms-3 me-3" for="locationTypeSwitcher">Dine-in</label>
                        </div>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light align-self-center dropdown-toggle" type="button" id="locationDropdownButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span id="cof_pos_location">Lier</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="locationDropdownButton">
                        @foreach($locations as $location)
                            <a 
                                class="dropdown-item locationDropdownSelect" 
                                href="#" 
                                data-location-id="{{ $location->id }}" 
                                data-pos-name="{{ $location->pos_name }}" 
                                data-pos-address="{{ $location->pos_address1 }}" 
                                data-pos-address-t="{{ $location->pos_address2 }}" 
                                data-pos-vat="{{ $location->pos_vat }}" 
                                data-pos-receipt-title="{{ $location->pos_receipt_title }}" 
                                data-pos-receipt-footer-line="{{ $location->pos_receipt_footer_line1 }}" 
                                data-pos-receipt-footer-line-t="{{ $location->pos_receipt_footer_line2 }}"  
                                data-pos-receipt-footer-line-tt="{{ $location->pos_receipt_footer_line3 }}"
                                >
                                {{ $location->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>