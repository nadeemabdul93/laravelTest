@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <ul>
                <li class="sub-menu-item">               
                    <a href="{{route('files')}}">Uploaded files</a>
                </li>
                <li class="sub-menu-item">       
                        <a href="{{route('upload-csv')}}">Upload csv and sort </a>
                            
                </li>
            </ul>
        
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-striped table-sm">
                        <tr>
                            <th>Uploads</th>
                            <td>{{ auth()->user()->total_uploads }}</td>
                        </tr>
                        <tr>
                            <th>Downloads</th>
                            <td>{{ auth()->user()->total_downloads }}</td>
                        </tr>
                    </table>
                    
                </div>
                
            </div>

        </div>
        <div class="col-md-9">
            <div style="width: 50%; margin: auto;">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Downloads', 'Uploads'],
                datasets: [{
                    label: 'Total',
                    data: [{{ auth()->user()->total_downloads  }}, {{ auth()->user()->total_uploads }}],
                    backgroundColor: ['blue', 'green'],
                    borderColor: ['blue', 'green'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
