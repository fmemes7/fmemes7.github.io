<?php
require_once("Main.php"); // loading Main controller
class Report extends Main
{
	public $userId;
	public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('loggedIn') != 1) {
        	redirect('main/loginPage', 'location');
        }
        $this->userId=$this->session->userdata('userId');
    }
    public function index()
    {
        $this->autoPost();
    }
    public function autoPost()
    {
    	$data['pageTitle'] = 'Auto Post Campaign List';
    	$data['body'] = 'shadowpostig/report/auto_post_list';
        $this->_mainView($data);
    }
    public function autoPostList()
    {
    	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('main/accessForbidden', 'location');
        }
        $page = isset($_POST['page']) ? intval($_POST['page']) : 15;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';
        $post_type = trim($this->input->post('post_type', true));
        $scheduled_from = trim($this->input->post('scheduled_from', true));
        if ($scheduled_from) {
            $scheduled_from = date('Y-m-d', strtotime($scheduled_from));
        }
        $scheduled_to = trim($this->input->post('scheduled_to', true));
        if ($scheduled_to) {
            $scheduled_to = date('Y-m-d', strtotime($scheduled_to));
        }
        $is_searched = $this->input->post('is_searched', true);
        if ($is_searched) {
            $this->session->set_userdata('facebook_auto_poster_scheduled_from', $scheduled_from);
            $this->session->set_userdata('facebook_auto_poster_scheduled_to', $scheduled_to);
            $this->session->set_userdata('facebook_auto_poster_post_type', $post_type);
        }
        $search_scheduled_from = $this->session->userdata('facebook_auto_poster_scheduled_from');
        $search_scheduled_to = $this->session->userdata('facebook_auto_poster_scheduled_to');
        $search_post_type = $this->session->userdata('facebook_auto_poster_post_type');
        $where_simple = [];
        if ($search_post_type) {
            $where_simple['post_type'] = $search_post_type;
        }
        if ($search_scheduled_from) {
            if ($search_scheduled_from != '1970-01-01') {
                $where_simple["Date_Format(schedule_time,'%Y-%m-%d') >="] = $search_scheduled_from;
            }
        }
        if ($search_scheduled_to) {
            if ($search_scheduled_to != '1970-01-01') {
                $where_simple["Date_Format(schedule_time,'%Y-%m-%d') <="] = $search_scheduled_to;
            }
        }
        $where_simple['user_id'] = $this->userId;
        $where = ['where'=>$where_simple];
        $order_by_str = $sort.' '.$order;
        $offset = ($page - 1) * $rows;
        $result = [];
        $table = 'instagram_auto_post';
        $info = $this->common->readData($table, $where, $select = '', $join = '', $limit = $rows, $start = $offset, $order_by = $order_by_str, $group_by = '');
        $infooo = $this->db->last_query();
        $total_rows_array = $this->common->countRow($table, $where, $count = 'id', $join = '');
        $total_result = $total_rows_array[0]['total_rows'];
        for ($i = 0; $i < count($info); $i++) {
            $posting_status = $info[$i]['posting_status'];
            if ($posting_status == '2') {
                $info[$i]['status'] = '<span class="label label-success">Completed</span>';
            } elseif ($posting_status == '1') {
                $info[$i]['status'] = '<span class="label label-warning">Processing</span>';
            } else {
                $info[$i]['status'] = '<span class="label label-danger">Pending</span>';
            }
            $post_type = $info[$i]['post_type'];
            $post_type = ucfirst(str_replace('_submit', '', $post_type));
            $info[$i]['post_type'] = $post_type;
            if ($info[$i]['schedule_time'] != '0000-00-00 00:00:00') {
                $scheduled_at = date('M j, y H:i', strtotime($info[$i]['schedule_time']));
            } else {
                $scheduled_at = '<i class="fa fa-remove red" title="Instantly posted"></i>';
            }
            $info[$i]['scheduled_at'] = $scheduled_at;
            if (strlen($info[$i]['message']) >= 60) {
                $info[$i]['message_formatted'] = substr($info[$i]['message'], 0, 60).'...';
            } else {
                $info[$i]['message_formatted'] = $info[$i]['message'];
            }
            if ($posting_status == '2') {
                $post_url = "<a title='Visit your post' target='_BLANK' href='".$info[$i]['post_url']."'><span class='btn btn-info btn-circle btn-lg btn-outline'><i class='fa fa-hand-o-right'></i></span></a>";
            } else {
                $post_url = "<a title='This post is not published yet.' class='btn btn-info btn-circle btn-lg btn-outline'> <i class='fa fa-remove'></i></a>";
            }
            
            $delete_url = "&nbsp;&nbsp;&nbsp;<a title='Delete this post from our database' id='".$info[$i]['id']."' class='delete btn-sm btn btn-info btn-circle btn-lg btn-outline'><i class='fa fa-trash'></i></a>";
            $action_url = $post_url.$delete_url;
            $info[$i]['action_url'] = $action_url;
        }
        echo convertToGridData($info, $total_result);
    }
    public function deletePpost()
    {
        if (!$_POST) {
            exit();
        }
        $id = $this->input->post('id');
        if ($this->common->deleteData('instagram_auto_post', ['id'=>$id, 'user_id'=>$this->userId])) {
            echo '1';
        } else {
            echo '0';
        }
    }
}