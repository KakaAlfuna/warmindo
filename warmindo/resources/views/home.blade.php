@extends('layouts.app')

@section('css')
    
@endsection

@section('content')
<div id="controller" class="container">
    <div class="col-md-12">
        <h2 class="text-center" style="font-family: Arial, Helvetica, sans-serif">
            Warmindo         
        </h2>            
        <i class="fas fa-shopping-cart" data-bs-toggle="modal" data-bs-target="#modalCart" style="float: right"></i>
    </div>
    
    
    <div class="col-md-12">
        <strong>
            <h3>Makanan</h3>
        </strong>
    </div>
    <div class="row">
        <div class="col-md-4 mt-2" v-for="makanan in makanans">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">@{{ makanan.nama_makanan }}/ <strong>Rp. @{{ number_format(makanan.harga) }},-</strong></h5>
                    <p class="card-text">@{{ makanan.detail }}</p>
                    <div class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPesanan" v-on:click="openModal(makanan)">Pesan</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-5">
        <strong>
            <h3>Minuman</h3>
        </strong>
    </div>
    <div class="row">
        <div class="col-md-4 mt-2" v-for="minuman in minumans">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">@{{ minuman.nama_minuman }}/ <strong>Rp. @{{ number_format(minuman.harga) }},-</strong></h5>
                    <p class="card-text">@{{ minuman.detail }}</p>
                    <div class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPesanan" v-on:click="openModal(minuman)">Pesan</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 mt-5">
        <a href="{{ url('/bayar') }}" class="btn btn-primary">bayar</a>            
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
                        <input class="form-control" type="text" :value="number_format(total)" disabled>
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
                    <ul>
                        <li v-for="(pesanan, index) in pesanans">
                            @{{ pesanan.menu }} - @{{ pesanan.quantity }} pcs - Rp. @{{ number_format(pesanan.total) }}
                            <button class="btn btn-sm btn-danger" @click="removeFromCart(pesanan.id)">Delete</button>
                        </li>
                    </ul>
                    <hr>                    
                    <p>Total Pesanan: Rp. @{{ number_format(totalPesanan) }}</p>
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

    var app = new Vue({
        el: '#controller',
        data: {
            makanans: [],
            minumans: [],
            selectedItem: {},
            pesanans: [],
            totalPesanan: 0,
        },
        mounted: function() {
            this.get_makanan();
            this.get_minuman();
            this.get_pesanan();
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
                this.selectedItem = { ...selectedItem, quantity: 1 };
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
                    });
            },
            checkout() {
                axios.post('/checkout')
                    .then(() => {
                        this.get_pesanan()        
                    })
            },
        },
        computed: {
            total() {
                return this.selectedItem.total || 0;
            },
        },
    });
</script>
@endsection
