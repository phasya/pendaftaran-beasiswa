@extends('layouts.admin')

@section('title', 'Kelola Pendaftar')

@section('content')
    <div class="flex justify-between flex-wrap md:flex-nowrap items-center pt-3 pb-2 mb-3 border-b">
        <h1 class="text-2xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-chart-bar mr-2 text-orange-500"></i>
            Kelola Pendaftar
        </h1>
        <div class="flex items-center space-x-2 mt-2 md:mt-0">
            <div class="relative">
                <button
                    class="px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-1 transition-all duration-200 shadow-sm flex items-center"
                    type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-filter mr-2"></i>
                    Filter Status
                    <i class="fas fa-chevron-down ml-2 text-sm"></i>
                </button>
                <ul class="dropdown-menu bg-white border border-gray-200 rounded-lg shadow-lg mt-1 py-1 min-w-max">
                    <li><a class="dropdown-item filter-status px-4 py-2 hover:bg-orange-50 hover:text-orange-600 transition-colors duration-150 cursor-pointer"
                            data-status="">Semua Status</a></li>
                    <li><a class="dropdown-item filter-status px-4 py-2 hover:bg-orange-50 hover:text-orange-600 transition-colors duration-150 cursor-pointer"
                            data-status="pending">Pending</a></li>
                    <li><a class="dropdown-item filter-status px-4 py-2 hover:bg-orange-50 hover:text-orange-600 transition-colors duration-150 cursor-pointer"
                            data-status="diterima">Diterima</a></li>
                    <li><a class="dropdown-item filter-status px-4 py-2 hover:bg-orange-50 hover:text-orange-600 transition-colors duration-150 cursor-pointer"
                            data-status="ditolak">Ditolak</a></li>
                </ul>
            </div>
            <button
                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center"
                onclick="exportTable()">
                <i class="fas fa-file-excel mr-2"></i>
                Export Excel
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

    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <div class="bg-gradient-to-r from-gray-50 to-white border-b border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <h6 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-table mr-2 text-orange-500"></i>
                        Daftar Pendaftar
                        @if($pendaftars->total() > 0)
                            <span
                                class="ml-2 bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $pendaftars->total() }}
                                Total</span>
                        @endif
                    </h6>
                </div>
            </div>
        </div>

        <div class="p-0">
            @if($pendaftars->count() > 0)
                <!-- Advanced Search Section -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200 p-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="searchNama" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user text-orange-500 mr-1"></i>Cari Nama Pendaftar
                            </label>
                            <input type="text" id="searchNama"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white shadow-sm"
                                placeholder="Masukkan nama pendaftar..." autocomplete="off">
                        </div>
                        <div>
                            <label for="searchBeasiswa" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-graduation-cap text-blue-500 mr-1"></i>Cari Beasiswa
                            </label>
                            <input type="text" id="searchBeasiswa"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white shadow-sm"
                                placeholder="Masukkan nama beasiswa..." autocomplete="off">
                        </div>
                        <div>
                            <label for="searchKontak" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope text-green-500 mr-1"></i>Cari Kontak
                            </label>
                            <input type="text" id="searchKontak"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white shadow-sm"
                                placeholder="Email atau nomor HP..." autocomplete="off">
                        </div>
                        <div>
                            <label for="searchTanggal" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar text-purple-500 mr-1"></i>Cari Tanggal
                            </label>
                            <input type="text" id="searchTanggal"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white shadow-sm"
                                placeholder="DD MMM YYYY atau HH:MM..." autocomplete="off">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button onclick="clearAllSearch()"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all duration-200 shadow-sm text-sm flex items-center">
                            <i class="fas fa-times mr-2"></i>
                            Clear All
                        </button>
                    </div>
                </div>

                <div class="overflow-hidden">
                    <div class="overflow-x-auto">
                        <table id="pendaftarTable" class="w-full table-auto bg-white">
                            <thead class="bg-gradient-to-r from-orange-500 to-orange-600">
                                <tr>
                                    <th
                                        class="px-4 py-4 text-center font-bold text-white text-sm w-16 border-r border-orange-400">
                                        <i class="fas fa-hashtag mr-1"></i>No
                                    </th>
                                    <th
                                        class="px-4 py-4 text-left font-bold text-white text-sm min-w-56 border-r border-orange-400">
                                        <i class="fas fa-user mr-2"></i>Pendaftar
                                    </th>
                                    <th
                                        class="px-4 py-4 text-left font-bold text-white text-sm min-w-48 border-r border-orange-400">
                                        <i class="fas fa-graduation-cap mr-2"></i>Beasiswa
                                    </th>
                                    <th
                                        class="px-4 py-4 text-left font-bold text-white text-sm min-w-48 border-r border-orange-400">
                                        <i class="fas fa-envelope mr-2"></i>Kontak
                                    </th>
                                    <th
                                        class="px-4 py-4 text-center font-bold text-white text-sm w-32 border-r border-orange-400">
                                        <i class="fas fa-info-circle mr-2"></i>Status
                                    </th>
                                    <th
                                        class="px-4 py-4 text-center font-bold text-white text-sm w-36 border-r border-orange-400">
                                        <i class="fas fa-calendar mr-2"></i>Tanggal
                                    </th>
                                    <th class="px-4 py-4 text-center font-bold text-white text-sm w-32">
                                        <i class="fas fa-cogs mr-2"></i>Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pendaftars as $index => $pendaftar)
                                    <tr
                                        class="hover:bg-gradient-to-r hover:from-orange-25 hover:to-orange-50 transition-all duration-200 group">
                                        <td
                                            class="px-4 py-4 text-center text-sm font-semibold text-gray-600 border-r border-gray-100">
                                            <div
                                                class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mx-auto group-hover:bg-orange-100 transition-colors">
                                                {{ $index + 1 }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 border-r border-gray-100">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg mr-3 ring-2 ring-orange-100">
                                                    {{ strtoupper(substr($pendaftar->nama_lengkap, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-gray-900">{{ $pendaftar->nama_lengkap }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 flex items-center mt-1">
                                                        <i class="fas fa-id-card mr-1 text-gray-400"></i>{{ $pendaftar->nim }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 border-r border-gray-100">
                                            <div class="text-sm font-bold text-blue-600">{{ $pendaftar->beasiswa->nama_beasiswa }}
                                            </div>
                                            <div class="text-xs text-green-600 font-semibold mt-1 flex items-center">
                                                <i class="fas fa-money-bill-wave mr-1"></i>
                                                Rp {{ number_format($pendaftar->beasiswa->jumlah_dana, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 border-r border-gray-100">
                                            <div class="space-y-2">
                                                <div class="text-xs text-gray-700 flex items-center">
                                                    <i class="fas fa-envelope text-blue-500 mr-2 w-3"></i>
                                                    <span class="truncate max-w-44 font-medium"
                                                        title="{{ $pendaftar->email }}">{{ $pendaftar->email }}</span>
                                                </div>
                                                <div class="text-xs text-gray-700 flex items-center">
                                                    <i class="fas fa-phone text-green-500 mr-2 w-3"></i>
                                                    <span class="font-medium">{{ $pendaftar->no_hp }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center border-r border-gray-100">
                                            @if($pendaftar->status == 'pending')
                                                <span
                                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 shadow-sm">
                                                    <i class="fas fa-clock mr-1"></i>Pending
                                                </span>
                                            @elseif($pendaftar->status == 'diterima')
                                                <span
                                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-800 shadow-sm">
                                                    <i class="fas fa-check-circle mr-1"></i>Diterima
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-800 shadow-sm">
                                                    <i class="fas fa-times-circle mr-1"></i>Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-center border-r border-gray-100">
                                            <div class="text-sm font-bold text-gray-900">
                                                {{ $pendaftar->created_at->format('d M Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $pendaftar->created_at->format('H:i') }} WIB</div>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('admin.pendaftar.show', $pendaftar) }}"
                                                    class="inline-flex items-center justify-center w-9 h-9 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                                                    title="Lihat Detail" data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                                <button type="button"
                                                    class="inline-flex items-center justify-center w-9 h-9 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                                                    title="Hapus Data" data-bs-toggle="tooltip"
                                                    onclick="confirmDelete('{{ $pendaftar->id }}', '{{ $pendaftar->nama_lengkap }}')">
                                                    <i class="fas fa-trash-alt text-sm"></i>
                                                </button>
                                            </div>

                                            <!-- Hidden form for delete -->
                                            <form id="delete-form-{{ $pendaftar->id }}"
                                                action="{{ route('admin.pendaftar.destroy', $pendaftar) }}" method="POST"
                                                class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="text-center py-20">
                    <div class="flex flex-col items-center">
                        <div
                            class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6 shadow-inner">
                            <i class="fas fa-users text-5xl text-gray-400"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-600 mb-2">Belum Ada Pendaftar</h4>
                        <p class="text-gray-500 max-w-md">Belum ada mahasiswa yang mendaftar beasiswa saat ini. Data akan muncul
                            setelah ada pendaftaran.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <!-- Custom DataTables Styles -->
    <style>
        /* Remove default DataTables styling conflicts */
        #pendaftarTable_wrapper {
            @apply bg-white p-6;
        }

        #pendaftarTable_wrapper .dataTables_length {
            @apply mb-4;
        }

        #pendaftarTable_wrapper .dataTables_length label {
            @apply text-sm font-semibold text-gray-700 flex items-center;
        }

        #pendaftarTable_wrapper .dataTables_length select {
            @apply ml-2 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white shadow-sm;
        }

        #pendaftarTable_wrapper .dataTables_info {
            @apply text-sm font-medium text-gray-600 flex items-center;
        }

        #pendaftarTable_wrapper .dataTables_info::before {
            content: "\f05a";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            @apply mr-2 text-blue-500;
        }

        #pendaftarTable_wrapper .dataTables_paginate {
            @apply mt-4;
        }

        #pendaftarTable_wrapper .dataTables_paginate .paginate_button {
            @apply px-3 py-2 mx-1 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-orange-50 hover:text-orange-600 hover:border-orange-300 transition-all duration-200 shadow-sm;
        }

        #pendaftarTable_wrapper .dataTables_paginate .paginate_button.current {
            @apply bg-orange-500 text-white border-orange-500 hover:bg-orange-600 hover:text-white shadow-md;
        }

        #pendaftarTable_wrapper .dataTables_paginate .paginate_button.disabled {
            @apply text-gray-400 bg-gray-50 border-gray-200 cursor-not-allowed hover:bg-gray-50 hover:text-gray-400 hover:border-gray-200;
        }

        /* Hide default search */
        #pendaftarTable_wrapper .dataTables_filter {
            @apply hidden;
        }

        /* Processing indicator */
        #pendaftarTable_wrapper .dataTables_processing {
            @apply bg-orange-500 text-white rounded-lg border-0 shadow-lg font-semibold;
        }

        /* Table styling */
        #pendaftarTable {
            border-collapse: separate;
            border-spacing: 0;
        }

        #pendaftarTable tbody tr:nth-child(even) {
            @apply bg-gray-25;
        }

        /* Custom scrollbar */
        .dataTables_scrollBody::-webkit-scrollbar {
            @apply h-2 w-2;
        }

        .dataTables_scrollBody::-webkit-scrollbar-track {
            @apply bg-gray-100 rounded;
        }

        .dataTables_scrollBody::-webkit-scrollbar-thumb {
            @apply bg-orange-400 rounded hover:bg-orange-500;
        }

        /* Filter active state */
        .filter-active {
            @apply bg-orange-500 text-white;
        }

        /* Loading overlay */
        .dataTables_wrapper .dataTables_processing {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            @apply bg-orange-500 text-white px-6 py-3 rounded-lg shadow-xl font-semibold;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {

            #pendaftarTable_wrapper .dataTables_length,
            #pendaftarTable_wrapper .dataTables_info,
            #pendaftarTable_wrapper .dataTables_paginate {
                @apply text-center;
            }

            #pendaftarTable_wrapper .dataTables_length label {
                @apply justify-center;
            }

            .dataTables_paginate .paginate_button {
                @apply px-2 py-1 text-xs;
            }
        }
    </style>

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable with enhanced configuration
            var table = $('#pendaftarTable').DataTable({
                "language": {
                    "decimal": "",
                    "emptyTable": "Tidak ada data yang tersedia di tabel",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "infoFiltered": "(difilter dari _MAX_ total entri)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "loadingRecords": "Memuat...",
                    "processing": "Memproses...",
                    "search": "Cari:",
                    "zeroRecords": "Tidak ada catatan yang cocok ditemukan",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "responsive": true,
                "pageLength": 10,
                "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                "order": [[1, "asc"]],
                "columnDefs": [
                    {
                        "targets": [0, 6], // No and Action columns
                        "orderable": false,
                        "searchable": false
                    },
                    {
                        "targets": [4, 5], // Status and Date columns
                        "className": "text-center"
                    }
                ],
                "processing": true,
                "stateSave": false,
                "dom": '<"flex justify-between items-center mb-4"<"flex items-center"l><"">>' +
                    '<"overflow-hidden rounded-lg border border-gray-200"t>' +
                    '<"flex justify-between items-center mt-4"<"flex items-center"i><"flex items-center"p>>',
                "initComplete": function () {
                    // Initialize tooltips
                    $('[data-bs-toggle="tooltip"]').tooltip();

                    // Add icons to length menu
                    $('.dataTables_length label').prepend('<i class="fas fa-list mr-2 text-orange-500"></i>');
                },
                "drawCallback": function () {
                    // Reinitialize tooltips after each draw
                    $('[data-bs-toggle="tooltip"]').tooltip();
                }
            });

            // Status filter functionality
            $('.filter-status').on('click', function (e) {
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

            // Advanced search functionality with debouncing
            let searchTimeout;

            function setupSearch(inputId, columnIndex) {
                $('#' + inputId).on('input', function () {
                    clearTimeout(searchTimeout);
                    const value = this.value;
                    searchTimeout = setTimeout(function () {
                        table.column(columnIndex).search(value).draw();
                    }, 300);
                });
            }

            // Setup all search inputs
            setupSearch('searchNama', 1);     // Nama pendaftar
            setupSearch('searchBeasiswa', 2); // Beasiswa
            setupSearch('searchKontak', 3);   // Kontak
            setupSearch('searchTanggal', 5);  // Tanggal

            // Clear all searches function
            window.clearAllSearch = function () {
                $('#searchNama, #searchBeasiswa, #searchKontak, #searchTanggal').val('');
                table.columns().search('').draw();
                $('.filter-status').removeClass('filter-active');
                $('.filter-status[data-status=""]').addClass('filter-active');
            };

            // Keyboard shortcuts
            $(document).keydown(function (e) {
                if (e.keyCode === 27) { // ESC key
                    clearAllSearch();
                }
            });

            // Auto-focus first search input
            $('#searchNama').focus();
        });

        // Enhanced delete confirmation with SweetAlert style
        function confirmDelete(pendaftarId, namaPendaftar) {
            if (confirm(`⚠️ PERINGATAN!\n\nApakah Anda yakin ingin menghapus data pendaftar "${namaPendaftar}"?\n\n❌ Data yang telah dihapus tidak dapat dikembalikan!\n✅ Klik OK untuk melanjutkan\n❌ Klik Cancel untuk membatalkan`)) {
                // Show loading state
                const button = event.target.closest('button');
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;

                // Submit form
                document.getElementById('delete-form-' + pendaftarId).submit();
            }
        }

        // Enhanced export functionality
        function exportTable() {
            const exportButton = event.target;
            const originalHTML = exportButton.innerHTML;

            // Show loading state
            exportButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengexport...';
            exportButton.disabled = true;

            try {
                var table = $('#pendaftarTable').DataTable();
                var data = table.rows({ search: 'applied' }).data();

                // Create CSV content with BOM for proper Excel encoding
                var csvContent = "\uFEFF"; // UTF-8 BOM
                csvContent += "No,Nama Lengkap,NIM,Beasiswa,Dana Beasiswa,Email,No HP,Status,Tanggal Daftar,Waktu Daftar\n";

                data.each(function (row, index) {
                    // Extract data more reliably
                    var $row = $(row);

                    var nama = $($row[1]).find('.text-sm.font-bold').first().text().trim();
                    var nim = $($row[1]).find('.text-xs').first().text().replace(/.*\s/, '').trim();
                    var beasiswa = $($row[2]).find('.text-sm.font-bold').first().text().trim();
                    var dana = $($row[2]).find('.text-xs').first().text().replace('Rp ', '').replace(/\./g, '').trim();
                    var email = $($row[3]).find('span.truncate').first().text().trim();
                    var hp = $($row[3]).find('.text-xs').last().find('span.font-medium').text().trim() ||
                        $($row[3]).text().match(/\d{10,}/)?.[0] || '';
                    var status = $($row[4]).find('span').first().text().replace(/.*\s/, '').trim();
                    var tanggal = $($row[5]).find('.text-sm.font-bold').first().text().trim();
                    var waktu = $($row[5]).find('.text-xs').first().text().replace(' WIB', '').trim();

                    // Clean and escape data
                    nama = nama.replace(/"/g, '""');
                    beasiswa = beasiswa.replace(/"/g, '""');
                    email = email.replace(/"/g, '""');

                    csvContent += `${index + 1},"${nama}","${nim}","${beasiswa}","${dana}","${email}","${hp}","${status}","${tanggal}","${waktu}"\n`;
                });

                // Create and download file
                var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                var link = document.createElement("a");

                if (link.download !== undefined) {
                    var url = URL.createObjectURL(blob);
                    link.setAttribute("href", url);
                    link.setAttribute("download", `data_pendaftar_${new Date().toISOString().split('T')[0]}.csv`);
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    // Show success message
                    setTimeout(() => {
                        alert('✅ Data berhasil diexport ke Excel!\n\n📁 File telah disimpan ke folder Download Anda.');
                    }, 500);
                } else {
                    alert('❌ Browser Anda tidak mendukung download file.\nSilakan gunakan browser yang lebih baru.');
                }

            } catch (error) {
                console.error('Export error:', error);
                alert('❌ Terjadi kesalahan saat mengexport data.\nSilakan coba lagi atau hubungi administrator.');
            } finally {
                // Restore button state
                setTimeout(() => {
                    exportButton.innerHTML = originalHTML;
                    exportButton.disabled = false;
                }, 1000);
            }
        }

        // Add search highlighting
        function highlightSearchTerm(text, term) {
            if (!term) return text;
            const regex = new RegExp(`(${term})`, 'gi');
            return text.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
        }

        // Enhanced table interactions
        $('#pendaftarTable tbody').on('mouseenter', 'tr', function () {
            $(this).find('.transform').addClass('scale-105');
        }).on('mouseleave', 'tr', function () {
            $(this).find('.transform').removeClass('scale-105');
        });

        // Add loading animation for page navigation
        $(document).on('click', 'a:not([href^="#"]):not(.no-loading)', function (e) {
            const $link = $(this);
            if (!$link.hasClass('dropdown-item')) {
                const originalText = $link.html();
                $link.html('<i class="fas fa-spinner fa-spin mr-1"></i>' + $link.text());

                // Restore after a delay if navigation doesn't occur
                setTimeout(() => {
                    $link.html(originalText);
                }, 3000);
            }
        });

        // Initialize tooltips with enhanced options
        $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip({
                trigger: 'hover focus',
                delay: { show: 300, hide: 100 },
                template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner bg-gray-900 text-white text-xs px-2 py-1 rounded shadow-lg"></div></div>'
            });
        });

        // Add smooth scroll to top functionality
        window.scrollToTop = function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        // Show scroll to top button when needed
        $(window).scroll(function () {
            if ($(this).scrollTop() > 300) {
                if ($('#scrollToTop').length === 0) {
                    $('body').append(`
                            <button id="scrollToTop" 
                                    onclick="scrollToTop()" 
                                    class="fixed bottom-4 right-4 w-12 h-12 bg-orange-500 hover:bg-orange-600 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 z-50">
                                <i class="fas fa-arrow-up"></i>
                            </button>
                        `);
                }
            } else {
                $('#scrollToTop').remove();
            }
        });
    </script>
@endsection