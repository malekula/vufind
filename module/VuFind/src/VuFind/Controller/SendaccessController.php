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
    /**
     * Display send access form.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function homeAction()
    {
        return $this->forwardTo('Sendaccess', 'Email');
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

    /**
     * Receives input from the user and sends an email to the recipient set in
     * the config.ini
     *
     * @return void
     */
    public function emailAction()
    {
        $link_registration = "https://opac.libfl.ru/WebReaderT";
        $link_login = "https://opac.libfl.ru/personal/loginemployee.aspx";
        $link_contactsLibrary = "https://libfl.ru/ru/item/contacts";

        $view = $this->createViewModel();
        $view->name = $this->params()->fromPost('name');
        $view->email = $this->params()->fromPost('email');
        $view->accessInfo = $this->params()->fromPost('access_info');

        if ($this->formWasSubmitted('submit', $view->useRecaptcha)) {

            if (empty($view->email)) {
                $this->flashMessenger()->addMessage('bulk_error_missing', 'error');
                return;
            }
            $config = $this->serviceLocator->get('VuFind\Config')->get('config');
            $sendAccess = isset($config->Sendaccess) ? $config->Sendaccess : null;
            // Staff
            $recipient_email = isset($sendAccess->recipient_email) ? $sendAccess->recipient_email : 'maksim.kuleba@gmail.com'; // E-mail получателя
            $recipient_name = isset($sendAccess->recipient_name) ? $sendAccess->recipient_name : 'Максим'; // Имя получателя
            $email_subject = isset($sendAccess->email_subject) ? date("d.m.Y H:i").' '.$sendAccess->email_subject : date("d.m.Y H:i").' Способы доступа'; //Тема письма
            $sender_email = isset($sendAccess->sender_email) ? $sendAccess->sender_email : 'vufind-noreply@libfl.ru'; // E-mail отправителя
            $sender_name = isset($sendAccess->sender_name) ? $sendAccess->sender_name : 'ВГБИЛ'; // Имя отправителя

            /*$email_message = empty($view->name) ? "" : "<b>" . $this->translate('re_name') . ":</b> " . $view->name . "<br/>";
            $email_message .= empty($view->email) ? '' : "<b>" . $this->translate('re_email') . ":</b> " . $view->email . "<br/>";
            $email_message .= empty($view->comments) ? '' : "<b>" . $this->translate('re_comments') . ":</b><br/> " . $view->comments . "<br/><br/>";
            $email_message .= "<b><u>" . mb_strtoupper($this->translate('re_additional_info')) . ":</u></b><br/>";
            $email_message .= empty($view->date) ? '' : "<b>" . $this->translate('re_date') . ":</b> " . date('d.m.Y H:i')."<br/>";
            $email_message .= empty($view->os) ? '' : "<b>" . $this->translate('re_os') . ":</b> " . $view->os."<br/>";
            $email_message .= empty($view->browser) ? '' : "<b>" . $this->translate('re_browser') . ":</b> " . $view->browser."<br/>";
            $email_message .= empty($view->scrResolution) ? '' : "<b>" . $this->translate('re_screen_resolution') . ":</b> " . $view->scrResolution."<br/>";
            $email_message .= empty($view->url) ? '' : "<b>" . $this->translate('re_page_url') . ":</b> " . $view->url . "<br/>";
            $email_message .= "<br/>";
            $email_message .= "<b>User agent:</b> " . filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');*/

            $accessInfo = !empty($view->accessInfo) ? json_decode($view->accessInfo, JSON_OBJECT_AS_ARRAY) : "";
            $email_message = "<table bgcolor='#dfe7ee' border='0' cellpadding='0' cellspacing='0' style='margin:0; padding:0' width='100%'>"
                                ."<tr>"
                                    ."<td height='100%' style='padding: 20px 0px;'>"
                                        ."<table border='0' cellpadding='0' cellspacing='0' style='max-width:600px; margin:0 auto; padding:0' bgcolor='#ffffff'>"
                                            ."<tbody>"
                                                ."<tr>"
                                                    ."<td style='padding: 50px 0px;'>"
                                                        ."<a href='https://libfl.ru/ru' target='_blank'>"
                                                            ."<img src='https://cdn.libfl.ru:6684/images/email/libfl-animated-logo.gif' alt='Библиотека иностранной литературы' border='0' width='120' height='201' style='display: block; margin: 0 auto'>"
                                                        ."</a>"
                                                    ."</td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td style='padding: 30px 20px 0;text-align: center'>"
                                                        ."<span style='font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:normal; color:#000000; font-size:26px; line-height:1.3'>Вы можете получить книгу <b>".$accessInfo['title']."</b> в одном из залов Библиотеки.</span><br><br>"
                                                        ."<span style='font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:normal; color:#000000; font-size:18px; line-height:1.3'>Сообщите сотруднику <b>инвентарный номер</b> книги:</span>"
                                                    ."</td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td>"
                                                        ."<table width='70%' border='0' cellpadding='0' cellspacing='0' style='margin:30px auto;padding:0'>"
                                                            ."<tbody>"
                                                                ."<tr style='margin: 0 50px;'>"
                                                                    ."<th style='text-align:left; color: #d0cfcf; font-size: 12px; font-weight: 300; padding: 8px;'>Местонахождение в библиотеке</th>"
                                                                    ."<th style='text-align:left; color: #d0cfcf; font-size: 12px; font-weight: 300; padding: 8px;'>Инвентарный номер</th>"
                                                                ."</tr>"
                                                                ."<tr>"
                                                                    ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                                                    ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                                                ."</tr>";
                                                                foreach ($accessInfo['locations'] as $location => $inv_numbers) {
                                                                    $email_message .= "<tr style='margin: 0px 50px;'>";
                                                                    /*if ($location == 'access_locationClarify') {
                                                                        $email_message .= "<td style='font-size:14px; padding:8px;'>".$this->translate($location)."</td>";
                                                                    } else {
                                                                        $email_message .= "<td style='font-size:14px; padding:8px;'><a href='".$link_contactsLibrary."' target='_blank' style='color:#0000f3; text-decoration:none;'>".$this->translate($location)."</a></td>";
                                                                    }*/
                                                                    $email_message .= "<td style='font-size:14px; padding:8px;'><a href='#' target='_blank' style='color:#0000f3; text-decoration:none;'>".$this->translate($location)."</a></td>";
                                                                    $email_message .= "<td style='font-size:14px; padding:8px; text-align:center;'>";
                                                                        foreach ($inv_numbers as $inv_number) {
                                                                            $email_message .= "<span style='padding: 0px 7px 0px 0px;'>".$inv_number."</span>";
                                                                        }
                                                                    $email_message .= "</td>";
                                                                    $email_message .= "</tr>";
                                                                    $email_message .= "<tr>"
                                                                    ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                                                    ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                                                    ."</tr>";
                                                                }
                                                                //."<tr style='margin: 0 50px;'>"
                                                                    //."<td style='font-size: 14px; padding: 8px;'><a href='#' style='color: #0000f3; text-decoration: none;'>Центр американской культуры (3 этаж)</a></td>"
                                                                    //."<td style='font-size: 14px; padding: 8px;'>b33333</td>"
                                                                //."</tr>"
                                                                //."<tr>"
                                                                    //."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                                                    //."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                                                //."</tr>"
                                                                //."<tr style='margin: 0 50px;'>"
                                                                    //."<td style='font-size: 14px; padding: 8px;'><a href='#' style='color: #0000f3; text-decoration: none;'>Детский зал (2 этаж)</a></td>"
                                                                    //."<td style='font-size: 14px; padding: 8px;'>c11111</td>"
                                                                //."</tr>"
                                                                //."<tr>"
                                                                    //."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                                                    //."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                                                //."</tr>"
                                                            $email_message .= "</tbody>"
                                                        ."</table>"
                                                    ."</td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td style='padding: 0 0 30px;text-align: center'>"
                                                        ."<a href='#' style='display:inline-block;margin:0 0 30px;background-color:#0000f3;color:#ffffff;font-size:16px;letter-spacing:0.01;text-decoration: none; padding: 15px 30px;border-radius: 3px;'>Перейти к книге</a>"
                                                    ."</td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td height='39px' style='padding: 30px 0;'>"
                                                        ."<table border='0' cellpadding='0' cellspacing='0' style='margin: 0 auto;'>"
                                                            ."<tbody>"
                                                                ."<tr>"
                                                                    ."<td style='padding: 0 5px;'>"
                                                                        ."<a href='https://vk.com/public59877134' target='_blank' style='display: inline-block; text-decoration: none;'>"
                                                                            ."<img width='39' height='39' style='width: auto; height: 39px;' src='https://cdn.libfl.ru:6684/images/email/vk.png' alt='ВКонтакте'>"
                                                                        ."</a>"
                                                                    ."</td>"
                                                                    ."<td style='padding: 0 5px;'>"
                                                                        ."<a href='https://www.instagram.com/libfl/' target='_blank' style='display: inline-block; text-decoration: none;'>"
                                                                            ."<img width='39' height='39' style='width: auto; height: 39px;' src='https://cdn.libfl.ru:6684/images/email/in.png' alt='Instagram'>"
                                                                        ."</a>"
                                                                    ."</td>"
                                                                    ."<td style='padding: 0 5px;'>"
                                                                        ."<a href='https://www.facebook.com/LIBFL/' target='_blank' style='display: inline-block; text-decoration: none;'>"
                                                                            ."<img width='39' height='39' style='width: auto; height: 39px;' src='https://cdn.libfl.ru:6684/images/email/fb.png' alt='Facebook'>"
                                                                        ."</a>"
                                                                    ."</td>"
                                                                    ."<td style='padding: 0 5px;'>"
                                                                        ."<a href='https://www.youtube.com/channel/UCyK_Z9yBQCcFzUZePsR5W4w' target='_blank' style='display: inline-block; text-decoration: none;'>"
                                                                            ."<img width='39' height='39' style='width: auto; height: 39px;' src='https://cdn.libfl.ru:6684/images/email/yt.png' alt='YouTube'>"
                                                                        ."</a>"
                                                                    ."</td>"
                                                                ."</tr>"
                                                            ."</tbody>"
                                                        ."</table>"
                                                    ."</td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td style='padding: 10px 20px 20px;text-align: center'>"
                                                        ."<span style='font-size: 12px;line-height: 26px;font-weight: 200;color: #787878;'>"
                                                            ."Всероссийская Государственная Библиотека Иностранной Литературы им. М.И. Рудомино<br>"
                                                            ."г. Москва, Николоямская 1 <a href='https://libfl.ru/ru/subscribe_preview#' target='_blank' style='text-decoration: none; color: #0000f3;'>посмотреть на карте</a>  |  <a href='https://libfl.ru/ru' target='_blank' style='text-decoration: none; color: #787878;'>+7 (495) 915–36–41</a>  |  <a href='https://libfl.ru/ru' target='_blank' style='text-decoration: none; color: #0000f3;'>www.libfl.ru</a>"
                                                        ."</span>"
                                                    ."</td>"
                                                ."</tr>"
                                            ."</tbody>"
                                        ."</table>"
                                        ."<table>"
                                            ."<tbody>"
                                                ."<tr>"
                                                    ."<td>"
                                                    ."</td>"
                                                ."</tr>"
                                            ."</tbody>"
                                        ."</table>"
                                    ."</td>"
                                ."</tr>"
                            ."</table>";

            try {
                $mailer = $this->serviceLocator->get('VuFind\Mailer');
                //$mailer->addHeader('Content-Type', 'text/html');
                $mailer->send(
                    new Address($recipient_email, $recipient_name),
                    new Address($sender_email, $sender_name),
                    $email_subject, $email_message
                );
                $this->flashMessenger()->addMessage(
                    'thank_you_for_your_report_error', 'success'
                );
            } catch (MailException $e) {
                $this->flashMessenger()->addMessage($e->getMessage(), 'error');
            }
        }

        return $view;
    }

    public function bookaccessAction()
    {
        $link_registration = "https://opac.libfl.ru/WebReaderT";
        $link_login = "https://opac.libfl.ru/personal/loginemployee.aspx";
        $link_contactsLibrary = "https://libfl.ru/ru/item/contacts";

        $view = $this->createViewModel();
        $view->name = $this->params()->fromPost('name');
        $view->email = $this->params()->fromPost('email');
        $view->accessInfo = $this->params()->fromPost('access_info');

        if ($this->formWasSubmitted('submit', $view->useRecaptcha)) {

            if (empty($view->email)) {
                $this->flashMessenger()->addMessage('bulk_error_missing', 'error');
                return;
            }
            $config = $this->serviceLocator->get('VuFind\Config')->get('config');
            $sendAccess = isset($config->Sendaccess) ? $config->Sendaccess : null;
            // Staff
            $recipient_email = isset($sendAccess->recipient_email) ? $sendAccess->recipient_email : 'maksim.kuleba@gmail.com'; // E-mail получателя
            $recipient_name = isset($sendAccess->recipient_name) ? $sendAccess->recipient_name : 'Максим'; // Имя получателя
            $email_subject = isset($sendAccess->email_subject) ? date("d.m.Y H:i").' '.$sendAccess->email_subject : 'LIBFL.RU ' . date("Y.m.d H:i") . ' ' . trim(mb_substr($bookInfo['title'], 0, 30)) . '... запрос о доступе.'; //Тема письма
            $sender_email = isset($sendAccess->sender_email) ? $sendAccess->sender_email : 'vufind-noreply@libfl.ru'; // E-mail отправителя
            $sender_name = isset($sendAccess->sender_name) ? $sendAccess->sender_name : 'ВГБИЛ'; // Имя отправителя

            $accessInfo = !empty($view->accessInfo) ? json_decode($view->accessInfo, JSON_OBJECT_AS_ARRAY) : "";
            $email_message = "<table bgcolor='#dfe7ee' border='0' cellpadding='0' cellspacing='0' style='margin:0; padding:0' width='100%'>"
                                ."<tr>"
                                    ."<td height='100%' style='padding: 20px 0px;'>"
                                        ."<table border='0' cellpadding='0' cellspacing='0' style='max-width:600px; margin:0 auto; padding:0' bgcolor='#ffffff'>"
                                            ."<tbody>"
                                                ."<tr>"
                                                    ."<td style='padding: 50px 0px;'>"
                                                        ."<a href='https://libfl.ru/ru' target='_blank'>"
                                                            ."<img src='https://cdn.libfl.ru:6684/images/email/libfl-animated-logo.gif' alt='Библиотека иностранной литературы' border='0' width='120' height='201' style='display: block; margin: 0 auto'>"
                                                        ."</a>"
                                                    ."</td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td style='padding: 30px 20px 0;text-align: center'>"
                                                        ."<span style='font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:normal; color:#000000; font-size:26px; line-height:1.3'>Вы можете получить книгу <b>".$accessInfo['title']."</b> в одном из залов Библиотеки.</span><br><br>"
                                                        ."<span style='font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:normal; color:#000000; font-size:18px; line-height:1.3'>Сообщите сотруднику <b>инвентарный номер</b> книги:</span>"
                                                    ."</td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td>"
                                                        ."<table width='70%' border='0' cellpadding='0' cellspacing='0' style='margin:30px auto;padding:0'>"
                                                            ."<tbody>"
                                                                ."<tr style='margin: 0 50px;'>"
                                                                    ."<th style='text-align:left; color: #d0cfcf; font-size: 12px; font-weight: 300; padding: 8px;'>Местонахождение в библиотеке</th>"
                                                                    ."<th style='text-align:left; color: #d0cfcf; font-size: 12px; font-weight: 300; padding: 8px;'>Инвентарный номер</th>"
                                                                ."</tr>"
                                                                ."<tr>"
                                                                    ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                                                    ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                                                ."</tr>";
                                                                foreach ($accessInfo['locations'] as $location => $inv_numbers) {
                                                                    $email_message .= "<tr style='margin: 0px 50px;'>";
                                                                    /*if ($location == 'access_locationClarify') {
                                                                        $email_message .= "<td style='font-size:14px; padding:8px;'>".$this->translate($location)."</td>";
                                                                    } else {
                                                                        $email_message .= "<td style='font-size:14px; padding:8px;'><a href='".$link_contactsLibrary."' target='_blank' style='color:#0000f3; text-decoration:none;'>".$this->translate($location)."</a></td>";
                                                                    }*/
                                                                    $email_message .= "<td style='font-size:14px; padding:8px;'><a href='#' target='_blank' style='color:#0000f3; text-decoration:none;'>".$this->translate($location)."</a></td>";
                                                                    $email_message .= "<td style='font-size:14px; padding:8px; text-align:center;'>";
                                                                        foreach ($inv_numbers as $inv_number) {
                                                                            $email_message .= "<span style='padding: 0px 7px 0px 0px;'>".$inv_number."</span>";
                                                                        }
                                                                    $email_message .= "</td>";
                                                                    $email_message .= "</tr>";
                                                                    $email_message .= "<tr>"
                                                                    ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                                                    ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                                                    ."</tr>";
                                                                }
                                                            $email_message .= "</tbody>"
                                                        ."</table>"
                                                    ."</td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td style='padding: 0 0 30px;text-align: center'>"
                                                        ."<a href='#' style='display:inline-block;margin:0 0 30px;background-color:#0000f3;color:#ffffff;font-size:16px;letter-spacing:0.01;text-decoration: none; padding: 15px 30px;border-radius: 3px;'>Перейти к книге</a>"
                                                    ."</td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td height='39px' style='padding: 30px 0;'>"
                                                        ."<table border='0' cellpadding='0' cellspacing='0' style='margin: 0 auto;'>"
                                                            ."<tbody>"
                                                                ."<tr>"
                                                                    ."<td style='padding: 0 5px;'>"
                                                                        ."<a href='https://vk.com/public59877134' target='_blank' style='display: inline-block; text-decoration: none;'>"
                                                                            ."<img width='39' height='39' style='width: auto; height: 39px;' src='https://cdn.libfl.ru:6684/images/email/vk.png' alt='ВКонтакте'>"
                                                                        ."</a>"
                                                                    ."</td>"
                                                                    ."<td style='padding: 0 5px;'>"
                                                                        ."<a href='https://www.instagram.com/libfl/' target='_blank' style='display: inline-block; text-decoration: none;'>"
                                                                            ."<img width='39' height='39' style='width: auto; height: 39px;' src='https://cdn.libfl.ru:6684/images/email/in.png' alt='Instagram'>"
                                                                        ."</a>"
                                                                    ."</td>"
                                                                    ."<td style='padding: 0 5px;'>"
                                                                        ."<a href='https://www.facebook.com/LIBFL/' target='_blank' style='display: inline-block; text-decoration: none;'>"
                                                                            ."<img width='39' height='39' style='width: auto; height: 39px;' src='https://cdn.libfl.ru:6684/images/email/fb.png' alt='Facebook'>"
                                                                        ."</a>"
                                                                    ."</td>"
                                                                    ."<td style='padding: 0 5px;'>"
                                                                        ."<a href='https://www.youtube.com/channel/UCyK_Z9yBQCcFzUZePsR5W4w' target='_blank' style='display: inline-block; text-decoration: none;'>"
                                                                            ."<img width='39' height='39' style='width: auto; height: 39px;' src='https://cdn.libfl.ru:6684/images/email/yt.png' alt='YouTube'>"
                                                                        ."</a>"
                                                                    ."</td>"
                                                                ."</tr>"
                                                            ."</tbody>"
                                                        ."</table>"
                                                    ."</td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td style='padding: 10px 20px 20px;text-align: center'>"
                                                        ."<span style='font-size: 12px;line-height: 26px;font-weight: 200;color: #787878;'>"
                                                            ."Всероссийская Государственная Библиотека Иностранной Литературы им. М.И. Рудомино<br>"
                                                            ."г. Москва, Николоямская 1 <a href='https://libfl.ru/ru/subscribe_preview#' target='_blank' style='text-decoration: none; color: #0000f3;'>посмотреть на карте</a>  |  <a href='https://libfl.ru/ru' target='_blank' style='text-decoration: none; color: #787878;'>+7 (495) 915–36–41</a>  |  <a href='https://libfl.ru/ru' target='_blank' style='text-decoration: none; color: #0000f3;'>www.libfl.ru</a>"
                                                        ."</span>"
                                                    ."</td>"
                                                ."</tr>"
                                            ."</tbody>"
                                        ."</table>"
                                        ."<table>"
                                            ."<tbody>"
                                                ."<tr>"
                                                    ."<td>"
                                                    ."</td>"
                                                ."</tr>"
                                            ."</tbody>"
                                        ."</table>"
                                    ."</td>"
                                ."</tr>"
                            ."</table>";

            try {
                $mailer = $this->serviceLocator->get('VuFind\Mailer');
                //$mailer->addHeader('Content-Type', 'text/html');
                $mailer->send(
                    new Address($recipient_email, $recipient_name),
                    new Address($sender_email, $sender_name),
                    $email_subject, $email_message
                );
                $this->flashMessenger()->addMessage(
                    'thank_you_for_your_report_error', 'success'
                );
            } catch (MailException $e) {
                $this->flashMessenger()->addMessage($e->getMessage(), 'error');
            }
        }

        return $view;
    }

    public function bookinfoAction()
    {
        $link_registration = "https://opac.libfl.ru/WebReaderT";
        $link_login = "https://opac.libfl.ru/personal/loginemployee.aspx";
        $link_contactsLibrary = "https://libfl.ru/ru/item/contacts";

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

            $bookInfo = !empty($view->bookInfo) ? json_decode($view->bookInfo, JSON_OBJECT_AS_ARRAY) : "";
            // Staff
            $recipient_email = isset($sendAccess->recipient_email) ? $sendAccess->recipient_email : 'maksim.kuleba@gmail.com'; // E-mail получателя (сотрудника)
            $recipient_name = isset($sendAccess->recipient_name) ? $sendAccess->recipient_name : 'Recipient name'; // Имя получателя
            $email_subject = isset($sendAccess->email_subject) ? date("d.m.Y H:i").' '.$sendAccess->email_subject : date("Y.m.d H:i") . ' запрос информации.'; //Тема письма
            $sender_email = isset($sendAccess->sender_email) ? $sendAccess->sender_email : 'vufind-noreply@libfl.ru'; // E-mail отправителя
            $sender_name = isset($sendAccess->sender_name) ? $sendAccess->sender_name : 'ВГБИЛ'; // Имя отправителя

            // Reader
            $readerName = $view->readerName;
            $readerEmail = $view->readerEmail;
            $readerEmailSubject = 'LIBFL.RU ' . date("Y.m.d H:i") . ' ' . trim(mb_substr($bookInfo['title'], 0, 30)) . '... информация о доступе.';


            $staff_email_message = "<p><b>Читатель: </b></p>"
                            ."<p><b>E-mail пользователя: </b>" . $readerEmail . "</p>"
                            ."<p><b>Имя: </b>" . $readerName . "</p><br/>"
                            ."<p><b>Запросил информацию о книге:</b></p>"
                            ."<p><b>Название книги: </b>" . $bookInfo['title'] . "</p>"
                            ."<p><b>Автор: </b>" . $bookInfo['author'][0] . "</p>"
                            ."<p><b>URL: </b>" . $bookInfo['url'] . "</p><br/>";

            /*$reader_email_message = "<p><b>Название книги: </b>" . $bookInfo['title'] . "</p>"
                            ."<p><b>Автор: </b>" . $bookInfo['author'][0] . "</p>"
                            ."<p><b>URL: </b>" . $bookInfo['url'] . "</p><br/>"
                            ."<p><b>Вы можете получить книгу:</b></p>"
                            ."<table width='70%' border='0' cellpadding='0' cellspacing='0' style='margin:30px auto;padding:0'>"
                                ."<tbody>"
                                    ."<tr style='margin: 0 50px;'>"
                                        ."<th style='text-align:left; color: #d0cfcf; font-size: 12px; font-weight: 300; padding: 8px;'>Местонахождение в библиотеке</th>"
                                        ."<th style='text-align:left; color: #d0cfcf; font-size: 12px; font-weight: 300; padding: 8px;'>Инвентарный номер</th>"
                                    ."</tr>"
                                    ."<tr>"
                                        ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                        ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                    ."</tr>";
                                    foreach ($bookInfo['locations'] as $location => $inv_numbers) {
                                        $reader_email_message .= "<tr style='margin: 0px 50px;'>";
                                        /*if ($location == 'access_locationClarify') {
                                            $email_message .= "<td style='font-size:14px; padding:8px;'>".$this->translate($location)."</td>";
                                        } else {
                                            $email_message .= "<td style='font-size:14px; padding:8px;'><a href='".$link_contactsLibrary."' target='_blank' style='color:#0000f3; text-decoration:none;'>".$this->translate($location)."</a></td>";
                                        }*/
                                        /*$reader_email_message .= "<td style='font-size:14px; padding:8px;'><a href='#' target='_blank' style='color:#0000f3; text-decoration:none;'>".$this->translate($location)."</a></td>";
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
                                $reader_email_message .= "</tbody>"
                            ."</table>";*/

            /*$reader_email_message = "<table bgcolor='#dfe7ee' border='0' cellpadding='0' cellspacing='0' style='margin:0px 0px 0px 0px; padding:0px 0px 0px 0px' width='100%'>"
                                ."<tr>"
                                    ."<td height='100%' style='padding:20px 0px 20px 0px;'>"
                                        ."<table border='0' cellpadding='0' cellspacing='0' style='max-width:600px; margin:0px auto; padding:0px 0px 0px 0px' bgcolor='#ffffff'>"
                                            ."<tbody>"
                                                ."<tr>"
                                                    ."<td style='padding:50px 0px 50px 0px;'>"
                                                        ."<a href='https://libfl.ru/ru' target='_blank'>"
                                                            ."<img src='https://cdn.libfl.ru:6684/images/email/libfl-animated-logo.gif' alt='Библиотека иностранной литературы' border='0' width='120' height='201' style='display:block; margin:0px auto'>"
                                                        ."</a>"
                                                    ."</td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td style='padding:30px 20px 0; text-align:center'>"
                                                        ."<p><b>Название книги: </b>" . $bookInfo['title'] . "</p>"
                                                        ."<p><b>Автор: </b>" . $bookInfo['author'][0] . "</p>"
                                                        //."<p><b>URL: </b>" . $bookInfo['url'] . "</p><br/>"
                                                        ."<p><b>Вы можете получить книгу:</b></p>"
                                                    ."</td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td>"
                                                        ."<table width='70%' border='0' cellpadding='0' cellspacing='0' style='margin:30px auto;padding:0'>"
                                                            ."<tbody>"
                                                                ."<tr style='margin: 0 50px;'>"
                                                                    ."<th style='text-align:left; color: #d0cfcf; font-size: 12px; font-weight: 300; padding: 8px;'>Местонахождение в библиотеке</th>"
                                                                    ."<th style='text-align:left; color: #d0cfcf; font-size: 12px; font-weight: 300; padding: 8px;'>Инвентарный номер</th>"
                                                                ."</tr>"
                                                                ."<tr>"
                                                                    ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                                                    ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                                                ."</tr>";
                                                                foreach ($bookInfo['locations'] as $location => $inv_numbers) {
                                                                    $reader_email_message .= "<tr style='margin: 0px 50px;'>";
                                                                    if ($location == 'access_locationClarify') {
                                                                        $reader_email_message .= "<td style='font-size:14px; padding:8px;'>".$this->translate($location)."</td>";
                                                                    } else {
                                                                        $reader_email_message .= "<td style='font-size:14px; padding:8px;'><a href='".$link_contactsLibrary."' target='_blank' style='color:#0000f3; text-decoration:none;'>".$this->translate($location)."</a></td>";
                                                                    }
                                                                    $reader_email_message .= "<td style='font-size:14px; padding:8px; text-align:center;'>";
                                                                        foreach ($inv_numbers as $inv_number) {
                                                                            $reader_email_message .= "<span style='padding: 0px 7px 0px 0px;'>".$inv_number."</span>";
                                                                        }
                                                                    $reader_email_message .= "</td>";
                                                                    $reader_email_message .= "</tr>";
                                                                    $reader_email_message .= "<tr>"
                                                                        ."<td height='1px' style='margin:0px 0px 0px 0px; padding:0px 0px 0px 0px; height:1px; background-color:#dddddd;'></td>"
                                                                        ."<td height='1px' style='margin:0px 0px 0px 0px; padding:0px 0px 0px 0px; height:1px; background-color:#dddddd;'></td>"
                                                                    ."</tr>";
                                                                }
                                                            $reader_email_message .= "</tbody>"
                                                        ."</table>"
                                                    ."</td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td style='padding: 0 0 30px;text-align: center'>"
                                                        ."<a href='".$bookInfo['url']."' style='display:inline-block;margin:0 0 30px;background-color:#0000f3;color:#ffffff;font-size:16px;letter-spacing:0.01;text-decoration: none; padding: 15px 30px;border-radius: 3px;'>Перейти к книге</a>"
                                                    ."</td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td height='1px' style='margin: 0;padding: 0;height: 1px;background-color: #dddddd;'></td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td height='39px' style='padding: 30px 0;'>"
                                                        ."<table border='0' cellpadding='0' cellspacing='0' style='margin: 0 auto;'>"
                                                            ."<tbody>"
                                                                ."<tr>"
                                                                    ."<td style='padding: 0 5px;'>"
                                                                        ."<a href='https://vk.com/public59877134' target='_blank' style='display: inline-block; text-decoration: none;'>"
                                                                            ."<img width='39' height='39' style='width: auto; height: 39px;' src='https://cdn.libfl.ru:6684/images/email/vk.png' alt='ВКонтакте'>"
                                                                        ."</a>"
                                                                    ."</td>"
                                                                    ."<td style='padding: 0 5px;'>"
                                                                        ."<a href='https://www.instagram.com/libfl/' target='_blank' style='display: inline-block; text-decoration: none;'>"
                                                                            ."<img width='39' height='39' style='width: auto; height: 39px;' src='https://cdn.libfl.ru:6684/images/email/in.png' alt='Instagram'>"
                                                                        ."</a>"
                                                                    ."</td>"
                                                                    ."<td style='padding: 0 5px;'>"
                                                                        ."<a href='https://www.facebook.com/LIBFL/' target='_blank' style='display: inline-block; text-decoration: none;'>"
                                                                            ."<img width='39' height='39' style='width: auto; height: 39px;' src='https://cdn.libfl.ru:6684/images/email/fb.png' alt='Facebook'>"
                                                                        ."</a>"
                                                                    ."</td>"
                                                                    ."<td style='padding: 0 5px;'>"
                                                                        ."<a href='https://www.youtube.com/channel/UCyK_Z9yBQCcFzUZePsR5W4w' target='_blank' style='display: inline-block; text-decoration: none;'>"
                                                                            ."<img width='39' height='39' style='width: auto; height: 39px;' src='https://cdn.libfl.ru:6684/images/email/yt.png' alt='YouTube'>"
                                                                        ."</a>"
                                                                    ."</td>"
                                                                ."</tr>"
                                                            ."</tbody>"
                                                        ."</table>"
                                                    ."</td>"
                                                ."</tr>"
                                                ."<tr>"
                                                    ."<td style='padding: 10px 20px 20px;text-align: center'>"
                                                        ."<span style='font-size: 12px;line-height: 26px;font-weight: 200;color: #787878;'>"
                                                            ."Всероссийская Государственная Библиотека Иностранной Литературы им. М.И. Рудомино<br/>"
                                                            ."г. Москва, Николоямская 1 <a href='https://libfl.ru/ru/subscribe_preview#' target='_blank' style='text-decoration: none; color: #0000f3;'>посмотреть на карте</a>  |  <a href='https://libfl.ru/ru' target='_blank' style='text-decoration: none; color: #787878;'>+7 (495) 915–36–41</a>  |  <a href='https://libfl.ru/ru' target='_blank' style='text-decoration: none; color: #0000f3;'>www.libfl.ru</a>"
                                                        ."</span>"
                                                    ."</td>"
                                                ."</tr>"
                                            ."</tbody>"
                                        ."</table>"
                                    ."</td>"
                                ."</tr>"
                            ."</table>";*/
            $reader_email_message = "<table cellpadding='0' cellspacing='0' style='width:100%; background-color:#DFE7EE'>"
                                        ."<tr>"
                                            ."<td style='height:100%; padding:20px 0px 20px 0px'>"
                                                ."<table cellpadding='0' cellspacing='0' style='max-width:600px; margin:0px auto; background-color:#FFFFFF'>"
                                                    ."<tr>"
                                                        ."<td style='padding:50px 0px 50px 0px'>"
                                                            ."<a href='https://libfl.ru/ru' target='_blank'>"
                                                                ."<img src='https://cdn.libfl.ru:6684/images/email/libfl-animated-logo.gif' alt='Библиотека иностранной литературы' width='120' height='201' style='display:block; margin:0px auto'>"
                                                            ."</a>"
                                                        ."</td>"
                                                    ."</tr>"
                                                    ."<tr>"
                                                        ."<td style='padding:30px 20px 0px 20px; text-align:center'>"
                                                            ."<p><b>Название книги: </b>" . $bookInfo['title'] . "</p>"
                                                            ."<p><b>Автор: </b>" . $bookInfo['author'][0] . "</p>"
                                                            //."<p><b>URL: </b>" . $bookInfo['url'] . "</p><br/>"
                                                            ."<p><b>Вы можете получить книгу:</b></p>"
                                                        ."</td>"
                                                    ."</tr>"
                                                    ."<tr>"
                                                        ."<td>"
                                                            ."<table cellpadding='0' cellspacing='0' style='width:70%; margin:30px auto'>"
                                                                    ."<tr style='margin:0px 50px'>"
                                                                        ."<th style='color:#d0cfcf'>Местонахождение в библиотеке</th>"
                                                                        ."<th style='text-align:left; color:#D0CFCF; font-size:12px; font-weight:300; padding:8px'>Инвентарный номер</th>"
                                                                    ."</tr>"
                                                                    ."<tr>"
                                                                        ."<td style='margin:0px 0px 0px 0px; padding:0px 0px 0px 0px; height:1px; background-color:#DDDDDD'></td>"
                                                                        ."<td style='margin:0px 0px 0px 0px; padding:0px 0px 0px 0px; height:1px; background-color:#DDDDDD'></td>"
                                                                    ."</tr>"
                                                                    /*foreach ($bookInfo['locations'] as $location => $inv_numbers) {
                                                                        $reader_email_message .= "<tr style='margin: 0px 50px 0px 50px;'>";
                                                                        if ($location == 'access_locationClarify') {
                                                                            $reader_email_message .= "<td style='font-size:14px; padding:8px 8px 8px 8px;'>".$this->translate($location)."</td>";
                                                                        } else {
                                                                            $reader_email_message .= "<td style='font-size:14px; padding:8px 8px 8px 8px;'><a href='".$link_contactsLibrary."' target='_blank' style='color:#0000F3; text-decoration:none;'>".$this->translate($location)."</a></td>";
                                                                        }
                                                                        $reader_email_message .= "<td style='font-size:14px; padding:8px 8px 8px 8px; text-align:center;'>";
                                                                            foreach ($inv_numbers as $inv_number) {
                                                                                $reader_email_message .= "<span style='padding:0px 7px 0px 0px;'>".$inv_number."</span>";
                                                                            }
                                                                        $reader_email_message .= "</td>";
                                                                        $reader_email_message .= "</tr>";
                                                                        $reader_email_message .= "<tr>"
                                                                        ."<td style='margin:0px 0px 0px 0px; padding:0px 0px 0px 0px; height:1px; background-color:#DDDDDD;'></td>"
                                                                        ."<td style='margin:0px 0px 0px 0px; padding:0px 0px 0px 0px; height:1px; background-color:#DDDDDD;'></td>"
                                                                        ."</tr>";
                                                                    }*/
                                                                ."</table>"
                                                        ."</td>"
                                                    ."</tr>"
                                                ."</table>"
                                            ."</td>"
                                        ."</tr>"
                                    ."</table>";

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
