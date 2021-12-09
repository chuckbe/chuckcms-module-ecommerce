
<!-- Modal -->
<div class="modal fade stick-up disable-scroll" id="editLocationModal" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content-wrapper">
            <div class="modal-content">
                <div class="modal-header clearfix text-left">
                    <h5 class="modal-title">Bewerk de volgende <span class="semi-bold">locatie</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <p class="p-b-10">Bewerk de volgende velden om de locatie te wijzigen.</p>
                    </div>
                    <form role="form" method="POST" action="{{ route('dashboard.module.ecommerce.locations.save') }}">
                        <div class="form-group-attached">
                            <div class="row">
                                <div class="col-md-12">
                                <div class="form-group form-group-default required">
                                    <label>Naam</label>
                                    <input type="text" id="edit_location_name" name="name" class="form-control" required>
                                </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                <div class="form-group form-group-default">
                                    <label>POS gebruikers (user_id,user_id,...)</label>
                                    <input type="text" id="edit_location_pos_users" name="pos_users" class="form-control">
                                </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group form-group-default">
                                    <label>POS Naam *</label>
                                    <input type="text" id="edit_location_pos_name" name="pos_name" class="form-control" required>
                                  </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group form-group-default">
                                    <label>POS Adreslijn 1 *</label>
                                    <input type="text" id="edit_location_pos_address1" name="pos_address1" class="form-control" required>
                                  </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group form-group-default">
                                    <label>POS Adreslijn 2</label>
                                    <input type="text" id="edit_location_pos_address2" name="pos_address2" class="form-control">
                                  </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group form-group-default">
                                    <label>POS BTW-nummer *</label>
                                    <input type="text" id="edit_location_pos_vat" name="pos_vat" class="form-control" required>
                                  </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group form-group-default">
                                    <label>POS Ticket Titel *</label>
                                    <input type="text" id="edit_location_pos_receipt_title" name="pos_receipt_title" class="form-control" required>
                                  </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group form-group-default">
                                    <label>POS Afsluitlijn 1</label>
                                    <input type="text" id="edit_location_pos_receipt_footer_line1" name="pos_receipt_footer_line1" class="form-control">
                                  </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group form-group-default">
                                    <label>POS Afsluitlijn 2</label>
                                    <input type="text" id="edit_location_pos_receipt_footer_line2" name="pos_receipt_footer_line2" class="form-control">
                                  </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group form-group-default">
                                    <label>POS Afsluitlijn 3</label>
                                    <input type="text" id="edit_location_pos_receipt_footer_line3" name="pos_receipt_footer_line3" class="form-control">
                                  </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group form-group-default required">
                                    <label>Volgorde</label>
                                    <input type="number" min="0" steps="1" max="9999" id="edit_location_order" name="order" class="form-control" required>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 m-t-10 sm-m-t-10 pull-right">
                                <input type="hidden" id="edit_location_id" name="id" value="">
                                <input type="hidden" name="update">
                                <input type="hidden" name="_token" value="{{ Session::token() }}">
                                <button type="button" class="btn btn-default m-t-5" data-dismiss="modal" aria-hidden="true">Annuleren</button>
                                <button type="submit" class="btn btn-primary float-right">Bewerken</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>