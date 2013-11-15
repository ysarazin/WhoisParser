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
class Template_Gtld_nicline extends AbstractTemplate
{

    /**
	 * Blocks within the raw output of the whois
	 *
	 * @var array
	 * @access protected
	 */
    protected $blocks = array(1 => '/Registrant:(.*?)(?=Administrative Contact)/is',
            2 => '/Administrative Contact:(.*?)(?=Technical Contact)/is',
            3 => '/Technical Contact:(.*?)(?=Domain servers in listed order)/is',
            4 => '/Created:(.*?)(?=Expires)/is',
            5 => '/Expires:(.*?)(?=Last updated)/is');

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
            4 => array('/Created:(?>[\x20\t]*)(.+)$/im' => 'created'),
            5 => array('/Expires:(?>[\x20\t]*)(.+)$/im' => 'expires'));

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

        $ResultSet->created = preg_replace('/:\d\d\d/', '', $ResultSet->created);
        $ResultSet->expires = preg_replace('/:\d\d\d/', '', $ResultSet->expires);
        
        foreach ($ResultSet->contacts as $contactType => $contactArray) {
            foreach ($contactArray as $contactObject) {
                $filteredAddress = array_map('trim', explode("\n", trim($contactObject->address)));

                preg_match('/(?>[\x20\t]*)(.*)(?>[\x20\t]{1,})\((.*)\)/i', $filteredAddress[0], $matches);

                if (sizeof($matches) === 0) {
                    $contactObject->name = $filteredAddress[0];
                } else {
                    if (isset($matches[1])) {
                        $contactObject->name = trim($matches[1]);
                    }

                    if (isset($matches[2])) {
                        $contactObject->handle = trim($matches[2]);
                    }
                }

                if ($contactType == 'owner') {
                    $contactObject->email = $filteredAddress[1];
                    $contactObject->email = $filteredAddress[2];
                    $contactObject->address = $filteredAddress[3];

                    preg_match('/(?>[\x20\t]*)(.*)(?>[\x20\t]{1,})(.*)/i', $filteredAddress[4], $matches);
                    if (sizeof($matches) === 0) {
                        $contactObject->city = $filteredAddress[4];
                    } else {
                        if (isset($matches[1])) {
                            $contactObject->city = trim($matches[1]);
                        }

                        if (isset($matches[2])) {
                            $contactObject->state = trim($matches[2]);
                        }
                    }

                    preg_match('/(?>[\x20\t]*)(.*)(?>[\x20\t]{1,})(\w\w)/i', $filteredAddress[5], $matches);
                    if (sizeof($matches) === 0) {
                        $contactObject->zipcode = $filteredAddress[5];
                    } else {
                        if (isset($matches[1])) {
                            $contactObject->zipcode = trim($matches[1]);
                        }

                        if (isset($matches[2])) {
                            $contactObject->country = trim($matches[2]);
                        }
                    }

                    $contactObject->phone = $filteredAddress[6];
                } else {
                    $contactObject->email = $filteredAddress[1];
                    $contactObject->address = $filteredAddress[2];

                    preg_match('/(?>[\x20\t]*)(.*)(?>[\x20\t]{1,})(.*)/i', $filteredAddress[3], $matches);
                    if (sizeof($matches) === 0) {
                        $contactObject->city = $filteredAddress[3];
                    } else {
                        if (isset($matches[1])) {
                            $contactObject->city = trim($matches[1]);
                        }

                        if (isset($matches[2])) {
                            $contactObject->state = trim($matches[2]);
                        }
                    }

                    preg_match('/(?>[\x20\t]*)(.*)(?>[\x20\t]{1,})(\w\w)/i', $filteredAddress[4], $matches);
                    if (sizeof($matches) === 0) {
                        $contactObject->zipcode = $filteredAddress[4];
                    } else {
                        if (isset($matches[1])) {
                            $contactObject->zipcode = trim($matches[1]);
                        }

                        if (isset($matches[2])) {
                            $contactObject->country = trim($matches[2]);
                        }
                    }

                    $contactObject->phone = $filteredAddress[5];
                }
            }
        }
    }
}
