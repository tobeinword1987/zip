/**
 * Created by lyudmila on 07.10.16.
 */
var myCodeMirror;

window.onload = function() {
     myCodeMirror = CodeMirror.fromTextArea(document.getElementById('editTemplateText'), {
        lineNumbers: true,               // показывать номера строк
    });
};

$(document).ready(function() {

    $('.preview').click(function(){
        var commentZip="Author:icons8\n"+
                "Link:https://icons8.com\n"+
                "License:"+$("#linkToLicence").val()+'\n'+
                $('#licenceText').val()+'\n\n'+
                'Have comments? You are very welcome! ['+$('#linkToCollection').val()+']';
        $('#zipComment').html(commentZip);
    });

    $(".dropdown-menu li a").click(function(){
        $('#chooseTemplate').val($(this).text());
        $('#chooseTemplateHidden').val($(this).text());
        //inser text from file to textarea
        $.ajax({
            url: "/getTemplateText",
            data: "data="+$(this).text()
        }).done(function(msg) {
            myCodeMirror.getDoc().setValue(msg);
        });
    });
});
