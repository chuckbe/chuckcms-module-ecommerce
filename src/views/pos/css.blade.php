<style>
:root {
  --main-color: #000;
}

.header .logo {
    max-height: calc(100% - 1.5rem);
}
.custom-control {
    position: relative;
    display: block;
    min-height: 1.5rem;
    padding-left: 1.5rem
}

.custom-control-inline {
    display: -ms-inline-flexbox;
    display: inline-flex;
    margin-right: 1rem
}

.custom-control-input {
    position: absolute;
    left: 0;
    z-index: -1;
    width: 1rem;
    height: 1.25rem;
    opacity: 0
}

.custom-control-input:checked~.custom-control-label::before {
    color: #fff;
    border-color: #007bff;
    background-color: #007bff
}

.custom-control-input:focus~.custom-control-label::before {
    box-shadow: 0 0 0 .2rem rgba(0,123,255,.25)
}

.custom-control-input:focus:not(:checked)~.custom-control-label::before {
    border-color: #80bdff
}

.custom-control-input:not(:disabled):active~.custom-control-label::before {
    color: #fff;
    background-color: #b3d7ff;
    border-color: #b3d7ff
}

.custom-control-input:disabled~.custom-control-label,.custom-control-input[disabled]~.custom-control-label {
    color: #6c757d
}

.custom-control-input:disabled~.custom-control-label::before,.custom-control-input[disabled]~.custom-control-label::before {
    background-color: #e9ecef
}

.custom-control-label {
    position: relative;
    margin-bottom: 0;
    vertical-align: top
}

.custom-control-label::before {
    position: absolute;
    top: .25rem;
    left: -1.5rem;
    display: block;
    width: 1rem;
    height: 1rem;
    pointer-events: none;
    content: "";
    background-color: #fff;
    border: #adb5bd solid 1px
}

.custom-control-label::after {
    position: absolute;
    top: .25rem;
    left: -1.5rem;
    display: block;
    width: 1rem;
    height: 1rem;
    content: "";
    background: no-repeat 50%/50% 50%
}

.custom-checkbox .custom-control-label::before {
    border-radius: .25rem
}

.custom-checkbox .custom-control-input:checked~.custom-control-label::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26l2.974 2.99L8 2.193z'/%3e%3c/svg%3e")
}

.custom-checkbox .custom-control-input:indeterminate~.custom-control-label::before {
    border-color: #007bff;
    background-color: #007bff
}

.custom-checkbox .custom-control-input:indeterminate~.custom-control-label::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='4' viewBox='0 0 4 4'%3e%3cpath stroke='%23fff' d='M0 2h4'/%3e%3c/svg%3e")
}

.custom-checkbox .custom-control-input:disabled:checked~.custom-control-label::before {
    background-color: rgba(0,123,255,.5)
}

.custom-checkbox .custom-control-input:disabled:indeterminate~.custom-control-label::before {
    background-color: rgba(0,123,255,.5)
}

.custom-radio .custom-control-label::before {
    border-radius: 50%
}

.custom-radio .custom-control-input:checked~.custom-control-label::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e")
}

.custom-radio .custom-control-input:disabled:checked~.custom-control-label::before {
    background-color: rgba(0,123,255,.5)
}

.custom-switch {
    padding-left: 2.25rem
}

.custom-switch .custom-control-label::before {
    left: -2.25rem;
    width: 1.75rem;
    pointer-events: all;
    border-radius: .5rem
}

.custom-switch .custom-control-label::after {
    top: calc(.25rem + 2px);
    left: calc(-2.25rem + 2px);
    width: calc(1rem - 4px);
    height: calc(1rem - 4px);
    background-color: #adb5bd;
    border-radius: .5rem;
    transition: background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-transform .15s ease-in-out;
    transition: transform .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    transition: transform .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-transform .15s ease-in-out
}

@media (prefers-reduced-motion:reduce) {
    .custom-switch .custom-control-label::after {
        transition: none
    }
}

.custom-switch .custom-control-input:checked~.custom-control-label::after {
    background-color: #fff;
    -webkit-transform: translateX(.75rem);
    transform: translateX(.75rem)
}

.custom-switch .custom-control-input:disabled:checked~.custom-control-label::before {
    background-color: rgba(0,123,255,.5)
}
.handlerArea .kassierInformatie, .handlerArea .promoInformatie {
    max-height: 85%;
}
.handlerArea .kassieriInfomatie .btn, .handlerArea .promoInformatie .btn {
    font-size: 20px;
    font-weight: 300;
    text-align: center;
}
.menuItemArea {
    height: calc(100vh - 315px);
    max-width: 100%;
    margin: 0;
    overflow-y: scroll;
    max-height: 100%;
}
.menuArea ul.nav .nav-item {
    background-color: var(--main-color);
    border-radius: 5px;
}
.menuArea ul.nav .nav-item .active {
    background-color: var(--main-color) !important;
    border: 1px solid var(--main-color);
}

.menuArea ul.nav .nav-link {
    text-transform: uppercase;
    color: #fff;
}
.menuItemArea.container .tab-content {
    width: 100%;
}
.menuItemArea .card-title {
    font-weight: 300;
}
/* cart section */
.bestelHeader {
    height: 71px;
    margin: 0;
    max-width: 100%;
}
.bestelHeader .bestelHeaderInstellingen .btn {
    color: #fff;
    background-color: var(--main-color);
    font-size: .65rem;
    height: 100%;
}
.bestelTabHandler {
    background-color: #F4F5F6;
    max-width: 100%;
    margin: 0;
}
.bestelTabHandler nav {
    width: auto;
    flex-wrap: nowrap;
    overflow-x: scroll;
}
.bestelTabHandler nav::-webkit-scrollbar {
    width: 0;
    background: transparent;
}
.bestelTabHandler nav .nav-link {
    font-size: .8rem;
    font-weight: 300;
}
.bestelTabHandler nav .nav-link {
    border-radius: 0;
    margin-left: .1rem;
    margin-right: .1rem;
    background-color: grey;
    color: #fff;
    min-width: fit-content;
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.bestelTabHandler nav .nav-link.active {
    background-color: var(--main-color);
    color: #fff;
}
.bestelTabHandler nav .nav-link .remove-tab {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    min-width: 30px;
}

.bestelTabArea {
    max-width: 100%;
    height: calc(100vh - 470px);
    padding: 0 0.5rem;
    margin: 0.5rem 0;
}
.bestelTabArea > div {
    overflow-y: scroll;
    max-height: 100%;
    scrollbar-width: thin;
    scrollbar-color: rgba(0, 0, 0, 0.65) var(--main-color);
    width: 100%;
}
.bestelTabArea .bestelOrder {
    max-width: 100%;
    padding: .5rem 0;
    margin: 0;
}
.bestelTabArea .bestelOrder .bestelOrderDetails, .bestelTabArea .bestelOrder .bestelOrderQuantity, .bestelTabArea .bestelOrder .bestelOrderPrice, .bestelTabArea .bestelOrder .bestelOrderTitle, .bestelTabArea .bestelOrder .bestelOrderImg {
    padding: 0;
    margin: 0;
}
.bestelTabArea .bestelOrder .bestelOrderDetails {
    display: inline-flex;
    justify-content: space-between;
}
.bestelTabArea .bestelOrder .bestelOrderQuantity {
    max-width: 100%;
    display: flex;
    justify-content: center;
}
.bestelTabArea .bestelOrder .bestelOrderTitle {
    color: var(--main-color);
    font-size: 0.8rem;
    font-weight: bold;
    padding-left: .5rem;
    display: block;
    align-items: center;
}
.bestelTabArea .bestelOrder .bestelOrderQuantity .bestelOrderQuantityControl.trash {
    background-color: var(--main-color);
}
.bestelTabArea .bestelOrder .bestelOrderQuantity .bestelOrderQuantityControl {
    background-color: grey;
    color: #fff;
    text-align: center;
    padding: 10px;
    margin: 0 .6rem;
    border-radius: 5px;
    font-size: .8rem;
    display: flex;
    align-items: center;
}
.bestelTabArea .bestelOrder .bestelOrderQuantity input {
    max-width: 1.2rem;
    border: 1px solid var(--main-color);
    border-radius: 5px;
    color: darkslategrey;
    font-size: 0.8rem;
    text-align: center;
}
.bestelTabArea .bestelOrder .bestelOrderPrice {
    font-size: .8rem;
    font-weight: 300;
}

.klantArea .container .card .klantDetails {
    display: inline-flex;
    align-items: center;
    text-align: start;
}
.klantArea .container .card .klantIcon {
    color: var(--main-color);
    padding: 0 0.5rem;
}
.klantArea .container .card .klantGegevens {
    padding: 0 0.5rem;
}
.klantArea .container .card .klantGegevens p {
    font-size: 0.6rem;
    padding: 0;
    margin: 0;
    color: var(--main-color);
}
.klantArea .container .card .klantKoppeler .btn {
    font-size: .65rem;
    background-color: var(--main-color);
    color: #fff;
    padding: 0.5rem;
}
.priceCalculatorArea .container .card {
    color: var(--main-color);
    font-size: 0.65rem;
}
.priceCalculatorArea .container .card .priceCalculatorDivider {
    border-top: 1px dashed var(--main-color);
}
.priceCalculatorArea .container .card .totaal {
    font-size: 1.2rem;
}
.betaalArea .btn {
    background-color: var(--main-color);
    color: #fff;
    width: 100%;
    margin: auto;
    padding: 10px;
    margin-left: 15px;
    font-size: 1.5rem;
    text-transform: uppercase;
}

</style>