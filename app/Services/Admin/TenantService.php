<?php

namespace Monica\Services\Admin;

use Monica\Models\Tenant;
use Monica\Models\User;
use Illuminate\Database\DatabaseManager;
use Exception;

/**
* This class handle all the complex actions
*/
class TenantService
{
    protected $dbManager;

    protected $tenant;

    protected $user;

    public function __construct(
        DatabaseManager $dbManager,
        Tenant $tenant,
        User $user
    ) {
        $this->dbManager = $dbManager;
        $this->tenant = $tenant;
        $this->user = $user;
    }

    /**
     * Register tenant and creates the tenant owner
     * @param  array  $data tenant information
     * @return bool
     */
    public function registerTenant(array $data) : bool
    {
        try {
            $this->dbManager->beginTransaction();
            $tenant = $this->tenant->create($data);
            $tenantAdminData = $this->extractTenantAdminData($data, $tenant);
            $tenantAdmin = $this->user->create($tenantAdminData);
            $tenantAdmin->assign('tenant_admin');
            $this->dbManager->commit();
            return true;
        } catch (Exception $e) {
            // TO DO: Add log
            $this->dbManager->rollback();
            // TO DO: Add custom exception
            throw new Exception('Error registering a new Tenant', 1);
        }
    }

    /**
     * Retrieves all the tenants in the system
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->tenant->all();
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
