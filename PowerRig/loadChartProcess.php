<?php 

include "connection.php";

//monthly sold
$monthly_rs = Database::search("SELECT MONTH(`invoice_date`) AS `month`,
       SUM(`total_price`) AS `total_price_sum`
FROM `invoice`
INNER JOIN `invoice_item` ON invoice.invoice_id = invoice_item.invoice_invoice_id 
GROUP BY `month`");

$num = $monthly_rs->num_rows;

$months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
$month_num = [1,2,3,4,5,6,7,8,9,10,11,12];
$monthly_price = [0,0,0,0,0,0,0,0,0,0,0,0];

for ($i = 0; $i < $num; $i++) { 
    $monthly_data = $monthly_rs->fetch_assoc();

    $monthOfInvoice = $monthly_data["month"];
    
    $monthIndex = array_search($monthOfInvoice, $month_num);
    if ($monthIndex !== false) {
        $monthly_price[$monthIndex] += $monthly_data["total_price_sum"];
    }
}

//most sold
$sold_rs = Database::search("SELECT `title`,`user_email`,SUM(`product_qty`) AS `most_sold`
FROM `invoice`
INNER JOIN `invoice_item` ON invoice.invoice_id = invoice_item.invoice_invoice_id 
INNER JOIN `product` ON invoice_item.product_id = product.id 
INNER JOIN `sub_category` ON product.sub_category_id = sub_category.sub_category_id WHERE `category_id` = '1' 
GROUP BY `product_id` ORDER BY `most_sold` DESC LIMIT 5");

$labels = array();
$data = array();

for ($y = 0; $y < $sold_rs->num_rows; $y++) { 
    $sold_data = $sold_rs->fetch_assoc();

    $labels[] = $sold_data["title"]. " (".$sold_data["user_email"].")";
    $data[] = $sold_data["most_sold"];

}

$json = array();
$json["months"] = $months;
$json["monthly_price"] = $monthly_price;
$json["labels"] = $labels;
$json["data"] = $data;
echo json_encode($json);

?>
