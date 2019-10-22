@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                      @if(Session::has('alert-' . $msg))

                      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                      @endif
                    @endforeach
                </div>
                <div class="card-header">Set the Job</div>

                    <form method="POST" action="/cronjob">
                        @csrf

                        Query:
                        <input class="form-control" height="100" rows="3" name="query" id="query"required><br><br>

                        Time:
                        <input type="text" name="time" class="form-control without_ampm" required><br><br>

                        <div class="form-group row" >
                            <label class="col-sm-2">Routine: </label>
                            <div class="col-sm-10" id="routine_gap" >
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="routineTime" id="inlineRadio1" value="hourly" required>
                                    <label class="form-check-label" for="inlineRadio1">Hourly</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="routineTime" id="inlineRadio2" value="daily">
                                    <label class="form-check-label" for="inlineRadio2">Daily</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="routineTime" id="inlineRadio3" value="weekly">
                                    <label class="form-check-label" for="inlineRadio3">Weekly</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="routineTime" id="inlineRadio4" value="monthly">
                                    <label class="form-check-label" for="inlineRadio4">Monthly</label>
                                </div>
                            </div>
                            <div class="col-sm-12" id="routine-day">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="routineDay[]" id="inlineCheckbox1[]" value="1" required_without_all>
                                    <label class="form-check-label" for="inlineCheckbox1">Sunday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="routineDay[]" id="inlineCheckbox2[]" value="2">
                                    <label class="form-check-label" for="inlineCheckbox2">Monday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="routineDay[]"  id="inlineCheckbox3[]" value="3">
                                    <label class="form-check-label" for="inlineCheckbox3">Tuesday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="routineDay[]"  id="inlineCheckbox4[]" value="4">
                                    <label class="form-check-label" for="inlineCheckbox4">Wednesday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox"  name="routineDay[]" id="inlineCheckbox5[]" value="5">
                                    <label class="form-check-label" for="inlineCheckbox5">Thursday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="routineDay[]" id="inlineCheckbox6[]" value="6">
                                    <label class="form-check-label" for="inlineCheckbox6">Friday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="routineDay[]" id="inlineCheckbox7[]" value="7">
                                    <label class="form-check-label" for="inlineCheckbox7">Saturday</label>
                                </div>
                            </div>
                        </div><br>

                        <div class="form-group row">
                        <label class="col-sm-2">Output File: </label>
                        <div class="col-sm-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="file_type" id="radiocsv" value="csv" checked required>
                                <label class="form-check-label" for="radiocsv">csv</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="file_type" id="radioxls" value="xls">
                                <label class="form-check-label" for="radioxls">xls</label>
                            </div>
                        </div>
                        </div>

                        <input type="submit" value="Schedule Report" class="btn btn-primary">



                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
