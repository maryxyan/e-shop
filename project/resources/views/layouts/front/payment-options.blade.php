@if(isset($payment['name']))
    @if($payment['name'] == config('bank-transfer.name'))
        @include('front.payments.bank-transfer')
    @endif
@endif
