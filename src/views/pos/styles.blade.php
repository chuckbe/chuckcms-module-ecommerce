<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
<style>
/* colors */
:root {
    /* primary color */
  --primary-color :  #000000;
  /* accent colors */
  --accent-color-1 : #EEEEEE;
  --accent-color-2 : rgba(255, 255, 255, var(--opacity2));   --opacity2 : 0.12;

  /* header color */
  --header-color : #000000;

  /* tab shadow */
  --shadow-color : #0700ff;

  /* text colors */
  --text-color-1: var(--primary-color);
  --text-color-2: #fff;

}
/* colors */
html, body, row {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: 'Lato', sans-serif;
}
.wrapper {
    max-height: 100vh;
    max-width: 100vw;
    overflow: hidden;
}
/* order or menu area/left side */
.main {
    height: 100vh;
    padding: 0;
}
.header {
    background-color: var(--header-color);
    color: #FFF;
    width: 100%;
    padding: 1rem 0;
    margin: 0;
}
.header .logo {
    /* max-height: calc(100% - 1.5rem);*/
    height: 40px;
    -webkit-filter: invert(100%); /* Safari/Chrome */
    filter: invert(100%);

}
.header .headerSearchArea form{
    margin: 0;
    display: flex;
}
.header .headerSearchArea form input{
    border-color: var(--primary-color);
    border-radius: 5px;
    font-size: 1rem;
    line-height: 1.5;
    border-width: 1px;
    padding: .375rem .75rem;
}
.header .headerSearchArea form .btn{
    margin-left: .5rem;
    margin-right: .5rem;
}
.header .headerSearchArea form .btn i{
   line-height: 1.5;
}
.header .headerSearchArea .btn{
    background-color: #f8f9fa;
}
/*.menuArea {
    height: 16vh;
    margin: 0;
}*/
.menuArea nav {
    padding: 2.5rem 1.5rem 0;
}
.menuArea ul.nav .nav-item {
    /* background-color: rgba(231, 40, 112, 0.65)!important; */
    background-color: var(--accent-color-1) !important;
    border-radius: 5px;
}
.menuArea ul.nav .nav-item .active {
    background-color: var(--primary-color) !important;
    color: var(--text-color-2);
}
.menuArea ul.nav .nav-link {
    text-transform: uppercase;
    color: var(--text-color-1);
}
.menuItemArea {
    /* height: 100%; */
    /* height: calc(100vh - 320px); */
    max-width: 100%;
    margin: 0;
}
.menuItemArea > div.container {
    overflow-y: scroll;
    max-height: 100%;
    scrollbar-width: thin;
    scrollbar-color: rgba(0, 0, 0, 0.65) var(--primary-color);
}
.menuItemArea > div.container::-webkit-scrollbar {
    width: 11px;
}
.menuItemArea > div.container::-webkit-scrollbar-track {
    background: #CECECE;
    border-radius: 6px;
}
.menuItemArea > div.container::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.65);
    border-radius: 6px;
/*    border: 3px solid var(--primary-color);*/
}
.menuItemArea > div.container .tab-content {
    width: 100%;
}
#navigationTab::-webkit-scrollbar {
  display: none;
}
/* placeholder shimmer */
@-webkit-keyframes placeHolderShimmer {
  0% {
    background-position: -468px 0;
  }
  100% {
    background-position: 468px 0;
  }
}

@keyframes placeHolderShimmer {
  0% {
    background-position: -468px 0;
  }
  100% {
    background-position: 468px 0;
  }
}
.animated-background, .preloadimage, .text-line {
  -webkit-animation-duration: 1.25s;
          animation-duration: 1.25s;
  -webkit-animation-fill-mode: forwards;
          animation-fill-mode: forwards;
  -webkit-animation-iteration-count: infinite;
          animation-iteration-count: infinite;
  -webkit-animation-name: placeHolderShimmer;
          animation-name: placeHolderShimmer;
  -webkit-animation-timing-function: linear;
          animation-timing-function: linear;
  background: #F6F6F6;
  background: -webkit-gradient(linear, left top, right top, color-stop(8%, #F6F6F6), color-stop(18%, #F0F0F0), color-stop(33%, #F6F6F6));
  background: linear-gradient(to right, #F6F6F6 8%, #F0F0F0 18%, #F6F6F6 33%);
  background-size: 800px 104px;
  height: 96px;
  position: relative;
}

.preloadimage {
  height: 52px;
  max-width: 100%;
  margin: 6px 0;
}

.text-line {
  height: 16px;
  width: 100%;
  margin: 4px 0;
}
/* placeholder shimmer ends */

.menuItemArea .card-title {
    font-weight: 300;
}
.menuItemArea .card-text {
    font-weight: 300;
}
.menuItemArea .tab-pane .dropdown {
    font-size: .7rem;
    padding-top: 1rem;
    text-align: center;
}
.menuItemArea .tab-pane .posproduct {
    cursor: pointer;
}
.menuItemArea .tab-pane .posproduct.unavailable{
    opacity: 0.5;
    pointer-events: none;
}
.handlerArea {
    /* height: 18vh; */
    margin: 0;
    margin-top: auto;
    position: sticky;
    bottom: 0;
    /* background-color: #fff; */
}
.handlerArea .kassieriInfomatie,
.handlerArea .promoInformatie {
    color: var(--text-color-1);
    max-height: 85%;
}
.handlerArea .kassieriInfomatie .card-title,
.handlerArea .promoInformatie .card-title {
    font-weight: 300;
    font-size: 16px;
}
.handlerArea .kassieriInfomatie .card-text {
     font-weight: bold;
     font-size: 1em;
}
.handlerArea .kassieriInfomatie .btn,
.handlerArea .promoInformatie .btn {
    background-color: var(--primary-color);
    color: var(--text-color-2);
    font-size: 20px;
    font-weight: 300;
    text-align: center;
}
/* bestel area or right side */
.bestelling {
    background-color: #FDFDFB;
    height: 100vh;
    padding: 0;
    margin: 0;
}
.bestelHeader {
    padding: 1rem 0;
    height: 71px;
    margin: 0;
    max-width: 100%;
}
.bestelHeader .bestelHeaderTitle {
    color: var(--text-color-1);
}
.bestelHeader .bestelHeaderInstellingen .btn {
    color: var(--text-color-1);
    background-color: var(--accent-color-2);
    font-size: .65rem;
    height: 100%;
}
.bestelTabHandler {
    background-color: #F4F5F6;
    max-width: 100%;
    margin: 0;
}
.bestelTabHandler nav {
    flex-wrap: nowrap;
    overflow-x: scroll;
}
.bestelTabHandler nav::-webkit-scrollbar  {
    display: none;
}
.bestelTabHandler nav .nav-link{
    border-radius: 0;
    margin-left: .1rem;
    margin-right: .1rem;
    background-color: var(--accent-color-2);
    color: var(--text-color-1);
    min-width: fit-content;
    position: relative;
    display: flex;
    justify-content: space-between;
}
.bestelTabHandler nav .nav-link .remove-tab {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    min-width: 30px;
}
.bestelTabHandler nav .nav-link.active {
    background-color: var(--primary-color);
    color: var(--text-color-2);
}
.bestelTabHandler nav .nav-link {
    font-size: .8rem;
    font-weight: 300;
}
.bestelTabArea {
    max-width: 100%;
    height: calc(100vh - 425px);;
    padding: 0 0.5rem;
    margin: 0.5rem 0;
}
.bestelTabArea > div {
    overflow-y: scroll;
    max-height: 100%;
    scrollbar-width: thin;
    scrollbar-color: rgba(0, 0, 0, 0.65) var(--primary-color);
    width: 100%;
}
.bestelTabArea > div::-webkit-scrollbar {
    width: 11px;
}
.bestelTabArea > div::-webkit-scrollbar-track {
    background: #CECECE;
}
.bestelTabArea > div::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.65);
    border-radius: 6px;
}

.bestelTabArea .bestelOrder{
    max-width: 100%;
    padding: .5rem 0;
    margin: 0;
}
.bestelTabArea .bestelOrder .bestelOrderDetails {
    display: inline-flex;
    justify-content: space-between;
}
.bestelTabArea .bestelOrder .bestelOrderDetails,
.bestelTabArea .bestelOrder .bestelOrderQuantity,
.bestelTabArea .bestelOrder .bestelOrderPrice,
.bestelTabArea .bestelOrder .bestelOrderTitle,
.bestelTabArea .bestelOrder .bestelOrderImg {
    padding: 0;
    margin: 0;
}

.bestelTabArea .bestelOrder .bestelOrderTitle {
    color: var(--text-color-1);
    font-size: 0.8rem;
    font-weight: bold;
    padding-left: .5rem;
    display: block;
    align-items: center;
}
.bestelTabArea .bestelOrder .bestelOrderImg img{
    border-radius: 5px;
}
.bestelTabArea .bestelOrder .bestelOrderQuantity{
    max-width: 100%;
    display: flex;
    justify-content: center;
}
.bestelTabArea .bestelOrder .bestelOrderQuantity input{
    max-width: 1.8rem;
    border: 1px solid var(--primary-color);
    border-radius: 5px;
    color: darkslategrey;
    font-size: 0.8rem;
    text-align: center;
}
.bestelTabArea .bestelOrder .bestelOrderQuantity .bestelOrderQuantityControl {
    background-color: #EEEEEE;
    text-align: center;
    padding: 10px 13px;
    margin: 0 .8rem;
    border-radius: 5px;
    font-size: .8rem;
    display: flex;
    align-items: center;
}
.bestelTabArea .bestelOrder .bestelOrderQuantity .bestelOrderQuantityControl.trash {
    background-color: #EEEEEE;
}
.bestelTabArea .bestelOrder .bestelOrderQuantity .bestelOrderQuantityControl.trash .deletebtn{
    color: var(--text-color-2);
}
.bestelTabArea .bestelOrder .bestelOrderQuantity .bestelOrderQuantityControl a,
.bestelTabArea .bestelOrder .bestelOrderPrice{
    color: var(--text-color-1);
}
.bestelTabArea .bestelOrder .bestelOrderPrice {
    font-size: .8rem;
    font-weight: 300;
}
.klantArea , .priceCalculatorArea {
    margin: 1rem 0;
}
.klantArea .container .card
.priceCalculatorArea .container .card {
    max-width: 95%;
    margin: auto;
}
.klantArea .container .card .card-title {
    font-size: .8rem;
}
.klantArea .container .card .klantDetails{
    display: inline-flex;
    align-items: center;
    text-align: start;
}
.klantArea .container .card .klantIcon {
    color: var(--text-color-1);
    padding: 0 0.5rem;
}
.klantArea .container .card .klantGegevens {
    padding: 0 0.5rem;
}
.klantArea .container .card .klantGegevens p{
    font-size: 0.6rem;
    padding: 0;
    margin: 0;
    color: var(--text-color-1);
}
.klantArea .container .card .klantKoppeler .btn {
    font-size: .65rem;
    background-color: var(--primary-color);
    color: var(--text-color-2);
    padding: 0.5rem;
}
.priceCalculatorArea .container .card {
    color: var(--text-color-1);
    font-size: 0.65rem;
}
.priceCalculatorArea .container .card .priceCalculatorDivider {
    border-top: 1px dashed var(--text-color-1);
}
.priceCalculatorArea .container .card .totaal {
    font-size: 1.2rem;
}
.betaalArea.row {
  bottom: 1rem;
  position: absolute;
  width: 100%!important;
}

.betaalArea .btn{
    background-color: var(--primary-color);
    color: var(--text-color-2);
    width: 100%;
    margin: auto;
    padding: 10px;
    margin-left: 15px;
    font-size: 1.5rem;
    text-transform: uppercase;
}
@media only screen and (max-width: 767px) {
  .wrapper{
      display: block !important;
      max-height: unset;
      overflow-x: hidden;
      overflow-y: visible;
  }
  .betaalArea.row{
      position: relative;
      bottom: unset;
  }
  .main, .bestelling{
    padding: 0;
    flex: 0 0 100%;
    max-width: 100%;
    /* height: unset; */
  }
  .menuItemArea > div.container .tab-content{
      max-height: 100vh;
    overflow-y: scroll;
  }
  .menuItemArea > div.container .tab-content> .tab-pane>.row{
      margin: 0 !important;
  }
  /*.menuItemArea > div.container .tab-content> .tab-pane>.row .cof_pos_product_card{
      flex: 0 0 100%;
    max-width: 100%;
  }*/
  .handlerArea{
      height: unset;
  }
  .handlerArea>.container>.row>div{
      flex: 0 0 100%;
    max-width: 100%;
  }
  .bestelling {
      padding: 1rem 0;
      max-width:720px;
      margin: 0 auto;
  }
  .bestelTabHandler nav{
    flex-wrap: wrap;
    flex-direction: row !important;
    overflow-x: scroll;
  }

}
/*@media (min-width: 576px) {
    .bestelling {
        max-width:540px;
        margin: 0 auto;
    }
}
@media (min-width: 992px) {
    .bestelling{
        max-width:960px;
        margin: 0 auto;
    }
}

@media (min-width: 1200px) {
    .bestelling {
        max-width:1140px;
        margin: 0 auto;
    }
}*/
</style>
