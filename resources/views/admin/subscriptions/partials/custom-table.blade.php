<style>
    .custom-checkbox {
        width: 18px;
        height: 18px;
        position: relative;
        cursor: pointer;
    }

    .custom-badge {
        font-size: 14px;
        font-weight: 500;
        border-radius: 14px;
        padding: 5px 10px;
        display: inline-block;
    }

    .badge-active {
        color: #578f6d;
        background-color: #dff5e8;
    }

    .badge-inactive {
        color: #cf6773;
        background-color: #ffebf0;
    }

    .table thead {
        background-color: #fafafa;
    }

    .table thead tr th {
        font-size: 14px;
        color: #7d7d7d;
    }

    .table tbody tr td {
        font-size: 16px;
        color: #383838;
        font-weight: 500;

    }

    .badge-light {
        background: none;
    }
</style>

@if ($subscriptions->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>
                    #
                </th>
                <th>
                    Name
                </th>
                <th>
                    Email
                </th>
                <th>
                    Role
                </th>
                <th>
                    Plan Duration
                </th>
                <th>
                    Status
                </th>
                <th>
                    Subscribe At

                    <a href="{{ request()->fullUrlWithQuery([
                        'sort_subscribe_at' => request()->get('sort_subscribe_at', 'desc') === 'desc' ? 'asc' : 'desc',
                    ]) }}"
                        class="text-primary ms-1">↑↓</a>
                </th>
                <th>
                    Subscription
                    <br>
                    Expire At
                </th>


                <th style="vertical-align: middle;text-align: center;">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subscriptions as $key => $subscription)
                <tr>
                    <td style="vertical-align: middle;">
                        {{ $key + $subscriptions->firstItem() }}
                    </td>

                    <td style="vertical-align: middle;">
                        <a href="{{ route('admin.users.view', $subscription->user->id) }}">
                            <div class="mb-2"
                                style="width:40px; height:40px;
                                            background:#d62d41;
                                            color:white; display:flex; align-items:center; justify-content:center;
                                            border-radius:50%; font-weight:bold;">
                                {{ getAvatarName($subscription->user->name) }}
                            </div>


                            {{ ucwords(Str::limit($subscription->user->name, 10, '...')) }}
                        </a>

                    </td>
                    <td style="vertical-align: middle;">

                        {{ limitEmailName($subscription->user->email, 10, '...') }}
                    </td>


                    <td style="vertical-align: middle;">
                        {{ $subscription->user->roles[0]->name }}</td>
                    <td style="vertical-align: middle;">

                        {{ $subscription->plan_duration }}</td>


                    </td>
                    <td style="vertical-align: middle;">
                        {{ Str::ucfirst($subscription->custom_status ?? '') }}
                    </td>


                    <td style="vertical-align: middle;">
                        {{ date('d/m/Y', strtotime($subscription->current_period_start)) }}
                    </td>
                    <td style="vertical-align: middle;">

                        @if ($subscription->renews_at != null)
                            {{ date('d/m/Y', strtotime($subscription->renews_at)) }}
                        @else
                            <span class="badge badge-success">Never</span>
                        @endif

                    </td>

                    <td style="vertical-align: middle; text-align: center;">

                        @if ($subscription->planRequest != null)
                            <button class="btn btn-danger btn-xs w-100 mb-1"
                                onclick="openModal('{{ $subscription->planRequest->id }}','{{ $subscription->planRequest->plan->product_name }}','{{ $subscription->planRequest->plan->interval_count . ' ' . $subscription->planRequest->plan->interval }}','{{ $subscription->planRequest->plan->amount }}','{{ $subscription->user->roles[0]->name }}','{{ $subscription->user->device_limit }}')">
                                Plan Request
                            </button>
                        @endif
                        <br>
                        @if ($subscription->custom_status == 'active')
                            <a class="btn btn-secondary btn-xs w-100 btn-cancel-subscription"
                                href="{{ route('admin.custom.subscriptions.cancel', $subscription->user_id) }}">
                                Cancel
                            </a>
                        @endif


                        {{-- @if ($subscription->livemode == null || $subscription->stripe_status == 'canceled')
                            <a href="{{ route('admin.subscriptions.custom.view', $subscription->user_id) }}"
                                class="btn btn-xs btn-link mb-1 w-75">Subscribe Now</a>
                            <br>
                            <a href="{{ route('admin.subscriptions.user.mail.upgrade', $subscription->user_id) }}"
                                class="btn btn-xs btn-secondary mb-1 w-75 send-mail-btn"
                                data-url="{{ route('admin.subscriptions.user.mail.upgrade', $subscription->user_id) }}">
                                <span class="btn-text">Send Mail</span>
                                <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"
                                    aria-hidden="true"></span>
                            </a>
                            @if ($subscription->stripe_status == 'canceled')
                                <a class="btn btn-xs btn-outline-primary mb-1 w-75"
                                    href="{{ route('admin.report.subscription.detail', $subscription->user->id) }}">
                                    {{ trans('View') }}
                                </a>
                            @endif
                        @else
                            <a style="width: 80px;" class="btn btn-xs btn-outline-primary mb-1"
                                href="{{ route('admin.report.subscription.detail', $subscription->user->id) }}">
                                {{ trans('View') }}
                            </a>

                            <a style="width: 80px;" class="btn btn-xs btn-info mb-1"
                                href="{{ url('admin/subscriptions/edit', $subscription->id) }}">
                                {{ trans('Edit') }}
                            </a>

                            <form action="{{ url('admin/subscriptiondestroy', $subscription->user_id) }}"
                                method="POST" onsubmit="return confirm('{{ trans('are You Sure') }}');"
                                style="display: inline-block;">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input style="width: 80px;" type="submit" class="btn btn-xs btn-danger" value="Cancel">
                            </form>
                        @endif --}}



                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
    <div class="container px-5 mt-5">
        <div class="row justify-content-end">
            {{ $subscriptions->links() }}
        </div>
    </div>
@else
    <table class="table table-striped projects">
        <thead>
            <tr>
                <th style="width: 1%">
                    #
                </th>
                <th style="width: 20%">
                    Name
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
                </th>
                <th style="width: 20%">
                    Expiry Date
                </th>

                <th style="width: 10%">
                    Status
                </th>
                <th style="width: 20%">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="9" class="text-center" style="height: 200px;">
                    <h3>Data not found.</h3>
                </td>
            </tr>

        </tbody>
    </table>
@endif



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Subscription Plan Request</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col"><b>Plan Name</b></div>
                    <div id="txtPlanName" class="col"></div>
                </div>
                <div class="row">
                    <div class="col"><b>Plan Duration</b></div>
                    <div id="txtPlanDuration" class="col"></div>
                </div>
                <div class="row">
                    <div class="col"><b>Allowted Devices</b></div>
                    <div id="txtDeviceLimit" class="col"></div>
                </div>
                {{-- <div class="row">
                    <div class="col"><b>Amount</b></div>
                    <div id="txtAmount" class="col">Test</div>
                </div> --}}
            </div>
            <div class="modal-footer">
                <input id="txtPlanReqId" type="text" hidden>
                {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                <button id="btnAccept" type="button" class="btn btn-primary">Accept</button>
                <button id="btnReject" type="button" class="btn btn-danger">Reject</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(planReqId, planName, planDuration, amount, role, deviceLimit) {
        console.log(planName)
        document.getElementById('txtPlanName').innerText = planName += `(${role})`;
        document.getElementById('txtPlanDuration').innerText = planDuration;
        // document.getElementById('txtAmount').innerText = amount;
        document.getElementById('txtDeviceLimit').innerText = deviceLimit;
        document.getElementById('txtPlanReqId').value = planReqId;
        const modal = new bootstrap.Modal(
            document.getElementById('myModal')
        );

        modal.show();
    }

    document.getElementById('btnAccept').addEventListener('click', () => {
        const planReqId = document.getElementById('txtPlanReqId').value;

        let url = "{{ route('admin.subscriptions.plan.request.accept', ':id') }}";
        url = url.replace(':id', planReqId);
        const modal = new bootstrap.Modal(
            document.getElementById('myModal')
        );

        modal.hide();

        Swal.fire({
            title: 'Please wait...',
            text: 'Processing request',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        location.href = url;
    })
    document.getElementById('btnReject').addEventListener('click', () => {
        const planReqId = document.getElementById('txtPlanReqId').value;

        let url = "{{ route('admin.subscriptions.plan.request.reject', ':id') }}";
        url = url.replace(':id', planReqId);
        const modal = new bootstrap.Modal(
            document.getElementById('myModal')
        );

        modal.hide();

        Swal.fire({
            title: 'Please wait...',
            text: 'Processing request',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        location.href = url;
    });
</script>
<script>
    document.querySelectorAll('.btn-cancel-subscription').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const url = this.href;

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to cancel this subscription.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    window.location.href = url;
                }
            });
        });
    });
</script>
