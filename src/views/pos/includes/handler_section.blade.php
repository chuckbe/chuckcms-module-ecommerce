<div class="container px-3 ps-lg-5 pr-lg-5 pt-4">
    <div class="handlerArea row">
        <div class="col-lg-4 p-1">
            <div class="card shadow kassierInformatie">
                <div class="card-body py-2">
                    <div class="row pb-1 align-items-center">
                        <div class="col-lg-12 m-0 py-1 px-3">
                            <p class="card-text mb-1"><small>Kassier: <br>{{ucwords(Auth::user()->name)}}</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 p-1">
            <div class="card shadow promoInformatie">
                <div class="card-body py-2">
                    <div class="row pb-3 align-items-center">
                        <div class="col-6">
                            <button class="btn bg-dark text-white btn-sm w-100" id="openCouponsModal"><small><i class="fas fa-list"></i> KORTINGEN</small></button>
                        </div>
                        <div class="col-6">
                            <button class="btn bg-dark text-white shadow btn-sm w-100"><small><i class="fas fa-qrcode"></i> SCAN</small></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>