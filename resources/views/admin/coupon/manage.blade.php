@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')

<div class="container-fluid">
    <style>
        .form-label {
            font-weight: bold;
        }
    </style>
    <form id="coupon-form" action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        <input type="hidden" name="edit_id" id="edit_id" value="{{ !empty($coupon->id) ? $coupon->id : '' }}">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header py-3">
                        <h4 class="card-title mt-1"><i class="fas fa-ticket-alt"></i> {{ $action }} {{ $title }}</h4>
                        <div class="card-tools">
                            <a href="{{ route('admin.coupons') }}" class="btn btn-secondary pull-right">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Coupon Details -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                    <label>Coupon Details:</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code" class="form-label">Coupon Code</label>
                                    <input type="text" class="form-control" name="code" id="code"
                                           value="{{ !empty($coupon->code) ? $coupon->code : '' }}"
                                           placeholder="Enter coupon code" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_type" class="form-label">Discount Type</label>
                                    <select name="discount_type" id="discount_type" class="form-control">
                                        <option value="fixed" {{ !empty($coupon->discount_type) && $coupon->discount_type == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                        <option value="percentage" {{ !empty($coupon->discount_type) && $coupon->discount_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_value" class="form-label">Discount Value</label>
                                    <input type="number" step="0.01" class="form-control" name="discount_value" id="discount_value"
                                           value="{{ !empty($coupon->discount_value) ? $coupon->discount_value : '' }}"
                                           placeholder="Enter discount value" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="min_order_value" class="form-label">Minimum Order Value</label>
                                    <input type="number" step="0.01" class="form-control" name="min_order_value" id="min_order_value"
                                           value="{{ !empty($coupon->min_order_value) ? $coupon->min_order_value : '' }}"
                                           placeholder="Enter minimum order value">
                                </div>
                            </div>
                        </div>

                        <!-- Validity -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                    <label>Validity:</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" id="start_date"
                                           value="{{ !empty($coupon->start_date) ? \Illuminate\Support\Carbon::parse($coupon->start_date)->format('Y-m-d') : '' }}"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date"
                                           value="{{ !empty($coupon->end_date) ? \Illuminate\Support\Carbon::parse($coupon->end_date)->format('Y-m-d') : '' }}"
                                           required>
                                </div>
                            </div>

                        </div>

                        <!-- Limits -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                    <label>Limits:</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="usage_limit" class="form-label">Usage Limit</label>
                                    <input type="number" class="form-control" name="usage_limit" id="usage_limit"
                                           value="{{ !empty($coupon->usage_limit) ? $coupon->usage_limit : '' }}"
                                           placeholder="Enter total usage limit">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="usage_per_customer" class="form-label">Usage Per Customer</label>
                                    <input type="number" class="form-control" name="usage_per_customer" id="usage_per_customer"
                                           value="{{ !empty($coupon->usage_per_customer) ? $coupon->usage_per_customer : '' }}"
                                           placeholder="Enter usage limit per customer">
                                </div>
                            </div>
                        </div>

                        <!-- Allowed Categories -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                    <label>Allowed Categories:</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="allow_category" class="form-label">Select Categories (Optional)</label>
                                    @php
                                        $selectedCategories = old('allow_category', !empty($coupon->allow_category) ? explode(',', $coupon->allow_category) : []);
                                    @endphp
                                    <select name="allow_category[]" id="allow_category" class="form-control" multiple>
                                        @if(!empty($categories))
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="form-text text-muted">Leave empty to allow for all categories.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="description" id="description"
                                              placeholder="Please enter description">{!! old('description', !empty($coupon->description) ? $coupon->description : '') !!}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="1" {{ !empty($coupon) && $coupon->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !empty($coupon) && $coupon->status == 0 ? 'selected' : '' }}>Inactive</option>
                                        <option value="2" {{ !empty($coupon) && $coupon->status == 2 ? 'selected' : '' }}>Expired</option>

                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <button class="btn px-5 bg-gradient-dark btn-flat float-right" type="submit">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('sub-script')
    <script type="text/javascript">
        (function() {
            function initFormEnhancements() {
                if (typeof CKEDITOR !== 'undefined') {
                    if (CKEDITOR.instances.description) {
                        CKEDITOR.instances.description.destroy(true);
                    }
                    CKEDITOR.replace('description');
                }

                const $categorySelect = $('#allow_category');
                const selectedValues = @json($selectedCategories ?? []);

                $categorySelect.select2({
                    placeholder: 'Select categories',
                    allowClear: true,
                    width: '100%',
                    theme: 'bootstrap4',
                    templateResult: function (data) {
                        if (!data.id) {
                            return data.text;
                        }
                        if ($(data.element).prop('selected')) {
                            return null;
                        }
                        return data.text;
                    },
                    templateSelection: function (data) {
                        return data.text;
                    }
                });

                if (selectedValues.length) {
                    $categorySelect.val(selectedValues).trigger('change');
                }

                $('#coupon-form').on('submit', function () {
                    if (typeof CKEDITOR !== 'undefined') {
                        for (const instance in CKEDITOR.instances) {
                            if (Object.prototype.hasOwnProperty.call(CKEDITOR.instances, instance)) {
                                CKEDITOR.instances[instance].updateElement();
                            }
                        }
                    }
                });
            }

            function loadCkEditor(callback) {
                if (typeof CKEDITOR !== 'undefined') {
                    callback();
                    return;
                }
                const script = document.createElement('script');
                script.src = 'https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js';
                script.onload = callback;
                document.head.appendChild(script);
            }

            $(document).ready(function() {
                loadCkEditor(initFormEnhancements);
            });
        })();
    </script>
@endpush
