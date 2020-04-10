@extends('beautymail::templates.widgets')

@section('content')

	@include('beautymail::templates.widgets.articleStart')

		<h4 class="secondary"><strong>{{$title}}</strong></h4>
		<p>Detail</p>
       <div style="width:100%; padding: 20px;">
	   <table>
		 <thead>
		 <th>Reference Produit</th>
		 <th>Quantite</th>
		 <th>Total</th>
		 <tr>
		 </tr>
		 </thead>
		 <tbody>
			 <tr>
				 <td>7788888888</td>
				 <td>4</td>
				 <td>127899 fr</td>
			 </tr>
		 </tbody>
		 </table>
	   </div>
		<p>{{$customer_details['name']}}</p>
		<p>{{$customer_details['phone']}}</p>
		<p>{{$customer_details['email']}}</p>
		<div class="card">
			<div class="card-header">
				Produit commande

			</div>
			<div class="card-body">
			  <p>{{$order_details['SKU']}}</p>
			  <p>{{$order_details['price']}}</p>
			  <p>{{$order_details['order_date']}}</p>
			</div>
		</div>

	@include('beautymail::templates.widgets.articleEnd')



@stop