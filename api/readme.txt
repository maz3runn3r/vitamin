#############################################################################################

Request Method: POST

Web Service Type: REST

Authentication: Basic - username : testuser
                        password : M3yJPzevUk5cumQj
                       API_KEY : DAm5S3wVZG7B68J8H673VS1EaT15Oy05
                       Method : new_order_dispatch

Further Authorization: Users are authorized finally by IP address
Please submit the IP address where your request will be posted from
before attempting to use this service. Send to 
sc@webworldsolutions.se

Testing Server: https://dev.sallad.online/api

Testing: Stage 1 - Users must successfully POST custom headers and receive JSON response.
                 - Current response will produce all dispatch orders. When user has
                 - successfully processed the response and added order rows to their database,
                 - we will then add a 'FETCHED' status to all order rows requested by this
                 - service to prevent duplicate records in user's database and to
                 - decrease server load for both sender and receiver. After this requests 
                 - will only collect realtime orders.


#############################################################################################

For faster development and testing of SOAP & REST webservices we use the boomerang Chrome app. 
You can simply import the included .json file into boomerang to start testing right away.
If you want to test in this way the link below can be opened in the Chrome browser.
It's FREE.

https://chrome.google.com/webstore/detail/boomerang-soap-rest-clien/eipdnjedkpcnlmmdfdkgfpljanehloah

- Boomerang_logistics_request_client.json

#############################################################################################


If you use PHP we have also included a PHP example file that can be integrated into your code.
This file utilizes Javascript as it is very easy to work with JSON requests and responses.

- logistics_request_example.php


#############################################################################################