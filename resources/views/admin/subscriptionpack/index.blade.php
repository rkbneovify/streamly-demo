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
            <h1>Plan</h1> {{ session()->get( 'info' ) }}
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Subscription Plan</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    <a class="btn btn-xs btn-primary" href="{{  url('admin/subscriptionpack/create') }} ">
                                        Add new subscription plan
                                    </a>

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">SubscriptionPlan</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body table-responsive p-0">
          <table class="table table-striped projects">
              <thead>
                  <tr>
                      <th style="width: 1%">
                          #
                      </th>
                      <th style="width: 20%">
                        Product
                      </th>
                      <th style="width: 20%">
                        Nickname
                      </th>
                      <th style="width: 20%">
                          Duration
                      </th>
                      <th style="width: 20%">
                          Rate ($)
                      </th>
                      <th style="width: 20%">
                          Trial days
                      </th><th style="width: 20%">
                      Created on(m/d/Y H:i:s)
                      </th><th style="width: 20%">
                        Active
                      </th>
                      
                      <th style="width: 20%">
                      Action
                      </th>
                  </tr>
              </thead>
              <tbody>
              @forelse ($plan as $plan)
              <tr>
              <td>#</td>
              
    <td>
      @foreach($product as $id => $pack)
     
      {{ ( $pack->id == $plan->product) ? $pack->name : '' }}
        
      
      @endforeach
    </td>
    <td>{{ $plan->nickname }}</td>
    <td>{{ $plan->interval_count }}-{{ $plan->interval }}</td>
    <td>
    

    @if (!empty($plan->tiers))

<details>
  <summary>Tiers</summary>
  <table class="table table-bordered">
	<thead style="background-color:#FFFFFF;">
		<tr >
			<td >
				<div  style="align-items: center; justify-content: flex-start;"><span class="Text-color--dark Text-fontSize--13 Text-fontWeight--medium Text-lineHeight--20 Text-numericSpacing--proportional Text-typeface--upper Text-wrap--noWrap Text-display--inline"><strong>Quantity</strong></span>
				</div>
			</td>
			<td >
				<div  style="align-items: center; justify-content: flex-start;"><span class="Text-color--dark Text-fontSize--13 Text-fontWeight--medium Text-lineHeight--20 Text-numericSpacing--proportional Text-typeface--upper Text-wrap--noWrap Text-display--inline"><strong>Price per unit</strong></span>
				</div>
			</td>
			<td >
				<div  style="align-items: center; justify-content: flex-start;"><span class="Text-color--dark Text-fontSize--13 Text-fontWeight--medium Text-lineHeight--20 Text-numericSpacing--proportional Text-typeface--upper Text-wrap--noWrap Text-display--inline"><strong>Flat amount</strong></span>
				</div>
			</td>
		</tr>
	</thead>
	<tbody >
  @foreach($plan->tiers as $id => $tier)
    @if($loop->first)
    <tr >
			<td class="">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau"><span>For the first <strong>{{$id+1}} to {{ $tier->up_to }}</strong></span></span>
				</div>
			</td>
			<td class="" style="max-width: 150px; min-width: 150px; width: 150px;">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span >{{Cashier::formatAmount($tier->amount??'0', $plan->currency??'USD')}}</span></div>
			</td>
			<td class="Table-cel">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau">{{Cashier::formatAmount($tier->flat_amount??'-', $plan->currency??'USD')}}</span></div>
			</td>
		</tr>
        @endif
        @if(!($id==0) && !($id == array_key_last($plan->tiers)))
		<tr >
			<td class="">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau"><span>For the next <strong>{{$plan->tiers[$id-1]->up_to + 1}}   to {{$tier->up_to}}</strong></span></span>
				</div>
			</td>
			<td class="" style="max-width: 150px; min-width: 150px; width: 150px;">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span >{{Cashier::formatAmount($tier->amount??'0', $plan->currency??'USD')}}</span></div>
			</td>
			<td class="Table-cel">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau">{{Cashier::formatAmount($tier->flat_amount??'-', $plan->currency??'USD')}}</span></div>
			</td>
		</tr>
        @endif
        @if($loop->last)  
		<tr >
			<td class="">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau"><span>For the rest {{ $tier->up_to }}</span></span>
				</div>
			</td>
			<td class="" style="max-width: 150px; min-width: 150px; width: 150px;">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span >{{Cashier::formatAmount($tier->amount??'0', $plan->currency??'USD')}}</span></div>
			</td>
			<td class="Table-cel">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau">{{Cashier::formatAmount($tier->flat_amount??'-', $plan->currency??'USD')}}</span></div>
			</td>
		</tr>
        @endif
        @endforeach
	</tbody>
</table>
 </details>
 
      <span class="badge badge-success">Tiers Plan</span>
@elseif (!empty($plan->amount))
{{Cashier::formatAmount($plan->amount??'0', $plan->currency??'USD')}}

@else
unknown
@endif
    
    </td>
    <td>{{ $plan->trial_period_days??'no trial days'}}</td>
    <td>{{ date('m/d/Y H:i:s',$plan->created) }}</td>
    <td>
      {{-- {{ $plan->active }}     --}}
                         @if ($plan->active==true)
      <span class="badge badge-success">Active</span>
@elseif ($plan->active==false)
<span class="badge badge-danger">Inactive</span>
@else
unknown
@endif</td>
    
    <td>
                                
                                    <a class="btn btn-xs btn-primary" href="{{  url('admin/subscriptionpack/view', $plan->id) }}">
                                        {{ trans('view') }}
                                    </a>

                                    <a class="btn btn-xs btn-info" href="{{ url('admin/subscriptionpack/edit', $plan->id) }}">
                                        {{ trans('edit') }}
                                    </a>

                                    <form action="{{ url('admin/subscriptionpack/destroy', $plan->id) }}" method="POST" onsubmit="return confirm('{{ trans('Are You Sure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                    </form>
                          
                                
                            </td></tr>
@empty
    <p>No Subscription Pack</p>
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