<?php

    $GetTimeDB = mysqli_query($con,"SELECT * FROM reporttime");
    $rowTimeDB = mysqli_fetch_array($GetTimeDB);
    $LastDayReport = date('d',strtotime($rowTimeDB["time"]));
?>