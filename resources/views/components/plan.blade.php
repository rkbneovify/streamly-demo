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
						<table class="table table-bordered">
							<thead style="background-color:#FFFFFF;">
								<tr>
									<td>
										<div style="align-items: center; justify-content: flex-start;"><span class="Text-color--dark Text-fontSize--13 Text-fontWeight--medium Text-lineHeight--20 Text-numericSpacing--proportional Text-typeface--upper Text-wrap--noWrap Text-display--inline"><strong>Quantity</strong></span> </div>
									</td>
									<td>
										<div style="align-items: center; justify-content: flex-start;"><span class="Text-color--dark Text-fontSize--13 Text-fontWeight--medium Text-lineHeight--20 Text-numericSpacing--proportional Text-typeface--upper Text-wrap--noWrap Text-display--inline"><strong>Price per unit</strong></span> </div>
									</td>
									<td>
										<div style="align-items: center; justify-content: flex-start;"><span class="Text-color--dark Text-fontSize--13 Text-fontWeight--medium Text-lineHeight--20 Text-numericSpacing--proportional Text-typeface--upper Text-wrap--noWrap Text-display--inline"><strong>Flat amount</strong></span> </div>
									</td>
								</tr>
							</thead>
							<tbody> @foreach($singlePlansDetail->tiers as $id => $tier) @if($loop->first)
								<tr>
									<td class="">
										<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau"><span>For the first <strong>{{$id+1}} to {{ $tier->up_to }}</strong></span></span>
										</div>
									</td>
									<td class="" style="max-width: 150px; min-width: 150px; width: 150px;">
										<div class="" style="align-items: flex-start; justify-content: flex-start;"><span>{{Cashier::formatAmount($tier->amount??'0', $singlePlansDetail->currency??'USD')}}</span></div>
									</td>
									<td class="Table-cel">
										<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau">{{Cashier::formatAmount($tier->flat_amount??'-', $singlePlansDetail->currency??'USD')}}</span></div>
									</td>
								</tr> @endif @if(!($id==0) && !($id == array_key_last($singlePlansDetail->tiers)))
								<tr>
									<td class="">
										<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau"><span>For the next <strong>{{$singlePlansDetail->tiers[$id-1]->up_to + 1}}   to {{$tier->up_to}}</strong></span></span>
										</div>
									</td>
									<td class="" style="max-width: 150px; min-width: 150px; width: 150px;">
										<div class="" style="align-items: flex-start; justify-content: flex-start;"><span>{{Cashier::formatAmount($tier->amount??'0', $singlePlansDetail->currency??'USD')}}</span></div>
									</td>
									<td class="Table-cel">
										<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau">{{Cashier::formatAmount($tier->flat_amount??'-', $singlePlansDetail->currency??'USD')}}</span></div>
									</td>
								</tr> @endif @if($loop->last)
								<tr>
									<td class="">
										<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau"><span>For the rest {{ $tier->up_to }}</span></span>
										</div>
									</td>
									<td class="" style="max-width: 150px; min-width: 150px; width: 150px;">
										<div class="" style="align-items: flex-start; justify-content: flex-start;"><span>{{Cashier::formatAmount($tier->amount??'0', $singlePlansDetail->currency??'USD')}}</span></div>
									</td>
									<td class="Table-cel">
										<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau">{{Cashier::formatAmount($tier->flat_amount??'-', $singlePlansDetail->currency??'USD')}}</span></div>
									</td>
								</tr> @endif @endforeach </tbody>
						</table>
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
            