
var div = document.getElementById('RemReport');
Continue_Fun();
function Continue_Fun() {
    console.log("Removed ",div);;
    div.removeChild(div.firstChild);
}

function StoreReport(){
    $(document).ready(function(){
        $("#RemReport").load('Report.php');
      });
}