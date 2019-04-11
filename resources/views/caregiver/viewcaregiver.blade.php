@section('page-css')
@extends('layouts.master')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
  <div class="breadcrumb">
                <h1>Clinician</h1>
            </div>
            <div class="separator-breadcrumb border-top"></div>

            <div class="row mb-4">
            

                <div class="col-md-12 mb-4">
                    <div class="card text-left">

                        <div id ="ClientsTable" class="card-body viewclients">
                            <h4 class="card-title mb-3">View Care Givers</h4>
                            <span class="label label-info pull-right" id="totalspan">{{count($caregiver)}} CareGiver(s)</span>
                                <div class="table-responsive">
                                    <table id="hidden_column_table1" class="display table table-striped table-bordered" style="width:100%">
                                        <thead>
                                                     <tr>
                                                <th>No.</th>
                                                <th>Name</th>
                                                <th>ID No.</th>
                                                <th>Phone</th>
                                                <th>Facility</th>
                                                <th>Email</th>
                                                <th>Created At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                            @foreach($clinician as $clinician_)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>

                                                    <td>{{ucwords($clinician_->first_name)}} {{ucwords($clinician_->last_name)}}</td>
                                                    <td>{{$clinician_->phone_no}}</td>
                                                    <td>@if(!empty($clinician_->facility_id)){{$clinician_->master_facility->name}}@endif</td>
                                                    {{-- <td>{{$clinician_->facility_id['name']}}</td> --}}
                                                    <td>{{$clinician_->email}}</td>
                                                    <td>@if(!empty($clinician_->facility_id)){{$clinician_->master_facility->name}}@endif</td>
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
                
                
                           
                            <!-- end of row -->

                <!-- THE EDIT PART -->
                <div hidden id="EditClinicians" class="card-body editclinician">
                        <h4 class="card-title mb-3">Edit Clinican</h4>
                        <form data-toggle="validator" role="form" method="post" action="{{route('editClinicianData')}}" id="editclinicianform">
                        {{ csrf_field() }}
                        <div class="d-flex flex-column">
                            <div class="row">
                                    <div class="form-group col-md-6">
                                            <label for="firstname1" class="col-sm-2 col-form-label">First Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="first_name" id="first_name" required class="form-control" placeholder="Enter your first name">
                                                </div>
                                    </div>
                                    {{-- <input type="hidden" id="person_id" name="person_id"> --}}
                                    <input type="hidden" id="user_id" name="user_id">
                                    {{-- <input type="hidden" id="client_id" name="client_id"> --}}
                                            <div class="form-group col-md-6">
                                                <label for="lastname1" class="col-sm-2 col-form-label">Last Name</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="last_name" id="last_name" required class="form-control" placeholder="Enter your last name">
                                                    </div>
                                            </div>
                            </div>
                            <div class="row">
                                    <div class="form-group col-md-6">
                                            <label for="id1" class="col-sm-2 col-form-label">ID No.</label>
                                                <div class="col-sm-10">
                                                    <input type="number" name="id_no" id="id_no" required class="form-control" placeholder="Enter your ID Number">
                                                </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                            <label for="phone1" class="col-sm-2 col-form-label">Phone No.</label>
                                                <div class="col-sm-10">
                                                    <input type="tel" name="phone_no" id="phone_no" required class="form-control" placeholder="Enter your phone number">
                                                </div>
                                    </div>
                            </div>
                            <div class="row">
                                    <div class="form-group col-md-6">
                                            <label for="email1" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" name="email" id="email" required class="form-control" id="inputEmail3" placeholder="Email">
                                            </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="facility1" class="col-sm-2 col-form-label">Facility</label>
                                            <div class="col-sm-10">
                                            <select class="form-control" required data-width="100%" id="facility" name="facility_id">
                                                    <option value="">Select Facility</option>
                                                        @if (count($facilities) > 0)
                                                            @foreach($facilities as $facility)
                                                            <option value="{{$facility->facility_id }}">{{ ucwords($facility->name) }}</option>
                                                                @endforeach
                                                        @endif
                                            </select>
                                            </div>
                                </div>
                            
                        </div>
                    <button type="submit" class="btn btn-primary pd-x-20">Edit Clinician</button>
                        </div>

                        </form>

                </div>
            </div>
            </div> <!-- end of column -->
            </div> <!-- end of row -->