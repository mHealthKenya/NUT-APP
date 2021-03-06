@section('page-css')
@extends('layouts.master')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
  <div class="breadcrumb">
                <h1>Care Giver</h1>
            </div>
            <div class="separator-breadcrumb border-top"></div>

            <div class="row mb-4">
            

                <div class="col-md-12 mb-4">
                    <div class="card text-left">

                        <div id ="ClientsTable" class="card-body viewclients">
                            <h4 class="card-title mb-3">View Care Givers</h4>
                            <span class="label label-info pull-right" id="totalspan">{{count($client)}} CareGiver(s)</span>
                                <div class="table-responsive">
                                    <table id="hidden_column_table1" class="display table table-striped table-bordered" style="width:100%">
                                        <thead>
                                                     <tr>
                                                <th>No.</th>
                                               
                                                <th>ID No.</th>
                                                <th>Phone</th>
                                             
                                                <th>Email</th>
                                                <th>Created At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                            @foreach($client as $clinician_)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>

                                                    <td>{{$clinician_->id_number}}</td>
                                                    <td>{{$clinician_->phone_number}}</td>
                                                    {{-- <td>@if(!empty($clinician_->facility_id)){{$clinician_->master_facility->name}}@endif</td> --}}
                                                    {{-- <td>{{$clinician_->facility_id['name']}}</td> --}}
                                                    {{-- <td>{{$clinician_->email}}</td> --}}
                                                    {{-- <td>@if(!empty($clinician_->facility_id)){{$clinician_->master_facility->name}}@endif</td> --}}
                                                    <td>{{$clinician_->created_at}}</td>
                                                    <td><a onclick="editClinician({{$clinician_}});" class="btn btn-info btn-xs" style="color: white; margin-right:3px;">Edit</a>
                                                        <a onclick ="deleteClinician({{$clinician_->user_id}});" class="btn btn-xs btn-danger" style="color: white; margin-right:3px;">Delete</a>
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