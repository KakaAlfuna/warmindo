@extends('layouts.admin')

@section('header', 'Menu')

@section('css')
@endsection

@section('content')
    <div class="container" id="controller">
        <a @click="addMenu()" class="btn btn-primary">Add Menu</a>
        <table id="datatable" class="table table-bordered mt-2">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Menu</th>
                <th scope="col">Harga</th>
                <th scope="col">Detail</th>
                <th scope="col">Gambar</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
                <tr v-for='menu in menus'>
                    <td>@{{ menu.index }}</td>
                    <td>@{{ menu.nama_makanan }}</td>
                    <td>@{{ menu.harga }}</td>
                    <td>@{{ menu.detail }}</td>
                    <td>
                        <img :src="'storage/assets/' + menu.nama_makanan + '.jpeg'" alt="" style="width: 100px; height: 100px">
                    </td>
                    <td>
                        <button class="btn btn-warning" v-on:click="editMenu(menu)" >Edit</button>
                        <button class="btn btn-danger" v-on:click="deleteMenu(menu.id)" >Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Modal --}}
        <div class="modal" tabindex="-1" id="modalAdd">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Menu</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" :action="actionUrl" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="menu">Nama Menu</label>
                                    <input class="form-control" name='nama_makanan' placeholder="Masukkan nama menu" type="text">
                                    <label for="harga">Harga</label>
                                    <input class="form-control" name='harga' placeholder="Masukkan harga" type="number">
                                    <label for="kuantiti">Detail</label>
                                    <input class="form-control" name='detail' placeholder="Masukkan detail (opsional)" type="text">                   
                                    <label for="total">Gambar</label>
                                    <input class="form-control" name='gambar' type="file">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </form>
                    </div>                
                </div>
            </div>
        </div>
        {{-- Modal --}}
        <div class="modal" tabindex="-1" id="modalEdit">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Menu</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" :action="actionUrl" enctype="multipart/form-data">
                            @csrf
                            {{-- {{ method_field('PUT') }} --}}
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="menu">Nama Menu</label>
                                    <input class="form-control" name='nama_makanan' placeholder="Masukkan nama menu" type="text" :value="data.nama_makanan">
                                    <label for="harga">Harga</label>
                                    <input class="form-control" name='harga' placeholder="Masukkan harga" type="number" :value="data.harga" >
                                    <label for="kuantiti">Detail</label>
                                    <input class="form-control" name='detail' placeholder="Masukkan detail (opsional)" type="text" :value="data.detail" >                   
                                    <label for="total">Gambar</label>
                                    <input class="form-control" name='gambar' type="file">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </form>
                    </div>                
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script type="text/javascript">
    var menuApi = "{{ url('/api/menus') }}";
    var actionUrl = "{{ url('/addMenu') }}";

    var app = new Vue({
        el: '#controller',
        data: {
            menuApi,
            actionUrl,
            menus: [],
            data: {},
            editStatus: false,
        },
        mounted: function() {
            this.get_menu();
        },
        methods: {
            get_menu() {
                axios.get(menuApi)
                    .then(response => {
                        this.menus = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }, 
            addMenu() {
                this.data = {};
                this.actionUrl = "{{ url('/addMenu') }}";
                $('#modalAdd').modal();
            },
            editMenu(data){
                this.data = data;
                this.actionUrl = this.actionUrl+'/'+data.id;
                this.editStatus = true;
                $('#modalEdit').modal();
            },
            deleteMenu(id) {
                this.actionUrl = this.actionUrl+'/'+id;
                if (confirm("Are you sure?")) {
                    axios.delete(this.actionUrl, { data: { _method: 'DELETE' } })
                    .then(response => {
                            location.reload();
                        });
                }
            },
        },
    });
</script>
@endsection
