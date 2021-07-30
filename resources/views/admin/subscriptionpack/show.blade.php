<?php 
use Laravel\Cashier\Cashier;?>
@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>View Subscription Plan</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">View Subscription Plan</li>
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
                        
                        <form method="POST" enctype="multipart/form-data" action="{{ url('admin/insertquotation') }}">
                      
        
        {{ csrf_field() }}
            <div class="card-body">
              <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name">Name</label>
                
              <input type="text" name="name" disabled   class="form-control"  disabled value={{ $plan->nickname }}>
                @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
              </div>
              
              <div class="form-group{{ $errors->has('duration') ? ' has-error' : '' }}">
                <label for="duration">Duration</label>
                <input type="text" name="duration" class="form-control" disabled value="{{ $plan->interval_count  }} {{$plan->interval }}">
                
                @if ($errors->has('duration'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('duration') }}</strong>
                                    </span>
                                    @endif
              </div>

    @if (!empty($plan->tiers))
    <div class="form-group {{ $errors->has('created') ? ' has-error' : '' }}">
              
              <label for="created">Tier</label>
              
   @component('components.tiertable',['singlePlansDetail' => $plan,'type'=>'tiered'])
                            @endcomponent
 </div>
@elseif (!empty($plan->amount))
<div class="form-group{{ $errors->has('rate') ? ' has-error' : '' }}">
                <label for="rate">Rate($)</label>
                <input type="text" name="rate" class="form-control" disabled value="{{Cashier::formatAmount($plan->amount??'0', $plan->currency??'USD')}} ">
                
                @if ($errors->has('rate'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('rate') }}</strong>
                                    </span>
                                    @endif
              </div>
@else
unknown
@endif
    


              
              <div class="form-group {{ $errors->has('created') ? ' has-error' : '' }}">
              
                <label for="created">Created on</label>
                 <input type="text" name="created" placehoder="Start Date" class="form-control" disabled value={{date('m/d/Y H:i:s',$plan->created)}} />
                 <!-- <input type="text" name="created" placehoder="Start Date" class="form-control" id="created"/> -->
                @if ($errors->has('created'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('created') }}</strong>
                                    </span>
                                    @endif
              </div>

              <div class="form-row">
                <div class="col-md-6">
                    <div class='form-group cvc required'>
                        <label class='control-label'>Text 1</label>
                        <textarea name="text1" class='form-control' rows="5" cols="20" disabled>{{$plan->metadata->text1}}</textarea>
                        <small class="help-block" style="display:none;"></small>
                    </div>
                </div><div class="col-md-6">
                    <div class='form-group zip required'>
                        <label class='control-label'>Text 2</label>
                        <textarea name="text2" class='form-control' rows="5" cols="20" disabled>{{$plan->metadata->text2}}</textarea>
                        <small id="ziphelp" ></small>
                    </div>
                </div>
            </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
             
</div>
              
      </div>
      
      </form>   
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection