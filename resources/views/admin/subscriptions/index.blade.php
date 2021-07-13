<?php 
// dd($subscriptions);
 ?>
@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Subscription</h1> {{ session()->get( 'info' ) }}
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Subscription</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Subscription</h3>  <div class="text-center">
            </div>
          <div class="card-tools">
            
    <a class="btn btn-xs btn-primary" href="{{  url('admin/subscriptions/create') }} ">
      Add new subscription
  </a>
  <a  class=" btn btn-xs btn-warning" href="{{ url('stripesync') }}">Stripe Expiry Date Sync</a>
          </div>
        </div>
        <div class="card-body p-0">
          <table class="table table-striped projects">
              <thead>
                  <tr>
                      <th style="width: 1%">
                          #
                      </th>
                      <th style="width: 20%">
                          User Name
                      </th>
                      <th style="width: 20%">
                          Email
                      </th>
                      <th style="width: 20%">
                          Subscription
                      </th>
                      <th style="width: 20%">
                          Quantity
                      </th>
                      <th style="width: 20%">
                          Start Date
                      </th><th style="width: 20%">
                          Expiry Date
                      </th>
                      
                      <th style="width: 10%" >
                          Status
                      </th>
                      <th style="width: 20%">
                      Action
                      </th>
                  </tr>
              </thead>
              <tbody>
              @forelse ($subscriptions as $subscription)
              <tr>
              <td>#</td>
              
    <td>
      
      
                       {{ $subscription->user->name??'' }} 
                       
              
    </td>
    <td>{{ $subscription->user->email??'' }} </td>
    {{-- <select name="product" id="product" class="form-control" disabled>
                  
      @foreach($plans as $id => $pack)
      @if($subscription->stripe_plan==$pack->id)
      <option value="{{ $pack->id }}" selected="true">{{ $pack->nickname }}</option>
      @else
      <option value="{{ $pack->id }}">{{ "Value not found" }}</option>
      @endif 
      @endforeach
    </select> --}}
    {{-- <td>{{ $subscription->stripe_plan }}</td> --}}
    
    
    
    <td>
      @foreach($plans as $id => $pack)
     
      @if( $pack->id == $subscription->stripe_plan) 
     {{$pack->nickname }} 
       @if (!empty($pack->tiers)) 
       <span class="badge badge-success">Tiers Plan</span>
       @endif 
      @endif 
        
      
      @endforeach
    </td>
    <td>{{ $subscription->quantity }}</td>
    <td>{{ $subscription->created_at ??'' }}</td>
    <td>{{ $subscription->renews_at ??'' }}</td>
    <td >
                            {{$subscription->stripe_status??''}}
    </td>
    <td>
                                
                                    <a class="btn btn-xs btn-primary" href="{{  url('admin/subscriptions/view', $subscription->id) }}">
                                        {{ trans('view') }}
                                    </a>

                                    <a class="btn btn-xs btn-info" href="{{ url('admin/subscriptions/edit', $subscription->id) }}">
                                        {{ trans('edit') }}
                                    </a>

                                    <form action="{{ url('admin/subscriptiondestroy', $subscription->user_id) }}" method="POST" onsubmit="return confirm('{{ trans('are You Sure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="Cancel">
                                    </form>
                          
                                
                            </td></tr>
@empty
    <p>No Subscription</p>
@endforelse
                  
                </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection