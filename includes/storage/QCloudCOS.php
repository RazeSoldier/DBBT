<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @copyright
 */

namespace DBBT\Storage;

use DBBT\{
    Checker,
    Config
};
use Qcloud\Cos\Client;

class QCloudCOS implements IStorage
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var string The source file path
     */
    private $source;

    /**
     * @var Client
     */
    private $cosClient;

    /**
     * @var array
     */
    private $remoteConfig;

    /**
     * QcloudCOSBackup constructor.
     * @param Config $config
     * @param string $source
     */
    public function __construct(Config $config, string $source)
    {
        $this->config = $config;
        $this->source = $source;
        $this->checkConfig();

        $remoteAuth = $this->config->get( 'RemoteAuth' );
        $authConfig = [
            'region' => $remoteAuth['region'],
            'credentials' => [
                'appId' => $remoteAuth['appId'],
                'secretId' => $remoteAuth['secretId'],
                'secretKey' => $remoteAuth['secretKey']
            ]
        ];
        $this->cosClient = new Client( $authConfig );

        $remoteConfig = $this->config->get( 'RemoteConfig' );
        $this->remoteConfig = [ $remoteConfig['bucket'], $remoteConfig['uploadPath'] ];
    }

    private function checkConfig()
    {
        // Checks $gRemoteAuth @{
        if ( !$this->config->has( 'RemoteAuth' ) ) {
            throw new \LogicException( '$gRemoteAuth does not exist' );
        }
        $rules = [
            'region' => 'required',
            'appId' => 'required',
            'secretId' => 'required',
            'secretKey' => 'required'
        ];
        $check = new Checker( $rules, $this->config->get( 'RemoteAuth' ) );
        if ( !$check->checkAll() ) {
            throw new \LogicException( '$gRemoteAuth misconfiguration' );
        }
        // @}
        // Checks $gRemoteConfig @{
        if ( !$this->config->has( 'RemoteConfig' ) ) {
            throw new \LogicException( '$gRemoteConfig does not exist' );
        }
        $rules = [
            'bucket' => 'required',
            'uploadPath' => 'required'
        ];
        $check = new Checker( $rules, $this->config->get( 'RemoteConfig' ) );
        if ( !$check->checkAll() ) {
            throw new \LogicException( '$gRemoteConfig misconfiguration' );
        }
        // @}
    }

    public function save() : bool
    {
        try {
            $arr = $this->remoteConfig;
            $arr[] = fopen( $this->source, 'rb' );
            $result = $this->cosClient->Upload( ...$arr );
            if ( is_object( $result ) ) {
                return true;
            }
            return false;
        } catch ( \Exception $e ) {
            echo $e . "\n";
            die ( 1 );
        }
    }
}