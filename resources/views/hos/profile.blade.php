@extends('hos.layouts.app')

@section('content')
<div class="container-fluid main_content bg-light p-2">   
        <div class="row mx-0">
          <div class="col-12">
            <h4 class="text-center" style="text-shadow: 0 0 12px #456399, 0 0 1px #b2cee3;">Admin Profile</h4>
          </div>
          <div class="col-3">
            <div class="card shadow border-0 py-3">
              <div class="form-group text-center my-2">
                 <img src="{{ asset('public/hos/img/prof.png') }}" class="bg-light" alt="prof" style="width: 90px;height: auto;border-radius: 50px">
              </div>
              <h5 class="text-center"><b>{{ Auth::user()->name }}</b></h5>
              <h6 class="text-center text-primary"><b>admin</b></h6>
            </div>
          </div>
          <div class="col-9">
            <div class="card shadow border-0 p-3">
              <form>
                <div class="form-group row">
                  <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                  <div class="col-sm-10">
                    <input type="email" readonly class="form-control" id="staticEmail" value="{{ Auth::user()->email }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="mob" class="col-sm-2 col-form-label">Mobile</label>
                  <div class="col-sm-10">
                    <input type="number" readonly class="form-control" id="mob" value="{{ Auth::user()->mobile }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                  <div class="col-sm-10">
                    <input type="password" readonly class="form-control" id="inputPassword" placeholder="*********">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
@endsection