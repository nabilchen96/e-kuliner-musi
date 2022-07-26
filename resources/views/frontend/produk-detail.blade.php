@extends('frontend.app')
@push('style')
    <style>
        .card-image {
            background-size: cover;
            background-position: center;
            height: 300px;
        }

        .card-image-small {
            background-size: cover;
            background-position: center;
            aspect-ratio: 1 / 1;
        }
    </style>
@endpush
@section('content')
    <div class="container mt-4 mb-5 px-4">
        <div>
            <div>
                <div class="row">
                    <div class="col-lg-4">
                        <div style="background-image: url('{{ asset('gambar_produk') }}/{{ $detail->gambar_1 }}');"
                            class="card shadow card-image">
                        </div>
                        <div class="row mt-3">
                            <div class="col-2 mb-3" style="padding: 0 0 0 10px">
                                <div style="background-image: url('{{ asset('gambar_produk') }}/{{ $detail->gambar_2 }}');"
                                    class="card shadow card-image-small">
                                </div>
                            </div>
                            <div class="col-2 mb-3" style="padding: 0 0 0 10px">
                                <div style="background-image: url('{{ asset('gambar_produk') }}/{{ $detail->gambar_3 }}');"
                                    class="card shadow card-image-small">
                                </div>
                            </div>
                            <div class="col-2 mb-3" style="padding: 0 0 0 10px">
                                <div style="background-image: url('{{ asset('gambar_produk') }}/{{ $detail->gambar_4 }}');"
                                    class="card shadow card-image-small">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="" style="min-height: 500px">
                            <div class="card-body p-0">
                                <h4>{{ $detail->judul_produk }}</h4>
                                <span class="badge bg-primary mb-2">{{ $detail->jenis_produk }}</span>
                                <p><b> Rp. {{ number_format($detail->harga) }}</b></p>
                                <i class="bi bi-shop"></i>
                                {{ $detail->name }}
                                <br />
                                <i class="bi bi-whatsapp"></i>
                                Hubungi Toko

                                <br /><br />
                                <b>Deskripsi</b>
                                <p>
                                    <?php echo nl2br($detail->deskripsi); ?>
                                </p>
                            </div>
                            <h4 class="mt-5">Diskusi</h4>
                            @if (Auth::check())
                                <form id="form">
                                    <input type="hidden" name="id_produk" id="id_produk">
                                    <textarea name="pesan" id="pesan" class="form-control mb-4" cols="30" rows="5" placeholder="Diskusi"></textarea>
                                    <button class="btn btn-primary" id="tombol_kirim">
                                        <i class="bi bi-send"></i> Kirim
                                    </button>
                                </form>
                                <br /><br />
                            @else   
                                <div class="alert alert-info">
                                    <i class="bi bi-door-closed"></i> Login untuk mulai ikut berdiskusi
                                </div>
                            @endif
                            <div id="diskusi">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="container mt-5">
        <div class="row">
            {{-- daftar produk --}}
            <h4 class="px-4">Daftar Produk</h4>
            @include('frontend.components.list-produk', ['data' => $produk])

        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            getData()
        })

        let url = window.location.href

        let newsSplit = url.split('/')
        let newsId = newsSplit[newsSplit.length - 1];

        document.getElementById('id_produk').value = newsId

        function getData() {
            axios.get('/back/diskusi-produk/' + newsId).then(function(res) {
                let data = res.data.data

                console.log(data);

                let diskusi = ''

                data.forEach(e => {

                    diskusi += `<p style="font-size: 12px; margin-top: 20px; margin-bottom: 5px">
                                    <i class="bi bi-people"></i> ${e.name} |
                                    <i class="mx-1 bi bi-calendar"></i> ${e.created_at}
                                </p>
                                ${e.pesan}
                                `
                });


                document.getElementById('diskusi').innerHTML = diskusi
            })
        }

        form.onsubmit = (e) => {

            let formData = new FormData(form);

            e.preventDefault();

            document.getElementById("tombol_kirim").disabled = true;

            axios({
                    method: 'post',
                    url: '/back/store-diskusi-produk',
                    data: formData,
                })
                .then(function(res) {
                    //handle success         
                    if (res.data.responCode == 1) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: res.data.respon,
                            timer: 3000,
                            showConfirmButton: false
                        })

                        document.getElementById("form").reset();
                        getData()

                    } else {
                        //error validation
                        // document.getElementById('password_alert').innerHTML = res.data.respon.password ?? ''
                        // document.getElementById('email_alert').innerHTML = res.data.respon.email ?? ''
                    }

                    document.getElementById("tombol_kirim").disabled = false;
                })
                .catch(function(res) {
                    //handle error
                    console.log(res);
                    document.getElementById("tombol_kirim").disabled = false;
                });
        }
    </script>
@endpush
