<style>
    .select2-selection__choice {
        background-color: #28a745 !important;
        color: #fff !important;
    }
</style>

<div class="modal fade" id="addProject" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-default">
                <h4 class="modal-title w-100 text-center">Add New Project</h4>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form action="{{ route('addProject') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Project Title</label>
                                <input type="text" name="project_name" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Assigned To</label>
                                <select class="form-control select2" id="admin_id" name="admin_id[]" multiple
                                    data-placeholder="Select Admin" required>
                                    @foreach ($adminUsers as $admin)
                                        <option value="{{ $admin->id }}">
                                            {{ $admin->fname }} {{ $admin->lname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success">Save</button>
                </div>

            </form>
        </div>
    </div>
</div>



