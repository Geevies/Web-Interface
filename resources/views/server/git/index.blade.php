{{--Copyright (c) 2017 "Werner Maisl"--}}

{{--This file is part of the Aurora Webinterface--}}

{{--The Aurora Webinterface is free software: you can redistribute it and/or modify--}}
{{--it under the terms of the GNU Affero General Public License as--}}
{{--published by the Free Software Foundation, either version 3 of the--}}
{{--License, or (at your option) any later version.--}}

{{--This program is distributed in the hope that it will be useful,--}}
{{--but WITHOUT ANY WARRANTY; without even the implied warranty of--}}
{{--MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the--}}
{{--GNU Affero General Public License for more details.--}}

{{--You should have received a copy of the GNU Affero General Public License--}}
{{--along with this program. If not, see <http://www.gnu.org/licenses/>.<!DOCTYPE html>--}}

@extends('layouts.app')

@section('styles')
    <link href="{{ asset('/assets/css/datatables.bootstrap.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="alert alert-success">
            This page allows you to see the testing todos for all current pull requests.<br>
            Feel free to join the test server, try out what is mentioned in the todos and then comment if it is working as expected
        </div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Pull Request Overview</div>

                    <div class="panel-body">
                        <table id="git-table" class="table table-condensed">
                            <thead>
                            <tr>
                                <th>Pull ID</th>
                                <th>Title</th>
                                <th>Working / Broken / Untested</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    <script src="{{ asset('/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/datatables.bootstrap.js') }}"></script>
    <script>
        $(function() {
            $('#git-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('server.git.data') }}'
            });
        });
    </script>
@endsection