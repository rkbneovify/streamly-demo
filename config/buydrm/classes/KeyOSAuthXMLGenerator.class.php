<?php
  /** File contains KeyOSAuthXMLGenerator and other auxiliary classes
   *      used for AuthXML generation.
   *
   * @author Vyacheslav
   * @version 04.19.19.1234
   *
   * NOTE: If authentication XML contains licenses, it cannot contain
   *       any Domain action (JOIN or LEAVE) and vice versa.
   */


  /** KeyOSAuthXMLGenerator implements methods to generate and sign authentication XML.
   */
  class KeyOSAuthXMLGenerator extends KeyOS_Exception {
    public $AllowedKeyIdList = null;

    private $_lic_list = null;
    private $_contk_list = null;
    private $_custom_nodes = array();
    private $_now_utc = null; // Should be set before exp_time to avoid situation when exp_time is less than now_time (generation time).
    private $_exp_time = null;
    private $_username = null;
    private $_external_id = null;
    private $_ip = null;
    private $_pem = null;

    // Domain properties
    private $_domain_ext = null; // KeyOS_Domain object
    private $_domain_action = null; // KeyOS_DomainExt::JOIN / KeyOS_DomainExt::LEAVE

    // GeoFence. Array of allowed two-symbol country codes
    private $_country_codes = array();


    /** KeyOSAuthXMLGenerator::__construct() - Sets path to private RSA Key file (.pem) and initializes some other properties.
     *
     * @param string
     * @return void
     * @throws Exception
     */
    function __construct($pem_key) {
      if (!file_exists($pem_key)) {
        $this->_throw(__METHOD__ . '(): PEM file (' . $pem_key . ') doesn\'t exist. Please ensure that you have passed valid file\'s location and/or filename.');
      }

      $this->_pem = $pem_key;
      $this->_now_utc = KeyOS_Date::UTC2UnixTimeStamp();
      $this->_lic_list = new KeyOS_UniqueList();
      $this->_contk_list = new KeyOS_UniqueList();
      $this->AllowedKeyIdList = new KeyOS_UniqueList();
    }


    /** KeyOSAuthXMLGenerator::validation()  -  Get/Set bool - whether validation is enabled or disabled
     *                                          for this authentication XML generator.
     *                                  This method overrides inherited method to set validation flag
     *                                  to all licenses (registered) and their Policy and PlayRight instances as well.
     *
     * NOTE: Allows to disable validation so that new parameters can be added w/o modifications of generator.
     *
     * @param bool IF SET | no params IF GET
     * @return $this IF SET | bool IF GET
     * @throws Exception
     */
    public function validation($bool = null) {
      if (func_num_args() == 0) {
        return parent::validation();
      }

      foreach ($this->_getPreparedLicensesList() as $lic) {
        $lic->validation($bool);
      }

      return parent::validation($bool);
    }

    /** KeyOSAuthXMLGenerator::joinDomainId() - Sets DOMAIN JOIN action with passed Domain Id
     *
     * @param string $domain_id - GUID string, KeyOS_GUID
     * @return $this
     * @throws Exception
     */
    public function joinDomainId ($domain_id) {
      if ($this->_domain_action) {
        $this->_throw(__METHOD__ . '(): Domain action and ID were already set.');
      }

      if (! $this->_lic_list->isEmpty()) {
        $this->_throw(__METHOD__ . '(): One authentication XML cannot contain licenses together with Domain \'join\' or \'leave\' action.');
      }

      $this->_domain_ext = new KeyOS_DomainExt($domain_id);
      $this->_domain_action = KeyOS_DomainExt::JOIN;

      return $this;
    }

    /** KeyOSAuthXMLGenerator::leaveDomainId() - Sets DOMAIN LEAVE action with passed Domain Id
     *
     * @param string $domain_id - GUID string, KeyOS_GUID
     * @return $this
     * @throws Exception
     */
    public function leaveDomainId ($domain_id) {
      $this->joinDomainId($domain_id)->_domain_action = KeyOS_DomainExt::LEAVE;

      return $this;
    }

    /** KeyOSAuthXMLGenerator::registerLicense() - Registers license to add it into authentication XML.
     *
     * NOTE: This method is called after new license creation using methods:
     *       newSimpleLicense(), newRootLicense() and newLeafLicense().
     *       Also newWidevineLicense() and newFairPlayLicense().
     *
     * @param mixed $license - KeyOS_PR_SimpleLic, KeyOS_PR_RootLic, KeyOS_PR_LeafLic,
     *                         KeyOS_WV_SimpleLic, KeyOS_FP_SimpleLic.
     *
     * @return $this
     * @throws Exception
     */
    public function registerLicense ($license) {
      if ($this->_domain_action) {
        $this->_throw(__METHOD__ . '(): One authentication XML cannot contain licenses together with Domain \'join\' or \'leave\' action.');
      }

      if ($license instanceof KeyOS_License) {
        // Make sure that there is only one Widevine License registered
        if ($license->isWidevine()) {
          foreach ($this->_lic_list->toArray() as $lic) {
            if ($lic->isWidevine()) {
              $this->_throw(__METHOD__ . '(): Attempt to register more than one Widevine license. Allowed only one Widevine license.');
            }
          }
        }

        // Make sure that there is only one FairPlay License registered
        if ($license->isFairPlay()) {
          foreach ($this->_lic_list->toArray() as $lic) {
            if ($lic->isFairPlay()) {
              $this->_throw(__METHOD__ . '(): Attempt to register more than one FairPlay license. Allowed only one FairPlay license.');
            }
          }
        }

        // Sets License's validation flag equal to KeyOSAuthXMLGenerator's one and adds license to the list of all licenses
        $this->_lic_list->add($license->validation($this->validation()));

        return $this;
      }

      $this->_throw(__METHOD__ . '(): Attempt to register unknown type of license.');
    }

    /** KeyOSAuthXMLGenerator::registerLicense() - Registers ContentKey to add it into authentication XML.
     *
     * NOTE: This method is deprecated and now intended to be used by newContentKey(...) method
     *       which creates instance of KeyOS_ContentKey and registers it to add it into AuthXML.
     *
     * @return $this
     */
    public function registerContentKey ($contk) {
      $this->_contk_list->add($contk);

      return $this;
    }

   /** KeyOSAuthXMLGenerator::newWidevineLicense() - Creates new Widevine SIMPLE license.
    *
    * @return pointer to new KeyOS_WV_SimpleLic object.
    * @throws Exception
    */
    public function newWidevineLicense () {
      return $this->_newLicense(KeyOS_License::WIDEVINE_SIMPLE);
    }

    /** KeyOSAuthXMLGenerator::newFairPlayLicense() - Creates new FairPlay SIMPLE license.
     *
     * @return pointer to new KeyOS_FP_SimpleLic object.
     * @throws Exception
     */
    public function newFairPlayLicense () {
      return $this->_newLicense(KeyOS_License::FAIRPLAY_SIMPLE);
    }

    /** KeyOSAuthXMLGenerator::newSimpleLicense() - Creates new SIMPLE license.
     *
     * @param mixed $key_id - [Optional] KeyOS_GUID, GUID or Base64 string
     * @return pointer to new KeyOS_PR_SimpleLic object.
     * @throws Exception
     */
    public function newSimpleLicense ($key_id = null) {
      return $this->_newLicense(KeyOS_License::SIMPLE, null, $key_id);
    }

    /** KeyOSAuthXMLGenerator::newRootLicense() - Creates new ROOT license.
     *
     * @param mixed $root_key_id - KeyOS_GUID, GUID or Base64 string
     * @return pointer to new KeyOS_PR_RootLic object.
     * @throws Exception
     */
    public function newRootLicense ($root_key_id) {
      return $this->_newLicense(KeyOS_License::ROOT, $root_key_id);
    }

    /** KeyOSAuthXMLGenerator::newLeafLicense() - Creates new LEAF license.
     *
     * @param mixed $root_key_id - KeyOS_GUID, GUID or Base64 string
     * @param mixed $key_id - [Optional] KeyOS_GUID, GUID or Base64 string
     * @return pointer to new KeyOS_PR_LeafLic object.
     * @throws Exception
     */
    public function newLeafLicense ($root_key_id, $key_id = null) {
      return $this->_newLicense(KeyOS_License::LEAF, $root_key_id, $key_id);
    }

    /** KeyOSAuthXMLGenerator::newPoliciesSet() - Creates object of Policies Set.
     *
     * @param mixed $id - Optional. GUID string or KeyOS_GUID.
     *
     * NOTE: If argument is passed, it creates new KeyOS_LicensePolicies with passed Id (GUID).
     *
     * @return pointer to new KeyOS_LicensePolicies object
     */
    public function newPoliciesSet($id = null) {
      return new KeyOS_LicPolicy($id);
    }

    /** KeyOSAuthXMLGenerator::newPlayRightsSet() - Creates object of PlayRights Set.
     *
     * @param mixed $id - Optional. GUID string or KeyOS_GUID.
     *
     * NOTE: If argument is passed, it creates new KeyOS_PlayRightsSet with passed Id (GUID).
     *
     * @return pointer to new KeyOS_PlayRightsSet object
     */
    public function newPlayRightsSet($id = null) {
      return new KeyOS_PlayRightsSet($id);
    }

    /** KeyOSAuthXMLGenerator::newContentKey() - Creates instance of KeyOS_ContentKey.
     *
     * NOTE: Several ContentKey may be added. They will differ by KeyID.
     *
     * @params mixed $key_id  -  KeyOS_GUID, GUID-string or GUID-string-base64
     * @params mixed $aes_key_id  -  KeyOS_GUID, GUID-string or GUID-string-base64
     * @params mixed $aes_key_iv  -  KeyOS_GUID, GUID-string or GUID-string-base64
     * @params mixed $aes_data  -  KeyOS_GUID, GUID-string or GUID-string-base64
     *
     * @return pointer to new KeyOS_ContentKey instance.
     */
    public function newContentKey($key_id, $aes_key_id, $aes_key_iv, $aes_data) {
      $content_key = new KeyOS_ContentKey($key_id, $aes_key_id, $aes_key_iv, $aes_data);
      $this->registerContentKey($content_key);

      return $content_key;
    }

    /** KeyOSAuthXMLGenerator::AuthXMLGenerationTime() - Gets generation time of authentication XML itself in UTC.
     *
     * Usage:
     *    - GET:
     *      - AuthXMLGenerationTime()  -  returns generation date/time string like 2020-12-31 23:23:59.000 (UTC)
     *      - AuthXMLGenerationTime(false)  -  returns generation date/time in seconds (UnixTime) (UTC)
     *
     * @param bool $fl_formatted_date
     * @return TimeStamp (string or seconds)
     */
    public function AuthXMLGenerationTime ($fl_formatted_date = true) {
      return $fl_formatted_date ? KeyOS_Date::dtf($this->_now_utc) : $this->_now_utc;
    }

    /** KeyOSAuthXMLGenerator::AuthXMLExpirationTime() - Sets / Gets expiration time of authentication XML itself in UTC.
     *
     * NOTE: It assumes that date passed is already in UTC or relative.
     *
     * Usage:
     *    - SET:
     *      - AuthXMLExpirationTime('2020-12-31 23:23:59')  -  set date/time to 2020-12-31 23:23:59 (should be UTC)
     *      - AuthXMLExpirationTime(1609478639)  - sets same as 2020-12-31 23:23:59 but in seconds (UnixTime) (should be UTC)
     *      - AuthXMLExpirationTime('+2 minutes')  -  set date/time to now + 2 minutes (will be automatically converted to UTC)
     *    - GET:
     *      - AuthXMLExpirationTime()  -  returns date/time string like 2020-12-31 23:23:59.000 (UTC)
     *      - AuthXMLExpirationTime(false)  -  returns date/time in seconds (UnixTime) (UTC)
     *      - AuthXMLExpirationTime(true|false, true)  -  returns date/time, but will throw exception if date/time is not set yet. Used internally.
     *
     * @param mixed $datetime_utc - TimeStamp (string or seconds) | null or bool IF GET
     * @param bool $fl_exception_if_not_set - IF GET - to throw exception if date/time is not set
     * @return $this IF SET | TimeStamp (string or seconds) IF GET
     * @throws Exception
     */
    public function AuthXMLExpirationTime ($datetime_utc = null, $fl_exception_if_not_set = false) {
      if ($datetime_utc === null || is_bool($datetime_utc)) {
        if ($fl_exception_if_not_set && ! $this->_exp_time) {
          $this->_throw(__METHOD__ . '(): AuthXML\'s Expiration date/time\ is not set.');
        }

        $fl_formatted_date = $datetime_utc !== false;
        return $fl_formatted_date ? KeyOS_Date::dtf($this->_exp_time) : $this->_exp_time;
      }

      $datetime_utc = KeyOS_Date::UTC2UnixTimeStamp($datetime_utc);

      if ($datetime_utc <= $this->_now_utc) {
        $this->_throw(__METHOD__ . '(): AuthXML\'s Expiration date/time (' . KeyOS_Date::dtf($datetime_utc) . ') should be greater than AuthXML\'s Generation date/time (' . $this->AuthXMLGenerationTime() . ').');
      }

      $this->_exp_time = $datetime_utc;
      return $this;
    }

    /** KeyOSAuthXMLGenerator::IP()  -  Sets / Gets IP.
     *
     * @param string $ip
     * @return $this
     * @throws Exception
     */
    public function IP($ip = null) {
      if (func_num_args() == 0) {
        return $this->_ip;
      }

      if ($this->validation() && @inet_pton($ip) === false) {
        $this->_throw(__METHOD__ . '(): Incorrect IP: ' . $ip);
      }

      $this->_ip = $ip;
      return $this;
    }

    /** AuthXMLGenerator::Username()  -  Sets / Gets Username (of end-user, not KeyOS one).
     *
     * NOTE: Maximum 40 symbols.
     *
     * @param string $username
     * @return $this
     * @throws Exception
     */
    public function Username($username = null) {
      if (func_num_args() == 0) {
        return $this->_username;
      }

      if ($this->validation() && strlen($username) > 40) {
        $this->_throw(__METHOD__ . '(): Cannot be longer than 40 symbols. Length: ' . strlen($username));
      }

      $this->_username = $username;
      return $this;
    }

    /** AuthXMLGenerator::ExternalId()  -  Sets / Gets External Id.
     *
     * NOTE: Maximum 40 symbols.
     *
     * @param string $id
     * @return $this
     * @throws Exception
     */
    public function ExternalId($id = null) {
      if (func_num_args() == 0) {
        return $this->_external_id;
      }

      if ($this->validation() && strlen($id) > 40) {
        $this->_throw(__METHOD__ . '(): Cannot be longer than 40 symbols. Length: ' . strlen($id));
      }

      $this->_external_id = $id;
      return $this;
    }

    /** KeyOSAuthXMLGenerator::CustomerNodes()  -  Adds / Gets CustomNodes - custom key/value pairs
     *
     * Usage:
     *    - SET:
     *      - CustomNodes('node1', 'value1')  -  adds one custom key/value pair
     *      - CustomNodes(array('node1'=>'value1', 'node2'=>'value2', ...))  -  adds array (assoc) of Custom Nodes (key/value pairs)
     *    - GET:
     *      - CustomNodes()  -  Gets assoc array of Custom Nodes (key/value pairs)
     *
     * @param $key_or_key_value_pairs - assoc array of key/value pairs - IF SET | null - no arguments IF GET
     * @param $value - IF first argument is key (not assoc array of key/values pairs). Otherwise this arg is ignored
     * @return $this IF SET | assoc array of key/value paris - IF GET
     * @throws Exception
     */
    public function CustomNodes($key_or_key_value_pairs = null, $value = null) {
      if (func_num_args() == 0) {
        return $this->_custom_nodes;
      }

      if (! is_array($key_or_key_value_pairs)) {
        if (func_num_args() < 2) {
          $this->_throw(__METHOD__ . '(): Custom fields should be associated array. Type: ' . typeof($key_or_key_value_pairs));
        }

        $key_or_key_value_pairs = array($key_or_key_value_pairs => $value);
      }

      foreach ($key_or_key_value_pairs as $key => $value) {
        $key = (string)$key;

        if (preg_match('/^[^\d]+\w+$/', $key) !== 1) {
          $this->_throw(__METHOD__ . '(): XML-node name may consist of letters, numbers and underscores only and shouldn\'t start from a number. Passed: ' . $key);
        }

        $this->_custom_nodes[$key] = (string)$value;
      }

      return $this;
    }

    /** KeyOSAuthXMLGenerator::AllowedCountries()  -  Adds/Gets two-symbol country codes of allowed countries.
     *    Such codes as the following are acceptable: US, CA, GB, A0, A1 and etc.
     *
     * Example of usage:
     *    SET: AllowedCountries('USCAGB') or AllowedCountries(array('US', 'CA', 'GB'))
     *    GET as Array: AllowedCountires() or AllowedCountires(false)
     *    GET as string: AllowedCountires(true)
     *
     * @param $codes (string or array) IF SET | bool IF GET
     * @return $this if SET | $codes (string or array) IF GET
     * @throws Exception
     */
    public function AllowedCountries($codes = null) {
      if ($codes === null || is_bool($codes)) {
        $fl_return_as_str = $codes === true;
        return $fl_return_as_str ? join('', $this->_country_codes) : $this->_country_codes;
      }

      $codes = is_string($codes) ? str_split($codes, 2) : (array)$codes;

      foreach ($codes as $code) {
        if (preg_match('/^[a-z]{1}[a-z012]{1}$/i', $code) !== 1) {
          parent::_throw(__METHOD__ . 'Invalid country code passed: \'' . $code . '\'. Two-symbols country codes are accepted only.');
        }

        $this->_country_codes[$code] = $code;
      }

      return $this;
    }


    /** KeyOSAuthXMLGenerator::generateAuthenticationXML() - Signs authentication XML with passed private key.
     *
     * @return string XML
     * @throws Exception
     */
    public function generateAuthenticationXML() {
      // Check AuthXML settings and throw exception if something is wrong
      $this->checkAuthXMLSettings();

      // Prepare licenses list with bound policies and playrights sets
      $licenses_list = $this->_getPreparedLicensesList();

      // Create Opening XML Elements
      $xml_root = new KeyOS_SimpleXML('<KeyOSAuthenticationXML/>');
      $xml_data = $xml_root->addChild('Data');


      // Create Domain JOIN or LEAVE action if no licenes added.
      if ($this->_domain_action) {
        $xml_domain = $xml_data->addChild('Domain');
        $xml_domain->addAttribute('action', $this->_domain_action);
        $xml_domain->addChild('DomainId', $this->_domain_ext->getDomainId()->toString());
      }


      // Create License XML-elements if no Domain JOIN or LEAVE action created.

      // First Widevine and FairPlay settings if any
      foreach ($licenses_list as $lic) {
        if (! $lic->isWidevine() && ! $lic->isFairPlay()) {
          continue;
        }

        $xml_data->addChildXML($lic->Policy()->generateXML());

        $r = $lic->PlayRight();
        if ($r->isNotEmpty()) {
          $xml_data->addChildXML($r->generateXML());
        }
      }


      // Then PlayReady settings if any

      foreach ($licenses_list as $lic) {
        if (! $lic->isPlayReady()) {
          continue;
        }

        $lic_type = $lic->getType();

        $xml_license = $xml_data->addChild('License');
        $xml_license->addAttribute('type', $lic_type);

        // RootKeyId, RootRMId
        if ($lic_type === KeyOS_License::ROOT || $lic_type === KeyOS_License::LEAF) {
          if ($lic->getRootKeyId()) {
            $xml_license->addChild('RootKeyId', $lic->getRootKeyId()->toString());
          }

          if ($lic->getRootRMId()) {
            $xml_license->addChild('RootRMId', $lic->getRootRMId());
          }
        }

        // KeyId, RMId
        if ($lic_type === KeyOS_License::SIMPLE || $lic_type === KeyOS_License::LEAF) {
          $key_id = $lic->getKeyId();

          if ($key_id) {
            // If list of allowed KeyIDs contains something, then this KeyID should be put there too.
            if (! $this->AllowedKeyIdList->isEmpty()) {
              $this->AllowedKeyIdList->add($key_id);
            }

            $xml_license->addChild('KeyId', $lic->getKeyId()->toString());
          }

          if ($lic->getRMId()) {
            $xml_license->addChild('RMId', $lic->getRMId());
          }
        }

        // DomainId
        if ($lic_type === KeyOS_License::SIMPLE || $lic_type === KeyOS_License::ROOT) {
          if ($lic->getDomainId()) {
            $xml_license->addChild('DomainId', $lic->getDomainId()->toString());
          }
        }

        // Policy->Id
        $p = $lic->getPoliciesSet();

        if ($lic->isPersistent() || ! $p->isEmpty()) {
          $xml_license->addChild('Policy')->addChild('Id', $p->getId()->toString());
        }

        // Play->Id
        $pr = $lic->getPlayRightsSet();

        if (! $pr->isEmpty()) {
          $xml_license->addChild('Play')->addChild('Id', $pr->getId()->toString());
        }
      }


      // All registered content keys are added to authentication Xml
      foreach ($this->_contk_list->toArray() as $contk ) {
        $xml_data->addChildXML($contk->generateXML());
      }
        
         
      // Create external Policy XML-nodes
      $unique_policies_sets = new KeyOS_UniqueList();

      foreach ($licenses_list as $lic) {
        if (! $lic->isPlayReady()) {
          continue;
        }

        $p = $lic->Policy();
        $lic_is_persistent = $lic->isPersistent();

        if ($unique_policies_sets->has($p) || ($p->isEmpty() && ! $lic_is_persistent)) {
          continue;
        }

        $unique_policies_sets->add($p);

        $xml_policy = $xml_data->addChild('Policy');
        $xml_policy->addAttribute('id', $p->getId()->toString());

        if ($lic_is_persistent) {
          $xml_policy->addAttribute('persistent', 'true');
        }

        foreach ($p->getPolicies() as $key => $value) {
          $xml_policy->addChild($key, $value);
        }
      }


      // Create external Play XML-nodes
      $unique_play_rights_sets = new KeyOS_UniqueList();

      foreach ($licenses_list as $lic) {
        if (! $lic->isPlayReady()) {
          continue;
        }

        $pr = $lic->PlayRight();

        if (!$pr || $unique_play_rights_sets->has($pr) || $pr->isEmpty()) {
          continue;
        }

        $unique_play_rights_sets->add($pr);

        $xml_play = $xml_data->addChild('Play');
        $xml_play->addAttribute('id', $pr->getId()->toString());


        // Create Play Enablers XML-nodes
        if ($pr->PlayEnablersList->hasItems()) {
          $xml_play_enablers = $xml_play->addChild('Enablers');

          foreach ($pr->PlayEnablersList->toArray() as $enabler_id => $enabler) {
            $xml_play_enablers->addChild('Id', $enabler_id);
          }
        }

        // Create OutputProtections XML-node
        if ($pr->hasOutputProtection()) {
          $xml_op = $xml_play->addChild('OutputProtections');

          // Create OPL XML-node in OutputProtections node
          if ($pr->hasOPL()) {
            $xml_opl = $xml_op->addChild('OPL');

            foreach ($pr->OPL() as $op_name => $value) {
              $xml_opl->addChild($op_name, $value);
            }
          }

          // Create Explicit XML-elements in OutputProtections node
          foreach ($pr->OPExplicit() as $op_name => $data) {
            // Explicit output protections may have multiple IDs set for them.
            $xml_expl = $xml_op->addChild($op_name);

            foreach ($data as $guid => $config_data_list) {
              foreach ($config_data_list as $config_data) {
                $xml_expl->addChild('Id', $guid)->addAttribute('ConfigData', $config_data);
              }
            }
          }
        }
      }


      // Create AuthXML settings nodes

      // KeyIds for which authentication XML is allowed to request licenses
      if (! $this->AllowedKeyIdList->isEmpty()) {
        $xml_kid_list = $xml_data->addChild('KeyIDList');

        foreach ($this->AllowedKeyIdList->toArray() as $key_id_str => $item) {
          $xml_kid_list->addChild('KeyID', $key_id_str);
        }
      }

      if (count($this->_custom_nodes) > 0) {
        $xml_custom_nodes = $xml_data->addChild('CustomNodes');

        foreach ($this->_custom_nodes as $key => $value) {
          // Since value can be any input, surrounding it with <![CDATA[...]]>
          $xml_custom_nodes->addChildCDATA($key, $value);
        }
      }

      if (count($this->_country_codes) > 0) {
        $xml_data->addChild('GeoFence')->addChild('Country')->addChild('Allow', $this->AllowedCountries(true));
      }

      if ($this->_ip) {
        $xml_data->addChild('IP', $this->_ip);
      }

      if ($this->_username && strlen($this->_username) > 0) {
        // Since value can be any input, surrounding it with <![CDATA[...]]>
        $xml_data->addChildCDATA('Username', $this->_username);
      }

      if ($this->_external_id && strlen($this->_external_id) > 0) {
        // Since value can be any input, surrounding it with <![CDATA[...]]>
        $xml_data->addChildCDATA('ExternalID', $this->_external_id);
      }

      $xml_data->addChild('GenerationTime', $this->AuthXMLGenerationTime());
      $xml_data->addChild('ExpirationTime', $this->AuthXMLExpirationTime(true, true));

      // UniqueId is set as GUID string but without hyphens and brackets.
      $xml_data->addChild('UniqueId', KeyOS_GUID::newGUID()->toString(false, false, false));

      return $xml_root->asXML();
    }

    /** KyeOSAuthXMLGenerator::signAuthenticationXML() - Signs authentication XML.
     *
     * @param string $xml
     * @return string
     * @throws Exception
     */
    public function signAuthenticationXML($xml) {
      try {
        $resource = fopen($this->_pem, 'r');

        if (!$resource) {
          $this->_throw(__METHOD__ . '(): Unable to open ' . $this->_pem . ' for reading.');
        }
      }
      catch(Exception $e) {
        $this->_throw(__METHOD__ . '(): Unable to open ' . $this->_pem . ' Error: ' . $e->getMessage());
      }


      if (filesize($this->_pem) <= 0) {
        $this->_throw(__METHOD__ . '(): PEM (' . $this->_pem . ') is empty.');
      }

      $certificate_contents = fread($resource, filesize($this->_pem));
      if (!$certificate_contents) {
        $this->_throw(__METHOD__ . '(): Unable to read ' . $this->_pem);
      }

      fclose($resource);


      $xml_root = simplexml_load_string($xml);
      $xml_data = $xml_root->Data;

      # Add element describing public key.
      $info = pathinfo($this->_pem);

      $xml_data->addChild('RSAPubKeyId', $info['filename']);

      # Fetch private key from the certificate.
      $pkeid = openssl_get_privatekey($certificate_contents);

      if (!$pkeid) {
        $this->_throw(__METHOD__ . '(): Unable to get private key from the certificate.');
      }

      # Generate signature based on the private key from the certificate and data we've got
      # from the input file.
      if (! openssl_sign($xml_data->asXML(), $signature, $pkeid)) {
        $this->_throw(__METHOD__ . '(): Unable to sign data with private key.');
      }

      $xml_root->addChild('Signature', base64_encode($signature));

      # Remove private key from the memory.
      openssl_free_key($pkeid);

      return $xml_root->asXML();
    }

    /** KeyOSAuthXMLGenerator::encodeAuthenticationXML() - Encodes authentication data and prepares it for usage in SL player.
     *
     * @param string $xml
     * @return string
     */
    public function encodeAuthenticationXML($xml) {
      return base64_encode($xml);
    }

    /** KeyOSAuthXMLGenerator::_newLicense() - Creates and registers new license
     *
     * @param mixed $license_or_type - KeyOS_License::SIMPLE/ROOT/LEAF or license object.
     * @param mixed $root_key_id - GUID string, KeyOS_GUID or KeyOS_GUID.
     *
     * NOTE: $root_key_id should be passed for LEAF and ROOT license.
     *
     * @return pointer to new license object
     * @throws Exception
     */
    private function _newLicense($license_type, $root_key_id = null, $key_id = null) {
      if ($license_type === KeyOS_License::WIDEVINE_SIMPLE) {
        $license = new KeyOS_WV_SimpleLic();
      }
      else if ($license_type === KeyOS_License::FAIRPLAY_SIMPLE) {
        $license = new KeyOS_FP_SimpleLic();
      }
      else if ($license_type === KeyOS_License::SIMPLE) {
        $license = new KeyOS_PR_SimpleLic($key_id);
      }
      else if ($license_type === KeyOS_License::LEAF) {
        $license = new KeyOS_PR_LeafLic($root_key_id, $key_id);
      }
      else if ($license_type === KeyOS_License::ROOT) {
        $license = new KeyOS_PR_RootLic($root_key_id);
      }
      else {
        $this->_throw(__METHOD__ . '(): Attempt to create license with unknown type \'' . $license_type . '\'.');
      }

      $this->registerLicense($license);

      return $license;
    }

    /** KeyOSAuthXMLGenerator::checkAuthXMLSettings() - Checks AuthXML settings.
     *
     * @return $this
     * @throws Exception
     */
    public function checkAuthXMLSettings() {
      if (! $this->validation()) {
        return $this;
      }

      // Check if authentication XML contains whether domain action or license request settings
      if ($this->_domain_action) {
        if (! $this->AllowedKeyIdList->isEmpty()) {
          $this->_throw(__METHOD__.'(): List of allowed KeyIDs cannot be used if Domain Join/Leave action is set.');
        }
        else if ($this->_external_id) {
          $this->_throw(__METHOD__.'(): ExtenralId cannot be used if Domain Join/Leave action is set.');
        }
      }
      // Check authentication XML contains license settings
      else if ($this->_lic_list->isEmpty()) {
        $this->_throw(__METHOD__.'(): No licenses added/registered.');
      }
      // Check other
      else {
        // Check if AllowedKeyIdList is used for ROOT licenses only.
        // If it contains ROOT licenses only and list of allowed KeyIDs is not empty,
        // then exception should be thrown.
        $fl_AllowedKeyIdList_for_ROOT_License_only = $this->AllowedKeyIdList->hasItems();

        foreach ($this->_lic_list->toArray() as $lic) {
          // Check if AllowedKeyIdList is used for at least one non-ROOT license
          if ($fl_AllowedKeyIdList_for_ROOT_License_only && $lic->getType() != KeyOS_License::ROOT) {
            $fl_AllowedKeyIdList_for_ROOT_License_only = false;
          }

          // Check PlayReady
          if ($lic->isPlayReady()) {
            $pr = $lic->PlayRight();

            // Check OutputProtections
            if ($pr->OPExplicit(KeyOSAuthXML_OPL::DIGITAL_VIDEO_EXPLICIT, 'ABB2C6F1-E663-4625-A945-972D17B231E7') !== null) {
              // If DigitalVideoExplicit is ABB2C6F1-E663-4625-A945-972D17B231E7, then Uncompressed Digital Video should be set to 300+
              if (intval($pr->OPL(KeyOSAuthXML_OPL::UNCOMPRESSED_DIGITAL_VIDEO)) < 300) {
                $this->_throw(__METHOD__ . '(): ' . KeyOSAuthXML_OPL::DIGITAL_VIDEO_EXPLICIT . ' {ABB2C6F1-E663-4625-A945-972D17B231E7} may be specified in a PlayReady License only if the PlayReady License also specifies Uncompressed Digital Video Output Protection Level to a value greater than or equal to 300.');
              }
            }
          }
          // Check Widevine
          else if ($lic->isWidevine()) {
            // Throw exception if Widevine License Policies Set is empty
            if ($lic->Policy()->isEmpty()) {
              $this->_throw(__METHOD__.'(): Widevine License Policies Set cannot be empty.');
            }

            // Throw exception if Widevine ContentKeySpec Set is empty
            if ($lic->ContentKeySpec()->isEmpty()) {
              $this->_throw(__METHOD__.'(): Widevine ContentKeySpec Set cannot be empty.');
            }

            // Throw exception if Widevine ContentKeySpec does not contain TrackType.
            if (! $lic->ContentKeySpec()->TrackType()) {
              $this->_throw(__METHOD__.'():  Widevine ContentKeySpec Set should contain TrackType. It should be one of: SD, HD, AUDIO.');
            }
          }
        }

        // Throw exception if AllowedKeyIdList is used for ROOT licenses only
        if ($fl_AllowedKeyIdList_for_ROOT_License_only) {
          $this->_throw(__METHOD__.'(): Authentication XML with ROOT licenses only should not contain list of allowed KeyIDs.');
        }
      }

      return $this;
    }

    /** KeyOSAuthXMLGenerator::_getPpreparedLicensesList() - Gets prepared list of licenses
     *
     * @return array of KeyOS_UniqueList objects
     * @throws Exception
     */
    private function _getPreparedLicensesList() {
      // Prepare licenses with bound policies and playrights sets

      // Get array of licenses objects
      $licenses_list = $this->_lic_list->toArray();

      // Replace licenses objects with clones in licenses array

      // Also all persistent policies sets (persistent set or license) that
      // do not have policies will be replaced with one common policies set.
      $empty_policies_set_for_persistent_license = null;

      foreach ($licenses_list as $key => $lic) {
        // Cloning
        $licenses_list[$key] = $lic = clone $lic;

        $p = $lic->getPoliciesSet();

        if ($p->isEmpty() && $lic->isPersistent()) {
          if (! $empty_policies_set_for_persistent_license) {
            $empty_policies_set_for_persistent_license = new KeyOS_LicPolicy();
            $empty_policies_set_for_persistent_license->isPersistent(true);
          }

          $lic->bindPoliciesSet($empty_policies_set_for_persistent_license);
        }
      }

      // Create clones of common policies sets if one license bound with
      // the same policies set is persistent and other is non-persistent.
      $new_policies_sets_by_origin_ids = array();

      foreach ($licenses_list as $lic) {
        $p = $lic->getPoliciesSet();

        foreach ($licenses_list as $lic2) {
          $p2 = $lic2->getPoliciesSet();

          if (! $p->isEqualId($p2) || $lic->isPersistent() === $lic2->isPersistent()) {
            continue;
          }

          // Remember Id of origin policies set
          $origin_policies_set_id = $p2->getId()->toString();

          if (array_key_exists($origin_policies_set_id, $new_policies_sets_by_origin_ids)) {
            $p2 = $new_policies_sets_by_origin_ids[$origin_policies_set_id];
          }
          else {
            // Clone policies set and generate new Id for it
            $p2 = clone $p2;
            $p2->regenerateId();

            // Remember pair: Id of origin policies set and new policies set
            $new_policies_sets_by_origin_ids[$origin_policies_set_id] = $p2;
          }

          // Bind license with cloned policies set
          $lic2->bindPoliciesSet($p2);
        }
      }

      return $licenses_list;
    }

    /** KeyOSAuthXMLGenerator::_debugGetFormattedAuthXML() - Gets formatted AuthXML.
     *
     * NOTE: This method can be used for debugging only.
     *
     * @return $this
     * @throws Exception
     */
    public function _debugGetFormattedAuthXML() {
      $dom = new DOMDocument;
      $dom->preserveWhiteSpace = FALSE;
      $dom->loadXML($this->signAuthenticationXML($this->generateAuthenticationXML()));
      $dom->formatOutput = TRUE;

      return $dom->saveXml();
    }

    /** KeyOSAuthXMLGenerator::_debugShowFormattedAuthXML() - Shows formatted AuthXML.
     *
     * NOTE: This method can be used for debugging only.
     *
     * @return $this
     * @throws Exception
     */
    public function _debugShowFormattedAuthXML() {
      echo '<pre style="font-family: verdana; font-size: 10px; text-align: left;">'
           . htmlspecialchars($this->_debugGetFormattedAuthXML())
           . '</pre>';

      return $this;
    }

    #region Deprecated methods
    /** KeyOSAuthXMLGenerator::getAuthXMLGenerationTime() - Gets AuthXML generation date/time which is current time in UTC.
     *
     * @param bool $fl_formatted_date
     * @return string timestamp
     */
    public function getAuthXMLGenerationTime ($fl_formatted_date = true) {
      return $this->AuthXMLGenerationTime($fl_formatted_date);
    }

    /** KeyOSAuthXMLGenerator::setAuthXMLExpirationTime() - Sets expiration time of authentication XML itself in UTC.
     *
     * NOTE: It assumes that date passed is already in UTC or relative.
     *
     * @param mixed $datetime_utc (string time or time in seconds)
     * @return $this
     * @throws Exception
     */
    public function setAuthXMLExpirationTime ($datetime_utc) {
      return $this->AuthXMLExpirationTime($datetime_utc);
    }

    /** KeyOSAuthXMLGenerator::getAuthXMLExpirationTime() - Gets expiration time of authentication XML itself.
     *
     * @param bool $throw_exception_if_empty - If true, throws exception if not set
     * @return string timestamp
     * @throws Exception
     */
    public function getAuthXMLExpirationTime ($fl_exception_if_not_set = false) {
      return $this->AuthXMLExpirationTime(true, $fl_exception_if_not_set);
    }

    /** KeyOSAuthXMLGenerator::setIP() Sets IP.
     *
     * @param string $ip
     * @return $this
     */
    public function setIP($ip = null) {
      $this->_ip = $ip;

      return $this;
    }

    /** AuthXMLGenerator::setExternalId() Sets an external Id.
     *
     * @param string $id
     * @return $this
     * @throws Exception
     */
    public function setExternalId($id) {
      if (strlen($id) > 40) {
        $this->_throw(__METHOD__ . '(): String can\'t be longer than 40 symbols.');
      }

      $this->_external_id = $id;
      return $this;
    }

    /** KeyOSAuthXMLGenerator::addCustomerNodes()
     *
     * @param assoc array of key/value pairs.
     * @return $this
     * @throws Exception
     */
    public function addCustomNodes($key_value_pairs) {
      if (!is_array($key_value_pairs)) {
        $this->_throw(__METHOD__ . '(): Invalid parameter type. Custom fields needs to be presented inside the associated array.');
      }

      foreach ($key_value_pairs as $key => $value) {
        if (! is_string($key) || ! is_string($value)) {
          $this->_throw(__METHOD__.'($key_value_pairs) >> Both key and value should be strings');
        }

        $this->_custom_nodes[$key] = $value;
      }

      return $this;
    }

    /** KeyOSAuthXMLGenerator::getCustomNodes() - Gets custome nodes.
     *
     * @return array of key/value pairs
     */
    public function getCustomNodes() {
      return $this->_custom_nodes;
    }
    #endregion
  }

  class KeyOS_Exception {
    private $_error = null;

    // Specifies whether validation is enabled or disabled.
    private $_fl_validation = true;

    /** KeyOS_Exception::validation()  -  Get/Set bool - whether validation is enabled or disabled
     *
     * NOTE: Allows to disable validation so that new parameters can be added w/o modifications of generator.
     *
     * @param bool IF SET | no params IF GET
     * @return bool
     */
    public function validation($bool = null) {
      if (func_num_args() == 0) {
        return $this->_fl_validation;
      }

      $this->_fl_validation = !!$bool;
      return $this;
    }

    /** KeyOS_Exception::error() - Sets / Gets error message.
     *
     * @param $this IF SET | string $error IF GET
     * @return string
     */
    public function error($error = null) {
      if (func_num_args() == 0) {
        return $this->_error;
      }

      $this->_error = $error;
      return $this;
    }

    /** KeyOS_Exception::_throw() - Throws an exception and sets internal error.
     *
     * @param mixed $error
     * @return void
     * @throws Exception
     */
    protected function _throw($error) {
      throw new Exception($this->error($error)->error());
    }
  }

  class KeyOS_GUID extends KeyOS_Exception {
    const EMPTY_GUID = '00000000-0000-0000-0000-000000000000';
    const EMPTY_GUID_BASE64 = 'AAAAAAAAAAAAAAAAAAAAAA==';

    private $_guid = null; // GUID value is stored here in lower case HEX - w/o "{", "}" and "-" symbols.


    /** KeyOS_GUID::newGUID() - Generates and returns new random GUID.
     *
     * NOTE: This static mehtod just returns "new KeyOS_GUID()".
     *
     * USAGE: KeyOS_GUID::newGUID()->toString().
     *
     * @return KeyOS_GUID
     */
    static public function newGUID () {
      return new KeyOS_GUID();
    }

    /** KeyOS_GUID::isValid() - Checks if argument is valid KeyOS_GUID object,
     *                         GUID string or GUID Base64 string.
     *
     * @param mixed $guid
     *
     * @return bool
     */
    static public function isValid($guid) {
      return self::isValidGUID($guid) || self::isValidGUIDString($guid) || self::isValidGUIDBase64String($guid);
    }

    /** KeyOS_GUID::isValidGUID() - Checks if KeyOS_GUID object is valid.
     *
     * @param mixed $guid
     *
     * @return bool
     */
    static public function isValidGUID($guid) {
      return is_object($guid) && $guid instanceof KeyOS_GUID;
    }

    /** KeyOS_GUID::isValidGUIDString() - Checks if GUID string is valid.
     *
     * @param mixed $guid
     * @param bool $check_brackets - False is default. If true, then guid should be in brackets - '{' and '}'.
     * @param bool $check_hyphens - True is default. If true, then guid should be with hyphens/delimeters - '-'.
     *
     * @return bool
     */
    static public function isValidGUIDString($guid, $check_brackets = false, $check_hyphens = true) {
      if (! is_string($guid)) {
        return false;
      }

      $pattern = array('[A-F\d]{8}', '[A-F\d]{4}', '[A-F\d]{4}', '[A-F\d]{4}', '[A-F\d]{12}');
      $pattern = join($check_hyphens ? '-' : '-?', $pattern);
      $pattern = ($check_brackets ? '\{' : '\{?') . $pattern . ($check_brackets ? '\}' : '\}?');

      return preg_match('/^' . $pattern . '$/i', $guid) === 1;
    }

    /** KeyOS_GUID::isValidGUIDBase64String() - Checks if it is valid base64-encoded GUID string.
     *
     * @param mixed $base64_guid
     *
     * @return bool
     */
    static public function isValidGUIDBase64String($base64_guid) {
      return is_string($base64_guid) && strlen($base64_guid) == 24 && base64_decode($base64_guid) !== false;
    }


    /** KeyOS_GUID::__construct() - Sets or generates new GUID.
     *
     * @param mixed $guid - Optional. Simple or Base64-encoded GUID value.
     */
    function __construct($guid = null) {
      if (isset($guid)) {
        $this->set($guid);
      }
      else {
        $this->regenerate(); // Here it generates and sets new random GUID.
      }
    }

    /** KeyOS_GUID::isEqual() - Compares passed GUID with itself.
     *
     * @param mixed $guid - string, Base64-encoded string or KeyOS_GUID.
     *
     * @return bool
     */
    public function isEqual($guid) {
      return $this->_guid === (new KeyOS_GUID($guid))->toString(false, false, false);
    }

    /** KeyOS_GUID::toString() - Gets GUID string.
     *
     * @param bool $upper_case - False is default. If true, returns value in upper case.
     * @param bool $with_brackets - False is default. If true, returns value in brackets '{' and '}'.
     * @param bool $with_hyphens - True is default. If false, returns values without hyphens.
     *
     * @return string
     */
    public function toString($upper_case = false, $with_brackets = false, $with_hyphens = true) {
      $guid = $upper_case ? strtoupper($this->_guid) : $this->_guid;

      if ($with_hyphens) {
        $guid = substr($guid, 0, 8)
              . '-' . substr($guid, 8, 4)
              . '-' . substr($guid, 12, 4)
              . '-' . substr($guid, 16, 4)
              . '-' . substr($guid, 20,12);
      }

      return ($with_brackets ? '{' : '') . $guid . ($with_brackets ? '}' : '');
    }

    /** KeyOS_GUID::toBase64String() - Gets Base64-encoded GUID string.
     *
     * @return string - Base64-encoded GUID string
     */
    public function toBase64String() {
      $base64_guid = '';
      $guid_parts = explode('-', $this->toString());

      foreach ($guid_parts as $k => $part) {
        if ($k < 3) {
          $part = join('', array_reverse(str_split($part, 2)));
        }

        $base64_guid .= pack('H*', $part);
      }

      return base64_encode($base64_guid);
    }

    /** KeyOS_GUID::regenerate() - Re-generates and sets new random GUID.
     *
     * @return $this
     */
    public function regenerate() {
      if (function_exists('com_create_guid')) {
        $guid = com_create_guid();
      }
      else {
        mt_srand((double)microtime() * 10000); // optional for php 4.2.0 and up.
        $guid = strtolower(md5(uniqid(rand(), true)));
      }

      return $this->_setGUIDString($guid);
    }

    /** KeyOS_GUID::set() - Sets simple or Base64-encoded GUID.
     *
     * @param mixed $guid - Value can be KeyOS_GUID or string with/without brackets '{' and '}', also it can contain hyphens or not.
     * @return $this
     * @throws Exception
     */
    public function set($guid) {
      if (self::isValidGUID($guid)) {
        return $this->_setGUID($guid);
      }
      else if (self::isValidGUIDString($guid, false, false)) {
        return $this->_setGUIDString($guid);
      }
      else if (self::isValidGUIDBase64String($guid)) {
        return $this->_setGUIDBase64String($guid);
      }

      $this->_throw(__METHOD__ . '(): \'' . $guid . '\' is invalid simple or Base64-encoded GUID.');
    }

    /** KeyOS_GUID::_setGUID() - Sets KeyOS_GUID object without validation.
     *
     * @param KeyOS_GUID $guid
     * @return $this
     */
    private function _setGUID(KeyOS_GUID $guid) {
      $this->_guid = $guid->toString(false, false, false);

      return $this;
    }

    /** KeyOS_GUID::_setGUIDString() - Sets GUID string without validation.
     *
     * @param string $guid
     * @return $this
     */
    private function _setGUIDString($guid) {
      $this->_guid = strtolower(preg_replace('/[{}-]/', '', $guid));

      return $this;
    }

    /** KeyOS_GUID::_setGUIDBase64String() - Sets Base64-encoded GUID string.
     *
     * @param string $base64_guid - Base64-encoded GUID.
     * @return $this
     */
    private function _setGUIDBase64String($base64_guid) {
      $guid_bin = base64_decode($base64_guid);

      return $this->_setGUIDString(
        bin2hex(strrev(substr($guid_bin, 0, 4)))
        . bin2hex(strrev(substr($guid_bin, 4, 2)))
        . bin2hex(strrev(substr($guid_bin, 6, 2)))
        . bin2hex(substr($guid_bin, 8, 2))
        . bin2hex(substr($guid_bin, 10, 6))
      );
    }
  }


  /*
   * KeyOS_Id class
   *
   */
  class KeyOS_Id extends KeyOS_Exception {
    private $_id = null; // KeyOS_GUID

    public function __construct ($id = null) {
      if (isset($id)) {
        $this->setId($id);
      }
      else {
        $this->regenerateId();
      }
    }

    /** KeyOS_Id::setId() - Sets Id of this object.
     *
     * @return $this
     */
    public function setId ($id) {
      $this->_id = new KeyOS_GUID($id instanceof KeyOS_Id ? $id->getId() : $id);

      return $this;
    }

    /** KeyOS_Id::regenerateId() - Generates and sets new Id of this object.
     *
     * @return $this
     */
    public function regenerateId () {
      $this->_id = new KeyOS_GUID();

      return $this;
    }

    /** KeyOS_Id::getId() - Returns Id of this object.
     *
     * @return KeyOS_GUID
     */
    public function getId () {
      return $this->_id;
    }

    /** KeyOS_Id::isEqualId() - Compares if Id of this object is equal to Id of passed object.
     *
     * @param mixed $id - GUID string, KeyOS_GUID or instance of KeyOS_Id.
     * @return bool
     */
    public function isEqualId($id) {
      return $this->_id->isEqual($id instanceof KeyOS_Id ? $id->getId() : $id);
    }
  }

  /*
   * KeyOS_UniqueList class
   *
   * Instance of this class will contain list of items whose IDs (GUIDs) are
   * unique within the list.
   *
   * NOTE: If there is added item with ID which is already exists in the list,
   *       then old item will be replaced with new one.
   *
   */
  class KeyOS_UniqueList extends KeyOS_Exception {
    private $_unique_list = array();
    private $_is_current = true;
    private $_count = 0;


    public function __construct() {
      if (func_num_args() > 0) {
        $args = func_get_args();
        $this->_add($args);
      }
    }


    /*
     * KeyOS_UniqueList::add() - Adds instance of KeyOS_Id, KeyOS_GUID,
     *                           array of them or KeyOS_UniqueList.
     *
     * NOTE: It does compare only Ids of KeyOS_Id/KeyOS_GUID object, not whole objects.
     *
     * return $this
     */
    public function add ($args) {
      $args = func_get_args();
      return $this->_add($args);
    }

    private function _add ($arg) {
      if ($arg instanceof KeyOS_UniqueList) {
        $arg = $arg->toArray();
      }

      if (is_array($arg)) {
        foreach ($arg as $item) {
          $this->_add($item);
        }
      }
      else {
        if ($arg instanceof KeyOS_GUID || is_string($arg)) {
          $arg = new KeyOS_Id($arg);
        }

        if ($arg instanceof KeyOS_Id) {
          $this->_is_current = false;
          $this->_unique_list[$arg->getId()->toString()] = $arg;
        }
        else {
          $this->_throw(__METHOD__.'(): Unallowed type >> It can only take any number (1+) of arguments that should be instances of KeyOS_Id, KeyOS_UniqueList, arrays or arrays of any of them >> [' .print_r($arg, true) . '] ['. $arg . ']');
        }
      }

      return $this;
    }

    /*
     * KeyOS_UniqueList::clear() - Clears list.
     *
     * return $this
     */
    public function clear () {
      $this->_is_current = false;
      $this->_unique_list = array();

      return $this;
    }

    /*
     * KeyOS_UniqueList::toArray() - Returns array of objects.
     *
     * NOTE: Since each KeyOS_Id object has possibility to change/regenerate its id,
     *       then it means that theorically list can have KeyOS_Id objects with the same Id.
     *       That is why toArray() method makes checking and returns only unique objects.
     *
     * NOTE: If get list has two objects with the same Id, it returns last added one.
     *
     * @return array of KeyOS_Id instances
     */
    public function toArray () {
      return $this->_current()->_unique_list;
    }

    /*
     * KeyOS_UniqueList::getById() - Gets pointer to object by passed Id.
     *
     * @param mixed $id - GUID string or KeyOS_GUID
     *
     * @return pointer or null
     */
    public function getById ($id) {
      foreach ($this->_unique_list as $unique_data) {
        if ($unique_data->isEqualId($id)) {
          return $unique_data;
        }
      }

      return null;
    }

    /*
     * KeyOS_UniqueList::has() - Checks if object with passed Id is already added.
     *
     * @param mixed $id - GUID string, KeyOS_GUID, KeyOS_Id
     *
     * @return pointer or null
     */
    public function has ($data_or_id) {
      return $this->getById($data_or_id) !== null;
    }

    /*
     * KeyOS_UniqueList::hasItems() - Checks if list is NOT empty.
     *
     * @return bool
     */
    public function hasItems() {
      return $this->_current()->_count > 0;
    }

    /*
     * KeyOS_UniqueList::isEmpty() - Checks if list is empty.
     *
     * @return bool
     */
    public function isEmpty() {
      return !$this->hasItems();
    }

    /*
     * KeyOS_UniqueList::getCount() - Gets count of list items.
     *
     * NOTE: It excludes duplicated objects comparing by its IDs.
     *
     * @return integer - count of list items
     */
    public function getCount() {
      return $this->_current()->_count;
    }

    /*
     * KeyOS_UniqueList::getIdFrom() - Fetches GUID Id from passed object or GUID.
     *
     * @param mixed $id - GUID string, KeyOS_GUID, KeyOS_Id
     *
     * @return KeyOS_GUID
     */
    public function getIdFrom ($unique_data_or_id) {
      return $unique_data_or_id instanceof KeyOS_Id
             ? $unique_data_or_id->getId() : KeyOS_GUID($unique_data_or_id);
    }

    /*
     *
     */
    private function _current () {
      if (! $this->_is_current) {
        $unique_list = array();

        foreach ($this->_unique_list as $unique_data) {
          $unique_list[$unique_data->getId()->toString()] = $unique_data;
        }

        $this->_unique_list = $unique_list;
        $this->_count = count($this->_unique_list);
        $this->_is_current = true;
      }

      return $this;
    }
  }

  /*
   * KeyOS_PlayEnablersList
   *
   * Based on KeyOS_UniqueList but it also checks if PlayEnablers are valid
   */
  class KeyOS_PlayEnablersList extends KeyOS_UniqueList {
    public function add ($args) {
      $_args = func_get_args();
      $args_list = new KeyOS_UniqueList($this);
      $args_list->add($_args);

      if (! KeyOSAuthXML_PlayEnablers::validateList(array_keys($args_list->toArray()), $errmsg)) {
        $this->_throw(__METHOD__ . '(): ' . $errmsg);
      }

      return parent::add($args_list);
    }
  }

  /** KeyOS_LicPolicy
   *
   */
  class KeyOS_LicPolicy extends KeyOS_Id {
    const BEGIN_DATE = 'BeginDate';
    const EXPIRATION_DATE = 'ExpirationDate';
    const EXPIRATION_AFTER_FIRST_PLAY = 'ExpirationAfterFirstPlay';
    const MINIMUM_SECURITY_LEVEL = 'MinimumSecurityLevel';

    private $_now_utc = null;
    private $_is_persistent = false;
    private $_list = array();


    public function __construct($id = null) {
      parent::__construct($id);

      $this->_now_utc = KeyOS_Date::UTC2UnixTimeStamp();
    }


    /** KeyOS_LicPolicy::isPersistent() - Gets or sets persistent status of policies set.
     *
     * @param status $bool - If value passed, it sets bool state.
     * @return bool - Returns current state
     * @throws Exception
     */
    public function isPersistent($bool = null) {
      if ($bool === true || $bool === false) {
        $this->_is_persistent = $bool;
      }
      else if ($bool !== null) {
        $this->_throw(__METHOD__ . '(): Can take bool parameter only.');
      }

      return $this->_is_persistent || $this->hasPersistentPolicies();
    }


    /** KeyOS_LicPolicy::BeginDate() - Sets/Gets Begin date/time for license in UTC.
     *
     * NOTEs: - It assumes that passed date/time is already in GMT/UTC or relative.
     *
     * Usage:
     *    - SET:
     *      - BeginDate('2020-12-31 23:23:59')  -  set date/time to 2020-12-31 23:23:59 (should be UTC)
     *      - BeginDate(1609478639)  - sets same as 2020-12-31 23:23:59 but in seconds (UnixTime) (should be UTC)
     *      - BeginDate('+2 minutes')  -  set date/time to now + 2 minutes (will be automatically converted to UTC)
     *    - GET:
     *      - BeginDate()  -  returns date/time string like 2020-12-31 23:23:59.000 (UTC)
     *      - BeginDate(false)  -  returns date/time in seconds (UnixTime) (UTC)
     *
     * @param $datetime_utc (string time or time in seconds) | null/bool IF GET
     * @return $this IF SET | string date IF GET
     * @throws Exception
     */
    public function BeginDate ($datetime_utc = null) {
      if ($datetime_utc === null || is_bool($datetime_utc)) {
        $fl_formatted_date = $datetime_utc !== false;
        return key_exists(self::BEGIN_DATE, $this->_list) ? ($fl_formatted_date ? KeyOS_Date::dtf($this->_list[self::BEGIN_DATE]) : $this->_list[self::BEGIN_DATE]) : null;
      }

      $datetime_utc = KeyOS_Date::UTC2UnixTimeStamp($datetime_utc);
      $expiration_date = $this->ExpirationDate(false);

      if ($this->validation() && ($expiration_date && $expiration_date <= $datetime_utc)) {
        $this->_throw(__METHOD__ . '(): BeginDate (' . $this->_dts($datetime_utc) . ') should be less than ExpirationDate (' . KeyOS_Date::dtf($expiration_date) . ').');
      }

      $this->_list[self::BEGIN_DATE] = $datetime_utc;
      return $this;
    }

    /** KeyOS_LicPolicy::ExpirationDate() - Sets/Gets expiration date/time for license in UTC.
     *
     * NOTEs: - It assumes that passed date/time is already in GMT/UTC or relative.
     *
     * Usage:
     *    - SET:
     *      - ExpirationDate('2020-12-31 23:23:59')  -  set date/time to 2020-12-31 23:23:59 (should be UTC)
     *      - ExpirationDate(1609478639)  - sets same as 2020-12-31 23:23:59 but in seconds (UnixTime) (should be UTC)
     *      - ExpirationDate('+2 minutes')  -  set date/time to now + 2 minutes (will be automatically converted to UTC)
     *    - GET:
     *      - ExpirationDate()  -  returns date/time string like 2020-12-31 23:23:59.000 (UTC)
     *      - ExpirationDate(false)  -  returns date/time in seconds (UnixTime) (UTC)
     *
     * @param $datetime_utc (string time or time in seconds) | null/bool IF GET
     * @return $this IF SET | string date IF GET
     * @throws Exception
     */
    public function ExpirationDate ($datetime_utc = null) {
      if ($datetime_utc === null || is_bool($datetime_utc)) {
        $fl_formatted_date = $datetime_utc !== false;
        return key_exists(self::EXPIRATION_DATE, $this->_list) ? ($fl_formatted_date ? KeyOS_Date::dtf($this->_list[self::EXPIRATION_DATE]) : $this->_list[self::EXPIRATION_DATE]) : null;
      }

      $datetime_utc = KeyOS_Date::UTC2UnixTimeStamp($datetime_utc);
      $begin_date = $this->BeginDate(false);

      if ($this->validation()) {
        if ($datetime_utc < $this->_now_utc) {
          $this->_throw(__METHOD__ . '(): ExpirationDate (' . KeyOS_Date::dtf($datetime_utc) . ') is already expired.');
        }

        if ($begin_date && $datetime_utc <= $begin_date) {
          $this->_throw(__METHOD__ . '(): ExpirationDate (' . KeyOS_Date::dtf($datetime_utc) . ') should be greater than BeginDate (' . KeyOS_Date::dtf($begin_date) . '\'');
        }
      }

      $this->_list[self::EXPIRATION_DATE] = $datetime_utc;
      return $this;
    }

    /** KeyOS_LicPolicy::ExpirationAfterFirstPlay() - Sets/Gets Expiration After First Play in seconds.
     *
     * @param integer $seconds IF SET | null - no params IF GET
     * @return $this IF SET | integer $seconds IF GET
     * @throws Exception
     */
    public function ExpirationAfterFirstPlay ($seconds = null) {
      if (func_num_args() == 0) {
        return key_exists(self::EXPIRATION_AFTER_FIRST_PLAY, $this->_list) ? $this->_list[self::EXPIRATION_AFTER_FIRST_PLAY] : null;
      }

      if (preg_match('/^\d+$/', $seconds) !== 1) {
        $this->_throw(__METHOD__ . '(): Not valid second\'s number: ' . $seconds);
      }

      if ($this->validation() && $seconds < 1) {
        $this->_throw(__METHOD__ . '(): Second\'s number is less than one: ' . $seconds);
      }

      $this->_list[self::EXPIRATION_AFTER_FIRST_PLAY] = $seconds;
      return $this;
    }

    /** KeyOS_LicPolicy::MinimumSecurityLevel() - Sets/Gets Minimum Security Level.
     *
     * @param integer $msl - 150, 2000, 3000 - IF SET | null - no params IF GET
     * @return $this IF SET | integer $level IF GET
     * @throws Exception
     */
    public function MinimumSecurityLevel ($level = null) {
      if (func_num_args() == 0) {
        return key_exists(self::MINIMUM_SECURITY_LEVEL, $this->_list) ? $this->_list[self::MINIMUM_SECURITY_LEVEL] : null;
      }

      $allowed = array(150, 2000, 3000);

      if ($this->validation() && ! in_array($level, $allowed)) {
        $this->_throw(__METHOD__ . '(): Unknown: ' . $level . '. Allowed: ' . join(', ', $allowed));
      }

      $this->_list[self::MINIMUM_SECURITY_LEVEL] = $level;
      return $this;
    }

    /** KeyOS_LicPolicy::getPolicies() - Gets array of license policies
     *
     * @return assoc array of key/value pairs
     */
    public function getPolicies() {
      $list = $this->_list;

      foreach ($list as $key => $value) {
        if (in_array($key, array(self::BEGIN_DATE, self::EXPIRATION_DATE))) {
          $list[$key] = KeyOS_Date::dtf($value);
        }
      }

      return $list;
    }

    
    /* KeyOS_LicPolicy::isEmpty() - Checks if Policies Set object is empty and not persistent.
     *
     * NOTE: isEmpty() == !hasPolicies() && !isPersistent().
     *
     * @return bool
     */
    public function isEmpty() {
      return !$this->hasPolicies() && !$this->isPersistent();
    }

    /** KeyOS_LicPolicy::hasPolicies() - Checks if it contains license policies.
     *
     * NOTE: hasPolicies() === !isEmpty().
     *
     * @return bool
     */
    public function hasPolicies() {
      return count($this->_list) > 0;
    }

    /** KeyOS_LicPolicy::hasPersistentPolicies() - Checks if it contains any of persistent license policies.
     *
     * NOTE: Persistent policies are ExpirationAfterFirstPlay, BeginDate and ExpirationDate.
     *
     * @return bool
     */
    public function hasPersistentPolicies() {
      foreach ($this->_list as $key => $value) {
        if (in_array($key, array(self::BEGIN_DATE, self::EXPIRATION_DATE, self::EXPIRATION_AFTER_FIRST_PLAY))) {
          return true;
        }
      }

      return false;
    }

    #region Deprecated methods
    /** KeyOS_LicPolicy::setBeginDate() - Sets Begin date/time for license in UTC.
     *
     * NOTE: It assumes that passed date/time is already in GMT/UTC or relative.
     *
     * @param mixed $datetime_utc (string time or time in seconds)
     * @return $this
     * @throws Exception
     */
    public function setBeginDate ($datetime_utc) {
      return $this->BeginDate($datetime_utc);
    }

    /* KeyOS_LicPolicy::getBeginDate() - Gets BeginDate (UTC)
     *
     * @param bool $formatted_date - If true (default), then it converts unix timestamp into formatted date.
     * @return unix or string timestamp - UTC
     */
    public function getBeginDate ($fl_formatted_date = true) {
      return $this->BeginDate(!! $fl_formatted_date);
    }

    /** KeyOS_LicPolicy::setExpirationDate() - Sets expiration date/time for license in UTC.
     *
     * NOTE: It assumes that passed date/time is already in UTC.
     *
     * @param mixed $datetime_utc (string time or time in seconds)
     * @return $this
     * @throws Exception
     */
    public function setExpirationDate ($datetime_utc) {
      return $this->ExpirationDate($datetime_utc);
    }

    /* KeyOS_LicPolicy::getExpirationDate() - Gets ExpirationDate (UTC).
     *
     * @param bool $formatted_date - If true (default), then it converts unix timestamp into formatted date.
     * @return unix or string timestamp - UTC
     */
    public function getExpirationDate ($fl_formatted_date = true) {
      return $this->ExpirationDate(!! $fl_formatted_date);
    }

    /** KeyOS_LicPolicy::setExpirationAfterFirstPlay() - Sets Expiration After First Play in seconds.
     *
     * @param integer $seconds
     * @return $this
     * @throws Exception
     */
    public function setExpirationAfterFirstPlay ($seconds) {
      return $this->ExpirationAfterFirstPlay($seconds);
    }

    /* KeyOS_LicPolicy::getExpirationAfterFirstPlay() - Gets Expiration After First Play in seconds.
     *
     * @return integer seconds
     */
    public function getExpirationAfterFirstPlay () {
      return $this->ExpirationAfterFirstPlay();
    }

    /** KeyOS_LicPolicy::setMinimumSecurityLevel() - Sets Minimum Security Level.
     *
     * @param integer $msl - 150, 2000, 3000
     * @return $this
     * @throws Exception
     */
    public function setMinimumSecurityLevel ($msl) {
      return $this->MinimumSecurityLevel($msl);
    }

    /* KeyOS_LicPolicy::getMinimumSecurityLevel() - Gets Minimum Security Level.
     *
     * @return integer  - 150, 2000, 3000
     */
    public function getMinimumSecurityLevel () {
      return $this->MinimumSecurityLevel();
    }
    #endregion
  }


  /*
   * KeyOS_Pairs
   */
  abstract class KeyOS_Pairs extends KeyOS_Id {
    private $_pairs = array();

    /** KeyOS_Pairs::isEmpty() - Checks if empty
     *
     * @return bool
     */
    final public function isEmpty() {
      return ! $this->isNotEmpty();
    }

    /** KeyOS_Pairs::isNotEmpty() - Checks if NOT empty
     *
     * @return bool
     */
    public function isNotEmpty() {
      return count($this->Pairs());
    }

    /** KeyOS_Pairs::Pairs() - Gets assoc array of key/value pairs
     *
     * @return assoc array of key/value pairs
     */
    public function Pairs() {
      return $this->_pairs;
    }

    /** KeyOS_Pairs::PairsNotNulls() - Gets assoc array of key/value pairs with non-NULLs
     *
     * @return assoc array of key/value pairs with non-NULLs
     */
    public function PairsNotNulls() {
      // This does not work in some PHP versions
      //return array_filter($this->Pairs(), 'isset');

      // Anonymous functions can be used from PHP 5.3, so we use "is_not_null()" function that is declared in this file.
      return array_filter($this->Pairs(), 'is_not_null');
    }

    /** KeyOS_Pairs::has() - checks if key exists
     *
     * @param string $key
     * @return bool
     */
    public function has($key) {
      return key_exists($key, $this->_pairs);
    }

    /** KeyOS_Pairs::get() - gets item value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed value
     */
    public function get($key, $default = null) {
      return $this->has($key) ? $this->_pairs[$key] : $default;
    }


    /** KeyOS_Pairs::set() - sets key/value into list
     *
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    protected function set($key, $value) {
      $this->_pairs[$key] = $value;
      return $this;
    }

    /** KeyOS_Pairs::setBool() - sets key / bool value into list
     *
     * @param string $key
     * @param bool $bool_value
     * @param string $errmsg
     *
     * @return $this
     * @throws Exception
     */
    public function setBool($key, $bool_value, $errmsg = 'setBool(): can take bool parameter only.') {
      if (! is_bool($bool_value)) {
        $this->_throw($errmsg);
      }

      return $this->set($key, $bool_value);
    }
  }


  class KeyOS_ContentKey extends KeyOS_Id {
    // NOTE: All values can be set in KeyOS_GUID, GUID-string or GUID-string-base64

    private $_aes_key_id = null;
    private $_aes_key_iv = null;
    private $_aes_data = null;

    function __construct($key_id, $aes_key_id, $aes_key_iv, $aes_data) {
      $this->setId($key_id); // Id (KeyOS_Id->id) is used to store KeyId
      $this->setAesKeyId($aes_key_id);
      $this->setAesKeyIV($aes_key_iv);
      $this->setAesData($aes_data);
    }

    public function setAesKeyId($aes_key_id) {
      $this->_aes_key_id = new KeyOS_GUID($aes_key_id);

      return $this;
    }

    public function getAesKeyId() {
      return $this->_aes_key_id;
    }

    public function setAesKeyIV($aes_key_iv) {
      $this->_aes_key_iv = new KeyOS_GUID($aes_key_iv);

      return $this;
    }

    public function getAesKeyIV() {
      return $this->_aes_key_iv;
    }

    public function setAesData($aes_data) {
      $this->_aes_data = new KeyOS_GUID($aes_data);

      return $this;
    }

    public function getAesData() {
      return $this->_aes_data;
    }

    /** KeyOS_ContentKey::generateXML() - Generates XML element.
     *
     * @return XML-element
     */
    public function generateXML() {
      $xml = new KeyOS_SimpleXML('<ContentKey />');

      // Put KeyID and AES KeyID as GUID-string into AuthXML-Data->ContentKey
      $xml->addChild('KeyId', $this->getId()->toString());
      $xml->addChild('AesKeyId', $this->getAesKeyId()->toString());

      // Put AES Key IV and AES DATA as base64-string into AuthXML-Data->ContentKey
      $xml->addChild('AesKeyIV', $this->getAesKeyIV()->toBase64String());
      $xml->addChild('AesData', $this->getAesData()->toBase64String());

      return $xml;
    }
  }


  /** Class KeyOS_LicPolicy_Abstract
   */
  abstract class KeyOS_LicPolicy_Abstract extends KeyOS_Pairs {
    private $_list_persistent = array();

    /** Abstract method
     * KeyOS_LicPolicy_Abstract::generateXML() - Generates XML element based on settings.
     *
     * @return XML-element
     */
    abstract public function generateXML();


    /** KeyOS_LicPolicy_Abstract::isPersistent() - Gets persistent status of policies set.
     *
     * @return bool
     */
    public function isPersistent() {
      return $this->hasPersistentPolicies();
    }

    /** KeyOS_LicPolicy_Abstract::hasPersistentPolicies() - Checks if it contains any persistent license policy.
     *
     * @return bool
     */
    public function hasPersistentPolicies() {
      foreach ($this->_list_persistent as $policy_name => $v) {
        if ($this->has($policy_name)) {
          return true;
        }
      }

      return false;
    }

    /** KeyOS_LicPolicy_Abstract::PersistentPolicies() - Gets array of persistent license policies
     *
     * @return array of persistent policies names
     */
    public function PersistentPolicies() {
      $pers = array();
      foreach ($this->_list_persistent as $policy_name => $v) {
        if ($this->has($policy_name)) {
          $pers[$policy_name] = $this->get($policy_name);
        }
      }

      return $pers;
    }

    /** KeyOS_LicPolicy_Abstract::registerPersistentPolicyNames() - puts a policy name into list of persistent ones
     *
     * @param $persistent_policy_name
     * @return $this
     */
    protected function registerPersistentPolicyNames($persistent_policy_name) {
      $args = func_get_args();

      foreach ($args as $policy_name) {
        $this->_list_persistent[$policy_name] = '';
      }

      return $this;
    }
  }


  /*
   * KeyOS_WV_LicPolicy
   */
  class KeyOS_WV_LicPolicy extends KeyOS_LicPolicy_Abstract {
    const FL_CAN_PLAY = 'fl_CanPlay';
    const FL_CAN_PERSIST = 'fl_CanPersist';
    const PLAYBACK_DURATION = 'PlaybackDuration';
    const LICENSE_DURATION = 'LicenseDuration';
    const ExplicitlyAllowedVMPValues = 'ExplicitlyAllowedVMPValues';
    const VMP_PlatformUnverified = 'PLATFORM_UNVERIFIED';
    const VMP_PlatformTampered = 'PLATFORM_TAMPERED';
    const VMP_PlatformSoftwareVerified = 'PLATFORM_SOFTWARE_VERIFIED';
    const VMP_PlatformHardwareVerified = 'PLATFORM_HARDWARE_VERIFIED';
    const VMP_PlatformNoVerification = 'PLATFORM_NO_VERIFICATION';
    const VMP_PlatformSecureStorageSoftwareVerified = 'PLATFORM_SECURE_STORAGE_SOFTWARE_VERIFIED';


    private $_list_vmp_values = array();

    public function __construct($id = null) {
      parent::__construct($id);

      // It should contain names of persistent policies only, not flags like self::FL_CAN_PERSIST
      $this->registerPersistentPolicyNames(self::LICENSE_DURATION);
      
      $this->registerVMPRulesNames(
        self::VMP_PlatformUnverified
        , self::VMP_PlatformTampered
        , self::VMP_PlatformSoftwareVerified
        , self::VMP_PlatformHardwareVerified
        , self::VMP_PlatformNoVerification
        , self::VMP_PlatformSecureStorageSoftwareVerified
      );
      
      $this->CanPlay(true);
      $this->CanPersist(false);
    }

    /** KeyOS_WV_LicPolicy::registerVMPRulesNames() - registers possible names/keys of VMP rules
     *
     * @param string[, string, ...]
     * @return $this
     */
    private function registerVMPRulesNames() {
      $args = func_get_args();

      foreach ($args as $item) {
        $this->_list_vmp_values[$item] = false;
      }

      return $this;
    }

    /** KeyOS_WV_LicPolicy::AllowedVMPRules() - adds or gets VMP rules that should be explicitly allowed.
     *
     * @param mixed - array of strings or string[, string, string ...]
     * @return $this IF SET or list of allowed VMP Rules IF GET
     * @throws Exception
     */
    public function AllowedVMPRules () {
      if (func_num_args() == 0) {
        return array_keys(array_filter($this->_list_vmp_values));
      }
      
      $args = func_get_args();

      foreach ($args as $item) {
        // Allows to pass array of strings, not only strings as separated arguments.
        if (is_array($item) && count($item) > 0) {
          foreach ($item as $i) {
            $this->AllowedVMPRules($i);
          }
        }
        else {
          // Checks if VMP rule is valid
          if ($this->validation() && (! is_string($item) || empty($item) || ! key_exists($item, $this->_list_vmp_values))) {
            $this->_throw(__METHOD__ . '(): Unknown: [' . (string)$item . ']. Allowed: ' . join(', ', array_keys($this->_list_vmp_values)));
          }

          $this->_list_vmp_values[$item] = true;
        }
      }

      return $this;
    }
    /** @deprecated Please use AllowedVMPRules() instead which is consistent with other methods. */
    public function allowVMPRules() {
      return $this->AllowedVMPRules(func_get_args());
    }

    /** KeyOS_WV_LicPolicy::CanPersist() - Sets or gets persistency status.
     *
     * @param status $bool (if SET)
     * @return $this (if SET) | bool if GET
     * @throws Exception
     */
    public function CanPersist($bool = null) {
      if (func_num_args() == 0) {
        return $this->get(self::FL_CAN_PERSIST, false);
      }

      return $this->setBool(self::FL_CAN_PERSIST, $bool, __METHOD__ . '(): can take bool parameter only.');
    }

    /** KeyOS_WV_LicPolicy::CanPlay() - Sets or gets bool status.
     *
     * @param status $bool (if SET)
     * @return $this (if SET) or bool (if GET)
     * @throws Exception
     */
    public function CanPlay($bool = null) {
      if (func_num_args() == 0) {
        return $this->get(self::FL_CAN_PERSIST, false);
      }

      return $this->setBool(self::FL_CAN_PLAY, $bool, __METHOD__ . '(): can take bool parameter only.');
    }

    /* KeyOS_WV_LicPolicy::isPersistent() - derived from KeyOS_LicPolicy_Abstract.
     *
     * Returns TRUE if CanPersist is set to TRUE or some persistent policies are set.
     *
     * @return bool
     */
    public function isPersistent () {
      return $this->CanPersist() || parent::isPersistent();
    }

    /** KeyOS_WV_LicPolicy::LicenseDuration() - Sets or gets duration in seconds.
     *
     * @param integer $seconds (if SET)
     * @return $this (if SET) or seconds (if GET)
     * @throws Exception
     */
    public function LicenseDuration($seconds = null) {
      if (func_num_args() == 0) {
        return $this->get(self::LICENSE_DURATION);
      }

      if (preg_match('/^\d+$/', $seconds) !== 1) {
        $this->_throw(__METHOD__ . '(): Not valid second\'s number: ' . $seconds);
      }

      return $this->set(self::LICENSE_DURATION, $seconds);
    }

    /** KeyOS_WV_LicPolicy::PlaybackDuration() - Sets or gets Playback Duration in seconds.
     *
     * @param integer $seconds (if SET)
     * @return $this (if SET) or seconds (if GET)
     * @throws Exception
     */
    public function PlaybackDuration($seconds = null) {
      if (func_num_args() == 0) {
        return $this->get(self::PLAYBACK_DURATION);
      }

      if (preg_match('/^\d+$/', $seconds) !== 1) {
        $this->_throw(__METHOD__ . '(): Not valid second\'s number: ' . $seconds);
      }

      if ($this->validation() && $seconds < 1) {
        $this->_throw(__METHOD__ . '(): Second\'s number is less than one: ' . $seconds);
      }

      return $this->set(self::PLAYBACK_DURATION, $seconds);
    }

    /** KeyOS_WV_LicPolicy::generateXML() - Generates XML element based on policies.
     *
     * @return XML-element
     */
    public function generateXML() {
      $xml = new KeyOS_SimpleXML('<WidevinePolicy/>');

      // KeyOS_WV_LicPolicy::isPersistent() returns TRUE if CanPersist is set to TRUE or there are set any persistent policy.
      $xml->addAttribute($this::FL_CAN_PERSIST, $this->isPersistent() ? 'true' : 'false');

      foreach ($this->PairsNotNulls() as $policy => $value) {
        if ($policy == self::FL_CAN_PERSIST) {
          // Skip this, since "fl_CanPersist" attribute is set by result of isPersistent(), not by CanPersist() method.
          continue;
        }

        if (in_array($policy, array($this::FL_CAN_PLAY))) {
          $xml->addAttribute($policy, $value ? 'true' : 'false');
          continue;
        }

        $xml->addChild($policy, $value);
      }


      // Get only VMP rules (array keys) where values are not FALSE.
      $allowed_vmp_rules = $this->AllowedVMPRules();

      // Add VMP rules (if any) separated by commas
      if (count($allowed_vmp_rules) > 0) {
        $xml->addChild(self::ExplicitlyAllowedVMPValues, join(',', $allowed_vmp_rules));
      }

      return $xml;
    }

    #region Deprecated methods
    /** KeyOS_WV_LicPolicy::setCanPersist() - Sets bool status.
     *
     * @param status $bool
     * @return $this
     * @throws Exception
     */
    public function setCanPersist($bool) {
      return $this->CanPersist($bool);
    }

    /* KeyOS_WV_LicPolicy::getCanPersist() - Gets bool status.
     * @return bool
     */
    public function getCanPersist() {
      return $this->CanPersist();
    }

    /** KeyOS_WV_LicPolicy::setCanPlay() - Sets bool status.
     *
     * @param status $bool
     * @return $this if SET | bool if GET
     * @throws Exception
     */
    public function setCanPlay($bool) {
      return $this->CanPlay($bool);
    }

    /* KeyOS_WV_LicPolicy::getCanPlay() - Gets bool status.
     *
     * @return $this IF SET | bool IF GET
     */
    public function getCanPlay() {
      return $this->CanPlay();
    }

    /** KeyOS_WV_LicPolicy::setLicenseDuration() - Sets duration in seconds.
     *
     * @param integer $seconds
     * @return $this
     * @throws Exception
     */
    public function setLicenseDuration($seconds) {
      return $this->LicenseDuration($seconds);
    }

    /* KeyOS_WV_LicPolicy::getLicenseDuration() - Gets Duration in seconds.
     *
     * @return integer seconds
     */
    public function getLicenseDuration() {
      return $this->LicenseDuration();
    }

    /** KeyOS_WV_LicPolicy::setPlaybackDuration() - Sets Playback Duration in seconds.
     *
     * @param integer $seconds
     * @return $this
     * @throws Exception
     */
    public function setPlaybackDuration($seconds) {
      return $this->PlaybackDuration($seconds);
    }

    /* KeyOS_WV_LicPolicy::getPlaybackDuration() - Gets Playback Duration in seconds.
     *
     * @return integer seconds
     */
    public function getPlaybackDuration() {
      return $this->PlaybackDuration();
    }
    #endregion
  }


  /*
   * KeyOS_FP_LicPolicy
   */
  class KeyOS_FP_LicPolicy extends KeyOS_LicPolicy_Abstract {
    const LEASE_EXPIRATION_DATE = 'LeaseExpirationDate';
    const LEASE_EXPIRATION_SECONDS = 'LeaseExpirationSeconds';
    const RENTAL_EXPIRATION_DATE = 'RentalExpirationDate';
    const RENTAL_EXPIRATION_SECONDS = 'RentalExpirationSeconds';
    const PERSISTENCE_DATE = 'PersistenceDate';
    const PERSISTENCE_SECONDS = 'PersistenceSeconds';
    const PERSISTENT = 'persistent';
    const HDCP_ENFORCEMENT = 'HDCPEnforcement';
    const HDCP_ENFORCEMENT_HDCP_NONE = 'HDCP_NONE';
    const HDCP_ENFORCEMENT_HDCP_TYPE0 = 'HDCP_TYPE0';
    const HDCP_ENFORCEMENT_HDCP_TYPE1 = 'HDCP_TYPE1';


    private $_list_leaserental = array();
    private $_hdcp_enforcement_values = array(self::HDCP_ENFORCEMENT_HDCP_NONE, self::HDCP_ENFORCEMENT_HDCP_TYPE0, self::HDCP_ENFORCEMENT_HDCP_TYPE1);


    public function __construct($id = null) {
      parent::__construct($id);

      $this->registerPersistentPolicyNames(
        self::PERSISTENCE_DATE,
        self::PERSISTENCE_SECONDS
      );

      $this->registerLeaseRentalPolicyNames(
        self::LEASE_EXPIRATION_DATE,
        self::LEASE_EXPIRATION_SECONDS,
        self::RENTAL_EXPIRATION_DATE,
        self::RENTAL_EXPIRATION_SECONDS
      );
    }


    /** KeyOS_FP_LicPolicy::Persistent() - Sets or gets persistency status.
     *
     * @param status $bool (if SET)
     * @return $this (if SET) or bool if GET
     * @throws Exception
     */
    public function Persistent ($bool = null) {
      if (func_num_args() == 0) {
        return $this->get(self::PERSISTENT, false);
      }

      if ($bool && $this->hasLeaseRentalPolicies()) {
        $this->_throw(__METHOD__ . '(): Persistent license cannot have Rental or Lease expiration policies');
      }

      return $this->setBool(self::PERSISTENT, $bool, __METHOD__ . '(): can take bool parameter only.');
    }


    /** KeyOS_FP_LicPolicy::isPersistent() - derived from KeyOS_LicPolicy_Abstract.
     *
     * Returns TRUE if Persistent is set to TRUE or some persistent policies are set.
     *
     * @return bool
     * @throws Exception
     */
    public function isPersistent () {
      return $this->Persistent() || parent::isPersistent();
    }

    /** KeyOS_FP_LicPolicy::PersistenceDate() - Sets or gets Persistent License Expiration date/time.
     *
     * @param mixed $datetime_utc
     * @return $this (if SET) or string/null $datetime_utc (if GET)
     * @throws Exception
     */
    public function PersistenceDate($datetime_utc = null) {
      if (func_num_args() == 0) {
        return $this->get(self::PERSISTENCE_DATE);
      }

      if ($this->hasLeaseRentalPolicies()) {
        $this->_throw(__METHOD__ . '(): Persistent license cannot have Rental or Lease expiration policies');
      }

      if ($this->has(self::PERSISTENCE_SECONDS)) {
        $this->_throw(__METHOD__ . '(): ' . self::PERSISTENCE_DATE . ' is not allowed, since ' . self::PERSISTENCE_SECONDS . ' was already set.');
      }

      $now_utc = KeyOS_Date::UTC2UnixTimeStamp();
      $datetime_utc = KeyOS_Date::UTC2UnixTimeStamp($datetime_utc);

      if ($this->validation() && $datetime_utc < $now_utc) {
        $this->_throw(__METHOD__ . '(): Attempt to set already expired date: \'' . self::PERSISTENCE_DATE . '\'.');
      }

      return $this->set(self::PERSISTENCE_DATE, KeyOS_Date::dtf($datetime_utc));
    }

    /** KeyOS_FP_LicPolicy::PersistenceSeconds() - Sets or gets how long (in seconds) license will be valid.
     *
     * @param integer $seconds (if SET)
     * @return $this (if SET) or seconds (if GET)
     * @throws Exception
     */
    public function PersistenceSeconds($seconds = null) {
      if (func_num_args() == 0) {
        return $this->get(self::PERSISTENCE_SECONDS);
      }

      if ($this->hasLeaseRentalPolicies()) {
        $this->_throw(__METHOD__ . '(): Persistent license cannot have Rental or Lease expiration policies');
      }

      if ($this->has(self::PERSISTENCE_DATE)) {
        $this->_throw(__METHOD__ . '(): ' . self::PERSISTENCE_SECONDS . ' is not allowed, since ' . self::PERSISTENCE_DATE . ' was already set.');
      }

      if (preg_match('/^\d+$/', $seconds) !== 1) {
        $this->_throw(__METHOD__ . '(): \'' . self::PERSISTENCE_SECONDS . '\' is not valid second\'s number: \'' . $seconds . '\'');
      }

      return $this->set(self::PERSISTENCE_SECONDS, $seconds);
    }
    

    /** KeyOS_FP_LicPolicy::LeaseExpirationDate() - Sets or gets Lease Expiration date/time.
     *
     * @param mixed $datetime_utc
     * @return $this (if SET) or string/null $datetime_utc (if GET)
     * @throws Exception
     */
    public function LeaseExpirationDate($datetime_utc = null) {
      if (func_num_args() == 0) {
        return $this->get(self::LEASE_EXPIRATION_DATE);
      }

      if ($this->isPersistent()) {
        $this->_throw(__METHOD__ . '(): Persistent license cannot have Rental or Lease expiration policies');
      }

      if ($this->has(self::LEASE_EXPIRATION_SECONDS)) {
        $this->_throw(__METHOD__ . '(): ' . self::LEASE_EXPIRATION_DATE . ' is not allowed, since ' . self::LEASE_EXPIRATION_SECONDS . ' was already set.');
      }

      $now_utc = KeyOS_Date::UTC2UnixTimeStamp();
      $datetime_utc = KeyOS_Date::UTC2UnixTimeStamp($datetime_utc);

      if ($this->validation() && $datetime_utc < $now_utc) {
        $this->_throw(__METHOD__ . '(): Attempt to set already expired date: \'' . self::LEASE_EXPIRATION_DATE . '\'.');
      }

      return $this->set(self::LEASE_EXPIRATION_DATE, KeyOS_Date::dtf($datetime_utc));
    }

    /** KeyOS_FP_LicPolicy::LeaseExpirationSeconds() - Sets or gets Lease Expiration (in seconds) after license is issued
     *
     * @param string $seconds
     * @return $this (if SET) or seconds/null (if GET)
     * @throws Exception
     */
    public function LeaseExpirationSeconds($seconds = null) {
      if (func_num_args() == 0) {
        return $this->get(self::LEASE_EXPIRATION_SECONDS);
      }

      if ($this->isPersistent()) {
        $this->_throw(__METHOD__ . '(): Persistent license cannot have Rental or Lease expiration policies');
      }

      if ($this->has(self::LEASE_EXPIRATION_DATE)) {
        $this->_throw(__METHOD__ . '(): ' . self::LEASE_EXPIRATION_SECONDS . ' is not allowed, since ' . self::LEASE_EXPIRATION_DATE . ' was already set.');
      }

      if (preg_match('/^\d+$/', $seconds) !== 1) {
        $this->_throw(__METHOD__ . '(): \'' . self::LEASE_EXPIRATION_SECONDS . '\' is not valid second\'s number: \'' . $seconds . '\'');
      }

      return $this->set(self::LEASE_EXPIRATION_SECONDS, $seconds);
    }

    /** KeyOS_FP_LicPolicy::RentalExpirationDate() - Sets or gets Rental Expiration date/time.
     *
     * @param mixed $datetime_utc
     * @return $this (if SET) or string $datetime_utc (if GET)
     * @throws Exception
     */
    public function RentalExpirationDate($datetime_utc = null) {
      if (func_num_args() == 0) {
        return $this->get(self::RENTAL_EXPIRATION_DATE);
      }

      if ($this->isPersistent()) {
        $this->_throw(__METHOD__ . '(): Persistent license cannot have Rental or Lease expiration policies');
      }

      if ($this->has(self::RENTAL_EXPIRATION_SECONDS)) {
        $this->_throw(__METHOD__ . '(): ' . self::RENTAL_EXPIRATION_DATE . ' is not allowed, since ' . self::RENTAL_EXPIRATION_SECONDS . ' was already set.');
      }

      $now_utc = KeyOS_Date::UTC2UnixTimeStamp();
      $datetime_utc = KeyOS_Date::UTC2UnixTimeStamp($datetime_utc);

      if ($this->validation() && $datetime_utc < $now_utc) {
        $this->_throw(__METHOD__ . '(): Attempt to set already expired date: \'' . self::RENTAL_EXPIRATION_DATE . '\'.');
      }

      return $this->set(self::RENTAL_EXPIRATION_DATE, KeyOS_Date::dtf($datetime_utc));
    }

    /** KeyOS_FP_LicPolicy::RentalExpirationSeconds() - Sets or gets Rental Expiration (in seconds) after license is issued
     *
     * @param string $seconds
     * @return $this (if SET) or seconds/null (if GET)
     * @throws Exception
     */
    public function RentalExpirationSeconds($seconds = null) {
      if (func_num_args() == 0) {
        return $this->get(self::RENTAL_EXPIRATION_SECONDS);
      }

      if ($this->isPersistent()) {
        $this->_throw(__METHOD__ . '(): Persistent license cannot have Rental or Lease expiration policies');
      }

      if ($this->has(self::RENTAL_EXPIRATION_DATE)) {
        $this->_throw(__METHOD__ . '(): ' . self::RENTAL_EXPIRATION_SECONDS . ' is not allowed, since ' . self::RENTAL_EXPIRATION_DATE . ' was already set.');
      }

      if (preg_match('/^\d+$/', $seconds) !== 1) {
        $this->_throw(__METHOD__ . '(): \'' . self::RENTAL_EXPIRATION_SECONDS . '\' is not valid second\'s number: \'' . $seconds . '\'');
      }

      return $this->set(self::RENTAL_EXPIRATION_SECONDS, $seconds);
    }

    /** KeyOS_FP_LicPolicy::registerLeaseRentalPolicyNames() - puts a policy name into list of Lease/Rental ones
     *
     * @param $lease_rental_policy_name
     * @return $this
     * @throws Exception
     */
    protected function registerLeaseRentalPolicyNames($lease_rental_policy_name) {
      $args = func_get_args();

      foreach ($args as $policy_name) {
        $this->_list_leaserental[$policy_name] = '';
      }

      return $this;
    }

    /** KeyOS_FP_LicPolicy::hasLeaseRentalPolicies() - Checks if it contains any lease or rental expiration policy.
     *
     * @return bool
     */
    public function hasLeaseRentalPolicies() {
      foreach ($this->_list_leaserental as $policy_name => $v) {
        if ($this->has($policy_name)) {
          return true;
        }
      }

      return false;
    }

    /** KeyOS_FP_LicPolicy::HDCPEnforcement() - Sets or gets HDCP Enforcement setting
     *
     * It has two allowed values:
     *  - HDCP_NONE - means that non-HDCP playback is allowed.
     *  - HDCP_TYPE0 - enforces HDCP type 0. Type 0 Content Streams may be transmitted by the HDCP Repeater to all HDCP Devices.
     *  - HDCP_TYPE1 - enforces HDCP 2.2 and is intended to preventing content from going to v2.1, 2.0 and v1.x HDCPs as they all have weaknesses. Assumed that UHD content requires that.
     *
     * @param $value string
     * @return $this (if SET) or string (if GET)
     * @throws Exception
     */
    public function HDCPEnforcement ($value = null) {
      if (func_num_args() == 0) {
        return $this->get(self::HDCP_ENFORCEMENT);
      }

      if ($this->validation() && ! in_array($value, $this->_hdcp_enforcement_values)) {
        $this->_throw(__METHOD__ . '(): Unknown: ' . $value . '. Allowed: ' . join(', ', $this->_hdcp_enforcement_values) . ".");
      }

      return $this->set(self::HDCP_ENFORCEMENT, $value);
    }


    /** KeyOS_FP_LicPolicy::generateXML() - Generates XML element based on policies.
     *
     * @return XML-element
     * @throws Exception
     */
    public function generateXML() {
      $xml = new KeyOS_SimpleXML('<FairPlayPolicy/>');

      // KeyOS_FP_LicPolicy::isPersistent() returns TRUE if Persistent is set to TRUE or there are set any persistent policy.
      // NOTE: Flag should always be set. Default is FALSE.
      $xml->addAttribute($this::PERSISTENT, $this->isPersistent() ? 'true' : 'false');

      foreach ($this->PairsNotNulls() as $policy => $value) {
        if ($policy == self::PERSISTENT) {
          // Skip this, since "PERSISTENT" attribute is set by result of isPersistent(), not by Persistent() method.
          continue;
        }

        $xml->addChild($policy, $value);
      }

      return $xml;
    }
  }


  /** KeyOS_PlayRightsSet
   *
   */
  class KeyOS_PlayRightsSet extends KeyOS_Id {
    public $PlayEnablersList = null;
    private $_output_protections = array();


    public function __construct($id = null) {
      parent::__construct($id);
      $this->PlayEnablersList = new KeyOS_PlayEnablersList();
    }


    #region OPLs HIGH-LEVEL SET/GET methods
    /** KeyOS_PlayRightsSet::OPL()  -  Sets / Gets Output Protection Levels
     *
     * NOTE: Mostly used internally by such methods as CompressedDigitalAudio() and so on.
     *
     * Usage:
     *    - SET: OPL($op_name, $level)  -  Sets Output Protection Level
     *    - GET:
     *      - OPL()  -  IF NO ARGs, return all Output Protection Levels that are set
     *      - OPL($op_name)  -  IF ONE ARG - $op_name passed, return this Output Protection (if any) w/ its Level
     *
     * @param $op_name  -  Output Protection name | null IF GET ALL
     * @param $level if SET | null if GET
     * @return KeyOS_PlayRightsSet | level num | null
     * @throws Exception
     */
    public function OPL($op_name = null, $level = null) {
      // IF NO ARGs, return all OPLs that are set
      if ($op_name === null) {
        $assoc_arr = array();
        foreach (KeyOSAuthXML_OPL::getOPLRights() as $name) {
          if (key_exists($name, $this->_output_protections)) {
            $assoc_arr[$name] = $this->_output_protections[$name];
          }
        }

        return $assoc_arr;
      }

      // IF ONE ARG - $op_name, then return its value if any
      if ($level === null) {
        return key_exists($op_name, $this->_output_protections) ? $this->_output_protections[$op_name] : null;
      }

      return $this->OutputProtection($op_name, $level);
    }

    public function CompressedDigitalAudio($level = null) { return $this->OPL(__FUNCTION__, $level); }
    public function UncompressedDigitalAudio($level = null) { return $this->OPL(__FUNCTION__, $level); }
    public function CompressedDigitalVideo($level = null) { return $this->OPL(__FUNCTION__, $level); }
    public function UncompressedDigitalVideo($level = null) { return $this->OPL(__FUNCTION__, $level); }
    public function AnalogVideo($level = null) { return $this->OPL(__FUNCTION__, $level); }
    #endregion

    #region Output Protection Explicit HIGH-LEVEL SET/GET methods
    /** KeyOS_PlayRightsSet::OPExplicit()  -  Sets / Gets Explicits Output Protections
     *
     * NOTE: Mostly used internally by such methods as AnalogVideoExplicit() and so on.
     *
     * Usage:
     *    - SET:
     *      - OPExplicit($op_name, $guid, $config_data)  -  Sets Explicit Output Protection w/ GUID and its ConfigData
     *      - OPExplicit($op_name, $guid, null)  -  Sets Explicit Output Protection w/ GUID
     *                                              and ConfigData will be set automatically IF THERE IS ONLY ONE POSSIBLE VALUE FOR IT,
     *                                              OTHERWISE EXCEPTION WILL BE THROWN.
     *    - GET:
     *      - OPExplicit()  -  IF NO ARGs, return all Explicit Output Protection that are set
     *      - OPExplicit($op_name)  -  IF ONE ARG - $op_name passed, return all GUIDs w/ ConfigData that are set for passed Explicit Output Protection
     *      - OPExplicit($op_name, $guid)  -  IF TWO ARGs - $op_name and $guid only passed, return all ConfigData(s) that is/are set for it
     *
     * @param $op_name  -  Output Protection name
     * @param $guid if SET | null if GET
     * @param $config_data | null if default when only one config data available
     * @return KeyOS_PlayRightsSet | level num | null
     * @throws Exception
     */
    public function OPExplicit($op_name = null, $guid = null, $config_data = null) {
      // IF NO ARGs, return all Explicit Output Protection that are set
      if ($op_name === null) {
        $assoc_arr = array();
        foreach (KeyOSAuthXML_OPL::getExplicitOPLRights() as $name) {
          if (key_exists($name, $this->_output_protections)) {
            $assoc_arr[$name] = $this->_output_protections[$name];
          }
        }

        return $assoc_arr;
      }

      // IF ONE ARG - $op_name passed, return all GUIDs w/ ConfigData that are set for passed Explicit Output Protection
      if ($guid === null) {
        return key_exists($op_name, $this->_output_protections) ? $this->_output_protections[$op_name] : null;
      }
      // IF TWO ARGs - $op_name and $guid only passed, return all ConfigData(s) that is/are set for it
      if (func_num_args() == 2) {
        return key_exists($op_name, $this->_output_protections) && is_array($this->_output_protections[$op_name]) && key_exists($guid, $this->_output_protections[$op_name]) ? $this->_output_protections[$op_name][$guid] : null;
      }

      // IF THREE ARGs, then set Explicit Output Protection w/ GUID and its ConfigData
      return $this->OutputProtection($op_name, ($config_data === null ? (array)$guid : array($guid => $config_data)));
    }

    public function AnalogVideoExplicit($guid = null, $config_data = null) { return $this->OPExplicit(__FUNCTION__, $guid, $config_data); }
    public function DigitalAudioExplicit($guid = null, $config_data = null) { return $this->OPExplicit(__FUNCTION__, $guid, $config_data); }
    public function DigitalVideoExplicit($guid = null, $config_data_or_width = null, $height = null) {
      return $this->OPExplicit(__FUNCTION__, $guid,
        (new KeyOS_GUID($guid))->isEqual('9645E831-E01D-4FFF-8342-0A720E3E028F') ? 'width='.$config_data_or_width.'&height='.$height : $config_data_or_width);
    }
    #endregion

    #region Output Protection LOW-LEVEL SET/GET method
    /** KeyOS_PlayRightsSet::OutputProtections() - Adds/Gets Output Protection Levels and Explicits
     *
     * NOTE: This is LOW-LEVEL method which uses KeyOSAuthXML_OPL static class and actually sets OP Levels and Explicits.
     *
     * WARNING: In future KeyOSAuthXML_OPL will be abolished and all work will be done PlayRight class as should be.
     *
     * @param mixed $op_name
     * @param mixed $data - array with explicit or OPL and ConfigData if any
     *
     * @return $this
     * @throws Exception
     */
    public function OutputProtection ($op_name = null, $data = null) {
      $args_num = func_num_args();
      if ($args_num < 2) {
        return $args_num == 0 ? $this->_output_protections : (key_exists($op_name, $this->_output_protections) ? $this->_output_protections[$op_name] : null);
      }

      $errmsg = '';

      if ($this->validation()) {
        if (! KeyOSAuthXML_OPL::exists($op_name)) {
          $this->_throw(__METHOD__ . '(): Unknown: ' . $op_name);
        }

        if (KeyOSAuthXML_OPL::validateOPL($op_name, $data, $errmsg) === false) {
          $this->_throw(__METHOD__ . '(): Invalid `' . $op_name . '`: ' . ($errmsg ? $errmsg : print_r($data, true)));
        }
      }

      if (in_array($op_name, KeyOSAuthXML_OPL::getExplicitOPLRights())) {
        // Explicit output protections may have multiple protection IDs set for them.
        foreach ($data as $guid => $config_data) {
          if (is_numeric($guid) && $config_data) {
            $guid = $config_data;
            $config_data = null;
          }

          $guid = (new KeyOS_GUID($guid))->toString(true);
          $allowed_config_data_list = null;

          try {
            @$allowed_config_data_list = KeyOSAuthXML_OPL::getAllowedExplicitData($op_name, $guid);
          }
          catch (Exception $e) {
            if ($this->validation()) {
              $this->_throw(__METHOD__ . '(): Unable to get Output Protection `' . $op_name . '`` / `' . $guid . '``.');
            }
          }

          // If ConfigData value is not specified and there is only one possible value, then set it automatically.
          if ($config_data === null && is_array($allowed_config_data_list) && count($allowed_config_data_list) == 1) {
            $config_data = $allowed_config_data_list[0];
          }

          if (! key_exists($op_name, $this->_output_protections) || ! is_array($this->_output_protections[$op_name])) {
            $this->_output_protections[$op_name] = array();
          }

          if (! key_exists($guid, $this->_output_protections[$op_name]) || ! is_array($this->_output_protections[$op_name][$guid])) {
            $this->_output_protections[$op_name][$guid] = array();
          }

          $this->_output_protections[$op_name][$guid][] = $config_data;
        }
      }
      else {
        // OPL level can be only single possible value, not array of them.
        $this->_output_protections[$op_name] = $data;
      }

      return $this;
    }
    #endregion

    /** KeyOS_PlayRightsSet::isEmpty() - Checks if object of PlayRights Set is empty.
     *
     * @return bool
     */
    public function isEmpty() {
      return ! $this->PlayEnablersList->hasItems() && ! $this->hasOutputProtection();
    }

    /* KeyOS_PlayRightsSet::hasOutputProtection() - Checks if it contains OPLs.
     *
     * @param $op_name IF SPECIFIED | null IF ANY
     * @return bool
     */
    public function hasOutputProtection($op_name = null) {
      return func_num_args() == 0 ? count($this->OutputProtection()) > 0 : $this->OutputProtection($op_name) !== null;
    }

    /* KeyOS_PlayRightsSet::hasOPL() - Checks if it contains Output Protection Levels.
     *
     * @param $op_name IF SPECIFIED | null IF ANY
     * @return bool
     */
    public function hasOPL($op_name = null) {
      return func_num_args() == 0 ? count($this->OPL()) > 0 : $this->OPL($op_name) !== null;
    }

    /* KeyOS_PlayRightsSet::hasExplicit() - Checks if it contains Output Protection Explicits.
     *
     * @param $op_name IF SPECIFIED | null IF ANY
     * @return bool
     */
    public function hasExplicit($op_name = null) {
      return func_num_args() == 0 ? count($this->OPExplicit()) > 0 : $this->OPExplicit($op_name) !== null;
    }

    #region Deprecated methods
    /** KeyOS_PlayRightsSet::addOutputProtection() - Adds Output Protection Levels
     *
     * @param mixed $opl
     * @param mixed $data - array with explicit or OPL and ConfigData if any
     *
     * @return $this
     * @throws Exception
     */
    public function addOutputProtection ($opl, $data) {
      return $this->OutputProtection($opl, $data);
    }

    /* KeyOS_PlayRightsSet::getOutputProtections() - Gets array of Output Protections
     *
     * @return assoc array - name/data
     *
     * NOTE: It returns ALL Output Protections: Explicits and OPLs
     */
    public function getOutputProtections() {
      return $this->OutputProtection();
    }

    /* KeyOS_PlayRightsSet::getOPLs() - Gets array of OPLs
     *
     * @return assoc array
     */
    public function getOPLs() {
      return $this->OPL();
    }

    /* KeyOS_PlayRightsSet::getExplicits() - Gets array of Explicit Output Protections
     *
     * @return assoc array
     */
    public function getExplicits() {
      return $this->OPExplicit();
    }

    /** KeyOS_PlayRightsSet::hasPlayRights() - Checks if it contains any Play Rights.
     *
     * NOTE: hasPlayRights() === !isEmpty().
     *
     * @return bool
     */
    public function hasPlayRights() {
      return $this->PlayEnablersList->hasItems() || $this->hasOutputProtection();
    }
    #endregion
  }

  /*
   * KeyOS_WV_ContentKeySpec
   *
   */
  class KeyOS_WV_ContentKeySpec extends KeyOS_Pairs {
    const TRACK_TYPE = 'TrackType';
    const SECURITY_LEVEL = 'SecurityLevel';
    const REQUIRED_HDCP = 'RequiredHDCP';
    const REQUIRED_CGMS = 'RequiredCGMS';
    /** @deprecated Please use REQUIRED_CGMS instead. */
    const REQUIRED_CGMI = 'RequiredCGMS';


    public function __construct($id = null) {
      parent::__construct($id);

      $this->TrackType('HD');
      $this->SecurityLevel(1);
    }


    /** KeyOS_WV_ContentKeySpec::TrackType - Sets or gets track type
     *
     * @param string $type (if SET)
     * @return $this (if SET) | string type (if GET)
     * @throws Exception
     */
    public function TrackType ($value = null) {
      if (func_num_args() == 0) {
        return $this->get(self::TRACK_TYPE);
      }

      $list = array('SD', 'HD', 'AUDIO');

      if ($this->validation() && ! in_array($value, $list)) {
        $this->_throw(__METHOD__.'(): Unknown: '.$value.'. Allowed: '.join(',', $list));
      }

      return $this->set(self::TRACK_TYPE, $value);
    }

    /** KeyOS_WV_ContentKeySpec::setSecurityLevel - Sets security level
     *
     * @param integer $value (if SET)
     * @return $this (if SET) | int/null (if GET)
     * @throws Exception
     */
    public function SecurityLevel ($value = null) {
      if (func_num_args() == 0) {
        return $this->get(self::SECURITY_LEVEL);
      }

      $list = array(1,2,3,4,5);

      if ($this->validation() && ! in_array($value, $list)) {
        $this->_throw(__METHOD__.'(): Unknown: '.$value.'. Allowed: '.join(',', $list));
      }

      return $this->set(self::SECURITY_LEVEL, $value);
    }

    /** KeyOS_WV_ContentKeySpec::RequiredHDCP
     *
     * @param string (if SET)
     * @return $this (if SET) or string/null (if GET)
     * @throws Exception
     */
    public function RequiredHDCP ($value = null) {
      if (func_num_args() == 0) {
        return $this->get(self::REQUIRED_HDCP);
      }

      $list = array('HDCP_NONE', 'HDCP_V1', 'HDCP_V2', 'HDCP_V2_1', 'HDCP_V2_2', 'HDCP_NO_DIGITAL_OUTPUT');

      if ($this->validation() && ! in_array($value, $list)) {
        $this->_throw(__METHOD__.'(): Unknown: '.$value.'. Allowed: '.join(', ', $list));
      }

      return $this->set(self::REQUIRED_HDCP, $value);
    }

    /** KeyOS_WV_ContentKeySpec::RequiredCGMS
     *
     * @param string (if SET)
     * @return $this (if SET) | string (if GET)
     * @throws Exception
     */
    public function RequiredCGMS ($value = null) {
      if (func_num_args() == 0) {
        return $this->get(self::REQUIRED_CGMS);
      }

      $list = array('CGMS_NONE', 'COPY_FREE', 'COPY_ONCE', 'COPY_NEVER');

      if ($this->validation() && ! in_array($value, $list)) {
        $this->_throw(__METHOD__.'(): Unknown: '.$value.'. Allowed: '.join(', ', $list));
      }

      return $this->set(self::REQUIRED_CGMS, $value);
    }

    /** KeyOS_WV_ContentKeySpec::generateXML() - Generates XML element based on settings.
     *
     * @return XML-element
     */
    public function generateXML() {
      $xml = new KeyOS_SimpleXML('<WidevineContentKeySpec/>');

      foreach ($this->PairsNotNulls() as $key => $value) {
        if ($key == self::TRACK_TYPE) {
          $xml->addAttribute($key, $value);
          continue;
        }

        $xml->addChild($key, $value);
      }

      return $xml;
    }

    #region Deprecated methods
    /** KeyOS_WV_ContentKeySpec::setTrackType - Sets track type
     *
     * @param string $value
     * @return $this
     * @throws Exception
     */
    public function setTrackType ($value) {
      return $this->TrackType($value);
    }

    /* KeyOS_WV_ContentKeySpec::setTrackType - gets track type
     *
     * @return string
     */
    public function getTrackType () {
      return $this->TrackType();
    }

    /** KeyOS_WV_ContentKeySpec::setSecurityLevel - Sets security level
     *
     * @param integer $value
     * @return $this
     * @throws Exception
     */
    public function setSecurityLevel ($value) {
      return $this->SecurityLevel($value);
    }

    /* KeyOS_WV_ContentKeySpec::getSecurityLevel()
     *
     * @return integer
     */
    public function getSecurityLevel () {
      return $this->SecurityLevel();
    }

    /** KeyOS_WV_ContentKeySpec::setRequiredHDCP
     *
     * @param string
     * @return $this
     * @throws Exception
     */
    public function setRequiredHDCP ($value) {
      return $this->RequiredHDCP($value);
    }

    /* KeyOS_WV_ContentKeySpec::getRequiredHDCP()
     *
     * @return string
     */
    public function getRequiredHDCP () {
      $this->RequiredHDCP();
    }

    /** KeyOS_WV_ContentKeySpec::setRequiredCGMI
     *
     * @param string
     * @return $this
     * @throws Exception
     */
    public function setRequiredCGMI ($value) {
      return RequiredCGMI($value);
    }
    

    /** KeyOS_WV_ContentKeySpec::getRequiredCGMI()
     *
     * @return string
     */
    public function getRequiredCGMI () {
      return RequiredCGMI();
    }
    
    /** @deprecated Please use REQUIRED_CGMS instead. */
    public function RequiredCGMI ($value = null) {
      return func_num_args() ? $this->RequiredCGMS($value) : $this->RequiredCGMS();
    }
    #endregion
  }


  /** Class KeyOS_FP_PlayRight
   *
   */
  class KeyOS_FP_PlayRight extends KeyOS_Pairs {
    // No PlayRight settings available at the momemnt
  }


  /** KeyOS_DomainExt class - Used to extend KeyOS_PR_RootLic and
   *        KeyOS_PR_SimpleLic to allow setting DomainId in a Root
   *        and Simple licenses.
   *
   * NOTE: This class won't be extended. Methods from this class will be called
   *       using magic method __call().
   */
  class KeyOS_DomainExt {
    // Values of these constants are used  as AuthXML->Data->Domain tag attributes.
    const JOIN = 'join';
    const LEAVE = 'leave';

    private $domain_id = null;

    function __construct($domain_id = null) {
      if (isset($domain_id)) {
        $this->setDomainId($domain_id);
      }
    }

    public function setDomainId($domain_id) {
      $this->domain_id = new KeyOS_GUID($domain_id);

      return $this;
    }

    public function getDomainId() {
      return $this->domain_id;
    }
  }

  /*
   * KeyOS_PR_RootLicExt class - Used to extend KeyOS_PR_RootLic and
   *        KeyOS_PR_LeafLic to allow setting RootKeyId and RootRMId
   *        in a Root and Leaf licenses.
   *
   * NOTE: This class won't be extended. Methods from this class will be called
   *       using magic method __call().
   */
  class KeyOS_PR_RootLicExt {
    private $_root_key_id = null; // KeyOS_GUID
    private $_root_rm_id = null; // KeyOS_GUID

    /*
     * KeyOS_PR_RootLicExt::setRootKeyId() - Sets RootKeyId
     *
     * @param mixed $root_key_id - GUID string/base64, KeyOS_GUID or KeyOS_GUID.
     *
     * @return $this
     */
    public function setRootKeyId($root_key_id) {
      $this->_root_key_id = new KeyOS_GUID($root_key_id);

      return $this;
    }

    /*
     * KeyOS_PR_RootLicExt::getRootKeyId() - Gets RootKeyId
     *
     * @return KeyOS_GUID
     */
    public function getRootKeyId() {
      return $this->_root_key_id;
    }

    /*
     * KeyOS_PR_RootLicExt::setRootRMId() - Sets RootRMId
     *
     * @param int $root_rm_id
     *
     * @return $this
     */
    public function setRootRMId($root_rm_id) {
      $this->_root_rm_id = intval($root_rm_id);

      return $this;
    }

    /*
     * KeyOS_PR_RootLicExt::getRootRMId() - Gets RootRMId
     *
     * @return int RootRMId
     */
    public function getRootRMId() {
      return $this->_root_rm_id;
    }
  }


  /*
   * KeyOS_PR_SimpleLicExt class - Used to extend KeyOS_PR_SimpleLic and
   *        KeyOS_PR_LeafLic to allow setting KeyId and RMId
   *        in a Simple and Leaf licenses.
   *
   * NOTE: This class won't be extended. Methods from this class will be called
   *       using magic method __call().
   */
  class KeyOS_PR_SimpleLicExt {
    private $_key_id = null; // KeyOS_GUID
    private $_rm_id = null; // KeyOS_GUID

    /*
     * KeyOS_PR_SimpleLicExt::setKeyId() - Sets KeyId
     *
     * @param mixed $key_id - GUID string/base64, KeyOS_GUID or KeyOS_GUID.
     *
     * @return $this
     */
    public function setKeyId($key_id) {
      $this->_key_id = new KeyOS_GUID($key_id);

      return $this;
    }

    /*
     * KeyOS_PR_SimpleLicExt::getKeyId() - Gets KeyId
     *
     * @return KeyOS_GUID
     */
    public function getKeyId() {
      return $this->_key_id;
    }

    /*
     * KeyOS_PR_SimpleLicExt::setRMId() - Sets RMId
     *
     * @param int $rm_id
     *
     * @return $this
     */
    public function setRMId($rm_id) {
      $this->_rm_id = intval($rm_id);

      return $this;
    }

    /*
     * KeyOS_PR_SimpleLicExt::getRMId() - Gets RMId
     *
     * @return int RMId
     */
    public function getRMId() {
      return $this->_rm_id;
    }
  }

  /*
   * KeyOS_License class - Base License class that should be inherited by
   *      KeyOS_PR_SimpleLic, KeyOS_PR_RootLic and KeyOS_PR_LeafLic.
   */
  class KeyOS_License extends KeyOS_Id {
    // NOTE: Constants values below are also used as values for XML tag attributes,
    // e.g. <License type="simple">...</License>
    const SIMPLE = 'simple';
    const LEAF = 'leaf';
    const ROOT = 'root';
    const WIDEVINE_SIMPLE = 'widevine_simple';
    const FAIRPLAY_SIMPLE = 'fairplay_simple';

    // Private properties
    private $_type = null; // const of KeyOS_License::
    private $_is_persistent = false;

    // PlayReady or Widevine policies set
    // KeyOS_LicensePolicies or KeyOS_WV_LicPolicy
    private $_policies_set_pointer = null;

    // PlayReady PlayRights or Widevine ContentKeySpec
    // KeyOS_PlayRightsSet or KeyOS_Widevine_ContentKeySpec
    private $_play_rights_set_pointer = null;

    // Properties contain methods available using magic method __call()
    protected $_domain_ext = null; // KeyOS_DomainExt
    protected $_root_ext = null; // KeyOS_PR_RootLicExt
    protected $_simple_ext = null; // KeyOS_PR_SimpleLicExt


    public function __construct($type) {
      parent::__construct();
      $this->setType($type);

      $this->bindPoliciesSet();
      $this->bindPlayRightsSet();
    }


    /** KeyOS_License::validation()  -  Get/Set bool - whether validation is enabled or disabled for this license.
     *                                  This method overrides inherited method to set validation flag
     *                                  to Policy and PlayRight instances as well.
     *
     * NOTE: Allows to disable validation so that new parameters can be added w/o modifications of generator.
     *
     * @param bool IF SET | no params IF GET
     * @return $this IF SET | bool IF GET
     */
    public function validation($bool = null) {
      if (func_num_args() == 0) {
        return parent::validation();
      }

      parent::validation($bool)->Policy()->validation($bool);
      $this->PlayRight()->validation($bool);
      return $this;
    }


    // Magic call methods of such extension classes as:
    //    KeyOS_PR_RootLicExt, KeyOS_PR_SimpleLicExt,
    //    KeyOS_LicenseLeafExt and KeyOS_DomainExt
    public function __call($method, $args) {
      if (in_array($method, array('setDomainId', 'getDomainId'))) {
        $ext = $this->_domain_ext;
      }
      else if (in_array($method, array('setRootKeyId', 'getRootKeyId', 'setRootRMId', 'getRootRMId'))) {
        $ext = $this->_root_ext;
      }
      else if (in_array($method, array('setKeyId', 'getKeyId', 'setRMId', 'getRMId'))) {
        $ext = $this->_simple_ext;
      }
      else {
        $this->_throw(__METHOD__ . '(): Unknown license\'s method called: \'' . $method . '()\'');
      }

      if (! $ext) {
        $this->_throw(__METHOD__ . '(): \'' . $method . '()\' method does not exist in \'' . ucfirst($this->getType()) . '\' License.');
      }

      $return = call_user_func_array(array($ext, $method), $args);

      return substr($method, 0, 3) == 'set' ? $this : $return;
    }

    /*
     * KeyOS_License::setType() - Sets license type
     *
     * @param KeyOS_License
     *
     * @return $this
     */
    protected function setType($type) {
      $this->_type = $type;

      return $this;
    }

    /*
     * KeyOS_License::getType() - Gets type of license
     *
     * @return KeyOS_LicenseType
     */
    public function getType() {
      return $this->_type;
    }

    /* KeyOS_License::isPersistent() - Gets license persistence state.
     *
     * @return bool - Returns current state
     */
    public function isPersistent() {
      return $this->_isPersistent();
    }

    /*
     * KeyOS_License::bindPoliciesSet() - Binds object of policies set.
     *
     * @param KeyOS_LicPolicy or KeyOS_***License $policies_set
     *
     * NOTE: if no argument passed, it unbinds current policies set and creates new one.
     *
     * @return $this
     */
    public function bindPoliciesSet ($policies_set = null) {
      if ($policies_set instanceof KeyOS_WV_LicPolicy
          || $policies_set instanceof KeyOS_FP_LicPolicy
          || $policies_set instanceof KeyOS_LicPolicy)
      {
        $this->_policies_set_pointer = $policies_set;
      }
      else if ($policies_set instanceof KeyOS_License) {
        $this->_policies_set_pointer = $policies_set->getPoliciesSet();
      }
      else if ($policies_set === null) {
        if ($this->isWidevine()) {
          $this->_policies_set_pointer = new KeyOS_WV_LicPolicy();
        }
        else if ($this->isFairPlay()) {
          $this->_policies_set_pointer = new KeyOS_FP_LicPolicy();
        }
        else {
          $this->_policies_set_pointer = new KeyOS_LicPolicy();
        }
      }
      else {
        $this->_throw(__METHOD__ . '() can take only policies set or license instances as argument.');
      }

      // Set Policy's validation flag equal to License's one
      $this->_policies_set_pointer->validation($this->validation());

      return $this;
    }

    /*
     * KeyOS_License::bindPlayRightsSet() - Binds object of play rights set.
     *
     * @param KeyOS_PlayRightsSet $play_rights_set
     *
     * NOTE: if no argument passed, it unbinds play rights set and creates new one.
     *
     * @return $this
     */
    public function bindPlayRightsSet ($play_rights_set = null) {
      if ($play_rights_set instanceof KeyOS_PlayRightsSet
          || $play_rights_set instanceof KeyOS_WV_ContentKeySpec
          || $play_rights_set instanceof KeyOS_FP_PlayRight)
      {
        $this->_play_rights_set_pointer = $play_rights_set;
      }
      else if ($play_rights_set instanceof KeyOS_License) {
        $this->_play_rights_set_pointer = $play_rights_set->getPlayRightsSet();
      }
      else if ($play_rights_set === null) {
        if ($this->isWidevine()) {
          $this->_play_rights_set_pointer = new KeyOS_WV_ContentKeySpec();
        }
        else if ($this->isFairPlay()) {
          $this->_play_rights_set_pointer = new KeyOS_FP_PlayRight();
        }
        else {
          $this->_play_rights_set_pointer = new KeyOS_PlayRightsSet();
        }
      }
      else {
        $this->_throw(__METHOD__ . '() can take only play rights set, content key spec set or license instances as argument.');
      }

      // Set PlayRight's validation flag equal to License's one
      $this->_play_rights_set_pointer->validation($this->validation());

      return $this;
    }

    // Alias for bindPlayRightsSet
    public function bindContentKeySpecSet ($wv_content_key_spec_set = null) {
      return bindPlayRightsSet ($wv_content_key_spec_set);
    }

    /*
     * KeyOS_License::getPoliciesSet() - Gets pointer to bound policies set object.
     *
     * @return KeyOS_LicensePolicies
     */
    public function getPoliciesSet () {
      return $this->_policies_set_pointer;
    }

    /*
     * KeyOS_License::getPlayRightsSet() - Gets pointer to bound play rights set object.
     *
     * @return KeyOS_PlayRightsSet
     */
    public function getPlayRightsSet () {
      return $this->_play_rights_set_pointer;
    }

    // Alias of getPlayRightsSet()
    public function getContentKeySpecSet () {
      return $this->getPlayRightsSet();
    }

    public function Policy ($policies_set = null) {
      if ($policies_set !== null) {
        $this->bindPoliciesSet($policies_set);
      }

      return $this->getPoliciesSet();
    }

    public function PlayRight ($play_rights_set = null) {
      if ($play_rights_set !== null) {
        $this->bindPlayRightsSet($play_rights_set);
      }

      return $this->getPlayRightsSet();
    }

    // Alias of PlayRight()
    public function ContentKeySpec ($content_key_spec = null) {
      return $this->PlayRight($content_key_spec);
    }

    /** KeyOS_License::isPlayReady() - Indicates whether it is Widevine license or not.
     *
     * @return bool
     */
    public function isPlayReady() {
      return in_array($this->getType(), array(KeyOS_License::SIMPLE, KeyOS_License::ROOT, KeyOS_License::LEAF));
    }

    /** KeyOS_License::isWidevine() - Indicates whether it is Widevine license or not.
     *
     * @return bool
     */
    public function isWidevine() {
      return in_array($this->getType(), array(KeyOS_License::WIDEVINE_SIMPLE));
    }

    /** KeyOS_License::isFairPlay() - Indicates whether it is FairPlay license or not.
     *
     * @return bool
     */
    public function isFairPlay() {
      return in_array($this->getType(), array(KeyOS_License::FAIRPLAY_SIMPLE));
    }

    /** KeyOS_License::_isPersistent() - Gets or sets license persistence state.
     *
     * @param status $bool - If value passed, it sets bool state.
     *
     * @return bool - Returns current state
     * @throws Exception
     */
    protected function _isPersistent($bool = null) {
      if ($bool === true || $bool === false) {
        $this->_is_persistent = $bool;
      }
      else if ($bool !== null) {
        $this->_throw(__METHOD__ . '(): \'isPersistent\' function can take bool parameter only.');
      }

      return $this->_is_persistent || ($this->_policies_set_pointer && $this->_policies_set_pointer->isPersistent());
    }
  }

  class KeyOS_PR_RootLic extends KeyOS_License {
    /** KeyOS_PR_RootLic::__construct() - Sets RootKeyId
     *
     * @param KeyOS_GUID $root_key_id
     * @throws Exception
     */
    public function __construct($root_key_id) {
      parent::__construct(KeyOS_License::ROOT);

      // ROOT and LEAF licenses is persistent by definition and it cannot be changed.
      $this->_isPersistent(true);

      // Root license may contain: RootKeyId, RoorRMId and DomainId.
      $this->_root_ext = new KeyOS_PR_RootLicExt(); // RootKeyId and RootRMId
      $this->_domain_ext = new KeyOS_DomainExt(); // DomainId

      // Root and Leaf licenses should contain RootKeyId
      // Magic method. Allowed after $this->_root_ext is initialized.
      $this->setRootKeyId($root_key_id);
    }
  }

  class KeyOS_PR_LeafLic extends KeyOS_License {
    /*
     * KeyOS_PR_LeafLic::__construct() - Sets RootKeyId
     *
     * @param KeyOS_GUID $root_key_id
     */
    public function __construct($root_key_id, $key_id = null) {
      parent::__construct(KeyOS_License::LEAF);

      // ROOT and LEAF licenses is persistent by definition and it cannot be changed.
      $this->_isPersistent(true);

      // Leaf license may contain: RootKeyId, RoorRMId, KeyId and RMId.
      $this->_root_ext = new KeyOS_PR_RootLicExt(); // RootKeyId and RootRMId
      $this->_simple_ext = new KeyOS_PR_SimpleLicExt(); // KeyId and RMId

      // Root and Leaf licenses should contain RootKeyId
      // Magic method. Allowed after $this->_root_ext is initialized.
      $this->setRootKeyId($root_key_id);

      // Set optional KeyId if passed
      if ($key_id) $this->setKeyId($key_id);
    }
  }

  class KeyOS_PR_SimpleLic extends KeyOS_License {
    /*
     * KeyOS_PR_SimpleLic::__construct()
     */
    public function __construct($key_id = null) {
      parent::__construct(KeyOS_License::SIMPLE);

      // Simple license may contain: KeyId, RMId and DomainId.
      $this->_simple_ext = new KeyOS_PR_SimpleLicExt();
      $this->_domain_ext = new KeyOS_DomainExt();

      // Set optional KeyId if passed
      if ($key_id) $this->setKeyId($key_id);
    }

    /** KeyOS_License::isPersistent() - Gets or sets license persistence state.
     *
     * @param status $bool - If value passed, it sets bool state.
     *
     * @return bool - Returns current state
     * @throws Exception
     */
    public function isPersistent($bool = null) {
      return $this->_isPersistent($bool);
    }
  }

  class KeyOS_WV_SimpleLic extends KeyOS_License {
    /*
     * KeyOS_WV_SimpleLic::__construct()
     */
    public function __construct() {
      parent::__construct(KeyOS_License::WIDEVINE_SIMPLE);
    }
  }

  class KeyOS_FP_SimpleLic extends KeyOS_License {
    /*
     * KeyOS_FP_SimpleLic::__construct()
     */
    public function __construct() {
      parent::__construct(KeyOS_License::FAIRPLAY_SIMPLE);
    }
  }


  // Static classes


  class KeyOSAuthXML_OPL {
    const COMPRESSED_DIGITAL_AUDIO = 'CompressedDigitalAudio';
    const UNCOMPRESSED_DIGITAL_AUDIO = 'UncompressedDigitalAudio';
    const COMPRESSED_DIGITAL_VIDEO = 'CompressedDigitalVideo';
    const UNCOMPRESSED_DIGITAL_VIDEO = 'UncompressedDigitalVideo';
    const ANALOG_VIDEO = 'AnalogVideo';

    const ANALOG_VIDEO_EXPLICIT = 'AnalogVideoExplicit';
    const DIGITAL_AUDIO_EXPLICIT = 'DigitalAudioExplicit';
    const DIGITAL_VIDEO_EXPLICIT = 'DigitalVideoExplicit';

    // RegEx used at least for an ConfigData of DIGITAL_VIDEO_EXPLICIT
    const RE_WIDTH_HEIGHT = '/^width=\d{2,5}&height=\d{2,5}$/';


    // Specifies whether validation is enabled or disabled.
    private static $_fl_validation = true;


    // All Output Protections
    private static $_all = array(
      self::COMPRESSED_DIGITAL_AUDIO,
      self::UNCOMPRESSED_DIGITAL_AUDIO,
      self::COMPRESSED_DIGITAL_VIDEO ,
      self::UNCOMPRESSED_DIGITAL_VIDEO,
      self::ANALOG_VIDEO,
      self::ANALOG_VIDEO_EXPLICIT,
      self::DIGITAL_AUDIO_EXPLICIT,
      self::DIGITAL_VIDEO_EXPLICIT
    );

    // Explicits Output Protections
    private static $_explicits = array(
      self::ANALOG_VIDEO_EXPLICIT,
      self::DIGITAL_AUDIO_EXPLICIT,
      self::DIGITAL_VIDEO_EXPLICIT
    );

    // OPLs only ($_all w/o $_explicits)
    // Will be initialized automatically later
    private static $_opls = null;

    // Allowed Analog Video Explicit policies and their values
    private static $_allowed_ave = array(
      'C3FD11C6-F8B7-4D20-B008-1DB17D61F2DA' => array(0, 1, 2, 3),
      '2098DE8D-7DDD-4BAB-96C6-32EBB6FABEA3' => array(0, 1, 2, 3),
      '225CD36F-F132-49EF-BA8C-C91EA28E4369' => array(0, 1, 2, 3),
      '811C5110-46C8-4C6E-8163-C0482A15D47E' => array(520000),
      'D783A191-E083-4BAF-B2DA-E69F910B3772' => array(520000),
      '760AE755-682A-41E0-B1B3-DCDF836A7306' => array(0)
    );

    // Allowed Digital Audio Explicit policies and their values
    private static $_allowed_dae = array(
      '6D5CFA59-C250-4426-930E-FAC72C8FCFA6' => array('00', '01', '10', '11')
    );

    // Allowed Digital Video Explicit policies and their values
    private static $_allowed_dve = array(
      '9645E831-E01D-4FFF-8342-0A720E3E028F' => array(self::RE_WIDTH_HEIGHT),
      'ABB2C6F1-E663-4625-A945-972D17B231E7' => array(1)
    );

    /* KeyOSAuthXML_OPL::validation()  -  Get/Set bool - whether validation is enabled or disabled
     *
     * NOTE: Allows to disable validation so that new parameters can be added w/o modifications of generator.
     *       Mostly for internal use.
     *
     * @param bool IF SET | no params IF GET
     * @return bool
     */
    public static function validation($bool = null) {
      return func_num_args() == 0 ? self::$_fl_validation : (self::$_fl_validation = !!$bool);
    }

    public static function exists($opl) {
      return in_array($opl, self::$_all);
    }

    public static function getExplicitOPLRights() {
      return self::$_explicits;
    }

    public static function getOPLRights() {
      // Init (if not yet) and return $_opls which is $_all w/o $_explicits
      return self::$_opls ? self::$_opls : (self::$_opls = array_diff(self::$_all, self::$_explicits));
    }

    public static function getAllowedExplicitData($opl, $guid = null) {
      if ($opl == self::ANALOG_VIDEO_EXPLICIT) $allowed = self::$_allowed_ave;
      else if ($opl == self::DIGITAL_AUDIO_EXPLICIT) $allowed = self::$_allowed_dae;
      else if ($opl == self::DIGITAL_VIDEO_EXPLICIT) $allowed = self::$_allowed_dve;
      else $allowed = null;

      return $guid ? $allowed[(new KeyOS_GUID($guid))->toString(true)] : $allowed;
    }

    public static function validateOPL($opl, $data, &$errmsg) {
      $errmsg = '';

      switch ($opl) {
        case self::COMPRESSED_DIGITAL_AUDIO:
          return in_array($data, array(100, 150, 200, 250, 300,301));
          break;
        case self::UNCOMPRESSED_DIGITAL_AUDIO:
          return in_array($data, array(100, 150, 200, 250, 300,301));
          break;
        case self::COMPRESSED_DIGITAL_VIDEO:
          return in_array($data, array(400, 500,501));
          break;
        case self::UNCOMPRESSED_DIGITAL_VIDEO:
          return in_array($data, array(100, 250, 270, 300,301));
          break;
        case self::ANALOG_VIDEO:
          return in_array($data, array(100, 150, 200,201));
          break;
        case self::ANALOG_VIDEO_EXPLICIT:
        case self::DIGITAL_AUDIO_EXPLICIT:
        case self::DIGITAL_VIDEO_EXPLICIT:
          foreach ($data as $guid => $config_data) {
            if (is_numeric($guid) && $config_data) {
              $guid = $config_data;
              $config_data = null;
            }

            $guid = (new KeyOS_GUID($guid))->toString(true);
            $config_data = $config_data === '' ? null : $config_data;
            $allowed = self::getAllowedExplicitData($opl);

            if (! key_exists($guid, $allowed)) {
              $errmsg = $guid . ' is unknown !';
              return false;
            }

            if ($config_data === null && count($allowed[$guid]) == 1) {
              $config_data = $allowed[$guid][0];
            }

            // If ConfigData value is not found
            if (! in_array($config_data, $allowed[$guid])) {

              // Try to check by RegEx's if any in allowed ConfigData list
              foreach ($allowed[$guid] as $re) {
                if (is_string($re) && strlen($re) > 2 && strpos($re, '/') === 0 && strpos(strrev($re), '/') === 0 && preg_match($re, $config_data)) {
                  // Finish if RegEx detected and it matches to ConfigData value
                  return true;
                }
              }

              $errmsg = 'ConfigData "' . $config_data . '" is not allowed for ' . $guid . '. Allowed: ' . join(', ', $allowed[$guid]);
              return false;
            }
          }
          return true;
          break;
      }
    }
  }

  class KeyOSAuthXML_PlayEnablers {
    private static $_list = array( # Key is GUID ID. Value array contains forbidden GUID IDs to use together with key's GUID ID.
      '786627D8-C2A6-44BE-8F88-08AE255B01A7' => array('B621D91F-EDCC-4035-8D4B-DC71760D43E9'),
      'B621D91F-EDCC-4035-8D4B-DC71760D43E9' => array('786627D8-C2A6-44BE-8F88-08AE255B01A7'),
      'D685030B-0F4F-43A6-BBAD-356F1EA0049A' => '',
      '002F9772-38A0-43E5-9F79-0F6361DCC62A' => '',

      // HDCP (Miracast)
      'A340C256-0941-4D4C-AD1D-0B6735C0CB24' => '',
      // HDCP (WiVu)
      '1B4542E3-B5CF-4C99-B3BA-829AF46C92F8' => '',
      // AirPlay
      '5ABF0F0D-DC29-4B82-9982-FD8E57525BFC' => '',
      // Pro:Idiom
      '253C45E3-EF71-4A34-AF79-5BEFBA101A16' => '',
      // Summit Wireless Audio System
      '3E2ECE5B-C792-4A81-B4E2-89F62A4AD972' => ''
    );

    public static function validate($guid, &$errmsg = null) {
      $guid_str = (new KeyOS_GUID($guid))->toString(true);
      $errmsg = isset(self::$_list[$guid_str]) ? null : 'PlayEnabler GUID ID does not exist: \'' . $guid_str . '\'.';

      return empty($errmsg);
    }

    public static function validateList ($guid_list, &$errmsg = null) {
      if (!is_array($guid_list)) {
        $guid_list = array($guid_list);
      }

      if (count($guid_list) < 1) {
        $errmsg = 'No PlayEnablers passed';
        return false;
      }

      $guid_list2 = $guid_list;

      foreach ($guid_list as $guid) {
        if (!self::validate($guid, $errmsg)) {
          return false;
        }

        $guid_str = (new KeyOS_GUID($guid))->toString(true);

        if (is_array(self::$_list[$guid_str]) && count(self::$_list[$guid_str]) > 0)
          foreach ($guid_list2 as $guid2) {
            $guid_str2 = (new KeyOS_GUID($guid2))->toString(true);

            if (in_array($guid_str2, self::$_list[$guid_str])) {
              $errmsg = 'The following PlayEnabler GUID IDs cannot be used together: \'' . $guid_str . '\' and \'' . $guid_str2 . '\'';

              return false;
            }
          }
      }

      return true;
    }

    public static function getList () {
      $list = array();

      foreach (self::$_list as $guid_str => $value) {
        $list[$guid_str] = new KeyOS_GUID($guid_str);
      }

      return $list;
    }
  }

  class KeyOS_SimpleXML extends SimpleXMLElement {
    /** KeyOS_SimpleXML::addChildXML()  -  Adds SimpleXMLElement code into a SimpleXMLElement
     *
     * @param SimpleXMLElement | KeyOSSimpleXML | XML-string $append
     * @return $child
     */
    public function addChildXML ($xml) {
      if (! ($xml instanceof SimpleXMLElement)) {
        $xml = new SimpleXMLElement($xml);
      }

      if (strlen(trim((string) $xml))) {
        // XML-Element contains just text node
        $child = $this->addChild($xml->getName(), (string) $xml);
      }
      else {
        // XML-Element may contain children
        $child = $this->addChild($xml->getName());

        foreach ($xml->children() as $c) {
          $child->addChildXML($c);
        }
      }

      foreach ($xml->attributes() as $n => $v) {
        $child->addAttribute($n, $v);
      }

      return $child;
    }

    /** KeyOS_SimpleXML::addChildXML()  -  same as addChild() but puts value into <![CDATA[...]]>
     *
     * NOTE: SimpleXMLElement does not provide a way to surround value with <![CDATA[...]]>.
     *
     * @param $key  -  name of XML tag/node
     * @param $value_to_be_in_cdata  -  string value
     * @return KeyOS_SimpleXML (SimpleXMLElement) of added element
     */
    public function addChildCDATA ($key, $value_to_be_in_cdata) {
      // NOTE: <content> tags are ignored by simple_load_string, so only content of <content> will be put into XML.
      return $this->addChild($key, simplexml_load_string('<content><![CDATA[' . $value_to_be_in_cdata . ']]></content>'));
    }
  }


  class KeyOS_Date {
    static private $_date_format = 'Y-m-d H:i:s.000';

    /** KeyOS_Date::UTC2UnixTimeStamp() - Converts UTC date/time into Unix TimeStamp.
     *
     * NOTE: It assumes that passed date is already in GMT/UTC.
     *       If no param passed, then it returns current UTC date/time.
     *
     * @param mixed $datetime_utc (string time or time in milleseconds)
     *
     * @return integer Unix TimeStamp UTC
     */
    static public function UTC2UnixTimeStamp ($datetime_utc = 'now') {
      return is_numeric($datetime_utc) ? $datetime_utc : strtotime($datetime_utc, time() - date('Z'));
    }

    /** KeyOS_Date::UnixTimeStamp() - Converts local date/time into Unix TimeStamp.
     *
     * NOTE: It assumes that local date/time is passed.
     *       If no param passed, then it returns current date/time.
     *
     * @param mixed $datetime_utc (string time or time in milleseconds)
     *
     * @return integer Unix TimeStamp
     */
    static public function UnixTimeStamp ($datetime = 'now') {
      return is_numeric($datetime) ? $datetime : strtotime($datetime);
    }

    /** KeyOS_Date::toUTC() - Converts date/time from local into the GMT/UTC.
     *
     * @param mixed $datetime_local (string time or time in milleseconds)
     *
     * NOTE: It assumes that date/time passed in local time.
     *
     * @return string date/time formatted
     */
    static public function toUTC($datetime_local = 'now') {
      $datetime_local = is_numeric($datetime_local) ? $datetime_local : strtotime($datetime_local);

      return gmdate(self::$_date_format, $datetime_local);
    }

    /*
     * KeyOS_Date::dtf() - Converts passed date/time into formatted date.
     *
     * @param mixed $date
     * @return string date/time formatted
     */
    static public function dtf ($datetime) {
      return date(self::$_date_format, self::UnixTimeStamp($datetime));
    }
  }


  if (! function_exists('is_not_null')) {
    function is_not_null($val) {
      return $val !== null;
    }
  }
?>