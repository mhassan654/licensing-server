<?php

namespace Mhassan654\LicenseServer\Http\Controllers\Api;

use Illuminate\Http\Request;

use Mhassan654\LicenseServer\Models\IpAddress;
use Mhassan654\LicenseSupport\Support\IpSupport;
use Mhassan654\LicenseServer\Services\LicenseService;
use Mhassan654\LicenseServer\Http\Controllers\BaseController;

class AuthController extends BaseController
{
    public function  __construct()
    {
        $this->middleware("auth:api",["except"=>["login"]]);
    }

    /**
     * Login with sanctum
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string|uuid',
        ]);

        $domain = $request->input('ls_domain');

        $licenseKey = $request->input('license_key');

        $license = LicenseService::getLicenseByKey( $licenseKey);

        if ($license) {
            $license->tokens()->where('name', $domain)->delete();

            $ipAddress = IpAddress::where('license_id', $license->id)->first();
            $serverIpAddress = IpSupport::getIP();

            if (!$ipAddress) {
                $ipAddress = IpAddress::create([
                    'license_id' => $license->id,
                    'ip_address' => $serverIpAddress,
                ]);
            }

            if ($ipAddress && $ipAddress->ip_address == $serverIpAddress) {
                $licenseAccessToken = $license->createToken($domain, ['license-access']);

                return [
                    'status' => true,
                    'message' => 'Successfully logged in.',
                    'access_token' => explode('|', $licenseAccessToken->plainTextToken)[1],
                ];
            }

            return response([
                'status' => false,
                'message' => 'This IP address is not allowed. Please contact the license provider.',
            ], 401);
        }

        return response([
            'status' => false,
            'message' => 'Invalid license key or license source.',
        ], 401);
    }
}
