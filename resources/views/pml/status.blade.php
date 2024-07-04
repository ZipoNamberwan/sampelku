@extends('main')

@section('stylesheet')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="/assets/vendor/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="/assets/vendor/datatables2/datatables.min.css" />
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
                        <h3 class="mb-0">Progres Lapor</h3>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                        @hasrole('pml')
                        <a href="{{url('/recommendation')}}" class="mb-2 btn btn-primary btn-round btn-icon" data-toggle="tooltip" data-original-title="Tambah Jadwal Panen">
                            <span class="btn-inner--icon"><i class="fas fa-plus-circle"></i></span>
                            <span class="btn-inner--text">Pengajuan Ganti Sampel</span>
                        </a>
                        @endrole

                        <div>
                            <p class="mb-0"><small>- Table bisa di scroll ke kanan</small></p>
                            <p class="mb-0"><small>- Kotak pencarian bisa digunakan untuk pencarian by nama/kode wilayah, nama responden</small></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" id="row-table">
                            <div class="table-responsive">
                                <table class="table" id="datatable-id" width="100%">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="max-width: 400px;">Sampel</th>
                                            <th style="max-width: 400px;">Pengganti</th>
                                            @hasrole('admin|pml') <th>Aksi</th> @endrole
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
            "url": '/sample/status',
            "type": 'GET',
        },
        "columns": [{
                "responsivePriority": 1,
                "data": "sample_name",
                "render": function(data, type, row) {
                    if (type === 'display') {
                        return `
                            <h5><i class="fas fa-arrow-alt-circle-down text-danger"></i> [${row.sample_unique_code}] ${row.sample_name}</h5>
                            <p style="font-size: 0.9rem;">${row.sample_address}</p>
                        `
                    }
                    return data;
                }
            },
            {
                "responsivePriority": 1,
                "data": "replacement_id",
                "render": function(data, type, row) {
                    if (type === 'display') {
                        if (data === null) {
                            return `
                                <p class="text-danger mb-1 font-weight-bold" style="font-size: 0.9rem">Menunggu Sampel Pengganti</p>               
                            `
                        } else {
                            return `
                                <h5><i class="fas fa-arrow-alt-circle-up text-success"></i> [${row.replacement_unique_code}] ${row.replacement_name}</h5>
                                <p style="font-size: 0.9rem;">${row.replacement_address}</p>
                             `
                        }
                    }
                    return data;
                }
            },
            @hasrole('admin|pml') {
                "responsivePriority": 1,
                "width": "5%",
                "data": "id",
                "render": function(data, type, row) {
                    return "<a href=\"/users/" + data + "/edit\" class=\"btn btn-outline-info  btn-sm\" role=\"button\" aria-pressed=\"true\" data-toggle=\"tooltip\" data-original-title=\"Ubah Data\">" +
                        "<span class=\"btn-inner--icon\"><i class=\"fas fa-edit\"></i></span></a>" +
                        "<form class=\"d-inline\" id=\"formdelete" + data + "\" name=\"formdelete" + data + "\" onsubmit=\"deleteSchedule('" + data + "','" + row.name + "')\" method=\"POST\" action=\"/users/" + data + "\">" +
                        '@method("delete")' +
                        '@csrf' +
                        "<button class=\"btn btn-icon btn-outline-danger btn-sm\" type=\"submit\" data-toggle=\"tooltip\" data-original-title=\"Hapus Data\">" +
                        "<span class=\"btn-inner--icon\"><i class=\"fas fa-trash-alt\"></i></span></button></form>";
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
</script>
@endsection