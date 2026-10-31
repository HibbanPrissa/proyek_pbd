@php
    $kelas = in_array($label, ['Aktif', 'Selesai'], true) ? 'aktif' : ($label === 'Proses' ? 'proses' : 'nonaktif');
@endphp
<span class="badge {{ $kelas }}">{{ $label }}</span>
