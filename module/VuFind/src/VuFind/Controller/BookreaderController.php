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

use Zend\Http\Client;

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
        $bookID = $this->params()->fromQuery('bookID', null);

        // $view->setVariable('bookID', $this->params()->fromQuery('bookID',null));
        $copyright_protection = null;
        $client = new Client('http://80.250.173.142/ALISAPI/Books/' . $bookID, array(
            'maxredirects' => 0,
            'timeout' => 30
        ));
        $client->setMethod('GET');
        $response = $client->send();
        if ($response->isSuccess()) {
            $bookInfo = json_decode($response->getBody());
            foreach ($bookInfo->Exemplars as $exemplars) {
                if ($exemplars->MethodOfAccessCode == 4002) {
                    if ($exemplars->AccessCode == 1001) {
                        $copyright_protection = false;
                    } else {
                        $copyright_protection = true;
                    }
                }
            }
        } else {
            $layout->setTemplate('layout/layout');
            $view->setTemplate('bookreader/error.phtml');
            $view->setVariable('error_code', 'E001');
            $view->setVariable('error_msg', 'isSuccess failed');
            return $view;
        }

        if (is_null($copyright_protection)) {
            // У книги нет электронных экземпляров
            // $layout->setTemplate('layout/layout');
            // $view->setTemplate('bookreader/error.phtml');
            // $view->setVariable('error_code', 'E001');
            // return $view;
        } else if ($copyright_protection) {
            $cookie = $this->getRequest()->getCookie();
            if (!$cookie->offsetExists('ReaderToken')) {
                return $this->redirect()->toUrl('//dev-oauth.libfl.ru');
            } else {
                $readerToken = $cookie->offsetGet('ReaderToken');
            }

            // if ($this->getRequest()->getCookie('ReaderToken')) {
            //     echo $this->getRequest()->getCookie('ReaderToken');
            //     // return $this->redirect()->toUrl('//dev-oauth.libfl.ru');
            // }
            $orderID = $this->params()->fromQuery('OrderId', null);
            $client = new Client('http://80.250.173.142/ALISAPI/Circulation/Orders/ById/' . $orderID, array(
                'maxredirects' => 0,
                'timeout' => 30
            ));
            $client->setMethod('GET');
            $response = $client->send();
            if ($response->isSuccess()) {
                $orderInfo = json_decode($response->getBody());
                $client = new Client('https://dev-oauth.libfl.ru/api/getUser', array(
                    'maxredirects' => 0,
                    'timeout' => 30
                ));
                $client->setMethod('POST');
                $headers = $client->getRequest()->getHeaders();
                $headers->addHeaderLine('Authorization', 'Bearer ' . $readerToken);
                $oauth_response = $client->send();
                if ($oauth_response->isSuccess()) {
                    $userInfo = json_decode($oauth_response->getBody());
                    if ($userInfo->ReaderId != $orderInfo->ReaderId) {
                        // TODO: Подумать над действием. Это значит что номер заказа не соответствует книге / читателю.
                        // return $this->redirect()->toUrl('//dev-oauth.libfl.ru');
                    }
                } else {
                    // echo 'Request error';
                    $layout->setTemplate('layout/layout');
                    $view->setTemplate('bookreader/error.phtml');
                    $view->setVariable('error_code', 'E001');
                    $view->setVariable('error_msg', 'isSuccess failed 2');
                    return $view;
                }
                // echo '<pre>';
                // print_r(json_decode($response->getBody()));
                // echo '</pre>';
                $view->setVariable('bookInfo', json_encode($bookInfo));
                $view->setVariable('orderInfo', $response->getBody());
            } else {
                // echo 'Request is failed #2';
                $layout->setTemplate('layout/layout');
                $view->setTemplate('bookreader/error.phtml');
                $view->setVariable('error_code', 'E001');
                $view->setVariable('error_msg', 'isSuccess failed 3');
                return $view;
            }
        } else {
            $view->setVariable('bookInfo', json_encode($bookInfo));
        }

        $client = new Client('http://80.250.173.142/ALISAPI/Books/ElectronicCopy/' . $bookID, array(
            'maxredirects' => 0,
            'timeout' => 30
        ));
        $client->setMethod('GET');
        $response = $client->send();
        if ($response->isSuccess()) {
            $view->setVariable('exemplar', $response->getBody());
        } else {
            // echo 'Что-то пошло не так...' . $bookID;
            $layout->setTemplate('layout/layout');
            $view->setTemplate('bookreader/error.phtml');
            $view->setVariable('error_code', 'E001');
            $view->setVariable('error_msg', 'isSuccess failed 4');
            return $view;
        }

        return $view;
    }

    public function cryptAction()
    {
        return $this->output($result);
    }
}
