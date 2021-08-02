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
 <div class="card card-primary card-outline">
          
          <div class="card-body">
            <h4>Plans</h4>
            
            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
              {{-- <li class="nav-item">
                <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Per Unit</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Tiered</a>
              </li> --}}


 @foreach ($plankey as $plank) 
 @if ($loop->first)  
 <li class="nav-item">
                <a class="nav-link active" id="{{$plank}}-tab" data-toggle="pill" href="#{{$plank}}" role="tab" aria-controls="{{$plank}}" aria-selected="true">{{$plank}}</a>
              </li>
 @else
 <li class="nav-item">
                <a class="nav-link" id="{{$plank}}-tab" data-toggle="pill" href="#{{$plank}}" role="tab" aria-controls="{{$plank}}" aria-selected="false">{{$plank}}</a>
              </li>
 @endif
 @endforeach


            </ul>
            <div class="tab-content" id="custom-content-below-tabContent">
            @foreach($plansdetails as $key => $singlePlansDetails)
        @if ($loop->first)  
         
   <div class="tab-pane fade show active" id="{{$key}}" role="tabpanel" aria-labelledby="{{$key}}-tab">
   
   <?php 
        $theids=$singlePlansDetails->pluck('id');

         ?>
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
              @forelse ($subscriptions->whereIn('stripe_plan',$theids) as $subscription)
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

     {{ $subscription->stripe_plan }}
    @if($key=='tiered')
      
     
       <span class="badge badge-success">Tiers Plan</span>
      
      @endif 
        
      
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
    @else

        <div class="tab-pane fade" id="{{$key}}" role="tabpanel" aria-labelledby="{{$key}}-tab">
        
        <?php 
        $theids=$singlePlansDetails->pluck('id');

         ?>
         
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
              @forelse ($subscriptions->whereIn('stripe_plan',$theids) as $subscription)
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

     {{ $subscription->stripe_plan }}
    @if($key=='tiered')
      
     
       <span class="badge badge-success">Tiers Plan</span>
      
      @endif 
        
      
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
              
            </div>
@endif
    @endforeach
              
              
            {{-- <div class="tab-custom-content">
              <p class="lead mb-0">{{$key}}</p>
            </div> --}}
           
            


            
          </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection