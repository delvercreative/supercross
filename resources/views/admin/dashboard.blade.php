
@extends('layouts.app')

@section('content')
<div class="container">
<edit-events rider-count="{{$next->count}}" users="{{$next->users}}" raceid="{{$next->id}}"></edit-events>

    {{-- <div class="card">
        <div class="card-body">
            <h3>Final Results Posted</h3>
            <ul class="list-group">
                <li class="list-group-item">Cras justo odio</li>
            </ul>
        </div>
    </div> --}}

        <div class="row-inner">
            <div class="panel panel-default">
                <div class="panel-heading mb-2 mt-2" style="font-weight: bold; text-align:center;">Change Race Status</div>

                <div class="panel-body">
                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif

                    <table class="table admin-table">
                        <thead class="thead-dark">
                          <tr>
                            <th scope="col">Rd</th>
                            <th scope="col">Race</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Race Status</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($races as $race)
                            <tr>
                                <td class="small-cell">{{$race->event->round}}</td>
                                <td class="small-txt small-cell">{{$race->name}}</td>
                                <td class="small-cell">{{$race->count}}</td>
                            <td>
                            
                                        <form method="POST" action="{{route('admin.race.status.update', $race)}}">
                                                @csrf
                                                
                                            <div class="flex table-inline-style">
                                            <select name="race_status" id="race_status_{{$race->id}}" class="form-control form-control-sm">
                                                    
                                                    
                                                        <option value="0" {{ (old("status", $race->status) == 0 ? "selected":"") }}>Not started</option>
                                                        <option value="1" {{ (old("status", $race->status) == 1 ? "selected":"") }}>In progress</option>
                                                        <option value="2" {{ (old("status", $race->status) == 2 ? "selected":"") }}>Complete</option>
                                                    
                                                </select>
                                            <div class="form-actions">
                                                <button type="submit" id="update-race" class="btn btn-success btn-small">Update</button>
                                            </div>
                                        </div>
                                </form>
                                    
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                      </table>

                </div> <!-- end panel-body -->
            </div> <!-- end panel -->
        </div>
</div>
@endsection