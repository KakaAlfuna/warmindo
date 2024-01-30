@extends('layouts.admin')

@section('header', 'Transaksi')

@section('css')
@endsection

@section('content')
    <div class="container" id="controller">
        <table id="datatable" class="table table-bordered">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Transaksi ID</th>
                <th scope="col">User ID</th>
                <th scope="col">Status</th>
                <th scope="col">Total</th>
              </tr>
            </thead>
            <tbody>
                <tr v-for='pesanan in pesanans'>
                    <td>@{{ pesanan.index }}</td>
                    <td>@{{ pesanan.uuid }}</td>
                    <td style="float: center">@{{ pesanan.user_id }}</td>
                    <td>@{{ pesanan.status }}</td>
                    <td>@{{ pesanan.total }}</td>
                </tr>
            </tbody>
          </table>
    </div>
@endsection

@section('js')
<script type="text/javascript">
    var cartApi = "{{ url('/api/transactions') }}";

    var app = new Vue({
        el: '#controller',
        data: {
            pesanans: [],
        },
        mounted: function() {
            this.get_pesanan();
        },
        methods: {
            get_pesanan() {
                axios.get(cartApi)
                    .then(response => {
                        this.pesanans = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }, 
        },
    });
</script>
@endsection
