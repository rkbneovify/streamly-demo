<?php 
use Laravel\Cashier\Cashier;
?>
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
										<div class="" style="align-items: flex-start; justify-content: flex-start;"><span>{{Cashier::formatAmount($tier->unit_amount_decimal??'0', $singlePlansDetail->currency??'USD')}}</span></div>
									</td>
									<td class="Table-cel">
										<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau">{{Cashier::formatAmount($tier->flat_amount_decimal??'-', $singlePlansDetail->currency??'USD')}}</span></div>
									</td>
								</tr> @endif @if(!($id==0) && !($id == array_key_last($singlePlansDetail->tiers)))
								<tr>
									<td class="">
										<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau"><span>For the next <strong>{{$singlePlansDetail->tiers[$id-1]->up_to + 1}}   to {{$tier->up_to}}</strong></span></span>
										</div>
									</td>
									<td class="" style="max-width: 150px; min-width: 150px; width: 150px;">
										<div class="" style="align-items: flex-start; justify-content: flex-start;"><span>{{Cashier::formatAmount($tier->unit_amount_decimal??'0', $singlePlansDetail->currency??'USD')}}</span></div>
									</td>
									<td class="Table-cel">
										<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau">{{Cashier::formatAmount($tier->flat_amount_decimal??'-', $singlePlansDetail->currency??'USD')}}</span></div>
									</td>
								</tr> @endif @if($loop->last)
								<tr>
									<td class="">
										<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau"><span>For the rest {{ $tier->up_to }}</span></span>
										</div>
									</td>
									<td class="" style="max-width: 150px; min-width: 150px; width: 150px;">
										<div class="" style="align-items: flex-start; justify-content: flex-start;"><span>{{Cashier::formatAmount($tier->unit_amount_decimal??'0', $singlePlansDetail->currency??'USD')}}</span></div>
									</td>
									<td class="Table-cel">
										<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau">{{Cashier::formatAmount($tier->flat_amount_decimal??'-', $singlePlansDetail->currency??'USD')}}</span></div>
									</td>
								</tr> @endif @endforeach </tbody>
						</table>