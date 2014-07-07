<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Service_v1 extends CI_Controller {

    private $global_url;

    public function __construct() {
        parent::__construct();
        $this->global_url = "http://" . $_SERVER['HTTP_HOST'] . "/shop2hand/uploads/";
        $this->load->library('Librarys');
        // Your own constructor code
    }

    public function upload($key = "") {


        $file = $_FILES;
//        print_r($file);
//        exit();
        $image = array();
        $image['error'] = '';
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '2000';
        $config['max_width'] = '2000';
        $config['max_height'] = '2000';
        $config['overwrite'] = FALSE;
        $filename = time() . $file[$key]['size'];
        $config['file_name'] = $filename;

        $this->load->library('upload', $config);

        $isWriteable = is_really_writable('./uploads/');
//        if ($isWriteable) {
//            echo 'yes it is really writeable';
//        } else {
//            echo 'no, it is not really writeable';
//        }

        if (!$this->upload->do_upload('$key')) {
            $error = array('error' => $this->upload->display_errors());
//            print_r($error['']);
            $image['error'] = $error['error'];
//            exit();
        } else {
            $data = array('upload_data' => $this->upload->data());
//            print_r($data);
//            echo "New File Name & Path " . $data['upload_data']['file_path'] . $filename;
            $fullImage = $data['upload_data']['file_name'];
            $thumbnail = $data['upload_data']['raw_name'] . $data['upload_data']['file_ext'];

//            $image['fullImage'] = base_url() . "uploads/" . $fullImage;
//            $image['thumbnail'] = base_url() . "uploads/" . $thumbnail;

            unset($config);
            // Create Source Image Thumb //
            $config['image_library'] = 'gd2';
            $config['source_image'] = $data['upload_data']['full_path'];
            $config['new_image'] = $thumbnail;
            $config['create_thumb'] = TRUE;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 50;
            $config['height'] = 50;

            $thumbnail = $data['upload_data']['raw_name'] . "_thumb" . $data['upload_data']['file_ext'];
            $image['fullImage'] = base_url() . "uploads/" . $fullImage;
            $image['thumbnail'] = base_url() . "uploads/" . $thumbnail;

            $this->load->library('image_lib', $config);
            if (!$this->image_lib->resize()) {
                print_r($this->image_lib->display_errors());
            }
        }


        return $image;
    }

    public function index() {

        $post = $this->input->post(NULL, TRUE);

        if (!empty($post)) {

            switch ($post['service_id']) {

                case "tester":
                    ///Param///
                    $image = $this->input->post('image', TRUE);


                    $imgName = time() . ".jpg";
                    $decoded = base64_decode($image);
                    file_put_contents('./uploads/' . $imgName, $decoded);

                    $response = array(
                        'response_code' => '0',
                        'response_text' => 55555,
                        'image' => $image
                    );

                    $this->output
                            ->set_content_type('application/json')
                            ->set_output(json_encode($response));

                    break;

                case "S101":

                    ///Param///
                    $email = $this->input->post('email', TRUE);
                    $password = $this->input->post('password', TRUE);
                    $name = $this->input->post('name', TRUE);
                    $login_type = $this->input->post('login_type', TRUE);
                    $facebook_id = $this->input->post('facebook_id', TRUE);
                    $facebook_img = $this->input->post('fb_picture', TRUE);

                    $user_login_tb = array(
                        'email' => $email,
                        'name' => $name,
                        'password' => $password,
                        'login_type' => $login_type,
                        'facebook_id' => $facebook_id,
                        'fb_picture' => $facebook_img
                    );

                    $this->S101_login($user_login_tb);
                    break;

                case "S102":

                    ///Param///
                    $name = $this->input->post('name', TRUE);
                    $email = $this->input->post('email', TRUE);
                    $password = $this->input->post('password', TRUE);
                    $profile_picture = $this->input->post('picture_profile', TRUE);
                    $phone = $this->input->post('phone',TRUE);

                    $user_tb = array(
                        'email' => $email,
                        'password' => $password,
                        'profile_picture' => $profile_picture,
                        'name' => $name,
                        'phone' => $phone
                    );
//
                    $this->S102_register($user_tb);

                    break;



                case "S104":

                    $user_id = $this->input->post('user_id', TRUE);
                    $password = $this->input->post('password', TRUE);
                    $profile_picture = $this->input->post('profile_picture', TRUE);
                    $name = $this->input->post('name', TRUE);

                    $user_tb = array(
                        'user_id' => $user_id,
                        'display_name' => $display_name,
                        'password' => $password,
                        'name' => $name
                    );

                    $this->S104_edit_user($user_tb);
                    break;

                case "S105":



                    $user_id = $this->input->post('user_id', TRUE);
                    $this->S105_get_user_profile($user_id);

                    break;

                case "S106":

                    $bike_id = $this->input->post('bike_id', TRUE);
                    $brand_id = $this->input->post('brand_id', TRUE);
                    $model_name = $this->input->post('model_name', TRUE);
                    $engine_size = $this->input->post('engine_size', TRUE);
                    $style_id = $this->input->post('style_id', TRUE);

                    $moto_bike_tb = array(
                        'id' => $bike_id,
                        'moto_brand_id' => $brand_id,
                        'moto_ride_style_id1' => $style_id,
                        'model_name' => $model_name,
                        'engine_size' => $engine_size
                    );

                    $this->S105_edit_bike($moto_bike_tb);

                    break;    

                case "s06":

                    $this->s06_get_range();
                    break;

                case "s07":

                    $this->s07_get_trip_list();
                    break;

                case "S108":

                    $this->S108_get_bike_brand_list();
                    break;

                case "s09":

                    $this->s09_search_trip();
                    break;

                case "s10":

                    $this->s10_join_trip();
                    break;

                case "s11":

                    $this->s11_update_location();
                    break;

                case "s12":

                    $this->s12_update_status();
                    break;

                case "s13":

                    $this->s13_upload();
                    break;

                case "s14":

                    $this->s14_get_trip_timeline();
                    break;

                case "s15":

                    $this->s15_get_trip_detail();
                    break;

                case "s16":

                    $this->s16_leave_trip();
                    break;

                case "s17":

                    $this->s17_edit_profile();
                    break;

                case "s18":

                    $name = $this->input->post('name');
                    $username = $this->input->post('username');
                    $password = $this->input->post('password');
                    $device = $this->input->post('device');
                    $token = $this->input->post('token');
                    $app_version = $this->input->post('appversion');
                    $os_version = $this->input->post('osversion');
                    $date_register = date("Y-m-d H:i:s");
                    $gender = $this->input->post('gender');
                    $lat = $this->input->post('lat');
                    $lng = $this->input->post('lng');
                    $status = $this->input->post('status');

                    print_r($_FILES);
                    $param = array(
                        'name' => $name,
                        'username' => $username,
                        'password' => $password,
                        'device' => $device,
                        'token' => $token,
                        'app_version' => $app_version,
                        'os_version' => $os_version,
                        'date_register' => $date_register,
                        'gender' => $gender,
                        'lat' => $lat,
                        'lng' => $lng,
                        'status' => $status
                    );

                    $this->s18_register($param);
                    break;

                case "s19":

                    $username = $this->input->post('username');
                    $password = $this->input->post('password');

                    $param = array(
                        'username' => $username,
                        'password' => $password
                    );
                    $this->s19_login($param);

                    break;

                case "S201":

                    $cat_id = $this->input->post('cat_id', TRUE);
                    $request_id = $this->input->post('request_id', TRUE);
                    $name = $this->input->post('name', TRUE);
                    $min_price = $this->input->post('min_price', TRUE);
                    $max_price = $this->input->post('max_price', TRUE);
                    $page = $this->input->post('page', TRUE);

                    $param = array(
                        'cat_id' => $cat_id,
                        'request_id' => $request_id,
                        'name' => $name,
                        'min_price' => $min_price,
                        'max_price' => $max_price,
                        'page' => $page
                    );

                    $this->S201_search_product($param);

                    break;

                case "S202":
//                    $jsonTrack = $this->input->post('json');
                    $product_id = $this->input->post('product_id', TRUE);
                    $this->S202_product_detail($product_id);
                    break;

                case "S203":
                    
                    $product_id = $this->input->post('product_id', TRUE);
                    $user_id = $this->input->post('user_id', TRUE);
                    $request_id = $this->input->post('request_id', TRUE);
                    $cat_id = $this->input->post('cat_id', TRUE);
                    $comment = $this->input->post('comment', TRUE);
                    
                    $param = array(
                        'product_id' => $product_id,
                        'comment_user_id' => $user_id,
                        'product_request_id' => $request_id,
                        'product_categorie_id' => $cat_id,
                        'comment' => $comment,
                        'date_created' => time()
                    );

                    $this->S203_add_comment($param);
                    break;

                case "S205":
//                    $jsonTrack = $this->input->post('json');
                    $this->S205_list_category();
                    break;

                case "S207":
//                    $jsonTrack = $this->input->post('json');
                    $this->S207_list_product();
                    break;

                case "S208":

                    $name = $this->input->post('p_name');
                    $price = $this->input->post('p_price');
                    $detail = $this->input->post('p_detail');
                    $date_create = time();
                    $user_id = $this->input->post('user_id');
                    $p_request_id = $this->input->post('product_request_id');
                    $cat_id = $this->input->post('product_categorie_id');
                    $condition = $this->input->post('p_condition');

                    $product_tb_data = array(
                        'p_name' => $name,
                        'p_price' => $price,
                        'p_detail' => $detail,
                        'p_date_created' => $date_create,
                        'p_condition' => $condition,
                        'user_id' => $user_id,
                        'product_request_id' => $p_request_id,
                        'product_categorie_id' => $cat_id
                    );
                    $this->S208_add_product($product_tb_data);
                    break;

                case "S210":

                    $product_id = $this->input->post('product_id');
                    $product_user_id = $this->input->post('product_user_id');
                    $product_request_id = $this->input->post('product_request_id');
                    $product_categorie_id = $this->input->post('product_categorie_id');


                    $product_image_tb_data = array(
                        'product_id' => $product_id,
                        'product_user_id' => $product_user_id,
                        'product_request_id' => $product_request_id,
                        'product_categorie_id' => $product_categorie_id
                    );
                    $this->S210_add_product_image($product_image_tb_data);
                    break;

                case "S211":

                    $product_id = $this->input->post('product_id');
                    $user_id = $this->input->post('user_id');


                    $product_tb_data = array(
                        'product_id' => $product_id,
                        'user_id' => $user_id,
                    );

                    $this->S211_like_product($product_tb_data);
                    break;

                case "S212":

                    $product_id = $this->input->post('product_id');
                    $user_id = $this->input->post('user_id');

                    $user_wishlist_tb_data = array(
                        'product_id' => $product_id,
                        'user_id' => $user_id,
                    );

                    $this->S212_user_add_wishlist($user_wishlist_tb_data);
                    break;

                case "S213":

//                    $product_id = $this->input->post('product_id');
                    $user_id = $this->input->post('user_id');
                    $this->S213_user_list_wishlist($user_id);

                    break;

                case "S444":
                    $this->S444();
                    break;
                default:
                    break;
            }
        }
    }

    public function S444() {

        echo "http://$_SERVER[HTTP_HOST]/shop2hand/uploads";
    }

    public function S102_register($user_tb) {

        //       $user_tb = array(
//                        'email' => $email,
//                        'password' => $password,
//                        'profile_picture' => $profile_picture,
//                        'name' => $name
//                    );

        $image = $user_tb['profile_picture'];
        $email = $user_tb['email'];

//        unset($user_tb['profile_picture']);


        $query = $this->db->get_where('user', array('email' => $email));
        if ($query->num_rows() > 0) {
            // Email Exist
            $response = array(
                'response_code' => '0',
                'response_text' => 'email is existed'
            );
            
        } else {

            //// Add to user
            $this->db->insert('user', $user_tb);
            $user_id = $this->db->insert_id();

            $imgName = "user_image_" . $user_id . ".jpg";

//            $img = str_replace('data:image/png;base64,', '', $image);
//            $img = str_replace(' ', '+', $img);
            
            if($image == ""){
                $url = "";
                $image_source = "";
            }else{
                $data = base64_decode($image);
                file_put_contents('./uploads/' . $imgName, $data);
                $url = base_url() . "uploads/" . $imgName;
                $image_source = $url;
            }

            //echo 'userID = '.$user_id." image = ".$url." decode_image = ".$data." Base_url = ".  base_url();
            //// update image to user
            $user_tb['profile_picture'] = $image_source;
            $this->db->where('id', $user_id);
            $this->db->update('user', $user_tb);

            $response = array(
                'response_code' => '1',
                'response_text' => '',
                'name' => $user_tb['name'],
                'user_id' => $user_id,
                'email' => $user_tb['email'],
                'password' => $user_tb['password'],
                'profile_picture' => $url
            );
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }

    public function S101_login($user_login_tb) {

        /*
          $user_login_tb = array(
            'email' => $email,
            'name' => $name,
            'password' => $password,
            'login_type' => $login_type,
            'facebook_id' => $facebook_id,
            'fb_picture' => $facebook_img
        );
         */

        $login_type = $user_login_tb['login_type'];
        $facebook_id = $user_login_tb['facebook_id'];
        $fb_image = $user_login_tb['fb_picture'];
//        $images = $this->upload('profile_picture');
//        $user_login_tb['profile_picture'] = $images['fullImage'];
//        $user_login_tb['profile_picture_thumbnail'] = $images['thumbnail'];

        if ($login_type == "facebook") {

            $query = $this->db->get_where("user", array("facebook_id" => $facebook_id));
            $data = $query->row_array();

            if (count($data) > 0) {
                $user_id = $data['id'];
            }
            unset($user_login_tb['login_type']);

            if ($query->num_rows() > 0) {
                //Facebook login passed
                $response = array(
                    'response_code' => '1',
                    'response_text' => '',
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'profile_picture' => $fb_image,
                    'fb_picture' => $fb_image,
                    'password' => $data['password']
                );

                $this->db->where('facebook_id', $facebook_id);
                $this->db->update('user', $user_login_tb);

            } else {

                //Facebook register #insert and Add Image
//                $imgName = $facebook_id . ".jpg";
//                $decoded = base64_decode($image);
//                file_put_contents('./uploads/' . $imgName, $decoded);
//                $url = base_url() . "uploads/" . $imgName;
//                $user_login_tb['profile_picture'] = $url;
                $user_login_tb['profile_picture'] = $fb_image;
                $user_login_tb['fb_picture'] = $fb_image;

                $this->db->insert('user', $user_login_tb);
                $lastUserID = $this->db->insert_id();

                $query = $this->db->get_where("user", array("id" => $lastUserID));
                $data = $query->row_array();

                $response = array(
                    'response_code' => '1',
                    'response_text' => '',
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'fb_picture' => $fb_image,
                    'profile_picture' => $fb_image,
                    'status' => "insert"
                );
            }
        } else {

            //Manual if user is exist 
            $query = $this->db->
                    get_where("user", array(
                "email" => $user_login_tb['email'],
                "password" => $user_login_tb['password']
                    )
            );
            $data = $query->row_array();

            if ($query->num_rows() > 0) {

                //Manual login success return Bike List
                $response = array(
                    'response_code' => '1',
                    'response_text' => '',
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'profile_picture' => $data['profile_picture']
                );
            } else {

                //Manual login failed
                $response = array(
                    'response_code' => '0',
                    'response_text' => 'Invalid email or password'
                );
            }
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }

    public function S104_edit_user($moto_user_tb) {

        //// Delete Image Before Upload////
        $query = $this->db->get_where("moto_user", array(
            "id" => $moto_user_tb['id']
        ));

        $data = $query->result_array();
        $profile_picture = explode("/", $data[0]['profile_picture']);
        $thumb_picture = explode("/", $data[0]['profile_picture_thumbnail']);
        $lastIndex = (count($profile_picture) - 1);
//        echo $thumb_picture[$lastIndex];
        if (file_exists("./uploads/" . $profile_picture[$lastIndex])) {
            unlink("./uploads/" . $profile_picture[$lastIndex]);
            unlink("./uploads/" . $thumb_picture[$lastIndex]);
        }

//        if($data[0]['profile_picture'] != ""){
//            delete_files("./upload/1392024266123431.png");
//            delete_files($data[0]['profile_picture_thumbnail']);
//        }
        ///////////////////////////////////

        $images = $this->upload('profile_picture');
        $moto_user_tb['profile_picture'] = $images['fullImage'];
        $moto_user_tb['profile_picture_thumbnail'] = $images['thumbnail'];

//        print_r($moto_user_tb);
        //// Update Statement
        $this->db->where('id', $moto_user_tb['id']);
        $this->db->update('moto_user', $moto_user_tb);

        $response = array(
            'response_code' => '1',
            'response_text' => '',
            'user_detail' => $moto_user_tb
        );
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }

    public function S105_get_user_profile($user_id){
        


        $list_product = $this->db->get_where('product',array(
            'user_id' => $user_id
        ));
        $all_products = $list_product->result_array();

        $product = array();
        $i = 0;
        foreach ($all_products as $all_product) {

            foreach ($all_product as $key => $value) {
                $product[$i][$key] = $value;
                if ($key == "id") {
                    $this->db->join("product_image", "product.id = product_image.product_id");
                    $this->db->where('product.id', $value);
                    $list_product_image = $this->db->get('product');

                    $first_image = $list_product_image->row_array();
//                    $arr[$i]['images'] = $imgArr;
                    $product[$i]['images'] = $first_image['image_path'];
                }
            }

            $i++;
        }

        
 

        $user_profile = $this->db->get_where('user',array(
            'id' => $user_id
        ));

  

        $arr = array(
            "user_profile" => $user_profile->row_array(),
            "products" => $product,
        );



        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arr));

    }

    public function S105_edit_bike() {

        $images = $this->upload('bike_photo');
        $error = $images['error'];

        if ($error == "") {

            $moto_bike_tb['bike_photo'] = $images['fullImage'];
            $moto_bike_tb['bike_photo_thumbnail'] = $images['thumbnail'];
            $this->db->where('id', $moto_bike_tb['id']);
            $this->db->update('moto_bike', $moto_bike_tb);
            //// Add to moto_user_device
            $lastBikeID = $moto_bike_tb['id'];

            //// get Bike Style Text & ride Style Text
            $this->db->select('moto_brand.brand_name , moto_ride_style.name')
                    ->from("moto_bike")
                    ->join("moto_ride_style", "moto_bike.moto_ride_style_id1 = moto_ride_style.id")
                    ->join("moto_brand", "moto_bike.moto_brand_id = moto_brand.id")
                    ->where(
                            "moto_bike.id = $lastBikeID"
            );
            $query = $this->db->get();
            $bikeData = $query->result_array();

//            echo $bikeData[0]['brand_name'];

            $response = array(
                'response_code' => '1',
                'response_text' => '',
                'bike_id' => $lastBikeID,
                'model_name' => $moto_bike_tb['model_name'],
                'engine_size' => $moto_bike_tb['engine_size'],
                'bike_photo' => $moto_bike_tb['bike_photo'],
                'bike_photo_thumbnail' => $moto_bike_tb['bike_photo_thumbnail'],
                'response_text' => '',
                'moto_brand' => $bikeData[0]['brand_name'],
                'moto_ride_style' => $bikeData[0]['name']
            );
        } else {

            $response = array(
                'response_code' => '0',
                'response_text' => $error
            );
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }

    public function s06_get_range() {
        
    }

    public function s07_get_trip_list() {
        
    }

    public function S108_get_bike_brand_list() {

        $query_moto_ride_style = $this->db->get('moto_ride_style');
        $query_moto_brand = $this->db->get('moto_brand');

        $arr = array();
        $i = 0;

        foreach ($query_moto_brand->result() as $row) {
            $arr['brands'][$i]['brand_id'] = $row->id;
            $arr['brands'][$i]['brand_name'] = $row->brand_name;
            $i++;
        }

        $j = 0;
        foreach ($query_moto_ride_style->result() as $row) {
            $arr['styles'][$j]['style_id'] = $row->id;
            $arr['styles'][$j]['style_name'] = $row->name;
            $j++;
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arr));
//        echo json_encode($arr);
    }

    public function s09_search_trip() {
        
    }

    public function s10_join_trip() {
        
    }

    public function s11_update_location() {
        
    }

    public function s12_update_status() {
        
    }

    public function s13_upload() {
        
    }

    public function s14_get_trip_timeline() {
        
    }

    public function s15_get_trip_detail() {
        
    }

    public function s16_leave_trip() {
        
    }

    public function s17_edit_profile() {
        
    }

    public function s18_register($param) {
        $this->users->register($param);
    }

    public function s19_login($param) {
        /// load users model ///
        $result = $this->moto_user->login($param);
        echo $result;
    }

    public function s20_edit_bike() {
        
    }

    public function S201_search_product($param) {

        /*
          $param = array(
          'cat_id' => $username,
          'request_id' => $request_id,
          'name' => $name,
          'min_price' => $min_price,
          'max_price' => $max_price,
          'page' => $page
          );

         */
        if ($param['page'] != "") {
            $page = $param['page'];
        } else {
            $page = 0;
        }

        if ($param['cat_id'] != "") {
            $this->db->where('product_categorie_id', $param['cat_id']);
        }

        if ($param['request_id'] != "") {
            $this->db->where('product_request_id', $param['request_id']);
        }

        if ($param['name'] != "") {
            $this->db->where('p_name', $param['name']);
        }

        if ($param['min_price'] != "") {
            $this->db->where('p_price > ', $param['min_price']);
        }

        if ($param['max_price'] != "") {
            $this->db->where('p_price < ', $param['max_price']);
        }

        $this->db->limit(10, $page);
        $list_product = $this->db->get('product');

//            $this->output->enable_profiler(TRUE);

        $all_products = $list_product->result_array();

        $arr = array();

        $i = 0;
        foreach ($all_products as $all_product) {

            foreach ($all_product as $key => $value) {
                $arr['products'][$i][$key] = $value;

                if ($key == "id") {
                    $this->db->join("product_image", "product.id = product_image.product_id");
                    $this->db->where('product.id', $value);
                    $list_product_image = $this->db->get('product');
                    $arr['products'][$i]['images'] = $list_product_image->result_array();
                }
            }

            $i++;
        }

        $arr['start_row'] = count($all_products);

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arr));
    }

    public function S202_product_detail($product_id) {

        $arr = array();
        $this->db->join("user","user.id = product.user_id");
        $this->db->where('product.id', $product_id);
        $product = $this->db->get('product');

//            $this->output->enable_profiler(TRUE);

        $product_detail = $product->result_array();
        $this->db->select('product_image.image_path');
        $this->db->join("product_image", "product.id = product_image.product_id");
        $this->db->where('product.id', $product_id);
        $list_product_image = $this->db->get('product');
        $product_detail[0]['images'] = $list_product_image->result_array();
        
        $this->db->select(
                'product_comment.comment_user_id,
                product_comment.comment,
                product_comment.date_created,
                user.name,
                user.profile_picture,
                user.fb_picture'
                );
        $this->db->join("product", "product.id = product_comment.product_id");
        $this->db->join("user", "user.id = product_comment.comment_user_id");
        $this->db->where('product.id',$product_id);
        $list_comment = $this->db->get('product_comment');
        $all_comment = $list_comment->result_array();
        
        $arr['product_detail'] = $product_detail;
        $arr['product_comment'] = $all_comment;

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arr));
    }

    public function S203_add_comment($param) {

        $this->db->insert('product_comment',$param);
        
        $response = array(
            'response_code' => '1',
            'response_text' => ''
        );

        
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }

    public function S205_list_category() {

        $cat_db = $this->db->get('product_categorie');
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($cat_db->result_array()));
    }

    public function S207_list_product() {

//        $this->db->join("product_image", "product.id = product_image.product_id");
        //condition// where
        /////////////

        $list_product = $this->db->get('product');
        $all_products = $list_product->result_array();

        $arr = array();
        $i = 0;
        foreach ($all_products as $all_product) {

            foreach ($all_product as $key => $value) {
                $arr[$i][$key] = $value;
                if ($key == "id") {
                    $this->db->join("product_image", "product.id = product_image.product_id");
                    $this->db->where('product.id', $value);
                    $list_product_image = $this->db->get('product');

//                    $imgArr = array();
//                    foreach ($list_product_image->result_array() as $key => $value) {
//                        
//                        $imgArr[$key] = $value;
//                        if ($imgArr[$key]['image_path']) {
//                            $imgArr[$key] = $value['image_path'];
//                        }
//                        
//                    }
                    $first_image = $list_product_image->row_array();
//                    $arr[$i]['images'] = $imgArr;
                    $arr[$i]['images'] = $first_image['image_path'];
                }
            }

            $i++;
        }


        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arr));
    }

    public function S208_add_product($product_tb_data) {

        /*
          $product_tb_data = array(
          'p_name' => $name,
          'p_price' => $price,
          'p_detail' => $detail,
          'p_date_created' => $username,
          'p_condition' => $condition,
          'user_id' => $user_id,
          'product_request_id' => $p_request_id,
          'product_categorie_id' => $cat_id

          );
         */

        $user_id = $product_tb_data['user_id'];
        $request_id = $product_tb_data['product_request_id'];
        $cat_id = $product_tb_data['product_categorie_id'];

        /// insert to product table ///
        $this->db->insert("product", $product_tb_data);
        $lastProductID = $this->db->insert_id();
        $product_tb_data['product_id'] = $lastProductID;
//        /// Add Product Image ///
//        $p_image = $this->input->post('p_image');
//        
//        // initial image name //
//        $imgName = "p_".$user_id.".jpg";
//        $decoded = base64_decode($p_image);
//        file_put_contents('./uploads/product_images/' . $imgName, $decoded);
//        $url = base_url() . "uploads/product_images/" . $imgName;
//        
//        $image_data_tb = array(
//            'image_path' => $url,
//            'product_id' => $lastProductID,
//            'product_user_id' => $user_id,
//            'product_request_id' => $request_id,
//            'product_categorie_id' => $cat_id
//        );
//        
//        /// insert to product_image table ///
//        $this->db->insert("product_image",$image_data_tb);
//        $product_tb_data['p_image'] = $url;
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($product_tb_data));
    }

    public function S210_add_product_image($product_image_tb_data) {

        /*
          $product_image_tb_data = array(
          'product_id' => $product_id,
          'product_user_id' => $product_user_id,
          'product_request_id' => $product_request_id,
          'product_categorie_id' => $product_categorie_id
          );
         */

        /// Add Product Image ///
        $p_image = $this->input->post('p_image');

        // initial image name //
        $milliseconds = round(microtime(true) * 100);
        $imgName = "p_" . $product_image_tb_data['product_user_id'] . "_" . $milliseconds . ".jpg";
        $decoded = base64_decode($p_image);
        file_put_contents('./uploads/product_images/' . $imgName, $decoded);
        $url = base_url() . "uploads/product_images/" . $imgName;

        $image_data_tb = array(
            'image_path' => $url,
            'product_id' => $product_image_tb_data['product_id'],
            'product_user_id' => $product_image_tb_data['product_user_id'],
            'product_request_id' => $product_image_tb_data['product_request_id'],
            'product_categorie_id' => $product_image_tb_data['product_categorie_id']
        );

        /// insert to product_image table ///
        $this->db->insert("product_image", $image_data_tb);

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($image_data_tb));
    }

    public function S211_like_product($product_tb_data) {

        /*

          $product_tb_data = array(
          'product_id' => $product_id,
          'user_id' => $user_id,
          );

         */
        
        //1. Get Old Like Count 
        $this->db->select('product.p_like_count');
        $this->db->where('product.id', $product_tb_data['product_id']);
        $product_like = $this->db->get('product');
        $like_count = $product_like->row_array();
        $old_like = $like_count['p_like_count'];
        $new_like = $old_like + 1;

        //2. Check if user exist like
        $this->db->where($product_tb_data);
        $p_like = $this->db->get('product_like');


        if ($p_like->num_rows() > 0) {
            //can't like
        } else {
            //can like
            $this->db->where('product.id', $product_tb_data['product_id']);
            $this->db->update('product', array('p_like_count' => $new_like));
            $this->db->insert('product_like', $product_tb_data);
        }


        $response = array(
            'response_code' => '1',
            'response_text' => ''
        );

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }

    public function S212_user_add_wishlist($user_wishlist_tb_data) {

        /* $user_wishlist_tb_data = array(
          'product_id' => $product_id,
          'user_id' => $user_id,
          ); */

        //1. Check if user exist wishlist


        $this->db->where($user_wishlist_tb_data);
        $user_wishlist = $this->db->get('user_wishlist');


        if ($user_wishlist->num_rows() > 0) {
            //can't like
            $response = array(
                'response_code' => '0',
                'response_text' => 'wishlist is exist'
            );
        } else {
            //can like
            $this->db->insert('user_wishlist', $user_wishlist_tb_data);

            $response = array(
                'response_code' => '1',
                'response_text' => ''
            );
        }



        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }

    public function S213_user_list_wishlist($user_id) {

        $this->db->join('product', 'product.id = user_wishlist.product_id');
        $this->db->where("user_wishlist.user_id",$user_id);
        $wish_list = $this->db->get('user_wishlist');

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($wish_list->result_array()));
    }

    public function S213_user_clear_wishlist() {

        $this->db->where('id', $id);
        $this->db->delete('user_wishlist');
    }

}

?>