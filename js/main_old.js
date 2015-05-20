
 $(document).ready(function(){
    $(".number").change(function() {
        var formfield=this;
        $(".number").attr("disabled", "disabled");   //Felder temporär deaktivieren
        imageurl=$(formfield).parent().find('img[class="mealimg"]').attr("src");
        fieldname=$(this).next().attr("name");      //der Name des hidden-Felds
        $(this).next().attr("name", "mealinfo");    //entsprechendes hidden Feld in mealinfo umbenennen - ändern
        var part=$(this).next().val().split("_");       
        val=part[0]+"_"+part[1]+"_"+$(this).val();  //die neue Anzahl ins value vom hidden Feld schreiben
        $(this).next().val(val);                    //hier dann eintragen
        $.post("ajax_scripts/order.php", $('input[name="mealinfo"]').serialize()).done(function(data) {
            data2=$.parseJSON(data);
            if(data2[0]===1) {  //error
                //alert("Bestellung konnte nicht gespeichert werden. Bitte gib keine Anzahlen gr&ouml;er als 1 ein. Dieses Feature kommt sp&auml;ter. Funktioniert es dann immer noch nicht, versuche dich neu einzuloggen");
                $(formfield).parent().find('img[class="mealimg"]').hide();
                $(formfield).parent().find('img[class="mealimg"]').attr("src", "images/cross.png").fadeIn(1000);
                $(formfield).next().val(part[0]+"_"+part[1]+"_"+part[2]); //Anzahl zurücksetzen im hidden Feld
                $(formfield).val(part[2]);  //Auch Textfeld auf alte Anzahl zurücksetzen

            }
            else {
                $(formfield).parent().find('img[class="mealimg"]').hide();
                $(formfield).parent().find('img[class="mealimg"]').attr("src", "images/tick.png").fadeIn(1000);
                if(data2[1]>=1) {
                    $(formfield).parent().addClass("tdborder");
                }
                else {
                    $(formfield).parent().removeClass("tdborder");
                }
            }
        });
        $(this).next().attr("name",fieldname);  //alten Namen wiederherstellen
        setTimeout(function(){
            $(".number").removeAttr("disabled");    //Alle Felder wieder aktivieren
            $(".outdated").attr("disabled", "disabled"); //vorherige Tage natürlich wieder deaktivieren
			if(data2[1]>0) {
				$(formfield).parent().parent().parent().find('input').attr("disabled", "disabled");
				$(formfield).removeAttr("disabled");
			}
            $(formfield).parent().find('img[class="mealimg"]').hide();
            $(formfield).parent().find('img[class="mealimg"]').attr("src", imageurl).fadeIn(1000);
            }, 3000);
        
   });
 	
}); 

document.getElementById('nonejs_button').style.display='none';