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
                    @if(empty($pid))
                        <h3>My Networks and Downlines</h3>
                    @else
                        <h3>{{ $userFullName }}'s Downlines</h3>
                    @endif
                </div>

                <div class="top-right">
                    @include('layouts.main.top-bar-right')
                </div>
            </div>

            <!-- Main content -->
            <div class="main-container">
                @include('layouts.main.alert')

                <div class="mobile-welcome-message display-mobile-only">
                    @if(empty($pid))
                        <h3>My Networks and Downlines</h3>
                    @else
                        <h3>{{ $userFullName }}'s Downlines</h3>
                    @endif
                </div>

                @if(\Auth::user()->id == 1)
                    <div class="setting-three-grid">
                        <div class="form-box">
                            <button data-micromodal-trigger="modal-1" class="max-btn">
                                Generate Partner Code
                            </button>
                        </div>
                        <div class="form-box"></div>
                        <div class="form-box">
                            <a href="{{ route('partner.code.generated') }}" class="primary-button max-btn" style="border:1px solid black !important;height:39px;">
                                All Generated Codes
                            </a>
                        </div>
                    </div><br>
                @endif

                <div class="home-data-wrapper" style="overflow: auto;">
                    <table class="max-table datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Contact Info</th>
                                <th>Package</th>
                                <th>Registration Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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
        $(document).on('click', '.more', function() {
            // code to be executed on click event
            $(this).find(".table-sub-menu-wrap").toggleClass("open-table-menu");
        });
        $(function () {
            var table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                // ajax: "{{ route('partner.networks') }}",
                ajax: "{{ (!empty($pid)) ? route('partner.networks',['pid'=>$pid]): route('partner.networks') }}",
                columns: [
                    {data: 'full_name', name: 'full_name'},
                    {data: 'username', name: 'username'},
                    {data: 'phone', name: 'phone'},
                    {data: 'package', name: 'package'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [[4, 'desc']],
                pagingType: 'full_numbers'
            });
        });
    </script>
@endsection