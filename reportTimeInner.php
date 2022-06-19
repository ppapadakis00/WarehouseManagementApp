<?php
$from=1;
$to=1;
$sort='quantity';
if(isset($_POST['fromh'])){
    $to = $_POST['fromh']['toValue'];
    $sort = $_POST['fromh']['sortValue'];
    $from =$_POST['fromh']['fromValue'];
}
?>
<html>
    <script src="BasicReport.js" defer></script>
    <div id="PostVariables">
        
    </div>
    <style>
        .ReportTable{
            font-size: 20px;
            font-weight: normal;
            margin-top: 10px;
            border-radius: 5px;
            border: 3px solid black;
            box-shadow: 0px 3px 3px 1px rgba(0,0,0,.5);
        }

        .ReportTable tr th{
            background-color: rgb(239, 138, 255);
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        .ReportTable tr td{
            background-color: rgb(104, 104, 104);
            padding: 5px 10px;
            text-align: center;
        }
    </style>
        <?php
            include("Setup.php");
        ?>
                <table class="ReportTable">
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Income</th>
                        <th>Date</th>
                    </tr>
                    <?php
                    $sorderby = $sort;
                    $queryResults = mysqli_query($con,"SELECT * FROM success_order ORDER BY $sorderby DESC");
                    while($rowResults = mysqli_fetch_array($queryResults)){
                        $product_ID = $rowResults['product_id'];
                        $quantity = $rowResults['quantity'];
                        $price = $rowResults['price'];
                        $temphour = date('H',strtotime($rowResults["date"]));
                        $tempDate = date("Y-m-d H:i:s",strtotime($rowResults["date"]));
                        $tempDate2 = $rowResults["date"];
                        if($temphour >=$from && $temphour<=$to){
                        $queryPro = mysqli_query($con,"SELECT * FROM products WHERE products.id = $product_ID");
                        $rowPro = mysqli_fetch_array($queryPro);
                        $tempProductName = $rowPro['product_name'];
                        ?>
                    <tr>
                        <td><?=$tempProductName?></td>
                        <td><?=$quantity?></td>
                        <td><?=$price?>€</td>
                        <td><?=$tempDate2?></td>
                    </tr>
                    <?php }
                    } ?>
                </table>
</html>