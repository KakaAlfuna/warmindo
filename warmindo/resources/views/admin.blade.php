@extends('layouts.app')

@section('css')
@endsection

@section('content')
    <div class="container" id="controller">
        <table id="datatable" class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">transaksi_id</th>
                <th scope="col">status</th>
                <th scope="col">total</th>
              </tr>
            </thead>
            <tbody>
                <tr v-for='pesanan in pesanans'>
                    <td>@{{ pesanan.index }}</td>
                    <td>@{{ pesanan.uuid }}</td>
                    <td>@{{ pesanan.status }}</td>
                    <td>@{{ pesanan.total }}</td>
                </tr>
            </tbody>
          </table>
    </div>
@endsection

@section('js')
<script type="text/javascript">
    var cartApi = "{{ url('/api/admin') }}";

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
