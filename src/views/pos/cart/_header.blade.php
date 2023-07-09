<div class="bestelHeader row align-items-center">
    <div class="col-4 text-left h-100">
        <h4 class="bestelHeaderTitle">Kassa</h4>
    </div>
    <div class="col-8 text-right bestelHeaderInstellingen h-100">
        @role('super-admin')
        <a href="{{ route('dashboard') }}"><button type="button" class="btn shadow-sm mr-3 me-3"><i class="fas fa-home"></i></button></a>
        @endrole

        <button type="button" class="btn shadow-sm mr-2 me-2" id="pos_listOrdersBtn">
            <i class="fas fa-list"></i>
        </button>

        <button type="button" class="btn shadow-sm mr-2 me-2" id="pos_refreshToggleBtn" onclick="window.location = window.location;">
            <i class="fas fa-redo"></i>
        </button>

        <button type="button" class="btn shadow-sm" id="pos_fullScreenToggleBtn">
            <i class="fas fa-expand-arrows-alt"></i>
        </button>
        {{-- <button type="button" class="btn shadow-sm"><i class="fas fa-cog"></i></button> --}}
    </div>
</div>
