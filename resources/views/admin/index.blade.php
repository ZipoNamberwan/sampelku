@extends('main')

@section('stylesheet')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="/assets/vendor/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="/assets/vendor/datatables2/datatables.min.css" />
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
                        <h3 class="mb-0">Penggantian Sampel</h3>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                        <div>
                            <p class="mb-0"><small>- Table bisa di scroll ke kanan</small></p>
                            <p class="mb-0"><small>- Kotak pencarian bisa digunakan untuk pencarian by nama/kode wilayah, nama responden</small></p>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-12 col-md-3">
                                <label class="form-control-label">Status</label>
                                <select onchange="onMainSampleFilter()" id="status_sample" class="form-control" data-toggle="select" name="status_sample" required>
                                    <option value="all">
                                        Semua
                                    </option>
                                    <option value="no">
                                        Menunggu Sampel Pengganti
                                    </option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label class="form-control-label">PML</label>
                                <select onchange="onMainSampleFilter()" id="pml" class="form-control" data-toggle="select" name="status_sample" required>
                                    <option value="all">
                                        Semua
                                    </option>
                                    @foreach($pml as $p)
                                    <option value="{{$p->id}}">
                                        {{$p->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label class="form-control-label">PCL</label>
                                <select onchange="onMainSampleFilter()" id="pcl" class="form-control" data-toggle="select" name="status_sample" required>
                                    <option value="all">
                                        Semua
                                    </option>
                                    @foreach($pcl as $p)
                                    <option value="{{$p->id}}">
                                        {{$p->name}}
                                    </option>
                                    @endforeach
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
                                            <th style="max-width: 400px;">Sampel Utama</th>
                                            <th style="max-width: 400px;">Pengganti</th>
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
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header pb-1">
                <div>
                    <h2 id="modaltitle">Penggantian Sampel</h2>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0" style="height: auto;">
                <div class="mt-2 mb-4 p-4" style="border: 1px solid #ccc !important; border-radius: 16px">
                    <div class="row">
                        <div class="col" id="id-sample-change">

                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="row mb-3 px-2">
                        <div class="col-sm-12 col-md-6 col-lg-3 p-0 my-1 mx-1">
                            <input placeholder="Kode/Nama Kecamatan" type="text" name="subdistrict" id="subdistrict-filter" class="form-control" onchange="filter()">
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 p-0 my-1 mx-1">
                            <input placeholder="Kode/Nama Desa" type="text" name="village" id="village-filter" class="form-control" onchange="filter()">
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 p-0 my-1 mx-1">
                            <input placeholder="KBLI" type="text" name="kbli" id="kbli-filter" class="form-control" onchange="filter()">
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 p-0 my-1 mx-1">
                            <input placeholder="Strata" type="text" name="strata" id="strata-filter" class="form-control" onchange="filter()">
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 p-0 my-1 mx-1">
                            <input placeholder="Kategori" type="text" name="category" id="category-filter" class="form-control" onchange="filter()">
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 p-0 my-1 mx-1">
                            <p class="mb-0" style="font-size: 0.7rem;">Tampilkan Sample Tambahan?</p>
                            <label class="custom-toggle">
                                <input type="checkbox" id="additional-filter">
                                <span class="custom-toggle-slider rounded-circle" data-label-off="Tidak" data-label-on="Ya"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="datatable-change" width="100%">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama</th>
                                <th>KBLI</th>
                                <th>Strata</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" id="replaced-id" />
                <input type="hidden" id="replacement-id" />

                <div class="row mt-3 mb-2 mx-2">
                    <div class="col-md-12 col-lg-6">
                        <div class="p-2" id="replaced" style="border: 1px solid #ccc !important; border-radius: 8px">

                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="p-2" id="replacement" style="border: 1px solid #ccc !important; border-radius: 8px">

                        </div>
                    </div>
                </div>
            </div>

            <div>
                <p id="loading-save" style="visibility: hidden;" class="mx-4 text-warning mt-3">Loading...</p>
            </div>
            <div id="replacement-error" style="display: none;" class="mx-4 text-valid mt-2">
                Sampel Pengganti Belum Dipilih
            </div>

            <div class="modal-footer pt-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button onclick="onSave()" type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header pb-1">
                <div>
                    <h2 id="modaltitle">Hapus Sampel Pengganti</h2>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0" style="height: auto;">
                <input type="hidden" id="replacement-delete-id" />
                <div class="row mt-3 mb-2">
                    <div class="col-md-12 col-lg-6">
                        <div class="p-2" id="replacement-delete" style="border: 1px solid #ccc !important; border-radius: 8px">

                        </div>
                    </div>
                </div>
            </div>

            <div>
                <p id="loading-delete" style="visibility: hidden;" class="mx-4 text-warning mt-3">Loading...</p>
            </div>

            <div class="modal-footer pt-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button onclick="onDelete()" type="button" class="btn btn-danger">Hapus</button>
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
                "width": "20%",
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
                            <h5 class="mb-1">Kategori: ${row.category}</h5>
                        `
                    }
                    return data;
                }
            },
            {
                "responsivePriority": 1,
                "data": "replacement_chain",
                "width": "20%",
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

                            if (data[i].replacement_id == null){
                                html = html + `<h5 class="mb-1">Kategori: ${data[i].category}</h5>`
                            }
                        }
                        return html
                    }
                    return data;
                }
            },
            {
                "responsivePriority": 1,
                "width": "5%",
                "data": "id",
                "render": function(data, type, row) {
                    var replacement = row.replacement_chain[row.replacement_chain.length - 1]
                    if (type === 'display') {
                        var replacedisabled = (replacement.status == 'Aktif' || replacement.status == null || replacement.status == 'Belum Dicacah') ? 'disabled' : ''
                        var deletedisabled = row.replacement_chain.length < 2 ? 'disabled' : ''
                        var pmlbutton = `
                            <button ${replacedisabled} data-toggle="modal" data-target="#sampleModal" class="btn btn-outline-success btn-sm" onclick='updateModal(${JSON.stringify(replacement)})' role="button"><i class="fas fa-exchange-alt"></i></button>
                            <button ${deletedisabled} data-toggle="modal" data-target="#deleteModal" class="btn btn-outline-danger btn-sm" onclick='deleteModal(${JSON.stringify(replacement)})' role="button"><i class="fas fa-trash"></i></button>
                        `
                        return pmlbutton
                    }

                    return data;
                }
            },
        ],
        "language": {
            'paginate': {
                'previous': '<i class="fas fa-angle-left"></i>',
                'next': '<i class="fas fa-angle-right"></i>'
            }
        }
    });

    function onMainSampleFilter() {
        var url = '?'

        var statusfilter = ''
        if (document.getElementById('status_sample').value != '') {
            url = url + '&status=' + document.getElementById('status_sample').value
        }
        var pmlfilter = ''
        if (document.getElementById('pml').value != '') {
            url = url + '&pml=' + document.getElementById('pml').value
        }
        var pclfilter = ''
        if (document.getElementById('pcl').value != '') {
            url = url + '&pcl=' + document.getElementById('pcl').value
        }

        table.ajax.url('/sample/status/' + url).load();
    }

    function updateModal(sample) {

        document.getElementById('replacement-error').style.display = 'none'
        document.getElementById('replacement-id').value = ''
        document.getElementById('replaced-id').value = sample.id

        tablechange.ajax.url('/sample/change').load()
        document.getElementById('subdistrict-filter').value = ''
        document.getElementById('village-filter').value = ''
        document.getElementById('kbli-filter').value = ''
        document.getElementById('strata-filter').value = ''
        document.getElementById('category-filter').value = ''
        document.getElementById('additional-filter').checked = false

        document.getElementById('id-sample-change').innerHTML = `
                <h3><i class="fas fa-arrow-alt-circle-down text-danger"></i> [${sample.sample_unique_code}] ${sample.sample_name}</h3>
                <p style="font-size: 0.9rem;" class="mb-0">${sample.sample_address}</p>
                <p style="font-size: 0.9rem;" class="mb-0">Strata: ${sample.strata}</p>
                <p style="font-size: 0.9rem;" class="mb-0">KBLI: ${sample.kbli}</p>
                <p style="font-size: 0.9rem;" class="mb-0">Kategori: ${sample.category}</p>
                <p style="font-size: 0.9rem;" class="mb-0">Kecamatan: [${sample.subdistrict_code}] ${sample.subdistrict_name}</p>
                <p style="font-size: 0.9rem;" class="mb-0">Desa/Kel: [${sample.village_code}] ${sample.village_name}</p>
                    
        `
        document.getElementById('replaced').innerHTML = `
                <h5><i class="fas fa-arrow-alt-circle-down text-danger"></i> [${sample.sample_unique_code}] ${sample.sample_name}</h5>
                <p style="font-size: 0.9rem;" class="mb-0">${sample.sample_address}</p> 
        `

        document.getElementById('replacement').innerHTML = ``


    }

    function deleteModal(sample) {

        document.getElementById('replacement-delete-id').value = sample.id

        document.getElementById('replacement-delete').innerHTML = `
            <h5><i class="fas fa-arrow-alt-circle-up text-success"></i> [${sample.sample_unique_code}] ${sample.sample_name}</h5>
            <p style="font-size: 0.9rem;" class="mb-0">${sample.sample_address}</p> 
        `
    }
</script>

<script>
    let debounceTimeout;

    var tablechange = $('#datatable-change').DataTable({
        "order": [],
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": '/sample/change',
            "type": 'GET',
        },
        "columns": [{
                "responsivePriority": 1,
                "width": "20%",
                "data": "sample_name",
                "render": function(data, type, row) {
                    if (type === 'display') {
                        var html = `
                            <h5 class="mb-1">[${row.sample_unique_code}] ${data}</h5>
                            <p style="font-size: 0.9rem" class="mb-1">${row.type}</p>
                            <p style="font-size: 0.9rem" class="mb-1">[${row.subdistrict_code}] ${row.subdistrict_name}</p>
                            <p style="font-size: 0.9rem" class="mb-1">[${row.village_code}] ${row.village_name}</p>
                            <p style="font-size: 0.9rem" class="mb-1">${row.sample_address}</p>
                        `
                        return html
                    }

                    return data;
                }
            }, {
                "responsivePriority": 2,
                "width": "5%",
                "data": "kbli",
            }, {
                "responsivePriority": 3,
                "width": "5%",
                "data": "strata",
            }, {
                "responsivePriority": 4,
                "width": "5%",
                "data": "category",
            },
            {
                "responsivePriority": 4,
                "width": "5%",
                "data": "id",
                "render": function(data, type, row) {
                    if (type === 'display') {
                        var isdisabled = (!row.available) ? 'disabled' : ''
                        var html = `
                            <button ${isdisabled} class="btn btn-primary btn-sm" role="button" onclick='selectChange(${JSON.stringify(row)})'><i class="ni ni-active-40"></i></button>
                         `
                        return html
                    }

                    return data;
                }
            },
        ],
        "language": {
            'paginate': {
                'previous': '<i class="fas fa-angle-left"></i>',
                'next': '<i class="fas fa-angle-right"></i>'
            }
        }
    });

    document.getElementById('subdistrict-filter').addEventListener('input', function() {
        if (event.type === 'input') {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                filter();
            }, 500);
        }
    });
    document.getElementById('village-filter').addEventListener('input', function() {
        if (event.type === 'input') {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                filter();
            }, 500);
        }
    });
    document.getElementById('kbli-filter').addEventListener('input', function() {
        if (event.type === 'input') {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                filter();
            }, 500);
        }
    });

    document.getElementById('strata-filter').addEventListener('input', function() {
        if (event.type === 'input') {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                filter();
            }, 500);
        }
    });

    document.getElementById('category-filter').addEventListener('input', function() {
        if (event.type === 'input') {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                filter();
            }, 500);
        }
    });

    document.getElementById('additional-filter').addEventListener('change', function() {
        filter();
    });

    function selectChange(sample) {
        document.getElementById('replacement').innerHTML = `
            <h5><i class="fas fa-arrow-alt-circle-up text-success"></i> [${sample.sample_unique_code}] ${sample.sample_name}</h5>
            <p style="font-size: 0.9rem;" class="mb-0">${sample.sample_address}</p> 
        `

        document.getElementById('replacement-id').value = sample.id

    }

    function filter() {
        var url = '?'

        var subdistrictfilter = ''
        if (document.getElementById('subdistrict-filter').value != '') {
            url = url + '&subdistrict=' + document.getElementById('subdistrict-filter').value
        }
        var villagefilter = ''
        if (document.getElementById('village-filter').value != '') {
            url = url + '&village=' + document.getElementById('village-filter').value
        }
        var kblifilter = ''
        if (document.getElementById('kbli-filter').value != '') {
            url = url + '&kbli=' + document.getElementById('kbli-filter').value
        }
        var stratafilter = ''
        if (document.getElementById('strata-filter').value != '') {
            url = url + '&strata=' + document.getElementById('strata-filter').value
        }
        var categoryfilter = ''
        if (document.getElementById('category-filter').value != '') {
            url = url + '&category=' + document.getElementById('category-filter').value
        }
        var additionalfilter = ''
        if (document.getElementById('additional-filter').checked) {
            url = url + '&additional=1'
        } else {
            url = url + '&additional=0'
        }

        tablechange.ajax.url('/sample/change/' + url).load();
    }

    function validate() {
        var replacement_valid = true
        if (document.getElementById('replacement-id').value == '') {
            replacement_valid = false
            document.getElementById('replacement-error').style.display = 'block'
        } else {
            document.getElementById('replacement-error').style.display = 'none'
        }

        return replacement_valid
    }

    function onSave() {
        document.getElementById('replacement-error').style.display = 'none'

        if (validate()) {
            document.getElementById('loading-save').style.visibility = 'visible'

            var updateData = {
                replaced: document.getElementById('replaced-id').value,
                replacement: document.getElementById('replacement-id').value,
            };

            $.ajax({
                url: `/sample/change`,
                type: 'PATCH',
                data: updateData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    document.getElementById('loading-save').style.visibility = 'hidden'
                    $('#sampleModal').modal('hide');
                    table.ajax.reload();
                },
                error: function(xhr, status, error) {
                    document.getElementById('loading-save').style.visibility = 'hidden'
                }
            });
        }
    }

    function onDelete() {

        document.getElementById('loading-delete').style.visibility = 'visible'

        var updateData = {
            delete: document.getElementById('replacement-delete-id').value,
        };

        $.ajax({
            url: `/sample/change`,
            type: 'DELETE',
            data: updateData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                document.getElementById('loading-delete').style.visibility = 'hidden'
                $('#deleteModal').modal('hide');
                table.ajax.reload();
            },
            error: function(xhr, status, error) {
                document.getElementById('loading-delete').style.visibility = 'hidden'
            }
        });

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