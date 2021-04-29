<?php

//fetch.php

$api_url = "http://localhost/hotel-0420/api/api.php?action=fetch_all";

$client = curl_init($api_url);

curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($client);

$result = json_decode($response);

$output = '';

if(is_countable($result) > 0)
{
	foreach($result as $row)
	{
		$output .= '

		<tr class="shop-item">
			<td class="shop-item-image"><img src="' .$row->RoomPic. '" " height="150" " width="150"></td>
			<td class="shop-item-title">'.$row->RoomDes.'</td>
			<td class="shop-item-price">'.$row->RoomPrice.'</td>
			<td>'.$row->RoomNumber.'</td>
			<td><button type="button" name="edit" class="btn btn-warning btn-xs edit" id="'.$row->id.'">Edit</button></td>
			<td><button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$row->id.'">Delete</button></td>
			<td><button type="button" name="add" class="btn btn-primary shop-item-button" id="'.$row->id.'">>ADD TO CART</button></td>
		</tr>
		';
	}
}
else
{
	$output .= '
	<tr>
		<td colspan="4" align="center">No Data Found</td>
	</tr>
	';
}

echo $output;

?>