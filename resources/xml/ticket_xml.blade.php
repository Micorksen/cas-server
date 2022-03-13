<cas:serviceResponse xmlns:cas="http://www.yale.edu/tp/cas">
@if(array_key_exists("authenticationSuccess", $serviceResponse))
    <cas:authenticationSuccess>
        <cas:user>{{ $serviceResponse["authenticationSuccess"]["user"] }}</cas:user>
        @if(array_key_exists("attributes", $serviceResponse["authenticationSuccess"]))
        <cas:attributes>
            @foreach($serviceResponse["authenticationSuccess"]["attributes"] as $key => $value)
                @if(is_array($value))
                    @foreach($value as $value2)
                        <cas:{{ $key }}>{{ $value2 }}</cas:{{ $key }}>
                    @endforeach
                @else
                    <cas:{{ $key }}>{{ $value }}</cas:{{ $key }}>
                @endif
            @endforeach
        </cas:attributes>
        @endif
        @if($serviceResponse["authenticationSuccess"]["proxyGrantingTicket"])
            <cas:proxyGrantingTicket>{{ $serviceResponse["authenticationSuccess"]["proxyGrantingTicket"] }}</cas:proxyGrantingTicket>
        @else
            <cas:proxyGrantingTicket />
        @endif
    </cas:authenticationSuccess>
@else
    <cas:authenticationFailure code="{{ $serviceResponse["authenticationFailure"]["code"] }}">{{ $serviceResponse["authenticationFailure"]["description"] }}</cas:authenticationFailure>
@endif
</cas:serviceResponse>
