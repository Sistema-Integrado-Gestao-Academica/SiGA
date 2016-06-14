$(document).ready(function(){
    $("#confirm_invite_btn").click(function(event){
      var confirmed = confirm("Tem certeza que deseja enviar este convite ?");
      if(!confirmed){
        event.preventDefault();
      } 
    });
});