@props([
    'id',
    'labelledby',
    'isActive' => false,
    'columns', // ✅ PERBAIKAN: Nama variabel diubah dari 'headers' menjadi 'columns'
    'tableData',
    'partial',
])

<div class="tab-pane fade {{ $isActive ? 'show active' : '' }}" id="{{ $id }}" role="tabpanel"
    aria-labelledby="{{ $labelledby }}">
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    {{-- ✅ PERBAIKAN: Loop sekarang menggunakan variabel $columns --}}
                    @foreach ($columns as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                {{-- Mengirim data dengan nama variabel generik 'collection' --}}
                @include($partial, ['collection' => $tableData])
            </tbody>
        </table>
    </div>

    {{-- Menampilkan link paginasi --}}
    @if ($tableData->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $tableData->links() }}
        </div>
    @endif
</div>
