var subscriptionTeachers = [];
var teacherPairTable;

$(document).ready(function(){

  var definedTeachersJson = $("#defined_teachers_json").val();
  var definedTeachers = JSON.parse(definedTeachersJson);

  $(function() {
    $('#define_teacher_pair_table').dataTable();
    $('#defined_teacher_pair_table').dataTable({
      language: {
        emptyTable: 'Nenhum professor adicionado para este candidato.'
      }
    });
    teacherPairTable = $('#defined_teacher_pair_table').DataTable();

    // Initialize the table with previous data
    definedTeachers.forEach(function(teacher){
      addTeacherToSubscription(null, parseInt(teacher.id), teacher.name );
    });
  });

  $("#confirm_teacher_pair").click(function(event){
    event.preventDefault();

    // Must be a pair of teachers
    if(subscriptionTeachers.length == 2){
      homologateSubscription();
    }else{
      bootbox.alert({
        size: "small",
        message: "<p class='text-center'>É preciso definir uma dupla de professores para o candidato.</p>",
        backdrop: true
      });
    }
  });
});

function homologateSubscription(){
  var siteUrl = $("#site_url").val();
  var subscriptionId = $("#subscriptionId").val();
  var url = siteUrl + '/selection_process/homolog/register/' + subscriptionId;

  $.post(
      url,
      {
        subscriptionTeachers: subscriptionTeachers
      },
      function(data){
        data = JSON.parse(data);
        if('error' in data){
          bootbox.alert({
            size: "small",
            message: data.message,
            backdrop: true
          });
        }else{
          // Redirect to given url
          window.location.href = siteUrl + data.redirectTo;
        }
      }
    );
}

function addTeacherToSubscription(event, teacherId, teacherName){
  if(event){
    event.preventDefault();
  }

  if(subscriptionTeachers.length <= 1){

    if(!subscriptionTeachers.includes(teacherId)){
      subscriptionTeachers.push(teacherId);

      var removeBtnId = "remove_" + teacherId;
      var node = teacherPairTable.row
        .add([
          teacherName,
          "<a id='" + removeBtnId +"' class='btn btn-danger'><i class='fa fa-minus-square'></i></a>"
        ]).draw().node();

      $(node).css('color', 'red').animate({color: 'black'});

      // Registering listener to delete click event
      $("#"+removeBtnId).click(function(){
        var removed = removeTeacherFromPair(teacherId);
        if(removed){
          teacherPairTable.row(node).remove().draw();
        }else{
          teacherPairTable.clear().draw();
        }
      });

    }else{
      bootbox.alert({
        size: "small",
        message: "<p class='text-center'>Professor(a) <b>" + teacherName + "</b> já adicionado(a)!</p>",
        backdrop: true
      });
    }
  }else{
    bootbox.alert({
      size: "small",
      message: "<p class='text-center'>Apenas 2 professores são permitidos.</p>",
      backdrop: true
    });
  }
}

function removeTeacherFromPair(teacherId){
  var index = subscriptionTeachers.indexOf(teacherId);
  if(index >= 0){
    subscriptionTeachers.splice(index, 1);
    return true;
  }else{
    subscriptionTeachers = [];
    return false;
  }
}