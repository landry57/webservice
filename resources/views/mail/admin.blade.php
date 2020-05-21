@extends('beautymail::templates.ark')
<style>
 #tab th {
  height: 30px;
}
#tab th {
 text-transform: uppercase;
}
#tab ,#right{
  margin-top: 50px;
  width: 100%;
  margin-left:auto; 
    margin-right:auto;
}
#right td{
align-content: right;
text-align: right;
}
#right th{
text-align: left;
align-content: left;
}
#tab th {
  background-color: #f2f2f2;
  color: #000000;
}
#tab  tr:nth-child(even) {background-color: #fff;}
#fr{
  width: 100%;
  border: 1px solid #000000;
}
</style>
        
@section('content') 

    @include('beautymail::templates.ark.heading', [
		'heading' => 'Yéty Beautyhair',
		'level' => 'h1'
	])

    @include('beautymail::templates.ark.contentStart')

        <h4 class="secondary"><strong>La boutique Yéti Beautyhair</strong></h4>
        <p>vous propose les produits de qualité <a href="http://d1815c57.ngrok.io/yetiweb">ici</a></p>

    @include('beautymail::templates.ark.contentEnd')

  
  
  @include('beautymail::templates.ark.contentStart')


<p>Commande éffectuée par </p>
<h4 class="secondary"><strong>M/Mme {{$user['name']}},</strong></h4>

@include('beautymail::templates.ark.contentEnd')

    @include('beautymail::templates.ark.heading', [
		'heading' => 'Détails commande',
		'level' => 'h2'
	])

    @include('beautymail::templates.ark.contentStart')
         <div>
         <table id="tab">
         <tr>
           <th><strong>Détails du destinataire </strong></th>
           <th><strong>Adresse de livraison</strong></th>
         </tr>
          <tr>
            <td>
              <p>{{$user['name']}}</p>
              <p>{{$user['phone']}}</p>
            </td>
            <td>
               <p>{{$user['email']}}</p>
               <p>{{$customer_details['name']}}</p>
          </td>
          </tr>
          <tr>   
          </tr>
          
        </table>
         </div>
        <h4 class="secondary" style="margin-top: 20px;"><strong>Votre commande pour :</strong></h4>
       
       <div>
       <table id="tab">
         <tr>
           <th></th>
           <th><strong>PRODUIT</strong></th>
          <th><strong>quantité</strong></th>
           <th><strong>PRIX</strong></th>
         </tr>
         @foreach($order_details as $order)
          <tr>
            <td><img src="https://8e1926e9.ngrok.io/{{$order['img']}}" width="100" height="70"></td>
             <td>
              <p>{{$order['name']}}</p>
            </td>
            <td><p>{{$order['quantity']}}</p></td>
            <td> <p>FRCFA {{$order['solde']}}</p></td>
          </tr>
          @endforeach
          <tr>  
          </tr>
          <tfoot>
          <tr> <td> <p>date:{{date('d/m/Y')}}</p></td></tr>
         
          </tfoot>
        </table>
       </div>
       <div id="fr">
          <table id="right">
            <tr>
              <th>Frais de livraison</th>
              <td>FCFA {{$customer_details['frais']}}</td>
            </tr>
            <tr>
              <th>TOTAL</th>
              <td>FCFA {{$total_price}}</td>
            </tr>
          </table>
       </div>
       

    @include('beautymail::templates.ark.contentEnd')

@stop