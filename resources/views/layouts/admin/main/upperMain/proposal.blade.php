<!-- Card Proposal Diterima -->
<div class="card card-primary card-round">
    <div class="card-header">
        <div class="card-head-row d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center">
                <h4 class="card-title mb-1 mb-md-0 me-md-3">Proposal Diterima</h4>
                <span class="card-category text-muted small" id="dateLabel">Belum ada tanggal dipilih</span>
            </div>
            <div class="card-tools d-flex align-items-center gap-2 flex-wrap">
                <div class="input-group input-group-sm" style="min-width: 240px;">
                    <input type="text" id="dateRange" class="form-control" placeholder="Pilih rentang tanggal">
                    <button class="btn btn-primary" id="searchDate" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-label-light dropdown-toggle" type="button"
                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        Export
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#">Excel</a>
                        <a class="dropdown-item" href="#">CSV</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body pb-0">
        <div class="mb-4 mt-2">
            <h1>120</h1>
        </div>
    </div>
</div>

<!-- Litepicker Styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>

<!-- Date Picker Script -->
<script>
    let pickerStart = null;
    let pickerEnd = null;

    const dateInput = document.getElementById('dateRange');
    const dateLabel = document.getElementById('dateLabel');

    const picker = new Litepicker({
        element: dateInput,
        singleMode: false,
        format: 'DD MMM YYYY',
        onSelect: (start, end) => {
            pickerStart = start;
            pickerEnd = end;
            updateDateLabel(start, end);
        }
    });

    function updateDateLabel(start, end) {
        if (start && end) {
            const label = `${start.format('D MMM')} - ${end.format('D MMM YYYY')}`;
            dateLabel.innerText = label;
        }
    }

    document.getElementById('searchDate').addEventListener('click', () => {
        const range = dateInput.value.trim();

        if (!pickerStart || !pickerEnd) {
            if (range.includes(' - ')) {
                const [startStr, endStr] = range.split(' - ');
                pickerStart = dayjs(startStr, 'DD MMM YYYY');
                pickerEnd = dayjs(endStr, 'DD MMM YYYY');

                if (pickerStart.isValid() && pickerEnd.isValid()) {
                    updateDateLabel(pickerStart, pickerEnd);
                } else {
                    alert("Format tanggal tidak valid.");
                    return;
                }
            } else {
                alert("Silakan pilih rentang tanggal yang valid.");
                return;
            }
        }

        // Simulasi pencarian
        console.log("Tanggal dipilih:", pickerStart?.format(), "-", pickerEnd?.format());

        // Bisa tambahkan logic filter data di sini
    });
</script>
