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

    protected $_bookID;
    protected $_orderID;
    protected $_CP = null; // Copyright protection

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
                $debugMsg = ('development' == APPLICATION_ENV) ? ': ' . $e->getMessage() : '';
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
        $this->_bookID = $this->params()->fromQuery('bookID', null);
        $this->_orderID = $this->params()->fromQuery('OrderId', null);

        $view = $this->createViewModel();

        if ($this->_bookID) {
            $client = new Client('http://80.250.173.142/ALISAPI/Books/' . $this->_bookID, array(
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
                            // Книга в свободном доступе
                            $view->setVariable('bookInfo', json_encode($bookInfo));
                            $client = new Client('http://80.250.173.142/ALISAPI/Books/ElectronicCopy/' . $this->_bookID, array(
                                'maxredirects' => 0,
                                'timeout' => 30
                            ));
                            $client->setMethod('GET');
                            $response = $client->send();
                            if ($response->isSuccess()) {
                                $view->setVariable('exemplar', $response->getBody());
                            } else {
                                $layout->setTemplate('layout/layout');
                                $view->setTemplate('bookreader/error.phtml');
                                $view->setVariable('error_code', 'E001');
                                $view->setVariable('error_msg', 'Не удалось получить электронную копию книги.');
                                return $view;
                            }
                            return $view;
                        } else {
                            // Книга защищена авторским правом
                            $layout->setTemplate('layout/layout');
                            $view->setTemplate('bookreader/error.phtml');
                            $view->setVariable('error_code', 'E001');
                            $view->setVariable('error_msg', 'Не удалось получить данные об экзмеплярах книги.');
                            return $view;
                        }
                    } else {
                        $layout->setTemplate('layout/layout');
                        $view->setTemplate('bookreader/error.phtml');
                        $view->setVariable('error_code', 'E001');
                        $view->setVariable('error_msg', 'У книги нет электронных экземпляров.');
                        return $view;
                    }
                }
            } else {
                $layout->setTemplate('layout/layout');
                $view->setTemplate('bookreader/error.phtml');
                $view->setVariable('error_code', 'E001');
                $view->setVariable('error_msg', 'Не удалось получить данные об экзмеплярах книги.');
                return $view;
            }
        }


        if ($this->_orderID) {
            $cookie = $this->getRequest()->getCookie();
            if (!$cookie->offsetExists('ReaderToken')) {
                return $this->redirect()->toUrl('//dev-oauth.libfl.ru');
            } else {
                $readerToken = $cookie->offsetGet('ReaderToken');
            }
            $client = new Client('http://80.250.173.142/ALISAPI/Circulation/Orders/ById/' . $this->_orderID, array(
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
                        $layout->setTemplate('layout/layout');
                        $view->setTemplate('bookreader/error.phtml');
                        $view->setVariable('error_code', 'E001');
                        $view->setVariable('error_msg', 'Книга закреплена за другой учетной записью.');
                        return $view;
                    }
                } else {
                    // echo 'Request error';
                    $layout->setTemplate('layout/layout');
                    $view->setTemplate('bookreader/error.phtml');
                    $view->setVariable('error_code', 'E001');
                    $view->setVariable('error_msg', 'Не удалось аутентифицировать пользователя.');
                    return $view;
                }
                $view->setVariable('bookInfo', json_encode($orderInfo->Book));
                $client = new Client('http://80.250.173.142/ALISAPI/Books/ElectronicCopy/' . $orderInfo->Book->ID, array(
                    'maxredirects' => 0,
                    'timeout' => 30
                ));
                $client->setMethod('GET');
                $response = $client->send();
                if ($response->isSuccess()) {
                    $view->setVariable('exemplar', $response->getBody());
                } else {
                    $layout->setTemplate('layout/layout');
                    $view->setTemplate('bookreader/error.phtml');
                    $view->setVariable('error_code', 'E001');
                    $view->setVariable('error_msg', 'Не удалось получить электронную копию книги.');
                    return $view;
                }
                return $view;
            } else {
                $layout->setTemplate('layout/layout');
                $view->setTemplate('bookreader/error.phtml');
                $view->setVariable('error_code', 'E001');
                $view->setVariable('error_msg', 'Не удалось получить данные о заказе.');
                return $view;
            }
        }

        /*else {
            $view->setVariable('bookInfo', json_encode($bookInfo));
        }

        $client = new Client('http://80.250.173.142/ALISAPI/Books/ElectronicCopy/' . $this->_bookID, array(
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
            $view->setVariable('error_msg', 'Не удалось получить электронную копию книги.');
            return $view;
        }

        return $view;*/
    }

    public function cryptAction()
    {
        return $this->output($result);
    }
}
