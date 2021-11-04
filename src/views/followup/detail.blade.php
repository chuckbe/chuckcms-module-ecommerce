<section class="section" id="cof_followupSection">
	<div class="container">
		<div class="row">
			@if($order->status == \Chuckbe\ChuckcmsModuleEcommerce\Models\Order::STATUS_AWAITING_TRANSFER && ChuckEcommerce::getSetting('integrations.banktransfer.active') == true)
			<div class="col order-awaiting-transfer">
				<h1>Bedankt {{ $order->surname }}</h1>
				<p>
					Bedankt voor uw bestelling. U heeft gekozen voor betaling per overschrijving. Om uw bestelling te vervolledigen vragen we u de volgende details te gebruiken. U krijgt deze informatie ook nog per e-mail. 
					<br><br>
					Gelieve het bedrag {{ ChuckEcommerce::formatPrice($order->final) }} over te maken naar: <br>
					<b>Naam: </b>{{ ChuckEcommerce::getSetting('integrations.banktransfer.name') }} <br>
					<b>IBAN: </b>{{ ChuckEcommerce::getSetting('integrations.banktransfer.iban') }} <br>
					<b>Bank: </b>{{ ChuckEcommerce::getSetting('integrations.banktransfer.bank') }} <br>
					<b>Mededeling: </b>WEB{{ $order->json['order_number'] }} 
					<br><br>
					Heeft u nog vragen? Dan kan u ons steeds contacteren! 
				</p>
			</div>
			@else
			<div class="col order-success d-none">
				<h1>Bedankt {{ $order->surname }}</h1>
				<p>Bedankt voor uw bestelling. Uw bestelling wordt zo klaargemaakt. U krijgt zodadelijk per e-mail nog een bevestiging. Heeft u nog vragen? Dan kan u ons steeds contacteren!</p>
			</div>
			<div class="col order-canceled d-none">
				<h1>Oops {{ $order->surname }}</h1>
				<p>Er is helaas iets misgegaan met uw betaling. Wilt u het nog eens proberen?</p>
				<p><a href="{{ route('module.ecommerce.checkout.pay', ['order_number' => $order->json['order_number']]) }}">Klik hier om het nog eens te proberen.</a></p>
				<p>Heeft u nog vragen over uw bestelling of betaling? Dan kan u ons steeds contacteren!</p>
			</div>
			@endif
		</div>
		<input type="hidden" name="order_number" value="{{ $order->json['order_number'] }}">
	</div>
</section>