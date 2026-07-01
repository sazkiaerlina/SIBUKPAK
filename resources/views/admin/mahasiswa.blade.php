@extends('layouts.admin')

@section('content')

@if(session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif

<h1 class="mb-4">
    Data mahasiswa
</h1>

<div class="card card-dashboard">

    <div class="card-body">

        <table class="table table-bordered table-hover">

            <thead class="table-primary">

                <tr>

                    <th width="10%">No</th>

                    <th>NIM</th>

                    <th>Nama Mahasiswa</th>

                    <th>Role</th>

                    <th width="20%">Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($mahasiswas as $item)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $item->nim }}</td>

                    <td>{{ $item->user->name }}</td>

                    <td>{{ ucfirst($item->user->role) }}</td>

                    <td>

                        <a href="{{ url('/admin/mahasiswa/'.$item->id) }}"
                        class="btn btn-info btn-sm">

                            Detail
                        </a>

                        <a
                        href="{{ url('/admin/mahasiswa/'.$item->id.'/edit') }}"
                        class="btn btn-warning btn-sm">

                        Edit

                        </a>

<form
action="{{ url('/admin/mahasiswa/'.$item->id) }}"
method="POST"
class="d-inline form-hapus">

    @csrf
    @method('DELETE')

    <button
    type="submit"
    class="btn btn-danger btn-sm">

        Hapus

    </button>

</form>




                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="text-center">
                        Belum ada data mahasiswa.
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection

@push('scripts')

<script>

document.querySelectorAll('.form-hapus').forEach(function(form){

    form.addEventListener('submit', function(e){

        e.preventDefault();

        Swal.fire({

            title: 'Hapus Mahasiswa?',

            text: 'Data mahasiswa beserta seluruh presensinya akan dihapus.',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#0d6efd',

            cancelButtonColor: '#6c757d',

            confirmButtonText: 'Ya, Hapus',

            cancelButtonText: 'Tidak'

        }).then((result)=>{

            if(result.isConfirmed){

                form.submit();

            }

        });

    });

});

</script>

@endpush