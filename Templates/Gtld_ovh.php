<?php
/**
 * Novutec Domain Tools
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category   Novutec
 * @package    DomainParser
 * @copyright  Copyright (c) 2007 - 2013 Novutec Inc. (http://www.novutec.com)
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */

/**
 * @namespace Novutec\WhoisParser
 */
namespace Novutec\WhoisParser;

/**
 * Template for IANA #146, #440
 *
 * @category   Novutec
 * @package    WhoisParser
 * @copyright  Copyright (c) 2007 - 2013 Novutec Inc. (http://www.novutec.com)
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class Template_Gtld_ovh extends AbstractTemplate
{

    /**
	 * Blocks within the raw output of the whois
	 *
	 * @var array
	 * @access protected
	 */
    protected $blocks = array(1 => '/Registrant:(.*?)(?=Administrative Contact)/is',
            2 => '/Administrative Contact:(.*?)(?=Technical Contact)/is',
            3 => '/Technical Contact:(.*?)(?=Billing Contact)/is',
            4 => '/Billing Contact:(.*?)(?=Registrar of Record)/is',
            5 => '/Record expires on (.*?)\./is',
            6 => '/Record created on (.*?)\./is');


    /**
	 * Items for each block
	 *
	 * @var array
	 * @access protected
	 */
    protected $blockItems = array(
            1 => array('/Registrant:\n(?>[\x20\t]*)(.+)/is' => 'contacts:owner:address'),
            2 => array('/Administrative Contact:\n(?>[\x20\t]*)(.+)/is' => 'contacts:admin:address'),
            3 => array('/Technical Contact:\n(?>[\x20\t]*)(.+)/is' => 'contacts:tech:address'),
            4 => array('/Billing Contact:\n(?>[\x20\t]*)(.+)/is' => 'contacts:billing:address'),
            5 => array('/Record expires on (.*?)\./is' => 'expires'),
            6 => array('/Record created on (.*?)\./is' => 'created'));

    /**
     * After parsing do something
     *
     * Fix addresses
     *
     * @param  object &$WhoisParser
     * @return void
     */
    public function postProcess(&$WhoisParser)
    {
        $ResultSet = $WhoisParser->getResult();

        foreach ($ResultSet->contacts as $contactType => $contactArray) {
            foreach ($contactArray as $contactObject) {
                $filteredAddress = array_map('trim', explode("\n", trim($contactObject->address)));

                if (sizeof($filteredAddress) === 6) {
                    $contactObject->name = $filteredAddress[0];
                    $contactObject->address = $filteredAddress[1];
                    $contactObject->city = $filteredAddress[2];
                    $contactObject->country = $filteredAddress[3];
                    $contactObject->phone = $filteredAddress[4];
                    $contactObject->email = $filteredAddress[5];
                } elseif (sizeof($filteredAddress) === 7) {
                    $contactObject->organization = $filteredAddress[0];
                    $contactObject->name = $filteredAddress[1];
                    $contactObject->address = $filteredAddress[2];
                    $contactObject->city = $filteredAddress[3];
                    $contactObject->country = $filteredAddress[4];
                    $contactObject->phone = $filteredAddress[5];
                    $contactObject->email = $filteredAddress[6];
                }
            }
        }
    }
}
