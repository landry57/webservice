@extends('beautymail::templates.widgets')

@section('content')
<style>
	div{
		margin: 20px auto;
		width: 100%;
	}
	h4{
		text-align: center;
	}
</style>
	@include('beautymail::templates.widgets.articleStart')

		<h4 class="secondary"><strong>{{$tab['subject']}}</strong></h4>
		

		  <div><p>{{$tab['message']}}</p></div>
         <footer>
			 <p>{{$tab['name']}}</p>
             <p>{{$tab['email']}}</p>
		 </footer>
	@include('beautymail::templates.widgets.articleEnd')


@stop