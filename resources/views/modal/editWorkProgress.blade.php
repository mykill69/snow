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

<div class="modal fade" id="editWorkModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editWorkForm" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Work Progress</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">

                {{-- Hidden ID only so we can keep @method PUT --}}
                <input type="hidden" id="editProgressId">

                <div class="form-group">
                    <label>Progress (%)</label>
                    <input type="number" name="progress" id="editProgress"
                           class="form-control" min="0" max="100" required>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="proj_status" id="editStatus" class="form-control" required>
                        @foreach (['Ongoing','Completed','Onhold'] as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-primary">Save changes</button>
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

