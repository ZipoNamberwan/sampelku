@extends('main')

@section('stylesheet')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="/assets/vendor/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="/assets/vendor/datatables2/datatables.min.css" />
<link rel="stylesheet" href="/assets/vendor/@fortawesome/fontawesome-free/css/fontawesome.min.css" />
<link rel="stylesheet" href="/assets/css/container.css">
<link rel="stylesheet" href="/assets/css/text.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .table td,
    .table th {
        font-size: .8125rem;
        word-wrap: break-word;
        white-space: nowrap !important;
        /* wrap table di sini cari di sini*/
    }
</style>
@endsection

@section('container')

<div class="header bg-warning pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-md-inline-block ml-md-4">
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
                        <h3 class="mb-0">Status Penggantian Sampel</h3>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                        @hasrole('pml')
                        <a href="{{url('/recommendation')}}" class="mb-2 btn btn-primary btn-round btn-icon" data-toggle="tooltip" data-original-title="Pengajuan Ganti Sampel">
                            <span class="btn-inner--icon"><i class="fas fa-plus-circle"></i></span>
                            <span class="btn-inner--text">Pengajuan Ganti Sampel</span>
                        </a>
                        @endrole

                        <div>
                            <p class="mb-0"><small>- Table bisa di scroll ke kanan</small></p>
                            <p class="mb-0"><small>- Kotak pencarian bisa digunakan untuk pencarian by nama/kode wilayah, nama responden</small></p>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-12 col-md-3">
                                <label class="form-control-label">Status Penggantian Sampel <span class="text-danger">*</span></label>
                                <select onchange="onStatusSampleChange()" id="status_sample" class="form-control" data-toggle="select" name="status_sample" required>
                                    <option value="all">
                                        Semua
                                    </option>
                                    <option value="no">
                                        Menunggu Sampel Pengganti
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" id="row-table">
                            <div class="table-responsive">
                                <table class="table" id="datatable-id" width="100%">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Sampel Utama</th>
                                            <th>Pengganti</th>
                                            @hasrole('pml') <th>Aksi</th> @endrole
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
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

<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header pb-1">
                <div>
                    <h2 id="modaltitle">Detail Sampel</h2>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0" style="height: auto;">
                <h3 id="sample-name-detail"></h3>
                <p id="address-detail" style="font-size: 0.9rem;" class="mb-0"></p>
                <p id="strata-detail" style="font-size: 0.9rem;" class="mb-0"></p>
                <p id="kbli-detail" style="font-size: 0.9rem;" class="mb-0"></p>
                <p id="category-detail" style="font-size: 0.9rem;" class="mb-0"></p>
                <p id="subdistrict-detail" style="font-size: 0.9rem;" class="mb-0"></p>
                <p id="village-detail" style="font-size: 0.9rem;" class="mb-0"></p>
                <p id="job-detail" style="font-size: 0.9rem;" class="mb-0"></p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('optionaljs')
<script src="/assets/vendor/select2/dist/js/select2.min.js"></script>
<script src="/assets/vendor/sweetalert2/dist/sweetalert2.js"></script>
<script src="/assets/vendor/datatables2/datatables.min.js"></script>
<script src="/assets/vendor/momentjs/moment-with-locales.js"></script>

<script>
    var table = $('#datatable-id').DataTable({
        "order": [],
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": '/sample/status/?status=all',
            "type": 'GET',
        },
        "columns": [{
                "responsivePriority": 1,
                "data": "sample_name",
                "render": function(data, type, row) {
                    if (type === 'display') {
                        return `
                            <h5 style="cursor: pointer" onclick='openDetail(${JSON.stringify(row)}, true, false)' data-toggle="modal" data-target="#detailModal">
                                <i class="fas fa-arrow-alt-circle-down text-danger"></i> [${row.sample_unique_code}] ${row.sample_name}
                                <i class="fas fa-info-circle text-info"></i>
                            </h5>
                            <p style="font-size: 0.9rem" class="mb-1">[${row.subdistrict_code}] ${row.subdistrict_name}, [${row.village_code}] ${row.village_name}</p>
                            <p class="mb-1" style="font-size: 0.9rem;">${row.sample_address}</p>
                            <p class="mb-1" style="font-size: 0.9rem;">${row.pcl}</p>
                            <p class="mb-1" style="font-size: 0.9rem;">${row.pml}</p>
                        `
                    }
                    return data;
                }
            },
            {
                "responsivePriority": 1,
                "data": "replacement_chain",
                "render": function(data, type, row) {
                    if (type === 'display') {

                        var html = ''
                        for (var i = 0; i < data.length; i++) {
                            if (i > 0) {
                                var icon = ''
                                var isreplaced = false
                                var isreplacing = false
                                if (data[i].status == 'Tidak Aktif' && data[i].replacement_id != null) {
                                    icon = '<i class="fas fa-arrow-alt-circle-up text-success"></i><i class="fas fa-arrow-alt-circle-down text-danger"></i>'
                                    isreplaced = true
                                    isreplacing = true
                                } else {
                                    icon = '<i class="fas fa-arrow-alt-circle-up text-success"></i>'
                                    isreplacing = true
                                }
                                var detail = `<i class="fas fa-info-circle text-info"></i>`

                                html = html + `<h5 style="cursor: pointer" onclick='openDetail(${JSON.stringify(data[i])}, ${isreplaced}, ${isreplacing})' data-toggle="modal" data-target="#detailModal">${icon} [${data[i].sample_unique_code}] ${data[i].sample_name} ${detail}</h5>`
                            }

                            if (data[i].replacement_id == null && data[i].status == 'Tidak Aktif') {
                                html = html + `<p class="text-danger mb-1 font-weight-bold" style="font-size: 0.9rem">Menunggu Sampel Pengganti</p>`
                            } else if (data[i].replacement_id == null && data[i].status == 'Aktif') {
                                html = html + `<p class="text-success mb-1 font-weight-bold" style="font-size: 0.9rem">Sampel Pengganti Berhasil Dicacah</p>`
                            } else if (data[i].replacement_id == null && (data[i].status == null || data[i].status == 'Belum Dicacah')) {
                                html = html + `<p class="text-muted mb-1 font-weight-bold" style="font-size: 0.9rem">Sampel Pengganti Sedang Dicacah</p>`
                            }
                        }
                        return html
                    }
                    return data;
                }
            },
            @hasrole('pml') {
                "responsivePriority": 1,
                "width": "5%",
                "data": "id",
                "render": function(data, type, row) {
                    var replacement = row.replacement_chain[row.replacement_chain.length - 1]
                    if (type === 'display') {
                        var pmlbutton = `
                            <button data-toggle="modal" data-target="#sampleModal" class="btn btn-outline-info btn-sm" onclick='updateModal(${JSON.stringify(replacement)})' role="button"><i class="fas fa-edit"></i></button>
                        `
                        return pmlbutton
                    }

                    return data;
                }
            },
            @endrole
        ],
        "language": {
            'paginate': {
                'previous': '<i class="fas fa-angle-left"></i>',
                'next': '<i class="fas fa-angle-right"></i>'
            }
        }
    });

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

    function loadSample() {
        // table.ajax.url('/jadwal-panen/data?subround=' + idsubround + '&year=' + yearid + monthUrl + commodityUrl + subdistrictUrl).load();
        table.ajax.reload();
    }

    function onStatusSampleChange() {
        table.ajax.url('/sample/status/?status=' + document.getElementById('status_sample').value).load();
    }

    function openDetail(sample, isreplaced, isreplacing) {
        var icon = ''
        if (isreplaced) {
            icon = icon + `<i class="fas fa-arrow-alt-circle-down text-danger"></i>`
        }
        if (isreplacing) {
            icon = icon + `<i class="fas fa-arrow-alt-circle-up text-success"></i>`
        }
        document.getElementById('sample-name-detail').innerHTML = `${icon} [${sample.sample_unique_code}] ${sample.sample_name}`
        document.getElementById('address-detail').innerHTML = `Alamat: ${sample.sample_address}`
        document.getElementById('strata-detail').innerHTML = `Strata: ${sample.strata}`
        document.getElementById('kbli-detail').innerHTML = `KBLI: ${sample.kbli}`
        document.getElementById('category-detail').innerHTML = `Kategori: ${sample.category}`
        document.getElementById('subdistrict-detail').innerHTML = `Kecamatan: [${sample.subdistrict_code}] ${sample.subdistrict_name}`
        document.getElementById('village-detail').innerHTML = `Desa/Kel: [${sample.village_code}] ${sample.village_name}`
        document.getElementById('job-detail').innerHTML = `Kegiatan: ${sample.job}`
    }
</script>
@endsection