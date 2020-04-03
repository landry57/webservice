@extends('beautymail::templates.widgets')

@section('content')

	@include('beautymail::templates.widgets.articleStart')

		<h4 class="secondary"><strong>Hello World</strong></h4>
		<p>This is a test</p>
		    <div class="card">
			<div class="card-header">
			je suis le header
			</div>
			<div class="card-body">
			je suis le <body>
			</body>
			<div class="card-footer">
			je suis le footer
			</div>
			</div>
			</div>

	@include('beautymail::templates.widgets.articleEnd')



@stop