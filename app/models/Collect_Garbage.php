<?php
  class Collect_Garbage{
    private $db;

    public function __construct(){
      $this->db = new Database;
    }
    public function get_complete_request($collector_id){
      $this->db->query('
          SELECT request_main.*, request_completed.*
          FROM request_main
          LEFT JOIN request_assigned ON request_main.req_id = request_assigned.req_id
          LEFT JOIN request_completed ON request_main.req_id = request_completed.req_id
          WHERE request_assigned.collector_id = :collector_id
          AND request_main.type = "completed"
      ');
  
      $this->db->bind(':collector_id', $collector_id);
  
      $results = $this->db->resultSet();
  
      return $results;
  }
  

   
    public function insert($data){
      $this->db->query('INSERT INTO request_completed (req_id, Polythene, Plastic, Glass, Paper_Waste, Electronic_Waste, Metals, credit_amount, note, added) VALUES (:req_id, :Polythene, :Plastic, :Glass, :Paper_Waste, :Electronic_Waste, :Metals, :credit_amount, :note, :added)');
      
      $this->db->bind(':req_id', $data['req_id']);
      $this->db->bind(':Polythene', $data['polythene_quantity']);
      $this->db->bind(':Plastic', $data['plastic_quantity']);
      $this->db->bind(':Glass', $data['glass_quantity']);
      $this->db->bind(':Paper_Waste', $data['paper_waste_quantity']);
      $this->db->bind(':Electronic_Waste', $data['electronic_waste_quantity']);
      $this->db->bind(':Metals', $data['metals_quantity']);
      $this->db->bind(':credit_amount', $data['credit_Amount']);
      $this->db->bind(':note', $data['note']);
      $addedValue = isset($data['added']) ? $data['added'] : 'no';
      $this->db->bind(':added', $addedValue);

      $result = $this->db->execute();
      if ($result) {
        $this->db->query('UPDATE request_main SET type = :type WHERE req_id = :req_id');
        $this->db->bind(':type', 'completed');
        $this->db->bind(':req_id', $data['req_id']);
        
        $updateResult = $this->db->execute();
         if($updateResult){
          return $updateResult;
         }
         else{
          return false;
         }

        }

      
      
  }

  
}