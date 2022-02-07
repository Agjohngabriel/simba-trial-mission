<x-app-layout>
    <x-slot name="header">
        <div class="row justify-content-between">
        <div class="col-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
        <div class="col-8 row justify-content-between">
            @foreach($wallets as $wall)
                <div>
                    <h4>{{$wall->type}}:  {{number_format($wall->balance, 2, ',', '.')}}</h4>
                    <input type="hidden" id="{{$wall->type}}" value="{{$wall->balance}}">
                </div>
            @endforeach

        </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="row justify-content-between">
                        <div class="col-4"><h2>Transaction</h2></div>
                    </div>
                    <form action="{{route('send')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="formGroupExampleInput">Source Currency</label>
                            <select name="source" id="source" class="form-control">
                                @foreach($wallets as $wallet)
                                <option value="{{$wallet->type}}">{{$wallet->type}}</option>
                                @endforeach
                            </select>
                            <div class="rates">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="formGroupExampleInput">Target Currency</label>
                            <select name="target" id="target" class="form-control">
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="NGN">NGN</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="formGroupExampleInput2">Amount</label>
                            <input type="number" name="amount" class="form-control" id="amount" placeholder="Enter Amount ">
                            <div class="amountt">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="formGroupExampleInput">Send To</label>
                            <select class="form-control" name="receiver">
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" id="submitButton" class="btn btn-primary">Send</button>
                        <p class="alert"></p>
                    </form>
                    <script>
                        //JQ
                        let $source = $('#source');
                        let $target = $('#target');
                        let $amount = $('#amount');


                        function calcVal() {
                            let sourcev = $source.val();
                            let targetv = $target.val();
                            let amountv = $amount.val();
                            $.get( "https://api.exchangerate.host/latest?", { base: sourcev, symbols: targetv } )
                                    .done(function( data ) {
                                        if(targetv === "USD") {
                                            let $rate = data.rates.USD;
                                            let $converted = amountv * $rate;
                                            let $a = (Math.round($converted * 100) / 100).toLocaleString();
                                            let $amount = amountv;
                                            $( ".rates" ).empty();
                                            $( ".rates" ).append( '<span>The Exchange rate is: ' + $rate + '</span>' );
                                            $( ".amountt" ).empty();
                                            $( ".amountt" ).append( '<span>The equivalent to USD is : ' +  $a + '</span>' );

                                            if (Number($('#'+sourcev+'').val()) > Number($amount)){
                                                $('#submitButton').attr("disabled", false);
                                                $( ".alert" ).empty();
                                            }else{
                                                $('#submitButton').attr("disabled", "");
                                                $( ".alert" ).empty();
                                                $( ".alert" ).append( '<span>Insufficient Funds in' + ' ' + sourcev + ' ' + 'wallet</span>' );
                                            }
                                        }
                                        if(targetv === "EUR") {
                                            let $rate = data.rates.EUR;
                                            let $converted = amountv * $rate;
                                            let $a = (Math.round($converted * 100) / 100).toLocaleString();
                                            let $amount = amountv;
                                            $( ".rates" ).empty();
                                            $( ".rates" ).append( '<span>The Exchange rate is: ' + $rate + '</span>' );
                                            $( ".amountt" ).empty();
                                            $( ".amountt" ).append( '<span>The equivalent to EUR is : ' +  $a + '</span>' );

                                            if (Number($('#'+sourcev+'').val()) > Number($amount)){
                                                $('#submitButton').attr("disabled", false);
                                                $( ".alert" ).empty();
                                            }else{
                                                $('#submitButton').attr("disabled", "");
                                                $( ".alert" ).empty();
                                                $( ".alert" ).append( '<span>Insufficient Funds in' + ' ' + sourcev + ' ' + 'wallet</span>' );
                                            }
                                        }
                                        if(targetv === "NGN") {
                                            let $rate = data.rates.NGN;
                                            let $converted = amountv * $rate;
                                            let $a = (Math.round($converted * 100) / 100).toLocaleString();
                                            let $amount = amountv;
                                            $( ".rates" ).empty();
                                            $( ".rates" ).append( '<span>The Exchange rate is: ' + $rate + '</span>' );
                                            $( ".amountt" ).empty();
                                            $( ".amountt" ).append( '<span>The equivalent to NGN is : ' +  $a + '</span>' );

                                            if (Number($('#'+sourcev+'').val()) > Number($amount)){
                                                $('#submitButton').attr("disabled", false);
                                                $( ".alert" ).empty();
                                            }else{
                                                $('#submitButton').attr("disabled", "");
                                                $( ".alert" ).empty();
                                                $( ".alert" ).append( '<span>Insufficient Funds in' + ' ' + sourcev + ' ' + 'wallet</span>' );
                                            }
                                        }
                                    });
                        }
                        $source.on("change", function() {
                            calcVal();
                        });
                        $target.on("change", function() {
                            calcVal();
                        });
                        $amount.on("keyup keydown", function() {
                            calcVal();
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
