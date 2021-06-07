@extends('chuckcms::backend.layouts.base')

@section('title')
Nieuwe bestelling
@endsection

@section('add_record')
	
@endsection

@section('css')
	
@endsection

@section('scripts')
	
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
					<li class="breadcrumb-item"><a href="{{ route('dashboard.module.ecommerce.orders.index') }}">Bestellingen</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nieuwe Bestelling</li>
                </ol>
            </nav>
        </div>
    </div>
	<div class="row">
		<div class="col-sm-12">
			<div class="jumbotron jumbotron-fluid">
				<div class="container pl-5">
					<p class="lead">Details</p>
					
					<div class="mb-3">
						<a href="#" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
							pakbon
						</a>
					</div>
					
					<b>Status:</b>	<a href="#" type="button" data-target="#updateStatusModal" data-toggle="modal" class="badge badge-success">Betaald</a> <br><br>
					
					<b>Verzending:</b> Abc <br>
                    <b>Verzendtijd:</b> Def 
                    <br><br>


                    <b>Facturatie & Verzendadres: </b>
                      
                    <br>

                    <b>Naam</b>: W/e <br>
                    <b>E-mail</b>: W/e 
                    <br>
                    
                    <b>Bedrijfsnaam</b>: W/e <br>
                    <b>BTW-nummer</b>: W/e <br>

                    
				</div>
			</div>
		</div>
	</div>
    <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
        <div class="col-sm-12 my-3">
        	<div class="table-responsive">
        		<table class="table" style="width:100%">
        			<thead>
        				<tr>
							<th scope="col">Product</th>
							<th scope="col">Hvl</th>
							<th scope="col" class="pr-5">Prijs</th>
							<th scope="col">Totaal</th>
        				</tr>
        			</thead>
        			<tbody>
						<tr class="order_line" data-id="">
							<td class="v-align-middle semi-bold">
								X
							</td>
							<td class="v-align-middle">X</td>
							<td class="v-align-middle">X </td>
							<td class="v-align-middle semi-bold">X</td>
						</tr>

						<tr class="total_line">
							<td class="v-align-middle semi-bold"></td>
							<td class="v-align-middle"></td>
							<td class="v-align-middle">Subtotaal </td>
							<td class="v-align-middle semi-bold">XYZ</td>
						</tr>

						<tr class="total_line">
							<td class="v-align-middle semi-bold"></td>
							<td class="v-align-middle"></td>
							<td class="v-align-middle">Totaal </td>
							<td class="v-align-middle semi-bold">XYZ</td>
						</tr>
        			</tbody>
        		</table>
        	</div>
        </div>
    </div>
</div>
@endsection