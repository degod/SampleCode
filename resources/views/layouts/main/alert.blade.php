
                <!-- ALERT MODAL -->
                <div style="width:100%" align="center">
                    <div style="position:absolute;top:10vh;z-index:9999;width:350px;background:#EFD1C7;border-radius:7px;">
                        @if(Session::has('success'))
                          <div class="alert alert-success alert-dismissible fade show" style="border-radius:3px;box-shadow:0 0 3px 1px #e3e3e3;padding:10px 14px;">
                              <strong>{{ Session::get('success') }}</strong>
                              <!-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button> -->
                          </div>
                        @endif
                        @if(Session::has('errors'))
                            <div class="alert alert-danger alert-dismissible fade show" style="border-radius:3px;box-shadow:0 0 3px 1px #e3e3e3;padding:10px 14px;">
                                @if(is_array(Session::get('errors')))
                                    <ul>
                                        @foreach(Session::pull('errors') as $error)
                                            <strong>{{ $error }}</strong>
                                        @endforeach
                                    </ul>
                                @elseif($errors && !is_string($errors) && $errors->any())
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li> {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <strong>⚠ {{ Session::pull('errors') }}</strong>
                                @endif
                                <!-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button> -->
                            </div>
                        @endif
                    </div>
                </div>
                <!-- ALERT MODAL - // -->
                