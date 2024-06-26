@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        
        @endif
        @if (session('success'))
        <div class="alert alert-success">
                <span> File Successfully downloaded</span>
            </div>
        @endif
            <form method="POST" action="{{ route('upload') }}" enctype="multipart/form-data">
                @csrf

                <div class="row mb-3">
                    <label for="sort-csv" class="col-md-4 col-form-label text-md-end"><h3>Upload File</h3></label>

                    <div class="col-md-6">                        
                        <input type="file" class="form-control" name="file" required>
                    </div>
                </div>                      

                <div class="row mb-0">
                    <div class="col-md-8 offset-md-4"> 
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            
            <h1>Files</h1>

            @if (auth()->user()->files->count() > 0)
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Filename</th>
                            <th>Type</th>
                            <th>Size</th>
                            
                            <th colspan="2">Action</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (auth()->user()->files as $file)
                            <tr>
                                <td>
                                    {{$file->id}}
                                </td>
                                <td>
                                    {{$file->filename}}
                                </td>
                                <td>
                                    {{$file->file_type}}
                                </td>
                                <td>
                                    {{$file->file_size}}
                                </td>
                                <td>
                                <button class="btn btn-success" onclick="generateLink({{ $file->id }})">Generate Link</button>

                                </td>
                                <td>
                                <button class="btn btn-danger"><a href="{{url('files/'. $file->id)}}" style=" color:white">Download</a> </button>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                </table>
            @else
                <p>No files uploaded yet.</p>
            @endif
    
        </div>
    </div>
        
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
    function generateLink(fileId) {
        $.ajax({
            url: "{{ route('files.generate-link') }}",
            type: 'POST',
            data: {
                file_id: fileId,
                _token: '{{ csrf_token() }}' // Ensure to include CSRF token for Laravel
            },
            success: function(response) {
                var link = response.link; // Use the link from the response
                var textarea = document.createElement("textarea");
                textarea.value = link; // Set the textarea value to the link
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand("copy");
                document.body.removeChild(textarea);

                // Show toast notification
                Toastify({
                    text: "Link copied to clipboard: " + link,
                    duration: 3000, // Toast duration in milliseconds (3 seconds)
                    gravity: "top", // Display location (other options: 'top', 'bottom')
                    position: 'center', // Toast position (other options: 'left', 'center', 'right')
                    close: true // Whether to add a close button
                }).showToast();
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(error);
                alert('Error generating link. Please try again.');
            }
        });
    }
    

</script>
@endsection