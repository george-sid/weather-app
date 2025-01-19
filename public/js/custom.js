var searchBtn = document.querySelector('.search-btn')
var cityName;
searchBtn?.addEventListener('click', function () {
    cityName = document.querySelector('.city-input').value;
    fetch('/locations/ajax/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ name: cityName })
    })
    .then(response => response.json())
    .then(data => {
        if(data.error){
            alert(data.error)
        }else{
            console.log(data.id);
            // Call the function to fetch location weather data
            fetchLocationWeatherData(data.id);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});


function fetchLocationWeatherData(locationId) {
    fetch(`/weather-data/ajax/details/${locationId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Location Details:', data);
        // You can handle the details data here, e.g., display it on the page
    })
    .catch(error => {
        console.error('Error fetching location details:', error);
    });
}