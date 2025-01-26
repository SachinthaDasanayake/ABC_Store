<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>

	<center>
		
		<h3>Customer name : {{$data->name}}</h3>

		<h3>Customer address : {{$data->rec_address}}</h3>

		<h3>phone : {{$data->phone}}</h3>

		<h2>Product title : {{$data->product->title}}</h2>

		<h2>Price : LKR {{$data->product->price}}</h2>

		<img height="250" width="300" src="products/{{$data->product->image}}">

	</center>

</body>
</html>