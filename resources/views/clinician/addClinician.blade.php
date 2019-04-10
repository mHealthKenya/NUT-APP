@extends('layouts.master')
@section('before-css')


@endsection

@section('main-content')
            <div class="breadcrumb">
                <h1>Clinician</h1>
    
            </div>

            <div class="separator-breadcrumb border-top"></div>

            <div class="row">
                <div class="col-md-12">
                    <h4>Add Clinician</h4>
                    <p>Complete the form below with correct details.</p>
                    <div class="card mb-5">
                        <div class="card-body">
                        <form data-toggle="validator" role="form" method="post" action="{{route('addClinicianData')}}" id="addclinicianform">
                        {{ csrf_field() }}
                            <div class="d-flex flex-column">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">First Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="first_name" id="first_name" required class="form-control" placeholder="Enter your first name">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">Last Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="last_name" id="last_name" required class="form-control" placeholder="Enter your last name">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">ID No.</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="id_number" id="id_no" required class="form-control" placeholder="Enter your ID Number">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">Phone No.</label>
                                        <div class="col-sm-10">
                                            <input type="tel" name="phone_number" id="phone_no" required class="form-control" placeholder="Enter your phone number">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" name="email" id="email"  required class="form-control" id="inputEmail3" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Facility</label>
                                            <div class="col-sm-10">
                                                <input type="facility" name="facility_id" id="facility"  required class="form-control" id="inputfacilityId3" placeholder="Enter Facility">
                                            </div>
                                        </div>
                                </div>
                           <div class="row">
                           <br>
                           </div>
                                <button type="submit" class="btn btn-primary pd-x-20">Add Clinician</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-top mb-5"></div>




            @endsection