@props(['id', 'collection', 'headers', 'partial', 'isActive' => false])

<div class="tab-pane fade {{ $isActive ? 'show active' : '' }}" id="{{ $id }}" role="tabpanel"
    aria-labelledby="{{ $id }}-tab">
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    @foreach ($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @include($partial, [
                    'collection' => $collection,
                    'type' => $id,
                    'headers' => $headers,
                ])
            </tbody>
        </table>
    </div>

    @if ($collection->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $collection->links() }}
        </div>
    @endif
</div>
