<section class="main-content-wrapper">
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('add_food_menu_category'); ?>
        </h3>
    </section>

    <div class="box-wrapper">
        <div class="table-box">
                <!-- form start -->
                <?php echo form_open(base_url('foodMenuCategory/addEditFoodMenuCategory')); ?>
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('category_name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="category_name" class="form-control"
                                    placeholder="<?php echo lang('category_name'); ?>"
                                    value="<?php echo set_value('category_name'); ?>">
                            </div>
                            <?php if (form_error('category_name')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('category_name'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('description'); ?></label>
                                <input tabindex="2" type="text" name="description" class="form-control"
                                    placeholder="<?php echo lang('description'); ?>"
                                    value="<?php echo set_value('description'); ?>">
                            </div>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('kot_order_type'); ?></label>
                                <select tabindex="3" name="kot_order_type" class="form-control">
                                    <option value="both" <?php echo set_select('kot_order_type', 'both', TRUE); ?>><?php echo lang('kot_order_type_both'); ?></option>
                                    <option value="dine_in" <?php echo set_select('kot_order_type', 'dine_in'); ?>><?php echo lang('kot_order_type_dine_in'); ?></option>
                                    <option value="takeaway" <?php echo set_select('kot_order_type', 'takeaway'); ?>><?php echo lang('kot_order_type_takeaway'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('category_default_order_type'); ?></label>
                                <select tabindex="4" name="default_order_type" class="form-control">
                                    <option value="" <?php echo set_select('default_order_type', '', TRUE); ?>><?php echo lang('None'); ?></option>
                                    <option value="1" <?php echo set_select('default_order_type', '1'); ?>><?php echo lang('dine'); ?></option>
                                    <option value="2" <?php echo set_select('default_order_type', '2'); ?>><?php echo lang('take_away'); ?></option>
                                    <option value="3" <?php echo set_select('default_order_type', '3'); ?>><?php echo lang('delivery'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="row my-2">
                    <div class="col-sm-12 col-md-2 mb-2">
                        <button type="submit" name="submit" value="submit"
                        class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                    </div>
                    <div class="col-sm-12 col-md-2 mb-2">
                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>foodMenuCategory/foodMenuCategories">
                            <?php echo lang('back'); ?>
                        </a>
                    </div>
                </div>
                <?php echo form_close(); ?>
        </div>
    </div>
</section>