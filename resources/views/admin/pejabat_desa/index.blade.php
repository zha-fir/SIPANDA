@extends('layouts.admin')
@section('title', 'Manajemen Pejabat Desa')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Pejabat Desa</h6>
    </div>
    <div class="card-body">
        <a href="{{ route('pejabat-desa.create') }}" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Tambah Pejabat</a>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead> <tr> <th>ID</th> <th>Nama Pejabat</th> <th>Jabatan</th> <th>Aksi</th> </tr> </thead>
                <tbody>
                    @forelse ($pejabatList as $item)
                    <tr>
                        <td>{{ $item->id_pejabat_desa }}</td>
                        <td>{{ $item->nama_pejabat }}</td>
                        <td>{{ $item->jabatan }}</td>
                        <td>
                            <a href="{{ route('pejabat-desa.edit', $item->id_pejabat_desa) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('pejabat-desa.destroy', $item->id_pejabat_desa) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center">Data kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection