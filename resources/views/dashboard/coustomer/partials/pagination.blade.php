<!--begin::Pagination-->
@if($customers->hasPages())
<div class="d-flex justify-content-between align-items-center flex-wrap pt-6">
    <div class="fs-6 fw-bold text-gray-700">
        Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} customers
    </div>
    <div class="d-flex align-items-center">
        {{ $customers->appends(request()->query())->links('custom-pagination') }}
    </div>
</div>
@endif
<!--end::Pagination-->
