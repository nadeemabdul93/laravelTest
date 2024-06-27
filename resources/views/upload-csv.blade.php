@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Sort Csv') }}</div>
                @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                <p>{{ session('path') }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
                <div class="card-body">
                    <form method="POST" action="{{ route('sort-csv')}}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="sort-csv" class="col-md-4 col-form-label text-md-end">Upload Csv</label>

                            <div class="col-md-6">
                                
                                <input type="file" class="form-control" name="csv_file" required>

                            </div>
                        </div>                      

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4"> 
                                <button type="submit" class="btn btn-primary">Upload and Sort CSV</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
