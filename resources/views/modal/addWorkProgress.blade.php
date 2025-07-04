<style type="text/css">
    .no-left-radius {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .select2-selection__choice {
        background-color: #007bff !important;
        /* Blue background */
        color: #fff !important;
        /* White text */
        border: none !important;
        padding: 2px 10px;
        border-radius: 0.2rem;
        margin-top: 4px;
    }
</style>

<div class="modal fade" id="exampleModalUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalUserLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('addWorkProgress') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Work Progress</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="admin_id" value="{{ auth()->user()->id }}">

                    <div class="form-group">
                        <label for="project_name">Project Name</label>
                        <input type="text" name="project_name" class="form-control" required>
                    </div>

                    <!-- Members field (Select2 multiple) -->
                    <div class="form-group">
                        <label for="members">Members</label>
                        <select class="form-control select2" id="members" name="members[]" multiple="multiple"
                            data-placeholder="Select members" required>
                            @foreach ($adminUsers as $admin)
                                @if (!in_array($admin->id, [3, 12]))
                                    <option value="{{ $admin->id }}">{{ $admin->fname }} {{ $admin->lname }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>



                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="From">From</label>
                                <input type="date" name="date_from" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_to">To</label>
                                <input type="date" name="date_to" class="form-control" required>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="duration">Duration (in days)</label>
                        <input type="text" id="duration" name="duration" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="progress">Progress (%)</label>
                        <input type="number" name="progress" class="form-control" min="0" max="100"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="proj_status">Project Status</label>
                        <select name="proj_status" class="form-control" required>
                            <option value="Ongoing">Ongoing</option>
                            <option value="Completed">Completed</option>
                            <option value="Onhold">On Hold</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Work</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        const from = document.querySelector('[name="date_from"]');
        const to = document.querySelector('[name="date_to"]');
        const durEl = document.getElementById('duration');

        function updateDuration() {
            if (from.value && to.value) {
                const fromDate = new Date(from.value);
                const toDate = new Date(to.value);
                if (!isNaN(fromDate) && !isNaN(toDate) && toDate >= fromDate) {
                    const days = Math.ceil((toDate - fromDate) / 86400000); // ms in a day
                    durEl.value = days + ' Day' + (days !== 1 ? 's' : '');
                } else {
                    durEl.value = '';
                }
            } else {
                durEl.value = '';
            }
        }
        from.addEventListener('change', updateDuration);
        to.addEventListener('change', updateDuration);
    });
</script>

<!-- Select2 -->


<script>
    $(document).ready(function() {
        $('#members').select2({
            placeholder: "Select members"
        });
    });
</script>
