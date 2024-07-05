<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterModel extends Model
{

    public function getRow($table, $where = '', $array = false, $select='*', $order_by = '')
    {
        $builder = $this->db->table($table);

        $builder->select($select);

        if (!empty($where))


            $builder->where($where);

        $query = $builder->get();

        if ($array):


            if (!empty($order_by)):

                $builder->orderBy("id", $order_by);

            endif;

            return (array)$query->getRowArray();
        else:

            if (!empty($order_by)):

                $builder->orderBy("id", $order_by);

            endif;


            return  $query->getRowArray();
        endif;


    }

    public function getRows($table, $where = '', $fields='*', $start = '', $offset = '', $order_by = '', $order_id = '')
    {

        $builder = $this->db->table($table)->select($fields);

        if (!empty($where))

            $builder->where($where);

        if (!empty($offset)):

            $builder->limit($offset, $start);

        endif;

        if (!empty($order_by)):

            $builder->orderBy($order_id, $order_by);

        endif;


        $query = $builder->get();

        return $query->getResult();


    }

    public function getRowsField($table, $select='*', $where = '', $start = '', $offset = '', $order_by = '', $order_id = '')
    {

        $builder = $this->db->table($table);

        $builder->select($select);

        if (!empty($where))

            $builder->where($where);

        if (!empty($offset)):

            $builder->limit($offset, $start);

        endif;

        if (!empty($order_by)):

            $builder->orderBy($order_id, $order_by);

        endif;


        $query = $builder->get();

        return $query->getResult();
    }

    public function getRowsArray($table, $where = '', $offset = '', $start = '')
    {

        $builder = $this->db->table($table);

        if (!empty($where))

            $builder->where($where);


        if (!empty($offset))

            $builder->limit($offset, $start);

        $query = $builder->get();

        return $query->getRowArray();
    }

    public function insertData($table, $vals=[], $filed_id='', $id = '')
    {
        $builder = $this->db->table($table)->set($vals);
        if (!empty($id)) {
            $exists = $this->db->table($table)->getWhere([$filed_id=>$id])->getFieldCount();
            if(empty($exists)) {
                $builder->insert();
            }else {
                $builder->where($filed_id, $id);
                $builder->update();
            }
            $error = $this->db->error();
            if(!empty($error['message'])) {
                echo $error['message'];
            }
            return $id;
        } else {
            $query = $builder->insert();
             if(!empty($builder->db()->error()['error'])) {
                 echo $builder->db()->error()['message'];
                 exit;
             }
            return $this->db->insertID();
        }
    }

    public function insertOrUpdate($table, $vals=[], $filed_id='', $id = '') {
        $builder = $this->db->table($table);

        if(is_array($id)) {
            $where_fields = $id;
        }else {
            $where_fields[$filed_id] = $id;
        }

        $entry_exists = $builder->select($filed_id)->where($where_fields)->get()->getRowArray();


        if(!empty($entry_exists)) {
            //Update
            $output = ['success'=>1,'method'=>'update'];
            $builder->set($vals)->where($where_fields)->update();
        }else {
            //Insert
            $output = ['success'=>1,'method'=>'insert'];
            $builder->set($vals)->insert();
        }

        return $output;
    }

    public function delete_data($table, $field = '', $where = '')
    {
        $builder = $this->db->table($table);
        if (!empty($where)) {
            $builder->where($field, $where);
            $builder->delete();
        }
    }

    public function last_query()
    {
        return $this->db->getLastQuery();
    }

    public function last_insert_id() {
        return $this->db->insertID();
    }

    public function q($query) {
        return $this->db->query($query);
    }

    public function query($query, $array = false, $single = false)
    {
        $builder = $this->db->query($query);

        if (!empty($builder)) {
            if ($array) {
                if($single) {
                    return $builder->getRowArray();
                }
                return $builder->getResultArray();

            } else {
                if ($single) {
                    return $builder->getRow();
                }
                return !is_bool($builder)?$builder->getResult():'true';
            }
        }
    }
}

