<?php

class GanttchartAppController extends AppController {
    
    /* Author Girish
     * This method is used to check dependancy field of tasks
     * Remove dependancy while move to project, copy to project, copy task, archive, delete
     */
    
    function task_dependency($EasycaseId = '') {
        /* dependency check start */
        $this->loadModel('Easycase');
        $this->loadModel('Status');
        $this->loadModel('Project');
        $allowed = "Yes";
        $depends = $this->Easycase->find('first', array(
            'conditions' => array('Easycase.id' => $EasycaseId),
            'fields' => array('Easycase.id', 'Easycase.depends', 'Easycase.project_id')
                )
        );

        if (is_array($depends) && count($depends) > 0 && trim($depends['Easycase']['depends']) != '') {
            $project_id = $depends['Easycase']['project_id'];
            $proj = $this->Project->find('first', array('conditions' => array('Project.id' => $project_id), 'fields' => array('Project.id', 'Project.workflow_id')));
            if (isset($proj['Project']['workflow_id']) && !empty($proj['Project']['workflow_id'])) {
                $status_id = $this->Status->find('first', array('conditions' => array('Status.workflow_id' => $proj['Project']['workflow_id']), 'order' => 'seq_order DESC', 'limit' => 1));
                $legend_val = $status_id['Status']['id'];
            } else {
                $legend_val = 3;
            }

            $result = $this->Easycase->find('all', array('conditions' => array('Easycase.id IN (' . $depends['Easycase']['depends'] . ')')));
            if (is_array($result) && count($result) > 0) {
                foreach ($result as $key => $parent) {
                    if ($parent['Easycase']['status'] == 2 && $parent['Easycase']['legend'] == 3) {
                        // NO ACTION
                    } elseif ($parent['Easycase']['isactive'] == 0) {
                        // NO ACTION
                    } elseif ($parent['Easycase']['legend'] == $legend_val) {
                        // NO ACTION
                    } else {
                        $allowed = "No";
                    }
                }
                $this->parent_task = $result;
            }
        }
        /* dependency check end */
        return $allowed;
    }
}

?>