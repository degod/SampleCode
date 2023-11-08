@extends('layouts.main.master')

@section('header_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.13.1/af-2.5.1/b-2.3.3/b-colvis-2.3.3/cr-1.6.1/date-1.2.0/fc-4.2.1/fh-3.3.1/kt-2.8.0/r-2.4.0/rg-1.3.0/rr-1.3.1/sc-2.0.7/sb-1.4.0/sp-2.1.0/sl-1.5.0/sr-1.2.0/datatables.min.css"/>
 
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.13.1/af-2.5.1/b-2.3.3/b-colvis-2.3.3/cr-1.6.1/date-1.2.0/fc-4.2.1/fh-3.3.1/kt-2.8.0/r-2.4.0/rg-1.3.0/rr-1.3.1/sc-2.0.7/sb-1.4.0/sp-2.1.0/sl-1.5.0/sr-1.2.0/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.13.1/af-2.5.1/b-2.3.3/b-colvis-2.3.3/cr-1.6.1/date-1.2.0/fc-4.2.1/fh-3.3.1/kt-2.8.0/r-2.4.0/rg-1.3.0/rr-1.3.1/sc-2.0.7/sb-1.4.0/sp-2.1.0/sl-1.5.0/sr-1.2.0/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href=" https://cdn.datatables.net/buttons/2.3.5/css/buttons.dataTables.min.css"/>
    <!-- <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.21.4/dist/bootstrap-table.min.css"> -->
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
                    <h3>Partners Manual Code Generator</h3>
                </div>

                <div class="top-right">
                    @include('layouts.main.top-bar-right')
                </div>
            </div>

            <!-- Main content -->
            <div class="main-container">
                @include('layouts.main.alert')

                <div class="home-data-wrapper" style="overflow: auto;">
                    @if(\Auth::user()->id == 1)
                        <button data-micromodal-trigger="modal-1" class="btn btn-lg max-btn">
                            Generate Partner Code
                        </button><br>
                    @endif

                    <div class="content">
                        <table class="max-table datatable">
                            <thead>
                                <tr>
                                    <th>For Who</th>
                                    <th>Code</th>
                                    <th>Package</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Used On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <!-- Generate Partner code modal -->
        <div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
            <div class="modal__overlay" tabindex="-1">
              <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <header class="modal__header">
                  <h2 class="modal__title copybtn" id="modal-1-title">
                    Partner Code Generator
                  </h2>
                  <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <main class="modal__content" id="modal-1-content">

                    <form method="POST" action="{{ route('partner.code.create') }}">
                        @csrf
                        <div class="setting-two-grid">
                            <div class="form-box">
                                <label for="">Partner Email</label>
                                <input type="email" placeholder="Email:" name="email">
                            </div>

                            <div class="form-box">
                                <div class="input-wrapper">
                                    <label for="">Package Paid</label>
                                    <select name="package">
                                        <option value="">Select a Package</option>
                                        @foreach($packages as $id=>$pack)
                                            <option value="{{ $id }}">{{ $pack->name }} - ₦{{ number_format($pack->amount) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-box">
                                <div class="input-wrapper">
                                    <label for="">Commission Settlement</label>
                                    <select name="settled">
                                        <option value="no">Settle Referrers</option>
                                        <option value="yes">Do Not Settle Anyone</option>
                                    </select>
                                </div>
                            </div>

                            <div class="footer-btn">
                                <button class="max-btn primary-button">Generate Code</button>
                            </div>
                        </div>
                    </form>
                </main>
              </div>
            </div>
        </div>
        <!-- end share button modal -->
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

    <!-- <script src="https://unpkg.com/bootstrap-table@1.21.4/dist/bootstrap-table.min.js"></script> -->

    <script type="text/javascript">
        $(function () {
            var table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('partner.code.generated') }}",
                columns: [
                    {data: 'email', name: 'email'},
                    {data: 'code', name: 'code'},
                    {data: 'package', name: 'package'},
                    {data: 'amount', name: 'amount'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [[5, 'desc']],
                pagingType: 'full_numbers'
            });
        });
    </script>
@endsection