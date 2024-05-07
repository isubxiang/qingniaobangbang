<?php

class IntegralexchangeModel extends CommonModel{
	
    protected $pk = 'exchange_id';
    protected $tableName = 'integral_exchange';
	
	 public function getTypes(){
		$config = D('Setting')->fetchAll();
		
		$prestigeName = $config['prestige']['name'] ? $config['prestige']['name'] : '威望'; 
        return array(
            1 => '积分',
            2  => $prestigeName,
        );
    }
}