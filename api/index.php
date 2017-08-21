<?php
/**
* SO Order Dispatch API v 0.91
* Dispatch orders from SBs to Customers
* Copyright (C) 2017  Shaun Cheeseman
**/
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-type: application/json; charset=utf-8');

require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

// Todo: Test if accepting post variables work.

// Todo: Expand for multiple users

// Check if the user has the right to be here
// and validate the request before allowing access
$api_user_ip = $_SERVER['REMOTE_ADDR'];
$api_key = $_SERVER['HTTP_API_KEY'];
$api_user = $_SERVER['PHP_AUTH_USER'];
$api_user_pass = $_SERVER['PHP_AUTH_PW'];
$api_method = $_SERVER['HTTP_METHOD'];

/*if($api_key !== 'DAm5S3wVZG7B68J8H673VS1EaT15Oy05') echo 'Fel: API_KEY';
if($api_method !== 'new_order_dispatch') echo 'Fel: Method';
if($api_user_ip !== '90.229.249.192' && 
	$api_user_ip !== '166.62.40.42' && 
	$api_user_ip !== '83.249.24.59') echo 'Fel: Access denied';
if($api_user !== 'testuser') echo 'Fel: Username';
if($api_user_pass !== 'M3yJPzevUk5cumQj') echo 'Fel: Password';

*/

/*if($api_key === 'DAm5S3wVZG7B68J8H673VS1EaT15Oy05' && 
	$api_method === 'new_order_dispatch' && 
	$api_user === 'testuser' && 
	$api_user_pass === 'M3yJPzevUk5cumQj' && 
	$api_user_ip === '90.229.249.192' ||
	$api_user_ip === '166.62.40.42' ||
	$api_user_ip === '83.249.24.59') {*/

	global $woocommerce;

	$orders = array();
	$new_order = array();
	$servername = "localhost";
	$username = "salladon_devOne";
	$password = "e=mc2Sallad";
	$dbname = "salladon_line_dev";
	$key = '978511765978';
	

	// Create connection
	$conn = mysql_connect($servername, $username, $password);
	if (!$conn) {
		die('Something went wrong!');
	}

	// get orders to deliver
	$sql = "SELECT * FROM `wp_woocommerce_order_items` WHERE order_item_name LIKE '%Utkörning%' ORDER BY order_id DESC";

	mysql_select_db($dbname);

	$result = mysql_query($sql, $conn);

	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$posts = array();
		$distance = $row['order_item_name'];
		if(stristr($distance, 'Hemkörning')){
			$distance = str_replace('Hemkörning', 'Utkörning', $distance);
		}
		$order_id = (int)$row['order_id'];

		$order_item_id = (int)$row['order_item_id'];

		$woo_order = new WC_Order( (int)$order_id + 1 );

		$order_datum = $woo_order->order_date;

		 $order_time = strtotime($order_datum);
		 $updated_order_time = (int)$order_time + (60 * 10);
		 $pick_up_time = $updated_order_time;
		 $formatted_order_time = date('H:i', $updated_order_time);
		 $delivery_time = (int)$updated_order_time + (60 * 15);
		 $formatted_delivery_time = date('H:i', $delivery_time);
		 $formatted_order_date = date('Y-m-d', $order_time);


		$sql_digger = "SELECT * FROM `wp_woocommerce_order_itemmeta` WHERE order_item_id = " .$order_item_id;

		$digger = mysql_query($sql_digger, $conn);

		while ($row = mysql_fetch_array($digger, MYSQL_ASSOC)) {

			if($row['meta_key'] === 'cost'){
				$delivery_price = (int)$row['meta_value'];
			}

		}

		$true_order_id = (int)$order_id + 1;

		$sb_name = "SELECT * FROM `wp_yith_vendors_commissions` WHERE order_id = " . $true_order_id;

		$name_digger = mysql_query($sb_name, $conn);

		echo mysql_error();

		while ($row = mysql_fetch_array($name_digger, MYSQL_ASSOC)) {

				$vendor_id = (int)$row['vendor_id'];

		}

		$sb_name_ = "SELECT * FROM `os_sb_config` WHERE vendor_id = " . $vendor_id;

		$name_digger_ = mysql_query($sb_name_, $conn);

		while ($row = mysql_fetch_array($name_digger_, MYSQL_ASSOC)) {

				$this_sb_name = $row['name'];
				$this_sb_address = $row['address'];
				$this_sb_city = $row['city'];
				$this_sb_postcode = $row['postcode'];
				$this_sb_tel = $row['Tel'];

		}

		$get_date = "SELECT * FROM `wp_postmeta` WHERE post_id = ".$order_id;

		$date_digger = mysql_query($get_date, $conn);

		while ($row_ = mysql_fetch_array($date_digger, MYSQL_ASSOC)) {

			if ($row_['meta_key'] === '_shipping_first_name'){

				$kund_fname = $row_['meta_value'];
			}

			if ($row_['meta_key'] === '_shipping_last_name'){
				$kund_lname = $row_['meta_value'];
			}

			if ($row_['meta_key'] === '_shipping_address_1'){
				$kund_address = $row_['meta_value'];
			}

			if ($row_['meta_key'] === '_shipping_city'){
				$kund_city = $row_['meta_value'];
			}

			if ($row_['meta_key'] === '_shipping_postcode'){
				$kund_zip = $row_['meta_value'];
			}

			if ($row_['meta_key'] === '_billing_phone'){
				$kund_tel = $row_['meta_value'];
			}

			if ($row_['meta_key'] === '_billing_email'){
				$kund_email = $row_['meta_value'];
			}

			if ($row_['meta_key'] === '_customer_ip_address'){
				$kund_ip = $row_['meta_value'];
			}

			

			if ($row_['meta_key'] === '_customer_user'){
				$kund_id = (int)$row_['meta_value'];


				$get_dist = "SELECT * FROM `wp_usermeta` WHERE user_id = ".$kund_id;

				$date_diggers = mysql_query($get_dist, $conn);

				while ($rows = mysql_fetch_array($date_diggers, MYSQL_ASSOC)) {
					if ($rows['meta_key'] === '_shipping_distance'){
						$distanz = ceil(number_format($rows['meta_value'],1));
					}
				}

				//$fb_id = get_user_meta($kund_id, '_fb_user_id', true);

			}

		}

	$data = array('date' => date('Y-m-d',time()),
						'order-id' => (string)$order_id,
						'restaurant-id' => (string)$vendor_id,
						'restaurant-name' => $this_sb_name,
						'restaurant-street' => $this_sb_address,
						'restaurant-postal_code' => $this_sb_postcode,
						'restaurant-city' => $this_sb_city,
						'restaurant-phone' => $this_sb_tel,
						'time-created' => date('Y-m-d H:i:s',$order_time),
						'time-pickup' => date('H:i',$order_time + (60*15)),
						'time-delivery' => date('H:i',$order_time + (60*30)),
						'customer-id' => (string)$kund_id,
						'customer-name' => $kund_fname.' '.$kund_lname,
						'customer-street' => $kund_address,
						'customer-postal_code' => $kund_zip,
						'customer-city' => $kund_city,
						'customer-phone' => $kund_tel,
						'customer-floor' => null,
						'customer-apartment_number' => null,
						'customer-security_code' => null,
						'customer-message' => 'Ring min mobile. jag komma ner',
						'customer-email' => $kund_email,
						'kilometer' => (int)$distanz);

	$hash = hash_hmac('sha512', json_encode($data), $key, false);

  	$post['credentials'] = array('id'=> 10994,
  								'hash' => $hash,
  								'version' => '1.0.0',
  								'client' => 'SalladOnline:Worxmate:1.0',
  								'language' => 'sv',
  								'serverdata' => '{\'HTTP_HOST\':\'dev.sallad.online\'}',
  								'time' => (string)number_format(time(),4,'.',''));
  	$post['function'] = 'create';

  	$post['data'] = $data;

  	$content_length = strlen(json_encode($post));

  	$the_headers = array(
	'Content-Type: application/json; charset=utf-8',
    'Content-Length: ' . $content_length,
    'Module-Name: Task');
  	break;

  	//$orders[] =  $post;

}

mysql_close($conn);

// street name example: 
//$data_string = json_encode($post, JSON_UNESCAPED_UNICODE);
$data_string = json_encode($post); 


$ch = curl_init('http://api.pizzamate.se');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $the_headers);
$result = curl_exec($ch);
curl_close($ch);

//echo json_encode($orders));
//var_dump($data_string_);
//var_dump($data_string);
echo $result;

?>