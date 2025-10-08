@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <h5 class="card-header">Edit Tournament</h5>
                <div class="card-body">
                    <form action="{{ route('admin.tournament.update') }}" method="POST" id="tournamentForm">
                        @csrf
                        <input type="hidden" name="id" value="{{ $tournament->id }}" id="">

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name">Tournament Name</label>
                                <input id="name" type="text" class="form-control" name="name"
                                    value="{{ $tournament->name }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="date">Date</label>
                                <input id="date" type="date" name="date" class="form-control"
                                    value="{{ $tournament->date }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="start_time">Start Time</label>
                                <input id="start_time" type="time" name="start_time" class="form-control"
                                    value="{{ $tournament->start_time }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="end_time">End Time</label>
                                <input id="end_time" type="time" name="end_time" class="form-control"
                                    value="{{ $tournament->end_time }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="time_to_enter">Time to Enter</label>
                                <input id="time_to_enter" type="time" name="time_to_enter" class="form-control"
                                    value="{{ $tournament->time_to_enter }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="open_close">Open / Close</label>
                                <select name="open_close" class="form-control">
                                    <option value="open" {{ $tournament->open_close == 'open' ? 'selected' : '' }}>Open
                                    </option>
                                    <option value="close" {{ $tournament->open_close == 'close' ? 'selected' : '' }}>Close
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="time_or_free">Tournament Type</label>
                                <select name="time_or_free" id="time_or_free" class="form-control">
                                    <option value="time" {{ $tournament->time_or_free == 'time' ? 'selected' : '' }}>Knock
                                        Out</option>
                                    <option value="free_form"
                                        {{ $tournament->time_or_free == 'free_form' ? 'selected' : '' }}>Free Form</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="elimination_type">Elimination Type</label>
                                <select name="elimination_type" id="elimination_type" class="form-control">
                                    <option value="all" {{ $tournament->elimination_type == 'all' ? 'selected' : '' }}>
                                        Play till end</option>
                                    <option value="percentage"
                                        {{ $tournament->elimination_type == 'percentage' ? 'selected' : '' }}>Eliminate by
                                        Percentage</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="elimination_percent">Elimination %</label>
                                <input id="elimination_percent" type="number" name="elimination_percent"
                                    class="form-control" value="{{ $tournament->elimination_percent }}"
                                    {{ $tournament->elimination_type == 'percentage' ? '' : 'disabled' }}>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="status">Status</label>
                                <select name="status" class="form-control">
                                    <option value="active" {{ $tournament->status == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ $tournament->status == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                    <option value="inprogress" {{ $tournament->status == 'inprogress' ? 'selected' : '' }}>
                                        In Progress</option>
                                    <option value="completed" {{ $tournament->status == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <button type="button" class="btn btn-success mt-4" id="add-round">+ Add Round</button>
                            </div>
                        </div>

                        {{-- Existing Rounds --}}
                        <div id="rounds-section"
                            style="{{ $tournament->tournament_rounds->count() ? '' : 'display:none;' }}">
                            <h5 class="mt-4">Rounds Configuration</h5>
                            <div id="rounds-container">
                                @foreach ($tournament->tournament_rounds as $index => $round)
                                    <div class="card p-3 mb-3">
                                        <h6 class="round-title">Round {{ $index + 1 }}</h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Select Game</label>
                                                <select name="game_id[]" class="form-control" required>
                                                    @foreach ($games as $game)
                                                        <option value="{{ $game->id }}"
                                                            {{ $round->game_id == $game->id ? 'selected' : '' }}>
                                                            {{ $game->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Start Time</label>
                                                <input type="time" name="round_start_time[]"
                                                    class="form-control round-start" value="{{ $round->start_time }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label>End Time</label>
                                                <input type="time" name="round_end_time[]"
                                                    class="form-control round-end" value="{{ $round->end_time }}">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger remove-round">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Tournament</button>
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

            let roundCount = $("#rounds-container .card").length;

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

            // parseTime: returns a Date object for comparison or null
            function parseTime(str) {
                if (!str) return null;
                const [h, m] = str.split(":").map(Number);
                if (isNaN(h) || isNaN(m)) return null;
                const d = new Date();
                d.setHours(h, m, 0, 0);
                d.setSeconds(0);
                d.setMilliseconds(0);
                return d;
            }

            // Validate a single round (passed the changed input or a jQuery round card context)
            function validateRoundTime($input) {
                // Accept either a direct input (.round-start/.round-end) or a round card element
                let $roundCard;
                if ($input.hasClass('round-start') || $input.hasClass('round-end')) {
                    $roundCard = $input.closest('.card');
                } else {
                    $roundCard = $input; // assume it's the card
                }

                const $rs = $roundCard.find(".round-start");
                const $re = $roundCard.find(".round-end");

                const rsVal = $rs.val();
                const reVal = $re.val();

                const rsTime = parseTime(rsVal);
                const reTime = parseTime(reVal);

                const tStart = parseTime($startTimeInput.val());
                const tEnd = parseTime($endTimeInput.val());

                // clear previous invalid classes
                $rs.removeClass("is-invalid");
                $re.removeClass("is-invalid");

                // If tournament start/end are not set, skip strict validation
                if (!tStart || !tEnd) return;

                // Validate each against tournament bounds
                if (rsVal && rsTime) {
                    if (rsTime < tStart || rsTime >= tEnd) {
                        toastr.error("Round start time must be within tournament time.");
                        $rs.addClass("is-invalid").val("");
                        return;
                    }
                }

                if (reVal && reTime) {
                    if (reTime > tEnd || reTime <= tStart) {
                        toastr.error("Round end time must be within tournament time.");
                        $re.addClass("is-invalid").val("");
                        return;
                    }
                }

                // Validate start < end for the same round
                if (rsVal && reVal && rsTime && reTime) {
                    if (rsTime >= reTime) {
                        toastr.error("Round start must be before round end.");
                        $re.addClass("is-invalid").val("");
                        return;
                    }
                }
            }

            // Attach handlers to existing round inputs (important for edit page)
            function bindExistingRoundHandlers() {
                $roundsContainer.off('change', '.round-start, .round-end'); // avoid duplicate handlers
                $roundsContainer.on('change', '.round-start, .round-end', function() {
                    validateRoundTime($(this));
                });
            }
            bindExistingRoundHandlers();

            // Enable/disable elimination % input
            $eliminationType.on("change", function() {
                $eliminationPercent.prop("disabled", $(this).val() !== "percentage");
            });

            // Date validation: not in past
            $dateInput.on("change", function() {
                const today = new Date().toISOString().split("T")[0];
                if ($(this).val() < today) {
                    toastr.error('Tournament date cannot be in the past.');
                    $(this).addClass("is-invalid").val("");
                } else {
                    $(this).removeClass("is-invalid");
                }
            });

            // Tournament start/end validation. Also re-validate rounds when tournament times change.
            $startTimeInput.add($endTimeInput).on("change", function() {
                validateTournamentTime();
            });

            function validateTournamentTime() {
                const tStartVal = $startTimeInput.val();
                const tEndVal = $endTimeInput.val();

                // remove invalid classes
                $startTimeInput.removeClass("is-invalid");
                $endTimeInput.removeClass("is-invalid");

                if (tStartVal && tEndVal) {
                    const tStart = parseTime(tStartVal);
                    const tEnd = parseTime(tEndVal);
                    if (!tStart || !tEnd) return;

                    if (tStart >= tEnd) {
                        toastr.error("Start time must be before end time.");
                        $endTimeInput.addClass("is-invalid").val("");
                        return;
                    }

                    // re-validate all rounds against these new bounds
                    $roundsContainer.find(".card").each(function() {
                        validateRoundTime($(this));
                    });
                }

                validateTimeToEnter();
            }

            // Entry time < start time
            $timeToEnter.on("change", function() {
                if ($timeToEnter.val() && $startTimeInput.val()) {
                    const entry = parseTime($timeToEnter.val());
                    const start = parseTime($startTimeInput.val());
                    if (entry && start && entry >= start) {
                        toastr.error("Entry time must be before tournament start.");
                        $timeToEnter.addClass("is-invalid").val("");
                    } else {
                        $timeToEnter.removeClass("is-invalid");
                    }
                }
            });

            // Add new round
            $addRoundBtn.on("click", function() {
                // For knockout tournaments, require tournament start/end to be present
                const isKnockOut = $tournamentType.val() === "time";
                if (isKnockOut && (!$startTimeInput.val() || !$endTimeInput.val())) {
                    toastr.error("Please set tournament start & end time first.");
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

                // Ensure new round's time fields match current tournament type (knockout/free-form)
                toggleRoundTimeFields();

                // Bind validation to newly added round inputs (delegate already set, but bind for immediate)
                $roundDiv.find(".round-start, .round-end").on("change", function() {
                    validateRoundTime($(this));
                });
            });

            // Remove round
            $roundsContainer.on("click", ".remove-round", function() {
                $(this).closest(".card").remove();
                reorderRounds();
            });

            function reorderRounds() {
                let i = 1;
                $roundsContainer.find(".round-title").each(function() {
                    $(this).text(`Round ${i++}`);
                });
                roundCount = i - 1;
            }

            function validateRoundTimeOnLoad() {
                // Validate all existing rounds once when page loads (in case some saved values are invalid)
                $roundsContainer.find(".card").each(function() {
                    validateRoundTime($(this));
                });
            }

            // Knockout vs Free-form toggle
            $tournamentType.on("change", toggleRoundTimeFields);

            function toggleRoundTimeFields() {
                const isKnockOut = $tournamentType.val() === "time";
                if (isKnockOut) {
                    $(".round-start, .round-end")
                        .prop("disabled", false)
                        .attr("required", true);
                } else {
                    // For free-form remove requirement and clear times (optional)
                    $(".round-start, .round-end")
                        .prop("disabled", true)
                        .removeAttr("required")
                        .val(""); // clear if you prefer to keep values, remove .val("") line
                }
            }

            // run once on page load
            toggleRoundTimeFields();
            bindExistingRoundHandlers();
            validateRoundTimeOnLoad();

            // Form submit validation
            $("#tournamentForm").on("submit", function(e) {
                let isValid = true;

                $(this).find("[required]").each(function() {
                    if (!$(this).val()) {
                        $(this).addClass("is-invalid");
                        isValid = false;
                    } else {
                        $(this).removeClass("is-invalid");
                    }
                });

                if ($roundsContainer.find(".card").length === 0) {
                    toastr.error("Please add at least one round.");
                    isValid = false;
                }

                // Final cross-check: ensure all rounds have valid times if knockout
                if ($tournamentType.val() === "time") {
                    $roundsContainer.find(".card").each(function() {
                        const $rs = $(this).find(".round-start");
                        const $re = $(this).find(".round-end");

                        if (!$rs.val() || !$re.val()) {
                            toastr.error(
                                "All rounds must have start and end times for Knock Out tournaments."
                                );
                            isValid = false;
                            $rs.addClass("is-invalid");
                            $re.addClass("is-invalid");
                        } else {
                            // Use validateRoundTime to do final sanity check (it will clear invalids)
                            validateRoundTime($rs);
                            // If validateRoundTime cleared value, it set is-invalid; test again:
                            if (!$rs.val() || !$re.val()) {
                                isValid = false;
                            }
                        }
                    });
                }

                if (!isValid) {
                    e.preventDefault();
                    toastr.error("Please fix errors before submitting.");
                    return false;
                }

                // allow form submit
                return true;
            });
        });
    </script>
@endsection
