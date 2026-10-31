{{-- Satu baris barang pada form pengadaan. $n = nomor baris, $it = isian lama (jika ada) --}}
<tr>
    <td>
        <select name="items[{{ $n }}][idbarang]" class="pilih-barang" required>
            <option value="">-- pilih barang --</option>
            @foreach ($barang as $b)
                <option value="{{ $b->idbarang }}" data-harga="{{ $b->harga }}"
                    {{ (string) ($it['idbarang'] ?? '') === (string) $b->idbarang ? 'selected' : '' }}>
                    {{ $b->nama }} ({{ $b->nama_satuan }})
                </option>
            @endforeach
        </select>
    </td>
    <td><input type="number" name="items[{{ $n }}][jumlah]" min="1" value="{{ $it['jumlah'] ?? 1 }}" required></td>
    <td><input type="number" name="items[{{ $n }}][harga_satuan]" min="0" class="isi-harga" value="{{ $it['harga_satuan'] ?? '' }}" required></td>
    <td><button type="button" class="btn-hapus hapus-baris">Hapus</button></td>
</tr>
