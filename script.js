const bar = document.getElementById('bar');
const close = document.getElementById('close');
const nav = document.getElementById('navbar');

if (bar) {
    bar.addEventListener('click', () => {
        nav.classList.add('active');
    })
}

if (close) {
    close.addEventListener('click', () => {
        nav.classList.remove('active');
    })
}
$(document).ready(function() {
    // Get the current location when the button is clicked
    $(document).on('click', '#get-location-btn', function(event) {
      event.preventDefault(); // Prevent the default form submission behavior
  
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          // Get the current latitude and longitude
          const lat = position.coords.latitude;
          const lon = position.coords.longitude;
  
          // Make a request to OpenStreetMap Nominatim API to get the address
          const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=jsonv2`;
          fetch(url)
            .then(response => response.json())
            .then(data => {
              // Autofill the form fields with the retrieved address
              $('input[name=name]').val('');
              $('input[name=email]').val('');
              $('input[name=address]').val(data.display_name);
              $('input[name=city]').val(data.address.city);
              $('input[name=state]').val(data.address.state);
              $('input[name=zip]').val(data.address.postcode);
            })
            .catch(error => console.log(error));
        });
      } else {
        console.log('Geolocation is not supported by this browser.');
      }
    });
  });
  