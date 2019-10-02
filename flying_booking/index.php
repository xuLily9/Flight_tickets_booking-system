<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Airline booking System</title>
</head>
<body>
<br>
<h1 >Airline booking System</h1>
<br>
<h2 style="font-weight: normal">Airline information</h2>
<?php
//database information.
$db_hostname = "mysql";
$db_database = "sgyxu27";
$db_username = "sgyxu27";
$db_password = "wr6822220";
$db_charset = "utf8mb4";
$dsn = "mysql:host=$db_hostname;dbname=$db_database;charset=$db_charset";
$opt = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
);

try{
    //creating pdo link
    $pdo = new PDO($dsn_str,$username,$passwd,$opt);
    $GLOBALS['error_info']="";
    $GLOBALS['order_price']="";

    if(isset($_POST['Success'])){
        echo "<h2>Congratulations！Your booking is successfull</h2>";
        echo "
            <form>
            <input type='submit' name='Back' value='BackIndex'>
            </form>
        ";
    }else if(check_destination()) {
        Show_destination_table();
        Plane_AirLine_Select();
        if(!empty($_POST['destination'])&&!empty($_POST['line'])&&!isset($_POST['submit_type'])) {
            show_price();
        }
        input_Email();
        submit_form();
    }
    //If there are not available seats, then just display the no available information.
    else {
        echo "Sorry there is no empty seat";
    }
    } catch (PDOException $e) {
    exit("PDO Error: ".$e->getMessage()."<br>");
}

$pdo = NULL;
//check whether there are empty seats
function check_destination() {
    $destination_num=0;
    $destination = $GLOBALS["pdo"]->prepare("SELECT capacity FROM plane WHERE capacity > 0");
    $destination->execute();
    foreach($destination as $row_once) {
        $destination_num += $row_once["capacity"];
    }
    return $destination_num;
}
//Show the information of airlines
function Show_destination_table()
{
    $Show_table = $GLOBALS["pdo"]->prepare("select destination,airline,capacity,base_price from plane");
    $success = $Show_table->execute();
    if($success) {
        $table_str="<table border='1' cellspacing='0'>";
        $table_str.= "<tr><th>destination</th><th>airline</th><th>capacity</th><th>base_price</th></tr>";
        $table_all = $Show_table->fetchAll();
        foreach ($table_all as $table_once){
            $table_str.="<tr>";
            $table_str.="<td>".$table_once["destination"]."</td>";
            $table_str.="<td>". $table_once["airline"]."</td>";
            $table_str.="<td align='center'>". $table_once["capacity"]."</td>";
            $table_str.="<td align='center'>". $table_once["base_price"]."</td>";
            $table_str.="</tr>";
        }

        $table_str.="</table>\n";
        echo $table_str;
    }
}
//show the selection menu
function Plane_AirLine_Select()
{
    $Plane="";
    $Plane.='<form id="myForm"  method="post"><fieldset><legend><span>1</span> Selection</legend>';
    $Plane.='<select name="destination" onchange="document.getElementById(\'myForm\').submit()"><option value="">Please select your destination</option>';
        $stmt = $GLOBALS["pdo"]->prepare("SELECT DISTINCT destination FROM plane WHERE capacity>0 GROUP BY destination ORDER BY destination ");
        $stmt->execute();
        $select_all=$stmt->fetchAll();
        foreach ($select_all as $select_once){
            if (isset($_POST['destination'])&&!isset($_POST['submit_type'])) {
                if($_POST['destination']===$select_once["destination"]){
                    $Plane.="<option value='".$select_once["destination"]."' selected='selected'>".$select_once["destination"]."</option>";
                }else{
                    $Plane.="<option value='".$select_once["destination"]."'>".$select_once["destination"]."</option>";
                }

            }else{
                $Plane.="<option value='".$select_once["destination"]."' >".$select_once["destination"]."</option>";
            }
        }
    $Plane.="</select>";
    $AirLine="";
    $AirLine.='<select name="line" onchange="document.getElementById(\'myForm\').submit()"><option value="">Please select your airline</option>';
    if(isset($_POST['destination'])&&!empty($_POST['destination'])) {
        $AirLine_pdo = $GLOBALS['pdo']->prepare("SELECT * FROM plane WHERE destination = :destination AND capacity > 0");
        $AirLine_pdo->execute(array(":destination" => $_POST['destination']));
        $AirLine_all = $AirLine_pdo->fetchAll();
        if($AirLine_all) {
            foreach ($AirLine_all as $AirLine_once) {
                if (isset($_POST['line']) && !isset($_POST['submit_type'])) {
                    if($_POST['line']===$AirLine_once['airline']){
                        $AirLine .= "<option value='".$AirLine_once['airline']."' selected='selected'>".$AirLine_once['airline']."</option>";
                    }else{
                        $AirLine .= "<option value='".$AirLine_once['airline']."'>".$AirLine_once['airline']."</option>";
                    }

                } else {
                    $AirLine .= "<option value='".$AirLine_once['airline']."'>".$AirLine_once['airline']."</option>";
                }
            }

        }else{
            show_Error("This airline has no empty seat, please chose another one");
        }
    }
    $AirLine .= "</select>";
        echo $Plane.$AirLine;
}

//show the price
function show_price(){
        $show_price = $GLOBALS['pdo']->prepare("SELECT base_price,capacity FROM plane WHERE destination = :course_title AND airline=:line AND capacity > 0");
        $show_price->execute(array(":course_title" => $_POST['destination'],":line"=>$_POST['line']));
        $price_result=$show_price->fetchAll();
        if($price_result) {
            $price = $price_result[0]["base_price"] - ($price_result[0]['capacity'] * 100);
            $price_str = "<p>Price：<span>$price</span></p><input type=\"hidden\" name=\"price\" value=\"$price\">";
            echo $price_str;
        }
}
//user enter the email
function input_Email() {
    $Email = (isset($_POST["email"])&&!empty($_POST["email"]))&&!isset($_POST['submit_type'])? $_POST["email"] : "";
    $input_Email="";
    $input_Email.="<legend><span class=\"number\">2</span>  Entry email</legend> <input type=\"text\" name=\"email\" value=\"$Email\" placeholder=\"email\" >
    <input type=\"submit\" name=\"submit_type\" value=\"submit\">";
    echo $input_Email;
}

//Once the user click submit button, check the information
function submit_form() {
    if  (isset($_POST['submit_type'])) {//check whether they click the submit button or not
        $result = check_params();
        if($result) {//if all three textfilds are not empty
            $destination = $_POST['destination'];
            $airline = $_POST['line'];
            $price = $_POST['price'];
            $email = $_POST['email'];
            if($order_price=check_And_order_price($destination, $airline)) {//check whether the selected airline has empty seat or not
                $GLOBALS['order_price']=$order_price;
                if(update_capacity("setDec", $destination, $airline)) {//the empty seat -1
                    $stmt = $GLOBALS['pdo']->prepare("INSERT INTO user VALUES( '$email','$destination','$airline','$price','$order_price')");//将预定信息存到数据库
                    $success = $stmt->execute();
                    if ($success) {//This booking is successful
                        reserve_success();
                    } else {//if booking is unsuccessful, the empty seat +1
                        update_capacity("setInc", $destination, $airline);
                    }
                }else{// The number of emptys seat minus one. fail！
                    show_Error("Booking unsuccessful");
                }
            }
            else {
                show_Error("This airline has no empty seat");
            }
       }
        else {
            //enter error information, show error message
            show_Error($GLOBALS['error_info']);
        }
    }
}

//Succussfully booking the course
function reserve_success() {
    $reserve_success="<br>Airline booking information.<table border='1' cellspacing='0'><tr>\n<th>Destination</th><th>Email address</th><th>Airline</th><th>Price</th></tr>";
    $reserve_success.="<tr><td>".$_POST["destination"]."</td><td>".$_POST["email"]."</td><td>".$_POST["line"]."</td><td>".$GLOBALS['order_price']."</td></tr></table>";
    $reserve_success.='<form  method="post"><input type="submit" name="Success" value="Success"></form>';
    echo $reserve_success;
}
//Show error message
function show_Error($str) {
    $error="<br>".$str."<form method='post'><input type='submit' name='Re-Airline' value='Re-Airline'></form>";
    echo $error;
}

function check_params(){
    if(empty($_POST['email'])) {
        $GLOBALS['error_info'] .= "Please enter your email address\n";
        return false;
    }else{
        $exp="/^[a-z'0-9]+([._-][a-z'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$/";
        if(!preg_match($exp, $_POST["email"])){
            $GLOBALS['error_info'] .= "Email address error!\n";
            return false;
        }
    }
    if(empty($_POST['destination'])) {
        $GLOBALS['error_info'] .= "Please select destination!\n";
        return false;
    }
    if(empty($_POST['line'])) {
        $GLOBALS['error_info'] .= "Please select airline!\n";
        return false;
    }
    return true;
}
//check and calculate the price of the airline
function check_And_order_price($destination, $airline) {
    $order_price=false;
    $check_price = $GLOBALS['pdo']->prepare("SELECT capacity,base_price FROM plane WHERE destination = :destination AND airline = :airline AND capacity > 0");
    $check_price->execute(array(":destination" => $destination, ":airline" => $airline));
    foreach($check_price as $once) {
        $order_price = $once['base_price']-($once['capacity']*100);
    }
    return $order_price;
}
//uodate the empty seat of the airlines
function update_capacity($type, $destination, $airline) {
    $operation = ($type == 'setInc')? "+ 1" : "- 1";
    $stmt = $GLOBALS['pdo']->prepare("UPDATE plane SET capacity = capacity $operation WHERE destination = :destination AND airline = :airline");
    $success = $stmt->execute(array(":destination" => $destination, ":airline" => $airline));
    return $success;
}

?>
</body>
</html>
