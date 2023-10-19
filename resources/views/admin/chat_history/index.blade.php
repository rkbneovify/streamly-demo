@extends('master')
@section('stylesheets')
<?php 
 //echo "<pre>";print_r($chat_history); exit;?>
<link href="{{ asset('dist/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@stop
@section('content')
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Chat List</title>
    <style type="text/css">
     
      .overlay{
        flex-direction: column;
      }

      .content-wrapper{
        min-height: 100% !important;
      }
      
</style>
  </head>
  <body class="hold-transition sidebar-mini">
    <div class="wrapper">
  
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Chat History</h1>
              </div>
              
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Chat history</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>

        <div class="col-6">
          @if($message = Session::get('success'))
            <div class="alert alert-success">
              <span>{{ $message }}</span>
            </div>
          @endif
        </div>
        <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card">
                    
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Sr No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Category</th>
                            <th>Describe Questions</th>
                            <th>Image</th>
                            <th>Date</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php //echo "<pre>";print_r($chat_history); exit;?>
                          <?php $i = 1;
                          
                          foreach($chat_history as $key=>$history){
                           ?>
                          <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php  if(isset($history->name)){
                                echo $history->name;
                            } ?></td>
                            <td><?php  if(isset($history->email)){
                                echo $history->email;
                            } ?></td>
                            
                            <td><?php  if(isset($history->category)){
                                echo $history->category;
                            } ?></td>
                            <td><?php  if(isset($history->describe_questions)){
                                echo $history->describe_questions;
                            } ?></td>
                            <td><?php if(isset($history->img_url) && $history->img_url !=''){ ?><img width="100px" src="<?php echo $history->img_url; ?>">
                            <?php }?>

                            </td>
                               <!-- Modal -->
                            <div class="modal fade" id="myModal" role="dialog">
                              
                              <div class="modal-dialog">
                              <div class="overlay" style="display: none;"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2"> Sending mail...</div></div>
                                <!-- Modal content-->
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalScrollableTitle">Question Reply Form</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    
                                  </div>
                                  <div class="modal-body">
                                   <form id="sendmemessage">
                                    <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="your_name" id="your_name">
                                    <input type="hidden" name="chat_id" id="chat_id">
                                    <div class="form-group">
                                      <label for="recipient-name" class="col-form-label">Recipient Mail id:</label>
                                      <input type="text" name="email" class="form-control" id="recipient-email" readonly>
                                    </div>
                                    <div class="form-group">
                                      <label for="recipient-name" class="col-form-label">Category:</label>
                                      <input type="text" name="category" class="form-control" id="category" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                      <label for="recipient-name" class="col-form-label">Describe Questions:</label>
                                      <input type="text" name="describe_questions" class="form-control" id="describe_questions">
                                    </div>
                                    <div class="form-group">
                                      <label for="message-text" class="col-form-label">Reply Answer:</label>
                                      <textarea class="form-control" name="reply_answer" id="reply_answer"></textarea>
                                    </div>
                                  
                                  </div>
                                   <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                      <button type="submit" class="btn btn-primary">Send message</button>
                                    </div>
                                    </form>
                                </div>
                                
                              </div>
                            </div>


                            <!-- Modal for chat answer history -->
                            <div class="modal fade" id="myModalChatAnswer" role="dialog">
                              
                              <div class="modal-dialog">
                              
                                <!-- Modal content-->
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="ChatAnswerModalTitle">Question Reply Form</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    
                                  </div>
                                  <div class="modal-body">
                                   
                                   <div id="list-group" class="list-group">
                                  
                                </div>
                                  
                                  </div>
                                   
                                    
                                </div>
                                
                              </div>
                            </div>
                              
                            <td><?php  if(isset($history->create_date)){
                                //echo $history->create_date;
                                echo date('Y-m-d',strtotime($history->create_date));
                            } ?></td>
                            <td><a href="javascript:void(0)" class="btn btn-xs btn-primary mb-1 btnSelect" data-id="<?php echo $history->id; ?>" data-img="<?php  if(isset($history->img_url)){
                                echo $history->img_url;
                            } ?>">Reply</a>
                            <a href="javascript:void(0)" class="btn btn-xs btn-primary ReplyAnswerHistory" data-id="<?php echo $history->id; ?>" data-img="<?php  if(isset($history->img_url)){
                                echo $history->img_url;
                            } ?>">Reply History</a>
                            </td>

                          </tr>
                          
                        <?php $i++; } ?>
                        </tbody>
                        
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->
      
      <!-- Footer -->
     

    </div>
    <!-- ./wrapper -->
  </body>
</html>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('dist/js/jquery.dataTables.min.js') }}" defer>
</script>
<script type="text/javascript">
    $(document).ready(function() {
    $('#example1').dataTable();

    $("#example1").on("click", ".ReplyAnswerHistory", function(){
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
       var id= $(this).data("id");
       //alert(id)

        $.ajax({
                url: "ajax-reply-data",
                type: "POST",
                data:{chat_id:id},
                success: function(data) {
                    console.log(data);
                    var html = data.html;
                    var que_title = data.que_title;
                    var error = data.error;
                    //alert(error);
                    
                    $('#myModalChatAnswer').modal({backdrop: 'static', keyboard: false});
                    $("#list-group").html(html);
                    $("#ChatAnswerModalTitle").html(que_title);
                    
          
                },
                error: function(data) {
                    console.log('Error:', data);
                    $("#list-group").html();
                   
                }
                
            });
       
       

     });
    
    $("#example1").on("click", ".btnSelect", function(){
 
     $('#myModal').modal({backdrop: 'static', keyboard: false});
     
       var currentRow=$(this).closest("tr");
       //var id=currentRow.find("td:eq(0)").html();
       var name=currentRow.find("td:eq(1)").html();
       var email=currentRow.find("td:eq(2)").html();
       //var ask_que=currentRow.find("td:eq(3)").html();
       var category=currentRow.find("td:eq(3)").html();
       var describe_questions=currentRow.find("td:eq(4)").html();
       var img_url = $(this).data('img');
       var id= $(this).data("id");

       $("#recipient-email").val(email);
       //$("#ask_questions").val(ask_que);
       $("#describe_questions").val(describe_questions);
       $("#category").val(category);
       $("#your_name").val(name);
       $("#chat_id").val(id);
       $('#reply_answer').val('');
       

     });

     $("#sendmemessage").submit(function(e){
       $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
     e.preventDefault();
     var formdata = $(this).serialize(); //
     $(".overlay").show();
     $("#sendmemessage").css('pointer-events','none');
     $(".modal-footer").css('pointer-events','none');
     $.ajax({
                url: "ajax-sendmail",
                type: "POST",
                data:formdata,
                success: function(data) {
                    console.log(data);
                    var error = data.error;
                    var msg = data.msg;
                    alert(msg);
                    $(".overlay").hide();
                    $("#sendmemessage").css('pointer-events','unset');
                    $(".modal-footer").css('pointer-events','unset');
                    $('#myModal').modal('toggle');

                },
                error: function(data) {
                    console.log('Error:', data);
                    $(".overlay").hide();
                    $("#sendmemessage").css('pointer-events','unset');
                    $(".modal-footer").css('pointer-events','unset');
                }
                
            });
      });
});
  </script> 