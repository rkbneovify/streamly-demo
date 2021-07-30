<?php 
use Laravel\Cashier\Cashier;
$login= Auth::check()?true:false;
?>
<div class="col-sm-4 select-plan-card">
				<div style="background: #FFFFFF 0% 0% no-repeat padding-box;box-shadow: 0px 3px 6px #00000029;border-radius: 4px;">
					<p>{{ $singlePlansDetail->nickname }}</p> @if (!empty($singlePlansDetail->tiers))
					<details>
						<summary>
							<h3>Tiers {{$singlePlansDetail->interval_count}} {{$singlePlansDetail->interval}}</h3></summary>
       @component('components.tiertable',['singlePlansDetail' => $singlePlansDetail,'type'=>'tiered'])
		
		@endcomponent
					</details> @elseif (!empty($singlePlansDetail->amount))
					<h3> {{Cashier::formatAmount($singlePlansDetail->amount??'0', $singlePlansDetail->currency??'USD')}}/ {{$singlePlansDetail->interval_count}} {{$singlePlansDetail->interval}}</h3> @else
					<h3>unknown</h3> @endif
					<hr/>
					<ul style="position: relative;width: 75%;padding-left: 20px;margin: 0 auto;">
						<li>Trial days: {{$singlePlansDetail->trial_period_days??'no trials'}} days</li> @foreach ($singlePlansDetail->metadata->jsonSerialize() as $key => $value)
						<li>{{$value??'Lorem Ipsum is simply dummy text of the printing'}}</li> @endforeach </ul>
					<?php

                        if ( isset($login) && $login ) {

							if(!empty($singlePlansDetail->tiers)){
								$selectPLanLink = 'href=/tierpayment/'.$singlePlansDetail->id;
							}else{
								$selectPLanLink = 'href=/payment/'.$singlePlansDetail->id;
							}
                        } else {
                            $selectPLanLink = 'onclick=openNavReg() href=javascript:void(0);';
                        }

                        ?> <a class="btn btn-primary btn-select-plan btn-xl js-scroll-trigger" {{$selectPLanLink}}>SELECT PLAN</a> </div>
			</div>
            