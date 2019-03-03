<?php
if (! defined('BASEPATH')) {
    exit('No Direct Script Access Allowed');
}
class Common extends CI_Model
{
    public function createData($table, $data)
    {
        $this->db->insert($table, $data);
        return true;
    }
    public function readData($table, $where = '', $select = '', $join = '', $limit = '', $start = null, $order_by = '', $group_by = '', $num_rows = 0)
    {
        $this->db->select($select);
        $this->db->from($table);
        
        if ($join!='') {
            $this->generateJoining($join);
        }
        if ($where!='') {
            $this->generateWhere($where);
        }
        if ($this->db->field_exists('deleted', $table)) {
            $deleted_str=$table.".deleted";
            $this->db->where($deleted_str, "0");
        }
        
        if ($order_by!='') {
            $this->db->order_by($order_by);
        }
        if ($group_by!='') {
            $this->db->group_by($group_by);
        }
        if (is_numeric($start) || is_numeric($limit)) {
            $this->db->limit($limit, $start);
        }
        $query=$this->db->get();
        $result_array=$query->result_array();
        if ($num_rows==1) {
            $num_rows=$query->num_rows();
            $result_array['extra_index']=array('num_rows'=>$num_rows);
        }
        return $result_array;
    }
    public function updateData($table, $where, $data)
    {
        $this->db->where($where);
        $this->db->update($table, $data);
        return true;
    }
    public function deleteData($table, $where)
    {
        $this->db->where($where);
        $this->db->delete($table);
        return true;
    }
    public function generateWhere($where)
    {
        $keys = array_keys($where);
        for ($i=0; $i<count($keys); $i++) {
            if ($keys[$i]=='where') {
                $this->db->where($where['where']);
            } else if ($keys[$i]=='where_in') {
                $keysWhere = array_keys($where['where_in']);
                for ($j=0; $j<count($keysWhere); $j++) {
                    $field=$keysWhere[$j];
                    $value=$where['where_in'][$keysWhere[$j]];
                    $this->db->where_in($field, $value);
                }
            } else if ($keys[$i]=='where_not_in') {
                $keysWhere = array_keys($where['where_not_in']);
                for ($j=0; $j<count($keysWhere); $j++) {
                    $field=$keysWhere[$j];
                    $value=$where['where_not_in'][$keysWhere[$j]];
                    $this->db->where_not_in($field, $value);
                }
            } else if ($keys[$i]=='or_where') {
                $this->db->or_where($where['or_where']);
            } else if ($keys[$i]=='or_where_advance') {
                $keysWhere = array_keys($where['or_where_advance']);
                for ($j=0; $j<count($keysWhere); $j++) {
                    $field=$where['or_where_advance'][$keysWhere[$j]];
                    $value=$keysWhere[$j];
                    $this->db->or_where($field, $value);
                }
            } else if ($keys[$i]=='or_where_in') {
                $keysWhere = array_keys($where['or_where_in']);
                for ($j=0; $j<count($keysWhere); $j++) {
                    $field=$keysWhere[$j];
                    $value=$where['or_where_in'][$keysWhere[$j]];
                    $this->db->or_where_in($field, $value);
                }
            }
        }
    }

    public function generateJoining($join)
    {
        $keys = array_keys($join);
        for ($i=0; $i<count($join); $i++) {
            $join_table=$keys[$i];
            $join_condition_type=explode(',', $join[$keys[$i]]);
            $join_condition=$join_condition_type[0];
            $join_type=$join_condition_type[1];
            $this->db->join($join_table, $join_condition, $join_type);
        }
    }
    public function countRow($table, $where = '', $count = 'id', $join = '', $group_by = '')
    {
        $this->db->select($count);
        $this->db->from($table);
        if ($join!='') {
            $this->generateJoining($join);
        }
        if ($where!='') {
            $this->generateWhere($where);
        }
        if ($group_by!='') {
            $this->db->group_by($group_by);
        }
                            
        $query=$this->db->get();
        $num_rows = $query->num_rows();
        $result_array[0]['total_rows']=$num_rows;
        return $result_array;
    }
    public function executeQuery($sql)
    {
        $query=$this->db->query($sql);
        return $query->result_array();
    }

    
    public function executeComplexQuery($sql)
    {
        return $query=$this->db->query($sql);
    }
    public function isActive($table, $where = '')
    {
        $this->db->select('status');
        $this->db->from($table);
        $where['status']=1;
        $this->db->where($where);
        $query=$this->db->get();
        $num_rows=$query->num_rows();
        if ($num_rows>0) {
            return true;
        } else {
            return false;
        }
    }

    public function isExist($table, $where = '', $select = '')
    {
        $this->db->select($select);
        $this->db->from($table);
        if ($where!='') {
            $this->db->where($where);
        }
        $query=$this->db->get();
        $num_rows=$query->num_rows();
        if ($num_rows>0) {
            return true;
        } else {
            return false;
        }
    }
    

    public function isUnique($table, $where = '', $select = '')
    {
        $this->db->select($select);
        $this->db->from($table);
        if ($where!='') {
            $this->db->where($where);
        }
        $query=$this->db->get();
        $num_rows=$query->num_rows();
        if ($num_rows>0) {
            return false;
        } else {
            return true;
        }
    }
    
    public function getEnumValues($table_name = "", $column_name = "")
    {
        $empty_array=array();
        if ($table_name=="" || $column_name=="") {
            return $empty_array();
        }
        $type = $this->db->query("SHOW COLUMNS FROM {$table_name} WHERE Field = '{$column_name}'")->row(0)->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $enum = explode("','", $matches[1]);
        asort($enum);
        return $enum;
    }
    
    
    /**
    * method to DUMP DATA
    * @access public
    * @return boolean
    * @param string
    */
    public function importDump($filename = '')
    {
        if ($filename=='') {
            return false;
        }
        if (!file_exists($filename)) {
            return false;
        }
        $templine = '';
        $lines = file($filename);
        foreach ($lines as $line) {
            if (substr($line, 0, 2) == '--' || $line == '') {
                continue;
            }
            $templine .= $line;
            if (substr(trim($line), -1, 1) == ';') {
                $this->executeComplexQuery($templine);
                $templine = '';
            }
        }
        return true;
    }
}
