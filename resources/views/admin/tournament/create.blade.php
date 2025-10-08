@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <h5 class="card-header">Add Tournament</h5>
                <div class="card-body">
                    <form action="{{ route('admin.tournament.store') }}" method="POST" id="tournamentForm">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name" class="col-form-label">Tournament Name</label>
                                <input id="name" type="text" class="form-control" name="name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="date">Date</label>
                                <input id="date" type="date" name="date" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="start_time">Start Time</label>
                                <input id="start_time" type="time" name="start_time" class="form-control" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="end_time">End Time</label>
                                <input id="end_time" type="time" name="end_time" class="form-control" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="time_to_enter">Time to Enter (Optional)</label>
                                <input id="time_to_enter" type="time" name="time_to_enter" class="form-control">
                            </div>
                        </div>

                        <div id="duration-info" class="alert alert-info" style="display:none;">
                            Tournament Duration: <span id="total-duration">0</span> minutes |
                            Remaining: <span id="remaining-duration">0</span> minutes
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="open_close">Open / Close</label>
                                <select name="open_close" class="form-control">
                                    <option value="open" selected>Open</option>
                                    <option value="close">Close</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="time_or_free">Tournament Type</label>
                                <select name="time_or_free" id="time_or_free" class="form-control">
                                    <option value="time" selected>Knock Out</option>
                                    <option value="free_form">Free Form</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="elimination_type">Elimination Type</label>
                                <select name="elimination_type" id="elimination_type" class="form-control">
                                    <option value="all" selected>Play till end</option>
                                    <option value="percentage">Eliminate by Percentage</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="elimination_percent">Elimination %</label>
                                <input id="elimination_percent" type="number" name="elimination_percent"
                                    class="form-control" placeholder="Enter percentage (if applicable)" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="status">Status</label>
                                <select name="status" class="form-control">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="inprogress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <button type="button" class="btn btn-success mt-4" id="add-round">+ Add Round</button>
                            </div>
                        </div>

                        {{-- Dynamic Rounds Section --}}
                        <div id="rounds-section" style="display: none;">
                            <h5 class="mt-4">Rounds Configuration</h5>
                            <div id="rounds-container"></div>
                        </div>

                        <button type="" class="btn btn-primary save_tournament">Create Tournament</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "timeOut": "4000"
            };

            let roundCount = 0;

            const $roundsSection = $("#rounds-section");
            const $roundsContainer = $("#rounds-container");
            const $tournamentType = $("#time_or_free");
            const $eliminationType = $("#elimination_type");
            const $eliminationPercent = $("#elimination_percent");
            const $addRoundBtn = $("#add-round");
            const $startTimeInput = $("#start_time");
            const $endTimeInput = $("#end_time");
            const $timeToEnter = $("#time_to_enter");
            const $dateInput = $("#date");
            const $totalDurationEl = $("#total-duration");
            const $remainingDurationEl = $("#remaining-duration");
            const $durationInfo = $("#duration-info");

            // Validation: disable elimination percent unless type is percentage
            $eliminationType.on("change", function() {
                $eliminationPercent.prop("disabled", $(this).val() !== "percentage");
            });

            // Date validation: must not be in past
            $dateInput.on("change", function() {
                const today = new Date().toISOString().split("T")[0];
                if ($(this).val() < today) {
                    toastr.error('Tournament date cannot be in the past.');
                    $(this).addClass("is-invalid").val("");
                } else {
                    $(this).removeClass("is-invalid");
                }
            });

            // Time validation: start < end
            $startTimeInput.add($endTimeInput).on("change", validateTournamentTime);

            function validateTournamentTime() {
                $startTimeInput.removeClass("is-invalid");
                $endTimeInput.removeClass("is-invalid");

                if ($startTimeInput.val() && $endTimeInput.val()) {
                    if ($startTimeInput.val() >= $endTimeInput.val()) {
                        toastr.error("Tournament start time must be before end time.");
                        $endTimeInput.addClass("is-invalid").val("");
                        return;
                    }
                    updateDuration();
                }
                validateTimeToEnter(); // also check entry time again
            }

            // Validate time_to_enter < start_time
            $timeToEnter.on("change", validateTimeToEnter);

            function validateTimeToEnter() {
                $timeToEnter.removeClass("is-invalid");
                if ($timeToEnter.val() && $startTimeInput.val()) {
                    if ($timeToEnter.val() >= $startTimeInput.val()) {
                        toastr.error("Time to enter must be before tournament start time.");
                        $timeToEnter.addClass("is-invalid").val("");
                    }
                }
            }

            function updateDuration() {
                const start = parseTime($startTimeInput.val());
                const end = parseTime($endTimeInput.val());
                if (!start || !end) return;

                const totalMinutes = (end - start) / 60000;
                $durationInfo.show();
                $totalDurationEl.text(totalMinutes);
                updateRemainingDuration();
            }

            function updateRemainingDuration() {
                const start = parseTime($startTimeInput.val());
                const end = parseTime($endTimeInput.val());
                if (!start || !end) return;

                let totalMinutes = (end - start) / 60000;
                let usedMinutes = 0;

                $roundsContainer.find(".card").each(function() {
                    const rs = $(this).find(".round-start").val();
                    const re = $(this).find(".round-end").val();
                    if (rs && re) {
                        const rsTime = parseTime(rs);
                        const reTime = parseTime(re);
                        if (rsTime && reTime && rsTime < reTime) {
                            usedMinutes += (reTime - rsTime) / 60000;
                        }
                    }
                });

                $remainingDurationEl.text(totalMinutes - usedMinutes);
            }

            function parseTime(str) {
                if (!str) return null;
                const [h, m] = str.split(":").map(Number);
                const d = new Date();
                d.setHours(h, m, 0, 0);
                return d;
            }

            // Add new round
            $addRoundBtn.on("click", function() {
                if (!$startTimeInput.val() || !$endTimeInput.val()) {
                    toastr.error("Please select tournament start and end times first.");
                    $startTimeInput.addClass("is-invalid");
                    $endTimeInput.addClass("is-invalid");
                    return;
                }

                roundCount++;
                $roundsSection.show();

                const roundHtml = `
        <div class="card p-3 mb-3">
            <h6 class="round-title">Round ${roundCount}</h6>
            <div class="row">
                <div class="col-md-4">
                    <label>Select Game</label>
                    <select name="game_id[]" class="form-control" required>
                        @foreach ($games as $game)
                            <option value="{{ $game->id }}">{{ $game->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Start Time</label>
                    <input type="time" name="round_start_time[]" class="form-control round-start" required>
                </div>
                <div class="col-md-3">
                    <label>End Time</label>
                    <input type="time" name="round_end_time[]" class="form-control round-end" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-round">Remove</button>
                </div>
            </div>
        </div>`;

                const $roundDiv = $(roundHtml).appendTo($roundsContainer);

                toggleRoundTimeFields();
                reorderRounds(); // ensure numbering is always correct

                // Track round time changes
                $roundDiv.find(".round-start, .round-end").on("change", function() {
                    validateRoundTime($(this));
                    updateRemainingDuration();
                });
            });

            // Remove round and re-sort numbers
            $roundsContainer.on("click", ".remove-round", function() {
                $(this).closest(".card").remove();
                reorderRounds();
                updateRemainingDuration();
            });

            function reorderRounds() {
                let i = 1;
                $roundsContainer.find(".round-title").each(function() {
                    $(this).text(`Round ${i++}`);
                });
                roundCount = i - 1; // update roundCount so next round continues correctly
            }

            function validateRoundTime($input) {
                const $roundCard = $input.closest(".card");
                const $rsInput = $roundCard.find(".round-start");
                const $reInput = $roundCard.find(".round-end");

                const rs = $rsInput.val();
                const re = $reInput.val();

                const tStart = parseTime($startTimeInput.val());
                const tEnd = parseTime($endTimeInput.val());

                $rsInput.removeClass("is-invalid");
                $reInput.removeClass("is-invalid");

                if (rs && (parseTime(rs) < tStart || parseTime(rs) > tEnd)) {
                    toastr.error("Round start time must be within tournament time.");
                    $rsInput.addClass("is-invalid").val("");
                }
                if (re && (parseTime(re) > tEnd || parseTime(re) < tStart)) {
                    toastr.error("Round end time must be within tournament time.");
                    $reInput.addClass("is-invalid").val("");
                }
                if (rs && re && parseTime(rs) >= parseTime(re)) {
                    toastr.error("Round start must be before round end.");
                    $reInput.addClass("is-invalid").val("");
                }
            }

            // Toggle round start/end times based on tournament type
            $tournamentType.on("change", toggleRoundTimeFields);

            function toggleRoundTimeFields() {
                const isKnockOut = $tournamentType.val() === "time";

                if (isKnockOut) {
                    $(".round-start, .round-end")
                        .prop("disabled", false)
                        .attr("required", true);
                } else {
                    $(".round-start, .round-end")
                        .prop("disabled", true)
                        .removeAttr("required")
                        .val(""); // optional: clear values when free form
                }
            }


            // Save tournament validation
            $(".save_tournament").on("click", function(e) {
                e.preventDefault();

                let isValid = true;

                // Required field check
                $("#tournamentForm [required]").each(function() {
                    if (!$(this).val()) {
                        $(this).addClass("is-invalid");
                        isValid = false;
                    } else {
                        $(this).removeClass("is-invalid");
                    }
                });

                // At least one round check
                if ($roundsContainer.find(".card").length === 0) {
                    toastr.error("Please add at least one round before saving.");
                    isValid = false;
                }

                if (!isValid) {
                    toastr.error("Please fill all required fields correctly.");
                    return;
                }

                // If everything valid → submit
                $("#tournamentForm").submit();
            });
        });
    </script>
@endsection
