/**
 * Created by lyudmila on 07.10.16.
 */
$(document).ready(function() {
    $('.preview').click(function(){
        var commentZip="Author:icons8\n"+
                "Link:https://icons8.com\n"+
                "License:"+$("#linkToLicence").val()+'\n'+
                $('#licenceText').val()+'\n\n'+
                'Have comments? You are very welcome! ['+$('#linkToCollection').val()+']';
        $('#zipComment').html(commentZip);
    });
});