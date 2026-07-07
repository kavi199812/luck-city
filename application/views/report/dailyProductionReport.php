<?php
/*
 * Daily Production Report View
 * Displays pre-made food items produced on a given date (single date filter).
 * Two columns: Food Name | Qty (with unit, e.g. "5.00 Unit")
 */
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">

<section class="main-content-wrapper">

    <section class="content-header">
        <h3 class="top-left-header text-left"><?php echo lang('daily_production_report'); ?></h3>
        <input type="hidden" class="datatable_name" data-title="<?php echo lang('daily_production_report'); ?>" data-id_name="datatable">
    </section>

    <div class="my-2">
        <?php if (isLMni() && isset($outlet_id)): ?>
            <h4><?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id)); ?></h4>
        <?php endif; ?>
        <?php if (isset($selected_date) && $selected_date): ?>
            <h4><?php echo lang('report_date'); ?><?php echo escape_output(date($this->session->userdata('date_format'), strtotime($selected_date))); ?></h4>
        <?php endif; ?>
    </div>

    <div class="box-wrapper">
        <!-- Filter Form -->
        <div class="row mb-3">
            <?php echo form_open(base_url() . 'Report/dailyProductionReport'); ?>

            <!-- Single Date Filter -->
            <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
                <div class="form-group">
                    <input tabindex="1" type="text" id="daily_production_date" name="date" readonly
                        class="form-control customDatepicker"
                        placeholder="<?php echo lang('date'); ?>"
                        value="<?php echo isset($selected_date) ? escape_output(date($this->session->userdata('date_format'), strtotime($selected_date))) : ''; ?>">
                </div>
            </div>

            <!-- Outlet Filter (multi-outlet admin only) -->
            <?php if (isLMni()): ?>
                <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
                    <div class="form-group">
                        <select tabindex="2" class="form-control select2 ir_w_100" id="outlet_id" name="outlet_id">
                            <?php
                            $outlets = getAllOutlestByAssign();
                            foreach ($outlets as $outlet_item):
                            ?>
                                <option <?= set_select('outlet_id', $outlet_item->id) ?> value="<?php echo escape_output($outlet_item->id); ?>"><?php echo escape_output($outlet_item->outlet_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Submit -->
            <div class="col-sm-12 col-md-3 col-lg-2 mb-3">
                <div class="form-group">
                    <button type="submit" name="submit" value="submit" class="btn bg-blue-btn w-100">
                        <?php echo lang('submit'); ?>
                    </button>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>

        <!-- Results Table -->
        <div class="table-box">
            <div class="box-body table-responsive">
                <table id="datatable" class="table table-striped">
                    <thead>
                        <tr>
                            <th class="ir_w_1"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('food_name'); ?></th>
                            <th><?php echo lang('qty'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $row_num = 0;
                        if (isset($dailyProductionReport) && !empty($dailyProductionReport)):
                            foreach ($dailyProductionReport as $row):
                                $row_num++;
                                $unit_display = trim(getAmtPCustom($row->total_qty) . ' ' . escape_output($row->unit_name));
                        ?>
                            <tr>
                                <td class="ir_txt_center"><?php echo $row_num; ?></td>
                                <td><?php echo escape_output($row->food_name); ?></td>
                                <td><?php echo $unit_display; ?></td>
                            </tr>
                        <?php
                            endforeach;
                        else:
                        ?>
                            <tr>
                                <td colspan="3" class="text-center">
                                    <?php echo lang('no_data_found'); ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if (isset($dailyProductionReport) && !empty($dailyProductionReport)): ?>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-right"><?php echo lang('grand_total'); ?>:</th>
                            <th><?php echo $row_num; ?> <?php echo lang('item'); ?>(s)</th>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

</section>

<!-- DataTables Scripts -->
<script src="<?php echo base_url(); ?>assets/datatable_custom/jquery-3.3.1.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/newDesign/js/forTable.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/custom_report.js"></script>
<script>
$(document).ready(function() {
    var table = $('#datatable').DataTable();
    
    // Sort by column 1 (Food Name) ascending by default
    table.order([[1, 'asc']]);
    
    // Dynamically assign sequential row numbers (1, 2, 3...) on draw
    table.on('draw.dt', function () {
        table.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    });
    
    // Redraw to apply the sequential numbers and default sort
    table.draw();
});
</script>
