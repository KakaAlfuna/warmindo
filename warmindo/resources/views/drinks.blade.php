@extends('layouts.admin')

@section('header', 'Minuman')

@section('css')
@endsection

@section('content')
    <div class="container" id="controller">
        <table id="datatable" class="table table-bordered">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Menu</th>
                <th scope="col">Harga</th>
                <th scope="col">Detail</th>
                <th scope="col">Gambar</th>
              </tr>
            </thead>
            <tbody>
                <tr v-for='minuman in minumans'>
                    <td>@{{ minuman.index }}</td>
                    <td>@{{ minuman.nama_minuman }}</td>
                    <td>@{{ minuman.harga }}</td>
                    <td>@{{ minuman.detail }}</td>
                    <td>
                        <img :src="'assets/' + minuman.nama_minuman + '.jpeg'" alt="" style="width: 100px; height: 100px">
                    </td>
                </tr>
            </tbody>
          </table>
    </div>
@endsection

@section('js')
<script type="text/javascript">
    var cartApi = "{{ url('/api/drinks') }}";

    var app = new Vue({
        el: '#controller',
        data: {
            minumans: [],
        },
        mounted: function() {
            this.get_minuman();
        },
        methods: {
            get_minuman() {
                axios.get(cartApi)
                    .then(response => {
                        this.minumans = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }, 
        },
    });
</script>
@endsection
