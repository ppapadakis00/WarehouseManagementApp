
var div2 = document.getElementById('DeleteBasicRep');

Continue_FunTwo();
function Continue_FunTwo() {
    console.log("Removed ",div2);;
    div2.removeChild(div2.firstChild);
}

function AddReport(){
    $(document).ready(function(){
        $("#PlaceReport").load('reportTimeInner.php');
      });
}

function BasicReport(){
    $(document).ready(function(){
        $("#DeleteBasicRep").load('TimeReport.php');
      });
}