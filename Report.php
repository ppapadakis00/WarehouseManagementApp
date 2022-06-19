<html>
    <div class="Report">
        <?php
        include("Setup.php");
        ?>
        <div class="ReportWindow">
            <div class="Title">Stores Report</div>
            <table class="ReportTable">
                <tr>
                    <th>Store</th>
                    <th>Total Products</th>
                    <th>Total Cost</th>
                </tr>
                <?php $ReportQuery = mysqli_query($con,"SELECT * FROM store_sales ORDER BY id DESC");
                while($ReportRow = mysqli_fetch_array($ReportQuery)){
                    $store_id = $ReportRow['store_id'];
                    $queryResults = mysqli_query($con,"SELECT * FROM stores WHERE stores.id = $store_id");
                    $row = mysqli_fetch_array($queryResults); ?>
                <tr>
                    <td><?=$row['store_name']?></td>
                    <td><?=$ReportRow['quantity']?></td>
                    <td><?=$ReportRow['price']?>€</td>
                </tr>
                <?php }?>
            </table>
            <div class="Continue-button" onClick = Continue_Fun();>Continue</div>
        </div>
        <script src="continue.js" defer></script>
    </div>
</html>