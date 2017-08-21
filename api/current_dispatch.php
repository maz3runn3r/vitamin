<?php
/** 
 * App: Logistics Request Example
 * Purpose: Query database for the latest delivery orders
 * Filetype: PHP
 * Request Technique: JS / PHP / AJAX
 *
 * Request Method: POST
 *
 * Web Service Type: REST
 *
 * Authentication: Basic - username : 
 *                         password : 
 *                         API_KEY : 
 *                         Method : 
 *
 * Testing Server: https://sallad.online/api
 *
 * Testing: Stage 1 - User must successfully POST custom headers and receive JSON response.
 *                  - Current response will produce all dispatch orders. When user has
 *                  - successfully processed the response and added order rows to their database,
 *                  - we will then add a 'FETCHED' variable to all order rows fetched by this
 *                  - service to prevent duplicate records in the user's database and to
 *                  - decrease server load for sender and receiver. After this all requests 
 *                  - will only collect realtime orders.
**/

// Encrypt user auth
$credentials = base64_encode('testuser:M3yJPzevUk5cumQj');

?>
<pre id="json">Fetching...</pre>
<script type="text/javascript">
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        // Get the response of the request
        var response = JSON.parse(xhttp.responseText);
        // Development: Display the response on a webpage
        var json_object = JSON.stringify((response), null, 2);
        document.getElementById("json").innerHTML = json_object;
        // A function outside this script can now be used to process the json_object
        // e.g. insert_orders(json_object)
      }
    };
    // Start the request
    xhttp.open("POST", "https://dev.sallad.online/api", true);
    // Add custom headers for authentication
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.setRequestHeader("API_KEY", "DAm5S3wVZG7B68J8H673VS1EaT15Oy05");
    xhttp.setRequestHeader("Method", "new_order_dispatch");
    xhttp.setRequestHeader("Authorization", "Basic <?php echo $credentials; ?>");
    // Send the request
    xhttp.send();
</script>
