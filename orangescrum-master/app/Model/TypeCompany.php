<?php

class TypeCompany extends AppModel {

    var $name = 'TypeCompany';
    
    /**
    * Getting selected task types
    * 
    * @method getSelTypes
    * @author Orangescrum
    * @return
    * @copyright (c) Aug/2014, Andolsoft Pvt Ltd.
    */
    function getSelTypes() {
	return $this->find("list", array("conditions" => array('TypeCompany.company_id' =>  SES_COMP ), 'fields' => array('TypeCompany.id', 'TypeCompany.type_id')));
    }
    
    /**
    * Getting all task types
    * 
    * @method getTypes
    * @author Orangescrum
    * @return
    * @copyright (c) Aug/2014, Andolsoft Pvt Ltd.
    */
    function getTypes() {
        return $this->find("list", array("conditions" => array('TypeCompany.company_id' => SES_COMP)));
    }

    function getTypeForCompany($company_id) {
        $companies_id = array("0", $company_id);
        if ($company_id) {
            App::import('Model', 'Type');
            $Type = new Type();
            $sql = "SELECT Type.* FROM type_companies AS TypeCompany LEFT JOIN types AS Type ON (TypeCompany.type_id=Type.id)
		WHERE TypeCompany.company_id=" . $company_id . " ORDER BY Type.name ASC";
            $TypeCompany = $this->query($sql);
            $typeArr = null;
            if (empty($TypeCompany)) {
                $typeArr = $Type->find('all', array('conditions' => array('Type.company_id' => $companies_id)));
            } else {
                $typeArr = $TypeCompany;
            }
            $ouputArr = null;
            foreach ($typeArr as $key => $value) {
                $typeData = array("id" => $value['Type']['id'], "name" => $value['Type']['name'], "short_name" => $value['Type']['short_name']);
                $ouputArr[$key] = $typeData;
            }
            return $ouputArr;
        }
    }

}

?>