@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Create  Subscription pack</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Create Subscription pack</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">General</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="panel-body">
                        <!-- Display Validation Errors -->
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        
            <form method="POST" enctype="multipart/form-data" action="{{ url('admin/insertsubscriptionpack') }}">
              
              @if($errors->first('plan_id')) <p class="text-danger text-sm danger_text">Please refresh this page and try again.</p> @endif
                 
        
        {{ csrf_field() }}
            <div class="card-body">
              {{-- <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name">Name</label>
                
              <input type="text" name="name"    class="form-control"   >
                @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
              </div> --}}
              <div class="form-group">
                <label for="">Plan name: <span class="text-red text-md">*</span></label>
                <input type="text" class="form-control" value="{{old('plan_name')}}" name="plan_name" id="" placeholder="Enter Plan Name...">
                @if($errors->first('plan_name')) <p class="text-danger text-sm danger_text">{{ $errors->first('plan_name') }}</p> @endif
            </div>
              {{-- <div class="form-group{{ $errors->has('duration') ? ' has-error' : '' }}">
                <label for="duration">Duration(in months)</label>
                <input type="text" name="duration" class="form-control"  >
                
                @if ($errors->has('duration'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('duration') }}</strong>
                                    </span>
                                    @endif
              </div> --}}

              <div class="form-group">
                <label for="">billing_scheme:</label>
                <select name="billing_scheme" id="billing_scheme">
                    <option value="per_unit">per_unit</option>
                    <!--<option value="tiered" id="custom_tiered">tiered</option>-->
                    <option value="volume" id="custom_volume">volume</option>
                   </select>
                    <div class="tiered" style="display: none">
            
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     
                        <div class="well clearfix">
        <a class="btn btn-primary pull-right add-record" data-added="0"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add Row</a>
      </div>
      <table class="table table-bordered" id="tbl_posts">
        <thead>
          <tr> <td> </td>
            <th>#</th>
            <th>FIRST UNIT</th>
            <th>LAST UNIT</th>
            <th>PER UNIT($)</th>
            <th>FLAT FEE($)</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="tbl_posts_body">
          <tr id="rec-1">
          <th scope="row"><span class="tiertext">FOR THE FIRST</span></th>
            <td><span class="sn">1</span>.</td>
            <td><span class="firstunit">0</span></td>
            <td><input class="up_to" name="rows[0][up_to]" type="number" min="1" max="1000" value="1" /></td>
            <td><input class="unit_amount" name="rows[0][unit_amount]" type="number" min="1" value="1" step="any" required /></td>
            <td><input class="flat_amount" name="rows[0][flat_amount]" type="number" min="0" value="0" step="any" required  /></td>
            <td></td>
          </tr>
          <tr id="rec-2">
          <th scope="row"><span class="tiertext">FOR THE NEXT</span></th>
            <td><span class="sn">2</span>.</td>
            <td><span class="firstunit">2</span></td>
            <td><input class="up_to" name="rows[1][up_to]" type="number" value="1000" max="1000"   /></td>
            <td><input class="unit_amount" name="rows[1][unit_amount]" type="number" min="1" value="1" step="any" required  /></td>
            <td><input class="flat_amount" name="rows[1][flat_amount]" type="number" min="0"  value="0" step="any" required  /></td>
            <td></td>
          </tr>
          
        </tbody>
      </table>
    </div> 
  
  <div style="display:none;">
    <table id="sample_table">
      <tr id="">
      <th scope="row"><span class="tiertext"></span></th>
       <td><span class="sn"></span>.</td>
       <td><span class="firstunit"></span></td>
       <td><input class="up_to"  type="number"  max="1000"  /></td>
       <td><input class="unit_amount"  type="number" value="1" min="1" step="any" required  /></td>
            <td><input class="flat_amount"  value="0" type="number" min="0" step="any" required  /></td>
            <td><a class="btn btn-xs delete-record" data-id="0"><span>&#128465;&#65039;</span></a></td>
     </tr>
   </table>
 </div>
                  
            </div>
              <div class="form-group">
                <label for="">Interval:</label>
                <select name="plan_interval" id="plan_interval">
                    <option value="day">Daily</option>
                    <option value="month">Monthly</option>
                    <option value="year">Yearly</option>
                    <option value="week">Weekly</option>
                    <option value="3-month">Every 3 months</option>
                    <option value="6-month">Every 6 months</option>
                    <option value="custom" id="custom_interval">Custom</option>
                </select>
                    <div class="custom" style="display: none">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="text" name="interval_count" value="0" maxlength="3" class="" id="interval_count" style="width: 5%">
                        <select name="coustom_interval" id="custom_interval">
                            <option value="month" id="mnth">months</option>
                            <option value="week" id="week">weeks</option>
                            <option value="day" id="day">days</option>
                        </select>
                    </div>
            </div>
            <div class="form-group{{ $errors->has('product') ? ' has-error' : '' }}">
              <label for="product">Products coming from stripe dashboard</label>

              <select name="product" id="product" class="form-control">
                @foreach($Product as $id => $pack)
                <option value="{{ $pack->id }}">
                  {{ $pack->name }}
                </option>
                @endforeach
              </select>
              @if ($errors->has('product'))
              <span class="help-block">
                <strong>{{ $errors->first('product') }}</strong>
              </span>
              @endif
            </div>
              {{-- <div class="form-group{{ $errors->has('rate') ? ' has-error' : '' }}">
                <label for="rate">Rate($)</label>
                <input type="text" name="rate" class="form-control" >
                
                @if ($errors->has('rate'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('rate') }}</strong>
                                    </span>
                                    @endif
              </div> --}}


              <div class="form-group" id="plan_amount_div">
                <label for="">Amount: <span class="text-red text-md">*</span></label><span id="symbol"> $</span>
                <input type="text" class="form-control amount" value="{{old('plan_amount')}}" name="plan_amount" id="plan_amount" placeholder="5.25">
                @if($errors->first('plan_amount')) <p class="text-danger text-sm danger_text">{{ $errors->first('plan_amount') }}</p> @endif
            </div>
              <div class="form-group">
                <label for="">Trial period days: <span class="text-red text-md">*</span></label>
                <input type="text" class="form-control amount" value="{{old('trial_period_days')}}" name="trial_period_days" id="trial_period_days" placeholder="10">
                @if($errors->first('trial_period_days')) <p class="text-danger text-sm danger_text">{{ $errors->first('trial_period_days') }}</p> @endif
            </div>
            <div class="form-row" id="dynamic_field">
              
              <div class="col-md-6">
                  <div class='form-group cvc required'>
                      <label class='control-label'>Text 1</label>
                      <textarea name="text[text1]" class='form-control' rows="5" cols="20"></textarea>
                      <small class="help-block" style="display:none;"></small>
                  </div>
              </div><div class="col-md-6">
                  <div class='form-group zip required'>
                      <label class='control-label'>Text 2</label>
                      <textarea name="text[text2]" class='form-control' rows="5" cols="20"></textarea>
                      <small id="ziphelp" ></small>
                  </div>
              </div>
              
          </div>
          <div class="row">  <div class="col-12"><button type="button" name="add" id="add" class="btn btn-primary">Add More</button></div></div>
              
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
               
</div>
              
      </div>
      <div class="row">
        <div class="col-12">
          
        <button type="submit" class="btn btn-success">
          Create
                                    </button>
                                    <a href="{{ url('admin/subscriptionpack') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </div>
      </form>   
      
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection
  
@section('scripts')

<script>
$(document).ready(function(){ 
var i=1;
      var number_of_child_divs =  $('#dynamic_field').children('.col-md-6').length;
      var j=0;
    $('#add').click(function(){  
      j=i+ number_of_child_divs;
    i++;  
    
    $('#dynamic_field').append(`<div class="col-md-6" id="row`+i+`" ><div class="form-group cvc required"><label class="control-label">Text `+j+` </label>
        <textarea name="text[text`+j+`]" class="form-control" rows="5" cols="20"></textarea> <small class="help-block" style="display:none;"></small> </div>
      <div class="row"><button type="button" name="remove" id="`+i+`" class="btn btn-danger btn_remove">X</button></div></div>`);  
});  


$(document).on('click', '.btn_remove', function(){  
    var button_id = $(this).attr("id");   
    $('#row'+button_id+'').remove();  
}); 
$(document).on('change','#plan_interval',function(e){
  var interval = $('#plan_interval').val();
  console.log('interval',interval);
  if(interval == 'custom'){
      $("#interval_count").val(0);
      $('.custom').css('display','block');
  }
  else{
      $("#interval_count").val(0);
      $('.custom').css('display','none');
  }
}); 
$(document).on('change','#billing_scheme',function(e){
  var billing_scheme = $('#billing_scheme').val();
  // console.log('billing_scheme',billing_scheme);
  if(billing_scheme == 'tiered'||'volume'){
      $("#interval_count").val(0);
      $('.tiered').css('display','block');
      $('#plan_amount_div').css('display','none');
  }
   if(billing_scheme == 'per_unit'){
    // console.log("billingschecne",billing_scheme);
      $("#interval_count").val(0);
      $('.tiered').css('display','none');
      $('#plan_amount_div').css('display','block');

  }
});


$('#interval_count').keypress(function (e) {
    var regex = new RegExp("^[0-9]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }else{
        alert("Only number is allowed.");
    }
    e.preventDefault();
    return false;
});


$('#interval_count').on('blur',function () {
    if(this.value > 12 && this.value <= 52){
        $('#week').css('display','block');
        $('#week')
            .removeAttr('selected')
            .attr('selected', true);
        $('#mnth').css('display','none');
    }else if(this.value > 52 && this.value <= 365){
        $('#day')
            .removeAttr('selected')
            .attr('selected', true);
        $('#week').css('display','none');
    }else if(this.value <=12){

        $('#mnth').css('display','block');
        $('#week').css('display','block');

        $('#mnth')
            .removeAttr('selected')
            .attr('selected', true);
    }else{
        $('#interval_count').val(0);
        alert("Please input valid days.");
    }
});
jQuery(document).delegate('a.add-record', 'click', function(e) {
//   var arr = $('input[name="up_to[]"]').map(function () {
//     return parseInt(this.value); // $(this).val()
// }).get();
// arr.pop();//remove the last infinity
// console.log("arrLastUnit",arr);
// console.log("max",Math.max(...arr));
// var lastmaxvalue=Math.max(...arr);
     e.preventDefault();    
     var content = jQuery('#sample_table tr'),
     size = jQuery('#tbl_posts >tbody >tr').length ,
     element = null,    
     element = content.clone();
     element.attr('id', 'rec-'+size);
    //  element.attr('min', lastmaxvalue);
     element.find('.delete-record').attr('data-id', size);
     element.appendTo('#tbl_posts_body');
     element.find('.sn').html(size);
     element.find('.tiertext').html('FOR THE NEXT');
     element.find('.up_to').attr('name', 'rows['+size+'][up_to]');
     element.find('.unit_amount').attr('name', 'rows['+size+'][unit_amount]');
     element.find('.flat_amount').attr('name', 'rows['+size+'][flat_amount]');
     
    //  console.log('lastunitinput',$('tr#rec-'+size-1+'td input.up_to'));
    var value = parseInt(size); 
   var vals = (size-1);
    //  console.log('lastunitinputid','tr#rec-'+vals);
    //  console.log('lastunitinputval',$('tr#rec-'+vals+' '+'td input.up_to').val());
    $('tr#rec-'+size+' '+'td input.up_to').val(1000);	
     $('tr#rec-'+size+' '+'td input.up_to').prop('min',parseInt($('tr#rec-'+vals+' '+'td input.up_to').val()));
     if($('tr#rec-'+vals+' '+'td input.up_to').val()=='1000'){
       console.log('infinity is present');
      var v= (parseInt($('tr#rec-'+vals+' '+'td span.firstunit').text())+1);
      $('tr#rec-'+vals+' '+'td input.up_to').val(v);
      element.find('input.up_to').prop('min',parseInt($('tr#rec-'+vals+' '+'td input.up_to').val()));

   element.find('.firstunit').html(parseInt($('tr#rec-'+vals+' '+'td input.up_to').val())+1);
     }else{
      // $('tr#rec-'+vals+' '+'td input.up_to').val()
      element.find('.firstunit').html(parseInt($('tr#rec-'+vals+' '+'td input.up_to').val())+1);
      element.find('input.up_to').prop('min',parseInt($('tr#rec-'+vals+' '+'td input.up_to').val()));
     }
     
    //  console.log('firstunit',element.find('.firstunit:last').text());
   });
    jQuery(document).delegate('a.delete-record', 'click', function(e) {
     e.preventDefault();    
     var didConfirm = confirm("Are you sure You want to delete");
     if (didConfirm == true) {
      var id = jQuery(this).attr('data-id');
      var targetDiv = jQuery(this).attr('targetDiv');
      jQuery('#rec-' + id).remove();
      
    //regnerate index number on table
    $('#tbl_posts_body tr').each(function(index){
      if(index==0){
        $(this).find('th span.tiertext').html('FOR THE FIRST');
        $(this).find('td span.firstunit').html('0');
      }else{
        $(this).find('th span.tiertext').html('FOR THE NEXT');
        // $(this).prev('.firstunit').html('0');
      }
		$(this).find('span.sn').html(index+1);
    });
    return true;
  } else {
    return false;
  }
});

$(document).on('change', 'input.up_to', function() {
  
  var _this = $(this);	
  // if($(this).val().length==$(this).attr("maxlength")){
            // $(this).next().focus();
            //  var xthis = this;
            var inputvalue = parseInt(_this.val()); 
   var valsfirst = (inputvalue+1);
   var _closet=_this.closest('tr').next();
   _closet.find('span.firstunit').html( valsfirst);
   _closet.find('input.up_to').prop('min',inputvalue);
            // console.log('focusval',$(this).next());
        // }
        
  var min = parseInt(_this.attr('min')); // if min attribute is not defined, 1 is default	
  var max = parseInt(_this.attr('max')); // if max attribute is not defined, 100 is default	
//  console.log("minn",min);	
//  console.log("maxx",max);	
//  console.log("valll",inputvalue);	
//  console.log('val',_this.val());
   if (inputvalue < min){	
    _this.val(min+2);
    
   _closet.find('span.firstunit').html( min+3);
   _closet.find('input.up_to').prop('min',min+2);
    }	
  if (inputvalue > max){	
    _this.val(max);}

    });




}); 


</script>
@stop
