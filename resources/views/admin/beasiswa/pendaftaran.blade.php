@extends('layouts.admin')

@section('title', 'Kelola Pendaftar')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-users"></i> Kelola Pendaftar</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-filter"></i> Filter Status
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item filter-status" href="#" data-status="">Semua Status</a></li>
                <li><a class="dropdown-item filter-status" href="#" data-status="pending">Pending</a></li>
                <li><a class="dropdown-item filter-status" href="#" data-status="diterima">Diterima</a></li>
                <li><a class="dropdown-item filter-status" href="#" data-status="ditolak">Ditolak</a></li>
            </ul>
        </div>
        <button class="btn btn-success" onclick="exportTable()">
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 bg-primary text-white stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-1">Total Pendaftar</h6>
                        <h4 class="mb-0">{{ $pendaftars->total() ?? 0 }}</h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-warning text-white stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-1">Pending</h6>
                        <h4 class="mb-0">{{ $pendaftars->where('status', 'pending')->count() }}</h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-success text-white stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-1">Diterima</h6>
                        <h4 class="mb-0">{{ $pendaftars->where('status', 'diterima')->count() }}</h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-danger text-white stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-1">Ditolak</h6>
                        <h4 class="mb-0">{{ $pendaftars->where('status', 'ditolak')->count() }}</h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col">
                <h6 class="card-title mb-0">
                    <i class="fas fa-list"></i> Daftar Pendaftar
                    @if($pendaftars->total() > 0)
                        <span class="badge bg-primary ms-2">{{ $pendaftars->total() }} Total</span>
                    @endif
                </h6>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        @if($pendaftars->count() > 0)
            <div class="table-responsive">
                <table id="pendaftarTable" class="table table-striped table-hover mb-0 align-middle display nowrap" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center no-search" style="width: 60px;">No</th>
                            <th style="min-width: 220px;">
                                <i class="fas fa-user me-1"></i> Pendaftar
                            </th>
                            <th style="min-width: 200px;">
                                <i class="fas fa-graduation-cap me-1"></i> Beasiswa
                            </th>
                            <th style="min-width: 200px;">
                                <i class="fas fa-envelope me-1"></i> Kontak
                            </th>
                            <th class="text-center" style="width: 130px;">
                                <i class="fas fa-info-circle me-1"></i> Status
                            </th>
                            <th class="text-center" style="width: 140px;">
                                <i class="fas fa-calendar me-1"></i> Tanggal
                            </th>
                            <th class="text-center no-search" style="width: 120px;">
                                <i class="fas fa-cogs me-1"></i> Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendaftars as $index => $pendaftar)
                        <tr>
                            <td class="text-center fw-bold text-muted">
                                {{ $index + 1 }}
                            </td>
                            <td>
                                <div class="d-flex align-items-start">
                                    <div class="avatar-wrapper me-3">
                                        <div class="avatar-initial rounded-circle bg-gradient-primary text-white d-flex align-items-center justify-content-center"
                                             style="width: 45px; height: 45px; font-size: 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                            {{ strtoupper(substr($pendaftar->nama_lengkap, 0, 2)) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold text-dark">{{ $pendaftar->nama_lengkap }}</h6>
                                        <div class="d-flex align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-id-card me-1"></i>{{ $pendaftar->nim }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="beasiswa-info">
                                    <h6 class="mb-1 text-primary fw-semibold">{{ $pendaftar->beasiswa->nama_beasiswa }}</h6>
                                    <div class="d-flex align-items-center">
                                        <small class="text-success fw-semibold">
                                            <i class="fas fa-money-bill-wave me-1"></i>
                                            Rp {{ number_format($pendaftar->beasiswa->jumlah_dana, 0, ',', '.') }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <div class="mb-1 d-flex align-items-center">
                                        <i class="fas fa-envelope text-primary me-2" style="width: 16px;"></i>
                                        <small class="text-truncate" style="max-width: 180px;" title="{{ $pendaftar->email }}">{{ $pendaftar->email }}</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-phone text-success me-2" style="width: 16px;"></i>
                                        <small>{{ $pendaftar->no_hp }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($pendaftar->status == 'pending')
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-semibold">
                                        <i class="fas fa-clock me-1"></i>Pending
                                    </span>
                                @elseif($pendaftar->status == 'diterima')
                                    <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-semibold">
                                        <i class="fas fa-check-circle me-1"></i>Diterima
                                    </span>
                                @else
                                    <span class="badge bg-danger text-white px-3 py-2 rounded-pill fw-semibold">
                                        <i class="fas fa-times-circle me-1"></i>Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="date-info">
                                    <div class="fw-semibold text-dark">{{ $pendaftar->created_at->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $pendaftar->created_at->format('H:i') }} WIB</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.pendaftar.show', $pendaftar) }}"
                                       class="btn btn-info btn-sm me-1" 
                                       title="Lihat Detail" 
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm" 
                                            title="Hapus Data"
                                            data-bs-toggle="tooltip"
                                            onclick="confirmDelete('{{ $pendaftar->id }}', '{{ $pendaftar->nama_lengkap }}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                
                                <!-- Hidden form for delete -->
                                <form id="delete-form-{{ $pendaftar->id }}" 
                                      action="{{ route('admin.pendaftar.destroy', $pendaftar) }}" 
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div class="empty-state">
                    <div class="empty-state-icon bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                         style="width: 120px; height: 120px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <i class="fas fa-users fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-2">Belum Ada Pendaftar</h4>
                    <p class="text-muted mb-0 lead">Belum ada mahasiswa yang mendaftar beasiswa saat ini</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<!-- Custom CSS -->
<style>
/* DataTables Enhancements */
.dataTables_wrapper {
    padding: 1.5rem;
}

.dataTables_filter {
    margin-bottom: 1rem;
}

.dataTables_filter input {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    width: 320px;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
}

.dataTables_filter input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    background: #fff;
}

.dataTables_length select {
    border: 2px solid #e9ecef;
    border-radius: 6px;
    padding: 0.4rem 2rem 0.4rem 0.75rem;
    background-position: right 0.75rem center;
}

.dataTables_info {
    color: #6c757d;
    font-weight: 500;
}

.dataTables_paginate .page-link {
    border-radius: 6px;
    margin: 0 2px;
    font-weight: 500;
}

.dataTables_paginate .page-item.active .page-link {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border-color: #007bff;
}

/* Table Enhancements */
#pendaftarTable {
    border-collapse: separate;
    border-spacing: 0;
}

#pendaftarTable thead th {
    background: linear-gradient(135deg, #343a40 0%, #495057 100%);
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    border: none;
    padding: 1.25rem 0.75rem;
    position: relative;
}

#pendaftarTable tbody td {
    vertical-align: middle;
    padding: 1.25rem 0.75rem;
    border-bottom: 1px solid #f8f9fa;
}

#pendaftarTable tbody tr {
    transition: all 0.2s ease;
}

#pendaftarTable tbody tr:hover {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.table-striped tbody tr:nth-of-type(odd) {
    background: rgba(0,0,0,0.02);
}

/* Stats Cards */
.stats-card {
    border: none;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.stats-card.bg-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
}

.stats-card.bg-warning {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%) !important;
}

.stats-card.bg-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.stats-card.bg-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
}

.stats-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}

/* Avatar Enhancements */
.avatar-initial {
    font-weight: 700;
    transition: all 0.3s ease;
}

.avatar-wrapper:hover .avatar-initial {
    transform: scale(1.1);
}

/* Badge Improvements */
.badge {
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Button Enhancements */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.btn-group .btn {
    border-radius: 6px !important;
    margin: 0 1px;
}

/* Contact Info */
.contact-info {
    line-height: 1.6;
}

.contact-info i {
    opacity: 0.8;
}

/* Date Info */
.date-info {
    line-height: 1.3;
}

/* Beasiswa Info */
.beasiswa-info h6 {
    line-height: 1.3;
}

/* Empty State */
.empty-state {
    padding: 4rem 2rem;
}

.empty-state-icon {
    transition: all 0.5s ease;
}

.empty-state:hover .empty-state-icon {
    transform: scale(1.1) rotate(5deg);
}

/* Card Enhancements */
.card {
    border-radius: 12px;
    box-shadow: 0 4px 25px rgba(0,0,0,0.08);
    border: none;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
    border-bottom: 2px solid #f8f9fa;
}

/* Filter Dropdown */
.dropdown-toggle::after {
    margin-left: 0.5rem;
}

.dropdown-menu {
    border-radius: 8px;
    box-shadow: 0 4px 25px rgba(0,0,0,0.15);
    border: none;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .dataTables_wrapper {
        padding: 1rem;
    }
    
    .dataTables_filter input {
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .stats-card .card-body {
        padding: 1.5rem 1rem;
    }
    
    .avatar-initial {
        width: 35px !important;
        height: 35px !important;
        font-size: 14px !important;
    }
    
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        margin: 1px 0;
        width: 100%;
    }
    
    .contact-info small {
        max-width: 120px !important;
    }
}

/* Loading Animation */
.dataTables_processing {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    border-radius: 8px;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Fade-in Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.fade-in {
    animation: fadeIn 0.6s ease-out;
}

.stats-card {
    animation: fadeIn 0.8s ease-out backwards;
}

/* Custom Scrollbar */
.dataTables_scrollBody::-webkit-scrollbar {
    height: 6px;
    width: 6px;
}

.dataTables_scrollBody::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.dataTables_scrollBody::-webkit-scrollbar-thumb {
    background: #007bff;
    border-radius: 3px;
}

.dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
    background: #0056b3;
}

/* Status Filter Highlight */
.filter-active {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
    color: white !important;
}
</style>

<!-- jQuery & DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable with enhanced features
    var table = $('#pendaftarTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json",
            "search": "Cari Pendaftar:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty": "Tidak ada data yang tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            },
            "emptyTable": "Tidak ada data yang tersedia di tabel",
            "zeroRecords": "Tidak ada data yang cocok dengan pencarian"
        },
        "responsive": true,
        "pageLength": 10,
        "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
        "order": [[1, "asc"]],
        "columnDefs": [
            {
                "targets": [0, 6], // No and Action columns
                "orderable": false,
                "searchable": false,
                "className": "text-center"
            },
            {
                "targets": [4, 5], // Status and Date columns
                "className": "text-center"
            }
        ],
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
               '<"row"<"col-sm-12"tr>>' +
               '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        "processing": true,
        "stateSave": true,
        "initComplete": function() {
            // Add fade-in animation
            $('#pendaftarTable').addClass('fade-in');
            
            // Initialize tooltips after table is loaded
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        "drawCallback": function() {
            // Reinitialize tooltips after each draw
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });
    
    // Status filter functionality
    $('.filter-status').on('click', function(e) {
        e.preventDefault();
        var status = $(this).data('status');
        
        // Update active filter
        $('.filter-status').removeClass('filter-active');
        $(this).addClass('filter-active');
        
        // Apply filter
        if (status === '') {
            table.column(4).search('').draw();
        } else {
            table.column(4).search(status).draw();
        }
    });
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Add smooth animations to stats cards
    $('.stats-card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
    });
});

// Enhanced delete confirmation
function confirmDelete(pendaftarId, namaPendaftar) {
    if (confirm(`Apakah Anda yakin ingin menghapus data pendaftar "${namaPendaftar}"?\n\nData yang telah dihapus tidak dapat dikembalikan!`)) {
        document.getElementById('delete-form-' + pendaftarId).submit();
    }
}

// Export table to Excel functionality
function exportTable() {
    var table = $('#pendaftarTable').DataTable();
    var data = table.rows({ search: 'applied' }).data();
    
    // Create CSV content
    var csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "No,Nama Lengkap,NIM,Beasiswa,Dana,Email,No HP,Status,Tanggal Daftar\n";
    
    data.each(function(row, index) {
        // Extract data from HTML elements
        var nama = $(row[1]).find('h6').text();
        var nim = $(row[1]).find('small').text().replace('', '').trim();
        var beasiswa = $(row[2]).find('h6').text();
        var dana = $(row[2]).find('small').text().replace('Rp ', '').trim();
        var email = $(row[3]).find('small').first().text();
        var hp = $(row[3]).find('small').last().text();
        var status = $(row[4]).find('.badge').text().trim();
        var tanggal = $(row[5]).find('.fw-semibold').text();
        
        csvContent += `${index + 1},"${nama}","${nim}","${beasiswa}","${dana}","${email}","${hp}","${status}","${tanggal}"\n`;
    });
    
    // Download file
    var encodedUri = encodeURI(csvContent);
    var link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "data_pendaftar_" + new Date().toISOString().split('T')[0] + ".csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Add loading animation for page transitions
$(document).on('click', 'a:not([href^="#"])', function() {
    const $this = $(this);
    if (!$this.hasClass('no-loading') && !$this.hasClass('dropdown-item')) {
        $this.html('<i class="fas fa-spinner fa-spin me-1"></i>' + $this.text());
    }
});
</script>
@endsection