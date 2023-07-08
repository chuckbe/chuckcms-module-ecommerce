<div class="handlerArea row">
    <div class="container pl-5 pr-5 pt-4">
        <div class="row">

            <div class="col-12 col-lg-4 p-1">
                <div class="card shadow kassieriInfomatie">
                    <div class="card-body">
                      <div class="row pb-2 align-items-center">
                          <div class="col-12 m-0 py-1 px-3">
                            <p class="card-text mb-1"><small>Kassier: <br>{{ucwords(Auth::user()->name)}}</small></p>
                          </div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8 p-1">
                <div class="card shadow promoInformatie">
                    <div class="card-body">{{--
                      <h5 class="card-title">Promo-code toevoegen</h5> --}}
                      <div class="row pb-3 align-items-center">
                        {{-- @foreach(ChuckRepeater::for(config('chuckcms-module-order-form.discounts.slug')) as $discount)
                        <div class="col-3">
                            <button class="btn btn-sm w-100"><small>{{ $discount->name }}</small></button>
                        </div>
                        @endforeach --}}
                        <div class="col-12 col-md-6">
                            <button class="btn w-100 mb-3 mb-md-0" id="openCouponsModal"><i class="fas fa-list"></i> KORTING</button>
                        </div>
                        <div class="col-12 col-md-6">
                            <button class="btn w-100"><i class="fas fa-qrcode"></i> SCAN</button>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
