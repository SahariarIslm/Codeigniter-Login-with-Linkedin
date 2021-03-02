<?php
class Linkedinlogin_model extends CI_Model
{
  function Is_already_register($id)
  {
    $this->db->where('ln_id', $id);
    $query = $this->db->get('customer_information');
    if($query->num_rows() > 0)
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  function Update_user_data($data, $id)
  {
    $this->db->where('ln_id', $id);
    $this->db->update('customer_information', $data);
  }

  function Insert_user_data($data)
  {
    $this->db->insert('customer_information', $data);
  }


  function show_api()
   {
    $result = $this->db->get('google_settings')->row();
        return $result;

   }

  function insert_data($table, $data)
  {
    $this->db->insert($table, $data);
    return $this->db->insert_id();
  }
  
  function update_data($id,$data = array())
  {
    return $this->db->where('id',$id)->update('google_settings', $data);
  }
}
?>