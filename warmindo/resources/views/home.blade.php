@extends('layouts.app')

@section('css')
<style>
    .card {
        width: 20rem;
        padding: 10px;
        border: none;
        position: relative;
    }
    .btn-buy{
        background-color: rgba(13, 184, 79, 0.932);
        position: relative;
        color: white;
        transition-duration: 0.4s;
        cursor: pointer;
    }
    .btn-buy:hover{
        background-color: white;
        border: 2px solid;
    }
</style>
@endsection

@section('content')
<div id="controller" class="container">
    <div class="col-md-12">
        <h2 class="text-center" style="font-family: Arial, Helvetica, sans-serif">
            Warmindo        
        </h2>
        <button class="btn position-relative mb-2" style="float: right"  data-bs-toggle="modal" data-bs-target="#modalCart">
            <i class="fas fa-shopping-cart"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                @{{ count }}
            </span>
        </button>            
    </div>
    
    <!--banner-->
    <div class="container-fluid">
        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('assets/nasi_goreng.png') }}" class="d-block w-100" style="height: 500px">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/mi_goreng.jpeg')}}" class="d-block w-100" style="height: 500px">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/mi_kuah.jpeg') }}" class="d-block w-100" style="height: 500px">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <div class="col-md-12 mt-5">
        <strong>
            <h3>Makanan</h3>
        </strong>
        <hr>
    </div>
    <div class="row">
        <div class="col-md-3 mt-2" v-for="makanan in makanans">
            <div class="card" style="width: 18rem;">
                <div class="card-header">
                    <img :src="'storage/assets/' + makanan.nama_makanan + '.jpeg'" alt="" style="width: 250px; height: 250px">
                </div>
                <div class="card-body">
                    <h5 class="card-title">@{{ makanan.nama_makanan }}
                    </h5>
                    <strong>Rp. @{{ number_format(makanan.harga) }},-</strong>
                    <p class="card-text">@{{ makanan.detail }}</p>
                    <div class="btn btn-buy" data-bs-toggle="modal" data-bs-target="#modalPesanan" v-on:click="openModal(makanan)">Pesan</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-5">
        <strong>
            <h3>Minuman</h3>
        </strong>
        <hr>
    </div>
    <div class="row">
        <div class="col-md-3 mt-2" v-for="minuman in minumans">
            <div class="card" style="width: 18rem;">
                <div class="card-header">
                    <img :src="'assets/' + minuman.nama_minuman + '.jpeg'" alt="" style="width: 250px; height: 250px">
                </div>
                <div class="card-body">
                    <h5 class="card-title">@{{ minuman.nama_minuman }}
                    </h5>
                    <strong>Rp. @{{ number_format(minuman.harga) }},-</strong>
                    <p class="card-text">@{{ minuman.detail }}</p>
                    <div class="btn btn-buy" data-bs-toggle="modal" data-bs-target="#modalPesanan" v-on:click="openModal(minuman)">Pesan</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Pesanan --}}
    <div class="modal" tabindex="-1" id="modalPesanan">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="addToCart()">
                        @csrf
                        <label for="menu">Menu:</label>
                        <input class="form-control" type="text" :value="selectedItem.nama_makanan ? selectedItem.nama_makanan : selectedItem.nama_minuman" disabled>
                        <label for="harga">Harga:</label>
                        <input class="form-control" type="text" :value="number_format(selectedItem.harga)" disabled>
                        <label for="kuantiti">Kuantiti:</label>
                        <input class="form-control" type="number" v-model="selectedItem.quantity" name="qty" id="qty" @input="calculateTotal">                   
                        <label for="total">Total:</label>
                        <input class="form-control" type="text" :value="number_format(selectedItem.total)" disabled>
                    </form>
                </div>                
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal" v-on:click="addToCart(selectedItem)">Pesan</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Keranjang Belanja -->
    <div class="modal" tabindex="-1" id="modalCart">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Keranjang Belanja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" v-if="pesanans.length > 0">
                        <tr>
                            <th>Menu</th>
                            <th>Kuantiti</th>
                            <th>Total</th>
                            <th>Delete</th>
                        </tr>
                        <tr v-for="(pesanan, index) in pesanans">
                            <td>@{{ pesanan.menu }}</td>
                            <td>@{{ pesanan.quantity }}</td>
                            <td>Rp.@{{ number_format(pesanan.total) }},-</td>
                            <td>
                                <button class="btn btn-sm btn-danger" @click="removeFromCart(pesanan.id)">Delete</button>
                            </td>
                        </tr>
                    </table>
                    <hr>                    
                    <p style="float: right">Total Pesanan: Rp. @{{ number_format(totalPesanan) }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="checkout()">Checkout</button>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection

@section('js')
<script type="text/javascript">
    var makananApi = "{{ url('api/menus') }}";
    var minumanApi = "{{ url('api/drinks') }}";
    var cartApi = "{{ url('/cart') }}";
    var countApi = "{{ url('/count') }}";

    var app = new Vue({
        el: '#controller',
        data: {
            makanans: [],
            minumans: [],
            selectedItem: {},
            pesanans: [],
            count: [],
            totalPesanan: 0,
        },
        mounted: function() {
            this.get_makanan();
            this.get_minuman();
            this.get_pesanan();
            this.get_count();
        },
        methods: {
            get_makanan() {
                axios.get(makananApi)
                    .then(response => {
                        this.makanans = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            get_minuman() {
                axios.get(minumanApi)
                    .then(response => {
                        this.minumans = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            get_count() {
                axios.get(countApi)
                    .then(response => {
                        this.count = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            get_pesanan() {
                axios.get(cartApi)
                    .then(response => {
                        this.pesanans = response.data;
                        this.calculateTotalPesanan();
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            openModal(selectedItem) {
                this.selectedItem = { ...selectedItem, quantity: 0 };
            },
            addToCart(selectedItem) {
                this.selectedItem = selectedItem;
                this.calculateTotal();
                axios.post('/addToCart', {
                    nama: selectedItem.nama_makanan ? selectedItem.nama_makanan : selectedItem.nama_minuman,
                    harga: selectedItem.harga,
                    quantity: selectedItem.quantity,
                    total: selectedItem.total,
                }).then(() => {
                    this.get_pesanan();
                    this.get_count();
                });
            },
            calculateTotal() {
                if (this.selectedItem.quantity === undefined || this.selectedItem.quantity === null) {
                    this.selectedItem.total = 0;
                } else {
                    this.selectedItem.total = this.selectedItem.harga * this.selectedItem.quantity;
                }
            },
            calculateTotalPesanan() {
                this.totalPesanan = this.pesanans.reduce((total, pesanan) => total + pesanan.total, 0);
            },
            number_format(x) {
                if (x === undefined || x === null) {
                    return '0';
                }
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            },
            removeFromCart(id) {
                axios.delete(`/cart/${id}`)
                    .then(() => {
                        this.get_pesanan();
                        this.get_count();
                    });
                },
            checkout() {
                axios.post('/checkout', {
                    total : this.totalPesanan
                })
                .then(() => {
                    this.get_pesanan();        
                    this.get_count();
                })
            },
        },
    });
</script>
@endsection
