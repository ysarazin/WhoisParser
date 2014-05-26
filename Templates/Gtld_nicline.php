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
    protected $blocks = array(1 => '/Registrant Name:(.*?)(?=Admin Name)/is',
            2 => '/Admin Name:(.*?)(?=Tech Name)/is',
            3 => '/Tech Name:(.*?)(?=Name Server)/is',
            4 => '/Creation Date:(.*?)(?=Registrar Registration Expiration Date)/is',
            5 => '/Registrar Registration Expiration Date:(.*?)(?=Registrar:)/is');

    /**
	 * Items for each block
	 *
	 * @var array
	 * @access protected
	 */
    protected $blockItems = array(
            1 => array(
                '/Registrant Name:(?>[\x20\t]*)(.+)\nRegistrant Organization:/is' => 'contacts:owner:name',
                '/Registrant Organization:(?>[\x20\t]*)(.+)\nRegistrant Street:/is' => 'contacts:owner:organization',
                '/Registrant Street:(?>[\x20\t]*)(.+)\nRegistrant City:/is' => 'contacts:owner:address',
                '/Registrant City:(?>[\x20\t]*)(.+)\nRegistrant State\/Province/is' => 'contacts:owner:city',
                '/Registrant State\/Province:(?>[\x20\t]*)(.+)\nRegistrant Postal Code:/is' => 'contacts:owner:state',
                '/Registrant Postal Code:(?>[\x20\t]*)(.+)\nRegistrant Country:/is' => 'contacts:owner:zipcode',
                '/Registrant Country:(?>[\x20\t]*)(.+)\nRegistrant Phone:/is' => 'contacts:owner:country',
                '/Registrant Phone:(?>[\x20\t]*)(.+)\nRegistrant Phone Ext:/is' => 'contacts:owner:phone',
                '/Registrant Fax:(?>[\x20\t]*)(.+)\nRegistrant Fax Ext:/is' => 'contacts:owner:fax',
                '/Registrant Email:(?>[\x20\t]*)(.+)\nRegistry Admin ID:/is' => 'contacts:owner:email',
            ),
            2 => array(
                '/Admin Name:(?>[\x20\t]*)(.+)\nAdmin Organization:/is' => 'contacts:admin:name',
                '/Admin Organization:(?>[\x20\t]*)(.+)\nAdmin Street:/is' => 'contacts:admin:organization',
                '/Admin Street:(?>[\x20\t]*)(.+)\nAdmin City:/is' => 'contacts:admin:address',
                '/Admin City:(?>[\x20\t]*)(.+)\nAdmin State\/Province/is' => 'contacts:admin:city',
                '/Admin State\/Province:(?>[\x20\t]*)(.+)\nAdmin Postal Code:/is' => 'contacts:admin:state',
                '/Admin Postal Code:(?>[\x20\t]*)(.+)\nAdmin Country:/is' => 'contacts:admin:zipcode',
                '/Admin Country:(?>[\x20\t]*)(.+)\nAdmin Phone:/is' => 'contacts:admin:country',
                '/Admin Phone:(?>[\x20\t]*)(.+)\nAdmin Phone Ext:/is' => 'contacts:admin:phone',
                '/Admin Fax:(?>[\x20\t]*)(.+)\nAdmin Fax Ext:/is' => 'contacts:admin:fax',
                '/Admin Email:(?>[\x20\t]*)(.+)\nRegistry Tech ID:/is' => 'contacts:admin:email',
            ),
            3 => array(
                '/Tech Name:(?>[\x20\t]*)(.+)\nTech Organization:/is' => 'contacts:tech:name',
                '/Tech Organization:(?>[\x20\t]*)(.+)\nTech Street:/is' => 'contacts:tech:organization',
                '/Tech Street:(?>[\x20\t]*)(.+)\nTech City:/is' => 'contacts:tech:address',
                '/Tech City:(?>[\x20\t]*)(.+)\nTech State\/Province/is' => 'contacts:tech:city',
                '/Tech State\/Province:(?>[\x20\t]*)(.+)\nTech Postal Code:/is' => 'contacts:tech:state',
                '/Tech Postal Code:(?>[\x20\t]*)(.+)\nTech Country:/is' => 'contacts:tech:zipcode',
                '/Tech Country:(?>[\x20\t]*)(.+)\nTech Phone:/is' => 'contacts:tech:country',
                '/Tech Phone:(?>[\x20\t]*)(.+)\nTech Phone Ext:/is' => 'contacts:tech:phone',
                '/Tech Fax:(?>[\x20\t]*)(.+)\nTech Fax Ext:/is' => 'contacts:tech:fax',
                '/Tech Email:(?>[\x20\t]*)(.+)\n/is' => 'contacts:tech:email',
            ),
            4 => array('/Creation Date:(?>[\x20\t]*)(.+)$/im' => 'created'),
            5 => array('/Registrar Registration Expiration Date:(?>[\x20\t]*)(.+)$/im' => 'expires')
        );

}
