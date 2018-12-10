<?php
class Mage_Customer_Model_Entity_Setup extends Mage_Customer_Model_Resource_Setup
{
    public function getDefaultEntities(){
        return array(
                'billing:rfc_nombre'=>array(
                        'type'=> 'text',
                        'label'=> 'Razón Social',
                        'visiable' => true,
                        'required' => true,
                        'sort_order' => 0,
                ),
                'billing:rfc'=>array(
                        'type'=> 'text',
                        'label'=> 'RFC',
                        'visiable' => true,
                        'required' => true,
                        'sort_order' => 1,
                ),
                'billing:direccion'=>array(
                        'type'=> 'text',
                        'label'=> 'Calle',
                        'visiable' => true,
                        'required' => true,
                        'sort_order' => 2,
                ),
                'billing:no_exterior'=>array(
                        'type'=> 'text',
                        'label'=> 'No. Exterior',
                        'visiable' => true,
                        'required' => true,
                        'sort_order' => 3,
                ),
                'billing:no_interior'=>array(
                        'type'=> 'text',
                        'label'=> 'No. Interior',
                        'visiable' => true,
                        'required' => false,
                        'sort_order' => 4,
                ),
                'billing:cp'=>array(
                        'type'=> 'text',
                        'label'=> 'CP',
                        'visiable' => true,
                        'required' => true,
                        'sort_order' => 5,
                ),
                'billing:rfc_colonia'=>array(
                        'type'=> 'text',
                        'label'=> 'Colonia',
                        'visiable' => true,
                        'required' => true,
                        'sort_order' => 6,
                ),
                'billing:rfc_municipio'=>array(
                        'type'=> 'text',
                        'label'=> 'Municipio',
                        'visiable' => true,
                        'required' => true,
                        'sort_order' => 7,
                ),
                'billing:rfc_estado'=>array(
                        'type'=> 'text',
                        'label'=> 'Estado',
                        'visiable' => true,
                        'required' => true,
                        'sort_order' => 8,
                ),
                'billing:rfc_email'=>array(
                        'type'=> 'text',
                        'label'=> 'Correo Electrónico',
                        'visiable' => true,
                        'required' => true,
                        'sort_order' => 9,
                ),
                'billing:rfc_solicita'=>array(
                        'type'=> 'text',
                        'label'=> 'Solicitó',
                        'visiable' => true,
                        'required' => true,
                        'sort_order' => 10,
                )
                

        );
    }
}
?>