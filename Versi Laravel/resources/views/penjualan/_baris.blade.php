{{-- Satu baris barang pada form penjualan. $n = nomor baris, $it = isian lama (jika ada) --}}
<tr>
    <td>
        <select name="items[{{ $n }}][idbarang]" required>
            <option value="">-- pilih barang --</option>
            @foreach ($barang as $b)
                <option value="{{ $b->idbarang }}" {{ $b->stok < 1 ? 'disabled' : '' }}
                    {{ (string) ($it['idbarang'] ?? '') === (string) $b->idbarang ? 'selected' : '' }}>
                    {{ $b->nama }} ({{ $b->nama_satuan }}) - Rp {{ number_format($b->harga_jual, 0, ',', '.') }} - stok {{ $b->stok }}
                </option>
            @endforeach
        </select>
    </td>
    <td><input type="number" name="items[{{ $n }}][jumlah]" min="1" value="{{ $it['jumlah'] ?? 1 }}" required></td>
    <td><button type="button" class="btn-hapus hapus-baris">Hapus</button></td>
</tr>
