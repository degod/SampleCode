<?php
    $style = "style2.css";
?>
@extends('layouts.main.master')

@section('style'){{ $style }}@endsection

@section('header_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.13.1/af-2.5.1/b-2.3.3/b-colvis-2.3.3/cr-1.6.1/date-1.2.0/fc-4.2.1/fh-3.3.1/kt-2.8.0/r-2.4.0/rg-1.3.0/rr-1.3.1/sc-2.0.7/sb-1.4.0/sp-2.1.0/sl-1.5.0/sr-1.2.0/datatables.min.css"/>
 
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.13.1/af-2.5.1/b-2.3.3/b-colvis-2.3.3/cr-1.6.1/date-1.2.0/fc-4.2.1/fh-3.3.1/kt-2.8.0/r-2.4.0/rg-1.3.0/rr-1.3.1/sc-2.0.7/sb-1.4.0/sp-2.1.0/sl-1.5.0/sr-1.2.0/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.13.1/af-2.5.1/b-2.3.3/b-colvis-2.3.3/cr-1.6.1/date-1.2.0/fc-4.2.1/fh-3.3.1/kt-2.8.0/r-2.4.0/rg-1.3.0/rr-1.3.1/sc-2.0.7/sb-1.4.0/sp-2.1.0/sl-1.5.0/sr-1.2.0/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href=" https://cdn.datatables.net/buttons/2.3.5/css/buttons.dataTables.min.css"/>
@endsection

@section('main_content')

        <!-- Main Section -->
        <main>
            <!-- Top of the main section -->
            <div class="topbar">
                <div class="mobile-logo">
                    <a href="{{ route('partner.dashboard') }}">
                        <img src="{{ url('/') }}/assets/img/prokip-logo.svg" alt="logo">
                    </a>
               </div> 

                <div class="welcome-message">
                    <h3>Partners Code Update</h3>
                </div>

                <div class="top-right">
                    @include('layouts.main.top-bar-right')
                </div>
            </div>

            <!-- Main content -->
            <div class="main-container">
                @include('layouts.main.alert')

                <div class="home-data-wrapper">
                    <div class="content">
                        <form method="POST" action="{{ route('partner.code.update', ['id'=>$code->id]) }}">
                            @csrf

                            <div class="edit-profile-section">
                                <div class="setting-three-grid">
                                    <div class="form-box">
                                        <label for="">Partner Email</label>
                                        <input value="{{ $code->email }}" readonly>
                                    </div>

                                    <div class="form-box">
                                        <label for="">Partner Code</label>
                                        <input value="{{ $code->code }}" readonly>
                                    </div>

                                    <div class="form-box">
                                        <div class="input-wrapper">
                                            <label for="">Package Paid</label>
                                            <select name="package">
                                                @foreach($packages as $id=>$pack)
                                                    <option value="{{ $id }}" {{ ($code->package==$id)?'selected':'' }}>{{ $pack->name }} - ₦{{ number_format($pack->amount) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-box">
                                        <div class="input-wrapper">
                                            <label for="">Commission Settlement</label>
                                            <select name="settled">
                                                <option value="no" {{ ($code->settled=='no')?'selected':'' }}>Settle Referrers</option>
                                                <option value="yes" {{ ($code->settled=='yes')?'selected':'' }}>Do Not Settle Anyone</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="footer-btn"><br><br>
                                <button class="max-btn primary-button">Update Code</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
@endsection

@section('footer_js')
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.html5.min.js"></script>
    <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
@endsection