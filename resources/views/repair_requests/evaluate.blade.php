@extends('layouts.app')

@section('content')
<h2 class="text-center mb-2">แบบประเมินการแจ้งซ่อมของ #{{ $request->TicketNumber }}</h2> 

<div class="container d-flex justify-content-center align-items-center" style="min-height: 50vh;">
    <div class="card p-5 shadow-lg rounded" style="width: 100%; max-width: 900px; min-height: 400px;">

        <!-- แสดงข้อความสำเร็จ -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>สำเร็จ!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form id="ratingForm" action="{{ route('repair_requests.evaluate.store', $request->TicketID) }}" method="POST">
            @csrf
            <input type="hidden" id="rating" name="rating" value="">

            <!-- แบบฟอร์มประเมิน -->
            <div class="form-group text-center mb-4">
                <label for="rating"></label>
                <div class="row justify-content-center">
                    <!-- Rating 1 -->
                    <div class="col-md-4 col-sm-6 mb-3">
                        <label class="d-block">
                            <img src="/images/unhappy.png" alt="Rating 1" class="img-fluid mb-2 rating-image" id="rating1" style="max-width: 120px; cursor: pointer;" onclick="selectRating(1)">
                        </label>
                    </div>
                    <!-- Rating 2 -->
                    <div class="col-md-4 col-sm-6 mb-3">
                        <label class="d-block">
                            <img src="/images/neutral.png" alt="Rating 2" class="img-fluid mb-2 rating-image" id="rating2" style="max-width: 120px; cursor: pointer;" onclick="selectRating(2)">
                        </label>
                    </div>
                    <!-- Rating 3 -->
                    <div class="col-md-4 col-sm-6 mb-3">
                        <label class="d-block">
                            <img src="/images/smile.png" alt="Rating 3" class="img-fluid mb-2 rating-image" id="rating3" style="max-width: 120px; cursor: pointer;" onclick="selectRating(3)">
                        </label>
                    </div>
                </div>
            </div>

            <!-- ปุ่มส่งแบบประเมิน -->
            <div class="d-flex justify-content-center">
                <button type="button" onclick="confirmSubmit()" class="btn btn-primary btn-lg px-5 py-2" style="border: 2px solid #0056b3; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
                    ส่งแบบประเมิน
                </button>
            </div>
        </form>
    </div>
</div>

<!-- CSS สำหรับการเลือกภาพ -->
<style>
    .rating-image {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
</style>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ฟังก์ชันเลือกคะแนน
    function selectRating(rating) {
        // คืนค่ารูปภาพทั้งหมดเป็นภาพปกติ
        document.getElementById('rating1').src = '/images/unhappy.png';
        document.getElementById('rating2').src = '/images/neutral.png';
        document.getElementById('rating3').src = '/images/smile.png';

        // เปลี่ยนรูปภาพที่ถูกเลือกให้เป็นรูปใหม่
        if (rating === 1) {
            document.getElementById('rating1').src = '/images/unhappy_red.png'; // ใส่ภาพที่คุณต้องการสำหรับ Rating 1
        } else if (rating === 2) {
            document.getElementById('rating2').src = '/images/neutral_red.png'; // ใส่ภาพที่คุณต้องการสำหรับ Rating 2
        } else if (rating === 3) {
            document.getElementById('rating3').src = '/images/smile_red.png'; // ใส่ภาพที่คุณต้องการสำหรับ Rating 3
        }

        // กำหนดค่าคะแนนให้ hidden input
        document.getElementById('rating').value = rating;
    }

    // ฟังก์ชันยืนยันการส่งแบบประเมินด้วย SweetAlert2
    function confirmSubmit() {
        const rating = document.getElementById('rating').value;
        if (rating === "") {
            Swal.fire({
                icon: 'warning',
                title: 'กรุณาเลือกคะแนนก่อน',
                showConfirmButton: false,
                timer: 1500
            });
        } else {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณจะไม่สามารถเปลี่ยนแปลงการประเมินได้หลังจากส่ง",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ส่งแบบฟอร์มเมื่อยืนยัน
                    document.getElementById('ratingForm').submit();
                }
            });
        }
    }
</script>
@endsection
