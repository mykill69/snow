@extends('access.layout')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
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
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

@section('body')
    <div class="container d-flex justify-content-center align-items-center pt-2 pb-5">
        <div class="w-100" style="max-width: 1000px;"> <!-- Set max width similar to .card width -->
            <div class="card w-100" style="box-shadow: 0 1px 6px 0 black;">
                <div class="container">
                    <h2 class="text-left text-lg">Create an MIS Support Ticket</h2>
                    <p class="text-left text-sm mt-2 mb-1" style="line-height: 1.3;">
                        By filling out this form, you can directly create a support ticket for your ICT-related concerns.
                        This allows the MIS team to respond to your request in a timely and efficient manner.
                        Please ensure the following:
                    </p>
                    <ul class="text-sm ps-3 mb-1" style="line-height: 1.3;">
                        <li class="mb-1">Provide a clear and specific subject for your concern.</li>
                        <li class="mb-1">Include a detailed description of the issue or request.</li>
                        <li class="mb-1">Attach any relevant files or screenshots, if applicable.</li>
                        <li class="mb-1">Select the appropriate category to ensure proper routing of your concern.</li>
                        <li class="mb-1">Provide accurate contact details so we can follow up if needed.</li>
                    </ul>

                    <li class="list-unstyled text-sm ps-3 mb-1" style="list-style-type: none; line-height: 1.3;">
                        Once submitted, your ticket will be queued and assigned to an MIS personnel for review.<br>
                        You can follow up anytime by calling the MIS personnel assigned to your ticket.
                        The contact number will be provided based on who is in charge of your request.<br>
                        You may also reach us via <strong>MS Teams</strong> at <em>MIS Helpdesk</em> or email us at
                        <a href="https://mail.google.com/mail/" target="_blank"
                            class="text-primary">cpsu_mis@cpsu.edu.ph</a>.
                    </li>
                </div>
            </div>

            <!-- Matching horizontal line -->
            <hr class="my-3" style="border-top: 1px solid gray;">



            <form id="request-form" action="{{ route('storeRequestForm') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1">Full Name</label>
                            <input type="text" class="form-control" id="exampleInputEmail1"
                                value="{{ Auth::user()->fname }} {{ Auth::user()->mname }} {{ Auth::user()->lname }}"
                                readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="exampleInputPassword1">Office/College</label>
                            <input type="text" class="form-control" id="exampleInputPassword1" name="department"
                                value="{{ Auth::user()->department }}" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category">Please choose a Category <span class="text-danger">*</span></label>
                        <select class="form-control" id="category" name="category" required onchange="setAdminId()">
                            <option value="" disabled selected>Select Category</option>
                            <option value="ICT Repair/Troubleshooting">ICT Repair/Troubleshooting</option>
                            <option value="Network Connection">Network Connection</option>
                            <option value="UTP Cable Replacement/Installation">UTP Cable Replacement/Installation
                            </option>
                            <option value="System Account Creation">System Account Creation</option>
                            <option value="System Update Request">System Update Request</option>
                            <option value="Institutional Email/MS Teams">Institutional Email/MS Teams</option>
                            <option value="Software Installation">Software Installation</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>

                    <div class="form-group" id="subCatWrapper" style="display: none;">
                        <label for="sub_cat">Please choose a sub-category</label>
                        <select class="form-control" id="sub_cat" name="sub_cat">
                            <!-- Options will be inserted by script -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="administrator">MIS Personnel Assigned</label>
                        <input type="text" class="form-control" id="administrator" name="admin_id" value=""
                            readonly>
                    </div>

                    <div class="form-group">
                        <label for="subject">Request Title <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="subject" name="subject" rows="2" required
                            placeholder="Please specify your concern. Be as specific and detailed as possible."></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="priority">Priority <span class="text-danger">*</span></label>
                            <select class="form-control" id="priority" name="priority" required>
                                <option value="" disabled selected>Select Priority</option>
                                <option value="1">Low</option>
                                <option value="2">Medium</option>
                                <option value="3">High</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="contact_no">Contact Number <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="contact_no" name="contact_no"
                                placeholder="Enter your contact number" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="exampleInputFile">Provide Additional Supporting Material <i
                                    class="text-muted">(optional)</i></label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="exampleInputFile"
                                        id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card">
                        <button type="submit" class="btn" style="background-color:#42BFDD;">Submit</button>
                    </div>
                </div>
            </form>
        </div>
{{--         
        <div id="page-loader"
     class="position-fixed top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center">
    <div class="spinner-border text-primary" role="status"></div>
    <p>Submitting your ticket… please wait</p>
</div> --}}
        {{-- <div id="page-loader"
         class="position-fixed top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center bg-white"
         style="z-index: 1055; display: none;">
        <div class="spinner-border text-primary" role="status"></div>
        <p>Submitting your request… please wait</p>
    </div> --}}
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
        Submitting your request… please wait
    </p>
</div>

        <!-- Fixed Footer -->
        <footer class="text-muted text-center bg-white py-2"
            style="position: fixed; left: 0; bottom: 0; width: 100%; z-index: 999;">
            <div class="float-right d-none d-sm-block pr-2">
                <b>Version</b> 1.0.0
            </div>
            <div class="float-left d-none d-sm-block pl-2">
                <i>Maintained and Managed by Management Information System Office. All rights reserved.</i>
            </div>
        </footer>

        <script src="template/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


   <script>
document.addEventListener('DOMContentLoaded', () => {
    const form   = document.getElementById('request-form');
    const loader = document.getElementById('page-loader');
    const bar    = document.getElementById('progress-bar');

    form.addEventListener('submit', e => {
        e.preventDefault();                        // keep page
        loader.style.display = 'flex';             // show overlay
        animateBarTo(90, 8000);                    // creep 0‑→‑90 % over ~8 s

        const fd = new FormData(form);

        fetch("{{ route('storeRequestForm') }}", {
            method : 'POST',
            headers: { 'X-Requested-With':'XMLHttpRequest',
                       'X-CSRF-TOKEN'   : document.querySelector('input[name=_token]').value },
            body   : fd
        })
        .then(r => r.ok ? r.json() : Promise.reject(r))
        .then(() => finishAndRedirect())
        .catch(() => showError());
    });

    /* ---- helpers ---- */
    function animateBarTo(target, duration) {
        const start   = parseFloat(bar.style.width) || 0;
        const diff    = target - start;
        const startTs = performance.now();
        requestAnimationFrame(function step(now) {
            const pct = Math.min(1, (now - startTs) / duration);
            bar.style.width = (start + diff * pct) + '%';
            if (pct < 1) requestAnimationFrame(step);
        });
    }

    function finishAndRedirect() {
        animateBarTo(100, 500);               // fill last 10 %
        setTimeout(() => window.location.href = "{{ route('home') }}", 600);
    }

    function showError() {
        bar.style.background = '#dc3545';     // red bar
        animateBarTo(100, 300);
        loader.querySelector('p').textContent =
            'Something went wrong – please try again';
        setTimeout(() => loader.style.display = 'none', 2000);
    }
});
</script>


        <script>
            $(function() {
                bsCustomFileInput.init();
            });
        </script>

        <script>
            function setAdminId() {
                const category = document.getElementById("category").value;
                const adminField = document.getElementById("administrator");
                const subCatWrapper = document.getElementById("subCatWrapper");
                const subCatSelect = document.getElementById("sub_cat");

                // Admin logic
                const categoryToAdmins = {
                    "Network Connection": [5, 10],
                    "System Account Creation": [1, 6, 7, 9],
                    "System Update Request": [1, 6, 7, 9],
                    "UTP Cable Replacement/Installation": [5, 10],
                    "ICT Repair/Troubleshooting": [8, 10],
                    "Institutional Email/MS Teams": [2, 11],
                    "Software Installation": [8, 9, 10],
                    "Others": [1, 2, 5, 6, 7, 8, 9, 10, 11]
                };

                const adminIdToName = {
                    1: "MICHAEL BALIVIA",
                    2: "GMAR PALMA",
                    5: "RICO ONDING",
                    6: "ROZNEN SOBERANO",
                    7: "EDWIN ABRIL",
                    8: "KIT IAN BAYOTAS",
                    9: "JOSHUA KYLE DALMACIO",
                    10: "MAXIMO HULGUIN",
                    11: "ROSALIE GARGOLES"
                };

                const adminIds = categoryToAdmins[category] || [];
                const adminNames = adminIds.map(id => adminIdToName[id] || `User#${id}`);
                adminField.value = adminNames.join(", ");

                // Sub-category logic
                const subCategories = {
                    "ICT Repair/Troubleshooting": [
                        "Printer",
                        "Desktop",
                        "Laptop",
                        "Other ICT"
                    ],
                    "Network Connection": [
                        "Network Account",
                        "Internet Connection",
                        "Others"
                    ],
                    "UTP Cable Replacement/Installation": [
                        "New Installation",
                        "Cable Replacement",
                        "Network Port Relocation",
                        "Cable Testing",
                        "Others"
                    ],
                    "System Account Creation": [
                        "CISS",
                        "HRIS",
                        "Document Tracking System",
                        "PPEI",
                        "Others"
                    ],
                    "System Update Request": [
                        "Feature Enhancement",
                        "Bug Fix",
                        "Access Modification",
                        "UI/UX Request",
                        "Others"
                    ],
                    "Institutional Email/MS Teams": [
                        "Account Creation",
                        "Password Reset",
                        "Email/MS Teams Configuration",
                        "Others"
                    ],
                    "Software Installation": [
                        "Office Applications",
                        "Antivirus",
                        "Design/Editing Tools",
                        "Programming Tools",
                        "Others"
                    ],
                    "Others": [
                        "Hardware Issue",
                        "Software Concern",
                        "Account Permissions",
                        "General Inquiry",
                        "Others"
                    ]
                };

                // Clear old sub_cat options
                subCatSelect.innerHTML = "";

                if (subCategories[category]) {
                    subCatWrapper.style.display = "block";
                    subCategories[category].forEach(sub => {
                        const option = document.createElement("option");
                        option.value = sub;
                        option.textContent = sub;
                        subCatSelect.appendChild(option);
                    });
                } else {
                    subCatWrapper.style.display = "none";
                }
            }
        </script>
    @endsection
