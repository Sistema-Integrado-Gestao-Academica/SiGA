var subscriptionTeachers = [];
var teacherPairTable;
$(document).ready(function(){
  $(function() {
    $('#define_teacher_pair_table').dataTable();
    $('#defined_teacher_pair_table').dataTable({
      language: {
        emptyTable: 'Nenhum professor adicionado para este candidato.'
      }
    });
    teacherPairTable = $('#defined_teacher_pair_table').DataTable();
  });

  $("#confirm_teacher_pair").click(function(event){
    event.preventDefault();

    // Must be a pair of teachers
    if(subscriptionTeachers.length == 2){

    }else{
      bootbox.alert({
        size: "small",
        message: "<p class='text-center'>É preciso definir uma dupla de professores para o candidato.</p>",
        backdrop: true
      });
    }
  });
});

function addTeacherToSubscription(event, teacherId, teacherName, subscriptionId){
  event.preventDefault();

  if(subscriptionTeachers.length <= 1){

    var subscriptionTeacher = {
      teacherId: teacherId,
      subscriptionId: subscriptionId
    };

    if(!teacherIsInPair(teacherId)){
      subscriptionTeachers.push(subscriptionTeacher);

      var removeBtnId = "remove_" + teacherId;
      var node = teacherPairTable.row
        .add([
          teacherName,
          "<a id='" + removeBtnId +"' class='btn btn-danger'><i class='fa fa-minus-square'></i></a>"
        ]).draw().node();

      $(node).css('color', 'red').animate({color: 'black'});

      // Registering listener to delete click event
      $("#"+removeBtnId).click(function(){
        removeTeacherFromPair(teacherId);
        teacherPairTable.row(node).remove().draw();
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

function teacherIsInPair(teacherId){
  var alreadyAdded = false;
  subscriptionTeachers.every(function(value, index){
    if(value.teacherId == teacherId){
      alreadyAdded = true;
      return false;
    }else{
      return true;
    }
  });
  return alreadyAdded;
}

function removeTeacherFromPair(teacherId){
  var indexToDelete;
  subscriptionTeachers.every(function(value, index){
    if(value.teacherId == teacherId){
      indexToDelete = index;
      return false;
    }else{
      return true;
    }
  });
  subscriptionTeachers.splice(indexToDelete, 1);
}