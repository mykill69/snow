@extends('pages.main')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff !important;
        border-color: #187744 !important;
        color: #fff;
        padding: 0 10px;
        margin-top: 0.31rem;
    }

    #page-loader {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .progress-loader {
        width: 200px;
        height: 10px;
        background-color: #dee2e6;
        border-radius: 5px;
        overflow: hidden;
        margin-top: 10px;
    }

    .progress-bar {
        width: 0%;
        height: 100%;
        background-color: #0d6efd;
        transition: width 0.5s ease;
    }

    #page-loader p {
        margin-top: 1.5rem;
        font-size: 1.2rem;
        font-weight: 500;
        color: #343a40;
        animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>


@section('body')
    <div class="content-wrapper">
        <div class="content" style="padding-top: 1%;">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Ticket: {{ $ticket->ticket_no }}</h3>
                    </div>
                    <form id="update-ticket-form" action="{{ route('updateTicket', $ticket->ticket_no) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- THIS is crucial --}}
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Full Name</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1"
                                        value="{{ $ticket->user->fname }} {{ $ticket->user->lname }}" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputPassword1">Office/College</label>
                                    <input type="text" class="form-control" id="exampleInputPassword1" name="department"
                                        value="{{ $ticket->department }}" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="subject">Request Title</span></label>
                                <textarea class="form-control" id="subject" name="subject" rows="2" value="{{ $ticket->subject }}"
                                    placeholder="Please specify your concern. Be as specific and detailed as possible." readonly>{{ $ticket->subject }} 
                               </textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="priority">Category</label>
                                    <input type="text" class="form-control" id="exampleInputPassword1" name="category"
                                        value="{{ $ticket->category }}" readonly>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="exampleInputPassword1">Sub-category</label>
                                    <input type="text" class="form-control" id="exampleInputPassword1" name="sub_cat"
                                        value="{{ $ticket->sub_cat }}" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="priority">Priority Level</label>
                                    <select name="priority" class="form-control" id="priority" disabled>
                                        <option value="1" {{ $ticket->priority == 1 ? 'selected' : '' }}>Low</option>
                                        <option value="2" {{ $ticket->priority == 2 ? 'selected' : '' }}>Medium
                                        </option>
                                        <option value="3" {{ $ticket->priority == 3 ? 'selected' : '' }}>High</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="exampleInputPassword1">Contact Number</label>
                                    <input type="text" class="form-control" id="exampleInputPassword1" name="contact_no"
                                        value="{{ $ticket->contact_no }}" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Additional Supporting Material</label>
                                <input type="text" class="form-control" id="exampleInputPassword1" name="file_name"
                                    value="{{ $ticket->file_name ? $ticket->file_name : 'N/A' }}" readonly>

                            </div>

                            <div class="form-group">
                                <label for="status"><strong>Status</strong></label>
                                <select class="form-control border border-danger text-danger font-weight-bold"
                                    id="status" name="status" required
                                    style="
                border-width: 3px;
                background-color: #FFFF;
                box-shadow: 0 0 5px rgba(220, 53, 69, 0.6);
                font-size: 16px;
            ">
                                    <option value="" disabled {{ is_null($ticket->status) ? 'selected' : '' }}>
                                        Select Status
                                    </option>

                                    {{-- Show current status (disabled but visible) --}}
                                    @if ($ticket->status == 1)
                                        <option value="1" disabled selected>New</option>
                                    @endif

                                    <option value="3" {{ $ticket->status == 3 ? 'selected' : '' }}>Resolved</option>
                                    <option value="2" {{ $ticket->status == 2 ? 'selected' : '' }}>Pending</option>
                                    <option value="4" {{ $ticket->status == 4 ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea class="form-control" id="remarks" name="remarks" rows="3"
                                    placeholder="Kindly provide any remarks or additional details on how you resolved the ticket" required>{{ $ticket->remarks }}</textarea>
                            </div>

                            <!-- /.card-body -->
                            <div class="footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="page-loader"
        class="position-fixed top-0 start-0 w-100 h-100 flex-column justify-content-center align-items-center"
        style="z-index:1055;display:none;background:linear-gradient(135deg,#f8f9fa,#e9ecef);font-family:'Segoe UI',Tahoma,sans-serif">

        {{-- static logo --}}
        <img src="{{ asset('template/img/mis_logo2.png') }}" alt="MIS logo"
            style="width:110px;height:auto;margin-bottom:28px">

        {{-- progress bar --}}
        <div class="progress-loader" style="width:220px;height:12px;background:#dee2e6;border-radius:6px;overflow:hidden">
            <div id="progress-bar" style="width:0;height:100%;background:#0d6efd;transition:width .4s ease"></div>
        </div>

        <p style="margin-top:1.3rem;font-size:1.15rem;font-weight:500;color:#343a40">
            Please wait — updating the status and sending notification…
        </p>
    </div>


    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 1.0.0
        </div>
        <i>Maintained and Managed by Management Information System Office. All rights reserved.</i>
    </footer>
    <script src="template/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('update-ticket-form'); // ← new ID
            const loader = document.getElementById('page-loader');
            const bar = document.getElementById('progress-bar');

            if (!form || !loader || !bar) return; // safety guard

            form.addEventListener('submit', e => {
                e.preventDefault(); // keep page
                loader.style.display = 'flex'; // show overlay
                animateBarTo(90, 8000); // creep 0‑→‑90 % (~8 s)

                const fd = new FormData(form);
                fd.append('_method', 'PUT'); // tell Laravel this is PUT

                fetch(form.action, {
                        method: 'POST', // still POST because of FormData
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: fd
                    })
                    .then(r => r.ok ? r.json() : Promise.reject(r))
                    .then(() => finishAndRedirect())
                    .catch(() => showError());
            });

            /* ---- helpers (same as create ticket) ---- */
            function animateBarTo(target, duration) {
                const start = parseFloat(bar.style.width) || 0;
                const diff = target - start;
                const startTs = performance.now();
                requestAnimationFrame(function step(now) {
                    const pct = Math.min(1, (now - startTs) / duration);
                    bar.style.width = (start + diff * pct) + '%';
                    if (pct < 1) requestAnimationFrame(step);
                });
            }

            function finishAndRedirect() {
                animateBarTo(100, 500); // fill last 10 %
                setTimeout(() => window.location.href = "{{ route('allTickets') }}", 600);
            }

            function showError() {
                bar.style.background = '#dc3545'; // red bar
                animateBarTo(100, 300);
                loader.querySelector('p').textContent =
                    'Something went wrong – please try again';
                setTimeout(() => loader.style.display = 'none', 2000);
            }
        });
    </script>
    </body>

    </html>
@endsection
