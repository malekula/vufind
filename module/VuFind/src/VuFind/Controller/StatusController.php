<?php
/**
 * Ajax Controller Module
 *
 * PHP version 5
 *
 * Copyright (C) Villanova University 2010.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category VuFind
 * @package  Controller
 * @author   Chris Hallberg <challber@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:plugins:controllers Wiki
 */
namespace VuFind\Controller;
use VuFind\Exception\Auth as AuthException;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * This controller handles global AJAX functionality
 *
 * @category VuFind
 * @package  Controller
 * @author   Chris Hallberg <challber@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:plugins:controllers Wiki
 */
class StatusController extends AbstractBase
{
    // define some status constants
    const STATUS_OK = 'OK';                  // good
    const STATUS_ERROR = 'ERROR';            // bad
    const STATUS_NEED_AUTH = 'NEED_AUTH';    // must login first

    /**
     * Type of output to use
     *
     * @var string
     */
    protected $outputMode;

    /**
     * Array of PHP errors captured during execution
     *
     * @var array
     */
    protected static $php_errors = [];
    
    protected $driver;

    /**
     * Constructor
     *
     * @param ServiceLocatorInterface $sm Service locator
     */
    public function __construct(ServiceLocatorInterface $sm)
    {
        // Add notices to a key in the output
        set_error_handler(['VuFind\Controller\StatusController', "storeError"]);
        parent::__construct($sm);
    }

    
    public function statAction()
    {
        // Set the output mode to JSON:
        $this->outputMode = 'json';

        // Call the method specified by the 'method' parameter; append Ajax to
        // the end to avoid access to arbitrary inappropriate methods.
        $callback = [$this, $this->params()->fromQuery('method') . 'Ajax'];
        /*if (is_callable($callback)) {
            try {
                return call_user_func($callback);
            } catch (\Exception $e) {
                $debugMsg = ('development' == APPLICATION_ENV)
                    ? ': ' . $e->getMessage() : '';
                return $this->output(
                    $this->translate('An error has occurred') . $debugMsg,
                    self::STATUS_ERROR,
                    500
                );
            }
        } else {
            return $this->output(
                $this->translate('Invalid Method'), self::STATUS_ERROR, 400
            );
        }*/
        return call_user_func($callback);
    }
    
    
    /**
     * Handles passing data to the class
     *
     * @return mixed
     */
    public function jsonAction()
    {
        // Set the output mode to JSON:
        $this->outputMode = 'json';

        // Call the method specified by the 'method' parameter; append Ajax to
        // the end to avoid access to arbitrary inappropriate methods.
        $callback = [$this, $this->params()->fromQuery('method') . 'Ajax'];
        if (is_callable($callback)) {
            try {
                return call_user_func($callback);
            } catch (\Exception $e) {
                $debugMsg = ('development' == APPLICATION_ENV)
                    ? ': ' . $e->getMessage() : '';
                return $this->output(
                    $this->translate('An error has occurred') . $debugMsg,
                    self::STATUS_ERROR,
                    500
                );
            }
        } else {
            return $this->output(
                $this->translate('Invalid Method'), self::STATUS_ERROR, 400
            );
        }
    }

    /**
     * Support method for getItemStatuses() -- filter suppressed locations from the
     * array of item information for a particular bib record.
     *
     * @param array $record Information on items linked to a single bib record
     *
     * @return array        Filtered version of $record
     */
    protected function filterSuppressedLocations($record)
    {
        static $hideHoldings = false;
        if ($hideHoldings === false) {
            $logic = $this->serviceLocator->get('VuFind\ILSHoldLogic');
            $hideHoldings = $logic->getSuppressedLocations();
        }

        $filtered = [];
        foreach ($record as $current) {
            if (!in_array($current['location'], $hideHoldings)) {
                $filtered[] = $current;
            }
        }
        return $filtered;
    }

    
    protected function getItemStatAjax()
    {
        $ids = $this->params()->fromPost('id', $this->params()->fromQuery('id'));
        //$results = $this->getStatuses($ids);
        
        $loader = $this->serviceLocator->get('VuFind\RecordLoader');
        $this->driver = new \VuFind\RecordDriver\SolrDefault;
        $this->record = new \VuFind\View\Helper\Root\Record;
        $title = [];
        foreach ($ids as $id) {
            $this->record = $loader->load($ids[0]);
            $title[] = array('id'=>$id, 'field'=>get_class($this->record).' '.get_class($this->driver));
            //return $this->output($this->record($this->driver)->getShortTitle(), self::STATUS_OK);
        }
        return $this->output($title, self::STATUS_OK);
    }
    
    protected function getStatuses($record_ids) {
        //$this->driver = new \VuFind\RecordDriver\SolrDefault;
        //$result = $this->driver->getStatusRecords($record_ids);
        //return $result;
    }
    
    
    /**
     * Get Item Statuses
     *
     * This is responsible for printing the holdings information for a
     * collection of records in JSON format.
     *
     * @return \Zend\Http\Response
     * @author Chris Delis <cedelis@uillinois.edu>
     * @author Tuan Nguyen <tuan@yorku.ca>
     */
    protected function getItemStatusesAjax()
    {
        $this->disableSessionWrites();  // avoid session write timing bug
        $catalog = $this->getILS();
        $ids = $this->params()->fromPost('id', $this->params()->fromQuery('id'));
        $results = $catalog->getStatuses($ids);

        if (!is_array($results)) {
            // If getStatuses returned garbage, let's turn it into an empty array
            // to avoid triggering a notice in the foreach loop below.
            $results = [];
        }

        // In order to detect IDs missing from the status response, create an
        // array with a key for every requested ID.  We will clear keys as we
        // encounter IDs in the response -- anything left will be problems that
        // need special handling.
        $missingIds = array_flip($ids);

        // Get access to PHP template renderer for partials:
        $renderer = $this->getViewRenderer();

        // Load messages for response:
        $messages = [
            'available' => $renderer->render('ajax/status-available.phtml'),
            'unavailable' => $renderer->render('ajax/status-unavailable.phtml'),
            'unknown' => $renderer->render('ajax/status-unknown.phtml')
        ];

        // Load callnumber and location settings:
        $config = $this->getConfig();
        $callnumberSetting = isset($config->Item_Status->multiple_call_nos)
            ? $config->Item_Status->multiple_call_nos : 'msg';
        $locationSetting = isset($config->Item_Status->multiple_locations)
            ? $config->Item_Status->multiple_locations : 'msg';
        $showFullStatus = isset($config->Item_Status->show_full_status)
            ? $config->Item_Status->show_full_status : false;

        // Loop through all the status information that came back
        $statuses = [];
        foreach ($results as $recordNumber => $record) {
            // Filter out suppressed locations:
            $record = $this->filterSuppressedLocations($record);

            // Skip empty records:
            if (count($record)) {
                if ($locationSetting == "group") {
                    $current = $this->getItemStatusGroup(
                        $record, $messages, $callnumberSetting
                    );
                } else {
                    $current = $this->getItemStatus(
                        $record, $messages, $locationSetting, $callnumberSetting
                    );
                }
                // If a full status display has been requested, append the HTML:
                if ($showFullStatus) {
                    $current['full_status'] = $renderer->render(
                        'ajax/status-full.phtml', [
                            'statusItems' => $record,
                            'callnumberHandler' => $this->getCallnumberHandler()
                         ]
                    );
                }
                $current['record_number'] = array_search($current['id'], $ids);
                $statuses[] = $current;

                // The current ID is not missing -- remove it from the missing list.
                unset($missingIds[$current['id']]);
            }
        }

        // If any IDs were missing, send back appropriate dummy data
        foreach ($missingIds as $missingId => $recordNumber) {
            $statuses[] = [
                'id'                   => $missingId,
                'availability'         => 'false',
                'availability_message' => $messages['unavailable'],
                'location'             => $this->translate('Unknown'),
                'locationList'         => false,
                'reserve'              => 'false',
                'reserve_message'      => $this->translate('Not On Reserve'),
                'callnumber'           => '',
                'missing_data'         => true,
                'record_number'        => $recordNumber
            ];
        }

        // Done
        return $this->output($statuses, self::STATUS_OK);
    }

    /**
     * Support method for getItemStatuses() -- when presented with multiple values,
     * pick which one(s) to send back via AJAX.
     *
     * @param array  $list        Array of values to choose from.
     * @param string $mode        config.ini setting -- first, all or msg
     * @param string $msg         Message to display if $mode == "msg"
     * @param string $transPrefix Translator prefix to apply to values (false to
     * omit translation of values)
     *
     * @return string
     */
    protected function pickValue($list, $mode, $msg, $transPrefix = false)
    {
        // Make sure array contains only unique values:
        $list = array_unique($list);

        // If there is only one value in the list, or if we're in "first" mode,
        // send back the first list value:
        if ($mode == 'first' || count($list) == 1) {
            if (!$transPrefix) {
                return $list[0];
            } else {
                return $this->translate($transPrefix . $list[0], [], $list[0]);
            }
        } else if (count($list) == 0) {
            // Empty list?  Return a blank string:
            return '';
        } else if ($mode == 'all') {
            // Translate values if necessary:
            if ($transPrefix) {
                $transList = [];
                foreach ($list as $current) {
                    $transList[] = $this->translate(
                        $transPrefix . $current, [], $current
                    );
                }
                $list = $transList;
            }
            // All values mode?  Return comma-separated values:
            return implode(",\t", $list);
        } else {
            // Message mode?  Return the specified message, translated to the
            // appropriate language.
            return $this->translate($msg);
        }
    }

    /**
     * Based on settings and the number of callnumbers, return callnumber handler
     * Use callnumbers before pickValue is run.
     *
     * @param array  $list           Array of callnumbers.
     * @param string $displaySetting config.ini setting -- first, all or msg
     *
     * @return string
     */
    protected function getCallnumberHandler($list = null, $displaySetting = null)
    {
        if ($displaySetting == 'msg' && count($list) > 1) {
            return false;
        }
        $config = $this->getConfig();
        return isset($config->Item_Status->callnumber_handler)
            ? $config->Item_Status->callnumber_handler
            : false;
    }


    /**
     * Support method for getItemStatuses() -- process a single bibliographic record
     * for location settings other than "group".
     *
     * @param array  $record            Information on items linked to a single bib
     *                                  record
     * @param array  $messages          Custom status HTML
     *                                  (keys = available/unavailable)
     * @param string $locationSetting   The location mode setting used for
     *                                  pickValue()
     * @param string $callnumberSetting The callnumber mode setting used for
     *                                  pickValue()
     *
     * @return array                    Summarized availability information
     */
    protected function getItemStatus($record, $messages, $locationSetting,
        $callnumberSetting
    ) {
        // Summarize call number, location and availability info across all items:
        $callNumbers = $locations = [];
        $use_unknown_status = $available = false;
        $services = [];

        foreach ($record as $info) {
            // Find an available copy
            if ($info['availability']) {
                $available = true;
            }
            // Check for a use_unknown_message flag
            if (isset($info['use_unknown_message'])
                && $info['use_unknown_message'] == true
            ) {
                $use_unknown_status = true;
            }
            // Store call number/location info:
            $callNumbers[] = $info['callnumber'];
            $locations[] = $info['location'];
            // Store all available services
            if (isset($info['services'])) {
                $services = array_merge($services, $info['services']);
            }
        }

        $callnumberHandler = $this->getCallnumberHandler(
            $callNumbers, $callnumberSetting
        );

        // Determine call number string based on findings:
        $callNumber = $this->pickValue(
            $callNumbers, $callnumberSetting, 'Multiple Call Numbers'
        );

        // Determine location string based on findings:
        $location = $this->pickValue(
            $locations, $locationSetting, 'Multiple Locations', 'location_'
        );

        if (!empty($services)) {
            $availability_message = $this->reduceServices($services);
        } else {
            $availability_message = $use_unknown_status
                ? $messages['unknown']
                : $messages[$available ? 'available' : 'unavailable'];
        }

        // Send back the collected details:
        return [
            'id' => $record[0]['id'],
            'availability' => ($available ? 'true' : 'false'),
            'availability_message' => $availability_message,
            'location' => htmlentities($location, ENT_COMPAT, 'UTF-8'),
            'locationList' => false,
            'reserve' =>
                ($record[0]['reserve'] == 'Y' ? 'true' : 'false'),
            'reserve_message' => $record[0]['reserve'] == 'Y'
                ? $this->translate('on_reserve')
                : $this->translate('Not On Reserve'),
            'callnumber' => htmlentities($callNumber, ENT_COMPAT, 'UTF-8'),
            'callnumber_handler' => $callnumberHandler
        ];
    }

    /**
     * Send output data and exit.
     *
     * @param mixed  $data     The response data
     * @param string $status   Status of the request
     * @param int    $httpCode A custom HTTP Status Code
     *
     * @return \Zend\Http\Response
     * @throws \Exception
     */
    protected function output($data, $status, $httpCode = null)
    {
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Cache-Control', 'no-cache, must-revalidate');
        $headers->addHeaderLine('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
        if ($httpCode !== null) {
            $response->setStatusCode($httpCode);
        }
        if ($this->outputMode == 'json') {
            $headers->addHeaderLine('Content-type', 'application/javascript');
            $output = ['data' => $data, 'status' => $status];
            if ('development' == APPLICATION_ENV && count(self::$php_errors) > 0) {
                $output['php_errors'] = self::$php_errors;
            }
            $response->setContent(json_encode($output));
            return $response;
        } else if ($this->outputMode == 'plaintext') {
            $headers->addHeaderLine('Content-type', 'text/plain');
            $response->setContent($data ? $status . " $data" : $status);
            return $response;
        } else {
            throw new \Exception('Unsupported output mode: ' . $this->outputMode);
        }
    }

}
