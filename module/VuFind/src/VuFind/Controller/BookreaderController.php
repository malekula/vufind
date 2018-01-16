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
 * Bookreader Class
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
     * Display Bookreader.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function viewerAction()
    {
        $layout = $this->layout();
        $layout->setTemplate('layout/bookreader');
        $view = $this->createViewModel();
        //$view->setTerminal(true);
        return $view;
    }

    public function cryptAction()
    {
        return $this->output($result);
    }
}
