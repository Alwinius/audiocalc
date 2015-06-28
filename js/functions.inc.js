$(document).ready(function(){
    $('#savelink').focus();
    $('.notepad').trumbowyg({
        autogrow: true,
        fullscreenable: false,
        btns: ['btnGrp-design', '|', 'btnGrp-lists', '|', 'link', 'formatting']
    });
    $("#savelink").click(function() {
        addaudio();
    });
    $(document).keydown(function(e) {
        if (((e.keyCode || e.which) === 13) && !$(".notepad").is(":focus")) {
            // Enter key pressed
            addaudio();
        }
        else if (((e.keyCode || e.which) === 32) && !$(".notepad").is(":focus")) {
            // Space key pressed
            e.preventDefault();
            if(dash.getState()==="disabled") {
                dash.continuet();
            }
            else if (dash.getState()==="enabled") {
                dash.pause();
            }
        }
        else if (((e.keyCode || e.which) === 27) && !$(".notepad").is(":focus")) {
            // ESC key pressed
            e.preventDefault();
            if(dash.getState()==="disabled"|dash.getState()==="enabled") {
                dash.stop();
            }
        }
    });
    $("#name").change(function() {
       //Prüfen, ob Name valid ist
        if(!checkname()) {
            $(this).popover("show");
        }
        });
    $("#duration").change(function() {
        //Prüfen, ob Duration valid ist
        if(!checkduration()) {
            $(this).popover("show");
        }
        });
    $("body").on('click', '.finishlink', function() {
        parent=$(this).parent().parent();
        audioname=parent.children().first().text();
        $.post("backend/finish.php", {name: audioname.trim(), action: "finish"}, function(data) {
            if(data!=='0') {
                if(parent.children().first().next().next().text()==="00:00:00") {
                    wtime=1;
                }
                else {
                    a = parent.children().first().next().next().text().split(':');
                    wtime=(+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]); 
                }
                var b=parent.children().first().next().text().split(':');
                var pay=((+b[0]) * 60 * 60 + (+b[1]) * 60 + (+b[2]))*56/3600;
                var payh=pay/wtime*3600;
                parent.children().last().prev().html("<img src=\"icons/valid.png\" alt=\"yes\"/> (<img src=\"icons/coin_gold.png\"/> " + (Math.round(payh * 100) / 100).toFixed(2) + "€/h - "+data+")");
                parent.children().last().html('<a href="#" class="unfinishlink"><img src="icons/back.png" alt="Unfinish" title="Unfinish"/></a> <a href="#" class="deletelink"><img src="icons/del.png" alt="Delete from Database" title="Delete From Database"/></a>');
            }
            else
            {
                alert("Something went wrong.");
            }
        });
    });
    $("body").on('click', '.unfinishlink', function() {
        parent=$(this).parent().parent();
        audioname=parent.children().first().text();
        $.post("backend/finish.php", {name: audioname.trim(), action: "unfinish"}, function(data) {
            if(data==1) {
                parent.children().last().prev().html("<img src=\"icons/crossout.png\" alt=\"no\"/>");
                parent.children().last().html('<a href="#" class="finishlink"><img src="icons/forward.png" alt="Finish" title="Finish"/></a> <a href="#" class="editlink"><img src="icons/pensil.png" alt="Continue With This File" title="Continue With This File"/></a>');
            }
            else
            {
                alert("Something went wrong.");
            }
        });
    });
    $("body").on('click', '.deletelink', function() {
        parent=$(this).parent().parent();
        audioname=parent.children().first().text();
        //first ask
        $('#deletequestion').modal("show");
        $('#yesdel').click(function() {
           $('#deletequestion').modal("hide");
           $.post("backend/delete.php", {name: audioname.trim(), action: "delete"}, function(data) {
               if(data==1 || data==7) {
                   parent.remove();
               }
               else
               {
                   alert(data);
//                   alert('The entry can\'t get deleted.');
               }
           });
           return;
        });
        return;
    });
    $("body").on('click', '.editlink', function() {
        //first replace texts at the workplace
        audioname=$(this).parent().parent().children().first().text();
        worktime=$(this).parent().parent().children().first().next().next().text();
        dash.configure(audioname.trim(), worktime, this);
        dash.start();
    });
    $(".pauseb").click(function() {
        this.blur();
        if(dash.getState()==="disabled") {
            dash.continuet();
        }
        else {
            dash.pause();
        }
    });
    $(".stopb").click(function() {
        this.blur();
        dash.stop();
    });
    $('.trumbowyg').on('tbwchange', function() {
        note.change();
    });
    $('.trumbowyg').on('tbwpaste', function() {
        note.change();
    });
});

function checkname() {
   //Prüfen, ob Name valid ist
   namecheck=/^([a-z]|[A-Z]|[_]|[0-9])*$/i;
   if(namecheck.test($("#name").val())) {
       return true;
   }
   else {
       return false;
   }
}
function checkduration() {
   //Prüfen, ob Name valid ist
   namecheck=/^([0-9]){1,2}:[0-5][0-9]:[0-5][0-9]$/i;
   if(namecheck.test($("#duration").val())) {
       return true;
   }
   else {
       return false;
   }
}

function addaudio() {
    //Eintrag hinzufügen
    if(checkduration()) {
        if(checkname()) {
            //jetzt kommt Ajax ins Spiel
            $.post("backend/addaudio.php", {name: $("#name").val(), duration: $("#duration").val()}, function(data) {
                if(data==1) {
                    //jetzt neue Zeile in Tabelle einfügen und Felder cleanen
                    var duration=$("#duration").val().split(":");
                    var dur=("0"+duration[0]).slice(-2);
                    $("<tr><td>"+$("#name").val()+"</td><td>"+dur+":"+duration[1]+":"+duration[2]+"</td><td>00:00:00</td><td><img src=\"icons/crossout.png\" alt=\"no\"/></td><td>"+'<a href="#" class="finishlink"><img src="icons/forward.png" alt="Finish" title="Finish"/></a> <a href="#" class="editlink"><img src="icons/pensil.png" alt="Continue With This File" title="Continue With This File"/></a>'+"</td></tr>").insertBefore($("#lastrow"));
                    $("#name").val("");
                    $("#duration").val("");
                }
                else
                {
                    alert("Error writing data.");
                }
            });
        }
        else
        {
            $("#name").popover("show");
        }
    }
    else
    {
        $("#duration").popover("show");
    }
}

var dashboard = function() {
    var sincebreak;
    var time;   
    var entry;
    var state="empty";
    var interval;
    var name;
    var lastup=0;
    var that=this;
    this.configure = function(name, work, entry) {
        //here we configure the dashboard
        var b=work.split(":");
        var worktimes=+b[0] * 60 * 60 + +b[1] * 60 + +b[2];
        $('.activeaudio').text(name);
        $('.worktime').text(normalise(worktimes));
        this.name=name;
        this.time=worktimes;
        this.sincebreak=0;
        this.entry=entry;
        this.state="disabled";
    };
    this.start=function() {
        if(this.state==="disabled") {
            $('.dashboard .btn').removeClass('disabled');
            $('.hourglas').attr('src', 'icons/hourglas_running.gif');
            $(this.entry).parent().parent().addClass('success');
            this.state="enabled";
            //now we start the timer    
            this.interval=setInterval(function() {
                that.time++;
                that.sincebreak++;
                $('.worktime').text(normalise(that.time));
                $('.pausetime').text(normalise(that.sincebreak));
                if(that.sincebreak%10===0) {
                    updateserver();
                }
            }, 1000);
        }
    };
    this.stop=function() {
        this.sincebreak=0;
        clearInterval(this.interval);
        this.state="empty";
        $('.dashboard .btn').addClass('disabled');
        $(this.entry).parent().parent().removeClass('success');
        $('.hourglas').attr('src', 'icons/hourglas_stop.png');
        $('.pauseb').text('Pause');
        $(this.entry).parent().parent().children().first().next().next().text(normalise(this.time));
        this.time=0;
        this.name="None";
        updatescreen();
        window.scrollTo(0,0);
    };
    this.pause=function() {
        updateserver();
        this.sincebreak=0;
        clearInterval(this.interval);
        this.state="disabled";
        $(this.entry).parent().parent().children().first().next().next().text(normalise(this.time));
        $('.hourglas').attr('src', 'icons/hourglas_stop.png');
        $('.pauseb').text('Continue');

    };
    this.continuet=function() {
        $('.pauseb').text('Pause');
        this.start();
    };
    this.getState=function() {
        return this.state;
    };
    
    function normalise(secs) {
        hours=("0"+Math.floor(secs/3600)).slice(-2);
        mins=("0"+Math.floor((secs-hours*3600)/60)).slice(-2);
        secs2=("0"+Math.floor(secs-hours*3600-mins*60)).slice(-2);
        return hours+":"+mins+":"+secs2;
    }
    function updateserver() {
        $.post("backend/update.php", {name: that.name, action: "updatewtime", wtime: that.time}, function(data) {
            if(data==1) {
                that.lastup=that.time;
            }
        });
    }
    function updatescreen() {
        $('.worktime').text(normalise(that.time));
        $('.pausetime').text(normalise(that.sincebreak));
        $('.activeaudio').text(that.name);
    }
};
var notepad =function() {
    var uptodate;
    var timeout;
    var that=this;
    //get the content from the server
    $.post("backend/notepad.php", {action: "get"}, function(data) {
        if(data==="$noentry$") {
            $('.notepad').html('');
        }
        else if(data==="0") {
            alert("Something went wrong.");
        }
        else {
            $('.notepad').html(data);
        }
    });
    setInterval(function() {
        if(that.uptodate===1) {
            $('#notestatus').attr("src","icons/valid.png");
            $('#notestatus').attr("title","Saved");
            $('#notestatus').attr("alt","Saved");
        }
        else if (that.uptodate===0) {
            $('#notestatus').attr("src","icons/alert.png");
            $('#notestatus').attr("title","Unsaved Changes");
            $('#notestatus').attr("alt","Unsaved Changes");
        } 
        else if(that.uptodate===6) {
            $('#notestatus').attr("src","icons/error.png");
            $('#notestatus').attr("title","Error Saving");
            $('#notestatus').attr("alt","Error Saving");
        }
    }, 1000);
    
    this.change=function() {
        clearTimeout(this.timeout);
        this.uptodate=0;
        this.timeout=setTimeout(function() {
            update();
        }, 2000);
    };
    
    function update() {
        // send the content to the server
        $.post("backend/notepad.php", {action: "set", content: $('.notepad').html()}, function(data) {
            if(data==="1") {
                that.uptodate=1;
            }
            else {
                that.uptodate=6;
            }
        });
    }    
};