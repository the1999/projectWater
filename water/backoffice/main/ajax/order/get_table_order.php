<?php

session_start();

include('../../../../config/main_function.php');

// $secure = "OMP?JFC/p|og^JP";
$connection = connectDB();

?>

<div class="table-responsive">

    <table class="table table-striped dataTables-example">

        <thead>

            <tr>

                <th>#</th>

                <th>เลขที่คำสั่งซื้อ</th>

                <th>ผู้ซื้อ</th>

                <th>วันที่สั่งซื้อ</th>

                <th>สินค้าที่ซื้อ</th>

                <th>จำนวนเงิน</th>

                <th>หลักฐานการโอน</th>

                <th>วันที่โอน</th>

                <th>สถานะ</th>

                <th width="10%"></th>

            </tr>

        </thead>

        <tbody>

            <?php

            $sql = "SELECT a.*,b.username FROM tbl_order_head a
            LEFT JOIN tbl_member b ON a.member_id = b.member_id";

            $rs = mysqli_query($connection, $sql) or die($connection->error);

            $i = 0;

            while ($row = mysqli_fetch_assoc($rs)) {

                $i++;

            ?>

                <tr id="tr_<?php echo $row['order_id']; ?>">

                    <td><?php echo $i; ?></td>


                    <td>
                        <?php echo $row['order_no']; ?>
                    </td>

                    <td>
                        <?php echo $row['username']; ?>
                    </td>


                    <td>
                        <?php echo date('d-m-Y H:i:s', strtotime($row['order_date'])); ?>
                    </td>

                    <td>
                        <?php

                        $sql_name = "SELECT a.*,c.product_name FROM tbl_order_head a
                        LEFT JOIN tbl_order_detail b ON a.order_id = b.order_id
                        LEFT JOIN tbl_product c ON b.product_id = c.product_id 
                        WHERE b.order_id = '{$row['order_id']}' ";

                        $rs_name = mysqli_query($connection, $sql_name) or die($connection->error);

                        $i = 0;
                        while ($row_name = mysqli_fetch_assoc($rs_name)) {
                            $i++;
                            echo $row_name['product_name'];
                            if ($i != $rs_name->num_rows) {
                                echo ",";
                            }
                        }
                        ?>
                    </td>

                    <td>
                        <?php echo $row['payment_amont']; ?>
                    </td>

                    <td>
                    <?php
                        $url;
                        if ($row['payment_slip'] == NULL) {
                            $url = 'no-image.jpg';
                        } else {
                            $url ='payment/'. $row['payment_slip'];
                        } ?>
                        <a target="_bank" href="../../image/<?php echo $url; ?>">
                        <img src="../../image/<?php echo $url; ?>" style="object-fit:cover;width:200px;height:200px;">
                        </a>
                    </td>

                    <td>
                        <?php echo date("d-m-Y", strtotime($row['approve_payment_date'])); ?>
                    </td>

                    <td>
                        <?php if ($row['status'] == 1) {
                            echo "รอชำระเงิน";
                        } else if ($row['status'] == 2) {
                            echo "รออนุมัติการชำระเงิน";
                        } else if ($row['status'] == 3) {
                            echo "ชำระเงินแล้ว";
                        } ?>
                    </td>

                    <td>

                        <?php

                        if ($row['status'] == 2) {
                        ?>
                            <button class="btn btn-sm btn-primary" onclick="Changestatus('<?php echo $row['order_id']; ?>');">อนุมัติ</button>
                        <?php
                        } else {
                        ?>
                            <button class="btn btn-sm btn-danger" onclick="Changestatus('<?php echo $row['order_id']; ?>');">ไม่อนุมัติ</button>

                        <?php
                        }
                        ?>

                    </td>

                    <!--  <td class="text-center">

                        <div class="btn-group">

                            <button class="btn-danger btn btn-xs" onclick="delete_item('<?php echo $row['order_id']; ?>');"><i class="fa fa-close"></i> ลบ</button>

                        </div>

                    </td> -->


                <?php } ?>

        </tbody>

    </table>

</div>

<script>

    function Changestatus(order_id)

    {

        $.ajax({

            type: 'POST',

            url: 'ajax/ChangeStatus.php',

            data: {

                table_name: "tbl_order_head",

                key_name: "order_id",

                key_value: order_id

            },

            dataType: 'json',

            success: function(data) {

                load_table();

            }

        });

    }



    function delete_item(order_id) {

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

                url: 'ajax/index/delete.php',

                type: 'POST',

                dataType: 'json',

                data: {

                    order_id: order_id

                },

                success: function(data) {

                    if (data.result == 1) {

                        swal({

                            title: "ดำเนินการสำเร็จ",

                            text: "ลบข้อมูลสำเร็จ",

                            type: "success",

                            showConfirmButton: true

                        });


                        $("#tr_" + order_id).fadeOut(1000);

                        setTimeout(function() {

                            swal.close();

                        }, 1500);

                    }

                }

            });

        });

    }
</script>