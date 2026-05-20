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
                    User Type
                </th>
                <th>
                    Subscription
                    <br>
                    Type
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
                        {{ getUserType($subscription->user) }}</td>
                    <td style="vertical-align: middle;">
                        {{-- {{ $subscription->livemode ?? 'Admin/Custom' }} --}}
                        @if ($subscription->livemode === null)
                            Admin/Custom
                        @elseif ($subscription->livemode === 1)
                            Stripe/Live
                        @elseif ($subscription->livemode === 0)
                            Stripe/Test
                        @endif

                        {{-- {{ getSubscriptionType($subscription) }} --}}

                    </td>
                    <td style="vertical-align: middle;">
                        {{ Str::ucfirst($subscription->stripe_status ?? '') }}
                    </td>


                    <td style="vertical-align: middle;">
                        {{ date('d/m/Y', strtotime($subscription->created_at)) }}
                    </td>
                    <td style="vertical-align: middle;">

                        @if (getSubscriptionExpireAt($subscription) != null)
                            {{ getSubscriptionExpireAt($subscription) }}
                        @else
                            <span class="badge badge-success">Never</span>
                        @endif

                    </td>

                    <td style="vertical-align: middle;text-align: center;">
                        @if ($subscription->livemode == null || $subscription->stripe_status == 'canceled')
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
                        @endif



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
