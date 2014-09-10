<?php
return array(
            'class'=>'RDbAuthManager',
            'connectionID'=>'db',
            'itemTable'=>'tak_rbac_authitem',
            'itemChildTable'=>'tak_rbac_authitemchild',
            'assignmentTable'=>'tak_rbac_authassignment',
            'rightsTable'=>'tak_rbac_rights',
            'defaultRoles' => array('Authenticated', 'Guest'),
        );