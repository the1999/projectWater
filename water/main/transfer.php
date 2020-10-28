<?php
include_once('header.php');
// $secure = "OMP?JFC/p|og^JP";
$connection = connectDB();

$order_id = $_GET['id'];

?>

<div class="container-login100" style="background-image: url('../template/login_v2/images/water-bg.jpg');">
    <div class="wrap-login100 p-l-55 p-r-55 p-t-80 p-b-30" style="width: 50%;">
        <h4 class="text-center" style="margin-top: -53px;">โอนเงิน</h4>

        <form id="frm_transfer" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">

            <!-- <input type="hidden" name="price"> -->

            <div class="ibox-content">

                <h5>ธนาคารไทยพาณิชย์</h5>
                <h5>เลขที่บัญชี : 861-XXX-XXXX</h5>

                <div class="custom-file">
                    <input id="logo" type="file" name="image" class="custom-file-input">
                    <label for="logo" class="custom-file-label">Choose file...</label>
                </div>

            </div>



            <div style="float: left;">
                <a href="order_list.php?id=<?php echo $order_id ?>">
                    <p><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> ย้อนกลับ</p>
                </a>

            </div>

            <div style="float: right;">

                <button type="button" class="login200-form-btn" onclick="submit_insert();">
                    ยืนยัน
                </button>

            </div>

        </form>

    </div>
</div>




<?php include('import_script.php'); ?>

<script>

    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
    // $(document).ready(function() {
    //     load_table();
    // });




    function submit_insert() {

        var formData = new FormData($("#frm_transfer")[0]);

        swal({
            title: 'กรุณายืนยันเพื่อทำรายการ',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'ยกเลิก',
            confirmButtonText: 'ยืนยัน',
            closeOnConfirm: false
        }, function() {

            $.ajax({
                type: 'POST',
                url: 'ajax/transfer/insert.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(data) {
                    if (data.result == 0) {
                        return false;
                        swal({
                            title: 'ผิดพลาด!',
                            text: 'กรุณากรอกข้อมูลให้ถูกต้อง !',
                            type: 'warning'
                        });
                        return false;
                    }
                    if (data.result == 1) {
                        $('#modal').modal('hide');
                        swal({
                            title: "ดำเนินการสำเร็จ!",
                            text: "ทำการบันทึกรายการ เรียบร้อย",
                            type: "success",
                            showConfirmButton: true
                        }, function() {
                            location.href = "index.php";
                        });
                    }
                }
            })
        });
    }
</script>

</body>

</html>