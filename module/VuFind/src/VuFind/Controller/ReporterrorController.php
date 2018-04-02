<?php
/**
 * Report Error Controller
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
class ReporterrorController extends AbstractBase
{
    /**
     * Display Report Error home form.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function homeAction()
    {
        return $this->forwardTo('Reporterror', 'Email');
    }

    /**
     * Receives input from the user and sends an email to the recipient set in
     * the config.ini
     *
     * @return void
     */
    public function emailAction()
    {
        $view = $this->createViewModel();
        $view->name = $this->params()->fromPost('name');
        $view->email = $this->params()->fromPost('email');
        $view->comments = $this->params()->fromPost('comments');

        $view->url = $this->params()->fromPost('url');
        $view->scrResolution = $this->params()->fromPost('scrResolution');
        $view->browser = $this->params()->fromPost('browser');
        $view->os = $this->params()->fromPost('os');

        if ($this->formWasSubmitted('submit', $view->useRecaptcha)) {

            if (empty($view->email) || empty($view->comments)) {
                $this->flashMessenger()->addMessage('bulk_error_missing', 'error');
                return;
            }
            $config = $this->serviceLocator->get('VuFind\Config')->get('config');
            $reportError = isset($config->Reporterror) ? $config->Reporterror : null;
            $recipient_email = isset($reportError->recipient_email) ? $reportError->recipient_email : 'vufind@libfl.ru';
            $recipient_name = isset($reportError->recipient_name) ? $reportError->recipient_name : 'ВГБИЛ';
            $email_subject = isset($reportError->email_subject) ? date("d.m.Y H:i").' '.$reportError->email_subject : date("d.m.Y H:i").' VuFind Report Error!';
            $sender_email = isset($reportError->sender_email) ? $reportError->sender_email : 'vufind-noreply@libfl.ru';
            $sender_name = isset($reportError->sender_name) ? $reportError->sender_name : 'VuFind Report Error!';

            $email_message = empty($view->name) ? "" : "<b>" . $this->translate('re_name') . ":</b> " . $view->name . "<br/>";
            $email_message .= empty($view->email) ? '' : "<b>" . $this->translate('re_email') . ":</b> " . $view->email . "<br/>";
            $email_message .= empty($view->comments) ? '' : "<b>" . $this->translate('re_comments') . ":</b><br/> " . $view->comments . "<br/><br/>";
            $email_message .= "<b><u>" . mb_strtoupper($this->translate('re_additional_info')) . ":</u></b><br/>";
            $email_message .= empty($view->date) ? '' : "<b>" . $this->translate('re_date') . ":</b> " . date('d.m.Y H:i')."<br/>";
            $email_message .= empty($view->os) ? '' : "<b>" . $this->translate('re_os') . ":</b> " . $view->os."<br/>";
            $email_message .= empty($view->browser) ? '' : "<b>" . $this->translate('re_browser') . ":</b> " . $view->browser."<br/>";
            $email_message .= empty($view->scrResolution) ? '' : "<b>" . $this->translate('re_screen_resolution') . ":</b> " . $view->scrResolution."<br/>";
            $email_message .= empty($view->url) ? '' : "<b>" . $this->translate('re_page_url') . ":</b> " . $view->url . "<br/>";
            $email_message .= "<br/>";
            $email_message .= "<b>User agent:</b> " . filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');

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
}
