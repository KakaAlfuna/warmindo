@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="container" id="controller">
    <div class="col-md 12 text-center">
        <h2>Anda akan membayar sejumlah Rp. @{{ number_format(transactions.total) }}</h2>
        <a class="btn btn-primary" id="pay-button">Bayar sekarang</a>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    var app = new Vue({
        el: '#controller',
        data: {
            cartApi: "{{ url('cart') }}",
            api: "{{ url('api/transaction') }}",
            transactions: [],
        },
        mounted: function() {
            this.get_pesanan();
        },
        methods: {
            get_pesanan() {
                axios.get(this.api)
                    .then(response => {
                        this.transactions = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            number_format(x) {
                if (x === undefined || x === null) {
                    return '0';
                }
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            },
        },
    });
</script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
      document.getElementById('pay-button').onclick = function(){
        // SnapToken acquired from previous step
        snap.pay('{{$transaction->snap_token}}', {
          // Optional
          onSuccess: function(result){
           window.location.href = '{{ route('success') }}';
          },
          // Optional
          onPending: function(result){
            /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
          },
          // Optional
          onError: function(result){
            /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
          }
        });
      };
    </script>
@endsection
