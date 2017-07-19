<?php
$m = new Mongo( 'mongodb://85.92.89.214:27017/' );
$data = 'https://dev-billing.tenthmatrix.co.uk/hit/get_customer_by_uuid.cgi?uuid=2E076D0C-2723-4275-8FC4-25D9079C5796';
$db = $m->selectDB( "test" );
$collection = $db->selectCollection( "customer" );
$contents = file_get_contents($data);

$usertimeline = json_decode($contents);
// echo $usertimeline[0]['uuid'];
 $customer_uuid =  $usertimeline->{'uuid'};
 $check_customer = $collection->findOne(array("uuid" => $customer_uuid));
 echo $check_customer;
if($check_customer){
echo 'yes';
}else{
echo 'insert';
}
// $collection->insert($usertimeline);
// $collection = $db->customers;
// $usertimeline = json_decode($document);

// $collection->insert($usertimeline);
// $contents = file_get_contents($data);
// print_r($contents);
// var_dump( $collection->find() );

// $("#submit").change(function(){
						// var dataString = 'url='+ivcnitem_uuid;	

						// $.ajax({
							// type: "POST",
							// url: "save.php",
							// data: dataString,
							// cache: false,
							// success: function(response)
							// {
							
							// alert('Added customer details in mongo');
							// }
						// });
					// });
					?>