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
            //console.log(data.id);
            // Call the function to fetch location weather data
            fetchLocationWeatherData(data.id);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

// Fetch detailed weather data when clicking a location
function fetchLocationWeatherData(locationId) {
    fetch(`/weather-data/ajax/details/${locationId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.text())
    .then(data => {
        const weatherTable = document.querySelector('#weather-details-container');
        weatherTable.innerHTML = ''; 
        weatherTable.innerHTML = data;

        const toggleButtons = document.querySelectorAll('.toggleDetails');
    
        toggleButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const row = button.closest('tr');
                const childRow = row.nextElementSibling; 
                
                if (childRow.style.display === 'none') {
                    childRow.style.display = '';
                    button.textContent = '-';
                } else {
                    childRow.style.display = 'none';
                    button.textContent = '+';
                }
            });
        });
    })
    .catch(error => {
        console.error('Error fetching location details:', error);
    });
}

