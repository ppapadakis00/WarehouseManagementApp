<html>
    <div class="Report">
        <?php
        include("Setup.php");
        ?>
        <script src="BasicReport.js" defer></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
        <div class="ReportWindow">
        <script src="continue2.js" defer></script>
            <div class="Title">Report</div>
            <div>
                <div>From Hour</div>
                <input class="productsList fromH" style="width:100px;" value="0" type="number" id="fromH">
            </div>
            <div>
                <div>To Hour</div>
                <input class="productsList toH" style="width:100px;" value="24" type="number" id="toH">
            </div>
            <div>Sort By</div>
            <select name="" id="" class="productsList sortby" style="width:150px; margin: 5px 0px;">
                <optgroup>
                    <option value="quantity">Quantity</option>
                    <option value="price">Income</option>
                </optgroup>
            </select>
            <div class="Continue-button sort" style="margin-top:10px;">Sort</div>
            <div id="PlaceReport" style="margin:10px 0px;">
                
            </div>
            <div class="Continue-button" onClick = Continue_FunTwo();>Exit</div>
        </div>
    </div>
</html>