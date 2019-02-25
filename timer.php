<?php
// header('Content-type:text/html;charset=gbk');
while(true){
	sleep(10);
	file_get_contents("http://127.0.0.1/chunxiaqiudongxiawucha/public/index.php/index/order/autotime");
	echo "success";
	echo "\n";
	echo "could not close";
	echo "\n";
}
?>