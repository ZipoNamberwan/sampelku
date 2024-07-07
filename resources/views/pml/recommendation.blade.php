@extends('main')

@section('stylesheet')
<link rel="stylesheet" href="/assets/vendor/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="/assets/vendor/@fortawesome/fontawesome-free/css/fontawesome.min.css" />
<link rel="stylesheet" href="/assets/css/container.css">
<link rel="stylesheet" href="/assets/css/text.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

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
                        <h2 class="mb-1">Pengajuan Penggantian Sampel</h2>
                        <p style="font-size: 0.8rem; margin-bottom: 0px">Menu ini digunakan untuk mengajukan penggantian sampel dengan cara mengupdate status sampel menjadi tidak aktif. Sampel yang diubah statusnya menjadi tidak aktif akan dikirimkan ke Admin untuk kemudian dipilihkan sampel penggantinya</p>
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

<div class="modal fade" id="sampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header pb-1">
                <div>
                    <h2 id="modaltitle">Modal title</h2>
                    <h3 id="modalsubtitle">Modal title</h3>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="sample_id" />
            <div class="modal-body pt-0" style="height: auto;">
                <div class="d-flex flex-wrap align-items-center mb-2">
                    <span style="font-size: 0.8rem;" class="mb-2">Jika status sampel ini diubah menjadi tidak aktif, maka Anda akan mengajukan sampel ini untuk diganti. Subject Matter kemudian akan memilih sampel pengganti yang tepat</span>

                    <label class="form-control-label">Status <span class="text-danger">*</span></label>
                    <select id="status" name="status" class="form-control" data-toggle="select" name="subdistrict" required>
                        <option value="Belum Dicacah">
                            Belum Dicacah
                        </option>
                        <option value="Aktif">
                            Aktif
                        </option>
                        <option value="Tidak Aktif">
                            Tidak Aktif
                        </option>
                    </select>

                    <div id="status_error" style="display: none;" class="text-valid mt-2">
                        Belum diisi
                    </div>
                </div>

                <div>
                    <p id="loading-save" style="visibility: hidden;" class="text-warning mt-3">Loading...</p>
                </div>
            </div>

            <div class="modal-footer pt-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button onclick="onSave()" type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('optionaljs')

<script src="/assets/vendor/select2update/select2.min.js"></script>

<script>
    let debounceTimeout;

    document.getElementById('search').addEventListener('input', function() {
        if (event.type === 'input') {
            clearTimeout(debounceTimeout);
            const inputValue = event.target.value.trim();
            if (inputValue.length >= 3) {
                debounceTimeout = setTimeout(() => {
                    loadSample();
                }, 500);
            } else {
                const resultDiv = document.getElementById('samplelist');
                resultDiv.innerHTML = '';
            }
        }
    });

    function loadSample() {
        let search = $('#search').val();

        const resultDiv = document.getElementById('samplelist');
        resultDiv.innerHTML = '<p style="font-size: 0.9rem" class="text-info">Loading...<p/>';

        $.ajax({
            type: 'GET',
            url: '/sample/?is_selected=true&search=' + search,
            success: function(response) {

                samples = []
                var response = JSON.parse(response);

                const resultDiv = document.getElementById('samplelist');
                resultDiv.innerHTML = '';

                if (response.length == 0 && search != '') {
                    const nosample = document.createElement('label')
                    nosample.className = 'form-control-label text-info'
                    nosample.innerHTML = 'Sampel Tidak Ditemukan...'

                    resultDiv.appendChild(nosample)
                }

                response.forEach(item => {
                    samples.push(item)

                    const isReplaced = item.replacement_id != null;
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'border p-2 rounded d-flex flex-wrap justify-content-between align-items-center mb-1 ' + (isReplaced ? 'bg-lighter' : 'bg-white');
                    itemDiv.style = isReplaced ? "cursor: no-drop;" : "cursor: pointer;"

                    if (!isReplaced) {
                        itemDiv.setAttribute('data-toggle', 'modal');
                        itemDiv.setAttribute('data-target', '#sampleModal');
                    }

                    itemDiv.addEventListener('click', function() {
                        if (!isReplaced) {
                            updateModal(item)
                        }
                    });

                    var replacement = isReplaced ?
                        `<h4 class="mb-1"><i class="fas fa-arrow-alt-circle-up text-success"></i> Digantikan oleh: [${item.replacement_unique_code}] ${item.replacement_name}</h4>` : ''
                    var replacing = item.replacing.length > 0 ? `<h4 class="mb-1"><i class="fas fa-arrow-alt-circle-down text-danger"></i> Pengganti dari: [${item.replacing[0].sample_unique_code}] ${item.replacing[0].sample_name}</h4>` : ''

                    var replacementicon = isReplaced ? '<i class="fas fa-arrow-alt-circle-down text-danger"></i>' : ''
                    var replacingicon = item.replacing.length > 0 ? '<i class="fas fa-arrow-alt-circle-up text-success"></i>' : ''

                    var status = (item.status === 'Tidak Aktif' && !isReplaced) ? '<p class="text-danger mb-1 font-weight-bold" style="font-size: 0.9rem">Sampel Tidak Aktif, Menunggu Sampel Pengganti</p>' : ''

                    itemDiv.innerHTML = `
                        <div class="mb-1">
                            <h4 class="mb-1">[${item.sample_unique_code}] ${item.sample_name} ${replacementicon} ${replacingicon}</h4>
                            <p style="font-size: 0.9rem" class="mb-1">${item.sample_address}</p>
                            <p class="mb-1" style="font-size: 0.9rem">*${item.type}*</p>
                            ${status}
                            ${replacement}
                            ${replacing}
                        </div>
                        <div class="d-flex mb-1">
                            <button ${isReplaced ? 'disabled' : ''} class="btn btn-outline-info btn-sm">
                                <span class="btn-inner--icon">
                                    <i class="fas fa-edit"></i>
                                </span>
                            </button>
                        </div>
                    `;

                    resultDiv.appendChild(itemDiv);

                });
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

    function validate() {
        var status_valid = true
        if (document.getElementById('status').value == 0) {
            status_valid = false
            document.getElementById('status_error').style.display = 'block'
        } else {
            document.getElementById('status_error').style.display = 'none'
        }

        return status_valid
    }

    function updateModal(sample) {

        document.getElementById('status_error').style.display = 'none'

        document.getElementById('modaltitle').innerHTML = sample.sample_name
        document.getElementById('modalsubtitle').innerHTML = sample.sample_address
        document.getElementById('sample_id').value = sample.id

        document.getElementById('status').value = 0
        const options = document.getElementById('status').options;
        var opt = []
        for (let i = 0; i < options.length; i++) {
            if (i == 0) {
                options[i].selected = true;
            } else {
                options[i].selected = false;
            }
            opt.push(options[i])
        }
        $('#status').empty()
        for (let i = 0; i < opt.length; i++) {
            $('#status').append(opt[i]);
        }

        const optionstmp = document.getElementById('status').options;
        for (let i = 0; i < optionstmp.length; i++) {
            if (optionstmp[i].value == sample.status) {
                optionstmp[i].selected = true;
            }
        }
    }

    function onSave() {

        document.getElementById('status_error').style.display = 'none'

        if (validate()) {
            document.getElementById('loading-save').style.visibility = 'visible'

            id = document.getElementById('sample_id').value
            var updateData = {
                status: document.getElementById('status').value,
            };

            $.ajax({
                url: `/sample/notactive/${id}`,
                type: 'PATCH',
                data: updateData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    loadSample()
                    $('#sampleModal').modal('hide');
                    document.getElementById('loading-save').style.visibility = 'hidden'
                },
                error: function(xhr, status, error) {
                    document.getElementById('loading-save').style.visibility = 'hidden'
                }
            });
        }
    }
</script>
@endsection