@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <h5 class="card-header">Add Game</h5>
                <div class="card-body">
                    <form action="{{ route('admin.game.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="inputText3" class="col-form-label">Title</label>
                                <input id="inputText3" type="text" class="form-control" name="title">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputEmail">Image</label>
                                <input id="inputEmail" type="file" name="image" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Description</label>
                            <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="inputPassword">Status</label>
                                <select name="status" class="form-control">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword">Slug</label>
                                <input type="text" name="slug" id="" class="form-control" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Rules</label>
                            <div id="rules-container">
                                <div class="input-group mb-2">
                                    <input type="text" name="rules[]" class="form-control" placeholder="Enter a rule">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-success add-rule">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const rulesContainer = document.getElementById("rules-container");

            // Add new rule input
            rulesContainer.addEventListener("click", function (e) {
                if (e.target.classList.contains("add-rule")) {
                    const newRule = document.createElement("div");
                    newRule.classList.add("input-group", "mb-2");
                    newRule.innerHTML = `
                        <input type="text" name="rules[]" class="form-control" placeholder="Enter a rule">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger remove-rule">-</button>
                        </div>
                    `;
                    rulesContainer.appendChild(newRule);
                }

                // Remove rule input
                if (e.target.classList.contains("remove-rule")) {
                    e.target.closest(".input-group").remove();
                }
            });
        });
    </script>
@endsection
