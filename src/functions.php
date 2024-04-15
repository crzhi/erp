<?php

if(!function_exists('erp_admin_dict_options')){
    /**
     * erp
     * @param $path
     * @param $needAllData
     * @return array
     */
    function erp_admin_dict_options($path, $needAllData = true): array
    {
        return admin_dict()->getOptions('uupt.erp.'.$path,$needAllData);
    }
}
