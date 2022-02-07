<x-app-layout>
    <x-slot name="header">
        <div class="row justify-content-between">
            <div class="col-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
            </div>
            <div class="col-8 row justify-content-between">
                @foreach($wallet as $wall)
                    <div>
                        <h4>{{$wall->type}}:  {{number_format($wall->balance, 2, ',', '.')}}</h4>
                    </div>
                @endforeach

            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(Session::has('message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif
                    <div class="row justify-content-between">
                        <div class="col-4"><h2>Transaction</h2></div>
                        <div class="col-4 mb-4"><a type="button" href="{{route('trans')}}" style="color: white" class="btn btn-primary btn-lg">New Transaction</a></div>
                    </div>
                    <table class="table">
                        <caption>List of Transactions</caption>
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ref</th>
                            <th scope="col">From </th>
                            <th scope="col">To</th>
                            <th scope="col">Value</th>
                            <th scope="col">Source Currency</th>
                            <th scope="col">Target Currency</th>
                            <th scope="col">Created_at</th>
                            <th scope="col">Updated_at</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($trans as $key => $tran)
                        <tr>
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$tran->trans_ref}}</td>
                            <td>@if(Auth()->user()->id == $tran->sender_id)You
                                @else
                                {{\App\Models\User::where('id', $tran->sender_id)->pluck('name')->first()}}@endif</td>
                            <td>@if(Auth()->user()->id == $tran->receiver_id)You
                                @else{{\App\Models\User::where('id', $tran->receiver_id)->pluck('name')->first()}}@endif</td>
                            <td>{{number_format($tran->amount, 2, ',', '.')}}</td>
                            <td>{{$tran->source_currency}}</td>
                            <td>{{$tran->target_currency}}</td>
                            <td>{{ $tran->created_at->format("F d, Y H:i")}}</td>
                            <td>{{ $tran->updated_at->format("F d, Y H:i")}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
