@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Download Data</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        
                        <div class="table-responsive">
                          <table class="table table-striped table-hover table-condensed">
                            <thead>
                              <tr>
                                  <th><strong>Report No.</strong></th>
                                <th><strong>Merchant ID</strong></th>
                                <th><strong>Reports</strong></th>
                                <th><strong>Download</strong></th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach($dataArray as $data)
                                
                                    <tr>    
                                      <td>{{ $data['id'] }}</td>
                                      <td>{{ $data['merchant_id'] }}</td>
                                      <td>{{ $data['date_added'] }}</td>          
                                      <td><a href="{{ $data['download_path'] }}">{{ $data['download_path'] }}</td> 
                                    </tr>
                                @endforeach
                            </tbody>
                          </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
