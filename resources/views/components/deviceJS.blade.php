jQuery('.remove-device').on('click', function(){
				const id = $(this).data('id');
				const devicename = $(this).data('devicename');
				const accesstoken = $(this).data('accesstoken');
				$('#accesstoken').val(accesstoken); 
			 $('#devicename').html(devicename); 
				$('#deleteFormDevice').attr('action', '/user/sessiondestroy/'+ id );
				jQuery('#removeDevice').modal('show');
			});
jQuery('.add-device-name').on('click', function(){
  const id = $(this).data('id');
  const type = $(this).data('type');
  const accesstoken = $(this).data('accesstoken');
  $('#accesstoken').val(accesstoken);
  $('#device_type').val(type);
  $('#DeviceNameUpdate').attr('action', '/user/personaldevicename/'+ id );
  jQuery('#changeDeviceName').modal('show');
});
@if ($errors->has('devicename'))
console.log("it has devicename error");
const id = {{  session()->get( 'deviceidseesion' )}};
console.log("gotid",id);
				$('#DeviceNameUpdate').attr('action', '/user/personaldevicename/'+ id );
$('#changeDeviceName').modal('show');

@endif
		
// Check all 
$('#checkall').click(function(){
      if($(this).is(':checked')){
         $('.update_check').prop('checked', true);
      }else{
         $('.update_check').prop('checked', false);
      }
   });
		$(document).on('click', '.update_record', function(){
        var id = [];var status;
        
        if(confirm("Are you sure you want to Update this data?"))
        {
            $('.update_check:checked').each(function(){
                id.push($(this).val());
            });
            console.log("id",id); 
            if(id.length > 0)
            {   
              
              status= this.id;
                $.ajax({
                    url:"{{url('admin/multisessiondestroy') }}",
                    headers: {'x-csrf-token': '{{ csrf_token() }}'},
                    method:"post",
                    data:{id:id,status:status},
                    success:function(data)
                    { 
                      console.log(data);
                        alert(data.message);
                        // console.log(data.message);
                        // console.log('data',data);
                        $.each(id, function( index, value ) {
                         // alert( index + ": " + value );
                          $('#'+value+'').fadeTo(400, 0, function () { 
                           $(this).remove();
                            });
                       });
                    },
          error: function (xhr, status, error) {
            alert(xhr.responseJSON.message);
            console.log(xhr.responseJSON);
          //   $.each(xhr.responseJSON.errors, function (key, item) 
          // {
          //   $("#errors").append("<li class='alert alert-danger'>"+item+"</li>")
          // });
          }
                });
            }
            else
            {
                alert("Please select atleast one checkbox");
            }
        }
    });

	$('.TDjQOc > div.dotmenu.dotmenu1.dotmenu2').click(function(){
$(this).toggleClass( "iWO5td" );
$(this).next().toggleClass( "is-visible" );
    //   console.log($(this));
   });

