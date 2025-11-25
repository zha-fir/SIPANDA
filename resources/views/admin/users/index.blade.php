@extends('layouts.admin')
@section('title', 'Manajemen Akun Pengguna')
@section('content')

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengguna Sistem</h6>
        </div>
        <div class="card-body">
            <a href="{{ route('users.create') }}" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Tambah Akun</a>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Wilayah (Khusus Kadus)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->nama_lengkap }}</td>
                                <td>{{ $user->username }}</td>
                                <td>
                                    @if($user->role == 'admin') <span class="badge badge-danger">ADMIN</span>
                                    @elseif($user->role == 'kades') <span class="badge badge-primary">KEPALA DESA</span>
                                    @elseif($user->role == 'kadus') <span class="badge badge-success">KEPALA DUSUN</span>
                                    @else <span class="badge badge-secondary">WARGA</span> @endif
                                </td>
                                <td>{{ $user->dusun->nama_dusun ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('users.edit', $user->id_user) }}" class="btn btn-sm btn-warning"><i
                                            class="fas fa-edit"></i></a>
                                    @if($user->id_user != Auth::id())
                                        <form action="{{ route('users.destroy', $user->id_user) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Yakin hapus user ini?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection