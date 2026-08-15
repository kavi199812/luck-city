<section class="main-content-wrapper">
    <?php
    if ($this->session->flashdata('exception')) {
        echo '<section class="alert-wrapper">
        <div class="alert alert-success alert-dismissible fade show" role="alert">     
            <div class="alert-body">
            <p><i class="m-right fa fa-check"></i>';
        echo escape_output($this->session->flashdata('exception'));unset($_SESSION['exception']);
        echo '</p></div></div></section>';
    }
    ?>
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h2 class="top-left-header"><?php echo lang('ordering_for_pos'); ?> - <?php echo lang('food_menus'); ?></h2>
                <input type="hidden" class="datatable_name" data-title="<?php echo lang('ordering_for_pos'); ?>" data-id_name="datatable">
            </div>
            <div class="col-md-6 text-end">
                <a class="btn bg-blue-btn m-right" href="<?php echo base_url() ?>foodMenuCategory/sortingForPOS">
                    <i class="fa fa-exchange"></i> <?php echo lang('food_menu_categories'); ?> <?php echo lang('ordering_for_pos'); ?>
                </a>
                <a class="btn bg-blue-btn" href="<?php echo base_url() ?>foodMenu/foodMenus">
                    <i class="fa fa-arrow-left"></i> <?php echo lang('back'); ?>
                </a>
            </div>
        </div>
    </section>

    <div class="box-wrapper_sorting">
        <div class="table-box">
            <!-- Category Filter -->
            <div class="row mb-3" style="padding: 10px 15px;">
                <div class="col-md-4">
                    <label class="form-label font-w-600" style="font-weight: 600; margin-bottom: 5px; display: block;">
                        <i class="fa fa-filter"></i> <?php echo lang('category'); ?>:
                    </label>
                    <select id="category_filter" class="form-control select2">
                        <?php foreach ($categories as $cat) { ?>
                            <option value="<?php echo escape_output($cat->id); ?>" <?php echo (isset($selected_category_id) && $selected_category_id == $cat->id) ? 'selected' : ''; ?>>
                                <?php echo escape_output($cat->category_name); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-8 d-flex align-items-end">
                    <p style="color: #64748b; font-size: 13px; margin: 0 0 8px 0;">
                        <i class="fa fa-info-circle"></i> Drag and drop the rows to change the display order in the POS screen.
                    </p>
                </div>
            </div>

            <!-- /.box-header -->
            <div class="table-responsive">
                <form method="post" id="sorting_form">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="ir_w_1" style="width: 60px;"> <?php echo lang('sn'); ?></th>
                                <th style="width: 40px; text-align: center;"><i class="fa fa-arrows-alt"></i></th>
                                <th class="ir_w_10"><?php echo lang('code'); ?></th>
                                <th class="ir_w_28"><?php echo lang('name'); ?></th>
                                <th class="ir_w_15"><?php echo lang('sale_price'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="sortMenu">
                            <?php
                            if (!empty($foodMenus)) {
                                foreach ($foodMenus as $i => $fm) {
                                    $sn = $i + 1;
                            ?>
                                <tr>
                                    <td class="counters ir_txt_center" style="font-weight: 600;"><?php echo escape_output($sn); ?></td>
                                    <td style="text-align: center; cursor: grab; color: #94a3b8;"><i class="fa fa-bars"></i></td>
                                    <td>
                                        <input type="hidden" name="menus[]" value="<?php echo escape_output($fm->id); ?>">
                                        <?php echo escape_output($fm->code); ?>
                                    </td>
                                    <td style="font-weight: 600;"><?php echo escape_output($fm->name); ?></td>
                                    <td><?php echo escape_output(getAmtP($fm->sale_price)); ?></td>
                                </tr>
                            <?php
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 30px; color: #888;">
                                        No food items found in this category.
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </form>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</section>

<script src="<?php echo base_url(); ?>frequent_changing/js/jquery.dragsort.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/food_menu_sorting.js"></script>
