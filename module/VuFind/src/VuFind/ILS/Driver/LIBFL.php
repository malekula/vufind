<?php
/**
 * Lightweight Dummy ILS Driver -- Always returns hard-coded sample values.
 *
 * PHP version 5
 *
 * Copyright (C) Villanova University 2007.
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
 * @package  ILS_Drivers
 * @author   Andrew S. Nagy <vufind-tech@lists.sourceforge.net>
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:plugins:ils_drivers Wiki
 */
namespace VuFind\ILS\Driver;

/**
 * Lightweight Dummy ILS Driver -- Always returns hard-coded sample values.
 *
 * @category VuFind
 * @package  ILS_Drivers
 * @author   Andrew S. Nagy <vufind-tech@lists.sourceforge.net>
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:plugins:ils_drivers Wiki
 */
class LIBFL extends AbstractBase
{
    protected $soap;

    /**
     * Initialize the driver.
     *
     * Validate configuration and perform all resource-intensive tasks needed to
     * make the driver active.
     *
     * @throws ILSException
     * @return void
     */
    public function init()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        $this->soap = $client = new \SoapClient("http://opac.libfl.ru/LIBFLDataProviderAPI/service.asmx?WSDL");
    }

    /**
     * Get Status
     *
     * This is responsible for retrieving the status information of a certain
     * record.
     *
     * @param string $id The record id to retrieve the holdings for
     *
     * @return mixed     On success, an associative array with the following keys:
     * id, availability (boolean), status, location, reserve, callnumber.
     */
    public function getStatus($id)
    {
        return [];
    }

    /**
     * Get Statuses
     *
     * This is responsible for retrieving the status information for a
     * collection of records.
     *
     * @param array $ids The array of record ids to retrieve the status for
     *
     * @return mixed     An array of getStatus() return values on success.
     */
    public function getStatuses($bookID)
    {
        try {
            $status = $this->soap->GetBookStatus(array("id"=>$bookID));
        } catch (Exception $e) {
            $status = $e->getMessage();
        }
        return $status;
    }

    /**
     * Get Exemplar Statuses
     *
     * This is responsible for retrieving the status information for a
     * exemplars of records.
     *
     * @param array $ids The array of record ids to retrieve the status for
     *
     * @return mixed     An array of getStatus() return values on success.
     */
    public function getExemplarStatuses($exemplarID, $fund)
    {
        try {
            $status = $this->soap->GetExemplarStatus(array("IDDATA"=>$exemplarID, "BaseName"=>strtoupper($fund)));
        } catch (Exception $e) {
            $status = $e->getMessage();
        }
        return $status;
    }

    /**
     * Get Group Exemplar Statuses
     *
     * This is responsible for retrieving the status information for a
     * exemplars of records.
     *
     * @param array $ids The array of record ids to retrieve the status for
     *
     * @return mixed     An array of getStatus() return values on success.
     */
    public function getGroupExemplarStatuses($exemplarGroupID, $fund)
    {
        $exemplarIDs = explode('.', $exemplarGroupID);
        $groupStatus = (object)[];
        $statuses = array();
        foreach ($exemplarIDs as $id=>$exemplarID) {
            try {
                $status = $this->soap->GetExemplarStatus(array("IDDATA"=>$exemplarID, "BaseName"=>strtoupper($fund)));
                $result = json_decode($status->GetExemplarStatusResult);
                if (!in_array($result->availability, $statuses)) {
                    $statuses[] = $result->availability;
                }
                $exemplar_id = 'exemplar';
                $groupStatus->$exemplar_id[$exemplarID] = $result->availability;
            } catch (Exception $e) {
                $status = $e->getMessage();
            }
        }
        $groupStatus->id = $exemplarGroupID;
        //$groupStatus->availability = (in_array('available', $statuses)) ? 'available' : (in_array('busy', $statuses)) ? 'busy' : 'unavailable';
        if (in_array('available', $statuses)) {
            $groupStatus->availability = 'available';
        } else if (in_array('unavailable', $statuses)) {
            $groupStatus->availability = 'unavailable';
        } else if (in_array('busy', $statuses)) {
            $groupStatus->availability = 'busy';
        } else {
            $groupStatus->availability = 'unknown';
        }
        return $groupStatus;
    }

    /**
     * Get Holding
     *
     * This is responsible for retrieving the holding information of a certain
     * record.
     *
     * @param string $id     The record id to retrieve the holdings for
     * @param array  $patron Patron data
     *
     * @return mixed     On success, an associative array with the following keys:
     * id, availability (boolean), status, location, reserve, callnumber, duedate,
     * number, barcode.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getHolding($id, array $patron = null)
    {
        return [];
    }

    /**
     * Get Purchase History
     *
     * This is responsible for retrieving the acquisitions history data for the
     * specific record (usually recently received issues of a serial).
     *
     * @param string $id The record id to retrieve the info for
     *
     * @return mixed     An array with the acquisitions data on success.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getPurchaseHistory($id)
    {
        return [];
    }
}
