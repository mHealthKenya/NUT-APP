@section('page-css')
@extends('layouts.master')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
  <div class="breadcrumb">
                <h1>Messages</h1>
            </div>
            <div class="separator-breadcrumb border-top"></div>

            <div class="row mb-4">
            

                <div class="col-md-12 mb-4">
                    <div class="card text-left">

                        <div id ="ClientsTable" class="card-body viewclients">
                            <h4 class="card-title mb-3">View Messages</h4>
                            <span class="label label-info pull-right" id="totalspan">{{count($client)}} Message(s)</span>
                                <div class="table-responsive">
                                    <table id="hidden_column_table1" class="display table table-striped table-bordered" style="width:100%">
                                        <thead>
                                                     <tr>
                                                <th>No.</th>  
                                                <th>Message</th>
                                                <th>Destination</th>
                                                <th>Send Date</th>
                                                <th>Status</th>
                                                <th>Response</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                            @foreach($client as $clinician_)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>

                                                    <td>{{$clinician_->message}}</td>
                                                    <td>{{$clinician_->destination}}</td>
                                                    {{-- <td>@if(!empty($clinician_->facility_id)){{$clinician_->master_facility->name}}@endif</td> --}}
                                                    {{-- <td>{{$clinician_->facility_id['name']}}</td> --}}
                                                    {{-- <td>{{$clinician_->email}}</td> --}}
                                                    {{-- <td>@if(!empty($clinician_->facility_id)){{$clinician_->master_facility->name}}@endif</td> --}}
                                                    <td>{{$clinician_->send_date}}</td>
                                                    <td>@if($clinician_->status == 0) Not Sent @elseif($clinician_->status == 1) Sent @endif</td>
                                                    <td>{{$clinician_->response}}</td>
                                                    
                                                </tr>
                                            @endforeach
                                            </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    
                                        
                                
                                <!-- end of col -->
                
        <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
           <script src="{{asset('assets/js/datatables.script.js')}}"></script>
       
       @endsection