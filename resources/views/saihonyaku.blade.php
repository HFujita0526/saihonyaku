@extends('layouts.layout')
@section('content')
    <div class="container-fluid mt-4 px-4" id="scroll-area">
        <div class="pc-container">
            <div id="content">
            </div>

            <div class="d-flex justify-content-center pb-5" id="loading">
                <div class="d-none spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/saihonyaku.js'])
@endsection
