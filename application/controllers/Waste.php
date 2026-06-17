<?php
/*
  ###########################################################
  # PRODUCT NAME: 	iRestora PLUS - Next Gen Restaurant POS | NULLED by raz0r
  ###########################################################
  # AUTHER:		Doorsoft
  ###########################################################
  # EMAIL:		info@doorsoft.co
  ###########################################################
  # COPYRIGHTS:		RESERVED BY Door Soft
  ###########################################################
  # WEBSITE:		http://www.doorsoft.co
  ###########################################################
  # This is Waste Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Waste extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Common_model');
        $this->load->model('Inventory_model');
        $this->load->model('Waste_model');
        $this->Common_model->setDefaultTimezone();
        $this->load->library('form_validation');

        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        if (!$this->session->has_userdata('outlet_id')) {
            $this->session->set_flashdata('exception_2', lang('please_click_green_button'));

            $this->session->set_userdata("clicked_controller", $this->uri->segment(1));
            $this->session->set_userdata("clicked_method", $this->uri->segment(2));
            redirect('Outlet/outlets');
        }
        //start check access function
        $segment_2 = $this->uri->segment(2);
        $segment_3 = $this->uri->segment(3);
        $controller = "137";
        $function = "";

        if($segment_2=="wastes"){
            $function = "view";
        }elseif($segment_2=="wasteDetails" && $segment_3){
            $function = "view_details";
        }elseif($segment_2=="addEditWaste" ||  $segment_2=="food_menus_ingredients" || $segment_2=="autoWastePreMadeFoods"){
            $function = "add";
        }elseif($segment_2=="deleteWaste"){
            $function = "delete";
        }else{
            $this->session->set_flashdata('exception_er', lang('menu_not_permit_access'));
            redirect('Authentication/userProfile');
        }

        if(!checkAccess($controller,$function)){
            $this->session->set_flashdata('exception_er', lang('menu_not_permit_access'));
            redirect('Authentication/userProfile');
        }
        //end check access function
        $login_session['active_menu_tmp'] = '';
        $this->session->set_userdata($login_session);
    }


     /**
     * wastes
     * @access public
     * @return void
     * @param no
     */
    public function wastes() {
        $outlet_id = $this->session->userdata('outlet_id');
        $data = array();
        $data['wastes'] = $this->Common_model->getAllByOutletId($outlet_id, "tbl_wastes");
        $data['main_content'] = $this->load->view('waste/wastes', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * delete Waste
     * @access public
     * @return void
     * @param int
     */
    public function deleteWaste($id) {
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');
        $this->Common_model->deleteStatusChangeWithChild($id, $id, "tbl_wastes", "tbl_waste_ingredients", 'id', 'waste_id');
        $this->session->set_flashdata('exception', lang('delete_success'));
        redirect('Waste/wastes');
    }
     /**
     * add/Edit Waste
     * @access public
     * @return void
     * @param int
     */
    public function addEditWaste($encrypted_id = "") {

        $id = $this->custom->encrypt_decrypt($encrypted_id, 'decrypt');
        $outlet_id = $this->session->userdata('outlet_id');
        $company_id = $this->session->userdata('company_id');
        if (htmlspecialcharscustom($this->input->post('submit'))) {
            $this->form_validation->set_rules('date', lang('date'), 'required|max_length[50]');
            $this->form_validation->set_rules('total_loss', lang('total_loss'), 'required|numeric|max_length[50]');
            $this->form_validation->set_rules('note', lang('note'), 'max_length[200]');
            $this->form_validation->set_rules('employee_id',lang('responsible_person'), 'required|numeric|max_length[50]');
            if ($this->form_validation->run() == TRUE) {

                $waste_info = array();
                $waste_info['reference_no'] = htmlspecialcharscustom($this->input->post($this->security->xss_clean('reference_no')));
                $waste_info['date'] = date('Y-m-d', strtotime($this->input->post($this->security->xss_clean('date'))));
                $waste_info['total_loss'] =htmlspecialcharscustom($this->input->post($this->security->xss_clean('total_loss')));
                $waste_info['note'] =htmlspecialcharscustom($this->input->post($this->security->xss_clean('note')));
                $waste_info['employee_id'] =htmlspecialcharscustom($this->input->post($this->security->xss_clean('employee_id')));
                $waste_info['user_id'] = $this->session->userdata('user_id');
                $waste_info['outlet_id'] = $this->session->userdata('outlet_id');
                $waste_info['food_menu_id'] =htmlspecialcharscustom($this->input->post($this->security->xss_clean('food_menu_id')));
                $waste_info['food_menu_waste_qty'] =htmlspecialcharscustom($this->input->post($this->security->xss_clean('food_menu_waste_qty')));
                if ($id == "") {
                    $waste_id = $this->Common_model->insertInformation($waste_info, "tbl_wastes");
                    $this->saveWasteIngredients($_POST['ingredient_id'], $waste_id, 'tbl_waste_ingredients');
                    $this->session->set_flashdata('exception', lang('insertion_success'));
                } else {
                    $this->Common_model->updateInformation($waste_info, $id, "tbl_wastes");
                    $this->Common_model->deletingMultipleFormData('waste_id', $id, 'tbl_waste_ingredients');
                    $this->saveWasteIngredients($_POST['ingredient_id'], $id, 'tbl_waste_ingredients');
                    $this->session->set_flashdata('exception',lang('update_success'));
                }

                redirect('Waste/wastes');
            } else {
                if ($id == "") {
                    $data = array();
                    $data['pur_ref_no'] = $this->Waste_model->generateWasteRefNo($outlet_id);
                    $data['ingredients'] = $this->Waste_model->getIngredientList($outlet_id);
                    $data['food_menus'] = $this->Waste_model->getFoodMenuList($outlet_id);
                    $data['employees'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_users");
                    $data['main_content'] = $this->load->view('waste/addWaste', $data, TRUE);
                    $this->load->view('userHome', $data);
                } else {
                    $data = array();
                    $data['encrypted_id'] = $encrypted_id;
                    $data['ingredients'] = $this->Waste_model->getIngredientList($outlet_id);
                    $data['food_menus'] = $this->Waste_model->getFoodMenuList($outlet_id);
                    $data['employees'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_users");
                    $data['waste_details'] = $this->Common_model->getDataById($id, "tbl_wastes");
                    $data['waste_ingredients'] = $this->Waste_model->getWasteIngredients($id);
                    $data['main_content'] = $this->load->view('waste/editWaste', $data, TRUE);
                    $this->load->view('userHome', $data);
                }
            }
        } else {
            if ($id == "") {
                $data = array();
                $data['pur_ref_no'] = $this->Waste_model->generateWasteRefNo($outlet_id);
                $data['ingredients'] = $this->Waste_model->getIngredientList($outlet_id);
                $data['food_menus'] = $this->Waste_model->getFoodMenuList($outlet_id);
                $data['employees'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_users");
                $data['main_content'] = $this->load->view('waste/addWaste', $data, TRUE);
                $this->load->view('userHome', $data);
            } else {
                $data = array();
                $data['encrypted_id'] = $encrypted_id;
                $data['ingredients'] = $this->Waste_model->getIngredientList($outlet_id);
                $data['food_menus'] = $this->Waste_model->getFoodMenuList($outlet_id);
                $data['employees'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_users");
                $data['waste_details'] = $this->Common_model->getDataById($id, "tbl_wastes");
                $data['waste_ingredients'] = $this->Waste_model->getWasteIngredients($id);
                $data['main_content'] = $this->load->view('waste/editWaste', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        }
    }
     /**
     * save Waste Ingredients
     * @access public
     * @return void
     * @param string
     * @param int
     * @param string
     */
    public function saveWasteIngredients($waste_ingredients, $waste_id, $table_name) {
        foreach ($waste_ingredients as $row => $ingredient_id):
            $fmi = array();
            $fmi['ingredient_id'] = $ingredient_id;
            $fmi['waste_amount'] = $_POST['waste_amount'][$row];
            $fmi['last_purchase_price'] = $_POST['last_purchase_price'][$row];
            $fmi['loss_amount'] = $_POST['loss_amount'][$row];
            $fmi['waste_id'] = $waste_id;
            $fmi['outlet_id'] = $this->session->userdata('outlet_id');
            $this->Common_model->insertInformation($fmi, "tbl_waste_ingredients");
        endforeach;
    }
     /**
     * Waste Details
     * @access public
     * @return void
     * @param int
     */
    public function wasteDetails($id) {
        $encrypted_id = $id;
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');

        $data = array();
        $data['encrypted_id'] = $encrypted_id;
        $data['waste_details'] = $this->Common_model->getDataById($id, "tbl_wastes");
        $data['waste_ingredients'] = $this->Waste_model->getWasteIngredients($id);
        $data['main_content'] = $this->load->view('waste/wasteDetails', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * food menus ingredients
     * @access public
     * @return object
     * @param no
     */
    public function food_menus_ingredients() {
        $outlet_id = $this->session->userdata('outlet_id');
        $company_id = $this->session->userdata('company_id');
        $id = $_GET['id'];
        if ($id) {
            $sql = "select * from tbl_food_menus_ingredients left join tbl_ingredients on tbl_food_menus_ingredients.ingredient_id=tbl_ingredients.id left join tbl_units on tbl_ingredients.unit_id=tbl_units.id where tbl_food_menus_ingredients.company_id=" . $company_id . " and tbl_food_menus_ingredients.food_menu_id=" . $id;
            $results = $this->Common_model->customeQuery($sql);
            foreach ($results as $key => $result) {
                $g_unit_price = $this->Common_model->get_row_array("tbl_purchase_ingredients", array('outlet_id' => $outlet_id, 'ingredient_id' => $result['ingredient_id']), '*', '', '1', 'id', 'DESC');
                if (!empty($g_unit_price)) {
                    $results[$key]['unit_price'] = $g_unit_price[0]['unit_price'];
                } else {
                    $results[$key]['unit_price'] = 0;
                }
            }
        } else {
            $sql = "select * from tbl_food_menus_ingredients left join tbl_ingredients on tbl_food_menus_ingredients.ingredient_id=tbl_ingredients.id left join tbl_units on tbl_ingredients.unit_id=tbl_units.id where tbl_food_menus_ingredients.company_id=" . $company_id . " and tbl_food_menus_ingredients.food_menu_id=" . $id;
            $results = $this->Common_model->customeQuery($sql);
            foreach ($results as $key => $result) {
                $g_unit_price = $this->Common_model->get_row_array("tbl_purchase_ingredients", array('outlet_id' => $outlet_id, 'ingredient_id' => $result['ingredient_id']), '*', '', '', 'id', 'DESC');
                if (!empty($g_unit_price)) {
                    $results[$key]['unit_price'] = $g_unit_price[0]['unit_price'];
                } else {
                    $results[$key]['unit_price'] = 0;
                }
            }
        }

        echo json_encode($results);
    }

    public function autoWastePreMadeFoods() {
        if (!$this->session->has_userdata('user_id')) {
            echo json_encode(array('status' => 'error', 'message' => 'User not logged in.'));
            return;
        }

        $outlet_id = $this->session->userdata('outlet_id');
        $company_id = $this->session->userdata('company_id');
        $user_id = $this->session->userdata('user_id');

        $sql = "SELECT ingr_tbl.*, i.id as id, i.conversion_rate,
                (select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_purchase, 
                (select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_consumption,
                (select SUM(consumption) from tbl_sale_consumptions_of_modifiers_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_modifiers_consumption,
                (select SUM(waste_amount) from tbl_waste_ingredients where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_waste,
                (select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients where ingredient_id=i.id AND consumption_status='Plus' AND outlet_id=$outlet_id AND del_status='Live') total_consumption_plus,
                (select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients where ingredient_id=i.id AND consumption_status='Minus' AND outlet_id=$outlet_id AND del_status='Live') total_consumption_minus,
                (select SUM(quantity_amount) from tbl_production_ingredients where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live' AND status=1) total_production,
                (select SUM(quantity_amount) from tbl_transfer_ingredients where ingredient_id=i.id AND to_outlet_id=$outlet_id AND del_status='Live' AND status=1 AND transfer_type=1) total_transfer_plus,
                (select SUM(quantity_amount) from tbl_transfer_ingredients where ingredient_id=i.id AND from_outlet_id=$outlet_id AND del_status='Live' AND status=1 AND transfer_type=1) total_transfer_minus,
                (select SUM(quantity_amount) from tbl_transfer_received_ingredients where ingredient_id=i.id AND to_outlet_id=$outlet_id AND del_status='Live' AND status=1) total_transfer_plus_2,
                (select SUM(quantity_amount) from tbl_transfer_received_ingredients where ingredient_id=i.id AND from_outlet_id=$outlet_id AND del_status='Live' AND status=1) total_transfer_minus_2
                FROM tbl_ingredients i
                LEFT JOIN tbl_food_menus_ingredients ingr_tbl ON i.id = ingr_tbl.ingredient_id
                WHERE i.company_id = $company_id AND i.del_status = 'Live' AND i.ing_type = 'Pre-made Item'
                GROUP BY i.id";

        $ingredients = $this->db->query($sql)->result();
        $wasted_items = array();
        $total_loss = 0;

        foreach ($ingredients as $ingredient) {
            $conversion_rate = (int)$ingredient->conversion_rate ? (int)$ingredient->conversion_rate : 1;

            $total_purchase = (float)$ingredient->total_purchase;
            $total_consumption = (float)$ingredient->total_consumption;
            $total_modifiers_consumption = (float)$ingredient->total_modifiers_consumption;
            $total_waste = (float)$ingredient->total_waste;
            $total_consumption_plus = (float)$ingredient->total_consumption_plus;
            $total_consumption_minus = (float)$ingredient->total_consumption_minus;
            $total_production = (float)$ingredient->total_production;
            $total_transfer_plus = (float)$ingredient->total_transfer_plus;
            $total_transfer_minus = (float)$ingredient->total_transfer_minus;
            $total_transfer_plus_2 = (float)$ingredient->total_transfer_plus_2;
            $total_transfer_minus_2 = (float)$ingredient->total_transfer_minus_2;

            $totalStock = ($total_purchase * $conversion_rate) - $total_consumption - $total_modifiers_consumption - $total_waste + $total_consumption_plus - $total_consumption_minus + ($total_transfer_plus * $conversion_rate) - ($total_transfer_minus * $conversion_rate) + ($total_transfer_plus_2 * $conversion_rate) - ($total_transfer_minus_2 * $conversion_rate) + ($total_production * $conversion_rate);

            if ($conversion_rate == 0 || $conversion_rate == '') {
                $total_sale_unit = (int)($totalStock / 1);
            } else {
                $total_sale_unit = (int)($totalStock / $conversion_rate);
            }
            $stock_qty = (float)($total_sale_unit . "." . ($totalStock % $conversion_rate));

            if ($stock_qty > 0) {
                $g_unit_price = $this->Common_model->get_row_array("tbl_purchase_ingredients", array('outlet_id' => $outlet_id, 'ingredient_id' => $ingredient->id), '*', '', '1', 'id', 'DESC');
                if (!empty($g_unit_price)) {
                    $last_purchase_price = $g_unit_price[0]['unit_price'];
                } else {
                    $last_purchase_price = 0;
                }
                $loss_amount = $stock_qty * $last_purchase_price;
                $total_loss += $loss_amount;

                $wasted_items[] = array(
                    'ingredient_id' => $ingredient->id,
                    'waste_amount' => $stock_qty,
                    'last_purchase_price' => $last_purchase_price,
                    'loss_amount' => $loss_amount
                );
            }
        }

        if (empty($wasted_items)) {
            echo json_encode(array('status' => 'info', 'message' => 'No Pre-Made Food in stock to waste.'));
            return;
        }

        $waste_info = array(
            'reference_no' => $this->Waste_model->generateWasteRefNo($outlet_id),
            'date' => date('Y-m-d'),
            'total_loss' => $total_loss,
            'note' => 'Auto-wasted all Pre-Made Food',
            'employee_id' => $user_id,
            'user_id' => $user_id,
            'outlet_id' => $outlet_id,
            'del_status' => 'Live'
        );

        $waste_id = $this->Common_model->insertInformation($waste_info, 'tbl_wastes');

        foreach ($wasted_items as $item) {
            $fmi = array(
                'ingredient_id' => $item['ingredient_id'],
                'waste_amount' => $item['waste_amount'],
                'last_purchase_price' => $item['last_purchase_price'],
                'loss_amount' => $item['loss_amount'],
                'waste_id' => $waste_id,
                'outlet_id' => $outlet_id,
                'del_status' => 'Live'
            );
            $this->Common_model->insertInformation($fmi, 'tbl_waste_ingredients');
        }

        echo json_encode(array('status' => 'success', 'message' => 'Pre-Made Food stock has been successfully wasted. Total loss amount: ' . getAmtP($total_loss)));
    }

}
