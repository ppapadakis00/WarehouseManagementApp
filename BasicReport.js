$(document).ready(function(){
    $(document).click(function(event){
        var info = $(event.target);

        if(info.hasClass("sort")){

            let fromValue = $(".fromH").get(0).value;
            let toValue = $(".toH").get(0).value;
            let sortValue = $(".sortby").get(0).value;
    
            let valuesObj = {
                fromValue,
                toValue,
                sortValue
            };

            info=$(event.target).val();
            $.post("reportTimeInner.php",{
                fromh: valuesObj
            },function(data,status){
                $("#PlaceReport").html(data);
            });
        }else{
            return;
        }
    });
});