@extends('layouts.app')

@section('content')
<h1>Weather Dashboard</h1>
<div class="container">
	<div class="weather-input">
		<h3>Enter a City Name</h3>
		<input class="city-input" type="text" name="city" placeholder="E.g., New York, London, Tokyo">
		<button class="search-btn">Search</button>
	</div>
	<div class="weather-data">
		
	</div>
</div>
@endsection