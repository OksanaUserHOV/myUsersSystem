  $( document ).ready(function(){

    function firstUpper(str){
      return str[0].toUpperCase() + str.slice(1);
    }

    function addRow(id, name_first, name_last, role, active_circle){
      var row = '<tr data-user-id="' + id +'">';
          row += '<td class="align-middle"><div class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top">';
          row += '<input type="checkbox" class="custom-control-input" id="item-' + id + '">';
          row += '<label class="custom-control-label" for="item-' + id + '"></label>';
          row += '</div></td>';
          row += '<td class="text-nowrap align-middle"><span class="first-name">'+ name_first +'</span> <span class="last-name">' + name_last + '</span></td>';
          row += '<td class="text-nowrap align-middle role" data-role="' + role + '"><span>' + firstUpper(role) + '</span></td>';
          row += '<td class="text-center align-middle"><i class="fa fa-circle ' + active_circle + '"></i></td>';
          row += '<td class="text-center align-middle"><div class="btn-group align-top">';
          row += '<button class="btn btn-sm btn-outline-secondary badge edit" type="button" data-toggle="modal" data-target="#user-form-modal">Edit</button>';
          row += '<button class="btn btn-sm btn-outline-secondary badge delete-user" type="button" data-toggle="modal" data-target="#modal-delete" data-delete-user-id="' + id + '"><i  class="fa fa-trash"></i></button>';
          row += '</div></td></tr>';
      return row;

    }

    $( "#all-items" ).click( function() {
      if($(this).prop("checked" ) == true ){
        $( "tbody :checkbox" ).prop( "checked", true );
      }
      else{
        $( "tbody :checkbox" ).prop( "checked", false );
      }      
    });

    $( "tbody :checkbox" ).click(function(){
        if($(this).prop("checked" ) == false ){
          $( "#all-items" ).prop( "checked", false );
        }          
    });   


    $('.checkbox').click(function(){
        $(this).toggleClass('active');  
        if($(this).hasClass('active')){

        $('#checkbox_status').attr('checked','checked');
         
        }else{
          $('#checkbox_status').removeAttr('checked');
        }
          
    }); 

    $('.btnOK').click( function() {

        var users = $("[id^='item']:checked");
     
        if (users.length === 0) {
          $('#modal-body-alert p').text('No users selected');
          $("#form-modal-alert").modal('show');
          return false;
        } 

        var f = $(this).closest(".form-row");
        var select_action = f.find(".select-action");
        var selectAction =   select_action.find('option:selected').val();

        if (selectAction == '-Please Select-') {
          $('#modal-body-alert p').text('No action is selected in the select box');
          $("#form-modal-alert").modal('show');
          return false;
        }

        var users_arr = [];
        users.each(function() {
          users_arr.push($(this).attr('id'));          
        }); 

        if (selectAction == 'Delete') {
          $('#btnDelete').attr('data-delete-id', users_arr);
          $("#modal-delete").modal('show');

          return false;
        }        
     
        $.ajax({
                  url: 'action.php',
                  method: 'POST',
                  data: {'action': 'update', 'users': users_arr, 'selectAction' : selectAction},
                  dataType: 'html',
                  success: function(data){
                    
                      data = JSON.parse(data);
 
                      if(data.status === false){
                          
                          $('#modal-delete').modal('hide');
                          $('#modal-body-alert p').text(data.error.message);
                          $("#form-modal-alert").modal('show');                          
                          return false;
                      }                      


                      if (selectAction == "Set active") {
                          data.ids.forEach(function(item){
                              tr  = $("tr[data-user-id=" + item +"]");
                              circle = tr.find('i.fa-circle');
                              circle.removeClass('not-active-circle');
                              circle.addClass('active-circle');
                              tr.find(':checkbox').prop( "checked", false );
                          });
                      }
                      if (selectAction == "Set not active") {
                          data.ids.forEach(function(item){
                              tr  = $("tr[data-user-id=" + item +"]");
                              circle = tr.find('i.fa-circle'); 
                              circle.removeClass('active-circle');
                              circle.addClass('not-active-circle'); 
                              tr.find(':checkbox').prop( "checked", false );      
                          });
                      } 

                    $('.select-action option:first-child').prop('selected', true); 
                    $('#modal-delete').modal('hide');
                  }
        });
       return false;
    });


    $(".addUser").click(function(){

        $("#UserModalLabel").text('Add user');
        $("#first-name").val('');
        $("#last-name").val('');
        $('.checkbox').removeClass('active'); 
        $('#checkbox_status').removeAttr('checked');
        $('#select-role option').each(function(){
            $(this).removeAttr('selected');
        });
        $('#btnSave').removeAttr('data-user-id');
        $('#btnSave').attr('data-action', 'add'); 
    });


      
    $(document).on('click', '.edit', function(e){
      
        var editRow = $(this).closest("tr");
        var editId = editRow.data('user-id');

        $('#btnSave').attr('data-user-id', editId);
        $('#btnSave').attr('data-action', 'edit');

        $("#UserModalLabel").text('Edit user');
        $("#first-name").val(editRow.find(".first-name").text());
        $("#last-name").val(editRow.find(".last-name").text());
        
        if(editRow.find('i').hasClass('active-circle')){
          $('.checkbox').addClass('active');
          $('#checkbox_status').attr('checked','checked');
        }
        if(editRow.find('i').hasClass('not-active-circle')){
          $('.checkbox').removeClass('active');
          $('#checkbox_status').removeAttr('checked');
        }
        var role = editRow.find('.role').data('role');

        $('#select-role option').each(function(){
                  if( $(this).val() == role ){
                       $(this).prop('selected', true);
                  }
                  else{
                    $(this).prop('selected', false);
                  }

        });        
   }); 

        $(document).on('click', '.delete-user', function(e){

          var deleteId = $(this).attr('data-delete-user-id');          
          $('#btnDelete').attr('data-delete-id', deleteId);
        });



        $("#btnSave").click(function(){

          var action = $(this).attr('data-action');
          if( action == 'edit'){
            var id = $(this).attr('data-user-id');
          }else{
            id = null;
          }
          var first_name = $('#first-name').val();
          var last_name = $('#last-name').val();

          if($(".checkbox").hasClass('active')){
              var status = true;  
          }else{
              status = false; 
          }

          var role = $('#select-role').val();

          $('.msg').removeClass('d-block').addClass('d.none');

          $.ajax({
          url: 'action.php',
          method: 'POST',
          data: {'action': action, 'id': id, 'first_name' : first_name, 'last_name' : last_name, 'status': status, 'role': role},
          dataType: 'html',
          success: function(data){

            data = JSON.parse(data);

            if (data.status === false) {
                $('.msg').addClass('d-block').html(data.error.message);
                return false;
            }             

            if (action == 'edit') {
                var tr  = $("tr[data-user-id=" + data.user.id +"]");
                
                tr.find('.first-name').text(data.user.name_first);
                tr.find('.last-name').text(data.user.name_last);
                tr.find('.role span').text( firstUpper(data.user.role) );            
                tr.find('.role').data('role', data.user.role);

                var circle = tr.find('i.fa-circle');

                if(data.user.status === true){
                  circle.removeClass('not-active-circle');
                  circle.addClass('active-circle');
                }else{
                  circle.removeClass('active-circle');
                  circle.addClass('not-active-circle');
                }
              }else{
                  if(data.user.status === true){
                    var active_circle =  'active-circle';
                  }else{
                    active_circle = 'not-active-circle';                  
                  }
                  $html = addRow(data.user.id, data.user.name_first, data.user.name_last, data.user.role, active_circle);
                  $('tbody').append($html);              

            }
            $('#user-form-modal').modal('hide');            
          }
          });
        });

        $("#btnDelete").click(function(){   
          
          var ids = $(this).attr('data-delete-id');
          $.ajax({
                    url: 'action.php',
                    method: 'POST',
                    data: {'action': 'delete', 'ids' : ids},
                    dataType: 'html',
                    success: function(data){
                      data = JSON.parse(data);                      
                      if(data.status === false){
                          
                          $('#modal-delete').modal('hide');
                          $('#modal-body-alert p').text(data.error.message);
                          $("#form-modal-alert").modal('show');                          
                          return false;
                      }
                      
                          data.ids.forEach(function(item){
                            $("tr[data-user-id~=" +  item + "]").remove();
                          });

                      $('#modal-delete').modal('hide');

                    }
          });
        });

        $('#user-form-modal').on('hidden.bs.modal', function () {
            $('.msg').removeClass('d-block').addClass('d.none');
        })


});
