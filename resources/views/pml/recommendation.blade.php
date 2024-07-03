@extends('main')

@section('stylesheet')
<link rel="stylesheet" href="/assets/vendor/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="/assets/vendor/@fortawesome/fontawesome-free/css/fontawesome.min.css" />
<link rel="stylesheet" href="/assets/css/container.css">
<link rel="stylesheet" href="/assets/css/text.css">

@endsection

@section('container')
<div class="header bg-warning pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">

    <div class="row">
        <div class="col">
            <div class="card-wrapper">
                <!-- Custom form validation -->
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <h2 class="mb-1">Sampel Status Tidak Aktif</h2>
                        <p style="font-size: 0.8rem; margin-bottom: 0px">Menu ini digunakan untuk mengupdate status sampel menjadi tidak aktif. Sampel yang diubah statusnya menjadi tidak aktif akan dikirimkan ke Admin untuk kemudian dipilihkan sampel penggantinya</p>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-control-label" for="name">Cari Sampel di Sini</label>
                                <p style="font-size: 0.8rem;">Cari Berdasarkan Kode, Nama atau Alamat Perusahaan</p>
                                <input type="text" name="search" class="form-control" id="search">
                                <div style="margin-top: 10px;" id="samplelist"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('optionaljs')

<script>
    let debounceTimeout;

    document.getElementById('search').addEventListener('input', function() {
        if (event.type === 'input') {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                loadSample();
            }, 500);
        }
    });

    function loadSample() {
        let search = $('#search').val();

        const resultDiv = document.getElementById('samplelist');
        resultDiv.innerHTML = '<p>Loading...<p/>';

        $.ajax({
            type: 'GET',
            url: '/sample/?is_selected=true&search=' + search,
            success: function(response) {

                samples = []
                var response = JSON.parse(response);

                console.log(response)

                const resultDiv = document.getElementById('samplelist');
                resultDiv.innerHTML = '';

            },
            error: function(jqXHR, textStatus, errorThrown) {
                const resultDiv = document.getElementById('samplelist');

                resultDiv.innerHTML = `
                        <div class="d-flex">
                            <span class="mr-2">Gagal Menampilkan Sampel</span>
                            <button onclick="loadSample()" class="btn btn-sm btn-outline-primary">Muat Ulang</button>
                        </div>
                `;
            }
        });
    }
</script>
@endsection