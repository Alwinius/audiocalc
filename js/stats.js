$(document).ready(function(){
        $.post("backend/getbymonth.php", {"action":"getmonths"}, function(data) {
            if(data!=="0" && data!=="[]") {
                //that's the list
                var months=$.parseJSON(data);
                $('.monthsel option').remove();
                for (index = 0; index < months.length; index++) {
                    if(index<(months.length)) {
                        $('.monthsel').append('<option selected="selected">'+months[index]+"</option>");
                    }
                    else {
                        $('.monthsel').append("<option>"+months[index]+"</option>");
                    }
                //get the first data now in the table
                }
                update(months[months.length-1]);
            }
            else {
                $('.audiolist tbody').append("<tr><td colspan=\"6\" style=\"text-align: center;\">No finished audios yet. Go back to work!</td></tr>");
            }
        });
    
    function update(month) {
        $.post("backend/getbymonth.php", {"action":"getmonth", "month":month}, function(data2) {
            var entries=$.parseJSON(data2);
            var total=0;
            var totalwtime=0;
            $('.audiolist tbody').children().remove();
            for (index=0; index<entries.length; index++) {
                var b=entries[index]["duration"].split(':');
                var pay=((+b[0]) * 60 * 60 + (+b[1]) * 60 + (+b[2]))*56/3600;
                var a = entries[index]["wduration"].split(':');
                var wtime=(+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
                totalwtime+=wtime;
                var payh=pay/wtime*3600;
                total+=pay;
                $('.audiolist tbody').append("<tr><td>"+entries[index]["name"]+"</td><td>"+entries[index]["duration"]+"</td><td>"+entries[index]["wduration"]+"</td><td><img src=\"icons/coin_gold.png\"/>/<img src=\"icons/alarmclock.png\"/> "+(Math.round(payh * 100) / 100).toFixed(2)
                        +"€</td><td><img src=\"icons/coin_gold.png\"/> "+(Math.round(pay * 100) / 100).toFixed(2)+"€</td><td>"+entries[index]["created"]+"</td></tr>");
            }
            total=(Math.round(total * 100) / 100).toFixed(2);
            totalwtime=(Math.round(totalwtime/3600 * 100) / 100).toFixed(2);
            $('.total').text(total+"€");
            $('.totalhrs').text(totalwtime+"h");
            $('.avgrate').text((Math.round(total/totalwtime * 100) / 100).toFixed(2)+"€/h");
        });  
    }
    $('.monthsel').on('change', function() {        
        update($(this).find(":selected").val());
    });
    
    
});