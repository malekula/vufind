<?php
/**
 * Book Reader Controller
 *
 * PHP version 5
 *
 * @category VuFind
 * @package  Controller
 * @author   Maksim Kuleba <maksim.kuleba@gmail.com>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org Main Site
 */
namespace VuFind\Controller;

/**
 * Report Error Class
 *
 * Controls the report error
 *
 * @category VuFind
 * @package  Controller
 * @author   Maksim Kuleba <maksim.kuleba@gmail.com>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development Wiki
 */
class BookreaderController extends AbstractBase
{
    /**
     * Display Report Error home form.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function viewerAction()
    { 
        $view = $this->createViewModel();
        return $view;
    }
}
