@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="mhs/cari" method="get" class="form-inline">
                        <input type="search" name="q" class="form-control form-control-sm">
                        <input type="submit" value="Cari" class="btn btn-sm btn-primary">
                    </form>
                    
                    <a href="mhs/tambah">Tambah</a>
                    <table class="table">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <!--<th>Password</th>-->
                            <th>Action</th>
                        </tr>
                        @foreach($mhs as $n)
                        <tr>
                            <td>{{ $n['id'] }}</td>
                            <td>{{ $n['name'] }}</td>
                            <td>{{ $n['email'] }}</td>
                            <!--<td>{{ $n['password'] }}</td>-->
                            <td>
                                <a href="mhs/edit/{{ $n['id'] }}">Edit</a>
                                <a href="mhs/delete/{{ $n['id'] }}">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    </table>
    
                    <table>
                        <tr>
                            <td>Current Page: {{ $mhs->currentPage() }}</td>
                        </tr>
                        <tr>
                            <td>Total: {{ $mhs->total() }}</td>
                        </tr>
                        <tr>
                            <td>PerPage: {{ $mhs->perPage() }}</td>
                        </tr>
                            <td>Link: {{ $mhs->links() }}</td>
                        </tr>
                    </table>
    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

