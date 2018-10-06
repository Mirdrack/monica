<?php

namespace Monica\Service\Admin;

use Monica\Models\Tenant;
use Monica\Models\User;
use Illuminate\Database\DatabaseManager;

/**
* This class handle all the complex actions
*/
class TenantService
{
    protected $dbManager;

    protected $tenant;

    protected $user;
    
    function __construct(
        DatabaseManager $dbManager, 
        Tenant $tenant, 
        User $user
    )
    {
        $this->dbManager = $dbManager;
        $this->tenant = $tenant;
        $this->user = $user;
    }

    /**
     * Register tenant and creates the tenant owner
     */
    public function registerTenant(array $data)
    {
        try {
            $this->dbManager->beginTransaction();
            $tenant = $this->tenant->create($data);
            $tenantAdminData = $this->extractTenantAdminData($data, $tenant);
            $tenantAdmin = $this->user->create($tenantAdminData);
            $tenantAdmin->assign('tenant_admin');
            $this->dbManager->commit();
        } catch (Exception $e) {
            $this->dbManager->rollback();
            throw new Exception('Error registering a new Tenant', 1);
        }
    }

    /**
     * Extract the user data from the original request
     * in order to create the admin of the tenant
     * @param  array  $data Values from the request
     * @return array  Formated data to create the admin
     */
    private function extractTenantAdminData(array $data, Tenant $tenant) : array 
    {
        return [
            'name' => '',
            'email' => $data['email'],
            'password' => uniqid(),
            'tenant_id' => $tenant->id,
        ];
    }
}