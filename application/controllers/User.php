<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class User extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    } 

    /*
     * Listing of users
     */
    function index()
    {
        $params['limit'] = RECORDS_PER_PAGE; 
        $params['offset'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
        
        $config = $this->config->item('pagination');
        $config['base_url'] = site_url('user/index?');
        $config['total_rows'] = $this->User_model->get_all_users_count();
        $this->pagination->initialize($config);

        $data['users'] = $this->User_model->get_all_users($params);
        
        $data['_view'] = 'user/index';
        $this->load->view('layouts/main',$data);
    }

    /*
     * Adding a new user
     */
    function add()
    {   
        $this->load->library('form_validation');

		$this->form_validation->set_rules('password','Password','required|min_length[6]');
		$this->form_validation->set_rules('name','Name','required');
		$this->form_validation->set_rules('email','Email','required|is_unique[users]');
		$this->form_validation->set_rules('mobile','Mobile','required');
		$this->form_validation->set_rules('gender','Gender','required');
		
		if($this->form_validation->run())     
        {   
            $params = array(
				'password' => $this->input->post('password'),
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'mobile' => $this->input->post('mobile'),
				'gender' => $this->input->post('gender'),
				'created_at' => $this->input->post('created_at'),
				'updated_at' => $this->input->post('updated_at'),
            );
            
            $user_id = $this->User_model->add_user($params);
            redirect('user/index');
        }
        else
        {            
            $data['_view'] = 'user/add';
            $this->load->view('layouts/main',$data);
        }
    }  

    /*
     * Editing a user
     */
    function edit($id)
    {   
        // check if the user exists before trying to edit it
        $data['user'] = $this->User_model->get_user($id);
        
        if(isset($data['user']['id']))
        {
            $this->load->library('form_validation');

			$this->form_validation->set_rules('password','Password','required|min_length[6]');
			$this->form_validation->set_rules('name','Name','required');
			$this->form_validation->set_rules('email','Email','required|is_unique[users]');
			$this->form_validation->set_rules('mobile','Mobile','required');
			$this->form_validation->set_rules('gender','Gender','required');
		
			if($this->form_validation->run())     
            {   
                $params = array(
					'password' => $this->input->post('password'),
					'name' => $this->input->post('name'),
					'email' => $this->input->post('email'),
					'mobile' => $this->input->post('mobile'),
					'gender' => $this->input->post('gender'),
					'created_at' => $this->input->post('created_at'),
					'updated_at' => $this->input->post('updated_at'),
                );

                $this->User_model->update_user($id,$params);            
                redirect('user/index');
            }
            else
            {
                $data['_view'] = 'user/edit';
                $this->load->view('layouts/main',$data);
            }
        }
        else
            show_error('The user you are trying to edit does not exist.');
    } 

    /*
     * Deleting user
     */
    function remove($id)
    {
        $user = $this->User_model->get_user($id);

        // check if the user exists before trying to delete it
        if(isset($user['id']))
        {
            $this->User_model->delete_user($id);
            redirect('user/index');
        }
        else
            show_error('The user you are trying to delete does not exist.');
    }
    
}
