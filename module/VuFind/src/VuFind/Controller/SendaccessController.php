<?php
/**
 * Send Access Controller
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
use Zend\Mail\Address;

/**
 * Send Access Class
 *
 * Controls the send access to e-mail
 *
 * @category VuFind
 * @package  Controller
 * @author   Maksim Kuleba <maksim.kuleba@gmail.com>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development Wiki
 */
class SendaccessController extends AbstractBase
{
    protected $link_registration = "http://80.250.173.142/WebReaderT";
    protected $link_login = "http://80.250.173.142/personal/loginemployee.aspx";
    protected $link_contactsLibrary = "https://libfl.ru/ru/item/contacts";

    /**
     * Display send access form.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function homeAction()
    {
        return $this->forwardTo('Sendaccess', 'Develop');
    }

    /**
     * Display send access form.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function accessAction()
    {
        return $this->forwardTo('Sendaccess', 'Bookaccess');
    }

    /**
     * Display send bookinfo form.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function infoAction()
    {
        return $this->forwardTo('Sendaccess', 'Bookinfo');
    }

    public function developAction()
    {
        $view = $this->createViewModel();
        return $view;
    }

    public function bookaccessAction()
    {
        $view = $this->createViewModel();
        $view->readerName = $this->params()->fromPost('name');
        $view->readerEmail = $this->params()->fromPost('email');
        $view->readerQuestion = $this->params()->fromPost('question');
        $view->bookInfo = $this->params()->fromPost('bookinfo');

        if ($this->formWasSubmitted('submit', $view->useRecaptcha)) {

            if (empty($view->readerEmail)) {
                $this->flashMessenger()->addMessage('bulk_error_missing', 'error');
                return;
            }
            $config = $this->serviceLocator->get('VuFind\Config')->get('config');
            $sendAccess = isset($config->Sendaccess) ? $config->Sendaccess : null;

            $bookInfo = !empty($view->bookInfo) ? json_decode(urldecode($view->bookInfo), JSON_OBJECT_AS_ARRAY) : "";
            // Staff
            $recipient_email = isset($sendAccess->recipient_email) ? $sendAccess->recipient_email : 'maksim.kuleba@gmail.com'; // E-mail получателя (сотрудника)
            $recipient_name = isset($sendAccess->recipient_name) ? $sendAccess->recipient_name : 'Recipient name'; // Имя получателя
            $email_subject = isset($sendAccess->email_subject) ? date("d.m.Y H:i").' '.$sendAccess->email_subject : date("Y.m.d H:i") . ' запрос доступа.'; //Тема письма
            $sender_email = isset($sendAccess->sender_email) ? $sendAccess->sender_email : 'vufind-noreply@libfl.ru'; // E-mail отправителя
            $sender_name = isset($sendAccess->sender_name) ? $sendAccess->sender_name : 'ВГБИЛ'; // Имя отправителя

            // Reader
            $readerName = $view->readerName;
            $readerEmail = $view->readerEmail;
            $readerQuestion = $view->readerQuestion;
            $readerEmailSubject = '[LIBFL.RU ' . date("Y.m.d H:i") . '] ' . trim(mb_substr($bookInfo['title'], 0, 30)) . '... запрос о доступе.';

            $staff_email_message = "<p>ЗАПРОС ДОСТУПА. Письмо для <b>СОТРУДНИКА!</b>.</p>"
                            ."<p>" . $readerQuestion . "</p>"
                            ."<p><b>Автор: </b>" . $bookInfo['author'][0] . "</p>"
                            ."<p><b>Название книги: </b>" . $bookInfo['title'] . "</p>"
                            ."<p><b>URL: </b>" . $bookInfo['url'] . "</p><br/>"
                            ."<p><b>ИНФОРМАЦИЯ О ЧИТАТЕЛЕ:</b></p>"
                            ."<p><b>E-mail пользователя: </b>" . $readerEmail . "</p>"
                            ."<p><b>Имя: </b>" . $readerName . "</p><br/>";

            $reader_email_message = "<p>ЗАПРОС ДОСТУПА. Письмо для <b>ЧИТАТЕЛЯ!</b></p>"
                            ."<p>Уважаемый, " . $readerName . "</p>"
                            ."<p>Мы получили Ваш запрос</p>"
                            ."<p>" . $readerQuestion . "</p>"
                            ."<p><b>Автор: </b>" . $bookInfo['author'][0] . "</p>"
                            ."<p><b>Название книги: </b>" . $bookInfo['title'] . "</p>"
                            ."<p><b>URL: </b>" . $bookInfo['url'] . "</p><br/>"
                            ."<table>";
                            foreach ($bookInfo['locations'] as $location => $inv_numbers) {
                                //$reader_email_message .= "<span>".$this->translate($location)."</span><span>".implode(', ', $inv_numbers)."</span><br>";
                                $reader_email_message .= "<tr style='margin: 0px 50px;'>";
                                $reader_email_message .= "<td style='font-size:14px; padding:8px;'><a href='#' target='_blank' style='color:#0000f3; text-decoration:none;'>".$this->translate($location)."</a></td>";
                                $reader_email_message .= "<td style='font-size:14px; padding:8px; text-align:center;'>";
                                    foreach ($inv_numbers as $inv_number) {
                                        $reader_email_message .= "<span style='padding: 0px 7px 0px 0px;'>".$inv_number."</span>";
                                    }
                                $reader_email_message .= "</td>";
                                $reader_email_message .= "</tr>";
                                $reader_email_message .= "<tr>"
                                ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                ."</tr>";
                            }
                            $reader_email_message .= "</table>"
                            ."<p>График работы библиотеки</p>"
                            ."<p>Схема проезда</p>"
                            ."<p>Социальные сети</p>";

            try {
                $mailer = $this->serviceLocator->get('VuFind\Mailer');
                //$mailer->addHeader('Content-Type', 'text/html');
                $mailer->send(
                    new Address($recipient_email, $recipient_name),
                    new Address($sender_email, $sender_name),
                    $email_subject, $staff_email_message
                );
                $mailer->send(
                    new Address($readerEmail, $readerName),
                    new Address($sender_email, $sender_name),
                    $readerEmailSubject, $reader_email_message
                );
                $this->flashMessenger()->addMessage(
                    'send_bookinfo_success', 'success'
                );
            } catch (MailException $e) {
                $this->flashMessenger()->addMessage($e->getMessage(), 'error');
            }
        }
        return $view;
    }

    public function bookinfoAction()
    {
        $view = $this->createViewModel();
        $view->readerName = $this->params()->fromPost('name');
        $view->readerEmail = $this->params()->fromPost('email');
        $view->bookInfo = $this->params()->fromPost('bookinfo');

        if ($this->formWasSubmitted('submit', $view->useRecaptcha)) {

            if (empty($view->readerEmail)) {
                $this->flashMessenger()->addMessage('bulk_error_missing', 'error');
                return;
            }
            $config = $this->serviceLocator->get('VuFind\Config')->get('config');
            $sendAccess = isset($config->Sendaccess) ? $config->Sendaccess : null;

            $bookInfo = !empty($view->bookInfo) ? json_decode(urldecode($view->bookInfo), JSON_OBJECT_AS_ARRAY) : "";
            // Staff
            $recipient_email = isset($sendAccess->recipient_email) ? $sendAccess->recipient_email : 'maksim.kuleba@gmail.com'; // E-mail получателя (сотрудника)
            $recipient_name = isset($sendAccess->recipient_name) ? $sendAccess->recipient_name : 'Recipient name'; // Имя получателя
            $email_subject = isset($sendAccess->email_subject) ? date("d.m.Y H:i").' '.$sendAccess->email_subject : date("Y.m.d H:i") . ' запрос информации.'; //Тема письма
            $sender_email = isset($sendAccess->sender_email) ? $sendAccess->sender_email : 'vufind-noreply@libfl.ru'; // E-mail отправителя
            $sender_name = isset($sendAccess->sender_name) ? $sendAccess->sender_name : 'ВГБИЛ'; // Имя отправителя

            // Reader
            $readerName = $view->readerName;
            $readerEmail = $view->readerEmail;
            $readerEmailSubject = '[LIBFL.RU ' . date("Y.m.d H:i") . '] ' . trim(mb_substr($bookInfo['title'], 0, 30)) . '... информация о доступе.';


            $staff_email_message = "<p>ИНФОРМАЦИЯ О КНИГЕ. Письмо для <b>СОТРУДНИКА!</b>.</p>"
                            ."<p><b>Читатель: </b></p>"
                            ."<p><b>E-mail пользователя: </b>" . $readerEmail . "</p>"
                            ."<p><b>Имя: </b>" . $readerName . "</p><br/>"
                            ."<p><b>Запросил информацию о книге:</b></p>"
                            ."<p><b>Название книги: </b>" . $bookInfo['title'] . "</p>"
                            ."<p><b>Автор: </b>" . $bookInfo['author'][0] . "</p>"
                            ."<p><b>URL: </b>" . $bookInfo['url'] . "</p><br/>";

            $reader_email_message = "<p>ИНФОРМАЦИЯ О КНИГЕ. Письмо для <b>ЧИТАТЕЛЯ!</b></p>"
                            ."<p><b>Автор: </b>" . $bookInfo['author'][0] . "</p>"
                            ."<p><b>Название книги: </b>" . $bookInfo['title'] . "</p>"
                            ."<p><b>URL: </b>" . $bookInfo['url'] . "</p><br/>"
                            ."<table>";
                            foreach ($bookInfo['locations'] as $location => $inv_numbers) {
                                //$reader_email_message .= "<span>".$this->translate($location)."</span><span>".implode(', ', $inv_numbers)."</span><br>";
                                $reader_email_message .= "<tr style='margin: 0px 50px;'>";
                                $reader_email_message .= "<td style='font-size:14px; padding:8px;'><a href='#' target='_blank' style='color:#0000f3; text-decoration:none;'>".$this->translate($location)."</a></td>";
                                $reader_email_message .= "<td style='font-size:14px; padding:8px; text-align:center;'>";
                                    foreach ($inv_numbers as $inv_number) {
                                        $reader_email_message .= "<span style='padding: 0px 7px 0px 0px;'>".$inv_number."</span>";
                                    }
                                $reader_email_message .= "</td>";
                                $reader_email_message .= "</tr>";
                                $reader_email_message .= "<tr>"
                                ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                ."</tr>";
                            }
                            $reader_email_message .= "</table>"
                            ."<p>График работы библиотеки</p>"
                            ."<p>Схема проезда</p>"
                            ."<p>Социальные сети</p>";

            try {
                $mailer = $this->serviceLocator->get('VuFind\Mailer');
                //$mailer->addHeader('Content-Type', 'text/html');
                $mailer->send(
                    new Address($recipient_email, $recipient_name),
                    new Address($sender_email, $sender_name),
                    $email_subject, $staff_email_message
                );
                $mailer->send(
                    new Address($readerEmail, $readerName),
                    new Address($sender_email, $sender_name),
                    $readerEmailSubject, $reader_email_message
                );
                $this->flashMessenger()->addMessage(
                    'send_bookinfo_success', 'success'
                );
            } catch (MailException $e) {
                $this->flashMessenger()->addMessage($e->getMessage(), 'error');
            }
        }
        return $view;
    }
}
