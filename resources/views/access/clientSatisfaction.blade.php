@extends('access.layout')
@section('body')
    <style>
        .survey-table {
            width: 100%;
            border-collapse: collapse;
        }

        .survey-table th,
        .survey-table td {
            border: 1px solid #ccc;
            vertical-align: middle;
            padding: 10px;
        }

        .radio-options {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .radio-options>div {
            flex: 1;
            min-width: 200px;
        }

        .form-check {
            margin-bottom: 8px;
        }
    </style>
    <div class="container d-flex justify-content-center align-items-center pt-2">
        <div class="w-100 mb-5" style="max-width: 1150px;">


            <div class="card shadow">

                <div class="card-header">
                    <h3 class="card-title font-weight-bold mb-2 text-center w-100">
                        CLIENT SATISFACTION SURVEY FORM {{ $survey->ticket_no }}
                    </h3>
                </div>

                <div class="card-body pb-8">

                    <p class="text-muted text-left">
                        Your feedback on your <u>recently conducted transaction</u> will help this office provide better
                        service.
                        Personal information shared will be kept confidential and you always have the option not to answer
                        this form.
                    </p>

                    <form action="{{ route('updateSurvey', $survey->ticket_no) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name of the Office</label>
                            <input type="text" class="form-control" id="exampleInputPassword1"
                                value="{{ Auth::user()->department }}" readonly>
                        </div>
                        <input type="hidden" class="form-control" name="dept_id" id="dept_id"
                            value="{{ Auth::user()->id }}" readonly>
                        <div class="form-group">
                            <label for="email">Client type <span class="text-danger">*</span></label>
                            <select class="form-control" id="clienttype" name="clienttype" required>
                                <option value="" disabled selected>Please Choose below</option>
                                <option value="1">CPSU Employee (if service/transaction is requested and availed by an
                                    individual employee)</option>
                                <option value="2">CPSU Office/Unit (if service/transaction is requested an availed by
                                    another CPSU Office/Unit)</option>
                                <option value="3">CPSU Student</option>
                                <option value="4">CPSU Alumni</option>
                                <option value="5">General Public</option>
                                <option value="6">Other Government Agency</option>
                                <option value="7">Private Organization</option>
                                <option value="8">Non-Government Organization (NGO)</option>
                                <option value="9">Others, please specify</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <!-- Date -->
                            <div class="form-group col-md-6">
                                <label for="date">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>

                            <!-- Sex -->
                            <div class="form-group col-md-2">
                                <label>Sex <span class="text-danger">*</span></label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="sexMale" name="sex[]"
                                        value="Male">
                                    <label class="form-check-label" for="sexMale">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="sexFemale" name="sex[]"
                                        value="Female">
                                    <label class="form-check-label" for="sexFemale">Female</label>
                                </div>
                            </div>

                            <!-- Age -->
                            <div class="form-group col-md-4">
                                <label for="age">Age <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="age" name="age" min="1"
                                    required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Region of residence</label>
                            <input type="text" class="form-control" id="address" name="address">
                        </div>

                        <div class="form-group">
                            <label for="feedback">Service/s Availed</label>
                            <textarea class="form-control" id="feedback" name="feedback" rows="3"
                                placeholder="Kindly describe the service(s) you availed"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="instuctions">Instructions</label>
                            <p class="text-muted">
                                <em class="text-bold">Check mark (&check;)</em> your answer to the Citizen's Charter (CC)
                                questions. The Cetizen's Charter is an official document
                                that reflects the services of a government agency/office including its requirements, fees,
                                and processing times among others.
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="ccQuestions">CC1 - Which of the following best describes your awareness of a
                                CC?</label>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ccQuestion1" name="ccQuestions[]"
                                    value="1">
                                <label class="form-check-label" for="ccQuestion1">
                                    1. I know what a CC is and I saw this office's CC.
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ccQuestion2" name="ccQuestions[]"
                                    value="2">
                                <label class="form-check-label" for="ccQuestion2">
                                    2. I know what a CC is but did NOT see this office's CC.
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ccQuestion3" name="ccQuestions[]"
                                    value="3">
                                <label class="form-check-label" for="ccQuestion3">
                                    3. I learned of the CC only when I saw the office's CC.
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ccQuestion4" name="ccQuestions[]"
                                    value="4">
                                <label class="form-check-label" for="ccQuestion4">
                                    4. I do not know what a CC is and did not see this office's CC. (Answer N/A on CC2 and
                                    CC3)
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ccQuestions">CC2 - If aware of CC (answered codes 1-3 in CC1), would you say that
                                the CC of this office was...?</label>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ccQuestion1" name="ccQuestions[]"
                                    value="1">
                                <label class="form-check-label" for="ccQuestion1">
                                    1. Easy to see
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ccQuestion2" name="ccQuestions[]"
                                    value="2">
                                <label class="form-check-label" for="ccQuestion2">
                                    2. Somewhat easy to see
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ccQuestion3" name="ccQuestions[]"
                                    value="3">
                                <label class="form-check-label" for="ccQuestion3">
                                    3. Difficult to see
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ccQuestion4" name="ccQuestions[]"
                                    value="4">
                                <label class="form-check-label" for="ccQuestion4">
                                    4. Not Visible at all
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ccQuestion5" name="ccQuestions[]"
                                    value="5">
                                <label class="form-check-label" for="ccQuestion5">
                                    5. N/A
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ccQuestions">CC3 - If aware of CC (answered codes 1-3 in CC1), how much did the CC
                                help you on your transaction?</label>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ccQuestion1" name="ccQuestions[]"
                                    value="1">
                                <label class="form-check-label" for="ccQuestion1">
                                    1. Help very much
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ccQuestion2" name="ccQuestions[]"
                                    value="2">
                                <label class="form-check-label" for="ccQuestion2">
                                    2. Somewhat helped
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ccQuestion3" name="ccQuestions[]"
                                    value="3">
                                <label class="form-check-label" for="ccQuestion3">
                                    3. Did not help
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ccQuestion4" name="ccQuestions[]"
                                    value="4">
                                <label class="form-check-label" for="ccQuestion4">
                                    4. N/A
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="instuctions">Instructions</label>
                            <p class="text-muted">
                                For SQD 0-8, please put a <em class="text-bold">Check mark (&check;)</em> on the column
                                that best corresponds to your answer.
                            </p>
                        </div>


                        <table class="survey-table">
                            <tbody>
                                @for ($i = 0; $i <= 8; $i++)
                                    <tr>
                                        <td style="width: 45%;"><strong>SQD{{ $i }}</strong> -
                                            {{ [
                                                'I am satisfied with the service that I availed.',
                                                'I spent a reasonable amount of time for my transaction.',
                                                "The Office followed the transaction's requirements and steps based on the information provided.",
                                                'The steps (including payment) I needed to do for my transaction were easy and simple.',
                                                'I easily found information about my transaction from the office or its website.',
                                                'I paid a reasonable amount of fees for my transaction. (If service was free, mark N/A).',
                                                "I feel the office was fair to everyone, or 'walang palakasan', during the transaction.",
                                                'I was treated courteously by the staff, and (if asked for help) the staff was helpful.',
                                                'I got what I needed from the government office, or (if denied) the denial of request was sufficiently explained to me.',
                                            ][$i] }}
                                        </td>
                                        <td class="radio-options">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check"><input type="checkbox"
                                                            name="sqd{{ $i }}[]" value="1"
                                                            class="form-check-input sqd{{ $i }}"><label>😠
                                                            Strongly Disagree</label></div>
                                                    <div class="form-check"><input type="checkbox"
                                                            name="sqd{{ $i }}[]" value="2"
                                                            class="form-check-input sqd{{ $i }}"><label>🙁
                                                            Disagree</label></div>
                                                    <div class="form-check"><input type="checkbox"
                                                            name="sqd{{ $i }}[]" value="3"
                                                            class="form-check-input sqd{{ $i }}"><label>😐
                                                            Neither Agree nor Disagree</label></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check"><input type="checkbox"
                                                            name="sqd{{ $i }}[]" value="4"
                                                            class="form-check-input sqd{{ $i }}"><label>🙂
                                                            Agree</label></div>
                                                    <div class="form-check"><input type="checkbox"
                                                            name="sqd{{ $i }}[]" value="5"
                                                            class="form-check-input sqd{{ $i }}"><label>😄
                                                            Strongly Agree</label></div>
                                                    <div class="form-check"><input type="checkbox"
                                                            name="sqd{{ $i }}[]" value="0"
                                                            class="form-check-input sqd{{ $i }}"><label>❓ Not
                                                            Applicable</label></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>

                        <div class="form-group">
                            <label for="suggestions">Suggestions on how we can further imporve our servies
                                (optional)</label>
                            <textarea class="form-control" id="suggestions" name="suggestions" rows="3"
                                placeholder="Please provide any suggestions or comments to help us improve our services."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="text" class="form-control" id="exampleInputPassword1"
                                value="{{ Auth::user()->email }}" readonly>
                        </div>
                        <div class="form-group text-center">
                            <label for="thanks" class="h5">Thank you!</label>
                        </div>
                        <div class="text-left">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Fixed Footer -->
    <footer class="text-muted text-center py-2 bg-white"
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
        $(function() {
            bsCustomFileInput.init();
        });
    </script>


    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const fields = ['sqd0', 'sqd1', 'sqd2', 'sqd3', 'sqd4', 'sqd5', 'sqd6', 'sqd7', 'sqd8'];
            let valid = true;

            fields.forEach(field => {
                const checkboxes = document.querySelectorAll('input[name="' + field + '[]"]');
                const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

                if (!isChecked) {
                    valid = false;
                    checkboxes[0].closest('td').style.border = '2px solid red';
                } else {
                    checkboxes[0].closest('td').style.border = '';
                }
            });

            if (!valid) {
                alert("Please answer all survey questions.");
                e.preventDefault();
            }
        });
    </script>
@endsection
